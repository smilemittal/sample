<?php

namespace App\Http\Resources;

use App\Models\Attachement;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

class SubjectResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $directUploadLink = '';
        $statusString = \App\Models\STATUS_subject::statusStringFor($this->status);
        $statusTag = '';
        switch ($this->status) {
            case \App\Models\STATUS_subject::READY:
                $statusTag = 'bg-btnSubmitBg text-xs text-btnSubmitText py-1 px-2.5 rounded-full';

                break;
            case \App\Models\STATUS_subject::DISMISSED:
                $statusTag = 'bg-btnCancelBg text-xs text-btnCancelText py-1 px-2.5 rounded-full';

                break;
            case \App\Models\STATUS_subject::DRAFT:
                $statusTag = 'bg-bgAmberTag text-xs text-amber-500 py-1 px-2.5 rounded-full';

                break;
            case \App\Models\STATUS_subject::SUBMITTED:
                $statusTag = 'bgSubmittedBg text-xs py-1 px-2.5 rounded-full';
                break;
            case \App\Models\STATUS_subject::DEVELOP:
                $statusTag = 'bg-bgAmberTag text-xs text-amber-500 py-1 px-2.5 rounded-full';
                break;
            case \App\Models\STATUS_subject::REOPEN:
                $statusTag = 'bg-bgAmberTag text-xs text-amber-500 py-1 px-2.5 rounded-full';
                break;
            default:
                $statusTag = '';
                break;
        }

        $priorityString = \App\Models\PRIORITY_subject::priorityStringFor($this->priority);
        $prioritytag = '';
        switch ($this->priority) {
            case \App\Models\PRIORITY_subject::LOW:
                $prioritytag = 'bg-bgAmberTag text-xs text-amber-500 py-1 px-2.5 rounded-full';
                break;
            case \App\Models\PRIORITY_subject::MEDIUM:
                $prioritytag = 'bg-btnSubmitBg text-xs text-btnSubmitText py-1 px-2.5 rounded-full';
                break;

            case \App\Models\PRIORITY_subject::HIGH:
                $prioritytag = 'bg-btnCancelBg text-xs text-btnCancelText py-1 px-2.5 rounded-full';
                break;

            default:
                break;
        }

        // check if lastEditor->avatar is set
        $avatarUrl = '/';
        if (isset($this->lastEditor->avatar)) {
            $avatarUrl .= $this->lastEditor->avatar;
        } else {
            $avatarUrl  .= 'images/placeholder-profile.png';
        }

        return [
            'id'         => $this->id,
            'subject'    => $this->subject,
            'author'     => $this->author,
            'status'     => $statusString,
            'priority'   => $this->priority,
            'statusTag' =>  $statusTag,
            'prioritytag' => $prioritytag,
            'priorityString' => $priorityString,
            'imageUrl'       => $avatarUrl,
            'lastEditorName' => $this->lastEditor->name ?? 'PLNAME',
            'updated_at'     =>  Carbon::createFromFormat('Y-m-d H:i:s', $this->updated_at)->format('Y-m-d H:i:s'),
            'isDeleted'      => $this->archived_at,
            'statusValue'    => $this->status,
            'encryptId'      => encrypt_tech($this->id),
            'reason'         =>  $this->action_reason,
            'video_service_level'   => $this->video_service_level ?? '',
            'equipment'   => $this->equipment ?? '',
            'camera_style'   => $this->camera_style ?? '',
            'people_involved'   => $this->people_involved,
            'video_location'   => $this->video_location ?? '',
            'video_title'   => $this->video_title ?? '',
            'attachements' => $this->attachements ?? null,
            'develop_type' => $this->develop_type,
            'video_category' => $this->video_category,
            'video_description' => $this->video_description,
            'description'      => $this->description,
            'upload_link'      => $this->upload_link,
            'directUploadLink'  => $this->direct_upload,
            'reopen_description' => $this->reopen_description,
            'media_path' => "company/" . $this->company_id . "/media/subject/" . $this->id,
            'company'            => ($this->company) ? $this->company->name : 'Err',
            'archived_reason'    => $this->archived_reason,
            'identify_reason'    => $this->identify_reason
          ];
    }
}
