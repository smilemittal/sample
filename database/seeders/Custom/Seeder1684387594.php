<?php

namespace Database\Seeders\Custom;

use App\Models\EmailTemplate;
use Illuminate\Database\Seeder;

class Seeder1684387594 extends Seeder
{
    public function run()
    {
        $newUserEmailTemplateToseed = array(

            [
                'name' => 'user_module_expiry_notification',
                'subject' => 'Module_Expiry Notification',
                'content' => '<h1>Hi {username}!</h1>
                                <br><br>
                                <b> {by_username} </b>
                                your
                                <b>{module_name} </b>
                                module will expiry in
                                <b> {days}</b> days
                                <br><br>
                                ',
                'enable' => '1',
                'display_order' => 41,
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