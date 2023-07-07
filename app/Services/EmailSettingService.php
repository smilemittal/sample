<?php

namespace App\Services;

use Illuminate\Support\Facades\Request;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;
use App\Models\Setting;
use Illuminate\Support\Facades\DB;
use App\Jobs\XmeActionLog;
use Illuminate\Support\Facades\Storage;

class EmailSettingService
{
    public function emailSettings()
    {
        try {
            $data = [];
            $data['email_header_footer_color'] = Setting::get_value('email_header_footer_color');
            $data['email_header_image']        = Setting::get_value('email_header_image');
            $data['email_header_text_size']    = Setting::get_value('email_header_text_size');
            $data['email_body_text_size']      = Setting::get_value('email_body_text_size');
            $data['email_header_text']         = Setting::get_value('email_header_text');
            $data['email_footer_text']         = Setting::get_value('email_footer_text');
            $smtpSettings = Setting::get_value('smtp_settings');
            $data['smtp_settings'] = $smtpSettings ? json_decode($smtpSettings, 1) : [];

            return $data;
        } catch (\Exception | RequestException $e) {
            Log::error('email_setting_edit_service', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function emailSettingsUpdate($inputs)
    {
        try {
            DB::beginTransaction();
            $smtpSettings = [
                'host'          => $inputs['host'],
                'port'          => $inputs['port'],
                'username'      => $inputs['username'],
                'password'      => $inputs['password'],
                'from_address'  => $inputs['from_address'],
                'from_name'     => $inputs['from_name'],
                'encryption'    => $inputs['encryption'],
            ];
            Setting::update_value('smtp_settings', json_encode($smtpSettings));
            DB::commit();
            dispatch(new XmeActionLog(
                auth()->user(),
                'update',
                '{user} updated email  settings',
                null
            ));
            return $smtpSettings;
        } catch (\Exception | RequestException $e) {
            DB::rollback();
            Log::error('email_setting_update_service', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function emailLayoutSettingsUpdate($inputs)
    {
        try {
            DB::beginTransaction();
            if (array_key_exists('file', $inputs)) {
                $file = $inputs['file'];
                $file->store('email_header_image');
                Setting::update_value('email_header_image', 'email_header_image/' . $file->hashName());
                if (array_key_exists('emailHeaderImage', $inputs)) {
                    Storage::delete($inputs['emailHeaderImage']);
                }
            }
            Setting::update_value('email_header_footer_color', null);
            Setting::update_value('email_header_text_size', (string)$inputs['headerTextSize']);
            Setting::update_value('email_body_text_size', (string)$inputs['bodyTextSize']);
            Setting::update_value('email_header_text', (string)$inputs['headerText']);
            Setting::update_value('email_footer_text', (string)$inputs['footerText']);
            dispatch(new XmeActionLog(
                auth()->user(),
                'update',
                '{user} updated email templates settings',
                null
            ));
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('email_setting_layout_update_service', ['error' => $e->getMessage()]);
            throw $e;
        }
    }
}
