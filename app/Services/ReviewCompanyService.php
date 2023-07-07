<?php

namespace App\Services;

use App\Jobs\SendNotification;
use App\Jobs\XmeActionLog;
use App\Models\Invite;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Form;
use App\Models\CompanyForm;
use App\Models\AssignModuleUpdation;
use App\Models\ReviewFormComment;
use App\Models\User;

class ReviewCompanyService
{

    public function getList($inputs)
    {
        try {
            $data = $dataSearch = [];
            $updationModules = Form::select(
                'forms.*',
                'company_forms.company_id as form_company_id',
                'companies.name as company_name',
                'company_forms.archived_at as company_archived_at'
            )->join('company_forms', 'company_forms.form_id', '=', 'forms.id')
                ->join('companies', 'companies.id', 'company_forms.company_id')
                ->where('company_forms.status', '!=', CompanyForm::NEW)
                ->where('company_forms.module_status', 0)
                ->where('forms.xme_area', 0)
                ->whereNull('company_forms.archived_at');


            // if (isset($inputs['type'])  && $inputs['type']  == 1) {
            //     $updationModules    = $updationModules->where('xme_area', 1);
            //     // $pageName = 'on review assign company module page.';
            //     // $this->updatedFilterType($inputs['type'], $pageName, false);
            //     $data[] = 'type "' .($inputs['type'] == 1 ? 'Xme area' : 'Library') . '"';
            // } elseif (isset($inputs['type']) && $inputs['type'] == 0) {
            //     $updationModules    = $updationModules->where('xme_area', 0);
            //     // $pageName = 'on review assign company module page.';
            //     // $this->updatedFilterType($inputs['type'], $pageName, false);
            //     $data[] = 'type "' .($inputs['type'] == 1 ? 'Xme area' : 'Library') . '"';
            // }

            // active in-active
            if (isset($inputs['status']) && $inputs['status'] == 1) {
                $updationModules = $updationModules->where('forms.status', 1);
                // $pageName = 'on review assign company module page.';
                // $this->updatedFilterStatus($inputs['status'], $pageName, false);
                $data[] = 'status Active' ;
            } elseif (isset($inputs['status']) && $inputs['status'] == 0) {
                $updationModules    = $updationModules->where('forms.status', 0);
                // $pageName = 'on review assign company module page.';
                // $this->updatedFilterStatus($inputs['status'], $pageName, false);
                $data[] = 'status InActive';
            }
           
            // search by title and typeform id
            if (isset($inputs['search'])) {
                $search =  trim($inputs['search']);
                $updationModules = $updationModules->where(function ($q) use ($search) {
                    $q->where('forms.typeform_id', 'LIKE', "%{$search}%")
                    ->orWhere('forms.display_title', 'LIKE', "%{$search}%");
                });
                // $pageName = 'on review assign company module page.';
                // $searchBy = 'modules';
                // $this->updatedSearch($search, $searchBy, $pageName, false);
                $dataSearch[] = ' title "' . $search . '"';
            }

            // search by company name
            if (isset($inputs['searchCompany'])) {
                $companySearch =  trim($inputs['searchCompany']);
                $updationModules = $updationModules->where('companies.name', 'LIKE', "%{$companySearch}%");
                // $pageName = 'on review assign company module page.';
                // $searchBy = 'companies';
                // $this->updatedSearch($companySearch, $searchBy, $pageName, false);
                $dataSearch[] = 'company"' . $companySearch . '"';
            }

            $updationModules    = $updationModules->orderBy($inputs['sortField'], 'ASC');
            $updationModules = $updationModules->paginate(10);
            /**activity logs */
            $pageName = 'on review assign company module page.';
            if (!empty($data) || !empty($dataSearch)) {
                $text = '';
                $this->updateAllFilterLog($text, $data, $pageName, $dataSearch);
            }
            return $updationModules;
        } catch (\Exception | RequestException $e) {
            Log::error('update_company_list_service', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    
    public function reviewCommentHistory($inputs, $formId)
    {
        try {
            return  ReviewFormComment::where('company_id', $inputs['company_id'])
            ->where('form_id', $formId)->orderBy('id', 'DESC')->get();
        } catch (\Exception | RequestException $e) {
            Log::error('company_update_module_history', ['error' => $e->getMessage()]);
            throw $e;
        }
    }
    

    public  function updateCompanyModule($inputs, $commentId)
    {
        try {
            DB::beginTransaction();
            $assignedCompanyModule = AssignModuleUpdation::find($commentId);
            $assignedCompanyModule->comments = $inputs['commentedText'];
            if (!empty($inputs['isUpdateStatus']) && $inputs['isUpdateStatus'] == true) {
                $assignedCompanyModule->module_commented_at = Carbon::now();
                $assignedCompanyModule->status = AssignModuleUpdation::UPDATE;
                $formCompany = CompanyForm::withTrashed()->where('form_id', $assignedCompanyModule->form_id)
                    ->where('company_id', $assignedCompanyModule->company_id)->first();
                $formCompany->status = CompanyForm::UPDATE;
                $formCompany->save();
            }
            $assignedCompanyModule->save();
            $formCompany = CompanyForm::withTrashed()->where('form_id', $assignedCompanyModule->form_id)
                ->where('company_id', $assignedCompanyModule->company_id)->first();
            $this->mailUpdateCompanyModule($formCompany);
            DB::commit();
            return $assignedCompanyModule;
        } catch (\Exception | RequestException $e) {
            DB::rollBack();
            Log::error('updated_company_module_service', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    /** update company module email bell notifications function **/
    public function mailUpdateCompanyModule($xmeForm)
    {
        $loggedUser = Auth::user();
        if (isImpersonatedSuperAdmin()) {
            $isSupport = true;
        } else {
            $isSupport = false;
        }
        $users = User::with('role')->where('company_id', $xmeForm->company->id)
            ->whereHas('role', function ($q) {
                $q->where('name', '!=', User::ROLE_EMPLOYEE);
            })->where('id', '!=', Auth::user()->id)->get();

        $data = [
            'loggedUser'    => $loggedUser,
            'isSupport'     => $isSupport,
            'xmeForm'       => $xmeForm,
        ];
        dispatch(new SendNotification(
            $users,
            $data,
            'UpdateCompanyModuleNotification',
            'sendUpdateCompanyModuleNotification',
            null
        ));
    }



    /*** activity logs change filter for review assign company module */
    public function updatedFilterType($type, $pageName, $isDeleted)
    {
        $typeName = $type == 1 ? 'XmeArea' : 'Library';

        if (!empty($type != '')) {
            if ($isDeleted) {
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

    /*** activity logs change filter for status review assign company module */
    public function updatedFilterStatus($status, $pageName, $isDeleted)
    {
        $statusName =  $status == 1 ? 'Active' : 'InActive';

        if ($status != '') {
            if ($isDeleted) {
                dispatch(new XmeActionLog(
                    auth()->user(),
                    'filter',
                    '{user} change filtered  status by "' . $statusName . '" ' . $pageName . '',
                    null,
                ));
            } else {
                dispatch(new XmeActionLog(
                    auth()->user(),
                    'filter',
                    '{user} change filtered  status by "' . $statusName . '" ' . $pageName . '',
                    null,
                ));
            }
        }
    }

    /** activity logs search  in review assign company module*/
    public function updatedSearch($search, $searchBy, $pageName, $isDeleted)
    {
        if ($search != '') {
            if ($isDeleted) {
                dispatch(
                    new XmeActionLog(
                        auth()->user(),
                        'search',
                        '{user} searched ' . $searchBy . ' by "' . $search . '" ' . $pageName . '',
                        null,
                    )
                );
            } else {
                dispatch(
                    new XmeActionLog(
                        auth()->user(),
                        'search',
                        '{user} searched ' . $searchBy . ' by "' . $search . '" ' . $pageName . '',
                        null,
                    )
                );
            }
        }
    }

    public function updateAllFilterLog($text, $data, $pageName, $dataSearch)
    {
        if (!empty($data)) {
            $text .= " filters by " . implode(', ', $data);
        }
        if (!empty($dataSearch)) {
            $text .= " module searched by " . implode(', ', $dataSearch);
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
