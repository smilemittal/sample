<?php

namespace App\Http\Controllers\Billing;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ResumeSubscriptionController extends Controller
{
    /**
     * Resume current Subscription flow
     *
     * @param  Request  $request
     * @return Response
     */
    public function resume(Request $request)
    {
        $user = $request->user()->company;
        $endsAt = null;
        $subscription = $user->subscription('default');
        if ($subscription->onGracePeriod()) {
            $endsAt = $subscription->ends_at->diffForHumans();
        }

        return Inertia::render('Stripe/SubscriptionResume', compact('endsAt'));
    }

    /**
     * Resume current Subscription
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    public function update(Request $request)
    {
        $user = $request->user()->company;

        try {
            $user->subscription('default')->resume();
        } catch (Exception $e) {
            return $this->sendResetFailedResponse($request, $e->getMessage());
        }

        return $this->sendResetResponse($request);
    }

    /**
     * Get the response for a failed subscription resume operation.
     *
     * @param  Request  $request
     * @param  string  $response
     * @return JsonResponse
     */
    protected function sendResetFailedResponse(Request $request, $response)
    {
        return response()->json(['resume' => $response]);
    }

    /**
     * Get the response for a successful subscription resume.
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    protected function sendResetResponse(Request $request)
    {
        return response()->json(['message' => 'Subscription got resumed!']);
    }
}
