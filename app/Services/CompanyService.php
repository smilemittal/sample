<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;
use App\Models\Company;
use App\Models\User;
use App\Jobs\XmeActionLog;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class CompanyService
{
    public function getList($inputs)
    {
        try {
            $perPage = isset($inputs['per_page']) && $inputs['per_page'] ? $inputs['per_page'] : 50;
            if (isset($inputs['per_page']) && $perPage == 'all') {
                $perPage = false;
            }
            $filterArr = $fields = [];
            $text = '';
            if (isset($inputs['search'])) {
                $filterArr['search'] = $inputs['search'];
                $text = 'searched companies by "' . $inputs['search'] . '"';
                // $this->updatedSearch($filterArr['search'], $inputs['isArchived']);
            }
            $companyObj = new Company();

            $companyObj = $companyObj->list(
                $filterArr,
                $fields,
                'companies.id',
                'DESC',
                $inputs['isArchived'],
                $perPage
            );
            /**activity logs */
             if (!empty($text)) {
                $this->updateAllFilterLog($text, $inputs['isArchived']);
            }
            return $companyObj;
        } catch (\Exception | RequestException $e) {
            Log::error('company_list_service', ['error' => $e->getMessage()]);
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
                        '{user} searched companies by "' . $search . '" on companies archive page',
                        null,
                    )
                );
            } else {
                dispatch(
                    new XmeActionLog(
                        auth()->user(),
                        'search',
                        '{user} searched companies by "' . $search . '" on companies  page',
                        null,
                    )
                );
            }
        }
    }

    public function store($inputs)
    {
        try {
            DB::beginTransaction();
            if (!empty($inputs['file'])) {
                $inputs['logo'] = $this->storeLogoWith($inputs['file']);
            }
            $company = Company::create($inputs);
            dispatch(new XmeActionLog(
                auth()->user(),
                'store',
                '{user} created the  "{model}" company.',
                $company,
            ));
            DB::commit();
            return $company;
        } catch (\Exception | RequestException $e) {
            DB::rollback();
            Log::error('company_data_store_service', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function updateData($inputs, $companyId)
    {
        try {
            DB::beginTransaction();
            $company = Company::find($companyId);
            if (!empty($inputs['file'])) {
                if (!empty($company->logo)) {
                    Storage::delete($company->logo);
                }
                $inputs['logo'] = $this->storeLogoWith($inputs['file']);
            }
            $company->update($inputs);
            dispatch(new XmeActionLog(
                auth()->user(),
                'update',
                '{user} updated the "{model}" company',
                $company,
            ));
            DB::commit();
            return $company;
        } catch (\Exception | RequestException $e) {
            DB::rollback();
            Log::error('company_data_update_service', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function storeLogoWith($file)
    {
        try {
            $file->store('companies');
            return 'companies/' . $file->hashName();
        } catch (\Exception | RequestException $e) {
            Log::error('company_image_upload_service', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function edit($id)
    {
        try {
            return Company::find($id);
        } catch (\Exception | RequestException $e) {
            Log::error('company_edit_detail_service', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function destroy($id)
    {
        try {
            Company::where('id', $id)->delete();
        } catch (\Exception | RequestException $e) {
            DB::rollBack();
            Log::error('company_delete_service', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function actionAfterConfirmation($inputs)
    {

        try {
            $msg = '';
            DB::beginTransaction();
            if ($inputs['destination'] == 'Restore') {
                $currentCompany = Company::withTrashed()->find($inputs['modelId']);
                $currentCompany->restore();
                dispatch(new XmeActionLog(
                    auth()->user(),
                    'restore',
                    '{user} restored company "{model}"',
                    $currentCompany,
                ));
                $msg = trans('messages.restore_the_data_succesfully');
            } elseif ($inputs['destination'] == 'Hard Delete') {
                $currentCompany = Company::withTrashed()->find($inputs['modelId']);
                dispatch(new XmeActionLog(
                    auth()->user(),
                    'permanent-delete',
                    '{user} deleted company "{model}" permanently.',
                    $currentCompany,
                ));
                $currentCompany->forceDelete();
            } elseif ($inputs['destination'] == 'Soft Delete') {
                $currentCompany = Company::find($inputs['modelId']);
                dispatch(new XmeActionLog(
                    auth()->user(),
                    'permanent-delete',
                    '{user} deleted company "{model}" permanently.',
                    $currentCompany,
                ));
                $currentCompany->delete();
            }
            DB::commit();
            return [$currentCompany, $msg];
        } catch (\Exception | RequestException $e) {
            DB::rollBack();
            Log::error('Action_after_confirmation_service', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    // company logo delete service
    public function deleteImage($companyId)
    {
        try {
            DB::beginTransaction();
            $company = Company::find($companyId);
            $company->logo = null;
            $company->save();
            if (!empty($company->logo)) {
                Storage::delete($company->logo);
            }
            DB::commit();
            return $company;
        } catch (\Exception | RequestException $e) {
            DB::rollBack();
            Log::error('company_logo_delete_service', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function companyAction($inputs)
    {
        try {
            DB::beginTransaction();
            $msg = '';
            $xmeCompany = false;
            if ($inputs['company_id'] != "") {
                $xmeCompany = Company::withTrashed()->find($inputs['company_id']);
                //action log
                $users = User::withTrashed()->where('company_id', $xmeCompany->id)->get();
                $userId = [];
                foreach ($users as $user) {
                    $userId[] = $user->id;
                }
                if ($inputs['actionType'] == 'Archive') {
                    $xmeCompany->delete();
                    User::whereIn('id', $userId)->delete();
                    $this->setCompanyActionLog($inputs['actionType'], false, $xmeCompany);


                    $msg = trans("messages.company_archived_successfully");
                } elseif ($inputs['actionType'] == 'UnArchive') {
                    $xmeCompany->restore();
                    User::whereIn('id', $userId)->restore();
                    $this->setCompanyActionLog($inputs['actionType'], true, $xmeCompany);
                    $msg = trans("messages.company_unarchive_successfully");
                }
                $xmeCompany->save();
            }
            DB::commit();
            return [$xmeCompany, $msg];
        } catch (\Exception | RequestException $e) {
            DB::rollBack();
            Log::error('company_action_service', ['error' => $e->getMessage()]);
            throw $e;
        }

        # code...
    }

    public function multipleAction($inputs)
    {
        try {
            DB::beginTransaction();
            $companies = explode(",", $inputs['multipleCompanies']);

            if ($inputs['actionType'] == 'Archive') {
                foreach ($companies as $cmpId) {
                    $company = Company::with('user')->find($cmpId);
                    $company->delete();
                    $this->setCompanyActionLog($inputs['actionType'], false, $company);
                }
                $msg = trans("messages.multiple_companies_archived_successfully");
            } elseif ($inputs['actionType'] == 'UnArchive') {
                foreach ($companies as $cmpId) {
                    $company = Company::withTrashed()->with('user')->find($cmpId);
                    $company->restore();
                    $this->setCompanyActionLog($inputs['actionType'], true, $company);
                }
                $msg = trans("messages.multiple_companies_unarchive_successfully");
            }
            DB::commit();
            return [true, $msg];
        } catch (\Exception | RequestException $e) {
            DB::rollBack();
            Log::error('multiple_company_action_service', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    /**Company archive and restore logs **/
    public function setCompanyActionLog($actionType, $isArchived, $company)
    {
        if ($isArchived == 1) {
            dispatch(new XmeActionLog(
                auth()->user(),
                'UnArchive',
                '{user} ' . $actionType . ' the "{model}" company on archive companies page',
                $company,
            ));
        } else {
            dispatch(new XmeActionLog(
                auth()->user(),
                'Archive',
                '{user} ' . $actionType . ' the "{model}" company on companies page',
                $company,
            ));
        }
    }

    public function updateAllFilterLog($text, $isArchived)
    {
        if ($isArchived == 1) {
            dispatch(
                new XmeActionLog(
                    auth()->user(),
                    'search',
                    '{user} ' . $text . ' on companies archive page',
                    null,
                )
            );
        } else {
            dispatch(
                new XmeActionLog(
                    auth()->user(),
                    'search',
                    '{user} ' . $text .  ' on companies page',
                    null,
                )
            );
        }
    }
}
