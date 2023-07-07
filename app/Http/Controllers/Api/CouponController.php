<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Traits\ApiResponse;

class CouponController extends Controller
{
    use ApiResponse;

    public function validateCoupon(Request $request)
    {
        $responseArr = [];

        $vldtrRules = [
            'coupon_code' => 'required',
        ];

        $vldtrMessages = [
        ];

        $request->validate($vldtrRules, $vldtrMessages);
        $coupon = Coupon::select(['id', 'coupon_code', 'name', 'discount_type', 'discount_value', 'duration_type', 'duration_in_months', 'plan_id', 'trial_duration_in_months'])->where('coupon_code', $request->coupon_code)->first();

        $user = $request->user();
        if (
            $coupon &&
            $coupon->coupon_code == env('UNLOCK_PROGRAM_COUPON_CODE') &&
            $user->activePlan->id == env('UNLOCK_PROGRAM_SOURCE_PLAN_ID') &&
            $user->periodicDiscounts()->where('coupon_id', $coupon->id)->where('validate_upto', '<=', Carbon::now())->first()
        ) {
            $responseArr['message'] = trans('message.invalid_coupon_code');
            return $this->failResponse($responseArr, 403);
        }

        if (! empty($coupon)) {
            $coupon['text'] = $coupon->description_text;
        
            $plans = $coupon->plans;

            $isMonthlyPlanPresent = $plans->where('interval', 1)->count();
            $isYearlyPlanPresent = $plans->where('interval', 2)->count();
            
            $interval = 1;
            if ($isMonthlyPlanPresent && $isYearlyPlanPresent) {
                $interval = null;
            } else if ($isYearlyPlanPresent) {
                $interval = 2;
            }
            
            $coupon['plan_ids'] = $plans->pluck('stripe_plan_id')->toArray();
            $coupon['plan_names'] = $plans->pluck('name')->toArray();
            $coupon['interval'] = $interval;
            $responseArr['coupon'] = $coupon;
            $coupon['allow_woc'] = false;
            if (($coupon->discount_type == 'percentage' && $coupon->discount_value == 100) || $coupon->trial_duration_in_months) {
                $coupon['allow_woc'] = true;
            }
            if ($interval == 2) {
                $coupon['text'] = $coupon->description_text_yearly;
            }

            $responseArr['message'] = trans('message.coupon_applied_successfully');
            $responseArr['data'] = $coupon;
            return $this->successResponse($responseArr);
        }

        $responseArr['message'] = trans('message.invalid_coupon_code');
        return $this->failResponse($responseArr, 404);

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Coupon  $coupon
     * @return \Illuminate\Http\Response
     */
    public function show(Coupon $coupon)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Coupon  $coupon
     * @return \Illuminate\Http\Response
     */
    public function edit(Coupon $coupon)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Coupon  $coupon
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Coupon $coupon)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Coupon  $coupon
     * @return \Illuminate\Http\Response
     */
    public function destroy(Coupon $coupon)
    {
        //
    }
}
