<?php

namespace App\Services;

use App\Jobs\SendNotification;
use App\Jobs\XmeActionLog;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Form;
use Carbon\Carbon;
use App\Models\CompanyForm;
use App\Models\ReviewForm;
use App\Models\Subject;

class ReviewFormService
{
    public function getList($inputs)
    {
        try {
            $data = [];
            if ($inputs['isDeleted'] == 1) {
                $forms = Form::onlyTrashed()->withCount('responses');
            } else {
                if (User::hasRole(Auth::user(), User::ROLE_SUPERADMIN)) {
                    $forms = Form::withCount('responses')->where('review_date', '<=', Carbon::now()->format('Y-m-d'))
                        ->where('is_update', '!=', 1);
                }
                if (
                    User::hasRole(Auth::user(), User::ROLE_COMPANY) ||
                    User::hasRole(Auth::user(), User::ROLE_BUSINESSADMIN)
                ) {
                    $forms = Form::select('forms.*', 'company_forms.review_date
                        as member_review_date', 'company_forms.archived_at as company_archived_at')
                        ->join('company_forms', 'company_forms.form_id', '=', 'forms.id')
                        ->where('company_forms.company_id', Auth::user()->company_id)
                        ->where('company_forms.review_date', '<=', Carbon::now()->format('Y-m-d'))
                        ->where('company_forms.archived_at', '=', null);

                    $updateForms = Subject::where('form_id', '!=', 0)
                        ->where('identify_reason', Subject::REVIEW_UPDATE)
                        ->where('company_id', Auth::user()->company_id)->pluck('form_id');
                    $forms = $forms->whereNotIn('forms.id', $updateForms);
                }
            }
            $text = '';
            //search module
            if (isset($inputs['search'])) {
                $search =  trim($inputs['search']);
                $forms = $forms->where(function ($query) use ($search) {
                    $query->where('typeform_id', 'LIKE', "%{$search}%")
                        ->orWhere('display_title', 'LIKE', "%{$search}%");
                });
                $text = 'searched modules by "' . $inputs['search'] . '"';
                // dispatch(new XmeActionLog(
                //     auth()->user(),
                //     'search',
                //     '{user} searched modules by "' . $inputs['search'] . '" on review area page',
                //     null,
                // ));
            }

            //filter by form type
            if (isset($inputs['type'])  && $inputs['type'] == 1) {
                $forms->where('xme_area', 1);
            } elseif (isset($inputs['type']) && $inputs['type'] == 0) {
                $forms->where('xme_area', 0);
            }

            // filter by companies
            if (isset($inputs['filter'])) {
                $filter = $inputs['filter'];
                $forms->whereHas('companies', function ($q) use ($filter) {
                    $q->where('name', 'LIKE', "%{$filter}%");
                });
            }
            // filter by status
            if (isset($inputs['status'])) {
                if ($inputs['status'] == 1) {
                    $forms = $forms->where('forms.status', 1);
                } elseif ($inputs['status'] == 0) {
                    $forms = $forms->where(function ($query) {
                        $query->where('forms.status', 0)->orWhereNull('forms.status');
                    });
                }
            }

            /**activity logs */
            $pageName = 'on review area page';
             if (!empty($data)||!empty($text)) {
                $this->updateAllFilterLog($text, $data, $pageName);
            }
            return $forms->orderBy('forms.id', 'ASC')->paginate(10);
        } catch (\Exception | RequestException $e) {
            Log::error('review_modules_list_service', ['error' => $e->getMessage()]);
            throw $e;
        }
    }


    public function assignedModules($inputs)
    {
        try {

            if (
                User::hasRole(Auth::user(), User::ROLE_BUSINESSADMIN) ||
                User::hasRole(Auth::user(), User::ROLE_COMPANY)
            ) {
                $assignedModule = Form::select('forms.*', 'company_forms.review_date as member
                review_date', 'company_forms.module_status as module_status', 'company_forms.is_update as
                company_update', 'company_forms.id as form_company_id', 'company_forms.status as
                company_module_status')
                    ->join('company_forms', 'company_forms.form_id', '=', 'forms.id')
                    ->where('company_forms.company_id', Auth::user()->company_id)
                    ->where('company_forms.archived_at', null)
                    ->where('company_forms.module_status', '=', 0);

                // search by form type
                if (isset($inputs['assignedType']) == 'xme-area') {
                    $assignedModule->where('xme_area', 1);
                } elseif (isset($inputs['assignedType']) == 'library') {
                    $assignedModule->where('xme_area', 0);
                }

                //search by module  status
                if (isset($inputs['moduleStatus']) == 'new') {
                    $assignedModule->where('company_forms.status', CompanyForm::NEW);
                } elseif (isset($inputs['moduleStatus']) == 'update') {
                    $assignedModule->where('company_forms.status', CompanyForm::UPDATE);
                } elseif (isset($inputs['moduleStatus']) == 'adminrequest') {
                    $assignedModule->where('company_forms.status', CompanyForm::SEND_REQUSET_TO_ADMIN);
                }
                // search by module title
                if (isset($inputs['assignedSearch'])) {
                    $search =  trim(isset($inputs['assignedSearch']));
                    $assignedModule = $assignedModule->where(function ($query) use ($search) {
                        $query->where('typeform_id', 'LIKE', "%{$search}%")
                            ->orWhere('display_title', 'LIKE', "%{$search}%");
                    });
                }
                $assignedModule = $assignedModule
                    ->orderBy($inputs['sortField'], '')
                    ->paginate(10);
                return $assignedModule;
            }
        } catch (\Exception | RequestException $e) {
            Log::error('assigned_module_list_service', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function updateXmeAdminModule($inputs, $moduleId)
    {
        try {
            $xmeFormData = Form::where('id', $moduleId)->first();
            $xmeFormData->review_reason = ($inputs['reason']);
            $xmeFormData->save();

            /** update xmeAdmin module active log */
            dispatch(new XmeActionLog(
                auth()->user(),
                'update',
                '{user} updated module "{model}" in xme review area',
                $xmeFormData,
            ));
            return $xmeFormData;
        } catch (\Exception | RequestException $e) {
            Log::error('xmeadmin_form_update_service', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function updateReviewDate($inputs, $moduleId)
    {
        try {
            if ($inputs['userRole'] ==  'superadmin') {
                $xmeForm = Form::where('id', $moduleId)->first();
            } elseif ($inputs['userRole'] == 'company' || $inputs['userRole'] ==  'businessadmin') {
                $xmeForm = CompanyForm::where('form_id', $moduleId)
                    ->where('company_id', Auth::user()->company_id)->first();
            }
            if ($inputs['review_date'] == 1) {
                $nextReviewDate = Carbon::parse($xmeForm->review_date)->addMonths(6);
            } elseif ($inputs['review_date'] == 2) {
                $nextReviewDate = Carbon::parse($xmeForm->review_date)->addMonths(12);
            } elseif ($inputs['review_date'] == 3) {
                $nextReviewDate = Carbon::parse($inputs['customDate']);
            }
            $xmeForm->review_date = $nextReviewDate;
            $xmeForm->save();
            /**activity logs */
            $this->changeReviewDateLog(Carbon::parse($xmeForm->review_date), $xmeForm->form->display_title, $xmeForm);

            $this->mailUpdateReviewModuleDate($moduleId);

            return  $xmeForm;
        } catch (\Exception | RequestException $e) {
            Log::error('next_review_date_service', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function newAssignedModules($inputs)
    {
        try {
            $data = [];
            $assignedModules = Form::select(
                'forms.*',
                'company_forms.review_date as member_review_date',
                'company_forms.module_status as module_status',
                'company_forms.is_update as company_update',
                'company_forms.id as xme_form_company_id',
                'company_forms.status as company_module_status',
                'company_forms.archived_at as company_archived_at',
                'company_forms.company_id as form_company_id'
            )->join('company_forms', 'company_forms.form_id', '=', 'forms.id')
                ->where('company_forms.company_id', Auth::user()->company_id)
                ->where('company_forms.archived_at', null)
                ->where('forms.xme_area', 0)
                ->where('company_forms.module_status', '=', 0);


            // search by form type
            $pageName = ' in production page.';
            
            //search by module  status
            if (isset($inputs['status']) && $inputs['status'] == 'new') {
                $assignedModules->where('company_forms.status', CompanyForm::NEW);
                //$this->updatedFilterStatus($inputs['status'], $pageName, false);
            } elseif (isset($inputs['status']) && $inputs['status'] == 'update') {
                $assignedModules->where('company_forms.status', CompanyForm::UPDATE);
                //$this->updatedFilterStatus($inputs['status'], $pageName, false);
            } elseif (isset($inputs['status']) && $inputs['status'] == 'adminrequest') {
                $assignedModules->where('company_forms.status', CompanyForm::SEND_REQUSET_TO_ADMIN);
                //$this->updatedFilterStatus($inputs['status'], $pageName, false);
            }
            if (isset($inputs['status']) && !empty($inputs['status'])) {
                $data[] = 'module status "' . $inputs['status'] . '"';
            }
            $text = '';
            // search by module title
            if (isset($inputs['search'])) {
                $search =  trim($inputs['search']);
                $assignedModules = $assignedModules->where(function ($query) use ($search) {
                    $query->where('typeform_id', 'LIKE', "%{$search}%")
                        ->orWhere('display_title', 'LIKE', "%{$search}%");
                });
                // $this->updatedSearch($search, $pageName, false);
                $text = 'searched modules by "' . $inputs['search'] . '"';
            }
            $assignedModules = $assignedModules
                ->orderBy($inputs['sortField'], $inputs['orderDir'])
                ->paginate(10);
            /**activity logs */
            if (!empty($data)||!empty($text)) {
               $this->updateAllFilterLog($text, $data, $pageName);
           }
            return $assignedModules;
        } catch (\Exception | RequestException $e) {
            Log::error('new_assigned_modules_service', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function moveToLibrary($formId)
    {
        try {
            $companyForm = CompanyForm::with('form')->where('company_id', Auth::user()->company_id)
                ->where('form_id', decrypt_tech($formId))->first();
            $companyForm->module_status = 1;
            $companyForm->save();

            $reviewForm = ReviewForm::where('company_form_id', $companyForm->id)->first();
            if ($reviewForm) {
                $reviewForm->status = ReviewForm::CLOSED;
                $reviewForm->close_date = Carbon::now();
                $reviewForm->save();
            }

            $this->mailMoveToLibrary($companyForm);
            /**move to library active log */
            dispatch(new XmeActionLog(
                auth()->user(),
                'store',
                '{user}  ' . $companyForm->form->display_title  . ' move to Library from review area',
                $companyForm,
            ));
            return $companyForm;
        } catch (\Exception | RequestException $e) {
            Log::error('move_to_library_api_service', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function reviewHistory($inputs)
    {
        try {
            $data = [];
            if (
                User::hasRole(Auth::user(), User::ROLE_BUSINESSADMIN)
                || User::hasRole(Auth::user(), User::ROLE_COMPANY)
            ) {
                $modules = Form::select(
                    'forms.*',
                    'subjects.id as subject_id',
                    'company_forms.archived_at as company_archived_at'
                )
                    ->join('company_forms', 'company_forms.form_id', '=', 'forms.id')
                    ->join('subjects', 'subjects.form_id', 'company_forms.form_id')
                    ->where('company_forms.company_id', Auth::user()->company_id)
                    ->where('subjects.company_id', Auth::user()->company_id)
                    ->groupBy('forms.id');
                $pageName = 'on  review area history page';
            } elseif (User::hasRole(Auth::user(), User::ROLE_SUPERADMIN)) {
                $modules = Form::select('forms.*')->where('is_update', 1);
                $pageName = 'on xme-review area history page';
            }

            if (isset($inputs['type']) && $inputs['type'] == 'xme-area') {
                $modules    = $modules->where('forms.xme_area', 1);
                $this->updatedFilterType($inputs['type'], $pageName, false);
            } elseif (isset($inputs['type']) && $inputs['type']  == 'library') {
                $modules    = $modules->where('forms.xme_area', 0);
                $this->updatedFilterType($inputs['type'], $pageName, false);
            }

            if (isset($inputs['status']) && $inputs['status'] == 'active') {
                $modules    = $modules->where('forms.status', 1);
                $data[] = 'status "Active"';
            } elseif (($inputs['status']) && $inputs['status'] == 'inactive') {
                $modules    = $modules->where('forms.status', 0);
                $data[] = 'status "InActive"';
            }
            $text = '';
            if (isset($inputs['search'])) {
                $search =  trim($inputs['search']);
                // $this->updatedSearch($search, $pageName, false);
                $modules = $modules->where(function ($query) use ($search) {
                    $query->where('forms.typeform_id', 'LIKE', "%{$search}%")
                        ->orWhere('forms.display_title', 'LIKE', "%{$search}%");
                });
                $text = 'searched modules by "' . $inputs['search'] . '"';
            }
            $modules    = $modules->orderBy($inputs['sortField'], $inputs['orderDir']);
            $modules =    $modules->paginate(10);
            /**activity logs */
             if (!empty($data)||!empty($text)) {
                $this->updateAllFilterLog($text, $data, $pageName);
            }
            return $modules;
        } catch (\Exception | RequestException $e) {
            Log::error('review_history_api', ['error' => $e->getMessage()]);
            throw $e;
        }
    }


    /** update review module Date email bell notifications function **/
    public function mailUpdateReviewModuleDate($moduleId)
    {
        $loggedUser = Auth::user();
        $users      = [];
        if (isImpersonatedSuperAdmin()) {
            $isSupport = true;
        } else {
            $isSupport = false;
        }
        $xmeForm = Form::where('id', $moduleId)->first();
        $users = User::with('role')->where('company_id', $loggedUser->company_id)
            ->whereHas('role', function ($q) {
                $q->where('name', '!=', User::ROLE_EMPLOYEE);
            })->where('id', '!=', Auth::user()->id)
            ->orwhereHas('role', function ($q) {
                $q->where('name', User::ROLE_SUPERADMIN);
            })->get();

        $data = [
            'loggedUser'    => $loggedUser,
            'isSupport'     => $isSupport,
            'xmeForm'       => $xmeForm,
        ];
        dispatch(new SendNotification(
            $users,
            $data,
            'UpdateReviewModuleDateNotification',
            'sendUpdateReviewModuleDateNotification',
            null
        ));
    }

    /**review reminder email bell notifications function **/
    public function mailReviewReminder($xmeForm)
    {
        $loggedUser = Auth::user();
        $users      = [];
        if (isImpersonatedSuperAdmin()) {
            $isSupport = true;
        } else {
            $isSupport = false;
        }
        $users = User::with('role')->where('company_id', $loggedUser->company_id)
            ->whereHas('role', function ($q) {
                $q->where('name', '!=', User::ROLE_EMPLOYEE);
            })->where('id', '!=', Auth::user()->id)
            ->orwhereHas('role', function ($q) {
                $q->where('name', User::ROLE_SUPERADMIN);
            })->get();

        $data = [
            'loggedUser'    => $loggedUser,
            'isSupport'     => $isSupport,
            'xmeForm'       => $xmeForm,
        ];
        dispatch(new SendNotification(
            $users,
            $data,
            'ReviewReminderNotification',
            'sendReviewReminderNotification',
            null
        ));
    }

    /**move to library email bell notifications function **/
    public function mailMoveToLibrary($xmeForm)
    {
        $loggedUser = Auth::user();
        $users      = [];
        if (isImpersonatedSuperAdmin()) {
            $isSupport = true;
        } else {
            $isSupport = false;
        }
        $users = User::with('role')->where('company_id', $loggedUser->company_id)
            ->whereHas('role', function ($q) {
                $q->where('name', '!=', User::ROLE_EMPLOYEE);
            })->where('id', '!=', Auth::user()->id)
            ->orwhereHas('role', function ($q) {
                $q->where('name', User::ROLE_SUPERADMIN);
            })->get();

        $data = [
            'loggedUser'    => $loggedUser,
            'isSupport'     => $isSupport,
            'xmeForm'       => $xmeForm,
        ];
        dispatch(new SendNotification(
            $users,
            $data,
            'MoveToLibraryNotification',
            'sendMoveToLibraryNotification',
            null
        ));
    }

    /**module expiry email bell notifications function **/
    public function mailModuleExpiry($xmeForm)
    {
        $loggedUser = Auth::user();
        $users      = [];
        if (isImpersonatedSuperAdmin()) {
            $isSupport = true;
        } else {
            $isSupport = false;
        }
        $users = User::with('role')->where('company_id', $loggedUser->company_id)
            ->whereHas('role', function ($q) {
                $q->where('name', '!=', User::ROLE_EMPLOYEE);
            })->where('id', '!=', Auth::user()->id)
            ->orwhereHas('role', function ($q) {
                $q->where('name', User::ROLE_SUPERADMIN);
            })->get();

        $data = [
            'loggedUser'    => $loggedUser,
            'isSupport'     => $isSupport,
            'xmeForm'       => $xmeForm,
        ];
        dispatch(new SendNotification(
            $users,
            $data,
            'ModuleExpiryNotification',
            'sendModuleExpiryNotification',
            null
        ));
    }

    /**new assigned form reminder email bell notifications function **/
    public function mailNewAssignedFormReminder($xmeForm)
    {
        $loggedUser = Auth::user();
        $users      = [];
        if (isImpersonatedSuperAdmin()) {
            $isSupport = true;
        } else {
            $isSupport = false;
        }
        $users = User::with('role')->where('company_id', $loggedUser->company_id)
            ->whereHas('role', function ($q) {
                $q->where('name', '!=', User::ROLE_EMPLOYEE);
            })->where('id', '!=', Auth::user()->id)
            ->orwhereHas('role', function ($q) {
                $q->where('name', User::ROLE_SUPERADMIN);
            })->get();

        $data = [
            'loggedUser'    => $loggedUser,
            'isSupport'     => $isSupport,
            'xmeForm'       => $xmeForm,
        ];
        dispatch(new SendNotification(
            $users,
            $data,
            'NewAssignedFormReminderNotification',
            'sendNewAssignedFormReminderNotification',
            null
        ));
    }

    /*** activity logs change filter for type  history page */
    public function updatedFilterType($type, $pageName, $isArchived)
    {
        if ($type != '') {
            if ($isArchived) {
                dispatch(new XmeActionLog(
                    auth()->user(),
                    'filter',
                    '{user} change filtered  type by "' . $type . '" ' . $pageName . '',
                    null,
                ));
            } else {
                dispatch(new XmeActionLog(
                    auth()->user(),
                    'filter',
                    '{user} change filtered  type by "' . $type . '" ' . $pageName . '',
                    null,
                ));
            }
        }
    }

    /*** activity logs change filter for status  history page */
    public function updatedFilterStatus($status, $pageName, $isArchived)
    {
        if ($status !=  '') {
            if ($isArchived) {
                dispatch(new XmeActionLog(
                    auth()->user(),
                    'filter',
                    '{user} change filtered  status by "' . $status . '" ' . $pageName . '',
                    null,
                ));
            } else {
                dispatch(new XmeActionLog(
                    auth()->user(),
                    'filter',
                    '{user} change filtered  status by "' . $status . '" ' . $pageName . '',
                    null,
                ));
            }
        }
    }

    /** activity logs search*/
    public function updatedSearch($search, $pageName, $isArchived)
    {
        if ($search != '') {
            if ($isArchived) {
                dispatch(
                    new XmeActionLog(
                        auth()->user(),
                        'search',
                        '{user} searched modules by "' . $search . '" ' . $pageName . '',
                        null,
                    )
                );
            } else {
                dispatch(
                    new XmeActionLog(
                        auth()->user(),
                        'search',
                        '{user} searched modules by "' . $search . '" ' . $pageName . '',
                        null,
                    )
                );
            }
        }
    }


    public function changeReviewDateLog($reviewDate, $module, $xmeForm)
    {
        dispatch(new XmeActionLog(
            auth()->user(),
            'update',
            '{user} update the review date "' . $reviewDate . '" to module "' .  $module . '" ',
            $xmeForm,
        ));
    }



    public function updatedFilterTypeNewAssign($type, $pageName, $isArchived)
    {
        if ($type == 0) {
            $typeName = 'Library';
        } elseif ($type == 1) {
            $typeName = 'XmeArea';
        }

        if ($type != '') {
            if ($isArchived) {
                dispatch(new XmeActionLog(
                    auth()->user(),
                    'filter',
                    '{user} change filtered  type by "' . $typeName . '" ' . $pageName . '',
                    null,
                ));
            } else {
                dispatch(new XmeActionLog(
                    auth()->user(),
                    'filter',
                    '{user} change filtered  type by "' . $typeName . '" ' . $pageName . '',
                    null,
                ));
            }
        }
    }

    public function updateAllFilterLog($text, $data, $pageName)
    {
        if (!empty($data)) {
            $text .= " filters by " . implode(', ', $data);
        }
        dispatch(
            new XmeActionLog(
                auth()->user(),
                'search',
                '{user} ' . $text . ' ' . $pageName . '',
                null,
            )
        );
    }
}
