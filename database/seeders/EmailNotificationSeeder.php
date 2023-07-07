<?php

namespace Database\Seeders;

use App\Models\EmailTemplate;
use Illuminate\Database\Seeder;

class EmailNotificationSeeder extends Seeder
{
    public function run()
    {
        $newUserEmailTemplateToseed = array(
            [
                'name' => 'new_user_notification',
                'subject' => 'New User Notification',
                'content' => '<h1 style="text-align:center">Welcome</h1><br><br>You are registered to.
                                <br><br>
                                <h4>Your credentials:</h4><hr>
                                <br>
                                Username: {username}
                                <br>
                                Password: {password}',
                'enable' => '1',
                'display_order' => 1,
            ],
            [
                'name' => 'password_reset_link_notification',
                'subject' => 'Password Reset Link Notification',
                'content' => '<h1>Hi {username}!</h1>
                                <br><br>
                                You are receiving this email because we received a password reset request for your account.
                                <br><br>
                                This password reset link will expire in 60 minutes.
                                <br><br>
                                <a href="{reset_password_link}" style="background:linear-gradient(to right, #FF9F24, #e08610);padding: 0.9rem 2rem;font-size: 0.875rem;color:#fff;border-radius: .2rem;display: block;text-align: center;max-width: 270px;margin: auto;" target="_blank">Click Here to reset Password</a><br><br>',
                'enable' => '1',
                'display_order' => 2
            ],
            [
                'name' => 'user_invite_notification',
                'subject' => 'User Invite Mail Notification',
                'content' => '<h1>Good news!</h1>
                                <br><br>
                                You have been invited by {company_name} to create a Multiply Me member account
                                <br><br>
                                <a href="{invite_link}" style="background:linear-gradient(to right, #FF9F24, #e08610);padding: 0.9rem 2rem;font-size: 0.875rem;color:#fff;border-radius: .2rem;display: block;text-align: center;max-width: 270px;margin: auto;" target="_blank">Create your member account</a><br><br>
                                Please do not share your invitation link with anyone    
                                ',
                'enable' => '1',
                'display_order' => 3
            ],
            // [
            //     'name' => 'user_assign_admin_privileges_notification',
            //     'subject' => 'User Assign Admin Privileges Notification',
            //     'content' => '<h1>Good news!</h1>
            //                     <br><br>
            //                     You have been granted Business Admin Privileges for company - {company_name} by {username}
            //                     <br><br>
            //                     ',
            //     'enable' => '1',
            //     'display_order' => 4
            // ],
            // [
            //     'name' => 'group_new_asset_added_notification',
            //     'subject' => 'Group New Asset Added Notification',
            //     'content' => '<h1>Hi {username}!</h1>
            //                     <br><br>
            //                     A New Asset - {asset_name} has been added to your group - {group_name}
            //                     <br><br>
            //                     ',
            //     'enable' => '1',
            //     'display_order' => 5
            // ],
            // [
            //     'name' => 'user_group_added_notification',
            //     'subject' => 'User Group Added Notification',
            //     'content' => '<h1>Hi {username}!</h1>
            //                     <br><br>
            //                     You have been added to group - {group_name}
            //                     <br><br>
            //                     ',
            //     'enable' => '1',
            //     'display_order' => 6
            // ],


        );

        foreach ($newUserEmailTemplateToseed as $record) {
            $emailTemplate = EmailTemplate::where('name', $record['name'])->first();
            if (!$emailTemplate) {
                $emailTemplate = new EmailTemplate();
                $emailTemplate->name = $record['name'];
                $emailTemplate->subject = $record['subject'];
                $emailTemplate->content = $this->formatMultilineWhiteSpace($record['content']);
                $emailTemplate->enable = $record['enable'];
                $emailTemplate->display_order = $record['display_order'];
                $emailTemplate->save();
            }
        }
    }

    public function formatMultilineWhiteSpace($content)
    {
        $new_line_delimeter = "\n";
        $new_content = '';
        $lines = explode($new_line_delimeter, $content);
        foreach ($lines as $line) {
            $new_content .= trim($line) . $new_line_delimeter;
        }
        return trim($new_content);
    }
}
