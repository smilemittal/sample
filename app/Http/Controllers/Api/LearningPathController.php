<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\FormCollection;
use Illuminate\Http\Request;
use App\Traits\ApiResponse;
use App\Services\LearningPathService;
use Illuminate\Support\Facades\Log;
use App\Http\Resources\LearningPathCollection;
use App\Http\Resources\LearningPathUserCollection;
use App\Http\Resources\LearningPathModuleCollection;
use Illuminate\Support\Facades\Validator;
use App\Models\LearningPath;

class LearningPathController extends Controller
{
    //
    use ApiResponse;
    protected $learningPathService;

    public function __construct(LearningPathService $learningPathService = null)
    {
        $this->learningPathService = $learningPathService ?? new LearningPathService();
    }

    public function assignLearningModule(Request $request, $learningPathId)
    {
        $responseArr  = [];
        $inputs = $request->all();
        $assignedForm =   new LearningPathCollection($this->learningPathService
            ->assignLearningModule($inputs, $learningPathId));
        $responseArr = $assignedForm->response()->getData(true);
        $responseArr['message'] = trans('messages.assigned_learning_modules_successfully');
        try {
            return $this->successResponse($responseArr);
        } catch (\Exception $e) {
            Log::error('Assign_Learning_Module_Api', ['error' => $e]);
            report($e);
            return $this->failResponse();
        }
    }

    public function completedLearningpath(Request $request, $learningPathId)
    {
        try {
            $responseArr  = [];
            $inputs = $request->all();
            $completedForm =   new LearningPathCollection($this->learningPathService
                ->completedLearningpath($inputs, $learningPathId));
            $responseArr = $completedForm->response()->getData(true);
            $responseArr['message'] = trans('messages.comleted_learning_path_modules_successfully');
            return $this->successResponse($responseArr);
        } catch (\Exception $e) {
            Log::error('Completed_Learning_Module_Api', ['error' => $e]);
            report($e);
            return $this->failResponse();
        }
    }

    public function list(Request $request)
    {
        try {
            $inputs = $request->all();
            $responseArr  = [];
            $learningPath =   new LearningPathCollection($this->learningPathService
                ->list($inputs));
            $responseArr = $learningPath->response()->getData(true);
            $responseArr['message'] = trans('messages.learning_path_fetched_successfully');
            return $this->successResponse($responseArr);
        } catch (\Exception $e) {
            Log::error('Learningpath_List_Api', ['error' => $e]);
            report($e);
            return $this->failResponse();
        }
    }

    public function createLearningPath(Request $request)
    {
        $validations  = [
            'title' => 'required',
        ];
        $inputs = $request->all();
        $request->validate($validations);
        try {
            $isExist = LearningPath::where('title', $inputs['title'])
                ->where('company_id', \Auth::user()->company_id)->first();
            if ($isExist) {
                $responseArr['errros'] = ['title' =>
                [trans('messages.learning_path_already_exist_with_this_title')]];
                return $this->failResponse($responseArr, 422);
            }
            $responseArr  = [];
            $responseArr['data'] = $this->learningPathService->createLearningPath($inputs);
            $responseArr['message'] = trans('messages.learning_path_created_successfully');
            return $this->successResponse($responseArr);
        } catch (\Exception $e) {
            Log::error('Learningpath_List_Api', ['error' => $e]);
            report($e);
            return $this->failResponse();
        }
    }

    public function edit($learningPathId)
    {
        try {
            $responseArr  = [];
            $responseArr['data'] = $this->learningPathService->edit($learningPathId);
            $responseArr['message'] = trans('messages.edit_learning_path_data');
            return $this->successResponse($responseArr);
        } catch (\Exception $e) {
            Log::error('Edit_Learningpath_Api', ['error' => $e]);
            report($e);
            return $this->failResponse();
        }
    }

    public function update(Request $request, $learningPathId)
    {
        $validations  = [
            'title' => 'required',
        ];
        $inputs = $request->all();
        $request->validate($validations);

        $isExist = LearningPath::where('title', $inputs['title'])
            ->where('company_id', \Auth::user()->company_id)->where('id', '!=', $learningPathId)->first();
        if ($isExist) {
            $responseArr['errors'] = ['title' =>
            [trans('messages.learning_path_already_exist')]];
            return $this->failResponse($responseArr, 422);
        }
        $responseArr  = [];
        $responseArr['data'] = $this->learningPathService->update($learningPathId, $inputs);
        try {
            $responseArr['message'] = trans('messages.updated_learning_path_successfully');
            return $this->successResponse($responseArr);
        } catch (\Exception $e) {
            Log::error('Update_Learningpath_Api', ['error' => $e]);
            report($e);
            return $this->failResponse();
        }
    }

