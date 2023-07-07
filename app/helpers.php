<?php

use App\Models\Coupon;
use App\Models\Plan;
use App\Models\User;

if (!function_exists('getAppLocale')) {
    function getAppLocale($request)
    {
        $locale = app()->getLocale();

        // if ($request->user()) {
        //     $locale = $request->hasHeader("X-localization") ? $request->header("X-localization") : $request->user()->getLocale();
        // } else {
            if ($request->wantsJson() || $request->ajax()) {
                $locale = $request->hasHeader("X-localization") ? $request->header("X-localization") : $locale;
            } else {
                $locale = Session::has('locale') ? session('locale') : ($request->input('locale') ? $request->input('locale') : $locale);
            }
       // }
        return $locale;
    }
}


if (!function_exists('encrypt_tech')) {
    function encrypt_tech($string)
    {
        $privateKey     = config('app.key'); // user define key
        $secretKey         = '5fgf5HJ5g27'; // user define secret key
        $encryptMethod  = config('app.cipher');

        $key = hash('sha256', $privateKey);
        $ivalue = substr(hash('sha256', $secretKey), 0, 16); // sha256 is hash_hmac_algo
        $result = openssl_encrypt($string, $encryptMethod, $key, 0, $ivalue);
        return base64_encode($result);  // output is a encripted value
    }
}
if (!function_exists('decrypt_tech')) {
    function decrypt_tech($string)
    {
        $privateKey     = config('app.key'); // user define key
        $secretKey         = '5fgf5HJ5g27'; // user define secret key
        $encryptMethod  = config('app.cipher');
        $key    = hash('sha256', $privateKey);
        $ivalue = substr(hash('sha256', $secretKey), 0, 16); // sha256 is hash_hmac_algo
        return openssl_decrypt(base64_decode($string), $encryptMethod, $key, 0, $ivalue);
    }
}

if (!function_exists('isImpersonatedSuperAdmin')) {
    function isImpersonatedSuperAdmin() {
        return in_array(auth()->user()->role->name, [User::ROLE_SUPERADMIN, User::ROLE_SITEADMIN]);
    }
}
if (!function_exists('isSuperAdmin')) {
    function isSuperAdmin() {
        return auth()->check() && in_array(auth()->user()->role->name, [User::ROLE_SUPERADMIN, User::ROLE_SITEADMIN]);
    }
}

if (!function_exists('isCompanyAdmin')) {
    function isCompanyAdmin() {
        return auth()->check() && in_array(auth()->user()->role->name, [User::ROLE_COMPANY, User::ROLE_BUSINESSADMIN]);
    }
}

if (!function_exists('isMember')) {
    function isMember() {
        return auth()->check() && in_array(auth()->user()->role->name, [User::ROLE_EMPLOYEE]);
    }
}

if (!function_exists('isBusinessAdmin')) {
    function isBusinessAdmin() {
        return auth()->check() && in_array(auth()->user()->role->name, [User::ROLE_BUSINESSADMIN]);
    }
}

if (!function_exists('getAppVersion')) {
    function getAppVersion()
    {
        try {
            $path = base_path().'/version';
            $myfile = fopen($path, "r") or die("Unable to open version file!");
            $version = fread($myfile, filesize($path));
            fclose($myfile);
            return $version;
        } catch (Exception $e) {
            return config('app.version');
        }
    }
}

if (!function_exists('companyEmails')) {
    function companyEmails()
    {
        try {
            $usersArr = [];
            $users = User::select('email')->where('company_id',auth()->user()->company_id)->get();
            foreach($users as $user){
                $usersArr[] = $user->email;
            }
            return $usersArr;
        } catch (Exception $e) {
            return config('app.version');
        }
    }
}


if (!function_exists('freePlan')) {
    /**
     * Get Free Plan
     *
     * @return Plan
     */
    function freePlan()
    {
        return Plan::where('type', 'free')->first();
    }
}

if (!function_exists('getDefaultCoupon')) {

    function getDefaultCoupon()
    {
        $response = [];

        $couponObject = [
            'name' =>  null,
            'coupon_code' => null,
            'discount_value' =>  null,
        ];

        if (env('COUPON_ID', null)) {
            $coupon = Coupon::select(['coupon_code','name','discount_type','discount_value','duration_type','duration_in_months','trial_duration_in_months'])->where('id', env('COUPON_ID', 0))->first();
            if (!empty($coupon)) {
                $coupon['text'] = $coupon->description_text;
                $couponObject = $coupon;
            }
        }
        $response['coupon'] = $couponObject;
        $response['coupon_banner_text'] = env('COUPON_ID', null) ? env('COUPON_TEXT', null) : null;
        $response['coupon_only_for_free'] = env('COUPON_SHOW_FREE', false);
        return $response;
    }
}