<?php

namespace App\Services;

use App\Jobs\XmeActionLog;
use App\Models\Plan;
use Illuminate\Support\Facades\DB;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Stripe\Price;
use Stripe\Stripe;

class PlanService
{

    public function index($inputs)
    {
        try {
            $plans =  Plan::orderBy($inputs['sortField'], $inputs['orderDir']);
            $text = '';
            if (isset($inputs['search'])) {
                $search = trim($inputs['search']);
                $groups = $plans->where(function ($query) use ($search) {
                    $query->where('name', 'LIKE', "%{$search}%");
                });
                $text = 'searched plans by "' . $search . '"';

                // $this->updatedSearch($search);
            }
            $groups = $plans->paginate(10);
            /**activity logs */
            $pageName = 'on Subscription Plans page.';

            if (!empty($text)) {
               $this->updateAllFilterLog($text, $pageName);
           }
            return $groups;
        } catch (\Exception | RequestException $e) {
            Log::error('plan_list_service', ['error' => $e->getMessage()]);
            throw $e;
        }
    }


    public  function updatedSearch($search)
    {
        if ($search != '') {

            dispatch(
                new XmeActionLog(
                    auth()->user(),
                    'search',
                    '{user} searched plans by "' . $search . '" on plan  page',
                    null,
                )
            );
        }
    }


    public function store($input)
    {
        try {
            DB::beginTransaction();
            $features =  Arr::except($input, [
                'type',
                'name',
                'price',
                'description',
                'addons_description',
                'stripe_plan_id',
                'stripe_product_id',
                'stripe_addon_plan_id',
                'interval',
                'base_plan_id',
                'user_id',
                'valid_from',
                'valid_to',
                'status'
            ]);
            $data =  array(
                'name'        => $input['name'],
                'type'        =>  $input['type'] ?? Str::slug($input['name']),
                'price'       =>  $input['price'],
                'valid_from'  =>  $input['valid_from'],
                'valid_to'    =>  $input['valid_to'] ?? null,
                'interval'    =>  $input['interval'],
                'status'      =>  $input['status'] ?? 1,
                'description' => json_encode($features)
            );
            $plan =  Plan::create($data);
            if ($plan->price > 0) {
                Stripe::setApiKey(env('STRIPE_SECRET'));

                $stripePrice = Price::create(
                    [
                        'currency' => env('CASHIER_CURRENCY'),
                        'unit_amount' => $input['price'] * 100,
                        'recurring[interval]' => $input['interval'] == 1 ? 'month' : 'year',
                        'product' => (empty($input['user_id']) ? env('STRIPE_BASE_PROD_ID') : env('STRIPE_CUSTOM_PROD_ID')),
                    ]
                );
                $plan->stripe_plan_id = $stripePrice->id;
                $plan->stripe_product_id = $stripePrice->product;
                $plan->save();
            }
            if (auth()->check()) {
                dispatch(new XmeActionLog(
                    auth()->user(),
                    'store',
                    '{user} created plan "{model}"',
                    $plan,
                ));
            }
            DB::commit();
            return $plan;
        } catch (\Exception | RequestException $e) {
            DB::rollback();
            Log::error('plan_store_service', ['error' => $e->getMessage()]);
            throw $e;
        }
    }


    public function show($id)
    {
        try {
            return Plan::find($id);
        } catch (\Exception | RequestException $e) {
            Log::error('plan_edit_service', ['error' => $e->getMessage()]);
            throw $e;
        }
    }


    public function update($inputs, $id)
    {


        try {
            DB::beginTransaction();

            $members =  request()->except(
                'type',
                'name',
                'price',
                'description',
                'addons_description',
                'stripe_plan_id',
                'stripe_product_id',
                'stripe_addon_plan_id',
                'interval',
                'base_plan_id',
                'user_id',
                'valid_from',
                'valid_to',
                'status'
            );
            $plan =  Plan::find($id);
            $plan->name        = $inputs['name'];
            $plan->type        = $inputs['type'];
            $plan->price       = $inputs['price'];
            $plan->valid_from  = $inputs['valid_from'];
            $plan->valid_to    = $inputs['valid_to'];
            $plan->interval    = $inputs['status'];
            $plan->description = json_encode($members);
            $plan->save();
            dispatch(new XmeActionLog(
                auth()->user(),
                'update',
                '{user} updated plan "{model}"',
                $plan,
            ));
            DB::commit();
            return $plan;
        } catch (\Exception | RequestException $e) {
            DB::rollback();
            Log::error('plan_update_service', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function destroy($id)
    {
        try {
            $plan = Plan::find($id)->delete();
            dispatch(new XmeActionLog(
                auth()->user(),
                'delete',
                '{user} delete ' . $plan->name . ' plan "{model}"',
                $plan,
            ));
            return $plan;
        } catch (\Exception | RequestException $e) {
            Log::error('plan_destroy_service', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function updateAllFilterLog($text, $pageName)
    {
        dispatch(
            new XmeActionLog(
                auth()->user(),
                'search',
                '{user} ' . $text . ' ' . $pageName . '',
                null,
            )
        );
    }
}
