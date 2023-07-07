<?php

namespace App\Services;

use App\Jobs\SendNotification;
use Illuminate\Support\Facades\DB;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\LearningPath;
use App\Models\UserLearningPath;
use App\Models\LearningPathForm;
use App\Models\Form;
use App\Models\User;
use App\Models\LearningPathHistory;
use App\Jobs\XmeActionLog;
use Carbon\Carbon;

class LearningPathService
{
    public function assignLearningModule($inputs, $learningPathId)
    {
        try {
            $loggedUser = Auth::user();
            $isAccess = UserLearningPath::where('user_id', $loggedUser->id)
                ->where('company_id', $loggedUser->company_id)->where('learning_group_id', $learningPathId)->first();
            if (empty($isAccess)) {
                abort(404);
            }

            // assigned modules
            $assinModule = Form::select(
                'forms.*',
                'learning_paths.title',
                'learning_paths.id as learning_group_id',
                'company_forms.archived_at as company_archived_at',
                'learning_path_forms.read_only',
            )->join('learning_path_forms', 'learning_path_forms.form_id', '=', 'forms.id')
                ->join('learning_paths', 'learning_path_forms.learning_group_id', '=', 'learning_paths.id')
                ->join('company_forms', function ($join) {
                    $join->on('company_forms.form_id', '=', 'learning_path_forms.form_id')
                        ->where('company_forms.company_id', Auth::user()->company_id);
                })
                ->where('learning_path_forms.learning_group_id', $learningPathId)
                ->whereNull('company_forms.archived_at')
                ->whereNull('learning_path_forms.archived_at')
                ->whereNull('company_forms.archived_at')
                ->whereNull('forms.archived_at')
                ->whereNotExists(function ($query) use ($loggedUser) {
                    $query->select('*')->from('learning_path_histories')
                        ->whereRaw('learning_path_histories.form_id=forms.id')
                        ->whereRaw('learning_path_histories.learning_path_id=learning_paths.id')
                        ->where('learning_path_histories.user_id', $loggedUser->id);
                });

            $data = [];
            $text = '';
            if (isset($inputs['search'])) {
                $search =  trim($inputs['search']);
                $assinModule = $assinModule->where(function ($query) use ($search) {
                    $query->where('typeform_id', 'LIKE', "%{$search}%")
                        ->orWhere('display_title', 'LIKE', "%{$search}%");
                });
                $text = 'searched modules by "' . $inputs['search'] . '"';
            }
            if (isset($inputs['assignStatus']) && $inputs['assignStatus'] == 1) {
                $assinModule = $assinModule->where('forms.status', 1);
                $data[] = 'status "Active" ';
            } elseif (isset($inputs['assignStatus']) && $inputs['assignStatus'] == 0) {
                $assinModule = $assinModule->where('forms.status', 0);
                $data[] = 'status "InActive"';
            }
            $assinModule = $assinModule->orderBy($inputs['sortField'], $inputs['orderDir']);
            /**activity logs upcoming training */
            $pageName = 'on assign module learning path page.';
            if (!empty($data) || !empty($text)) {
                $this->updateAllFilterLog($text, $data, $pageName);
            }
            return  $assinModule = clone $assinModule->paginate(10);
        } catch (\Exception | RequestException $e) {
            Log::error('assign_learning_module_service', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function completedLearningpath($inputs, $learningPathId)
    {
        try {
            $data = [];
            $loggedUser = Auth::user();
            $completedModules = Form::withTrashed()->select(
                'forms.*',
                'form_history.learning_path_id as learning_group_id',
                'company_forms.archived_at as company_archived_at',
                DB::raw('MAX(form_history.created_at) as learning_created_at'),
                DB::raw('COUNT(form_history.form_id) as counter')
            )->with('attachements')
                ->join('company_forms', function ($join) {
                    $join->on('company_forms.form_id', '=', 'forms.id')
                        ->where('company_forms.company_id', Auth::user()->company_id);
                })
                ->join('learning_path_forms as learningpath', function ($join) use ($learningPathId) {
                    $join->on('learningpath.form_id', '=', 'forms.id')
                        ->where('learningpath.learning_group_id', $learningPathId);
                })
                ->join('learning_path_histories as form_history', function ($join) use ($loggedUser, $learningPathId) {
                    $join->on('form_history.form_id', '=', 'forms.id')
                        ->where('form_history.learning_path_id', $learningPathId)
                        ->where('form_history.user_id', $loggedUser->id);
                });
            $completedModules = $completedModules->where('form_history.user_id', Auth::user()->id)
                ->where('form_history.learning_path_id', $learningPathId)
                ->where('form_history.company_id', Auth::user()->company_id);
            $text = '';
            if (isset($inputs['search'])) {
                $search =  trim($inputs['search']);
                $completedModules = $completedModules->where(function ($query) use ($search) {
                    $query->where('typeform_id', 'LIKE', "%{$search}%")
                        ->orWhere('display_title', 'LIKE', "%{$search}%");
                });
                /**action log complete learning path search* */
                $text = 'searched modules by "' . $inputs['search'] . '"';
                // $this->updatedLearningPathSearch($search, $inputs['actionType']);
            }
            $completedModules = $completedModules
                ->groupBy('form_history.form_id')
                ->orderBy($inputs['sortField'], $inputs['orderDir']);
            /**activity logs upcoming training */
            $pageName = ' in the completed section of learning path training page.';
            if (!empty($text)) {
                $this->updateAllFilterLog($text, $data, $pageName);
            }
            return $completedModules->paginate(10);
        } catch (\Exception | RequestException $e) {
            Log::error('complete_modules_in_learning_path_service', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function list($inputs)
    {
        try {
            $data = [];
            $currentUser = Auth::user();
            if (
                User::hasRole(Auth::user(), User::ROLE_BUSINESSADMIN) ||
                User::hasRole(Auth::user(), User::ROLE_COMPANY)
            ) {
                $learningPaths = LearningPath::withCount('members', 'forms')
                    ->where('company_id', $currentUser->company_id);
                if ($inputs['isArchived']) {
                    $learningPaths = $learningPaths->onlyTrashed();
                    $pageName = 'on archive learning path page';
                }
                $pageName = 'on learning path page';
                $text = '';
                if (isset($inputs['search'])) {
                    $search =  trim($inputs['search']);
                    $learningPaths = $learningPaths->where('title', 'LIKE', "%{$search}%");
                    $text = 'searched by learning path "' . $inputs['search'] . '"';
                    // $searchName = 'searched by learning path';
                    // $this->updateSearch($searchName, $search, $pageName);
                }
                $learningPaths = $learningPaths->orderBy($inputs['sortField'], $inputs['orderDir'])->paginate(10);
                /**activity logs */
                if ( !empty($text)) {
                    $this->updateAllFilterLog($text, $data , $pageName);
                }
                return $learningPaths;
            }
        } catch (\Exception | RequestException $e) {
            Log::error('learning_path_list_service', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function createLearningPath($inputs)
    {
        try {
            (!empty($inputs['description'])) ? $description = $inputs['description'] : $description = "";
            $learningPath = LearningPath::create([
                'title' => $inputs['title'],
                'description' => $description, 'company_id' => Auth::user()->company_id
            ]);

            dispatch(new XmeActionLog(
                auth()->user(),
                'store',
                '{user} created "' . $inputs['title'] . '" learning path',
                $learningPath,
            ));

            $this->mailLearningPathCreation($learningPath);
            return $learningPath;
        } catch (\Exception | RequestException $e) {
            Log::error('learning_path_create_service', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function edit($learningPathId)
    {
        try {
            $learningPath =  LearningPath::withTrashed()->find($learningPathId);
            return $learningPath;
        } catch (\Exception | RequestException $e) {
            Log::error('edit_learning_path_service', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function getLearningPathMembers($inputs, $learningPathId)
    {
        try {
            $data = [];
            $members = UserLearningPath::select('user_learning_paths.*', 'users.archived_at')
                ->join('users', 'users.id', '=', 'user_learning_paths.user_id')
                ->where('learning_group_id', $learningPathId);
            $text = '';
            if (isset($inputs['search'])) {
                $search = trim($inputs['search']);
                $members = $members->where(function ($query) use ($search) {
                    $query->where('users.name', 'LIKE', "%{$search}%")
                        ->orWhere('users.email', 'LIKE', "%{$search}%");
                });
                $text = 'searched by learning path member"' . $inputs['search'] . '"';
                // $searchName = 'searched by learning path member';
                $pageName   = 'on learning path group page';
                // $this->updateSearch($searchName, $search, $pageName);
            }
            $members = $members->orderBy($inputs['sortField'], $inputs['orderDir'])->paginate(10);
            /**activity logs */
            $pageName   = 'on learning path group page';
            if (!empty($text)) {
               $this->updateAllFilterLog($text, $data, $pageName);
           }
            return $members;
        } catch (\Exception | RequestException $e) {
            Log::error('learning_path_member_list_service', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function getLearningPathModules($inputs, $learningPathId)
    {
        try {
            $data = [];
            $forms =  LearningPathForm::select(
                'learning_path_forms.*',
                'company_forms.archived_at as company_archived_at',
                'forms.display_title',
                'forms.archived_at as archived_at',
            )->join('forms', 'forms.id', '=', 'learning_path_forms.form_id')
                ->join('company_forms', function ($join) {
                    $join->on('company_forms.form_id', '=', 'learning_path_forms.form_id')
                        ->where('company_forms.company_id', Auth::user()->company_id)
                        ->where('company_forms.archived_at', null)
                        ->where('company_forms.module_status', 1);
                });
            $forms = $forms->where('learning_group_id', $learningPathId)
                ->whereNull('forms.archived_at');
            $text = '';
            if (isset($inputs['search'])) {
                $search = trim($inputs['search']);
                $forms = $forms->where(function ($query) use ($search) {
                    $query->where('forms.display_title', 'LIKE', "%{$search}%");
                });
                $text = 'searched by learning path modules"' . $inputs['search'] . '"';
                // $this->updateSearch($searchName, $search, $pageName);
            }
            $forms = $forms->orderBy($inputs['sortField'], $inputs['orderDir'])->paginate(10);
            /**activity logs */
            $pageName   = 'on learning path group page';
            if (!empty($text)) {
               $this->updateAllFilterLog($text, $data, $pageName);
           }
            return $forms;
        } catch (\Exception | RequestException $e) {
            Log::error('learning_path_modules_list_service', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function learningPathPendingMembers($inputs, $learningpathId)
    {
        try {
            $data = [];
            $users = User::where('company_id', Auth::user()->company_id);
            $users  = $users->whereDoesntHave('learningpath', function ($q) use ($learningpathId) {
                $q->where('learning_group_id', $learningpathId);
            });
            $text = '';
            if (isset($inputs['search'])) {
                $search = $inputs['search'];
                $users = $users->where(function ($query) use ($search) {
                    $query->where('users.name', 'LIKE', "%{$search}%");
                });
                $text = 'searched pending members by "' . $search . '"';
                // $pageName = 'on learning path page.';
                // $searchName = 'searched pending members by';
                // $this->updateSearch($searchName, $search, $pageName);
            }
            /**activity logs */
            $pageName = 'in members section of learning path page.';
            if (!empty($text)) {
                $this->updateAllFilterLog($text, $data, $pageName);
            }
            return $users->get();
        } catch (\Exception | RequestException $e) {
            DB::rollBack();
            Log::error('learning_path_pending_members_service', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function learningPathPendingModules($inputs, $learningpathId)
    {
        try {
            $data = [];
            $forms = Form::with('form_company')->whereHas('form_company', function ($q) {
                $q->where('company_id', Auth::user()->company_id)->where('module_status', 1);
            });
            $forms = $forms->whereDoesntHave('learningpath', function ($q) use ($learningpathId) {
                $q->where('learning_group_id', $learningpathId);
            });
            if (isset($inputs['type'])) {
                $type = $inputs['type'];
                $forms = $forms->with('directoryModules');
                $forms = $forms->whereHas('directoryModules', function ($query) use ($type) {
                    $query->where('directory_id', '=', $type);
                });
            }
            $text = '';
            if (isset($inputs['search'])) {
                $search = $inputs['search'];
                $forms = $forms->where(function ($query) use ($search) {
                    $query->where('forms.display_title', 'LIKE', "%{$search}%");
                });
                $text = 'searched pending modules by "' . $search . '"';

                // $pageName = 'on learning path page.';
                // $searchName = 'searched pending modules by';
                // $this->updateSearch($searchName, $search, $pageName);
            }
            if (isset($inputs['is_library']) && $inputs['is_library'] == 1) {
                $forms = $forms->where('xme_area', 0);
                // $pageName = 'on  learning path  pending modules page';
                // $this->updatedFilterType($inputs['is_library'], $pageName);
                $data[] = 'type " Library "';
            } elseif (isset($inputs['is_library']) && $inputs['is_library'] == 0) {
                $forms = $forms->where('xme_area', 1);
                $data[] = 'type " Xme area "';
                // $pageName = 'on  learning path  pending modules page';
                // $this->updatedFilterType($inputs['is_library'], $pageName);
            }
             /**activity logs */
             $pageName = 'in module section of learning path page.';

             if (!empty($text)) {
                $this->updateAllFilterLog($text, $data, $pageName);
            }
            return $forms->get();
        } catch (\Exception | RequestException $e) {
            DB::rollBack();
            Log::error('learning_path_pending_modules_service', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function addMemberToLearningpath($inputs, $learningPathId)
    {
        try {
            $learningPathId = $learningPathId;
            DB::beginTransaction();
            $member = '';
            $isExistMember = UserLearningPath::withTrashed()->where('user_id', $inputs['id'])
                ->where('learning_group_id', $learningPathId)
                ->where('company_id', Auth::user()->company_id)->first();
            $learningPath = LearningPath::find($learningPathId);
            if (!$isExistMember) {
                $member = new UserLearningPath();
                $member->user_id = $inputs['id'];
                $member->learning_group_id = $learningPathId;
                $member->company_id = Auth::user()->company_id;
                if ($member->save()) {
                    /**add member action logs */
                    $actionTitle = 'add member';
                    $this->updateMemberLog(
                        $actionTitle,
                        $member->user->name,
                        $learningPath->title,
                        $learningPath
                    );
                }
            } elseif ($isExistMember && !empty($isExistMember->archived_at)) {
                $isExistMember->archived_at  = null;
                $isExistMember->save();
                $member = $isExistMember;
                /**add exist member action logs */
                $actionTitle = 'add exist member';
                $this->updateMemberLog(
                    $actionTitle,
                    $member->user->name,
                    $learningPath->title,
                    $learningPath
                );
            }
            $this->mailAddMemberToLearningPath($learningPath, $member);
            DB::commit();
            return $member;
        } catch (\Exception | RequestException $e) {
            DB::rollBack();
            Log::error('add_member_to_learning_path_service', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function addModuleToLearningPath($inputs, $learningPathId)
    {
        try {
            DB::beginTransaction();
            $learningPathForm = LearningPathForm::withTrashed()->where('form_id', $inputs['form_id'])
                ->where('learning_group_id', $learningPathId)->where('company_id', Auth::user()->company_id)->first();
            $learningPath = LearningPath::find($learningPathId);
            if (!$learningPathForm) {
                $learningPathForm = new LearningPathForm();
                $learningPathForm->form_id = $inputs['form_id'];
                $learningPathForm->company_form_id = $inputs['company_form_id'];
                $learningPathForm->learning_group_id = $learningPathId;
                $learningPathForm->company_id = Auth::user()->company_id;
                if ($learningPathForm->save()) {
                    /**add module action log */
                    $actionTitle = 'add module';
                    $this->updateMemberLog(
                        $actionTitle,
                        $learningPathForm->form->display_title,
                        $learningPath->title,
                        $learningPath
                    );
                }
            } elseif ($learningPathForm && !empty($learningPathForm->archived_at)) {
                $learningPathForm->archived_at  = null;
                $learningPathForm->save();
                /**add exist module action log **/
                $actionTitle = 'add exist module';
                $this->updateMemberLog(
                    $actionTitle,
                    $learningPathForm->form->display_title,
                    $learningPath->title,
                    $learningPath
                );
            }
            $this->mailAddModulesToLearningPath($learningPath, $learningPathForm);
            DB::commit();
            $learningPathForm;
        } catch (\Exception | RequestException $e) {
            DB::rollBack();
            Log::error('add_modules_to_learning_path_service', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function lockLearningPath($learningPathId)
    {
        try {
            DB::beginTransaction();
            $learningPath = LearningPath::where("id", $learningPathId)
                ->where('company_id', Auth::user()->company_id)->first();
            $learningPath->is_lock = 1;
            $learningPath->save();
            $this->mailLockLearningPath($learningPath);

            /** lock learning path activity logs */
            dispatch(new XmeActionLog(
                auth()->user(),
                'store',
                '{user} lock "' . $learningPath->title . ' learning Path" on learning path page.',
                null,
            ));

            DB::commit();
            return $learningPath;
        } catch (\Exception | RequestException $e) {
            DB::rollBack();
            Log::error('lock_learning_path_service', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function updateScheduleDate($inputs, $learningPathId)
    {
        try {
            DB::beginTransaction();
            $scheduleDate = Carbon::parse($inputs['date'])->format('Y-m-d H:i:s');
            $isLearningPath = LearningPath::find($learningPathId);
            if (!($isLearningPath)) {
                return false;
            }
            if (isset($inputs['user']) && !empty($inputs['user'])) {
                $learningUser = UserLearningPath::with('user')->where('user_id', $inputs['user'])
                    ->where('learning_group_id', $learningPathId)->first();
                $learningUser->learningpath_schduled_at = $scheduleDate;
                $learningUser->save();
                $this->updateSheduleDateUser(
                    $scheduleDate,
                    $learningUser->user->name,
                    $isLearningPath->title,
                    $isLearningPath
                );
            } else {
                $learningUser = UserLearningPath::where('learning_group_id', $learningPathId)->get();
                foreach ($learningUser as $user) {
                    $user->learningpath_schduled_at = $scheduleDate;
                    $user->save();
                    $this->updateSheduleDateAllUser($scheduleDate, $isLearningPath->title, $isLearningPath);
                }
            }
            DB::commit();
            return true;
        } catch (\Exception | RequestException $e) {
            DB::rollBack();
            Log::error('update_scheduleDate_service', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function duplicateLearningPath($inputs, $learningPathId)
    {
        try {
            DB::beginTransaction();
            $xmeLearningpath = LearningPath::find($learningPathId);
            $description = $xmeLearningpath->description;
            $copiedLearningPath = LearningPath::create([
                'description' => $description, 'title' => $inputs['title'],
                'company_id' => Auth::user()->company_id
            ]);

            dispatch(new XmeActionLog(
                auth()->user(),
                'copied',
                '{user} create "' . $copiedLearningPath->title
                    . '" learning path to copy the "' .
                    $xmeLearningpath->title . '" learning path',
                $xmeLearningpath,
            ));

            $learningModules = LearningPathForm::where('learning_group_id', $learningPathId)
                ->where('company_id', Auth::user()->company_id)->get();
            foreach ($learningModules as $module) {
                LearningPathForm::create([
                    'form_id' => $module->form_id,
                    'company_id' => $module->company_id,
                    'learning_group_id' => $copiedLearningPath->id,
                    'display_order'     => $module->display_order
                ]);
            }
            $learningUsers = UserLearningPath::where('learning_group_id', $learningPathId)
                ->where('company_id', Auth::user()->company_id)->get();

            foreach ($learningUsers as $user) {
                UserLearningPath::create([
                    'user_id' => $user->user_id,
                    'company_id' => $user->company_id,
                    'learning_group_id' => $copiedLearningPath->id,
                ]);
            }
            $this->mailDuplicateLearningPath($xmeLearningpath);


            DB::commit();
            return $copiedLearningPath;
        } catch (\Exception | RequestException $e) {
            DB::rollBack();
            Log::error('duplicate_learning_path_service', ['error' => $e->getMessage()]);
            throw $e;
        }
    }
    /** for soft delete learning path action */
    public function learningPathAction($inputs)
    {
        try {
            DB::beginTransaction();
            $msg = '';
            if ($inputs['learningpath_id'] != "") {
                if ($inputs['actionType'] == 'Archive') {
                    $learningpaths = LearningPath::find($inputs['learningpath_id']);
                    $learningpaths->delete();
                    $msg = trans("messages.learning_path_archived_successfully");
                    $this->setLearningpathArchiveLogs($inputs['actionType'], $learningpaths->title, false, $learningpaths);
                    $this->mailArchiveLearningPath($learningpaths);
                } elseif ($inputs['actionType'] == 'UnArchive') {
                    $learningpaths = LearningPath::withTrashed()->find($inputs['learningpath_id']);
                    $learningpaths->restore();
                    $msg = trans("messages.learning_path_unarchive_successfully");
                    $this->setLearningpathArchiveLogs($inputs['actionType'], $learningpaths->title, true, $learningpaths);
                    $this->mailUnArchiveLearningPath($learningpaths);
                }
            }
            DB::commit();
            return [$learningpaths, $msg];
        } catch (\Exception | RequestException $e) {
            DB::rollBack();
            Log::error('learning_path_action_service', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    /** For Multiple Archive and Multiple UnArchive Action */
    public function multipleAction($inputs)
    {
        try {
            DB::beginTransaction();
            $msg = '';
            $learningpaths = explode(",", $inputs['multipleLearningPath']);
            if ($inputs['actionType'] == 'Archive') {
                foreach ($learningpaths as $learningPathId) {
                    $learningPath =   LearningPath::find($learningPathId);
                    $learningPath->delete();
                    $this->setLearningpathArchiveLogs(
                        $inputs['actionType'],
                        $learningPath->title,
                        false,
                        $learningPath
                    );
                }
                $msg = trans("messages.multiple_learning_path_archived_successfully");
            } elseif ($inputs['actionType'] == 'UnArchive') {
                foreach ($learningpaths as $learningPathId) {
                    $learningPath =  LearningPath::withTrashed()->find($learningPathId);
                    $learningPath->restore();
                    $this->setLearningpathArchiveLogs($inputs['actionType'], $learningPath->title, true, $learningPath);
                }
                $msg = trans("messages.multiple_learning_path_unarchive_successfully");
            }
            DB::commit();
            return [true, $msg];
        } catch (\Exception | RequestException $e) {
            DB::rollBack();
            Log::error('multiple_learning_path_action_service', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function updateLearningModuleStatus($inputs, $learningpathId)
    {
        try {
            DB::beginTransaction();
            $module = LearningPathForm::with('form')->where('form_id', $inputs['form_id'])
                ->where('learning_group_id', $learningpathId)->first();
            if ($inputs['actionType'] == 'read') {
                $module->read_only = Carbon::now();
                $msg = trans("messages.read_only_module_successfully");
                $this->updateLearningPathStatusLog('read only', $module->form->display_title);
            } else {
                $module->read_only = null;
                $msg = trans("messages.update_the_module_unread_only");
                $this->updateLearningPathStatusLog('unread', $module->form->display_title);
            }

            $module->save();
            DB::commit();
            return [$module, $msg];
        } catch (\Exception | RequestException $e) {
            DB::rollBack();
            Log::error('update_learning_module_status_service', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function removeLearningPathMember($inputs, $learningPathId)
    {
        try {
            DB::beginTransaction();
            $learningPath = LearningPath::find($learningPathId);
            $member = UserLearningPath::with('user')->where('user_id', $inputs['memberId'])
                ->where('learning_group_id', $learningPathId)->first();
            $member->learningpath_schduled_at = null;
            $member->save();
            $member->delete();
            $this->mailRemoveLearningPathMember($learningPath, $member);

            $actionTitle = 'remove member';
            $this->updateMemberLog($actionTitle, $member->user->name, $learningPath->title, $learningPath);

            DB::commit();
            return true;
        } catch (\Exception | RequestException $e) {
            DB::rollBack();
            Log::error('remove_learning_path_member_service', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function removeLearningPathModule($inputs, $learningPathId)
    {
        try {
            DB::beginTransaction();
            $learningPath = LearningPath::find($learningPathId);
            $learningGroup =   LearningPathForm::with('form')->where('form_id', $inputs['formId'])
                ->where('learning_group_id', $learningPathId)->first();
            if ($learningGroup) {
                $this->mailRemoveModulesfromLearningPath($learningPath, $learningGroup);
                $learningGroup->delete();
                /**remove module */
                $actionTitle = 'remove module';
                $this->updateMemberLog(
                    $actionTitle,
                    $learningGroup->form->display_title,
                    $learningPath->title,
                    $learningPath
                );
            }
            DB::commit();
            return true;
        } catch (\Exception | RequestException $e) {
            DB::rollBack();
            Log::error('remove_learning_path_module_service', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function update($learningPathId, $inputs)
    {
        try {
            DB::beginTransaction();
            $learningPath = LearningPath::where('id', $learningPathId)->first();
            $learningPath->update($inputs);
            $this->mailLearningPathUpdation($learningPath);

            dispatch(new XmeActionLog(
                auth()->user(),
                'update',
                '{user} update  "' . $inputs['title'] . '"  learning path',
                $learningPath,
            ));

            DB::commit();
            return $learningPath;
        } catch (\Exception | RequestException $e) {
            DB::rollBack();
            Log::error('update_learning_path_module_service', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function learningPathModuleHistory($inputs, $learningPathId)
    {
        try {

            $learningPathHistory =  LearningPathHistory::select('form.display_title as form_display_title', 'learning_path_histories.*')
                ->join('forms as form', 'form.id', '=', 'learning_path_histories.form_id')
                ->where('learning_path_id', $learningPathId)
                ->where('user_id', auth()->user()->id)
                ->where('form_id', $inputs['form_id'])
                ->orderBy('learning_path_histories.created_at', 'DESC')
                ->get();
            return $learningPathHistory;
        } catch (\Exception | RequestException $e) {
            Log::error('learning_path_module_history_service', ['error' => $e->getMessage()]);
            throw $e;
        }
        # code...
    }

    /*** start logs function */

    /**learning path archive and restore logs **/
    public function setLearningpathArchiveLogs($actionType, $learningPathName, $isArchived, $learningPath)
    {

        if ($isArchived == 1) {
            dispatch(new XmeActionLog(
                auth()->user(),
                'UnArchive',
                '{user} ' . $actionType . ' the "' . $learningPathName . '" learning path on archive learning paths page',
                $learningPath,
            ));
        } else {
            dispatch(new XmeActionLog(
                auth()->user(),
                'Archive',
                '{user} ' . $actionType . ' the "' . $learningPathName . '" learning path on  learning paths page',
                $learningPath,
            ));
        }
    }

    /***update the learning path status log **/
    public function updateLearningPathStatusLog($actionType, $module)
    {
        dispatch(new XmeActionLog(
            auth()->user(),
            'update',
            '{user} '  . $actionType . ' the "' . $module . '" learning path module.',
            null,
        ));
    }

    // Update Learning Path Module Order Service
    public function updateOrderAction($inputs, $learningPathId)
    {
        try {
            $count = 1;
            foreach ($inputs as $form_id) {
                LearningPathForm::where('form_id', $form_id)->where('learning_group_id', $learningPathId)
                    ->where('company_id', Auth::user()->company_id)
                    ->update(['display_order' => $count]);
                $count++;
            }
            return true;
        } catch (\Exception | RequestException $e) {
            Log::error('update_library_order_service', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    /** learning path create email bell notifications function **/
    public function mailLearningPathCreation($learningPath)
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
            })->where('id', '!=', Auth::user()->id)->get();
        $data = [
            'loggedUser'    => $loggedUser,
            'isSupport'     => $isSupport,
            'learningPath'  => $learningPath,
        ];
        dispatch(new SendNotification(
            $users,
            $data,
            'AddLearningPathNotification',
            'sendAddLearningPathNotification',
            null
        ));
    }

    /** learning path Update email bell notifications function **/
    public function mailLearningPathUpdation($learningPath)
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
            })->where('id', '!=', Auth::user()->id)->get();
        $data = [
            'loggedUser'    => $loggedUser,
            'isSupport'     => $isSupport,
            'learningPath'  => $learningPath,
        ];
        dispatch(new SendNotification(
            $users,
            $data,
            'UpdateLearningPathNotification',
            'sendUpdateLearningPathNotification',
            null
        ));
    }

    /** learning path Lock email bell notifications function **/
    public function mailLockLearningPath($learningPath)
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
            })->where('id', '!=', Auth::user()->id)->get();
        $data = [
            'loggedUser'    => $loggedUser,
            'isSupport'     => $isSupport,
            'learningPath'  => $learningPath,
        ];
        dispatch(new SendNotification(
            $users,
            $data,
            'LockLearningPathNotification',
            'sendLockLearningPathNotification',
            null
        ));
    }

    /** learning path Delete email bell notifications function **/
    public function mailDeleteLearningPath($learningPath)
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
            })->where('id', '!=', Auth::user()->id)->get();
        $data = [
            'loggedUser'    => $loggedUser,
            'isSupport'     => $isSupport,
            'learningPath'  => $learningPath,
        ];
        dispatch(new SendNotification(
            $users,
            $data,
            'DeleteLearningPathNotification',
            'sendDeleteLearningPathNotification',
            null
        ));
    }

    /** add module to learning path email bell notifications function **/
    public function mailAddModulesToLearningPath($learningPath, $xmeForm)
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
                $q->where('name', '!=', User::ROLE_SUPERADMIN);
            })->where('id', '!=', Auth::user()->id)->get();
        $data = [
            'loggedUser'    => $loggedUser,
            'isSupport'     => $isSupport,
            'learningPath'  => $learningPath,
            'xmeForm'       => $xmeForm,
        ];
        dispatch(new SendNotification(
            $users,
            $data,
            'AddModuleToLearningPathNotification',
            'sendAddModuleToLearningPathNotification',
            null
        ));
    }

    /** remove module from learning path email bell notifications function **/
    public function mailRemoveModulesfromLearningPath($learningPath, $xmeForm)
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
                $q->where('name', '!=', User::ROLE_SUPERADMIN);
            })->where('id', '!=', Auth::user()->id)->get();
        $data = [
            'loggedUser'    => $loggedUser,
            'isSupport'     => $isSupport,
            'learningPath'  => $learningPath,
            'xmeForm'       => $xmeForm,
        ];
        dispatch(new SendNotification(
            $users,
            $data,
            'RemoveLearningPathModuleNotification',
            'sendRemoveLearningPathModuleNotification',
            null
        ));
    }

    /** add member to learning path email bell notifications function **/
    public function mailAddMemberToLearningPath($learningPath, $member)
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
                    ->orwhere('name', User::ROLE_BUSINESSADMIN);
            })->where('id', '!=', Auth::user()->id)->orWhere('id', $member->user_id)->get();
        $data = [
            'loggedUser'    => $loggedUser,
            'isSupport'     => $isSupport,
            'learningPath'  => $learningPath,
            'member'        => $member,
        ];
        dispatch(new SendNotification(
            $users,
            $data,
            'AddMemberToLearningPathNotification',
            'sendAddMemberToLearningPathNotification',
            null
        ));
    }

    /** remove member from learning path email bell notifications function **/
    public function mailRemoveLearningPathMember($learningPath, $member)
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
                    ->orwhere('name', User::ROLE_BUSINESSADMIN);
            })->where('id', '!=', Auth::user()->id)->orWhere('id', $member->user_id)->get();
        $data = [
            'loggedUser'    => $loggedUser,
            'isSupport'     => $isSupport,
            'learningPath'  => $learningPath,
            'member'        => $member,
        ];
        dispatch(new SendNotification(
            $users,
            $data,
            'RemoveLearningPathMemberNotification',
            'sendRemoveLearningPathMemberNotification',
            null
        ));
    }

    /** Archive learning path email bell notifications function **/
    public function mailArchiveLearningPath($learningPath)
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
            'learningPath'  => $learningPath,
        ];
        dispatch(new SendNotification(
            $users,
            $data,
            'ArchiveLearningPathNotification',
            'sendArchiveLearningPathNotification',
            null
        ));
    }

    /** unrchive learning path email bell notifications function **/
    public function mailUnArchiveLearningPath($learningPath)
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
            'learningPath'  => $learningPath,
        ];
        dispatch(new SendNotification(
            $users,
            $data,
            'UnarchiveLearningPathNotification',
            'sendUnarchiveLearningPathNotification',
            null
        ));
    }

    /** restore learning path email bell notifications function **/
    public function mailRestoreLearningPath($learningPath)
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
            'learningPath'  => $learningPath,
        ];
        dispatch(new SendNotification(
            $users,
            $data,
            'RestoreLearningPathNotification',
            'sendRestoreLearningPathNotification',
            null
        ));
    }

    /** duplicate learning path email bell notifications function **/
    public function mailDuplicateLearningPath($learningPath)
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
            'learningPath'  => $learningPath,
        ];
        dispatch(new SendNotification(
            $users,
            $data,
            'DuplicateLearningPathNotification',
            'sendDuplicateLearningPathNotification',
            null
        ));
    }

    /**action logs search  */
    public function updateSearch($searchName, $search, $pageName)
    {
        if ($search != '') {
            dispatch(
                new XmeActionLog(
                    auth()->user(),
                    'search',
                    '{user} ' . $searchName . ' "' . $search . '" ' . $pageName . '',
                    null,
                )
            );
        }
    }

    /**action logs filter status**/
    public function updatedFilterStatus($status, $pageName)
    {
        $statusName =  $status == 1 ? 'Active' : 'InActive';
        if ($status != '') {
            dispatch(new XmeActionLog(
                auth()->user(),
                'filter',
                '{user} filtered  status by "' . $statusName . '"  ' . $pageName . '',
                null,
            ));
        }
    }


    /**action logs update learningPathSearch**/
    public function updatedLearningPathSearch($search, $isArchived)
    {
        if ($search != '') {
            if ($isArchived == 1) {
                dispatch(new XmeActionLog(
                    auth()->user(),
                    'search',
                    '{user} searched learningpath by "' . $search . '" on learningpath archive page',
                    null,
                ));
            } else {
                dispatch(new XmeActionLog(
                    auth()->user(),
                    'search',
                    '{user} searched learningpath by "' . $search . '" on learningpath page',
                    null,
                ));
            }
        }
    }

    /**add member and exist add member  remove member action logs**/
    public function updateMemberLog($actionTitle, $data, $learningPathName, $learningPath)
    {
        dispatch(new XmeActionLog(
            auth()->user(),
            'store',
            '{user} ' . $actionTitle . ' "' . $data .
                '" to "' . $learningPathName . ' learning path" on learning path page',
            $learningPath,
        ));
    }

    /**update action log shedule all user  */

    public function updateSheduleDateAllUser($scheduleDate, $learningPathName, $learningPath)
    {
        dispatch(new XmeActionLog(
            auth()->user(),
            'update',
            '{user} update shedule date "' . $scheduleDate .
                '" to all members to "' . $learningPathName .
                '" learning path page.',
            $learningPath,
        ));
    }

    /**update shedule member action logs */
    public function updateSheduleDateUser($scheduleDate, $member, $learningPathName, $learningPath)
    {
        dispatch(new XmeActionLog(
            auth()->user(),
            'update',
            '{user} update shedule date "' . $scheduleDate . '" to  member "
            ' . $member . '" to  "' . $learningPathName . '" learning path page.',
            $learningPath,
        ));
    }



    /*** activity logs change filter for type  history page */
    public function updatedFilterType($type, $pageName)
    {
        if ($type == 0) {
            $typeName = 'XmeArea';
        } else {
            $typeName = 'Library';
        }
        if ($type != '') {
            dispatch(new XmeActionLog(
                auth()->user(),
                'filter',
                '{user} change filtered  type by "' . $typeName . '" ' . $pageName . '',
                null,
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
