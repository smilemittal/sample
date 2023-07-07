<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\ApiResponse;
use App\Services\MemberService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\MemberCollection;
use App\Http\Resources\MemberPendingInviteCollection;
use App\Models\Company;
use App\Models\User;
use App\Models\Invite;
use App\Traits\CompanyAuthorization;

class MemberController extends Controller
{
    use ApiResponse;
    use CompanyAuthorization;
    protected $memberService;

    public function __construct(MemberService $memberService = null)
    {
        $this->memberService = $memberService ?? new MemberService();
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        try {
            $responseArr  = [];
            $inputs = $request->all();
            $members = new MemberCollection($this->memberService->getList($inputs));
            $responseArr = $members->response()->getData(true);
            $responseArr['message'] = trans('messages.member_detail_fetched_sccessfully');
            return $this->successResponse($responseArr);
        } catch (\Exception $e) {
            Log::error('List_Member_Api', ['error' => $e]);
            report($e);
            return $this->failResponse();
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $responseArr = [];
        $validations  = [
            'email' => 'required|email',
            'company_id' => 'required',
        ];
        $inputs = $request->all();
        $request->validate($validations);

        $hasUserTable = User::where('email', $inputs['email'])->first();
        $hasInviteTable = Invite::where('email', $inputs['email'])->first();
        if ($hasInviteTable || $hasUserTable) {
            $responseArr['errors'] = ['email' => [trans('messages.member_already_invited')]];
            return $this->failResponse($responseArr, 422);
        }
        try {
            $responseArr['data'] = $this->memberService->inviteMember($inputs);
            $responseArr['message'] = trans('messages.member_invited_successfully');
            return $this->successResponse($responseArr);
        } catch (\Exception $e) {
            Log::error('Invite_Member_Api', ['error' => $e]);
            report($e);
            return $this->failResponse();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $responseArr['data'] = $this->memberService->show($id);
            $responseArr['message'] = trans('messages.edit_member_data_successfully');
            return $this->successResponse($responseArr);
        } catch (\Exception $e) {
            Log::error('Detail_Member_Api', ['error' => $e]);
            report($e);
            return $this->failResponse();
        }

        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateMember(Request $request, $id)
    {
        $responseArr = [];
        $inputs = $request->all();
        $validations  = [
            'firstname' => 'required',
            'lastname' => 'required',
            'email' => 'required',
            'phone_nr' => 'required|digits:10',
        ];
        $request->validate($validations);

        try {
            $responseArr['data'] = $this->memberService->updateData($inputs, $id);
            $responseArr['message'] = trans('messages.member_data_updated_successfully');
            return $this->successResponse($responseArr);
        } catch (\Exception $e) {
            Log::error('Update_Member_Api', ['error' => $e]);
            report($e);
            return $this->failResponse();
        }
        //
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        try {
            $responseArr = [];
            $this->memberService->destroy($id);
            $responseArr['message'] = trans('messages.deleted_the_member_successfully');
            return $this->successResponse($responseArr);
        } catch (\Exception $e) {
            Log::error('Delete_Member_Api', ['error' => $e->getMessage()]);
            report($e);
            return $this->failResponse();
        }
    }
    // Get User Assigned forms
    public function getAssignForm(Request $request, $id)
    {
        try {
            $inputs = $request->all();
            $responseArr = [];
            $responseArr['data'] = $this->memberService->getAssignForm($inputs, $id);
            return $this->successResponse($responseArr);
        } catch (\Exception $e) {
            Log::error('Get_User_Assign_Form_Api', ['error' => $e->getMessage()]);
            report($e);
            return $this->failResponse();
        }
    }

    // Assign Form To user
    public function assignFormToUser(Request $request, $id)
    {
        try {
            $forms = $request->input('selectedForms');
            $responseArr = [];
            if (!empty($forms)) {
                $inputs = explode(',', $forms);
            } else {
                $inputs = "";
            }
            $responseArr['data'] =  $this->memberService->assignFormToUser($inputs, $id);
            $responseArr['message'] = trans('messages.module_assign_to_member_successfully');
            return $this->successResponse($responseArr);
        } catch (\Exception $e) {
            Log::error('Assign_Form_To_User_Api', ['error' => $e->getMessage()]);
            report($e);
            return $this->failResponse();
        }
    }

    // Login As User

    public function loginAsUser($userId, Request $request)
    {
        //try {
        $responseArr = [];
        $responseArr['data'] = $this->memberService->loginAsUser($userId);
        return $this->successResponse($responseArr);
        // } catch (\Exception $e) {
        //     Log::error('Login_As_User_Api', ['error' => $e->getMessage()]);
        //     report($e);
        //     return $this->failResponse();
        // }
    }

    // create member Account

    public function createMember(Request $request)
    {
        $responseArr = [];
        $validations  = [
            'firstname' => 'required',
            'lastname' => 'required',
            'email' => 'required',
            'company_id' => 'required',
            'email' => 'required|email',
            'password' => 'required',

        ];
        $inputs = $request->all();
        $request->validate($validations);
        $company = Company::find($inputs['company_id']);
        $result =$this->getComapnyAuth($company);
        if (!$result['companyCan']['total_users'] || !$result['companyCan']['members']) {
            $responseArr['errors'] = ['overlimit' =>
            [trans('messages.a_limit_of_the_subscription_has_been_reached_please_contact_with_administrator')]];
            return $this->failResponse($responseArr, 422);
        }

        try {
            $responseArr['data'] = $this->memberService->createMember($inputs);
            $responseArr['message'] = trans('messages.member_created_successfully');
            return $this->successResponse($responseArr);
        } catch (\Exception $e) {
            Log::error('Invite_Member_Api', ['error' => $e]);
            report($e);
            return $this->failResponse();
        }
    }

    // pending invite Member
    public function pendingInvite(Request $request)
    {
        try {
            $responseArr  = [];
            $inputs = $request->all();
            $pendingInvites = new MemberPendingInviteCollection($this->memberService->getPendingList($inputs));
            $responseArr = $pendingInvites->response()->getData(true);
            $responseArr['message'] = trans('messages.pending_detail');
            return $this->successResponse($responseArr);
        } catch (\Exception $e) {
            Log::error('List_Member_Api', ['error' => $e]);
            report($e);
            return $this->failResponse();
        }
    }

    public function resendInvite(Request $request, $id)
    {

        try {
            $responseArr  = [];
            $inputs = $request->all();
            $responseArr['data'] = $this->memberService->resendInvite($inputs, $id);
            $responseArr['message'] = trans('messages.resend_invite_successfully');
            return $this->successResponse($responseArr);
        } catch (\Exception $e) {
            Log::error('Resend_Invite_Api', ['error' => $e]);
            report($e);
            return $this->failResponse();
        }
    }

    public function deleteImage($memberId)
    {
        try {
            $responseArr['data'] = $this->memberService->deleteImage($memberId);
            $responseArr['message'] = trans('messages.delete_the_user_image');
            return $this->successResponse($responseArr);
        } catch (\Exception $e) {
            Log::error('List_Member_Api', ['error' => $e]);
            report($e);
            return $this->failResponse();
        }
    }

    public function assignBAPrivileges($userId)
    {
        try {
            $responseArr  = [];
            $responseArr['data'] = $this->memberService->assignBAPrivileges($userId);
            $responseArr['message'] = trans('messages.assign_the_bussiness_admin_privileges');
            return $this->successResponse($responseArr);
        } catch (\Exception $e) {
            Log::error('Assign_Business_Admin_Privileges_Api', ['error' => $e]);
            report($e);
            return $this->failResponse();
        }
    }

    public function removeBAPrivileges($userId)
    {
        try {
            $responseArr  = [];
            $responseArr['data'] = $this->memberService->removeBAPrivileges($userId);
            $responseArr['message'] = trans('messages.remove_the_bussiness_admin_privileges');
            return $this->successResponse($responseArr);
        } catch (\Exception $e) {
            Log::error('Remove_Business_Admin_Privileges_Api', ['error' => $e]);
            report($e);
            return $this->failResponse();
        }
    }

    /** for soft delete member action */
    public function memberAction(Request $request)
    {
        try {
            $inputs = $request->all();
            $responseArr = [];
            $results = $this->memberService->memberAction($inputs);
            $responseArr['data'] =  $results[0];
            $responseArr['message'] = $results[1];
            return $this->successResponse($responseArr);
        } catch (\Exception $e) {
            Log::error('Member_action_api', ['error' => $e->getMessage()]);
            report($e);
            return $this->failResponse();
        }
    }

    /** For multipleAction*/
    public function multipleAction(Request $request)
    {
        try {
            $inputs = $request->all();
            if ($inputs['multipleMembers'] == null) {
                $responseArr['errors'] = ['multipleMembers' =>
                [trans('messages.please select the values')]];
                return $this->failResponse($responseArr, 422);
            }
            $responseArr = [];
            $results = $this->memberService->multipleAction($inputs);
            $responseArr['data'] =  $results[0];
            $responseArr['message'] = $results[1];
            return $this->successResponse($responseArr);
        } catch (\Exception $e) {
            Log::error('multiple_member_action_api', ['error' => $e->getMessage()]);
            report($e);
            return $this->failResponse();
        }
    }

    public function deleteInvite($id)
    {
        try {
            $responseArr = [];
            $responseArr['data']  = $this->memberService->deleteInvite($id);
            $responseArr['message'] = trans('messages.delete_invite_successfully');
            return $this->successResponse($responseArr);
        } catch (\Exception $e) {
            Log::error('Delete_Invite_Api', ['error' => $e->getMessage()]);
            report($e);
            return $this->failResponse();
        }
    }

    public function validateToken($token)
    {
        try {
            $responseArr = [];
            $responseArr['data']  = $this->memberService->validateToken($token);
            $responseArr['message'] = trans('messages.token_vaidate_successfully');
            return $this->successResponse($responseArr);
        } catch (\Exception $e) {
            Log::error('Invite_Token_Validate_Api', ['error' => $e->getMessage()]);
            report($e);
            return $this->failResponse();
        }
    }

    public function updateBetaAccess(Request $request)
    {

        $user = $request->user();
        $message = '';
        if ($request->beta_access) {
            $user->beta_access = 1;
            $message = trans('messages.you_have_joined_the_beta_program');
        } else {
            $user->beta_access = 0;
            $message = trans('messages.you_have_left_the_beta_program');
        }
        $responseArr['message'] = $message;

        $user->save();
        return $this->successResponse($responseArr);
    }

    /**
     * Display the specified resource.
     */
    public function profile()
    {
        try {
            $responseArr['data'] = $this->memberService->show(auth()->id());
            $responseArr['message'] = trans('messages.profile_data_successfully');
            return $this->successResponse($responseArr);
        } catch (\Exception $e) {
            Log::error('profile_Api', ['error' => $e]);
            report($e);
            return $this->failResponse();
        }

        //
    }



    /**actionLogsMemberActions**/

}
