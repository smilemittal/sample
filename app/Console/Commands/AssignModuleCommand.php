<?php

namespace App\Console\Commands;

use App\Jobs\SendNotification;
use App\Models\CompanyForm;
use App\Models\GroupForm;
use App\Models\TrainingHistory;
use App\Models\User;
use Illuminate\Console\Command;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class AssignModuleCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:assign-module-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $formGroups = GroupForm::with('actualGroup.members.user', 'form')
        ->where('is_reassign', 1)->where(function ($query) {
            $query->whereHas('form', function ($q) {
                $q->whereNull('archived_at');
            });
        })->where('end_point', 1)->get();
        
        $today_date = \Carbon\Carbon::now();
        foreach ($formGroups as $group) {
            if ($group->end_point && (($group->end_type == 'fix' && $group->end_date < date('Y-m-d'))
            || ($group->end_type == 'custom' && $group->pending_end_times == 0))) {
                continue;
            }

            $actualGroup = $group->actualGroup;
            $company_id = $actualGroup->company_id;
            $company_admin =User::where('company_id', $company_id)->first();
            foreach ($actualGroup->members as $member) {
                $form_company = CompanyForm::where('form_id', $group->form_id)
                ->where('company_id', $actualGroup->company_id)->first();
                if ($form_company->archived_at == null) {
                    $training_history = TrainingHistory::where('form_id', $group->form_id)
                    ->where('group_id', $group->group_id)->where('user_id', $member->user_id)
                    ->latest()->first();

                    if ($group->reassign_interval == 'weekly') {
                        if ($today_date->dayOfWeekIso == $group->week_reassign_day) {
                            $assign_date = $today_date;
                        }
                    } elseif ($group->reassign_interval == 'monthly') {
                        if ($today_date->daysInMonth < $group->month_reassign_day) {
                            $assign_date = (clone $today_date)->endOfMonth();
                        } else {
                            if ($today_date->day == $group->month_reassign_day) {
                                $assign_date = $today_date;
                            }
                        }
                    } elseif ($group->reassign_interval == 'custom') {
                        $start_date = $group->updated_at;
                        $diffDays = $start_date->diffInDays($today_date);

                        if ($diffDays % $group->custom_interval == 0) {
                            $assign_date = $today_date;
                        }
                    }

                    $reassign_training = false;
                    if (!empty($assign_date)) {
                        if ($training_history) {
                            $training_history_assigned_date = Carbon::parse($training_history->assigned_date);
                            if ($training_history_assigned_date
                            ->format('Y-m-d') == (clone $assign_date)->format('Y-m-d')) {
                                $reassign_training = false;
                            } else {
                                $reassign_training = true;
                            }
                        } else {
                            $reassign_training = true;
                        }
                    }

                    if ($reassign_training) {
                        $reassign_time = \Carbon\Carbon::parse($group->reassign_time)->format('H:i');
                        $expiry_date = $this->getRessignExpiryDate($assign_date, $group);

                        if ((clone $assign_date)->format('Y-m-d') == (clone $today_date)
                        ->format('Y-m-d') && (clone $today_date)->format('H:i') == $reassign_time) {
                            $new_training = TrainingHistory::create([
                                'form_id' => $group->form_id,
                                'group_id' => $group->group_id,
                                'user_id' => $member->user_id,
                                'status' => 'pending',
                                'assigned_date' => $today_date->format('Y-m-d H:i:s'),
                                'expiry_date' => (!empty($expiry_date) ? $expiry_date->format('Y-m-d H:i:s') : ''),
                            ]);
                            
                            TrainingHistory::where('form_id', $group->form_id)->where('group_id', $group->group_id)
                                ->where('user_id', $member->user_id)->where('id', '!=', $new_training->id)
                                ->where('status', 'pending')
                                ->update(['status' => 'skipped', 'expiry_date' => $today_date->format('Y-m-d H:i:s')]);
                        }
                    }
                    $user_data=User::where('id', $member->user_id)->get();
                    $data = [
                       'member'      => $user_data,
                       'moduleData'  => $group->form->display_title,
                       'isSupport'   => null,
                       'loggedUser'  => $company_admin
                    ];
                    dispatch(new SendNotification(
                        $user_data,
                        $data,
                        'UserAssignFormNotification',
                        'sendNewModuleAssignedNotification',
                        null
                    ));
                }
            }
            if ($group->end_point && $group->end_type == 'custom') {
                $group->pending_end_times--;
                $group->save();
            }
        }
        return true;
        //
    }

    public function getRessignExpiryDate($date, $group)
    {
        if ($group->reassign_interval == 'weekly') {
            $expiry_date = (clone $date)->addWeek();
        } elseif ($group->reassign_interval == 'monthly') {
            $expiry_date = (clone $date)->addMonth();
        } elseif ($group->reassign_interval == 'custom') {
            $expiry_date = (clone $date)->addDays($group->custom_interval);
        }
        return $expiry_date;
    }
}
