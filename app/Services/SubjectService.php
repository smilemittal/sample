<?php

namespace App\Services;

use App\Models\User;
use Carbon\Carbon;
use App\Jobs\XmeActionLog;
use Exception;
use App\Models\Subject;
use App\Models\PpsSection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\SubjectPpsSection;
use Illuminate\Support\Facades\Session;
use GuzzleHttp\Exception\RequestException;
use App\Notifications\AddSubjectNotification;
use App\Helpers\NotificationHelper;
use App\Jobs\SendNotification;
use App\Notifications\SubjectStatusNotification;
use App\Models\AssignModuleUpdation;
use App\Models\CompanyForm;
use App\Models\Form;
use App\Models\Attachement;
use App\Models\ReviewFormComment;
use App\Models\ReviewForm;
use App\Notifications\BackBurnerNotification;
use App\Notifications\DeleteSubjectNotification;
use App\Notifications\FinaliseSubmitNotification;
use App\Notifications\SubjectArchiveNotification;
use App\Notifications\ThankYouFinalSubmitNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class SubjectService
{
    // create the subject
    public function store($input)
    {
        try {
            DB::beginTransaction();
            $model = Subject::create($input);
            if (!empty($input['files'])) {
                $this->storeTempUploads($input['files'], $model);
            }
            $this->fireCreateSubjectEmail($model);

            // action log according to session

            dispatch(new XmeActionLog(
                auth()->user(),
                'store',
                '{user} created subject "{model}" on identify page',
                $model,
            ));

            // Mail and Notifications
            DB::commit();
            return $model;
        } catch (\Exception | RequestException $e) {
            DB::rollback();
            Log::error('subject_create_service', ['error' => $e->getMessage()]);
            throw $e;
        }
    }
    /** create subject bell email notification */
    public function fireCreateSubjectEmail($subject)
    {

        $loggedUser = Auth::user();
        $users      = [];
        if (isImpersonatedSuperAdmin()) {
            $isSupport = true;
        } else {
            $isSupport = false;
        }
        if (User::hasRole(Auth::user(), User::ROLE_BUSINESSADMIN)
        || User::hasRole(Auth::user(), User::ROLE_EMPLOYEE)
        ) {
            $users = User::with('role')->where('company_id', $loggedUser->company_id)
                ->whereHas('role', function ($q) {
                    $q->where('name', User::ROLE_COMPANY);
                })->get();
        }
        $data = [
            'loggedUser'    => $loggedUser,
            'isSupport'     => $isSupport,
            'subject'       => $subject,
        ];
        dispatch(new SendNotification(
            $users,
            $data,
            'AddSubjectNotification',
            'sendCreatedSubjectNotification',
            null
        ));



        // $loggedUser = Auth::user();
        // if (isImpersonatedSuperAdmin()) {
        //     $isSupport = true;
        // } else {
        //     $isSupport = false;
        // }
        // //Mail and Notifications
        // if (User::hasRole($loggedUser, User::ROLE_EMPLOYEE)) {
        //     $businessAdmins = User::with('role')->where('company_id', $loggedUser->company_id)
        //         ->whereHas('role', function ($q) {
        //             $q->Where('name', User::ROLE_COMPANY);
        //         })->get();
        //     foreach ($businessAdmins as $user) {
        //         $user->notify(new AddSubjectNotification($loggedUser, $subject, $isSupport));
        //         NotificationHelper::sendCreatedSubjectNotification($user, $subject, $loggedUser);
        //     }
        // }
        // if (
        //     User::hasRole($loggedUser, User::ROLE_BUSINESSADMIN)
        //     || User::hasRole($loggedUser, User::ROLE_EMPLOYEE)
        // ) {
        //     $companyAdmin = User::with('role')->where('company_id', $loggedUser->company_id)
        //         ->whereHas('role', function ($q) {
        //             $q->Where('name', User::ROLE_COMPANY);
        //         })->first();
        //     $companyAdmin->notify(new AddSubjectNotification($loggedUser, $subject, $isSupport));
        //     NotificationHelper::sendCreatedSubjectNotification($companyAdmin, $subject, $loggedUser);
        // }
        // if (!User::hasRole($loggedUser, User::ROLE_COMPANY)) {
        //     NotificationHelper::sendUserSubmitSubjectIdentifyNotification($loggedUser);
        // }
    }

    // update the subject
    public function update($input, $subjectId)
    {
        try {
            DB::beginTransaction();
            $model = Subject::find($subjectId);
            if (!empty($input['direct_upload'])) {
                if (!empty($model->direct_upload)) {
                    Storage::delete($model->direct_upload);
                }
                $path = $input['direct_upload']
                ->store('company/' . Auth::user()->company_id . '/media/subject/' . $subjectId);
                $input['direct_upload'] = $path;
            }
            $model->update($input);
            if (!empty($input['files'])) {
                $this->storeTempUploads($input['files'], $model);
            }
            if (!empty($input['section_data']) && $input['develop']) {
                $existedIdsArr = [];
                $sections = PpsSection::where('status', 1)->get();
                foreach ($sections as $section) {
                    if (!empty($input['section_data'][$section->id])) {
                        foreach ($input['section_data'][$section->id] as $sectionRowValue) {
                            $images = null;
                            if (!empty($sectionRowValue['newImages'])) {
                                $images = $this->storeTempUploads($sectionRowValue['newImages'], $model, 1);
                                $images = implode(',', $images);
                            }
                            $sectionRowValue['data'] = json_decode($sectionRowValue['data']);
                            $data = [
                                "shot"       => $sectionRowValue['data']->shot,
                                "content"    => $sectionRowValue['data']->content,
                                "section_id" => $section->id,
                                "subject_id" => $model->id,
                                "type"       => $sectionRowValue['data']->type,
                                'images' => $images,
                                'videos' => $sectionRowValue['videos'] ?? ''
                            ];
                            if (!empty($sectionRowValue['id'])) {
                                $existedIdsArr[] = $sectionRowValue['id'];
                                SubjectPpsSection::where('id', $sectionRowValue['id'])->update($data);
                            } else {
                                $result = SubjectPpsSection::create($data);
                                $existedIdsArr[] = $result->id;
                            }
                        }
                    }
                }
                $model->ppsSectionDeatil()->whereNotIn('id', $existedIdsArr)->delete();
            }
            if ($input['develop']) {

                dispatch(new XmeActionLog(
                    auth()->user(),
                    'update',
                    '{user} updated subject "{model}" on develop page',
                    $model,
                ));

                Session::put('recentlyReviewed', $subjectId);
            } else {
                dispatch(new XmeActionLog(
                    auth()->user(),
                    'update',
                    '{user} updated subject "{model}" on identify page',
                    $model,
                ));
            }
            DB::commit();
            return $model;
        } catch (\Exception | RequestException $e) {
            DB::rollback();
            Log::error('subject_update_service', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    // get pps section
    public function getppsSection()
    {
        try {
            $sections = PpsSection::where("status", 1)->get();
            return $sections;
        } catch (Exception $e) {
            Log::error('pps_section_details_service', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    // delete Attachments
    public function deleteAttachement($id)
    {
        try {
            $attachment = Attachement::where("id", $id)->first();
            if ($attachment) {
                Storage::delete($attachment->path);
                $attachment->delete();
            }

            return true;
        } catch (Exception $e) {
            Log::error('delete_attachment_service', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    // subject file upload
    public function storeTempUploads($files, $model, $sectionId = null)
    {
        try {
            $imageNames = [];
            DB::beginTransaction();
            foreach ($files as $file) {
                if (empty($file)) {
                    continue;
                }
                $companyId = $model->company_id;
                $file->store("company/" . $companyId . "/media/subject/" . $model->id);
                $path = 'company/' . $companyId . '/media/subject/' . $model->id . "/" . $file->hashName();
                if ($sectionId) {
                    $imageNames[] = $path;
                } else {
                    $model->attachements()->create([
                        "filetype" => "image",
                        "path" => $path,
                        "subject_id" => $model->id,
                        "filename" => $file->hashName(),
                        "company_id" => $companyId,
                    ]);
                }

            }
            DB::commit();
            return $imageNames;
        } catch (\Exception | RequestException $e) {
            DB::rollback();
            Log::error('subject_image_service', ['error' => $e->getMessage()]);
            throw $e;
        }
    }


    //edit subject info
    public function editSubject($id)
    {
        try {
            $subject = Subject::with("attachements")->where("id", $id)->first();
            $sections = PpsSection::with([
                "section_detail" => function ($q) use ($subject) {
                    $q->where("subject_id", $subject->id);
                },
            ])->where('status', 1)->orderBy('display_order')->get();
            return ['subject' => $subject, 'sections' => $sections];
        } catch (Exception $e) {
            Log::error('edit_subject_service', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    //get all subjeact detai;
    public function getDetail($filterData)
    {
        try {
            $data = [];
            $subjects = Subject::with('company')->orderBy('id', 'DESC');
            // check identify and develop
            if ($filterData['is_develop'] == 'true') {
                if (User::hasRole(Auth::user(), User::ROLE_SUPERADMIN)) {
                    $subjects = $subjects->where(function ($query) {
                        $query->where('status', '=', \App\Models\STATUS_subject::SUBMITTED)
                            ->orWhere('status', '=', \App\Models\STATUS_subject::REOPEN);
                    });
                } else {
                    $subjects = $subjects->scpCompany()->scpDevelop();
                }
                $pageName = 'develop page';
            } else {
                $subjects = $subjects->scpCompany()->scpIdentify();
                $pageName = 'identify page';
            }
            // archive and soft delete
            if ($filterData['isArchived']) {
                $subjects = $subjects->onlyTrashed();
            }
            if (isset($filterData['priorityType'])) {
                $subjects = $subjects->where('priority', $filterData['priorityType']);

                if ($filterData['priorityType'] == 0) {
                    $data[] = 'priority Type "Low"';
                } elseif ($filterData['priorityType'] == 1) {
                    $data[] = 'priority Type "Medium"';
                } elseif ($filterData['priorityType'] == 2) {
                    $data[] = 'priority Type "High"';
                }
                // $this->updatedFilterType($filterData['priorityType'], $pageName, $filterData['isArchived']);
            }
            if (!empty($filterData['status'])) {
                $subjects = $subjects->where('status', $filterData['status']);

                if ($filterData['status'] == 101) {
                    $data[] = 'Idop Type "Approved"';
                } elseif ($filterData['status'] == 100) {
                    $data[] = 'Idop Type "Incomplete"';
                } elseif ($filterData['status'] == 103) {
                    $data[] = 'Idop Type "Submitted"';
                }
                // $this->updatedFilterType($filterData['status'], $pageName, $filterData['isArchived']);
            }
            if (isset($filterData['is_idops'])) {
                if ($filterData['is_idops'] == 1) {
                    $subjects = $subjects->where('identify_reason', 'REVIEW_UPDATE');
                }
                $data[] = 'status "' .($filterData['is_idops'] == 1 ? 'Review IDOPS' : 'IDOPS') . '"';
                // $this->updatedFilterStatus($filterData['is_idops'], $pageName, $filterData['isArchived']);
            }
            $text = '';
            if (!empty($filterData['search'])) {
                $search = trim($filterData['search']);
                $subjects = $subjects->where('subject', 'LIKE', "%{$search}%");
                // $this->updateSearch($search, $pageName, $filterData['isArchived']);
                $text = 'searched subject by "' . $search . '"';
            }
            $subjects = $subjects->paginate(10);
            /**activity logs */
            $pageName =  $filterData['isArchived'] == 1 ? 'on archive '. $pageName : 'on '. $pageName;

            if (!empty($data)||!empty($text)) {
                $this->updateAllFilterLog($text, $data, $pageName);
            }
            return $subjects;
        } catch (Exception $e) {
            Log::error('subject_detail_service', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function updateModule($inputs, $formId)
    {
        try {
            DB::beginTransaction();
            if ($inputs['updateActionType'] == 'reviewCompanyModule') {
                $xmeForm = Form::find($formId);
                $formCompany = CompanyForm::where('company_id', $inputs['company_id'])
                ->where('form_id', $xmeForm->id)->first();
                $moduleUpdations = new ReviewFormComment();
                if (!empty($inputs['files'])) {
                    $imgArr = [];
                    $files = $inputs['files'];
                    $companyId = Auth::user()->company_id;
                    foreach ($files as $file) {
                        if (empty($file)) {
                            continue;
                        }
                        $file->store("company/" . $companyId . "/media/form/" . $xmeForm->id);
                        $path = 'company/' . $companyId . '/media/form/' . $xmeForm->id . "/" . $file->hashName();
                        $imgArr[] = trim($path);
                    }
                    $moduleUpdations->images = implode(',', $imgArr);
                }
                $moduleUpdations->form_id = $xmeForm->id;
                $moduleUpdations->comments = $inputs['description'];
                $moduleUpdations->company_id = $inputs['company_id'];
                $moduleUpdations->user_id = Auth::user()->id;
                $moduleUpdations->company_form_id = $formCompany->id;
                $moduleUpdations->save();
                $CompanyForm = CompanyForm::where('form_id', $xmeForm->id)
                    ->where('company_id', $inputs['company_id'])->first();
                if (isSuperAdmin()) {
                    $CompanyForm->status = CompanyForm::UPDATE;
                } else {
                    $CompanyForm->status = CompanyForm::SEND_REQUSET_TO_ADMIN;
                }
                $CompanyForm->save();
                $reviewForm = ReviewForm::where('company_form_id', $CompanyForm->id)->first();
                if ($reviewForm) {
                    $reviewForm->status = ReviewForm::IN_PROCESS;
                    $reviewForm->save();
                }
                $this->mailRequestToUpdateModule($CompanyForm);
            } elseif ($inputs['updateActionType'] == 'reviewUpdateModule') {
                $loggedUser = Auth::user();
                $xmeForm = Form::find($formId);
                $isSubjectExist =   Subject::where('form_id', $xmeForm->id)
                    ->where('company_id', $loggedUser->company_id)
                    ->where('identify_reason', Subject::REVIEW_UPDATE)
                    ->where('status', '!=', \App\Models\STATUS_subject::SUBMITTED)
                    ->first();
                if (!($isSubjectExist)) {
                    $subjectFields['subject'] = $xmeForm->display_title;
                    $subjectFields['author'] = $loggedUser->id;
                    $subjectFields['company_id'] = $loggedUser->company_id;
                    $subjectFields['identify_reason'] = Subject::REVIEW_UPDATE;
                    $subjectFields['last_edit_by'] = $loggedUser->id;
                    $subjectFields['form_id'] = $xmeForm->id;
                    $subjectFields['description'] =  $inputs['description'];
                    $subjectFields['status'] = \App\Models\STATUS_subject::DRAFT;
                    $subjectFields['priority'] = 0;
                    Subject::create($subjectFields);
                }
                $this->mailUpdateReviewModule($xmeForm);
            }
            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('update_form_service', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function subjectAction($action)
    {
        try {
            if (!key_exists("destination", $action)) {
                return false;
            }

            if (!key_exists("modelId", $action)) {
                return false;
            }
            $model = Subject::with('company')->where("id", $action["modelId"])->first();
            if (!$model) {
                return;
            }
            if ($action["destination"] == 'develop') {
                $model->status = \App\Models\STATUS_subject::DEVELOP;
                $model->action_reason = null;
                $model->action_by = null;
                //mails and notification
                $subject = $model;
                $this->fireDevelopEmail($subject);
                $actionName = 'send to  develop subject';
                $pageName = 'to develop on identify page';
                $this->setSendToSubjectLog($actionName, $pageName, $model);
                $msg = trans("messages.send_the_identify_data_to_the_develop_section");
            } elseif ($action['destination'] == 'draft') {
                $model->status = \App\Models\STATUS_subject::DRAFT;
                $model->action_reason = null;
                $model->action_by = null;
                $msg = trans("messages.marked_the_status_draft");
            } elseif ($action['destination'] == 'incomplete') {
                $model->status = \App\Models\STATUS_subject::DEVELOP;
                /**active log incomplete  */
                $pageName   = 'on develop page';
                $this->setStatusChange($model->status, $model->subject, $pageName);
                $msg = trans("messages.marked_the_status_incomplete");
            } elseif ($action['destination'] == 'ready') {
                $model->status = \App\Models\STATUS_subject::READY;
                /**active log ready  */
                $pageName   = 'on develop page';
                $this->setStatusChange($model->status, $model->subject, $pageName);
                $msg = trans("messages.marked_the_status_ready");
            } elseif ($action['destination'] == 'finalise') {
                $model->status = \App\Models\STATUS_subject::SUBMITTED;
                $this->fireEmailFinalizeSubject($model);
                /**active log finalise  */
                $pageName   = 'on develop page';
                $this->setStatusChange($model->status, $model->subject, $pageName);
                $msg = trans("messages.finalise_and_submitted_the_subject");
            } elseif ($action['destination'] == 'identify') {
                $model->status = 0;
                $actionName = 'return to identify subject';
                $pageName = 'to identify on develop page';
                $this->setSendToSubjectLog($actionName, $pageName, $model);
                $msg = trans("messages.send_the_data_to_the_identify");
            } elseif ($action['destination'] == 'reOpen') {
                $model->status = \App\Models\STATUS_subject::REOPEN;
                $model->reopen_description = $action['reopen_description'];
                $msg = trans("messages.send_the_data_for_reopen");
                $company = $model->company;
                $this->setReopenSubjectLogs($company->name, $model);
            }
            $model->save();
            return [$model, $msg];
        } catch (Exception $e) {
            Log::error('subject_action_service', ['error' => $e->getMessage()]);
            throw $e;
        }
    }
    /** send to subject in develop bellnotifications */
    public function fireDevelopEmail($subject)
    {
        if (isImpersonatedSuperAdmin()) {
            $isSupport = true;
        } else {
            $isSupport = false;
        }
        $loggedUser = Auth::user();
        if (User::hasRole(Auth::user(), 'company')) {
            $businessAdmins = User::with("role")
                ->where("company_id", $loggedUser->company_id)
                ->whereHas("role", function ($q) {
                    $q->where("name", User::ROLE_BUSINESSADMIN);
                })
                ->get();

            foreach ($businessAdmins as $businessAdmin) {
                $businessAdmin->notify(
                    new SubjectStatusNotification(
                        $loggedUser,
                        $subject,
                        $isSupport
                    )
                );
            }
        } else {
            $businessAdmins = User::with("role")
                ->where("company_id", $loggedUser->company_id)
                ->whereHas("role", function ($q) {
                    $q->where("name", User::ROLE_BUSINESSADMIN);
                });

            if (!$isSupport) {
                $businessAdmins->where("id", "!=", $loggedUser->id);
            }
            $businessAdmins = $businessAdmins->get();

            $companyAdmin = User::where(
                "company_id",
                Auth::user()->company_id
            )
                ->whereHas("role", function ($q) {
                    $q->where("name", "company");
                })
                ->first();

            $companyAdmin->notify(
                new SubjectStatusNotification(
                    $loggedUser,
                    $subject,
                    $isSupport
                )
            );
        }
    }

    /**send subject in back burner bell notifications */
    public function fireBackBurnerEmail($model)
    {
        $loggedUser = Auth::user();
        $subject = $model;
        if (Session::has("previousUserSuperAdmin")) {
            $isSupport = true;
        } else {
            $isSupport = false;
        }

        if (User::hasRole(Auth::user(), User::ROLE_COMPANY)) {
            $companyAdmins = User::with("role")->where("company_id", $loggedUser->company_id)
                ->whereHas("role", function ($q) {
                    $q->where("name", User::ROLE_BUSINESSADMIN);
                })->get();
            foreach ($companyAdmins as $companyAdmin) {
                $companyAdmin->notify(new BackBurnerNotification($loggedUser, $subject, $isSupport));
            }
        } else {
            $businessAdmins = User::with("role")
                ->where("company_id", $loggedUser->company_id)
                ->whereHas("role", function ($q) {
                    $q->where("name", User::ROLE_COMPANY);
                });

            if (!$isSupport) {
                $businessAdmins->where("id", "!=", $loggedUser->id);
            }

            $businessAdmins = $businessAdmins->get();
            foreach ($businessAdmins as $businessAdmin) {
                $businessAdmin->notify(
                    new BackBurnerNotification(
                        $loggedUser,
                        $subject,
                        $isSupport
                    )
                );
            }
        }
        $admins = User::with("role")
            ->whereHas("role", function ($q) {
                $q->where("name", User::ROLE_SUPERADMIN);
            })
            ->get();
        foreach ($admins as $admin) {

            $admin->notify(
                new BackBurnerNotification(
                    $loggedUser,
                    $subject,
                    $isSupport
                )
            );
        }
    }

    /** for Subject soft delete action */
    public function deleteAction($inputs)
    {
        try {
            DB::beginTransaction();
            $subject = Subject::withTrashed()->with('lastEditor')->find($inputs['subject_id']);
            $reason =   isset($inputs['reasons']) ? $inputs['reasons'] : '';
            if ($inputs['subject_id'] != "") {
                if ($inputs['actionType'] == 'Archive') {
                    $subject->archived_reason = $reason;
                    $subject->delete();
                    $this->setSubjectLogs($inputs['actionType'], false, $inputs['actionPage'], $subject);
                    $msg = trans("messages.subject_archived_successfully");
                } elseif ($inputs['actionType'] == 'UnArchive') {
                    $subject->restore();
                    $this->setSubjectLogs($inputs['actionType'], true, $inputs['actionPage'], $subject);
                    $msg = trans("messages.subject_unarchive_successfully");
                }
                // fire notification
                $this->mailServiceArchive($reason, $subject, $inputs['actionType'], $inputs['actionPage']);

                $subject->save();
                /**soft delete functionlity active log */
            }
            DB::commit();
            return [$subject, $msg];
        } catch (\Exception | RequestException $e) {
            DB::rollBack();
            Log::error('subject_action_service', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    /** For Multiple Archive and Multiple UnArchive Action */
    public function multipleAction($inputs)
    {
        try {
            DB::beginTransaction();
            $subjectIds = explode(",", $inputs['multipleSubject']);

            if ($inputs['actionType'] == 'Archive') {
                foreach ($subjectIds as $subjectId) {
                    $subject = Subject::find($subjectId);
                    $subject->delete();
                    $this->setSubjectLogs($inputs['actionType'], false, $inputs['actionPage'], $subject);
                }
                $msg = trans("messages.multiple_subjects_archived_successfully");
            } elseif ($inputs['actionType'] == 'UnArchive') {
                foreach ($subjectIds as $subjectId) {
                    $subject = Subject::withTrashed()->find($subjectId);
                    $subject->restore();
                    $this->setSubjectLogs($inputs['actionType'], true, $inputs['actionPage'], $subject);
                }
                $msg = trans("messages.multiple_subjects_unarchive_successfully");
            }
            DB::commit();
            return [true, $msg];
        } catch (\Exception | RequestException $e) {
            DB::rollBack();
            Log::error('multiple_subject_action_service', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    /**subject delete bell email notifications **/
    public function fireSubjectDeleteEmail($subject)
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
                $q->where('name', User::ROLE_COMPANY)
                    ->orwhere('name', User::ROLE_BUSINESSADMIN)
                    ->orwhere('name', User::ROLE_EMPLOYEE);
            })->where('id', '!=', Auth::user()->id)
            ->orwhereHas('role', function ($q) {
                $q->where('name', User::ROLE_SUPERADMIN);
            })->get();
        $data = [
            'loggedUser'    => $loggedUser,
            'isSupport'     => $isSupport,
            'subject'       => $subject,
        ];
        dispatch(new SendNotification(
            $users,
            $data,
            'DeleteSubjectNotification',
            'sendRemoveSubjectNotification',
            null
        ));
    }

    /**user finalise submit subject  bell email notifications **/
    public function fireEmailFinalizeSubject($subject)
    {
        $loggedUser = Auth::user();
        if (isImpersonatedSuperAdmin()) {
            $isSupport = true;
        } else {
            $isSupport = false;
        }

        $users = User::with('role')->where('company_id', $loggedUser->company_id)
        ->whereHas('role', function ($q) {
            $q->where('name', User::ROLE_COMPANY)
                ->orwhere('name', User::ROLE_BUSINESSADMIN);
        })->where('id', '!=', Auth::user()->id)
        ->orwhereHas('role', function ($q) {
            $q->where('name', User::ROLE_SUPERADMIN);
        })->get();
        $data = [
            'loggedUser'    => $loggedUser,
            'isSupport'     => $isSupport,
            'subject'       => $subject,
        ];
        dispatch(new SendNotification(
            $users,
            $data,
            'FinaliseSubmitNotification',
            'sendUserFinalSubmitSubjectNotification',
            null,
        ));

        /** activitylogs */

        dispatch(new XmeActionLog(
            auth()->user(),
            'store',
            '{user} finalised and submitted "{model}".',
            $subject,
        ));
    }

    // fire notification if company archive action
    public function mailServiceArchive($reason, $subject, $actionType, $actionPage)
    {

        if (isImpersonatedSuperAdmin()) {
            $isSupport = true;
        } else {
            $isSupport = false;
        }
        $loggedUser = Auth::user();
        $roles = [];
        $roles[] = User::ROLE_SUPERADMIN;
        $roles[] = User::ROLE_COMPANY;
        $roles[] = User::ROLE_BUSINESSADMIN;
        if ($actionPage == 'identify') {
            $users = User::where('id', '=', $subject->last_edit_by)->where('id', '!=', Auth::user()->id)->get();
        } elseif (in_array(Auth::user()->role->name, $roles) && $actionPage == 'develop') {
            $users = User::with('role')->where('company_id', $subject->company_id)
                ->whereHas('role', function ($q) {
                    $q->where('name', '!=', User::ROLE_EMPLOYEE);
                })->where('id', '!=', Auth::user()->id)->get();
        }
        $data = [
            'loggedUser'    => $loggedUser,
            'subject'       => $subject,
            'isSupport'     => $isSupport,
            'actionType'    => $actionType,
            'reason'        => $reason,
            'actionPage'    => $actionPage,

        ];
        dispatch(new SendNotification(
            $users,
            $data,
            'SubjectArchiveNotification',
            null,
            null
        ));
    }


    /** update review module email bell notifications function **/
    public function mailUpdateReviewModule($xmeForm)
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
            'UpdateReviewModuleNotification',
            'sendUpdateReviewModuleNotification',
            null
        ));
    }

    /** send request to update module email bell notifications function **/
    public function mailRequestToUpdateModule($xmeForm)
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
            'RequestToUpdateModuleNotification',
            'sendRequestToUpdateModuleNotification',
            null
        ));
    }


    /*** activity logs change filter type for  develop page */
    public function updatedFilterType($type, $pageName, $isArchived)
    {
        if ($type == 101) {
            $typeName = 'Approved';
        } elseif ($type == 100) {
            $typeName = 'InComplete';
        } elseif ($type == 103) {
            $typeName = 'Submitted';
        } elseif ($type == 0) {
            $typeName = 'Draft';
        } elseif ($type == 0) {
            $typeName = 'Low';
        } elseif ($type == 1) {
            $typeName = 'Medium';
        } elseif ($type == 2) {
            $typeName = 'High';
        }

        if ($type != '') {
            if ($isArchived == 1) {
                dispatch(new XmeActionLog(
                    auth()->user(),
                    'filter',
                    '{user} change filtered  type by "' . $typeName . '" on archive ' . $pageName . '',
                    null,
                ));
            } else {
                dispatch(new XmeActionLog(
                    auth()->user(),
                    'filter',
                    '{user} change filtered  type by "' . $typeName . '" on ' . $pageName . '',
                    null,
                ));
            }
        }
    }

    /*** activity logs change filter status for  develop page */
    public function updatedFilterStatus($status, $pageName, $isArchived)
    {

        $statusName =  $status == 1 ? 'Review IDOPS' : 'IDOPS';
        if ($status != '') {
            if ($isArchived) {
                dispatch(new XmeActionLog(
                    auth()->user(),
                    'filter',
                    '{user} change filtered  status by "' . $statusName . '" on archive ' . $pageName . '',
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

    /**action logs search ***/
    public function updateSearch($search, $pageName, $isArchived)
    {
        if ($search != '') {
            if ($isArchived) {
                dispatch(new XmeActionLog(
                    auth()->user(),
                    'search',
                    '{user} searched subjects by "' . $search . '" on archive ' . $pageName . '',
                    null,
                ));
            } else {
                dispatch(new XmeActionLog(
                    auth()->user(),
                    'search',
                    '{user} searched subjects by "' . $search . '" on ' . $pageName . '',
                    null,
                ));
            }
        }
    }


    /** set Subject Action Log  */
    public function setReopenSubjectLogs($company, $subject)
    {
        dispatch(new XmeActionLog(
            auth()->user(),
            'reopen',
            '{user} reopen subject "{model}" to  "' . $company . ' company" to submit more details',
            $subject,
        ));
    }

    /**action logs send to subject */
    public function setSendToSubjectLog($actionName, $pageName, $subject)
    {
        dispatch(new XmeActionLog(
            auth()->user(),
            'update',
            '{user} ' . $actionName . ' "{model}"' . $pageName . '',
            $subject,
        ));
    }


    public function setStatusChange($status, $subject, $pageName)
    {

        if ($status == 101) {
            $statusName = 'Approved';
        } elseif ($status == 102) {
            $statusName = 'Dismissed';
        } elseif ($status == 103) {
            $statusName = 'Submitted';
        } elseif ($status == 100) {
            $statusName = 'Incomplete';
        }
        dispatch(new XmeActionLog(
            auth()->user(),
            'create',
            '{user}  status changed  "' . $statusName . '" to  subject "' . $subject . '"' . $pageName . '',
            null,
        ));
    }

    /** set Subject log list */
    public function setSubjectLogs($actionType, $isArchived, $pageName, $subject)
    {
        if ($isArchived == 1) {
            dispatch(new XmeActionLog(
                auth()->user(),
                'UnArchive',
                '{user} ' . $actionType . ' the "{model}" subject on archive ' . $pageName . ' page',
                $subject,
            ));
        } else {
            dispatch(new XmeActionLog(
                auth()->user(),
                'Archive',
                '{user} ' . $actionType . ' the  "{model}" subject on ' . $pageName . ' page',
                $subject,
            ));
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
