<?php

namespace App\Services;

use App\Jobs\XmeActionLog;
use App\Models\Invite;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Form;

class XmeAreaService
{
    public function index($inputs)
    {
        try {
            $currentUser = Auth::user();
            $collection = false;
            if (!User::hasRole($currentUser, User::ROLE_EMPLOYEE)) {
                $collection = Form::scpCompany()->with('form_company');
                if (!empty($inputs['is_archive'])) {
                    $collection->onlyTrashed();
                }

                if (isset($inputs['search'])) {
                    $search = trim($inputs['search']);
                    $collection->where('display_title', 'LIKE', "%{$search}%");
                    dispatch(new XmeActionLog(
                        auth()->user(),
                        'search',
                        '{user} searched modules by "' . $inputs['search'] . '" on xme area page',
                        null,
                    ));
                }
                $collection = $collection->where('xme_area', '=', 1)->paginate(10);
            } else {

                $directAssignedGroups = Form::join('company_forms', 'company_forms.form_id', '=', 'forms.id')
                ->withCount('responses')->with('user_form')->whereHas('user_form', function ($q) {
                    $q->where('user_id', Auth::user()->id);
                })->orderBy('company_forms.display_order')->where('xme_area', 1);
                $directAssignedGroups->whereNull('company_forms.archived_at');

               

                $assignedForms = Form::withCount('responses')
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
                    ->where('xme_area', 1);

                   

                // learning Modules
                $learningModules = Form::withCount('responses')
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
                    ->orderBy('company_forms.display_order')->where('xme_area', 1);

                    // dd($learningModules->toSql());

                // if learning module archeived
                if (isset($inputs['search'])) {
                    $search = trim($inputs['search']);
                    $assignedForms->where('forms.display_title', 'LIKE', "%{$search}%");
                }
                $assignedForms->union($directAssignedGroups);
                $assignedForms->union($learningModules);
                $assignedForms = $assignedForms->paginate(10);
                return $assignedForms;
            }
            return $collection;
        } catch (\Exception | RequestException $e) {
            Log::error('xme_area_list_service', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function tryDeleteInvite($email)
    {
        $invite = Invite::where('email', $email)->first();
        if ($invite) {
            $invite->delete();
        }
    }
}
