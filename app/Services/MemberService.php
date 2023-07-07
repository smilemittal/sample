<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Jobs\XmeActionLog;
use App\Models\User;
use App\Models\Role;
use App\Models\Form;
use App\Models\Invite;
use App\Models\UserForm;
use App\Events\UserInvited;
use Illuminate\Support\Str;
use App\Models\TrainingHistory;
use Illuminate\Support\Facades\Hash;
use App\Events\UserAcceptedInvitation;
use App\Models\Impersonation;
use Illuminate\Support\Facades\Session;
use App\Notifications\UserAssignFormNotification;
use App\Helpers\NotificationHelper;
use App\Jobs\SendNotification;
use App\Notifications\RevokeUserPrevilegesNotification;
use App\Notifications\AssignUserPrevilegesNotification;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class MemberService
{
    public function getList($inputs)
    {

        try {

            $perPage = isset($inputs['per_page']) && $inputs['per_page'] ? $inputs['per_page'] : 10;
            if (isset($inputs['per_page']) && $perPage == 'all') {
                $perPage = false;
            }
            $filterArr = $fields = [];
            $text = '';
            if (isset($inputs['search'])) {
                $filterArr['search'] = $inputs['search'];
                $text = 'searched members by "' . $inputs['search'] . '"';
                // $this->updatedSearch($filterArr['search'], $inputs['isArchived']);
            }
            $userObj = new User();
            $userObj = $userObj->list(
                $filterArr,
                $fields,
                'users.id',
                'DESC',
                $inputs['isArchived'],
                $perPage

            );
            /**activity logs */
             if (!empty($text)) {
                $this->updateAllFilterLog($text, $inputs['isArchived']);
            }
            return $userObj;
        } catch (\Exception | RequestException $e) {
            Log::error('member_list_service', ['error' => $e->getMessage()]);
            throw $e;
        }
    }



    public function updatedSearch($search, $isArchived)
    {
        if ($search != '') {
            if ($isArchived) {
                dispatch(
                    new XmeActionLog(
                        auth()->user(),
                        'search',
                        '{user} searched members by "' . $search . '" on member archive page',
                        null,
                    )
                );
            } else {
                dispatch(
                    new XmeActionLog(
                        auth()->user(),
                        'search',
                        '{user} searched members by "' . $search . '" on member  page',
                        null,
                    )
                );
            }
        }
    }


    public function updatediSearch($search)
    {
        if ($search != '') {
            dispatch(
                new XmeActionLog(
                    auth()->user(),
                    'search',
                    '{user} searched invited by "' . $search . '" on member  page',
                    null,
                )
            );
        }
    }



    public function inviteMember($inputs)
    {
        try {
            DB::beginTransaction();
            $tkn = Str::random(16);
            $invite = new Invite();
            $invite->email = $inputs['email'];
            $invite->token = $tkn;
            $invite->company_id = $inputs['company_id'];
            $loggedUser = Auth::user();
            if ($loggedUser->role->name != User::ROLE_SUPERADMIN) {
                $invite->company_id = Auth::user()->company_id;
            }
            $invite->invite_by = Auth::user()->id;
            $invite->invited_user_id = null;
            $invite->save();
            dispatch(new XmeActionLog(
                auth()->user(),
                'create',
                '{user} invited "{model}"',
                $invite,
            ));

            $invite->load('company');
            $inviteData['invite_by'] = Auth::user();
            $inviteData['company_name'] = $invite->company->name;
            $inviteData['invite_link'] = url("/register?token=$invite->token");
            $inviteData['user_email'] = $invite->email;
            event(new UserInvited($inviteData));
            DB::commit();
            return ['member' => $invite, 'inviteData' => $inviteData];
        } catch (\Exception | RequestException $e) {
            DB::rollback();
            Log::error('invite_member_service', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function show($id)
    {
        try {
            $currentUser = User::find($id);
            return $currentUser;
        } catch (\Exception | RequestException $e) {
            DB::rollback();
            Log::error('edit_user_service', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function updateData($inputs, $id)
    {
        try {
            DB::beginTransaction();
            $currentUser = User::find($id);
            $currentUser->update($inputs);
            if (!empty($inputs['file'])) {
                if (!empty($currentUser->avatar)) {
                    Storage::delete($currentUser->avatar);
                }
                $inputs['file']->store('avatars/');
                $path = 'avatars/' . $inputs['file']->hashName();
                $currentUser->avatar = $path;
                $currentUser->save();
            }


            dispatch(new XmeActionLog(
                auth()->user(),
                'update',
                '{user} updated member profile "{model}"',
                $currentUser,
            ));


            DB::commit();
            return $currentUser;
        } catch (\Exception | RequestException $e) {
            DB::rollback();
            Log::error('company_data_update_service', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $currentUser = User::find($id);

            dispatch(new XmeActionLog(
                auth()->user(),
                'delete',
                '{user} deleted member "{model}"',
                $currentUser,
            ));

            $currentUser->delete();
            DB::commit();
            return true;
        } catch (\Exception | RequestException $e) {
            DB::rollBack();
            Log::error('member_delete_service', ['error' => $e->getMessage()]);
            throw $e;
        }
    }
    public function getAssignForm($inputs, $memberId)
    {
        try {
            $selectedForms = array_map('strval', UserForm::where('user_id', $memberId)->get()
                ->pluck('form_id')->toArray(),);
            $forms = Form::join('company_forms', 'company_forms.form_id', 'forms.id')
            ->where('company_forms.company_id', Auth::user()->company_id)
            ->whereNull('company_forms.archived_at')
            ->where('company_forms.module_status', 1);
            // search filter
            if (isset($inputs['search'])) {
                $search = trim($inputs['search']);
                $forms = $forms->where('display_title', 'LIKE', "%{$search}%");
            }
            // search based upon xme and library
            if ($inputs['is_library'] == '1') {
                $forms = $forms->where('xme_area', 0);
            } elseif ($inputs['is_library'] == '0') {
                $forms = $forms->where('xme_area', 1);
            }

            if (User::hasRole(Auth::user(), 'company') || User::hasRole(Auth::user(), 'businessadmin')) {
                $forms = $forms->whereHas('form_company', function ($q) {
                    $q->where('module_status', 1);
                });
            }
            $forms = $forms->get();
            return ['selectedForm' => $selectedForms, 'form' => $forms];
        } catch (\Exception | RequestException $e) {
            Log::error('assigned_form_service', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function assignFormToUser($selectedForms, $currentUserId)
    {
        try {
            $previousSavedForms = UserForm::where('user_id', $currentUserId)
                ->pluck('form_id')->toArray();
            if (empty($previousSavedForms) && empty($selectedForms)) {
                return false;
            }
            $this->fireAssignFormToUserMail($currentUserId, $selectedForms, $previousSavedForms);
        } catch (\Exception | RequestException $e) {
            DB::rollBack();
            Log::error('assigned_form_to_user_service', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function fireAssignFormToUserMail($currentUserId, $selectedForms, $previousSavedForms)
    {
        $savedForms = [];
        if (!empty($selectedForms)) {
            if (count($selectedForms) > 1) {
                $moduleData = count($selectedForms);
            } else {
                $form = Form::find($selectedForms[0]);
                $moduleData = !empty($form->display_title) ? $form->display_title : "";
            }
            foreach ($selectedForms as $form_id) {
                $formUser = UserForm::firstOrCreate([
                    'user_id' => $currentUserId,
                    'form_id' => $form_id,
                ]);
                if (!in_array($form_id, $previousSavedForms)) {
                    if (!empty($formUser->form)) {
                        dispatch(new XmeActionLog(
                            auth()->user(),
                            'create',
                            '{user} assigned form "{model}" to "' . $formUser->form->display_title . '" form',
                            $formUser->form,
                        ));
                    }
                }
                if (!in_array($formUser->id, $savedForms)) {
                    $savedForms[] = $formUser->id;
                }
                $isModuleExist = TrainingHistory::where('group_id', 0)->where('user_id', $currentUserId)
                    ->where('form_id', $form_id)->first();
                if (empty($isModuleExist)) {
                    TrainingHistory::create([
                        'form_id' => $form_id,
                        'group_id' => 0,
                        'user_id' => $currentUserId,
                        'status' => 'pending',
                        'assigned_date' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'),
                    ]);
                }
            }

            $loggedUser = Auth::user();
            if (Session::has("previousUserSuperAdmin")) {
                $isSupport = true;
            } else {
                $isSupport = false;
            }
            $users = User::with('role')
                ->where('company_id', $loggedUser->company_id)
                ->where('id', $currentUserId)->get();
            $member = $users;
            $data = [
                'member'      => $member,
                'moduleData'  => $moduleData,
                'isSupport'   => $isSupport,
                'loggedUser'  => $loggedUser
            ];
            dispatch(new SendNotification(
                $users,
                $data,
                'UserAssignFormNotification',
                'sendNewModuleAssignedNotification',
                null
            ));
        }
        UserForm::where('user_id', $currentUserId)->whereNotIn('id', $savedForms)->delete();
    }

    public function loginAsUser($userId)
    {
        //try {
        $impersonator = auth()->user();
        $persona = User::find($userId);

        // Check if persona user exists, can be impersonated and if the impersonator has the right to do so.
        if (!$persona || !$persona->canBeImpersonated() || !$impersonator->canImpersonate()) {
            return false;
        }

        // Create new token for persona
        $personaToken = $persona->createToken('IMPERSONATION token');

        // Save impersonator and persona token references
        $impersonation = new Impersonation();
        $impersonation->user_id = $impersonator->id;
        $impersonation->personal_access_token_id = $personaToken->accessToken->id;
        $impersonation->save();
        session()->put('impersonate', $persona->id);
        // Log out impersonator
        //auth()->logout();

        dispatch(new XmeActionLog(
            auth()->user(),
            'login',
            'logged in as ' . $persona->name,
            null,
        ));


        return [
            "requested_id" => $userId,
            "persona" => $persona,
            "impersonator" => $impersonator,
            "token" => $personaToken->plainTextToken
        ];
        // } catch (\Exception | RequestException $e) {
        //     Log::error('login_as_user_servive', ['error' => $e->getMessage()]);
        //     throw $e;
        // }

    }

    // LEAVE IMPERSONATION
    public function leaveImpersonate()
    {
        // Get impersonated user
        $impersonatedUser = auth()->user();

        // Find the impersonating user
        $currentAccessToken = $impersonatedUser->currentAccessToken();
        $impersonation = Impersonation::where('personal_access_token_id', $currentAccessToken->id)->first();
        $impersonator = User::find($impersonation->user_id);
        $impersonatorToken = $impersonator->createToken('API token')->plainTextToken;

        // Logout impersonated user
        $impersonatedUser->currentAccessToken()->delete();

        return [
            "requested_id" => $impersonator->id,
            "persona" => $impersonator,
            "token" => $impersonatorToken,
        ];
    }
    public function createMember($inputs)
    {
        try {
            DB::beginTransaction();
            $inputs['name'] = $inputs['firstname'] . ' ' . $inputs['lastname'];
            $owner = User::where('company_id', $inputs['company_id'])->first();
            if (!$owner) {
                $inputs['role_id'] = 4;
            } else {
                $inputs['role_id'] = 5;
            }
            $inputs['password'] = Hash::make($inputs['password']);
            $newUser = User::create($inputs);
            Invite::where('email', $inputs['email'])->update(['invited_user_id' => $newUser->id]);
            event(new UserAcceptedInvitation($newUser));
            DB::commit();
            return $newUser;
        } catch (\Exception | RequestException $e) {
            DB::rollBack();
            Log::error('member_creation_service', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function reInviteUser($inviteId)
    {
        $currentInvite = Invite::find($inviteId);
        $hasInvite = User::where('email', $currentInvite->email)->first();
        if ($hasInvite) {
            return "meber already exist";
        }
        if (!$currentInvite) {
            return;
        }

        $loggedUser = Auth::user();
        if ($loggedUser->role->name != User::ROLE_SUPERADMIN) {
            $currentInvite->company_id = Auth::user()->company->id;
        }

        $currentInvite->invited_user_id = null;
        $currentInvite->save();


        dispatch(new XmeActionLog(
            auth()->user(),
            'create',
            '{user} reinvited "' . '{model}' . '"',
            $currentInvite,
        ));

        $currentInvite->load('company');
        $newInviteData['invite_by'] = Auth::user();
        $newInviteData['company_name'] = $currentInvite->company->name;
        $newInviteData['invite_link'] = url("/register?token=/{$currentInvite->token}");
        $newInviteData['user_email'] = $currentInvite->email;

        event(new UserInvited($newInviteData));
    }

    public function getPendingList($inputs)
    {
        try {
            $perPage = isset($inputs['per_page']) && $inputs['per_page'] ? $inputs['per_page'] : 10;
            if (isset($inputs['per_page']) && $perPage == 'all') {
                $perPage = false;
            }
            $filterArr = $fields = [];
            $text = '';
            if (isset($inputs['search'])) {
                $filterArr['search'] = $inputs['search'];
                // $this->updatediSearch($filterArr['search'], false);
                $text = 'searched invited by "' . $inputs['search'] . '"';
            }
            $pendingObj = new Invite();
            $pendingObj = $pendingObj->list($filterArr, $fields, 'invites.id', 'DESC', $perPage);
            /**activity logs upcoming training */
             if (!empty($text)) {
                $isArchived = '';
                $this->updateAllFilterLog($text, $isArchived);
            }
            return $pendingObj;
        } catch (\Exception | RequestException $e) {
            DB::rollBack();
            Log::error('invite_pending_service', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function deleteImage($memberId)
    {
        try {
            $user = User::find($memberId);
            $user->avatar = null;
            $user->save();
            return $user;
        } catch (\Exception | RequestException $e) {
            DB::rollBack();
            Log::error('member_image_delete_service', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function assignBAPrivileges($userId)
    {
        try {
            DB::beginTransaction();
            $user = User::find($userId);
            if ($user) {
                $role = Role::where('name', 'businessadmin')->first();
                if ($role) {
                    $user->role_id = $role->id;
                    $user->save();
                }
                $this->fireassignBAPrivilegesEmail($user);
            }
            DB::commit();
            return $user;
        } catch (\Exception | RequestException $e) {
            DB::rollBack();
            Log::error('assign_business_admin_previleges_api_service', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function removeBAPrivileges($userId)
    {
        try {
            DB::beginTransaction();
            $user = User::find($userId);
            if ($user) {
                $role = Role::where('name', 'employee')->first();
                if ($role) {
                    $user->role_id = $role->id;
                    if ($user->save()) {
                        dispatch(new XmeActionLog(
                            auth()->user(),
                            'update',
                            '{user} revoked business admin privileges from user "{model}"',
                            $user,
                        ));
                    }
                }
                $this->fireremoveBAPrivilegesEmail($user);
            }
            DB::commit();
            return $user;
        } catch (\Exception | RequestException $e) {
            DB::rollBack();
            Log::error('assign_business_admin_previleges_api_service', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function fireassignBAPrivilegesEmail($user)
    {
        $loggedUser = Auth::user();
        if (isImpersonatedSuperAdmin()) {
            $isSupport = true;
        } else {
            $isSupport = false;
        }
        if (User::hasRole($loggedUser, User::ROLE_COMPANY)) {
            $users = User::with('role')
                ->where('company_id', $loggedUser->company_id)
                ->Where('id', $user->id)
                ->get();

            $data = [
                'user'      => $user,
                'isSupport' => $isSupport,
                'loggedUser' => $loggedUser
            ];
            dispatch(new SendNotification(
                $users,
                $data,
                'AssignUserPrevilegesNotification',
                'sendUserAssignAdminPrivilegesCompanyNotification',
                null
            ));
        }
    }

    public function fireremoveBAPrivilegesEmail($user)
    {
        $loggedUser = Auth::user();
        if (isImpersonatedSuperAdmin()) {
            $isSupport = true;
        } else {
            $isSupport = false;
        }

        if (User::hasRole(Auth::user(), User::ROLE_COMPANY)) {
            $users = User::with('role')
                ->where('company_id', $loggedUser->company_id)
                ->Where('id', $user->id)
                ->get();

            $data = [
                'user'      => $user,
                'isSupport' => $isSupport,
                'loggedUser' => $loggedUser
            ];
            dispatch(new SendNotification(
                $users,
                $data,
                'RevokeUserPrevilegesNotification',
                'sendUserRevokeAdminPrivilegesCompanyNotification',
                null
            ));
        }
    }

    /** for soft delete member action */
    public function memberAction($inputs)
    {
        try {
            DB::beginTransaction();
            if ($inputs['member_id'] != "") {
                $user = user::withTrashed()->find($inputs['member_id']);
                // logs

                if ($inputs['actionType'] == 'Archive') {
                    $user->delete();
                    /** member action logs */
                    $this->setMemberActionLog($inputs['actionType'], false, $user);
                    $msg = trans("messages.member_archived_successfully");
                } elseif ($inputs['actionType'] == 'UnArchive') {
                    $user->restore();
                    /** member action logs */
                    $this->setMemberActionLog($inputs['actionType'], true, $user);
                    $msg = trans("messages.member_unarchive_successfully");
                }
                $user->save();
            }
            DB::commit();
            return [$user, $msg];
        } catch (\Exception | RequestException $e) {
            DB::rollBack();
            Log::error('member_action_service', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function resendInvite($inputs, $id)
    {
        try {
            $loggedUser = Auth::user();
            $invite = Invite::find($id);
            if ($loggedUser->role->name != User::ROLE_SUPERADMIN) {
                $invite->company_id = Auth::user()->company->id;
            }

            $invite->invited_user_id = null;
            $invite->save();
            dispatch(
                new XmeActionLog(auth()->user(), 'create', '{user} reinvited "' . '{model}' . '"', $invite, null)
            );
            $invite->load('company');
            $newInviteData['invite_by'] = Auth::user();
            $newInviteData['company_name'] = $invite->company->name;
            $newInviteData['invite_link'] = url("/register?token=$invite->token");
            $newInviteData['user_email'] = $invite->email;
            event(new UserInvited($newInviteData));
            return $newInviteData;
        } catch (\Exception | RequestException $e) {
            Log::error('resend_invite_service', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    /** For Multiple Archive and Multiple UnArchive Action */
    public function multipleAction($inputs)
    {
        try {
            DB::beginTransaction();
            $msg = '';
            $members = explode(",", $inputs['multipleMembers']);
            if ($inputs['actionType'] == 'Archive') {
                foreach ($members as $memberId) {
                    $member = User::find($memberId);
                    $member->delete();
                    $this->setMemberActionLog($inputs['actionType'], false, $member);
                }
                $msg = trans("messages.multiple_members_archived_successfully");
            } elseif ($inputs['actionType'] == 'UnArchive') {
                foreach ($members as $memberId) {
                    $member = User::withTrashed()->find($memberId);
                    $member->restore();
                    $this->setMemberActionLog($inputs['actionType'], true, $member);
                }
                $msg = trans("messages.multiple_members_unarchive_successfully");
            }
            DB::commit();
            return [true, $msg];
        } catch (\Exception | RequestException $e) {
            DB::rollBack();
            Log::error('multiple_member_action_service', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function deleteInvite($id)
    {
        try {
            DB::beginTransaction();
            $invite = Invite::find($id);
            dispatch(new XmeActionLog(
                auth()->user(),
                'delete',
                '{user} deleted Invite "{model}"',
                $invite,
            ));
            $invite->delete();
            DB::commit();
            return true;
        } catch (\Exception | RequestException $e) {
            DB::rollBack();
            Log::error('delete_invite_service', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function validateToken($token)
    {
        try {
            $debugMessage  = '';
            $tokenValid = false;
            $invite = Invite::with('company')->where('token', $token)->first();
            if (!$invite) {
                $debugMessage = "token not found: Token -> $token";
                $tokenValid = false;
                return [$debugMessage, $tokenValid, ''];
            }
            if ($usr = User::withTrashed()->where('email', $invite->email)->first()) {
                $debugMessage = "User with email already exist - " . $usr->email;
                $tokenValid = false;
                return [$debugMessage, $tokenValid, ''];
            }
            $tokenValid = true;
            return [$debugMessage, $tokenValid, $invite];
        } catch (\Exception | RequestException $e) {
            DB::rollBack();
            Log::error('validate_invite_token_service', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    /**member action logs **/

    public function setMemberActionLog($actionType, $isArchived, $member)
    {
        if ($isArchived == 1) {
            dispatch(new XmeActionLog(
                auth()->user(),
                'UnArchive',
                '{user} ' . $actionType . '  "{model}" member on archive members page',
                $member,

            ));
        } else {
            dispatch(new XmeActionLog(
                auth()->user(),
                'Archive',
                '{user} ' . $actionType . '  "{model}" member on  members page',
                $member,
            ));
        }
    }

    public function updateAllFilterLog($text, $isArchived)
    {
        if ($isArchived == 1) {
            dispatch(
                new XmeActionLog(
                    auth()->user(),
                    'search',
                    '{user} ' . $text . 'on member archive page',
                    null,
                )
            );
        } else {
            dispatch(
                new XmeActionLog(
                    auth()->user(),
                    'search',
                    '{user} ' . $text .  'on member page',
                    null,
                )
            );
        }
    }

}
