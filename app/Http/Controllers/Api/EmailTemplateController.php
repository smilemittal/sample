<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\EmailTemplateCollection;
use App\Services\EmailTemplatesService;
use Illuminate\Http\Request;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\Log;

class EmailTemplateController extends Controller
{
    use ApiResponse;
    protected $emailTemplateService;

    public function __construct(EmailTemplatesService $emailTemplateService = null)
    {
        $this->emailTemplateService = $emailTemplateService ?? new EmailTemplatesService();
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $responseArr  = [];
            $inputs = $request->all();
            $emailTemplate = new EmailTemplateCollection($this->emailTemplateService->index($inputs));
            $responseArr   = $emailTemplate->response()->getData(true);
            $responseArr['message'] = trans('messages.email_template_service');
            return $this->successResponse($responseArr);
        } catch (\Exception $e) {
            Log::error('Email_Template_Service', ['error' => $e]);
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
            $responseArr['data']    = $this->emailTemplateService->show($id);
            $responseArr['message'] = trans('messages.edit_email_template_data');
            return $this->successResponse($responseArr);
        } catch (\Exception $e) {
            Log::error('edit_email_template_api', ['error' => $e->getMessage()]);
            report($e);
            return $this->failResponse();
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $responseArr = [];
            $inputs = $request->all();
            $responseArr['data']    = $this->emailTemplateService->update($inputs, $id);
            $responseArr['message'] = trans('messages.updated_email_template_succesfully');
            return $this->successResponse($responseArr);
        } catch (\Exception $e) {
            Log::error('updated_email_template_api', ['error' => $e]);
            report($e);
            return $this->failResponse();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $this->emailTemplateService->destroy($id);
            $responseArr['message'] = trans('messages.email_template_delete_successfully');
            return $this->successResponse($responseArr);
        } catch (\Exception $e) {
            Log::error('email_template_delete_api', ['error' => $e->getMessage()]);
            report($e);
            return $this->failResponse();
        }
    }
}
