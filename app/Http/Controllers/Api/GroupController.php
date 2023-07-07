<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\FormCollection;
use Illuminate\Http\Request;
use App\Traits\ApiResponse;
use App\Services\GroupService;
use Illuminate\Support\Facades\Log;
use App\Http\Resources\GroupCollection;
use App\Http\Resources\GroupFormCollection;
use App\Http\Resources\GroupResource;
use App\Http\Resources\MemberCollection;
use App\Http\Resources\UserGroupCollection;
use Illuminate\Support\Facades\Validator;

class GroupController extends Controller
{
    use ApiResponse;
    protected $groupService;

    public function __construct(GroupService $groupService = null)
    {
        $this->groupService = $groupService ?? new GroupService();
    }

    public function list(Request $request)
    {
        try {
            $inputs = $request->all();
            $responseArr  = [];
            $groups =    new GroupCollection($this->groupService->list($inputs));
            $responseArr = $groups->response()->getData(true);
            $responseArr['message'] = trans('messages.group_data_fetched_successfully');
            return $this->successResponse($responseArr);
        } catch (\Exception $e) {
            Log::error('Group_List_Api', ['error' => $e]);
            report($e);
            return $this->failResponse();
        }
    }

    public function create(Request $request)
    {
        $inputs = $request->all();
        $responseArr  = [];
        $validations  = [
            'name' => 'required',
        ];
        $inputs = $request->all();
        $request->validate($validations);

        try {
            $responseArr['data'] =    ($this->groupService->create($inputs));
            $responseArr['message'] = trans('messages.group_created_successfully');
            return $this->successResponse($responseArr);
        } catch (\Exception $e) {
            Log::error('Group_Create_Api', ['error' => $e]);
            report($e);
            return $this->failResponse();
        }
    }

    public function edit($groupId)
    {
        try {
            $responseArr  = [];
            $responseArr['data'] =    new GroupResource($this->groupService->edit($groupId));
            $responseArr['message'] = trans('messages.edit_group_data');
            return $this->successResponse($responseArr);
        } catch (\Exception $e) {
            Log::error('Group_Edit_Api', ['error' => $e]);
            report($e);
            return $this->failResponse();
        }
    }

    public function update(Request $request, $groupId)
    {
        $responseArr  = [];
        $validations  = [
            'name' => 'required',
        ];
        $inputs = $request->all();
        $request->validate($validations);

        try {
            $inputs = $request->all();
            $responseArr['data'] =    ($this->groupService->update($inputs, $groupId));
            $responseArr['message'] = trans('messages.group_updated_successfully');
            return $this->successResponse($responseArr);
        } catch (\Exception $e) {
            Log::error('Group_Update_Api', ['error' => $e]);
            report($e);
            return $this->failResponse();
        }
    }

    public function groupMembers(Request $request, $groupId)
    {

        try {
        $responseArr  = [];
        $inputs = $request->all();
        $members =    new UserGroupCollection($this->groupService->groupMembers($inputs, $groupId));
        $responseArr = $members->response()->getData(true);
            $responseArr['message'] = trans('messages.group_members');
            return $this->successResponse($responseArr);
        } catch (\Exception $e) {
            Log::error('Group_Members_Api', ['error' => $e]);
            report($e);
            return $this->failResponse();
        }
    }

    public function groupModules(Request $request, $groupId)
    {
        try {
            $responseArr  = [];
            $inputs = $request->all();
            $forms = new GroupFormCollection($this->groupService->groupModules($inputs, $groupId));
            $responseArr = $forms->response()->getData(true);
            $responseArr['message'] = trans('messages.group_modules');
            return $this->successResponse($responseArr);
        } catch (\Exception $e) {
            Log::error('Group_Module_Api', ['error' => $e]);
            report($e);
            return $this->failResponse();
        }
    }