    public function getLearningPathMembers(Request $request, $learningPathId)
    {
        try {
            $inputs = $request->all();
            $responseArr  = [];
            $members = new LearningPathUserCollection($this->learningPathService
            ->getLearningPathMembers($inputs, $learningPathId));
            $responseArr = $members->response()->getData(true);
            $responseArr['message'] = trans('messages.list_of_the_learning_path_member_successfully');
            return $this->successResponse($responseArr);
        } catch (\Exception $e) {
            Log::error('Learningpath_Members_Api', ['error' => $e]);
            report($e);
            return $this->failResponse();
        }
    }

    public function getLearningPathModules(Request $request, $learningPathId)
    {
        try {
            $responseArr  = [];
            $inputs = $request->all();
            $forms = new LearningPathModuleCollection($this->learningPathService
                ->getLearningPathModules($inputs, $learningPathId));
            $responseArr = $forms->response()->getData(true);
            $responseArr['message'] = trans('messages.list_of_the_learning_path_modules');
            return $this->successResponse($responseArr);
        } catch (\Exception $e) {
            Log::error('Learningpath_Modules_Api', ['error' => $e]);
            report($e);
            return $this->failResponse();
        }
    }

    public function learningPathPendingMembers(Request $request, $learningPathId)
    {
        try {
            $inputs = $request->all();
            $responseArr['data'] = $this->learningPathService->learningPathPendingMembers($inputs, $learningPathId);
            $responseArr['message'] = trans('messages.learning_path_pending_users');
            return $this->successResponse($responseArr);
        } catch (\Exception $e) {
            Log::error('Learningpath_Pending_Members_Api', ['error' => $e]);
            report($e);
            return $this->failResponse();
        }
    }

    public function learningPathPendingModules(Request $request, $learningPathId)
    {
        try {
            $inputs = $request->all();
            $responseArr = [];
            $forms = new FormCollection($this->learningPathService
            ->learningPathPendingModules($inputs, $learningPathId));
            $responseArr['data'] = $forms->response()->getData(true);
            $responseArr['message'] = trans('messages.learning_path_pending_modules');
            return $this->successResponse($responseArr);
        } catch (\Exception $e) {
            Log::error('Learningpath_Pending_Modules_Api', ['error' => $e]);
            report($e);
            return $this->failResponse();
        }
    }

    public function addMemberToLearningPath(Request $request, $learningPathId)
    {
        try {
            $inputs = $request->all();
            $responseArr = [];
            $responseArr['data'] = $this->learningPathService->addMemberToLearningPath($inputs, $learningPathId);
            $responseArr['message'] = trans('messages.member_added_to_the_learning_path_successfully');
            return $this->successResponse($responseArr);
        } catch (\Exception $e) {
            Log::error('Add_Member_To_LearningPath_Api', ['error' => $e]);
            report($e);
            return $this->failResponse();
        }
    }

    public function addModuleToLearningPath(Request $request, $learningPathId)
    {
        try {
            $inputs = $request->all();
            $responseArr = [];
            $responseArr['data'] = $this->learningPathService->addModuleToLearningPath($inputs, $learningPathId);
            $responseArr['message'] = trans('messages.module_added_to_the_learning_path_successfully');
            return $this->successResponse($responseArr);
        } catch (\Exception $e) {
            Log::error('Add_Modules_To_LearningPath_Api', ['error' => $e]);
            report($e);
            return $this->failResponse();
        }
    }

    public function lockLearningPath($learningPathId)
    {
        try {
            $responseArr = [];
            $responseArr['data'] = $this->learningPathService->lockLearningPath($learningPathId);
            $responseArr['message'] = trans('messages.learning_path_locked_successfully');
            return $this->successResponse($responseArr);
        } catch (\Exception $e) {
            Log::error('Locked_Learning_Path_Api', ['error' => $e]);
            report($e);
            return $this->failResponse();
        }
    }

    public function updateScheduleDate(Request $request, $learningPathId)
    {
        try {
            $responseArr = [];
            $inputs = $request->all();
            $responseArr['data'] = $this->learningPathService->updateScheduleDate($inputs, $learningPathId);
            $responseArr['message'] = trans('messages.updated_the_schedule_date_succesfully');
            return $this->successResponse($responseArr);
        } catch (\Exception $e) {
            Log::error('UpdateSchedule_Date_Api', ['error' => $e]);
            report($e);
            return $this->failResponse();
        }
    }

