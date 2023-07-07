<?php

namespace App\Http\Controllers\Billing;

use App\Http\Controllers\Controller;
use App\Services\SalesHandyService;
use App\Traits\CompanyAuthorization;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Inertia\Inertia;
use Inertia\Response;
use Validator;

class CancelSubscriptionController extends Controller
{
    use CompanyAuthorization;

    /**
     * Cancel current Subscription flow
     *
     * @param  Request  $request
     * @return Response
     */
    public function cancelFlow(Request $request)
    {
        $user = $request->user()->company;
        $activePlan = $user->activePlan();
        $endsAt = null;
        $activeSubscription = false;

        if (! empty($activePlan)) {
            $subscription = $user->subscription('default');
            $stripeSubscription = $subscription->asStripeSubscription();
            $activeSubscription = $subscription && ! $subscription->cancelled();
            if (! empty($stripeSubscription)) {
                $timestamp = $stripeSubscription->current_period_end;
                $endsAt = Carbon::createFromTimeStamp($timestamp)->toFormattedDateString();
            }
        }

        return Inertia::render('Stripe/SubscriptionCancel', compact('activePlan', 'endsAt', 'activeSubscription'));
    }

    /**
     * Cancel current Subscription
     *
     * @param  Request  $request
     * @return View|RedirectResponse
     */
    public function destroy(Request $request)
    {
        $user = $request->user();
        $companyUsed = $this->getComapnyAuth()['companyUsed'];

        $this->validator($request->all())->validate();

        $plan = freePlan();
        $planCanDowngrade = [];
        // if the user is going to cancel the plan.
        $planDesc = $plan->description;
   
        foreach ($planDesc as $key => $value) {
            if (array_key_exists($key, $companyUsed) && array_key_exists($key, $planDesc)) {
                $planCanDowngrade[$key] = $planDesc[$key] - $companyUsed[$key];
            }
        }

        $planCanDowngrade = array_filter($planCanDowngrade, function ($i) {
            return $i < 0;
        });

        if (count($planCanDowngrade)) {
            $keyReason = Str::ucfirst(Str::replace('_', ' ', array_key_first($planCanDowngrade)));
            abort(403, 'You can\'t cancel the plan. You have to delete or adjust '.$keyReason.' for cancel the plan.');
        }
     
        try {
            $user->company->subscription('default')->cancel();
        } catch (Exception $e) {
            return $this->sendResetFailedResponse($request, $e->getMessage());
        }

        $endsAt = null;
        if ($user->company->subscription('default')->onGracePeriod()) {
            $endsAt = Carbon::now()->diffInSeconds(auth()->user()->company->subscription('default')->ends_at);
        }
        
        // $salesHandyService = new SalesHandyService($user->first_name, $user->last_name, $user->email);
        // $salesHandyService->cancelSubscription();
        // @Todo Send mail if required add $reason and $other(other reason if any)

        return $this->sendResetResponse($request, $endsAt);
    }

    /**
     * Get a validator for an incoming subscription update request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'reason' => ['required', 'string', 'min:2', 'max:1125'],
            'other' => ['nullable', 'string', 'min:2', 'max:1125'],
        ]);
    }

    /**
     * Get the response for a failed subscription cancellation.
     *
     * @param  Request  $request
     * @param  string  $response
     * @return RedirectResponse|JsonResponse
     */
    protected function sendResetFailedResponse(Request $request, $response)
    {
        return response()->json(['cancel' => $response], 400);
    }

    /**
     * Get the response for a successful subscription cancellation.
     *
     * @param  Request  $request
     * @param $endsAt
     * @return RedirectResponse|JsonResponse
     */
    protected function sendResetResponse(Request $request, $endsAt)
    {
        return response()->json(['message' => 'Subscription cancelled successfully.', 'endsAt' => $endsAt]);
    }
}
