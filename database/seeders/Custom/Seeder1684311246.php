<?php

namespace Database\Seeders\Custom;

use App\Models\EmailTemplate;
use Illuminate\Database\Seeder;

class Seeder1684311246 extends Seeder
{
    public function run()
    {
        $newUserEmailTemplateToseed = array(
            [
                'name'    => 'user_review_reminder_notification',
                'subject' => 'Review Reminder Notification',
                'content' => '<h1>Hi {username}!</h1>
                                <br><br>
                                your <b>{module_name} </b> module is coming
                                for review after {days} days. Thank you for being so thorough.
                                <br><br>
                                However, youre behind on {phase of the project}.
                                It was due <b>{days}</b> days,
                                and we need it finished as soon as possible
                                so we can do necessasry updates on your module ,
                                if required.
                                <br><br>
                                If youre having any difficulties with completing
                                this stage, be sure to contact on Support.
                                We will be happy to answer any questions you may have.
                                <br><br>
                                Thank you for your prompt attention to this matter.
                                <br><br>

                                ',
                'enable' => '1',
                'display_order' => 36,
            ],
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