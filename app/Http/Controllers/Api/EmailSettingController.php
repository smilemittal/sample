<?php

namespace App\Http\Controllers\Api;

use App\Helpers\MailHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\EmailSettingCollection;
use App\Mail\EmailFormat;
use App\Models\EmailTemplate;
use App\Services\EmailSettingService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Transport;

class EmailSettingController extends Controller
{

    use ApiResponse;
    protected $emailSettingService;

    public function __construct(EmailSettingService $emailSettingService = null)
    {
        $this->emailSettingService = $emailSettingService ?? new EmailSettingService();
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function emailSettings()
    {
        try {
            $responseArr = [];
            $responseArr['data']    = $this->emailSettingService->emailSettings();
            $responseArr['message'] = trans('messages.edit_email_setting_data');
            return $this->successResponse($responseArr);
        } catch (\Exception $e) {
            Log::error('Edit_Email_Setting_Api', ['error' => $e->getMessage()]);
            report($e);
            return $this->failResponse();
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function emailSettingsUpdate(Request $request)
    {
        try {
            $responseArr = [];
            $inputs = $request->all();
            $responseArr['data']    = $this->emailSettingService->emailSettingsUpdate($inputs);
            $responseArr['message'] = trans('messages.updated_email_settings_succesfully');
            return $this->successResponse($responseArr);
        } catch (\Exception $e) {
            Log::error('Updated_Email_Settings_Api', ['error' => $e]);
            report($e);
            return $this->failResponse();
        }
    }

    public function emailLayoutSettingsUpdate(Request $request)
    {
        try {
            $responseArr = [];
            $inputs = $request->all();
            $responseArr['data']    = $this->emailSettingService->emailLayoutSettingsUpdate($inputs);
            $responseArr['message'] = trans('messages.updated_email_layout_settings_succesfully');
            return $this->successResponse($responseArr);
        } catch (\Exception $e) {
            Log::error('Updated_Email_Settings_Api', ['error' => $e]);
            report($e);
            return $this->failResponse();
        }
    }

    public function sendTestEmail(Request $request)
    {

        $responseArr = [];
        try {
            $smtp_settings = [
                'host' => $request->host,
                'port' => $request->port,
                'encryption' => $request->encryption,
                'username' => $request->username,
                'password' => $request->password,
                'from_address' => $request->from_address,
                'from_name' => $request->from_name
            ];

            $email_data = [
                'to' => $request->email,
                'content' => $request->message,
                'subject' => $request->subject,
                'header' => EmailTemplate::get_header(),
                'footer' => EmailTemplate::get_footer()
            ];
            $mailer = app(Mailer::class);
            $currentTransport = $mailer->getSymfonyTransport();
            $mailer->setSymfonyTransport(
                Transport::fromDsn("smtp://{$smtp_settings['username']}:{$smtp_settings['password']}@{$smtp_settings['host']}:{$smtp_settings['port']}")
            );
            $mailer->send(new EmailFormat(
                $email_data['content'],
                $email_data['subject'],
                $email_data['header'],
                $email_data['footer'],
                $smtp_settings['from_address'],
                $smtp_settings['from_name']
            ));
            $mailer->setSymfonyTransport($currentTransport);
            $responseArr['message'] = trans('messages.Email Sent Successfully Please Verify It In Your Inbox Before Saving This Form.');
            return $this->successResponse($responseArr);
        } catch (\Exception $e) {
            Log::error('sendTestEmail_api', ['error' => $e]);
            report($e);
            return $this->failResponse();
        }
    }
}
