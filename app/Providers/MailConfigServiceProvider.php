<?php

namespace App\Providers;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;
use App\Models\Setting;
use Exception;

class MailConfigServiceProvider extends ServiceProvider
{
     /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        try {
            $smtp_settings = Setting::get_value('smtp_settings');

            $configuration = json_decode($smtp_settings);

            if (!is_null($configuration)) {
                $config = array(
                    'driver'     =>     'smtp',
                    'host'       =>     $configuration->host,
                    'port'       =>     $configuration->port,
                    'username'   =>     $configuration->username,
                    'password'   =>     $configuration->password,
                    'encryption' =>     $configuration->encryption,
                    'from'       =>     array('address' => $configuration->from_address, 'name' => $configuration->from_name),
                );
                Config::set('mail', $config);
            }
        } catch (Exception $ex) {
            report($ex);
        }
    }
}
