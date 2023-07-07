<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\Form;
use App\Jobs\XmeActionLog;
use App\Models\Group;
use App\Models\Company;
use App\Models\UserGroup;
use App\Models\GroupForm;
use App\Models\TrainingHistory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Notifications\AddedGroupNotification;
use App\Helpers\NotificationHelper;
use App\Jobs\SendNotification;
use App\Notifications\UserAddedToGroupNotification;
use App\Notifications\AssestAddedToGroupNotification;
use App\Notifications\DeleteMemberNotification;
use Carbon\Carbon;

class GroupService
{
    public function list($inputs)
    {
        try {
            $data = [];
            $groups =  Group::scpCompany()->with(['forms' => function ($q) {
                $q->whereHas('comapny_form');
            }])->withCount('members', 'forms');
            // if modules are archeived or not
            if ($inputs['isArchived']) {
                $groups = $groups->onlyTrashed();
            }
            $text = '';
            if (isset($inputs['search'])) {
                $search = trim($inputs['search']);
                $groups = $groups->where(function ($query) use ($search) {
                    $query->where('name', 'LIKE', "%{$search}%");
                });
                $text = 'searched groups by "' . $search . '"';
                /**logs */
                // $this->updatedSearch($inputs['isArchived'], $search);
            }
            $groups = $groups->orderBy($inputs['sortField'], $inputs['orderDir'])->paginate(25);
            /**activity logs */
            $pageName = $inputs['isArchived'] == 1 ? 'on archive groups page' : 'on groups page';
            if (!empty($text)) {
               $this->updateAllFilterLog($text, $data, $pageName);
           }
           return $groups;
        } catch (\Exception | RequestException $e) {
            Log::error('group_list_service', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function updatedSearch($isArchived, $search)
    {
        if ($isArchived) {

            dispatch(new XmeActionLog(
                auth()->user(),
                'search',
                '{user} searched groups by "' . $search . '" on archive group   page',
                null,
            ));
        } else {
            dispatch(new XmeActionLog(
                auth()->user(),
                'search',
                '{user} searched groups by "' . $search . '" on group page',
                null,
            ));
        }
    }

    public function create($inputs)
    {
        try {
            DB::beginTransaction();
            $group = new Group();
            $group->name = $inputs['name'];
            $group->company_id = Auth::user()->company_id;
            $assignedRole = [];
            if ($inputs['is_assigned_default'] == 'true' && empty($inputs['default_assignned_roles'])) {
                $assignedRole[] = 5;
                $assignedRole[] = 4;
                $assignedRole[] = 7;
                $group->default_assignned_roles = implode(',', $assignedRole);
            } elseif ($inputs['is_assigned_default'] == 'true' && !empty($inputs['default_assignned_roles'])) {
                $group->default_assignned_roles = implode(',', $inputs['default_assignned_roles']);
            }
            $group->is_assigned_default = ($inputs['is_assigned_default']== 'true') ? 1 : 0;
            if ($group->save()) {
                /**logs */

                dispatch(new XmeActionLog(
                    auth()->user(),
                    'store',
                    '{user} created group "{model}"',
                    $group,
                ));

                DB::commit();
                // $this->fireGroupCreationMails($group);
                return $group;
            }
        } catch (\Exception | RequestException $e) {
            DB::rollBack();
            Log::error('group_create_service', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function edit($groupId)
    {
        try {
            $xmeGroup =  Group::find($groupId);
            return $xmeGroup;
        } catch (\Exception | RequestException $e) {
            Log::error('group_edit_service', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function update($inputs, $groupId)
    {
        try {
            DB::beginTransaction();
            $group = Group::find($groupId);
            $assignedRole = [];
            if ($inputs['is_assigned_default'] && implode('', $inputs['default_assignned_roles'])==null) {
                $assignedRole[] = 5;
                $assignedRole[] = 4;
                $assignedRole[] = 7;
                $group->default_assignned_roles = implode(',', $assignedRole);
                $group->is_assigned_default  = 1;
            } elseif ($inputs['is_assigned_default']  && !empty($inputs['default_assignned_roles'])) {
                $group->default_assignned_roles = implode(',', $inputs['default_assignned_roles']);
                $group->is_assigned_default  = 1;
            } elseif (!$inputs['is_assigned_default']) {
                $group->is_assigned_default  = 0;
                $group->default_assignned_roles = null;
            }
            $group->name = $inputs['name'];
            $group->save();
            /**logs */

            dispatch(new XmeActionLog(
                auth()->user(),
                'update',
                '{user} updated ' . $inputs['name'] . ' group ',
                null
            ));

            DB::commit();
            return $group;
        } catch (\Exception | RequestException $e) {
            DB::rollBack();
            Log::error('group_update_service', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function groupMembers($inputs, $groupId)
    {
        try {
            $data = [];
            $text = '';
            $members = UserGroup::has('user')->with('user')
                ->where('group_id', $groupId);
            if (isset($inputs['search'])) {
                $search = trim($inputs['search']);
                $members = $members->whereHas('user',function ($query) use ($search) {
                    $query->where('name', 'LIKE', "%{$search}%")
                        ->orWhere('email', 'LIKE', "%{$search}%");
                });
                $text = 'searched members by "' . $search . '"';
            }
            $members = $members->orderBy($inputs['sortField'], $inputs['orderDir'])->paginate(25);
            /**activity logs */
            $pageName = 'in members section of group page.';
            if (!empty($text)) {
               $this->updateAllFilterLog($text, $data, $pageName);
           }
            return $members;
        } catch (\Exception | RequestException $e) {
            Log::error('group_members_service', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function groupModules($inputs, $groupId)
    {
        try {
            $data = [];
            $forms =  GroupForm::select(
                'group_forms.*',
                'forms.display_title',
                'forms.archived_at as archived_at',
                'company_forms.archived_at as company_archived_at'
            )
                ->join('groups', 'groups.id', '=', 'group_forms.group_id')
                ->join('forms', 'forms.id', '=', 'group_forms.form_id')
                ->join('company_forms', function ($join) {
                    $join->on('company_forms.form_id', '=', 'group_forms.form_id')
                        ->on('company_forms.company_id', '=', 'groups.company_id')
                        ->whereNull('company_forms.archived_at')->where('company_forms.module_status', 1);
                })
                ->where('group_id', $groupId)
                ->whereNull('forms.archived_at');
            $text = '';
            if (isset($inputs['search'])) {
                $search = trim($inputs['search']);
                $forms = $forms->where(function ($query) use ($search) {
                    $query->where('forms.display_title', 'LIKE', "%{$search}%");
                    // if ($search != '') {
                    //     //search logs for subject

                    //     dispatch(new XmeActionLog(
                    //         auth()->user(),
                    //         'search',
                    //         '{user} searched modules "' . $search . '"  in group module page',
                    //         null,
                    //     ));
                    // }
                });
                $text = 'searched modules by "' .$search . '"';
            }
            $forms = $forms->orderBy($inputs['sortField'], $inputs['orderDir'])->paginate(25, ['*'], 'formsPage');
            /**activity logs */
            $pageName = ' in module section of group page';
            if (!empty($text)) {
               $this->updateAllFilterLog($text, $data, $pageName);
           }
            return $forms;
        } catch (\Exception | RequestException $e) {
            Log::error('group_modules_service', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function fireGroupCreationMails($group)
    {
        $loggedUser = Auth::user();
        $users = [];
        if (isImpersonatedSuperAdmin()) {
            $isSupport = true;
        } else {
            $isSupport = false;
        }
        $company = Company::find($group->company_id);
        $users   = User::with('role')->where('company_id', $loggedUser->company_id)
            ->whereHas('role', function ($q) {
                $q->where('name', '!=', User::ROLE_EMPLOYEE);
            })->where('id', '!=', Auth::user()->id)->get();
        $data = [
            'group'     => $group,
            'company'   => $company,
            'isSupport' => $isSupport,
            'loggedUser' => $loggedUser
        ];

        dispatch(new SendNotification(
            $users,
            $data,
            'AddedGroupNotification',
            'sendNewAddGroupNotification',
            null
        ));
    }

    public function groupPendingMembers($inputs, $groupId)
    {
        try {
            $data = [];
            $users = User::where('company_id', Auth::user()->company_id);
            $users  = $users->whereDoesntHave('groups', function ($q) use ($groupId) {
                $q->where('group_id', $groupId);
            });
            $text = '';
            if (isset($inputs['search'])) {
                $search = $inputs['search'];
                $users = $users->where(function ($query) use ($search) {
                    $query->where('users.name', 'LIKE', "%{$search}%");
                });
                $text = 'searched pending members by "' . $search . '"';
                // if ($search != '') {
                //     //search logs for subject
                //     dispatch(new XmeActionLog(
                //         auth()->user(),
                //         'search',
                //         '{user} searched pending members by  "' . $search . ' "  in  group  page',
                //         null,
                //     ));
                // }
            }
            /**activity logs */
            $pageName = 'in members section of group page.';
            if (!empty($data)||!empty($text)) {
               $this->updateAllFilterLog($text, $data, $pageName);
           }
            return $users->get();
        } catch (\Exception | RequestException $e) {
            Log::error('group_pending_members_service', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function groupPendingModules($inputs, $groupId)
    {
        try {
            $data = [];
            $forms = Form::with('form_company')->whereHas('form_company', function ($q) {
                $q->where('company_id', Auth::user()->company_id)->where('module_status', 1);
            });
            $forms = $forms->whereDoesntHave('groups', function ($q) use ($groupId) {
                $q->where('group_id', $groupId);
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
                // if ($search != '') {
                //     //search logs for subject
                //     dispatch(new XmeActionLog(
                //         auth()->user(),
                //         'search',
                //         '{user} searched pending modules by  "' . $search . ' "  in  group  page',
                //         null,
                //     ));
                // }
            }

            if (isset($inputs['is_library']) && $inputs['is_library'] == 1) {
                $forms = $forms->where('xme_area', 0);
                // $pageName = 'on pending modules group  pages';
                // $this->updatedFilterType($inputs['is_library'], $pageName);
                $data[] = 'type " Library "';
            } elseif (isset($inputs['is_library']) && $inputs['is_library'] == 0) {
                $forms = $forms->where('xme_area', 1);
                // $pageName = 'on pending modules group  pages';
                // $this->updatedFilterType($inputs['is_library'], $pageName);
                $data[] = 'type " Xme area "';
            }
            /**activity logs */
            $pageName = 'in module section of group page.';

            if (!empty($text)) {
               $this->updateAllFilterLog($text, $data, $pageName);
           }
            return $forms->get();
        } catch (\Exception | RequestException $e) {
            Log::error('group_pending_modules_service', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function addMemberToGroup($inputs, $groupId)
    {
        try {
            DB::beginTransaction();
            $member = UserGroup::with('user', 'actualGroup')->withTrashed()
                ->where('user_id', $inputs['user_id'])->where('group_id', $groupId)->first();
            if (!$member) {
                $member = new UserGroup();
                $member->user_id = $inputs['user_id'];
                $member->group_id = $groupId;
                $member->save();
            } elseif ($member && !empty($member->archived_at)) {
                $member->archived_at  = null;
                $member->save();
            }

            $user = User::find($inputs['user_id']);
            $group = Group::find($groupId);
            $todayDate = \Carbon\Carbon::now();
            $group = Group::find($groupId);
            if (!empty($group->forms) && count($group->forms) > 0) {
                foreach ($group->forms as $groupform) {
                    TrainingHistory::where('form_id', $groupform->form_id)
                        ->where('group_id', $member->group_id)
                        ->where('user_id', $user->id)
                        ->where('status', 'pending')
                        ->update(['status' => 'skipped']);
                    TrainingHistory::create([
                        'form_id' => $groupform->form_id,
                        'group_id' => $member->group_id,
                        'user_id' => $member->user_id,
                        'status' => 'pending',
                        'assigned_date' => $todayDate->format('Y-m-d H:i:s'),
                    ]);
                }
            }
            $this->fireAddMemberToGroupEmail($group, $user, $member);
            DB::commit();
            return $group;
        } catch (\Exception | RequestException $e) {
            DB::rollBack();
            Log::error('add_member_to_group_service', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function addModuleToGroup($inputs, $groupId)
    {
        try {
            DB::beginTransaction();
            $groupform = GroupForm::with('form', 'actualGroup')
                ->withTrashed()->where('form_id', $inputs['form_id'])->where('group_id', $groupId)
                ->first();
            if (!$groupform) {
                $groupform = new GroupForm();
                $groupform->form_id = $inputs['form_id'];
                $groupform->company_form_id = $inputs['company_form_id'];
                $groupform->group_id = $groupId;
                $groupform->save();
            } elseif ($groupform && !empty($groupform->archived_at)) {
                $groupform->archived_at  = null;
                $groupform->save();
            }
            $form = Form::find($inputs['form_id']);
            $group = Group::find($groupId);
            $groupUsers = $group->members;
            $todayDate = \Carbon\Carbon::now();
            foreach ($groupUsers as $user) {
                $member = $user->user;
                TrainingHistory::where('form_id', $groupform->form_id)->where('group_id', $groupform->group_id)
                    ->where('user_id', $member->id)->where('status', 'pending')->update(['status' => 'skipped']);
                TrainingHistory::create([
                    'form_id' => $groupform->form_id,
                    'group_id' => $groupform->group_id,
                    'user_id' => $member->id,
                    'status' => 'pending',
                    'assigned_date' => $todayDate->format('Y-m-d H:i:s'),
                ]);
            }
            $this->fireAddModuleToGroupEmail($group, $form, $groupform);
            DB::commit();
            return $groupform;
        } catch (\Exception | RequestException $e) {
            DB::rollBack();
            Log::error('add_module_to_group_service', ['error' => $e->getMessage()]);
            throw $e;
        }
    }
    public function fireAddMemberToGroupEmail($group, $user, $member)
    {
        $loggedUser = Auth::user();
        /**logs */

        dispatch(new XmeActionLog(
            auth()->user(),
            'create',
            '{user} added member "' . $user->name . '" to "' . $group->name . ' group"',
            $member,
        ));
        $isSupport = false;

        if (User::hasRole(Auth::user(), User::ROLE_COMPANY)) {
            $companyAdmins = User::with('role')->where('company_id', $loggedUser->company_id)
                ->whereHas('role', function ($q) {
                    $q->where('name', User::ROLE_BUSINESSADMIN)
                        ->orwhere('name', User::ROLE_EMPLOYEE);
                })->get();
            foreach ($companyAdmins as $companyAdmin) {
                $companyAdmin->notify(new UserAddedToGroupNotification($loggedUser, $user, $group, $isSupport));
            }
        } elseif (User::hasRole(Auth::user(), User::ROLE_BUSINESSADMIN)) {
            $bussinessAdmins = User::with('role')->where('company_id', $loggedUser->company_id)
                ->whereHas('role', function ($q) {
                    $q->where('name', User::ROLE_COMPANY)
                        ->orWhere('name', User::ROLE_EMPLOYEE);
                })->get();
            foreach ($bussinessAdmins as $bussinessAdmin) {
                $bussinessAdmin->notify(new UserAddedToGroupNotification($loggedUser, $user, $group, $isSupport));
            }
        }
    }

    public function fireAddModuleToGroupEmail($group, $form, $groupform)
    {

        /**logs */
        dispatch(new XmeActionLog(
            auth()->user(),
            'create',
            '{user} add module "' . $form->display_title . '" to "' . $group->name . ' group"',
            $groupform,
        ));

        $loggedUser = Auth::user();
        $users = [];
        if (isImpersonatedSuperAdmin()) {
            $isSupport = true;
        } else {
            $isSupport = false;
        }
        $users   = User::with('role')->where('company_id', $loggedUser->company_id)
            ->whereHas('role', function ($q) {
                $q->where('name', '!=', User::ROLE_SUPERADMIN);
            })->where('id', '!=', Auth::user()->id)->get();
        $data = [
            'group'     => $group,
            'form'      => $form,
            'isSupport' => $isSupport,
            'loggedUser' => $loggedUser
        ];

        dispatch(new SendNotification(
            $users,
            $data,
            'AssestAddedToGroupNotification',
            'sendCompanyAddedModuleGroupNotification',
            null
        ));
    }

    /**for soft delete group action */
    public function groupAction($inputs)
    {
        try {
            DB::beginTransaction();
            $group = '';
            $msg = '';
            if ($inputs['group_id'] != "") {
                $group = Group::withTrashed()->find($inputs['group_id']);
                if ($inputs['actionType'] == 'Archive') {
                    $group->delete();
                    $msg = trans("messages.groups_archived_successfully");
                    $this->multipleActionLogList($inputs['actionType'], false, $group);
                } elseif ($inputs['actionType'] == 'UnArchive') {
                    $group->restore();
                    $msg = trans("messages.groups_unarchive_successfully");
                    $this->multipleActionLogList($inputs['actionType'], true, $group);
                }
            }
            DB::commit();
            return [$group, $msg];
        } catch (\Exception | RequestException $e) {
            DB::rollBack();
            Log::error('group_action_service', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    /** For Multiple Archive and Multiple UnArchive Action */
    public function multipleAction($inputs)
    {
        try {
            DB::beginTransaction();
            $groups = explode(",", $inputs['multipleGroup']);
            if ($inputs['actionType'] == 'Archive') {
                foreach ($groups as $groupId) {
                    $group = Group::find($groupId);
                    $group->delete();
                    $this->multipleActionLogList($inputs['actionType'], false, $group);
                }
                $msg = trans("messages.multiple_groups_archived_successfully");
            } elseif ($inputs['actionType'] == 'UnArchive') {
                foreach ($groups as $groupId) {
                    $group =   Group::withTrashed()->find($groupId);
                    $group->restore();
                    $this->multipleActionLogList($inputs['actionType'], true, $group);
                }
                $msg = trans("messages.multiple_groups_unarchive_successfully");
            }
            DB::commit();
            return [true, $msg];
        } catch (\Exception | RequestException $e) {
            DB::rollBack();
            Log::error('multiple_group_action_service', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function deleteGroupMember($inputs, $groupId)
    {
        try {
            DB::beginTransaction();
            $member = UserGroup::with('user', 'actualGroup')->where('user_id', $inputs['user_id'])
                ->where('group_id', $groupId)->first();
            /**logs */
            dispatch(new XmeActionLog(
                auth()->user(),
                'delete',
                '{user} removed member "' . $member->user->name . '" from "' . $member->actualGroup->name . ' group"',
                $member,
            ));
            $group = Group::find($groupId);
            $member->scheduled_at = null;
            $member->save();
            //$this->fireGroupMemberDeleteEmail($group, $member);
            $member->delete();

            DB::commit();
            return true;
        } catch (\Exception | RequestException $e) {
            DB::rollBack();
            Log::error('delete_group_member_service', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function deleteGroupModule($inputs, $groupId)
    {
        try {
            DB::beginTransaction();
            $formGroup = GroupForm::with('form', 'actualGroup')->where('form_id', $inputs['form_id'])
                ->where('group_id', $groupId)->first();
            /**logs */

            dispatch(new XmeActionLog(
                auth()->user(),
                'delete',
                '{user} removed module "' . $formGroup->form->display_title . '" to "' .
                    $formGroup->actualGroup->name . 'group"',
                $formGroup,
            ));

            $formGroup->delete();
            DB::commit();
            return true;
        } catch (\Exception | RequestException $e) {
            DB::rollBack();
            Log::error('delete_group_module_service', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function fireGroupMemberDeleteEmail($group, $member)
    {
        $loggedUser = Auth::user();
        $users = User::with('role')->where('company_id', Auth::user()->company_id)->whereHas('role', function ($q) {
            $q->where('name', User::ROLE_EMPLOYEE);
        })->get();

        $members = $users;
        $companyAdmin = User::where('company_id', Auth::user()->company_id)
            ->whereHas('role', function ($q) {
                $q->where('name', 'company');
            })->first();
        if (Session::has('previousUserSuperAdmin')) {
            $isSupport = true;
        } else {
            $isSupport = false;
        }
        foreach ($users as $user) {

            if ($loggedUser->id != $user->id) {
                //$user->notify(new DeleteMemberNotification($loggedUser, $group, $member, $isSupport));
            }
            $members->push($user);
        }

        if (User::hasRole(Auth::user(), User::ROLE_COMPANY)) {
            $bussinessAdmins = User::with('role')->where('company_id', $loggedUser->company_id)
                ->whereHas('role', function ($q) {
                    $q->where('name', User::ROLE_BUSINESSADMIN);
                })->get();
            foreach ($bussinessAdmins as $businessAdmin) {
                $members[] = $businessAdmin;
                $businessAdmin->notify(new DeleteMemberNotification($loggedUser, $group, $member, $isSupport));
            }
        } else {
            $bussinessAdmins = User::where('company_id', $loggedUser->company_id)
                ->whereHas('role', function ($q) {
                    $q->where('name', User::ROLE_BUSINESSADMIN);
                });
            if (!$isSupport) {
                $bussinessAdmins->where('id', '!=', $loggedUser->id);
            }
            $bussinessAdmins = $bussinessAdmins->get();
            $members->merge($bussinessAdmins);
            $companyAdmin->notify(new DeleteMemberNotification($loggedUser, $group, $member, $isSupport));
        }
    }

    public function saveReassignSettings($inputs, $groupId)
    {
        try {
            DB::beginTransaction();
            $groupModule = GroupForm::with('form', 'actualGroup')
                ->where('form_id', $inputs['form_id'])->where('group_id', $groupId)->first();
            $inputs['custom_interval'] = $inputs['reassign_interval'] == 'custom' ?
                $inputs['custom_interval'] : '0';
            if ($inputs['end_point'] == 0) {
                $inputs['end_type'] = null;
                $inputs['end_times'] = null;
                $inputs['end_date'] = null;
                $inputs['pending_end_times'] = null;
            } else {
                if ($inputs['end_type'] == 'fix') {
                    $inputs['end_times'] = null;
                    $inputs['pending_end_times'] = null;
                } else {
                    $inputs['pending_end_times'] = $inputs['end_times'];
                    $inputs['end_date'] = null;
                }
            }
            $groupModule->update($inputs);



            /**action logs reassign settings**/
            $sheduleDate = '';
            if (!empty($groupModule->reassign_interval) && $groupModule->reassign_interval == 'monthly') {
                $sheduleDate = ' Every month on day ' .
                    $groupModule->month_reassign_day . ' @ ' . $groupModule->reassign_time;
            } elseif ($groupModule->reassign_interval == 'weekly') {
                $sheduleDate = GroupForm::WEEKDAYS[$groupModule->week_reassign_day]
                    . ' @ ' . $groupModule->reassign_time;
            } else {
                $sheduleDate = 'After every' . $groupModule->custom_interval . 'days @ ' . $groupModule->reassign_time;
            }


            $sheduleClosedOn = '';
            if ($groupModule->is_reassign &&  $groupModule->end_point) {
                if ($groupModule->end_type == 'fix') {
                    $sheduleClosedOn = 'Schedule closed on ' .  $groupModule->end_date;
                } else {
                    $sheduleClosedOn = 'Schedule closed after' .  $groupModule->end_times . ' times and pending times
                is ' .  $groupModule->pending_end_times;
                }
            }

            if ($sheduleClosedOn) {
                $this->updateRassignSettings(
                    $sheduleDate,
                    $sheduleClosedOn,
                    $groupModule->form->display_title,
                    $groupModule->actualGroup->name,
                    $groupModule
                );
            }
            $this->updateRassignSettings(
                $sheduleDate,
                false,
                $groupModule->form->display_title,
                $groupModule->actualGroup->name,
                $groupModule
            );
            DB::commit();
            return $groupModule;
        } catch (\Exception | RequestException $e) {
            DB::rollBack();
            Log::error('save_reassign_module_service', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function removeAssignSetting($inputs, $groupId)
    {
        try {
            DB::beginTransaction();
            $groupModule = GroupForm::with('form')->where('form_id', $inputs['form_id'])
                ->where('group_id', $groupId)->first();
            $formGroup = $groupModule;
            $inputs['is_reassign'] = 0;
            $inputs['reassign_interval'] = null;
            $inputs['month_reassign_day'] = null;
            $inputs['week_reassign_day'] =  null;
            $inputs['reassign_time'] =  null;
            $inputs['end_point'] = 0;
            $inputs['end_type'] = null;
            $inputs['end_times'] = null;
            $inputs['end_date'] = null;
            $inputs['pending_end_times'] = null;

            $inputs['custom_interval'] = '0';
            $groupModule->update($inputs);
            /**logs */
            dispatch(new XmeActionLog(
                auth()->user(),
                'delete',
                '{user} remove reassign settings module for "' . $formGroup->form->display_title . '" in group  "' .
                    $formGroup->actualGroup->name . ' group"',
                $groupModule,
            ));

            DB::commit();
            return $groupModule;
        } catch (\Exception | RequestException $e) {
            DB::rollBack();
            Log::error('delete_reassign_module_service', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function groupForm($inputs, $groupId)
    {
        try {
            $formGroup =  GroupForm::with('form', 'actualGroup')->where('form_id', $inputs['form_id'])
                ->where('group_id', $groupId)->first();

            return $formGroup;
        } catch (\Exception | RequestException $e) {
            Log::error('group_form_service', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function updateScheduleDate($inputs, $groupId)
    {
        try {
            DB::beginTransaction();
            $scheduleDate = Carbon::parse($inputs['date'])->format('Y-m-d H:i');
            $isGroup = Group::find($groupId);
            if (!($isGroup)) {
                return false;
            }
            if (isset($inputs['user']) && !empty($inputs['user'])) {
                $groupUser = UserGroup::where('user_id', $inputs['user'])
                    ->where('group_id', $groupId)->first();
                $groupUser->scheduled_at = $scheduleDate;
                $groupUser->save();

                /**activity log shedule to user*/

                $this->updateSheduleDateUser(
                    $scheduleDate,
                    $groupUser->user->name,
                    $isGroup->name,
                    $isGroup
                );
            } else {
                $groupUser = UserGroup::where('group_id', $groupId)->get();
                foreach ($groupUser as $user) {
                    $user->scheduled_at  = $scheduleDate;
                    $user->save();
                    /**activity logs shedule all user  */
                    $this->updateSheduleDateAllUser($scheduleDate, $isGroup->name, $isGroup);
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



    /**multiple action logs  */
    public function multipleActionLogList($actionType, $isArchived, $group)
    {
        /***logs */

        if ($isArchived == 1) {
            dispatch(new XmeActionLog(
                auth()->user(),
                'UnArchive',
                '{user} ' . $actionType . ' the "{model}" group on archive groups page',
                $group,
            ));
        } else {
            dispatch(new XmeActionLog(
                auth()->user(),
                'Archive',
                '{user} ' . $actionType . ' the "{model}" group on groups page',
                $group,
            ));
        }
    }

    // update group order
    public function updateOrderAction($inputs)
    {
        try {
            DB::beginTransaction();
            $count = 1;
            foreach ($inputs as $group) {
                Group::where('id', $group)
                    ->where('company_id', Auth::user()->company_id)
                    ->update(['display_order' => $count]);
                $count++;
            }
            DB::commit();
            return true;
        } catch (\Exception | RequestException $e) {
            DB::rollBack();
            Log::error('update_group_order_service', ['error' => $e->getMessage()]);
            throw $e;
        }
    }



    /**update all user shedule date**/
    public function updateSheduleDateAllUser($scheduleDate, $groupName, $group)
    {
        dispatch(new XmeActionLog(
            auth()->user(),
            'update',
            '{user} update shedule date "' . $scheduleDate .
                '" to all members to "' . $groupName .
                ' group" on group members page.',
            $group,
        ));
    }


    /**update shedule member action logs */
    public function updateSheduleDateUser($scheduleDate, $member, $groupName, $group)
    {
        dispatch(new XmeActionLog(
            auth()->user(),
            'update',
            '{user} update shedule date "' . $scheduleDate . '" to  member "
            ' . $member . '" to  "' . $groupName . ' group" on  group members page.',
            $group,
        ));
    }


    /***action logs update reassign settings**/
    public function updateRassignSettings($scheduleDate, $closeDate, $module, $groupName, $group)
    {
        if ($closeDate) {
            dispatch(new XmeActionLog(
                auth()->user(),
                'update',
                '{user} update reassing date "' . $scheduleDate . '" and "' . $closeDate . '" to  module "
                ' . $module . '" to  "' . $groupName . ' group" on  group modules page.',
                $group,
            ));
        } else {
            dispatch(new XmeActionLog(
                auth()->user(),
                'update',
                '{user} update reassing date "' . $scheduleDate . '" to  module "
                ' . $module . '" to  "' . $groupName . ' group" on  group modules page.',
                $group,
            ));
        }
    }



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
