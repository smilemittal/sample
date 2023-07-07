<?php

namespace App\Providers;

use App\Models\Company;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use Laravel\Cashier\Cashier;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->register('App\Providers\MailConfigServiceProvider');
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Cashier::useCustomerModel(Company::class);
        $this->registerValidators();
    }

    /**
     * Register the Validators.
     *
     * @return void
     */
    protected function registerValidators()
    {
        $validators = [
            'not_archived' => 'NotArchivedPlan',
            'update_plan' => 'UpdatePlan',
            'downgrade_plan' => 'DowngradePlan',
        ];

        foreach ($validators as $key => $value) {
            Validator::extend(
                $key,
                'App\\Validators\\'.$value.'@validate'
            );
        }
    }
}
