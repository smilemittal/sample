<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\AssignModuleUpdation;
use App\Models\CompanyForm;
use App\Models\Subject;
use Illuminate\Support\Str;

class FormResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $url = '';
        if (count($this->attachements) > 0) {
            $attachement = $this->attachements()->getModels()[0];
            $url = $attachement->path;
        } else {
            $url = '/images/placeholder-16-9.jpeg';
        }

        if ($this->status == 1) {
            $status = 'Active';
            $statusClass = 'bg-btnSubmitBg text-btnSubmitText rounded-full px-3 py-1 text-xs';
        } else {
            $status = 'Inactive';
            $statusClass = 'bg-btnCancelBg text-btnCancelText rounded-full px-3 py-1 text-xs';
        }
        if ($this->xme_area == 0) {
            $moduleType = 'Library';
            $moduleClass = 'bg-btnSubmitBg text-btnSubmitText rounded-full px-3 py-1 text-xs';
        } else {
            $moduleType = 'Xme Area';
            $moduleClass = 'bg-bgAmberTag text-amber-500 rounded-full px-3 py-1 text-xs';

        }

        $responses = $this->responses()->getModels();
        //module completed count and class
        $completedCount = $completedTagClass = $formCompletedDate =  '';
        if ($this->complete_count) {
            $completedTagClass = $this->complete_count > 0 ? 'tag-orange' : 'tag-red' ;
            $completedCount = $this->complete_count > 0 ? $this->complete_count : 'NO';
        }
        if ($this->completed_date) {
            $formCompletedDate = $this->completed_date;
        }
        $companyModuleUpdateStatus = '';
        if ($this->company_module_status) {
            $companyModuleUpdateStatus = $this->company_module_status;
        }
        $isArcheivedClass= ((!empty($this->archived_at)) ? 'pointer-events:none' : '');

        // form is undergo for review
        $isFormUnderReview = false;
        $isFormReview = Subject::where('company_id', auth()->user()->company_id)->where('form_id', $this->id)
        ->where('identify_reason', Subject::REVIEW_UPDATE)->first();
        if($isFormReview) {
            $isFormUnderReview = true;
        }

        return [
            'id'            => $this->id,
            'company_id'    => $this->company_id,
            'enctyptCompanyFormId' => encrypt_tech($this->form_company_id),
            'typeform_id'   => $this->typeform_id,
            'created_at'    => \Carbon\Carbon::parse($this->created_at)->format('Y:m:d H:i:s'),
            'formattedCreated_at'    => \Carbon\Carbon::parse($this->created_at)->format('d/m/Y'),
            'display_title' => $this->display_title,
            'image'         => $url,
            'status'        => $status,
            'statusClass'   => $statusClass,
            'moduleType'    => $moduleType,
            'moduleClass'   => $moduleClass,
            'tagClass'      => count($responses) > 0 ? 'bg-bgAmberTag text-amber-500 rounded-full px-2 py-1' :
            'bg-btnCancelBg text-btnCancelText rounded-full px-2 py-1',
            'responsesCount' => count($responses) > 0 ? count($responses) : 'NO',
            'reviewFormattedDate'  => \Carbon\Carbon::parse($this->review_date)->format('d/m/Y'),
            'viewUrl'              => '/view-status/'.encrypt_tech($this->id).
            '?company_id='.encrypt_tech($this->companyId),
            'adminReviewDate' => \Carbon\Carbon::parse($this->review_date)->format('Y:m:d H:i:s'),
            'companyName'     => $this->company_name,
            'completedTagClass' => $completedTagClass,
            'completedCount'    => $completedCount,
            'formCompletedDate' => $formCompletedDate,
            'companyModuleStatus' => $companyModuleUpdateStatus,
            'encryptId'             => encrypt_tech($this->id),
            'review_category'        => $this->review_category,
            'description'            => $this->description,
            'review_date'            => \Carbon\Carbon::parse($this->review_date)->format('Y-m-d'),
            'formattedreviewDate'    => \Carbon\Carbon::parse($this->review_date)->format('d/m/Y'),
            'xme_area'               => ($this->xme_area ==1) ? true : false,
            'is_assigned_default' => ($this->is_assigned_default ==1) ? true : false,
            'default_assignned_roles' =>   explode(',', $this->default_assignned_roles),
            'newAssignedCompanyEncryptedFormid'=> encrypt_tech($this->xme_form_company_id),
            'companyFormReviewDate'  => \Carbon\Carbon::parse($this->form_review_date)->format('Y-m-d'),
            'listCompanyFormReviewDate'  => \Carbon\Carbon::parse($this->form_review_date)->format('Y-m-d H:i:s'),
            'isArcheivedClass'           => $isArcheivedClass,
            'archived_at'                => $this->archived_at,
            'company_archived_at'        => $this->company_archived_at,
            'company_form'               =>  CompanyForm::where('form_id', $this->id)
            ->where('company_id', auth()->user()->company_id)->first(),
            'isFormUnderReview'               => $isFormUnderReview
          ];
    }
}