    public function duplicateLearningPath(Request $request, $learningPathId)
    {
        $validations  = [
            'title' => 'required',
        ];
        $inputs = $request->all();
        $request->validate($validations);
        try {
            $responseArr['data'] = $this->learningPathService->duplicateLearningPath($inputs, $learningPathId);
            $responseArr['message'] = trans('messages.duplicated_learning_path_created_successfully');
            return $this->successResponse($responseArr);
        } catch (\Exception $e) {
            Log::error('Duplicate_LearningPath_Api', ['error' => $e]);
            report($e);
            return $this->failResponse();
        }
    }

    /**
     * for Subject soft delete action
     */
    public function learningPathAction(Request $request)
    {
        try {
            $inputs = $request->all();
            $responseArr = [];
            $results = $this->learningPathService->learningPathAction($inputs);
            $responseArr['data'] =  $results[0];
            $responseArr['message'] = $results[1];
            return $this->successResponse($responseArr);
        } catch (\Exception $e) {
            Log::error('learning_path_action_api', ['error' => $e->getMessage()]);
            report($e);
            return $this->failResponse();
        }
    }

    /**
     * For multipleAction
     */
    public function multipleAction(Request $request)
    {
        try {
            $inputs = $request->all();
            $responseArr = [];
            $results = $this->learningPathService->multipleAction($inputs);
            $responseArr['data'] = $results[0];
            $responseArr['message'] = $results[1];
            return $this->successResponse($responseArr);
        } catch (\Exception $e) {
            Log::error('multiple_learning_path_action_api', ['error' => $e->getMessage()]);
            report($e);
            return $this->failResponse();
        }
    }

    public function updateLearningModuleStatus(Request $request, $learningPathId)
    {
        try {
            $inputs = $request->all();
            $results = $this->learningPathService->updateLearningModuleStatus($inputs, $learningPathId);
            $responseArr['data'] = $results[0];
            $responseArr['message'] = $results[1];
            return $this->successResponse($responseArr);
        } catch (\Exception $e) {
            Log::error('Update_Learning_Module_Status', ['error' => $e]);
            report($e);
            return $this->failResponse();
        }
    }

    public function removeLearningPathMember(Request $request, $learningPathId)
    {
        try {
            $responseArr = [];
            $inputs = $request->all();
            $responseArr['data'] = $this->learningPathService->removeLearningPathMember($inputs, $learningPathId);
            $responseArr['message'] = trans('messages.member_remove_successfully');
            return $this->successResponse($responseArr);
        } catch (\Exception $e) {
            Log::error('Learning_Path_Member_Remove_Api', ['error' => $e]);
            report($e);
            return $this->failResponse();
        }
    }

    public function removeLearningPathModule(Request $request, $learningPathId)
    {
        try {
            $responseArr = [];
            $inputs = $request->all();
            $responseArr['data'] = $this->learningPathService->removeLearningPathModule($inputs, $learningPathId);
            $responseArr['message'] = trans('messages.module_remove_successfully');
            return $this->successResponse($responseArr);
        } catch (\Exception $e) {
            Log::error('Learning_Path_Module_Remove_Api', ['error' => $e]);
            report($e);
            return $this->failResponse();
        }
    }

    public function learningPathModuleHistory(Request $request, $learningPathId)
    {
        try {
            $responseArr  = [];
            $inputs = $request->all();
            $responseArr['data'] =   $this->learningPathService
                ->learningPathModuleHistory($inputs, $learningPathId);
            $responseArr['message'] = trans('messages.learning_path_module_history');
            return $this->successResponse($responseArr);
        } catch (\Exception $e) {
            Log::error('Learning_Path_Module_History_Api', ['error' => $e]);
            report($e);
            return $this->failResponse();
        }
    }

    public function updateOrderAction(Request $request, $id)
    {
        try {
            $inputs = $request->all();
            $responseArr  = [];
            $responseArr['data'] = $this->learningPathService->updateOrderAction($inputs, $id);
            $responseArr['message'] = trans('messages.updated_learningpath_order');
            return $this->successResponse($responseArr);
        } catch (\Exception $e) {
            Log::error('Update_Learning_Path_Order_Api', ['error' => $e]);
            report($e);
            return $this->failResponse();
        }
    }
}
