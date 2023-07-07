<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\ApiResponse;
use App\Services\FormService;
use App\Http\Resources\FormCollection;
use App\Http\Resources\FormResource;
use App\Models\Form;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ManageFormController extends Controller
{
    use ApiResponse;
    protected $formService;

    public function __construct(FormService $formService = null)
    {
        $this->formService = $formService ?? new FormService();
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $responseArr  = [];
            $inputs = $request->all();
            $forms = new FormCollection($this->formService->getList($inputs));
            $responseArr = $forms->response()->getData(true);
            $responseArr['message'] = trans('messages.form_detail_fetched_successfully');
            return $this->successResponse($responseArr);
        } catch (\Exception $e) {
            Log::error('List_Form_Api', ['error' => $e]);
            report($e);
            return $this->failResponse();
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $responseArr = [];
        $validations  = [
            'typeform_id' => 'required',
            'display_title' => 'required',
            'status' => 'required',
        ];
        $inputs = $request->all();
        $request->validate($validations);
        // validations

        if (Form::withTrashed()->where('typeform_id', $request->input('typeform_id'))->exists()) {
            $responseArr['errors'] = ['typeform_id' => [trans('messages.typeform_already_exist')]];
            return $this->failResponse($responseArr, 422);
        }
        if (($request->input('is_review') == true) && empty($request->input('review_category'))) {
            $responseArr['errors'] = ['review_category' => [trans('messages.please_select_the_review_date')]];
            return $this->failResponse($responseArr, 422);
        } elseif (($request->input('is_review') == true)  && ($request->input('review_category') == 3)
            && empty($request->input('review_date'))
        ) {
            $responseArr['errors'] = ['review_category' => [trans('messages.please_enter_the_review_date')]];
            return $this->failResponse($responseArr, 422);
        }

        try {
            $responseArr['data'] = $this->formService->store($inputs);
            $responseArr['message'] = trans('messages.created_the_module_successfully');
            return $this->successResponse($responseArr);
        } catch (\Exception $e) {
            Log::error('Create_Module_Api', ['error' => $e]);
            report($e);
            return $this->failResponse();
        }
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $responseArr = [];
            $data = $this->formService->edit($id);
            $responseArr['data']['form'] = new FormResource($data['form']);
            $responseArr['data']['time_detail'] = $data['timeDetail'];
            $responseArr['message'] = trans('messages.edit_form_data');
            return $this->successResponse($responseArr);
        } catch (\Exception $e) {
            Log::error('Edit_Form_Detail_Api', ['error' => $e->getMessage()]);
            report($e);
            return $this->failResponse();
        }
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
    public function updateForm(Request $request, string $id)
    {
        $responseArr = [];
        $validations  = [
            'typeform_id' => 'required',
            'display_title' => 'required',
            'status' => 'required',
        ];
        $inputs = $request->all();
        $request->validate($validations);

        // validations if typeform already exist
        if (Form::where('typeform_id', $inputs['typeform_id'])->exists()) {
            $conflictId = Form::where('typeform_id', $inputs['typeform_id'])->first()->id;
            if ($conflictId != $id) {
                $responseArr['errors'] = ['typeform_id' => [trans('messages.This_form_has_already_been_imported')]];
                return $this->failResponse($responseArr, 422);
            }
        }
        // validations if custom review date is not select
        if (($inputs['review_category'] == 3) && empty($inputs['review_date'])) {
            $responseArr['errors'] = ['typeform_id' => [trans('messages.please_select_the_review_date')]];
            return $this->failResponse($responseArr, 422);
        }
        try {
            $responseArr['data'] = $this->formService->update($inputs, $id);
            $responseArr['message'] = trans('messages.updated_the_module_successfully');
            return $this->successResponse($responseArr);
        } catch (\Exception $e) {
            Log::error('Create_Module_Api', ['error' => $e]);
            report($e);
            return $this->failResponse();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function updateFormStatus($moduleid)
    {
        try {
            $responseArr  = [];
            $results = $this->formService->updateFormStatus($moduleid);
            $responseArr['data'] =  $results[0];
            $responseArr['message'] = $results[1];
            return $this->successResponse($responseArr);
        } catch (\Exception $e) {
            Log::error('Update_Form_Status_Api', ['error' => $e]);
            report($e);
            return $this->failResponse();
        }
    }

    public function validateForm(Request $request)
    {
        $responseArr = [];
        $validations  = [
            'typeform_id' => 'required|min:8',
        ];
        $inputs = $request->all();
        $request->validate($validations);

        try {
            $responseArr  = [];
            $responseArr['data'] = $this->formService->validateForm($inputs);
            if ($responseArr['data'] == false) {
                $responseArr['errors'] = ['typeform_id' => [trans('messages.Invalid_form_id')]];
                return $this->failResponse($responseArr, 422);
            }
            return $this->successResponse($responseArr);
        } catch (\Exception $e) {
            Log::error('Validate_Form_Api', ['error' => $e]);
            report($e);
            return $this->failResponse();
        }
    }

    public function assignCompanyToForm(Request $request)
    {

        try {
            $responseArr  = [];
            $inputs = $request->all();
            $responseArr['data'] = $this->formService->assignCompanyToForm($inputs);
            $responseArr['message'] = trans('messages.assigned_module_to_company');
            return $this->successResponse($responseArr);
        } catch (\Exception $e) {
            Log::error('Assigned_Form_To_Company_Api', ['error' => $e]);
            report($e);
            return $this->failResponse();
        }
    }

    public function assignCompany($formId)
    {
        try {
            $responseArr  = [];
            $responseArr['data'] = $this->formService->assignCompany($formId);
            $responseArr['message'] = trans('messages.assigned_companies');
            return $this->successResponse($responseArr);
        } catch (\Exception $e) {
            Log::error('Assigned_Companies_Api', ['error' => $e]);
            report($e);
            return $this->failResponse();
        }
    }

    /**For formAction*/
    public function formAction(Request $request)
    {
        try {
            $inputs = $request->all();
            $responseArr = [];
            $results = $this->formService->formAction($inputs);
            $responseArr['data'] =  $results[0];
            $responseArr['message'] = $results[1];
            return $this->successResponse($responseArr);
        } catch (\Exception $e) {
            Log::error('Form_Action_Api', ['error' => $e->getMessage()]);
            report($e);
            return $this->failResponse();
        }
    }

    /** For multipleAction*/
    public function multipleAction(Request $request)
    {

        $inputs = $request->all();
        if ($inputs['multipleForms'] == null) {
            $responseArr['errors'] = ['multipleForms' =>
            [trans('messages.please select the values')]];
            return $this->failResponse($responseArr, 422);
        }
        try {
            $responseArr = [];
            $results = $this->formService->multipleAction($inputs);
            $responseArr['data'] =  $results[0];
            $responseArr['message'] = $results[1];
            return $this->successResponse($responseArr);
        } catch (\Exception $e) {
            Log::error('Multiple_Form_Action_Api', ['error' => $e->getMessage()]);
            report($e);
            return $this->failResponse();
        }
    }

    public function pullResponsesFor($id)
    {
        try {
            $responseArr = [];
            $responseArr['data'] =  $this->formService->pullResponsesFor($id);
            $responseArr['message'] = trans('messages.pull_response');
            return $this->successResponse($responseArr);
        } catch (\Exception $e) {
            Log::error('Pull_Responses_APi', ['error' => $e->getMessage()]);
            report($e);
            return $this->failResponse();
        }
    }
}
