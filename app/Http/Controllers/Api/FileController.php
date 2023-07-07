<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PlanCollection;
use App\Services\PlanService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class FileController extends Controller
{

    use ApiResponse;
    protected $planService;

    public function __construct(PlanService $planService = null)
    {
        $this->planService = $planService ?? new PlanService();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {
        try {
            $responseArr = [];
            $inputs      = $request->all();
            $plans       = new PlanCollection($this->planService->index($inputs));
            $responseArr = $plans->response()->getData(true);
            $responseArr['message'] = trans('messages.plan_fetched_successfully');
            return $this->successResponse($responseArr);
        } catch (\Exception $e) {
            Log::error('plan_list_api', ['error' => $e->getMessage()]);
            report($e);
            return $this->failResponse();
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $inputs = $request->all();

        $name = $request->input('name');
        $chunkPrefix = $name .'_'.$inputs['subject_id']. '_chunk_';
        if (!$request->has('file')) {
            $combinedChunks = '';
            $sPath = storage_path('app/temp/'.$chunkPrefix);
            foreach (File::glob($sPath."*") as $chunkPath) {
                $chunkContent = file_get_contents($chunkPath);
                $combinedChunks .= $chunkContent;
                unlink($chunkPath);
            }
    
            $actualPath = 'company/' . auth()->user()->company_id . '/mdeia/subject/' . $inputs['subject_id'];
            // Upload the complete file to S3
            Storage::put("{$actualPath}/{$name}", $combinedChunks);
        } else {
            $file = $request->file('file');
            $chunk = $request->input('chunk');
            $chunkName = $chunkPrefix . $chunk;

            // Store the chunk in temporary storage
            $path = $file->storeAs('temp', $chunkName);

            File::append($path, $file->get());
        }
        return response()->json(['uploaded' => true]);
    }
    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $responseArr = [];
            $responseArr['data']    = $this->planService->show($id);
            $responseArr['message'] = trans('messages.edit_plan_data');
            return $this->successResponse($responseArr);
        } catch (\Exception $e) {
            Log::error('edit_plan_api', ['error' => $e->getMessage()]);
            report($e);
            return $this->failResponse();
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $inputs = $request->all();
        $responseArr  = [];
        $validations  = [
            'name'        => 'required',
            'price'       => 'required',
            'description' => 'required',
            'interval'    => 'required',
            'valid_from'  => 'required',
            'status'      => 'required',
        ];
        $inputs = $request->all();
        $request->validate($validations);

        try {
            $responseArr['data']    = $this->planService->update($inputs, $id);
            $responseArr['message'] = trans('messages.updated_plan_succesfully');
            return $this->successResponse($responseArr);
        } catch (\Exception $e) {
            Log::error('update_plan_api', ['error' => $e]);
            report($e);
            return $this->failResponse();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $this->planService->destroy($id);
            $responseArr['message'] = trans('messages.plan_delete_succesfully');
            return $this->successResponse($responseArr);
        } catch (\Exception $e) {
            Log::error('delete_plan_api', ['error' => $e]);
            report($e);
            return $this->failResponse();
        }
    }
}
