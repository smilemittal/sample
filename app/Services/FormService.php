<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;
use App\Jobs\XmeActionLog;
use App\Jobs\CreateTypeFormWebhook;
use App\Jobs\SendNotification;
use App\Models\Form;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use App\Models\Company;
use App\Models\CompanyForm;
use App\Models\Attachement;
use App\Models\Response;
use App\Models\FormTimeDescription;
use App\Models\ReviewForm;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Notifications\FormArchiveNotification;
use Illuminate\Support\Facades\Storage;

class FormService
{
    public function getList($inputs)
    {
        try {
            $data = [];
            $forms = Form::scpCompany();
            if ($inputs['isArchived']) {
                $forms->onlyTrashed()->withCount('responses');
            }
            $text = '';
            //filter module by id and name
            if (isset($inputs['search'])) {
                $search =  trim($inputs['search']);
                $forms = $forms->where(function ($query) use ($search) {
                    $query->where('typeform_id', 'LIKE', "%{$search}%")
                        ->orWhere('display_title', 'LIKE', "%{$search}%");
                });
                $text = 'searched modules by "' . $inputs['search'] . '"';
                // $this->updatedSearch($search, $inputs['isArchived']);
            }
            if (isset($inputs['type']) && $inputs['type'] == 1) {
                $forms->where('xme_area', 1);
                $data[] = 'type "' .($inputs['type'] == 1 ? 'Xme area' : 'Library') . '"';
                // $this->updatefilterType($inputs['type'], $inputs['isArchived']);
            } elseif (isset($inputs['type']) && $inputs['type'] == 0) {
                $forms->where('xme_area', 0);
                $data[] = 'type "' .($inputs['type'] == 1 ? 'Xme area' : 'Library') . '"';
                // $this->updatefilterType($inputs['type'], $inputs['isArchived']);
            }
            // filter module by companies
            if (isset($inputs['filter'])) {
                $filter = $inputs['filter'];
                $forms->whereHas('companies', function ($q) use ($filter) {
                    $q->where('name', 'LIKE', "%{$filter}%");
                });
                if ($filter != '') {
                    if ($inputs['isArchived']) {
                        dispatch(new XmeActionLog(
                            auth()->user(),
                            'search',
                            '{user} searched companies by "' . $filter . '" on module archive page',
                            null,
                        ));
                    } else {
                        dispatch(new XmeActionLog(
                            auth()->user(),
                            'search',
                            '{user} searched companies by "' . $filter . '" on module page',
                            null,
                        ));
                    }
                }
            }

            // filter module by status
            if (isset($inputs['status'])) {
                if ($inputs['status'] == 1) {
                    $forms = $forms->where('forms.status', 1);
                    $data[] = 'status "' .($inputs['status'] == 1 ? 'Active' : 'InActive') . '"';
                    // $this->updatedFilterStatus($inputs['status'], $inputs['isArchived']);
                } elseif ($inputs['status'] == 0) {
                    $forms = $forms->where(function ($query) {
                        $query->where('forms.status', 0)->orWhereNull('forms.status');
                    });
                    $data[] = 'status "' .($inputs['status'] == 1 ? 'Active' : 'InActive') . '"';
                    // $this->updatedFilterStatus($inputs['status'], $inputs['isArchived']);
                }
            }
            /**activity logs upcoming training */
            if (!empty($data)||!empty($text)) {
                $this->updateAllFilterLog($text, $data, $inputs['isArchived']);
            }
            return $forms->orderBy($inputs['sortField'], 'DESC')->paginate(10);
        } catch (\Exception | RequestException $e) {
            Log::error('form_list_service', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function updatedSearch($search, $isArchived)
    {
        if ($search != '') {
            if ($isArchived == 1) {
                dispatch(new XmeActionLog(
                    auth()->user(),
                    'search',
                    '{user} searched modules by "' . $search . '" on modules  archive page',
                    null,
                ));
            } else {
                dispatch(new XmeActionLog(
                    auth()->user(),
                    'search',
                    '{user} searched modules by "' . $search . '" on modules   page',
                    null,
                ));
            }
        }
    }

    public function updatedFilterStatus($status, $isArchived)
    {
        $statusName =  $status == 1 ? 'Active' : 'InActive';
        if ($status != '') {
            if ($isArchived == 1) {
                dispatch(new XmeActionLog(
                    auth()->user(),
                    'filter',
                    '{user} filtered  status by "' . $statusName . '"  on modules archive page',
                    null,
                ));
            } else {
                dispatch(new XmeActionLog(
                    auth()->user(),
                    'filter',
                    '{user} filtered  status by "' . $statusName . '"  on modules page',
                    null,
                ));
            }
        }
    }


    public function updateFilterType($type, $isArchived)
    {
        if ($type == 1) {
            $typeName = 'XmeArea';
        } elseif ($type == 0) {
            $typeName = 'Library';
        }
        if ($type != '') {
            if ($isArchived == 1) {
                dispatch(new XmeActionLog(
                    auth()->user(),
                    'filter',
                    '{user} filtered  type by "' . $typeName . '"  on archive modules  page',
                    null,
                ));
            } else {
                dispatch(new XmeActionLog(
                    auth()->user(),
                    'filter',
                    '{user} filtered  type by "' . $typeName . '"  on modules  page',
                    null,
                ));
            }
        }
    }

    public function updateFormStatus($formId)
    {
        try {
            $model = Form::where('id', $formId)->first();
            $model->status == 0 ? $model->status = 1 : $model->status = 0;
            $model->save();
            $status = $model->status;
            $status == 1 ? $statusMsg = 'Active' : $statusMsg = 'Inactive';
            if ($statusMsg == 'Active') {
                $msg = $msg = trans("messages.updated_the_form_status_active_successfully");
            } elseif ($statusMsg == 'Inactive') {
                $msg = $msg = trans("messages.updated_the_form_status_inactive_successfully");
            }
            dispatch(new XmeActionLog(
                auth()->user(),
                'filter',
                '{user} changed status to "' . $statusMsg . '" for "' . $model->display_title . '" in  modules page.',
                null,
            ));
            return [$model, $msg];
        } catch (\Exception | RequestException $e) {
            Log::error('form_status_change_service', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function validateForm($inputs)
    {
        try {
            $id = $inputs['typeform_id'];
            $response = Http::withToken('HK5cFuhtjbN9wMV19YFqG7K76MF98qqTPA8QEzNZSSJd')
                ->get("https://woofeculture.typeform.com/forms/$id");
            if (!$response->successful()) {
                return false;
            }
            $this->putWebhookFor($id);
            return $response->json();
        } catch (\Exception | RequestException $e) {
            Log::error('validate_form_service', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function putWebhookFor(string $formId)
    {
        $result = Http::withHeaders([
            //FIXME: make url dependend on environment by using App url
            'Authorization' => 'Bearer tfp_3vw8Q2wx9XYBRTS6w9vvavtv9FPdeqmN8nLi2EDwxdbQ_3mLKuoJf7EEkjC',
            'Content-Type' => 'application/json'
        ])->put("https://api.typeform.com/forms/$formId/webhooks/$formId" . env('APP_ENV'), [
            'url' => env('WEBHOOK_URL'),
            'enabled' => true,
            'verify_ssl' => true
        ]);
    }

    public function store($inputs)
    {
        try {
            DB::beginTransaction();
            // review date
            if (empty($inputs['is_review'])) {
                $inputs['review_date'] = Carbon::now()->addMonths(12);
            }
            if (!empty($inputs['is_review']) && ($inputs['review_category'] == 1)) {
                $inputs['review_date'] = Carbon::now()->addMonths(6);
            } elseif (!empty($inputs['is_review']) && ($inputs['review_category'] == 2)) {
                $inputs['review_date'] = Carbon::now()->addMonths(12);
            } elseif (
                !empty($inputs['is_review']) && ($inputs['review_category'] == 3)
                && (!empty($inputs['review_date']))
            ) {
                $inputs['review_date'] = carbon::parse($inputs['review_date']);
            }

            //create form
            $assignedRole = [];
            if ($inputs['is_assigned_default'] && $inputs['default_assignned_roles'] == null) {
                $assignedRole[] = 5;
                $assignedRole[] = 4;
                $assignedRole[] = 7;
                $inputs['default_assignned_roles'] = implode(',', $assignedRole);
            }
            $form = Form::create($inputs);
            // create time description
            if (isset($inputs['descTime'])) {
                foreach (json_decode($inputs["descTime"]) as $timeDetail) {
                    if (!empty($timeDetail->startTimeData) && !empty($timeDetail->endTimeData)) {
                        $form->form_time_detail()->create(
                            [
                                'start_time' => $timeDetail->startTimeData,
                                'end_time' => $timeDetail->endTimeData,
                                'description' => $timeDetail->description
                            ]
                        );
                    }
                }
            }
            if (!empty($inputs['file'])) {
                $this->storeUploads($form->id, $inputs['file']);
            }
            dispatch(new CreateTypeFormWebhook($form->id));
            //create action log
            dispatch(new XmeActionLog(
                auth()->user(),
                'store',
                '{user} created module "{model}"',
                $form,
            ));
            DB::commit();
            return $form;
        } catch (\Exception | RequestException $e) {
            DB::rollBack();
            Log::error('form_store_service', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function edit($formId)
    {
        try {
            $form = Form::find($formId);
            $timeDetail = FormTimeDescription::where('form_id', $form->id)->get();
            return ['form' => $form, 'timeDetail' => $timeDetail];
        } catch (\Exception | RequestException $e) {
            DB::rollBack();
            Log::error('edit_form_service', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function update($inputs, $id)
    {
        try {

            DB::beginTransaction();
            // review date
            if ($inputs['review_category'] == 1) {
                $inputs['review_date'] = Carbon::now()->addMonths(6);
            } elseif ($inputs['review_category'] == 2) {
                $inputs['review_date'] = Carbon::now()->addMonths(12);
            } elseif (($inputs['review_category'] == 3) && (!empty($inputs['review_date']))) {
                $inputs['review_date'] = carbon::parse($inputs['review_date']);
            }

            //create form
            $form = Form::find($id);
            if ($inputs['is_assigned_default'] && $inputs['default_assignned_roles'] == null) {
                $assignedRole[] = 4;
                $assignedRole[] = 5;
                $assignedRole[] = 7;
                $inputs['default_assignned_roles'] = implode(',', $assignedRole);
            }
            $updatedForm = $form->update($inputs);
            if (!empty($inputs['file'])) {
                $this->updateUploads($id, $inputs['file']);
            }

            if (isset($inputs['descTime'])) {
                $existedIdsArr = [];
                $newIdsArr     = [];
                $idsArr         = [];
                //dd(json_decode($inputs["descTime"]));
                foreach (json_decode($inputs["descTime"]) as $timeDetail) {
                    if (!empty($timeDetail)) {
                        if ((property_exists($timeDetail, 'id'))) {
                            $existedIdsArr[] = $timeDetail->id;
                            FormTimeDescription::where('id', $timeDetail->id)->update([
                                'start_time'       =>     $timeDetail->start_time,
                                'end_time'    =>  $timeDetail->end_time,
                                'description' => $timeDetail->description,
                            ]);
                        } else {
                            $result = $form->form_time_detail()->create([
                                "start_time"       => $timeDetail->start_time,
                                "end_time"    => $timeDetail->end_time,
                                "description" => $timeDetail->description
                            ]);
                            $newIdsArr[] = $result->id;
                        }
                    }
                }
                $idsArr = array_merge($existedIdsArr, $newIdsArr);
                $form->form_time_detail()->whereNotIn('id', $idsArr)->delete();
            }

            //dispatch(new CreateTypeFormWebhook($form->id));

            //create action log
            dispatch(new XmeActionLog(
                auth()->user(),
                'update',
                '{user} update module "{model}"',
                $form,
                null
            ));
            DB::commit();
            return $form;
        } catch (\Exception | RequestException $e) {
            DB::rollBack();
            Log::error('update_form_service', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function assignCompanyToForm($inputs)
    {

        try {
            DB::beginTransaction();
            $newCompanies = array_diff($inputs['companies'], $inputs['old_companies']);
            $previousSavedCompanies = CompanyForm::where('form_id', $inputs['form_id'])
                ->pluck('company_id')->toArray();
            $users = [];
            $savedCompanies = $removedCompanyFormId = [];
            if (!empty($inputs['companies'])) {
                $loggedUser = Auth::user();
                $form = Form::find($inputs['form_id']);
                if (User::hasRole(Auth::user(), User::ROLE_SUPERADMIN)) {

                    $users = User::with('role')->whereIn('company_id', $newCompanies)
                        ->whereHas('role', function ($q) {
                            $q->where('name', '!=', User::ROLE_EMPLOYEE);
                        })->where('id', '!=', Auth::user()->id)->get();

                    $companyAdmin = User::with('role', 'company')->whereIn('company_id', $newCompanies)
                        ->whereHas('role', function ($q) {
                            $q->where('name', '=', User::ROLE_COMPANY);
                        })->where('id', '!=', Auth::user()->id)->get();

                    $data = [
                        'form' => $form,
                        'loggedUser' => $loggedUser
                    ];

                    dispatch(new SendNotification(
                        $companyAdmin,
                        $data,
                        'AdminAssignFormToCompanyNotification',
                        'sendNewModuleAssignedToCompanyNotification',
                        $users
                    ));
                }
                foreach ($inputs['companies'] as $company_id) {
                    $companyForm = CompanyForm::withTrashed()->where('form_id', $inputs['form_id'])
                    ->where('company_id', $company_id)->first();
                    if ($companyForm) {
                        $reviewForm = ReviewForm::withTrashed()->where('company_form_id', $companyForm->id)
                        ->first();
                    }
                    if (empty($companyForm)) {
                        $form = Form::find($inputs['form_id']);
                        ($form->xme_area == 1) ? $status = 1 : $status = 0;
                        $companyForm = CompanyForm::firstOrCreate([
                            'form_id' => $inputs['form_id'],
                            'company_id' => $company_id,
                            'display_order' => 0,
                            'review_date' => Carbon::now()->addMonths(12),
                            'review_category' => 2,
                            'status'          => CompanyForm::NEW,
                            'module_status'   => $status
                        ]);
                        $reviewForm = ReviewForm::create([
                            'company_form_id' => $companyForm->id,
                            'status'     => ReviewForm::NEW,
                            'date'       => Carbon::now(),
                        ]);
                    } elseif (!empty($companyForm)) {
                        $companyForm->restore();
                        if ($reviewForm) {
                            $reviewForm->restore();
                        }
                    }
                    if (!in_array($company_id, $previousSavedCompanies)) {
                        if (!empty($companyForm->form)) {
                            dispatch(new XmeActionLog(
                                auth()->user(),
                                'create',
                                '{user} assigned module "{model}" to "' .
                                @$companyForm->company->name . '" company on modules page',
                                $companyForm->form,
                            ));
                        }
                    }
                    if (!in_array($companyForm->id, $savedCompanies)) {
                        $savedCompanies[] = $companyForm->id;
                    }
                }
            }


            $removedCompanies = CompanyForm::where('form_id', $inputs['form_id'])->whereNotIn('id', $savedCompanies)
                ->get();
            foreach ($removedCompanies as $rmcompany) {
                $removedCompanyFormId[] = $rmcompany->id;
                if (!empty($rmcompany->form)) {
                    dispatch(new XmeActionLog(
                        auth()->user(),
                        'remove',
                        '{user} removed module "{model}" from "' .
                        @$rmcompany->company->name . '" company on modules page',
                        $rmcompany->form,
                    ));
                }
            }
            CompanyForm::where('form_id', $inputs['form_id'])->whereNotIn('id', $savedCompanies)->delete();
            ReviewForm::whereIn('company_form_id', $removedCompanyFormId)->delete();
            DB::commit();
            return $savedCompanies;
        } catch (\Exception | RequestException $e) {
            DB::rollBack();
            Log::error('assign_form_to_company_service', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function assignCompany($formid)
    {
        try {
            $forms = array_map('strval', CompanyForm::where('form_id', $formid)->get()
                ->pluck('company_id')->toArray());
            return $forms;
        } catch (\Exception | RequestException $e) {
            DB::rollBack();
            Log::error('assigned_companies_service', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function storeUploads($targetId, $file)
    {
        $form = Form::where('id', $targetId)->first();
        $companyId = $form->company_id;
        $file->store('company/' . $companyId . '/media');
        $path = 'company/' . $companyId . '/media/' . $file->hashName();
        $form->attachements()->create([
            'filetype' => 'image',
            'path' => $path,
            'form_id' => $form->id,
            'filename' => $file->hashName(),
            'company_id' => $companyId
        ]);
    }

    public function updateUploads($formId, $file)
    {
        $form = Form::where('id', $formId)->first();
        $companyId = $form->company_id;
        $file->store('company/' . $companyId . '/media');
        $path = 'company/' . $companyId . '/media/' . $file->hashName();
        $attachment = Attachement::where('form_id', $formId)->first();
        if ($attachment) {
            Storage::delete('company/' . $companyId . '/media/' . $attachment->filename);
            $attachment->update([
                'path' => $path,
                'filename' => $file->hashName(),
            ]);
        } else {
            $form->attachements()->create([
                'filetype' => 'image',
                'path' => $path,
                'form_id' => $form->id,
                'filename' => $file->hashName(),
                'company_id' => $companyId
            ]);
        }
    }
    /** For soft delete form Action */
    public function formAction($inputs)
    {
        try {
            DB::beginTransaction();
            $form = Form::withTrashed()->with('form_company')->find($inputs['form_id']);
            if ($inputs['form_id'] != "") {
                if ($inputs['actionType'] == 'Archive') {
                    $form->delete();
                    $this->setFormArchiveLogs($inputs['actionType'], false, $form);
                    $msg = trans("messages.module_archived_successfully");
                } elseif ($inputs['actionType'] == 'UnArchive') {
                    $form->restore();
                    $this->setFormArchiveLogs($inputs['actionType'], true, $form);
                    $msg = trans("messages.module_unarchive_successfully");
                }
                if ($form->xme_area == 0) {
                    $this->mailFormArchive($form, $inputs['actionType']);
                }
            }
            DB::commit();
            return [$form, $msg];
        } catch (\Exception | RequestException $e) {
            DB::rollBack();
            Log::error('form_action_service', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    // notification for is library form archive
    public function mailFormArchive($form, $actionType)
    {

        if (isImpersonatedSuperAdmin()) {
            $isSupport = true;
        } else {
            $isSupport = false;
        }
        $loggedUser = Auth::user();
        $companies = CompanyForm::where('form_id', $form->id)->groupBy('company_id')->pluck('company_id')->toArray();
        if ($companies) {
            $users = User::with('role')->whereIn('company_id', $companies)
                ->whereHas('role', function ($q) {
                    $q->where('name', '=', User::ROLE_COMPANY);
                })->where('id', '!=', Auth::user()->id)->get();
            if (count($users) > 0) {
                foreach ($users as $user) {
                    $user
                        ->notify(new FormArchiveNotification(
                            $loggedUser,
                            $form,
                            $isSupport,
                            $actionType
                        ));
                }
            }
        }
    }

    /** For Multiple Archive and Multiple UnArchive Action*/
    public function multipleAction($inputs)
    {
        try {
            DB::beginTransaction();
            $forms = explode(",", $inputs['multipleForms']);
            if ($inputs['actionType'] == 'Archive') {
                foreach ($forms as $formId) {
                    $form = Form::find($formId);
                    $form->delete();
                    $this->setFormArchiveLogs($inputs['actionType'], false, $form);
                }
                $msg = trans("messages.multiple_modules_archived_successfully");
            } elseif ($inputs['actionType'] == 'UnArchive') {
                foreach ($forms as $formId) {
                    $form = Form::withTrashed()->find($formId);
                    $form->restore();
                    $this->setFormArchiveLogs($inputs['actionType'], true, $form);
                }
                $msg = trans("messages.multiple_modules_unarchive_successfully");
            }
            DB::commit();
            return [true, $msg];
        } catch (\Exception | RequestException $e) {
            DB::rollBack();
            Log::error('multiple_form_action_service', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    /**form archive and restore logs **/
    public function setFormArchiveLogs($actionType, $isArchived, $form)
    {

        if ($isArchived == 1) {
            dispatch(new XmeActionLog(
                auth()->user(),
                'UnArchive',
                '{user} ' . $actionType . ' the "{model}" module on archive modules page',
                $form,
            ));
        } else {
            dispatch(new XmeActionLog(
                auth()->user(),
                'Archive',
                '{user} ' . $actionType . ' the "{model}" module on  modules page',
                $form,
            ));
        }
    }

    public function pullResponsesFor($typeformId)
    {
        try {

            DB::beginTransaction();
            $time_requested = new \DateTime("now", new \DateTimeZone("UTC"));
            $response = Http::withToken('HK5cFuhtjbN9wMV19YFqG7K76MF98qqTPA8QEzNZSSJd')
                ->get("https://woofeculture.typeform.com/forms/$typeformId/responses");
            $resArray = $response->json();
            $seperatedItems = $resArray['items'];
            foreach ($seperatedItems as $singleResponse) {
                // extract the email
                $resEmail = "no email found";
                foreach ($singleResponse['answers'] as $answer) {
                    if ($answer['type'] == 'email') {
                        $resEmail = $answer['email'];
                        Log::debug('this email: ' . $resEmail);
                    }
                }

                //dd();
                if (!(Response::where('response_id', $singleResponse['response_id'])->exists())) {
                    // save into our model
                    // see https://stackoverflow.com/questions/53793841/add-title-to-fillable-property-to-allow-mass-assignment-on-app-post

                    $submittedDate = Carbon::parse($singleResponse['submitted_at']);
                    $submittedDate->setTimezone(config('app.timezone'));

                    $jsonItem = json_encode($singleResponse, true);

                    if ($finalResponse = Response::create([
                        'email' => $resEmail,
                        'json' => $jsonItem,
                        'response_id' => $singleResponse['response_id'],
                        'typeform_id' => $typeformId,
                        'time_requested' => $time_requested,
                        'submitted_at' => $submittedDate->format('Y-m-d H:i:s'),
                        //'submitted_at' => new \DateTime($singleResponse['submitted_at'], new \DateTimeZone("UTC"))
                    ])) {
                        Log::debug('response saved');
                    } else {
                        Log::debug('could not save response');
                    }
                } else {
                    Log::debug('duplicate response found');
                }
                return true;
            }
        } catch (\Exception | RequestException $e) {
            DB::rollBack();
            Log::error('pull_responses_api_service', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function updateAllFilterLog($text, $data, $isArchived)
    {
        if (!empty($data)) {
            $text .= " filters by " . implode(', ', $data);
        }
        if ($isArchived == 1) {
            dispatch(
                new XmeActionLog(
                    auth()->user(),
                    'search',
                    '{user} ' . $text . ' on modules archive page',
                    null,
                )
            );
        } else {
            dispatch(
                new XmeActionLog(
                    auth()->user(),
                    'search',
                    '{user} ' . $text .  ' on modules page',
                    null,
                )
            );
        }
    }

}
