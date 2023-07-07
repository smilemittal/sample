<?php

namespace App\Http\Controllers\Billing;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;
use Inertia\Response;
use Validator;

class CardController extends Controller
{
    /**
     * Show the Stored Card detail.
     *
     * @param  Request  $request
     * @return Response
     */

    protected $stripe;

    public function __construct(Request $request)
    {
        $this->stripe =
            new \Stripe\StripeClient(
                config('stripe.secret')
            );
    }

    public function get(Request $request)
    {
        $user = $request->user()->company;
        try {
            $user->createOrGetStripeCustomer(['description' => 'XME Customer']);
            $intent = $user->createSetupIntent();
        } catch (Exception $e) {
            Log::error('Exception ' . $e->getMessage(), $user->toArray());
            abort(400, $e->getMessage());
        }

        return Inertia::render('Stripe/Card', [
            'card' => [
                'clientSecret' => $intent->client_secret,
                'cardLastFour' => $user->pm_last_four,
            ],
        ]);
    }

    /**
     * Update My Card details.
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    public function update(Request $request)
    {
        $this->validator($request->all())->validate();

        $user = $request->user();

        return $this->updateCard($request, $user);
    }

    /**
     * Get a validator for an incoming card update request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'payment_method' => ['required'],
        ]);
    }

    /**
     * Update Payment Method
     *
     * @param  Request  $request
     * @param  User  $user
     * @return JsonResponse
     */
    protected function updateCard(Request $request, User $user)
    {
        $company = $user->company;

        try {
            $defaultPaymentMethod = $company->defaultPaymentMethod();
            $company->updateDefaultPaymentMethod($request->payment_method);
            if (!empty($defaultPaymentMethod)) {
                $company->updateStripeCustomer([
                    'invoice_settings' => ['default_payment_method' => $defaultPaymentMethod->toArray()['id']],
                ]);
            }
            return $this->updated($request);
        } catch (Exception $e) {
            return $this->failed($e);
        }
    }

    /**
     * The user billing has been updated.
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    protected function updated(Request $request)
    {
        return response()->json(['message' => 'Card details updated successfully.',  'card_last_four' => auth()->user()->company->pm_last_four]);
    }

    /**
     * The user billing update has been failed.
     *
     * @param  Exception  $e
     * @return JsonResponse
     */
    protected function failed(Exception $e)
    {
        return response()->json([
            'errors' => [
                'payment_method' => [$e->getMessage()],
            ],
        ], 422);
    }

    public function PaymentMethod(Request $request)
    {
        $user = $request->user()->company;

        try {
            $user->createOrGetStripeCustomer(['description' => 'XME Customer']);
            $intent = $user->createSetupIntent();
        } catch (Exception $e) {
            Log::error('Exception ' . $e->getMessage(), $user->toArray());
            abort(400, $e->getMessage());
        }

        $default_source = $user->asStripeCustomer()->toArray();

        $paymentMethods = $user->paymentMethods()->toArray();

        if (!empty($paymentMethods)) {
            foreach ($paymentMethods as $key => $paymentMethod) {
                $paymentMethods[$key]['date'] = \Carbon\Carbon::parse($paymentMethod['created'])->format('d M Y');
            }
        }

        return Inertia::render('Stripe/PaymentMethod', [
            'card' => [
                'clientSecret' => $intent->client_secret,
                'cardLastFour' => $user->pm_last_four,
                'list' => !empty($paymentMethods) ? $paymentMethods : [],
                'default_source' => $default_source['invoice_settings']['default_payment_method']
            ],
        ]);
    }
}
