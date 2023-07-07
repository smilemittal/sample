<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\ApiResponse;
use App\Services\CompanyService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\CompanyCollection;
use App\Http\Resources\MenuItemCollection;
use App\Models\Company;
use App\Models\Coupon;
use App\Services\MenuService;
use App\Traits\CompanyAuthorization;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    use ApiResponse;
    protected $companyService;
    use CompanyAuthorization;

    public function __construct(CompanyService $companyService = null)
    {
        $this->companyService = $companyService ?? new CompanyService();
    }

    /**
     * Get the Authorization of user.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAuthorization(Request $request)
    {
        try {
            $responseArr = [];
            $responseArr['data']    = $this->getComapnyAuth();
            $responseArr['message'] = trans('messages.data_retrieved');
            return $this->successResponse($responseArr);
        } catch (\Exception $e) {
            Log::error('edit_company_detail_api', ['error' => $e->getMessage()]);
            report($e);
            return $this->failResponse();
        }
    }

    public function index(Request $request)
    {
        try {
            $responseArr  = [];
            $inputs = $request->all();
            $companies =  new CompanyCollection($this->companyService->getList($inputs));
            $responseArr = $companies->response()->getData(true);
            $responseArr['message'] = trans('messages.companies_detail_fetched_successfully');
            return $this->successResponse($responseArr);
        } catch (\Exception $e) {
            Log::error('List_Company_Api', ['error' => $e]);
            report($e);
            return $this->failResponse();
        }
        //
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
        $responseArr = [];
        $validations  = [
            'name' => ['required','unique:companies,name'],
            'phone_nr' => 'required',
            'abn' => ['required','unique:companies,abn'],
            'street' => 'required',
            'city' => 'required',
            'state' => 'required',
            'postcode' => 'required',
            'website' => 'nullable',
            'phone_nr' => 'nullable|numeric',
            'email' => 'nullable',
            'years_trading' => 'nullable|numeric',
        ];
        $inputs = $request->all();
        $request->validate($validations);
        try {
            $responseArr['data'] = $this->companyService->store($inputs);
            $responseArr['message'] = trans('messages.company_created_succesfully');
            return $this->successResponse($responseArr);
        } catch (\Exception $e) {
            Log::error('Store_Company_Api', ['error' => $e]);
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
            $responseArr = [];
            $responseArr['data']    = $this->companyService->edit($id);
            $responseArr['message'] = trans('messages.edit_company_data');
            return $this->successResponse($responseArr);
        } catch (\Exception $e) {
            Log::error('edit_company_detail_api', ['error' => $e->getMessage()]);
            report($e);
            return $this->failResponse();
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $responseArr = [];
        $validations  = [
            'name' => 'required|unique:companies,name,'.$id,
            'phone_nr' => 'required',
            'abn' => 'required|unique:companies,abn,'.$id,
            'street' => 'required',
            'city' => 'required',
            'state' => 'required',
            'postcode' => 'required',
            'website' => 'nullable',
            'phone_nr' => 'nullable|numeric',
            'email' => 'nullable',
            'years_trading' => 'nullable|numeric',
        ];
        
        $inputs = $request->all();
        $request->validate($validations);

        try {
            $inputs = $request->all();
            $responseArr['data'] = $this->companyService->updateData($inputs, $id);
            $responseArr['message'] = trans('messages.companies_detail_updated_successfully');
            return $this->successResponse($responseArr);
        } catch (\Exception $e) {
            Log::error('Update_Company_Api', ['error' => $e]);
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
        try {
            $responseArr = [];
            $this->companyService->destroy($id);
            $responseArr['message'] = trans('messages.deleted_the_company_data_successfully');
            return $this->successResponse($responseArr);
        } catch (\Exception $e) {
            Log::error('edit_company_detail_api', ['error' => $e->getMessage()]);
            report($e);
            return $this->failResponse();
        }
    }

    // perform modal actions
    public function actionAfterConfirmation(Request $request)
    {

        try {
            $responseArr = [];
            $inputs = $request->all();
            $response = $this->companyService->actionAfterConfirmation($inputs);
            $responseArr['data'] = $response[0];
            $responseArr['message'] =  $response[1];
            return $this->successResponse($responseArr);
        } catch (\Exception $e) {
            Log::error('action_after_confirmation_company_api', ['error' => $e->getMessage()]);
            report($e);
            return $this->failResponse();
        }
    }

    public  function deleteImage($companyId)
    {
        try {
            $responseArr = [];
            $responseArr['data']  = $this->companyService->deleteImage($companyId);
            $responseArr['message'] = trans('messages.deleted_the_company_logo_successfully');
            return $this->successResponse($responseArr);
        } catch (\Exception $e) {
            Log::error('delete_company_logo_api', ['error' => $e->getMessage()]);
            report($e);
            return $this->failResponse();
        }
    }

    public function companyAction(Request $request)
    {
        try {
            $inputs = $request->all();
            $responseArr = [];
            $results = $this->companyService->companyAction($inputs);
            $responseArr['data'] =  $results[0];
            $responseArr['message'] = $results[1];
            return $this->successResponse($responseArr);
        } catch (\Exception $e) {
            Log::error('company_action_api', ['error' => $e->getMessage()]);
            report($e);
            return $this->failResponse();
        }
    }

    public function multipleAction(Request $request)
    {
        try {
            $inputs = $request->all();
            if ($inputs['multipleCompanies'] == null) {
                $responseArr['errors'] = ['multipleCompanies' =>
                [trans('messages.please select the values')]];
                return $this->failResponse($responseArr, 422);
            }
            $responseArr = [];
            $results = $this->companyService->multipleAction($inputs);
            $responseArr['data'] =  $results[0];
            $responseArr['message'] = $results[1];
            return $this->successResponse($responseArr);
        } catch (\Exception $e) {
            Log::error('multiple_company_action_api', ['error' => $e->getMessage()]);
            report($e);
            return $this->failResponse();
        }
    }

    /**
     * Display the specified resource.
     */
    public function profile()
    {
        try {
            $id = auth()->user()->company_id;
            $responseArr = [];
            $responseArr['data']    = $this->companyService->edit($id);
            $responseArr['message'] = trans('messages.edit_company_data');
            return $this->successResponse($responseArr);
        } catch (\Exception $e) {
            Log::error('edit_company_detail_api', ['error' => $e->getMessage()]);
            report($e);
            return $this->failResponse();
        }
    }

    public function initialData(Request $request)
    {
        try {
            // $versionController  = new VersionController();
            // $authController = new AuthController();
            $menuService = new MenuService();
            $menuitems =  new MenuItemCollection($menuService->list());
            $responseArr['data']['menu'] = $menuitems->response()->getData(true);
            $responseArr['data']['default_coupon'] = getDefaultCoupon();
            return $this->successResponse($responseArr);
        } catch (\Exception $e) {
            $responseArr['message'] = $e->getMessage();
            return $this->failResponse($responseArr, 500);
        }
    }

    public function getDefaultCoupon(Request $request)
    {
        return getDefaultCoupon();
    }
}
