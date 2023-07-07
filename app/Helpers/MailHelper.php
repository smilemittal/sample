<?php


namespace App\Helpers;

use App\Mail\EmailFormat;
use Illuminate\Support\Facades\Mail;
use App\Models\Setting;

class MailHelper
{
    public static function sendMail($to, $content, $subject, $header, $footer)
    {
        $smtpSettings = json_decode(Setting::get_value('smtp_settings'), true);
        Mail::to($to)
            ->send(new EmailFormat(
                $content,
                $subject,
                $header,
                $footer,
                $smtpSettings['from_address'],
                $smtpSettings['from_name']
            ));
    }
}
