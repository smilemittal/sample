<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $icon = '';
        if ($this->type == 'App\Notifications\AssestAddedToGroupNotification') {
            $type = 'AssestAddedToGroup';
            $icon = 'UserGroupIcon';
        } elseif ($this->type == 'App\Notifications\AddedGroupNotification') {
            $type = 'AddedGroup';
            $icon = 'UserGroupIcon';
        } elseif ($this->type == 'App\Notifications\ArchiveFormLibraryNotification') {
            $type = 'ArchiveFormLibrary';
            $icon = 'ArchiveBoxIcon';
        } elseif ($this->type == 'App\Notifications\UnarchiveFormLibraryNotification') {
            $type = 'UnarchiveFormLibrary';
            $icon = 'ArchiveBoxArrowDownIcon';
        } elseif ($this->type == 'App\Notifications\DeleteMemberNotification') {
            $type = 'DeleteMember';
            $icon = 'TrashIcon';
        } elseif ($this->type == 'App\Notifications\DeleteSubjectNotification') {
            $type = 'DeleteSubject';
            $icon = 'TrashIcon';
        } elseif ($this->type == 'App\Notifications\SubjectStatusNotification') {
            $type = 'SubjectStatus';
            $icon = 'QuestionMarkCircleIcon';
        } elseif ($this->type == 'App\Notifications\UserTrainingCompleteNotification') {
            $type = 'UserTrainingComplete';
            $icon = 'CheckCircleIcon';
        } elseif ($this->type == 'App\Notifications\AdminArchiveNotification') {
            $type = 'AdminArchive';
            $icon = 'ArchiveBoxIcon';
        } elseif ($this->type == 'App\Notifications\AdminUnarchiveNotification') {
            $type = 'AdminUnarchive';
            $icon = 'ArchiveBoxArrowDownIcon';
        } elseif ($this->type == 'App\Notifications\FinaliseSubmitNotification') {
            $type = 'FinaliseSubmit';
            $icon = 'ClipboardDocumentCheckIcon';
        } elseif ($this->type == 'App\Notifications\BackBurnerNotification') {
            $type = 'BackBurner';
            $icon = 'FireIcon';
        } elseif ($this->type == 'App\Notifications\AssignUserPrevilegesNotification') {
            $type = 'AssignUserPrevileges';
            $icon = 'AdjustmentsVerticalIcon';
        } elseif ($this->type == 'App\Notifications\UserAssignFormNotification') {
            $type = 'UserAssignForm';
            $icon = 'PencilSquareIcon';
        } elseif ($this->type == 'App\Notifications\UserAddedToGroupNotification') {
            $type = 'UserAddedToGroup';
            $icon = 'UserGroupIcon';
        } elseif ($this->type == 'App\Notifications\RevokeUserPrevilegesNotification') {
            $type = 'RevokeUserPrevileges';
            $icon = 'AdjustmentsVerticalIcon';
        } elseif ($this->type == 'App\Notifications\AddSubjectNotification') {
            $type = 'AddSubject';
            $icon = 'UserPlusIcon';
        } elseif ($this->type == 'App\Notifications\AdminAssignFormToCompanyNotification') {
            $type = 'AdminAssignFormToCompany';
            $icon = 'PencilSquareIcon';
        } elseif ($this->type == 'App\Notifications\ThankYouFinalSubmitNotification') {
            $type = 'ThankYouFinalSubmit';
            $icon = 'PencilSquareIcon';
        } elseif ($this->type == 'App\Notifications\UpdateDirectoryNotification') {
            $type = 'UpdateDirectory';
            $icon = 'PencilSquareIcon';
        } elseif ($this->type == 'App\Notifications\AddModuleToDirectoryNotification') {
            $type = 'AddModuleToDirectory';
            $icon = 'PencilSquareIcon';
        } elseif ($this->type == 'App\Notifications\DeleteDirectoryModuleNotification') {
            $type = 'DeleteDirectoryModule';
            $icon = 'PencilSquareIcon';
        } elseif ($this->type == 'App\Notifications\SubjectArchiveNotification') {
            $type = 'SubjectArchive';
            $icon = 'ArchiveBoxArrowDownIcon';
        } elseif ($this->type == 'App\Notifications\FormArchiveNotification') {
            $type = 'FormArchive';
            $icon = 'ArchiveBoxArrowDownIcon';
        } elseif ($this->type == 'App\Notifications\AddDirectoryNotification') {
            $type = 'AddDirectory';
            $icon = 'FolderPlusIcon';
        } elseif ($this->type == 'App\Notifications\AddLearningPathNotification') {
            $type = 'AddLearningPath';
            $icon = 'AcademicCapIcon';
        } elseif ($this->type == 'App\Notifications\UpdateLearningPathNotification') {
            $type = 'UpdateLearningPath';
            $icon = 'AcademicCapIcon';
        } elseif ($this->type == 'App\Notifications\LockLearningPathNotification') {
            $type = 'LockLearningPath';
            $icon = 'LockClosedIcon';
        } elseif ($this->type == 'App\Notifications\DeleteLearningPathNotification') {
            $type = 'DeleteLearningPath';
            $icon = 'TrashIcon';
        } elseif ($this->type == 'App\Notifications\AddModuleToLearningPathNotification') {
            $type = 'AddModuleToLearningPath';
            $icon = 'PencilSquareIcon';
        } elseif ($this->type == 'App\Notifications\RemoveLearningPathModuleNotification') {
            $type = 'RemoveLearningPathModule';
            $icon = 'TrashIcon';
        } elseif ($this->type == 'App\Notifications\AddMemberToLearningPathNotification') {
            $type = 'AddMemberToLearningPath';
            $icon = 'AcademicCapIcon';
        } elseif ($this->type == 'App\Notifications\RemoveLearningPathMemberNotification') {
            $type = 'RemoveLearningPathMember';
            $icon = 'TrashIcon';
        } elseif ($this->type == 'App\Notifications\CompletedLearningPathModuleNotification') {
            $type = 'CompletedLearningPathModule';
            $icon = 'AcademicCapIcon';
        } elseif ($this->type == 'App\Notifications\ArchiveLearningPathNotification') {
            $type = 'ArchiveLearningPath';
            $icon = 'ArchiveBoxIcon';
        } elseif ($this->type == 'App\Notifications\UnarchiveLearningPathNotification') {
            $type = 'UnarchiveLearningPath';
            $icon = 'ArchiveBoxIcon';
        } elseif ($this->type == 'App\Notifications\RestoreLearningPathNotification') {
            $type = 'RestoreLearningPath';
            $icon = 'AcademicCapIcon';
        } elseif ($this->type == 'App\Notifications\DuplicateLearningPathNotification') {
            $type = 'DuplicateLearningPath';
            $icon = 'AcademicCapIcon';
        } elseif ($this->type == 'App\Notifications\UpdateReviewModuleNotification') {
            $type = 'UpdateReviewModule';
            $icon = 'AcademicCapIcon';
        } elseif ($this->type == 'App\Notifications\UpdateReviewModuleDateNotification') {
            $type = 'UpdateReviewModuleDate';
            $icon = 'AcademicCapIcon';
        } elseif ($this->type == 'App\Notifications\ReviewReminderNotification') {
            $type = 'ReviewReminder';
            $icon = 'AcademicCapIcon';
        } elseif ($this->type == 'App\Notifications\UpdateCompanyModuleNotification') {
            $type = 'UpdateCompanyModule';
            $icon = 'AcademicCapIcon';
        } elseif ($this->type == 'App\Notifications\RequestToUpdateModuleNotification') {
            $type = 'RequestToUpdateModule';
            $icon = 'AcademicCapIcon';
        } elseif ($this->type == 'App\Notifications\MoveToLibraryNotification') {
            $type = 'MoveToLibrary';
            $icon = 'AcademicCapIcon';
        } elseif ($this->type == 'App\Notifications\DeleteDirectoryNotification') {
            $type = 'DeleteDirectory';
            $icon = 'AcademicCapIcon';
        } elseif ($this->type == 'App\Notifications\ModuleExpiryNotification') {
            $type = 'ModuleExpiry';
            $icon = 'AcademicCapIcon';
        } elseif ($this->type == 'App\Notifications\NewAssignedFormReminderNotification') {
            $type = 'NewAssignedFormReminder';
            $icon = 'AcademicCapIcon';
        } else {
            $type = $this->type;
        }
        return [
            'id'                => $this->id,
            'component'         => $type,
            'type'              => $this->type,
            'data'              => $this->data,
            'differenceDate'    => \Carbon\Carbon::parse($this->created_at)->diffForHumans(),
            'read_at'           => $this->read_at,
            "notifiable_id"     => $this->notifiable_id,
            "notifiable_type"   => $this->notifiable_type,
            "updated_at"        => $this->updated_at,
            'iconClass'         => $icon
        ];
    }
}