    public function groupPendingMembers(Request $request, $groupId)
    {
        try {
            $inputs = $request->all();
            $responseArr['data'] = $this->groupService->groupPendingMembers($inputs, $groupId);
            $responseArr['message'] = trans('messages.group_pending_users');
            return $this->successResponse($responseArr);
        } catch (\Exception $e) {
            Log::error('Group_Pending_Members_Api', ['error' => $e]);
            report($e);
            return $this->failResponse();
        }
    }

    public function groupPendingModules(Request $request, $groupId)
    {
        try {
            $inputs = $request->all();
            $responseArr = [];
            $forms = new FormCollection($this->groupService->groupPendingModules($inputs, $groupId));
            $responseArr['data'] = $forms->response()->getData(true);
            $responseArr['message'] = trans('messages.group_pending_modules');
            return $this->successResponse($responseArr);
        } catch (\Exception $e) {
            Log::error('Group_Pending_Modules_Api', ['error' => $e]);
            report($e);
            return $this->failResponse();
        }
    }

    public function addMemberToGroup(Request $request, $groupId)
    {
        try {
            $inputs = $request->all();
            $responseArr = [];
            $responseArr['data'] = $this->groupService->addMemberToGroup($inputs, $groupId);
            $responseArr['message'] = trans('messages.member_added_to_the_group_successFully');
            return $this->successResponse($responseArr);
        } catch (\Exception $e) {
            Log::error('Add_Group_Member_Api', ['error' => $e]);
            report($e);
            return $this->failResponse();
        }
    }

    public function addModuleToGroup(Request $request, $groupId)
    {
        try {
            $inputs = $request->all();
            $responseArr = [];
            $responseArr['data'] = $this->groupService->addModuleToGroup($inputs, $groupId);
            $responseArr['message'] = trans('messages.module_added_to_the_group_successfully');
            return $this->successResponse($responseArr);
        } catch (\Exception $e) {
            Log::error('Add_Group_Module_Api', ['error' => $e]);
            report($e);
            return $this->failResponse();
        }
    }

    /** For soft delete Action*/
    public function groupAction(Request $request)
    {
        try {
            $inputs = $request->all();
            $responseArr = [];
            $results  = $this->groupService->groupAction($inputs);
            $responseArr['data'] =  $results[0];
            $responseArr['message'] = $results[1];
            return $this->successResponse($responseArr);
        } catch (\Exception $e) {
            Log::error('group_action_api', ['error' => $e->getMessage()]);
            report($e);
            return $this->failResponse();
        }
    }

    /** For multipleAction */
    public function multipleAction(Request $request)
    {
        try {
            $inputs = $request->all();
            $responseArr = [];
            $results = $this->groupService->multipleAction($inputs);
            $responseArr['data'] = $results[0];
            $responseArr['message'] = $results[1];
            return $this->successResponse($responseArr);
        } catch (\Exception $e) {
            Log::error('multiple_group_action_api', ['error' => $e->getMessage()]);
            report($e);
            return $this->failResponse();
        }
    }

    public function deleteGroupMember(Request $request, $groupId)
    {
        try {
            $responseArr = [];
            $inputs = $request->all();
            $responseArr['data'] = $this->groupService->deleteGroupMember($inputs, $groupId);
            $responseArr['message'] = trans('messages.group_member_deleted_successfully');
            return $this->successResponse($responseArr);
        } catch (\Exception $e) {
            Log::error('Group_Member_Delete_Api', ['error' => $e->getMessage()]);
            report($e);
            return $this->failResponse();
        }
    }

    public function deleteGroupModule(Request $request, $groupId)
    {
        try {
            $responseArr = [];
            $inputs = $request->all();
            $responseArr['data'] = $this->groupService->deleteGroupModule($inputs, $groupId);
            $responseArr['message'] = trans('messages.group_module_deleted_successfully');
            return $this->successResponse($responseArr);
        } catch (\Exception $e) {
            Log::error('Group_Module_Delete_Api', ['error' => $e->getMessage()]);
            report($e);
            return $this->failResponse();
        }
    }

