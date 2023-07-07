<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\TrainingHistory;
use App\Models\Form;
use App\Models\LearningPath;
use Illuminate\Support\Facades\Session;
use App\Jobs\XmeActionLog;
use App\Models\User;
use App\Models\UserLearningPath;
use App\Models\LearningPathHistory;
use App\Notifications\UserTrainingCompleteNotification;
use App\Helpers\NotificationHelper;
use App\Jobs\SendNotification;

class TrainingHistoryService
{
    public function upComingTraining($inputs)
    {
        try {

            $data = [];
            $forms = TrainingHistory::select(
                'forms.archived_at',
                'forms.typeform_id',
                'training_histories.id as training_id',
                'display_title',
                'xme_area',
                'forms.status',
                'training_histories.assigned_date as assigned_date',
                'training_histories.form_id',
                'forms.id',
                'training_histories.group_id as direct'
            )->join('user_forms', function ($join) {
                $join->on('user_forms.form_id', '=', 'training_histories.form_id')
                    ->on('user_forms.user_id', '=', 'training_histories.user_id');
            })->join('forms', 'forms.id', '=', 'training_histories.form_id')
                ->join('users', 'users.id', '=', 'training_histories.user_id')
                ->join('company_forms', function ($join) {
                    $join->on('company_forms.form_id', '=', 'training_histories.form_id')
                        ->on('company_forms.company_id', '=', 'users.company_id')
                        ->whereNull('company_forms.archived_at');
                })
                ->whereNull('forms.archived_at')
                ->where('training_histories.status', 'pending')
                ->where('training_histories.group_id', '=', 0)
                ->where('users.id', Auth::user()->id);
            $group_forms = TrainingHistory::select(
                'forms.archived_at',
                'forms.typeform_id',
                'training_histories.id as training_id',
                'display_title',
                'xme_area',
                'forms.status',
                'training_histories.assigned_date as assigned_date',
                'training_histories.form_id',
                'forms.id',
                'training_histories.group_id as direct'
            )->join('group_forms', function ($join) {
                $join->on('group_forms.form_id', '=', 'training_histories.form_id')
                    ->on('group_forms.group_id', '=', 'training_histories.group_id');
            })
                ->join('user_groups', function ($join) {
                    $join->on('user_groups.user_id', '=', 'training_histories.user_id')
                        ->on('user_groups.group_id', '=', 'training_histories.group_id');
                })
                ->join('forms', 'forms.id', '=', 'training_histories.form_id')
                ->join('users', 'users.id', '=', 'training_histories.user_id')
                ->join('company_forms', function ($join) {
                    $join->on('company_forms.form_id', '=', 'training_histories.form_id')
                        ->on('company_forms.company_id', '=', 'users.company_id')
                        ->whereNull('company_forms.archived_at');
                })
                ->whereNull('forms.archived_at')
                ->whereNull('user_groups.archived_at')
                ->whereNull('group_forms.archived_at')
                ->where('training_histories.status', 'pending')
                // ->where('user_groups.scheduled_at', '<=', \Carbon\Carbon::now())
                ->where('users.id', Auth::user()->id);
            $text = '';
            if (!empty($inputs['search'])) {
                $search = trim($inputs['search']);
                $forms->where('display_title', 'LIKE', "%{$search}%");
                $group_forms->where('display_title', 'LIKE', "%{$search}%");
                $text = 'searched modules by "' . $inputs['search'] . '"';
            }
            if (isset($inputs['upcomingStatus'])) {
                $forms->where('forms.status', $inputs['upcomingStatus']);
                $group_forms->where('forms.status', $inputs['upcomingStatus']);
                $data[] = 'status "' . ($inputs['upcomingStatus'] == 1 ? 'Active' : 'InActive') . '"';
            }
            $forms->union($group_forms);
            $upcomingForms = $forms
                ->orderBy($inputs['sortField'], 'DESC')->paginate(10);
            $upcomingForms->load('form.attachements', 'form');
            /**activity logs upcoming training */
            $pageName = ' in the upcoming section of my training page';
            if (!empty($data) || !empty($text)) {
                $this->updateAllFilterLog($text, $data, $pageName);
            }

            return $upcomingForms;
        } catch (\Exception | RequestException $e) {
            Log::error('upcoming_training_list_service', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function completedTraining($inputs)
    {
        try {
            $data = [];
            $completedForms = Form::withTrashed()->join(
                'training_histories',
                'training_histories.form_id',
                '=',
                'forms.id'
            )->join('users', 'users.id', '=', 'training_histories.user_id')
                ->leftJoin('user_groups', function ($join) {
                    $join->on('user_groups.user_id', '=', 'training_histories.user_id')
                        ->on('user_groups.group_id', '=', 'training_histories.group_id');
                })
                ->leftJoin('groups', function ($join) {
                    $join->on('groups.id', '=', 'user_groups.group_id');
                })
                ->leftJoin('group_forms', function ($join) {
                    $join->on('group_forms.form_id', '=', 'training_histories.form_id')
                        ->on('group_forms.group_id', '=', 'groups.id');
                })
                ->leftJoin('company_forms', function ($join) {
                    $join->on('company_forms.form_id', '=', 'training_histories.form_id')
                        ->on('company_forms.company_id', '=', 'groups.company_id');
                })
                ->where('users.id', Auth::user()->id)
                ->where('training_histories.status', '!=', 'pending');
            $text = '';
            if (isset($inputs['search'])) {
                $search = trim($inputs['search']);
                $completedForms->where('forms.display_title', 'LIKE', "%{$search}%");
                $text = 'searched modules by "' . $inputs['search'] . '"';
            }
            if (!empty($inputs['completedStatus'])) {
                $completedForms->where('training_histories.status', $inputs['completedStatus']);
                $data[] = ' status "' . ($inputs['completedStatus'] == 'skipped' ? 'Skipped' : 'Completed') . '"';
            }

            $completedForms = $completedForms
                ->select(
                    'forms.id as id',
                    'users.id as user_id',
                    'forms.typeform_id as typeform_id',
                    DB::raw('count(*) as complete_count, max(completed_date) as completed_date,
                 max(expiry_date) as expiry_date, max(company_forms.archived_at) as company_archived_at'),
                    'forms.display_title',
                    'forms.status',
                    'forms.archived_at as archived_at'
                )

                ->groupBy('id', 'user_id', 'typeform_id', 'display_title', 'status', 'archived_at')
                ->orderBy($inputs['sortField'], $inputs['orderDir'])->paginate(10, ['*'], 'cPage');

            /**activity logs completed training */
            $pageName = ' in the completed section of my training page';
            if (!empty($data) || !empty($text)) {
                $this->updateAllFilterLog($text, $data, $pageName);
            }

            return $completedForms;
        } catch (\Exception | RequestException $e) {
            Log::error('completed_training_list_service', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function learningPathTraining($inputs)
    {
        try {
            $data = [];
            $learningGroups = LearningPath::withTrashed()
            ->withCount('members', 'forms')->with('learningpath')
                ->join(
                    'user_learning_paths',
                    'user_learning_paths.learning_group_id',
                    '=',
                    'learning_paths.id'
                );
            $learningGroups = $learningGroups->where('user_learning_paths.user_id', Auth::user()->id);
            $learningGroups = $learningGroups->groupBy('learning_paths.id')
                ->where('user_learning_paths.archived_at', '=', null)
                ->where(
                    'user_learning_paths.learningpath_schduled_at',
                    '<=',
                    \Carbon\Carbon::now()->format('Y-m-d H:i:s')
                );
            $learningGroups = $learningGroups->orderBy($inputs['sortField'], $inputs['orderDir']);
            $text = '';
            if (isset($inputs['search'])) {
                $search = trim($inputs['search']);
                $learningGroups =    $learningGroups->where('learning_paths.title', 'LIKE', "%{$search}%");
                $text = 'searched modules by "' . $inputs['search'] . '"';
            }
            /**activity logs completed training */
            $pageName = ' in the upcoming training section of my training page';
            if (!empty($text)) {
                $this->updateAllFilterLog($text, $data, $pageName);
            }
            $learningGroups = clone $learningGroups;
            $learningGroups = $learningGroups->paginate(10);
            return $learningGroups;
        } catch (\Exception | RequestException $e) {
            Log::error('learningpath_training_list_service', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function viewTrainingHistory($formId, $inputs)
    {
        try {
            $data = [];
            $text = '';
            $Training =  TrainingHistory::with('group', 'form')
                ->where('form_id', $formId)
                ->where('status', '!=', 'pending')
                ->where('user_id', Auth::user()->id);
            if (isset($inputs['type'])) {
                $Training = $Training->where('status', $inputs['type']);
                $data[] = 'Type "' . ($inputs['type'] == 'completed' ? 'completed' : 'skipped') . '"';
            }
            /**activity logs upcoming training */
            $pageName = 'in completed training view history  of my training page';
            if (!empty($data)) {
                $this->updateAllFilterLog($text, $data, $pageName);
            }
            $Training = $Training->get();
            return $Training;
        } catch (\Exception | RequestException $e) {
            Log::error('training_histories_service', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function completeTraining($inputs)
    {
        try {
            $xmeForm =  Form::find($inputs['form_id'])->first();
            $inputs['xme_form_id'] += $xmeForm->id;
            if ($xmeForm) {
                if (isset($inputs['learning_path']) && !empty($inputs['learning_path'])) {
                    $learningPath = $inputs['learning_path'];
                    $loggedUser = Auth::user();
                    $isAccess = UserLearningPath::where('user_id', $loggedUser->id)
                        ->where('company_id', $loggedUser->company_id)
                        ->where('learning_group_id', $learningPath)->first();
                    if (!($isAccess)) {
                        abort(404);
                    }
                    $this->mailCompletedLearningpathModule($learningPath, $xmeForm);
                    $LearningPathHistory =  new LearningPathHistory();
                    $LearningPathHistory->user_id = Auth()->user()->id;
                    $LearningPathHistory->form_id = $inputs['form_id'];
                    $LearningPathHistory->company_id = Auth()->user()->company_id;
                    $LearningPathHistory->type = 'Learning_Path';
                    $LearningPathHistory->learning_path_id = $learningPath;
                    $LearningPathHistory->save();
                    dispatch(new XmeActionLog(
                        auth()->user(),
                        'create',
                        '{user} has been completed training
                        "' . $xmeForm->display_title . '" to complete date "
                        ' . $LearningPathHistory->updated_at . '" on learning path training page',
                        $LearningPathHistory,
                    ));
                    return $LearningPathHistory;
                }
                if (isset($inputs['tid'])) {
                    $training = TrainingHistory::find(decrypt_tech($inputs['tid']));
                    $training->update(['status' => 'completed', 'completed_date' => now()]);
                } else {
                    $training = TrainingHistory::create([
                        'form_id' => $inputs['form_id'],
                        'group_id' => -1,
                        'user_id' => \Auth::user()->id,
                        'assigned_date' => now(),
                        'completed_date' => now(),
                        'status' => 'completed',
                    ]);
                }
                if ($training) {
                    $form = $training->form;
                    $group = $training->group;
                    dispatch(new XmeActionLog(
                        auth()->user(),
                        'create',
                        '{user} has been completed training
                        "' . $form->display_title . '" to complete date "
                        ' . $training->completed_date . '" on training history page',
                        $training,
                    ));

                    $this->fireEmailCompleteTraining($group, $form);

                    return $training;
                } else {
                    return false;
                }
            }
        } catch (\Exception | RequestException $e) {
            Log::error('complete_training_service', ['error' => $e->getMessage()]);
            throw $e;
        }
    }


    /** email bell notification for complete training */
    public function fireEmailCompleteTraining($group, $form)
    {
        $loggedUser = Auth::user();
        $users = [];
        if (isImpersonatedSuperAdmin()) {
            $isSupport = true;
        } else {
            $isSupport = false;
        }

        if (User::hasRole(Auth::user(), User::ROLE_BUSINESSADMIN)) {
            $users = User::with('role')->where('company_id', $loggedUser->company_id)
                ->whereHas('role', function ($q) {
                    $q->where('name', User::ROLE_COMPANY);
                })->first();
        } elseif (User::hasRole(Auth::user(), User::ROLE_EMPLOYEE)) {
            $users = User::with('role')->where('company_id', $loggedUser->company_id)
                ->whereHas('role', function ($q) {
                    $q->where('name', User::ROLE_BUSINESSADMIN)->orWhere('name', User::ROLE_COMPANY);
                })->get();
        }
        $data = [
            'loggedUser'    => $loggedUser,
            'isSupport'     => $isSupport,
            'form'          => $form,
            'group'         => $group,
        ];
        dispatch(new SendNotification(
            $users,
            $data,
            'UserTrainingCompleteNotification',
            'sendUserCompletedModuleTrainingNotification',
            null
        ));
    }


    /** Complete learning path module email bell notifications function **/
    public function mailCompletedLearningpathModule($learningPathId, $xmeForm)
    {
        $loggedUser = Auth::user();
        $users      = [];
        if (isImpersonatedSuperAdmin()) {
            $isSupport = true;
        } else {
            $isSupport = false;
        }
        $learningPath = LearningPath::find($learningPathId);
        $users = User::with('role')->where('company_id', $loggedUser->company_id)
            ->whereHas('role', function ($q) {
                $q->where('name', User::ROLE_COMPANY)
                    ->orwhere('name', User::ROLE_BUSINESSADMIN);
            })->orwhere('id', Auth::user()->id)
            ->orwhereHas('role', function ($q) {
                $q->where('name', User::ROLE_SUPERADMIN);
            })->get();
        $data = [
            'loggedUser'    => $loggedUser,
            'isSupport'     => $isSupport,
            'learningPath'  => $learningPath,
            'xmeForm'       => $xmeForm,
        ];
        dispatch(new SendNotification(
            $users,
            $data,
            'CompletedLearningPathModuleNotification',
            'sendCompletedLearningpathModuleNotification',
            null
        ));
    }

    /** activity logs all filters and search */
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
