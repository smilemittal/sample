<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\InvoiceCollection;
use App\Http\Resources\InvoiceResource;
use App\Services\StripeService;
use App\Traits\ApiResponse;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class InvoiceController extends Controller
{
    use ApiResponse;

    private $stripeService;

    public function __construct(StripeService $stripeService = null)
    {
        $this->stripeService = $stripeService ?: new StripeService();
    }

    /**
     * Get upcoming invoices
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getUpcomingInvoice(Request $request)
    {
        $responseArr = [];
        try {
            $user = $request->user()->company;
            $subscription = $user->subscription('default');
            if ($subscription && !$subscription->cancelled()) {
                $responseArr['message'] = trans('message.invoices_fetched_successfully');
                $responseArr['data'] = new InvoiceResource($user->upcomingInvoice());
                return $this->successResponse($responseArr);
            } else {
                $responseArr['message'] = trans('message.no_record_found');
                return $this->successResponse($responseArr);
            }
        } catch (Exception $e) {
            Log::error('upcoming_invoices_api', [$e]);
            throw $e;
            $responseArr['message'] = trans('message.resource_not_found');
            return $this->failResponse($responseArr, 404);
        }
    }

    /**
     * Get payment history
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getPaymentHistory(Request $request)
    {
        try {
            $invoices = $this->stripeService->getInvoiceList($request);
            $result = new InvoiceCollection($invoices);
            $data = $result->response()->getData(true);
            
            $data['meta']['last_page'] = $invoices->last_page;
            $data['meta']['total'] = $invoices->total;
            $data['message'] = trans('message.invoices_fetched_successfully');
            return $this->successResponse($data);
        } catch (Exception $e) {
            Log::error('payment_history_api', [$e]);
            throw $e;
            $responseArr['message'] = trans('message.resource_not_found');
            return $this->failResponse($responseArr, 404);
        }
    }

    /**
     * Get default payment method
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function defaultPaymentMethod(Request $request)
    {
        $responseArr = [];
        try {
            $user = $request->user();
            $responseArr['method'] = 'card';
            $responseArr['card_last_four'] = $user->pm_last_four;
            $data['message'] = trans('message.data_fetched_successfully');
            $data['data'] = $responseArr;
            return $this->successResponse($data);
        } catch (Exception $e) {
            Log::error('payment_method_api', [$e]);
            throw $e;
            $responseArr['message'] = trans('message.resource_not_found');
            return $this->failResponse($responseArr, 404);
        }
    }
}