    public function saveReassignSettings(Request $request, $groupId)
    {
        $responseArr = [];
        $inputs = $request->all();
        $validations = [
            'reassign_interval' => 'required',
            'reassign_time' => 'required',
            'end_type' => 'required_if:end_point,==,1',
            'end_times' => 'required_if:end_type,==,custom',
            'end_date' => 'required_if:end_type,==,fix',
        ];

        $validationMsg = [
            'reassign_interval.required' => 'Please select a reassign interval',
            'reassign_time.required' => 'Please select a reassign time',
            'week_reassign_day.required' => 'Please select a weekly reassign day',
            'month_reassign_day.required' => 'Please select a monthly reassign day',
            'custom_interval.required' => 'Please select a custom interval for reassign',
            'custom_interval.min' => 'Custom Interval should be greater than 1',
            'end_type.required_if' => 'Please choose a end type.',
            'end_times.required_if' => 'Please enter a value.',
            'end_date.required_if' => 'Please choose a end date.',
        ];

        if ($inputs['reassign_interval'] == 'weekly' && empty($inputs['week_reassign_day'])) {
            $validations['week_reassign_day'] = 'required';
        } elseif ($inputs['reassign_interval'] == 'monthly' && empty($inputs['month_reassign_day'])) {
            $validations['month_reassign_day'] = 'required';
        } elseif ($inputs['reassign_interval'] == 'custom' && empty($inputs['custom_interval'])) {
            $validations['formGroup.custom_interval'] = 'required|numeric|min:1';
        }
        $request->validate($validations, $validationMsg);

        try {
            $responseArr['data'] = $this->groupService->saveReassignSettings($inputs, $groupId);
            $responseArr['message'] = trans('messages.reassign_the_module_successfully');
            return $this->successResponse($responseArr);
        } catch (\Exception $e) {
            Log::error('Reassign_Module_Api', ['error' => $e->getMessage()]);
            report($e);
            return $this->failResponse();
        }
    }

    public function removeAssignSetting(Request $request, $groupId)
    {

        try {
            $inputs = $request->all();
            $responseArr['data'] = $this->groupService->removeAssignSetting($inputs, $groupId);
            $responseArr['message'] = trans('messages.removed_the_assign_module');
            return $this->successResponse($responseArr);
        } catch (\Exception $e) {
            Log::error('Remove_Reassign_Module_Api', ['error' => $e->getMessage()]);
            report($e);
            return $this->failResponse();
        }
    }

    public function groupForm(Request $request, $groupId)
    {
        try {
            $inputs = $request->all();
            $responseArr['data'] = $this->groupService->groupForm($inputs, $groupId);
            $responseArr['message'] = trans('messages.group_form_info');
            return $this->successResponse($responseArr);
        } catch (\Exception $e) {
            Log::error('Group_Form_Info', ['error' => $e->getMessage()]);
            report($e);
            return $this->failResponse();
        }
    }

    public function updateScheduleDate(Request $request, $groupId)
    {
        try {
            $responseArr = [];
            $inputs = $request->all();
            $responseArr['data'] = $this->groupService->updateScheduleDate($inputs, $groupId);
            $responseArr['message'] = trans('messages.updated_the_schedule_date_succesfully');
            return $this->successResponse($responseArr);
        } catch (\Exception $e) {
            Log::error('Update_Group_Schedule_Date_Api', ['error' => $e]);
            report($e);
            return $this->failResponse();
        }
    }

    public function updateOrderAction(Request $request)
    {
        try {
            $inputs = $request->all();
            $responseArr  = [];
            $responseArr['data'] = $this->groupService->updateOrderAction($inputs);
            $responseArr['message'] = trans('messages.updated_group_order');
            return $this->successResponse($responseArr);
        } catch (\Exception $e) {
            Log::error('Update_Group_Order_Api', ['error' => $e]);
            report($e);
            return $this->failResponse();
        }
    }
}
