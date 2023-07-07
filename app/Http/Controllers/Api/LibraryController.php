<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\ApiResponse;
use App\Services\LibraryService;
use Illuminate\Support\Facades\Log;
use App\Http\Resources\FormCollection;
use App\Http\Resources\TFResponseCollection;

class LibraryController extends Controller
{
    use ApiResponse;
    protected $libraryService;

    public function __construct(LibraryService $libraryService = null)
    {
        $this->libraryService = $libraryService ?? new LibraryService();
    }
    
    public  function list(Request $request)
    {
        try {
            $inputs = $request->all();
            $responseArr  = [];
            $forms = new FormCollection($this->libraryService->list($inputs));
            $responseArr = $forms->response()->getData(true);
            $responseArr['message'] = trans('messages.library_collection_fetched_successfully');
            return $this->successResponse($responseArr);
        } catch (\Exception $e) {
            Log::error('Library_List_Api', ['error' => $e]);
            report($e);
            return $this->failResponse();
        }
    }

    public  function archivedModule($moduleId)
    {
        try {
            $responseArr  = [];
            $responseArr['data'] = $this->libraryService->archivedModule($moduleId);
            $responseArr['message'] = trans('messages.archived_moduled_successfully');
            return $this->successResponse($responseArr);
        } catch (\Exception $e) {
            Log::error('Archived_Module_Api', ['error' => $e]);
            report($e);
            return $this->failResponse();
        }
        # code...
    }

    public  function unArchivedModule($moduleId)
    {
        try {
            $responseArr  = [];
            $responseArr['data'] = $this->libraryService->unArchivedModule($moduleId);
            $responseArr['message'] = trans('messages.unarchived_moduled_successfully');
            return $this->successResponse($responseArr);
        } catch (\Exception $e) {
            Log::error('Unarchived_Module_Api', ['error' => $e]);
            report($e);
            return $this->failResponse();
        }
    }

    public function getResponses(Request $request)
    {
        try {
            $inputs = $request->all();
            $responseArr  = [];
            $reponses = new TFResponseCollection($this->libraryService->getResponses($inputs));
            $responseArr = $reponses->response()->getData(true);
            $responseArr['message'] = trans('messages.response_collection_fetched_successfully');
            return $this->successResponse($responseArr);
        } catch (\Exception $e) {
            Log::error('response_List_Api', ['error' => $e]);
            report($e);
            return $this->failResponse();
        }
    }

    public function updateOrderAction(Request $request)
    {
        try {
            $inputs = $request->all();
            $responseArr  = [];
            $responseArr['data'] = $this->libraryService->updateOrderAction($inputs);
            $responseArr['message'] = trans('messages.updated_library_order_successfully');
            return $this->successResponse($responseArr);
        } catch (\Exception $e) {
            Log::error('Update_Library_Order_Api', ['error' => $e]);
            report($e);
            return $this->failResponse();
        }
    }
}
