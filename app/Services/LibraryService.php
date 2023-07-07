<?php

namespace App\Services;

use Illuminate\Support\Facades\Request;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;
use App\Models\Form;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use App\Jobs\XmeActionLog;
use App\Models\CompanyForm;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Response;

class LibraryService
{
    public function list($inputs)
    {
        try {
            if (User::hasRole(Auth::user(), User::ROLE_EMPLOYEE)) {
                $collection = $this->memberLibrary($inputs);
            } elseif (
                User::hasRole(Auth::user(), User::ROLE_COMPANY) ||
                User::hasRole(Auth::user(), User::ROLE_BUSINESSADMIN)
            ) {
                $collection = $this->companyLibrary($inputs);
            }
            return $collection;
        } catch (\Exception | RequestException $e) {
            Log::error('library_list_service', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function companyLibrary($inputs)
    {
        $data = [];
        $collection = Form::join('company_forms', 'company_forms.form_id', '=', 'forms.id');
        if ($inputs['isArchived'] == 0) {
            $collection = $collection->whereNull('company_forms.archived_at');
        } else {
            $collection = $collection->whereNotNull('company_forms.archived_at');
        }
        $collection = $collection->where('company_forms.module_status', 1);
        $text = '';
        if (isset($inputs['search'])) {
            $search = trim($inputs['search']);
            $collection->where('display_title', 'LIKE', "%{$search}%");
            $text = 'searched modules by "' . $inputs['search'] . '"';
            // $this->updatedSearch($search, $inputs['isArchived']);
        }
        $collection->where('xme_area', '=', 0);
        $collection->orderBy('company_forms.display_order', 'ASC')
            ->where('company_forms.company_id', Auth::user()->company_id);
        $collection = $collection->select(
            'forms.*',
            'company_forms.directory_id as directory_id',
            'company_forms.id as form_company_id',
            'company_forms.review_date as form_review_date'
        )->paginate(40);
        /**activity logs */
        if (!empty($data)||!empty($text)) {
            $this->updateAllFilterLog($text, $inputs['isArchived']);
        }
        return $collection;
    }

    public function memberLibrary($inputs)
    {
        $directAssignedGroups = Form::join('company_forms', 'company_forms.form_id', '=', 'forms.id')
            ->withCount('responses')->with('user_form')->whereHas('user_form', function ($q) {
                $q->where('user_id', Auth::user()->id);
            })->orderBy('company_forms.display_order')->where('xme_area', 0);
        if ($inputs['isArchived']) {
            $directAssignedGroups->whereNotNull('company_forms.archived_at');
        } else {
            $directAssignedGroups->whereNull('company_forms.archived_at');
        }
        $assignedForms = Form::withCount('responses')->with('user_form')
            ->join('company_forms', 'company_forms.form_id', '=', 'forms.id')
            ->join('groups', 'groups.company_id', '=', 'company_forms.company_id')
            ->join('group_forms', function ($join) {
                $join->on('group_forms.form_id', '=', 'company_forms.form_id')
                    ->on('group_forms.group_id', '=', 'groups.id');
            })
            ->join('user_groups', 'user_groups.group_id', '=', 'groups.id')
            ->join('users', 'users.id', '=', 'user_groups.user_id')
            ->where('users.id', Auth::user()->id)->orderBy('company_forms.display_order')
            ->whereNull('company_forms.archived_at')
            ->whereNull('groups.archived_at')
            ->whereNull('user_groups.archived_at')
            ->whereNull('group_forms.archived_at')
            ->where('xme_area', 0);

        // learning Modules
        $learningModules = Form::withCount('responses')->with('user_form')
            ->join('company_forms', 'company_forms.form_id', '=', 'forms.id')
            ->join('learning_paths', 'learning_paths.company_id', '=', 'company_forms.company_id')
            ->join('learning_path_forms', function ($join) {
                $join->on('learning_path_forms.form_id', '=', 'company_forms.form_id')
                    ->on('learning_path_forms.learning_group_id', '=', 'learning_paths.id');
            })
            ->join(
                'user_learning_paths',
                'user_learning_paths.learning_group_id',
                '=',
                'learning_paths.id'
            )
            ->join('users', 'users.id', '=', 'user_learning_paths.user_id')
            ->where('users.id', Auth::user()->id)
            ->whereNull('company_forms.archived_at')
            ->WhereNull('learning_paths.archived_at')
            ->whereNull('learning_path_forms.archived_at')
            ->whereNull('user_learning_paths.archived_at')
            ->orderBy('company_forms.display_order')->where('xme_area', 0);

        if (isset($inputs['search'])) {
            $search = trim($inputs['search']);
            $assignedForms->where('forms.display_title', 'LIKE', "%{$search}%");
            $this->updatedSearch($search, $inputs['isArchived']);
        }
        $assignedForms->union($directAssignedGroups);
        $assignedForms->union($learningModules);
        $assignedForms = $assignedForms->paginate(10);
        return $assignedForms;
    }

    public function archivedModule($formId)
    {
        try {
            DB::beginTransaction();
            $loggedUser = Auth::user();
            $collection = CompanyForm::where('form_id', $formId)
                ->where('company_id', Auth::user()->company_id)->first();
            $collection->delete();

            dispatch(new XmeActionLog(
                auth()->user(),
                'archive',
                '{user} archive module "' . $collection->form->display_title . '" on library page',
                $collection,
            ));
            DB::commit();
            return $collection;
        } catch (\Exception | RequestException $e) {
            DB::rollback();
            Log::error('archived_form_service', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function unArchivedModule($formId)
    {
        try {
            DB::beginTransaction();
            $loggedUser = Auth::user();
            $collection = CompanyForm::withTrashed()->where('form_id', $formId)
                ->where('company_id', Auth::user()->company_id)->first();
            $collection->restore();

            dispatch(new XmeActionLog(
                auth()->user(),
                'unarchive',
                '{user} unarchive module "' . $collection->form->display_title . '" on archive library page',
                $collection,
            ));
            DB::commit();
            return $collection;
        } catch (\Exception | RequestException $e) {
            DB::rollback();
            Log::error('unarchived_form_service', ['error' => $e->getMessage()]);
            throw $e;
        }
    }



    public function getResponses($inputs)
    {
        try {
            if (!isSuperAdmin()) {
                $responses = Response::scpCompany();
                $responses->whereHas('xmeForm', function ($q) {
                    $q->where('xme_area', 0);
                });
            } else {
                $responses = new Response();
            }
            if (!empty($inputs['form_id'])) {
                $responses = $responses->where('typeform_id', $inputs['form_id']);
            }
            $responses = $responses->orderBy('submitted_at', 'Desc')->paginate(10);
            return $responses;
        } catch (\Exception | RequestException $e) {
            Log::error('get_response_list_service', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function updateOrderAction($inputs)
    {
        try {
            $count = 1;
            foreach ($inputs as $form_id) {
                CompanyForm::where('form_id', $form_id)
                    ->where('company_id', Auth::user()->company_id)
                    ->update(['display_order' => $count]);
                $count++;
            }
            return true;
        } catch (\Exception | RequestException $e) {
            Log::error('update_library_order_service', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function updatedSearch($search, $isArchved)
    {
        if ($search != '') {
            if ($isArchved) {
                dispatch(
                    new XmeActionLog(
                        auth()->user(),
                        'search',
                        '{user} searched modules by "' . $search . '" on archive library page',
                        null,
                    )
                );
            } else {
                dispatch(
                    new XmeActionLog(
                        auth()->user(),
                        'search',
                        '{user} searched modules by "' . $search . '" on library  page',
                        null,
                    )
                );
            }
        }
    }

    public function updateAllFilterLog($text, $isArchived)
    {
        if (!empty($data)) {
            $text .= " filters by " . implode(', ', $data);
        }
        if ($isArchived == 1) {
            dispatch(
                new XmeActionLog(
                    auth()->user(),
                    'search',
                    '{user} ' . $text . 'on archive library page',
                    null,
                )
            );
        } else {
            dispatch(
                new XmeActionLog(
                    auth()->user(),
                    'search',
                    '{user} ' . $text .  'on library page',
                    null,
                )
            );
        }
    }
}
