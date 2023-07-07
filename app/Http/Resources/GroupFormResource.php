<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\GroupForm;

class GroupFormResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
     
        $scheduleTime = '';
        if (!empty($this->reassign_interval)) {
            if ($this->reassign_interval == 'monthly') {
                $scheduleTime = ' Every month on day '.
                $this->month_reassign_day.' @ ' . $this->reassign_time;
            } elseif ($this->reassign_interval == 'weekly') {
                $scheduleTime = GroupForm::WEEKDAYS[$this->week_reassign_day].' @ '.$this->reassign_time;
            } else {
                $scheduleTime = 'After every' . $this->custom_interval . 'days @ '. $this->reassign_time ;
            }
        }
        $closedOn = '';
        if ($this->is_reassign && $this->end_point) {
            if ($this->end_type == 'fix') {
                $closedOn = 'Schedule closed on '. $this->end_date;
            } else {
                $closedOn = 'Schedule closed after'. $this->end_times .' times and pending times
                is '. $this->pending_end_times ;
            }
        }

        $url = '';
        if (count($this->form->attachements)>0) {
            $attachement = $this->form->attachements()->getModels()[0];
            $url = $attachement->path;
        } else {
            $url = '/images/placeholder-16-9.jpeg';
        }

        return [
            'id' => $this->id,
            'display_title' => $this->form->display_title,
            'closedOn'      => $closedOn,
            'scheduleTime'  => $scheduleTime,
            'image'         => $url,
            'formId'        => $this->form->id,
            'is_reassign'   => $this->is_reassign,
            'archived_at'    => $this->archived_at,
            'company_archived_at' => $this->company_archived_at
          ];
    }
}
