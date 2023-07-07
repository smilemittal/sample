<?php

namespace Database\Seeders\Custom;

use App\Models\EmailTemplate;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Sabberworm\CSS\Settings;

class Seeder1683023316 extends Seeder
{
    public function run()
    {
        
        $newUserEmailTemplateToseed = array(
            
            [
                'name' => 'user_created_learning_path_notification',
                'subject' => 'Create Learning Path Notification',
                'content' => '<h1>Hi {username}!</h1>
                                <br><br>
                                <b> {by_username} </b>
                                has been created new <b>{learning_path_title} </b>
                                learning path
                                <br><br>
                                ',
                'enable' => '1',
                'display_order' => 21,
            ],

            [
                'name' => 'user_updated_learning_path_notification',
                'subject' => 'Update Learning Path Notification',
                'content' => '<h1>Hi {username}!</h1>
                                <br><br>
                                <b> {by_username} </b>
                                has been updated <b>{learning_path_title} </b>
                                learning path
                                <br><br>
                                ',
                'enable' => '1',
                'display_order' => 22,
            ],

            [
                'name' => 'user_locked_learning_path_notification',
                'subject' => 'Lock Learning Path Notification',
                'content' => '<h1>Hi {username}!</h1>
                                <br><br>
                                <b> {by_username} </b>
                                has Locked <b>{learning_path_title} </b>
                                learning path
                                <br><br>
                                ',
                'enable' => '1',
                'display_order' => 23,
            ],

            [
                'name' => 'user_deleted_learning_path_notification',
                'subject' => 'Delete Learning Path Notification',
                'content' => '<h1>Hi {username}!</h1>
                                <br><br>
                                <b> {by_username} </b>
                                has delete <b>{learning_path_title} </b>
                                learning path
                                <br><br>
                                ',
                'enable' => '1',
                'display_order' => 24,
            ],

            [
                'name' => 'user_add_module_to_learning_path_notification',
                'subject' => 'Add Module To Learning Path Notification',
                'content' => '<h1>Hi {username}!</h1>
                                <br><br>
                                <b> {by_username} </b>
                                has Added <b>{module_name} </b> module in
                                <b>{learning_path_title} </b>
                                learning path
                                <br><br>
                                ',
                'enable' => '1',
                'display_order' => 25,
            ],

            [
                'name' => 'user_remove_learning_path_module_notification',
                'subject' => 'Remove Learning Path Module Notification',
                'content' => '<h1>Hi {username}!</h1>
                                <br><br>
                                <b> {by_username} </b>
                                has removed <b>{module_name} </b> module from
                                <b>{learning_path_title} </b>
                                learning path
                                <br><br>
                                ',
                'enable' => '1',
                'display_order' => 26,
            ],

            [
                'name' => 'user_add_member_to_learning_path_notification',
                'subject' => 'Add Member Learning Path Notification',
                'content' => '<h1>Hi {username}!</h1>
                                <br><br>
                                <b> {by_username} </b>
                                has Added <b>{member_name} </b> member to
                                <b>{learning_path_title} </b>
                                learning path
                                <br><br>
                                ',
                'enable' => '1',
                'display_order' => 27,
            ],

            [
                'name' => 'user_remove_learning_path_member_notification',
                'subject' => 'Remove Learning Path Member Notification',
                'content' => '<h1>Hi {username}!</h1>
                                <br><br>
                                <b> {by_username} </b>
                                has remove <b>{member_name} </b> member from
                                <b>{learning_path_title} </b>
                                learning path
                                <br><br>
                                ',
                'enable' => '1',
                'display_order' => 28,
            ],

            [
                'name' => 'user_complete_learning_path_module_notification',
                'subject' => 'Complete Learning Path Module Notification',
                'content' => '<h1>Hi {username}!</h1>
                                <br><br>
                                <b> {by_username} </b>
                                has completed <b>{module_name} </b> module from
                                <b>{learning_path_title} </b>
                                learning path
                                <br><br>
                                ',
                'enable' => '1',
                'display_order' => 29,
            ],

            [
                'name' => 'user_archive_learning_path_notification',
                'subject' => 'Archive Learning Path Notification',
                'content' => '<h1>Hi {username}!</h1>
                                <br><br>
                                <b> {by_username} </b>
                                has  Archive
                                <b>{learning_path_title} </b>
                                learning path
                                <br><br>
                                ',
                'enable' => '1',
                'display_order' => 30,
            ],

            [
                'name' => 'user_unarchive_learning_path_notification',
                'subject' => 'Unarchive Learning Path Notification',
                'content' => '<h1>Hi {username}!</h1>
                                <br><br>
                                <b> {by_username} </b>
                                has  unarchive
                                <b>{learning_path_title} </b>
                                learning path
                                <br><br>
                                ',
                'enable' => '1',
                'display_order' => 31,
            ],

            [
                'name' => 'user_restore_learning_path_notification',
                'subject' => 'Restore Learning Path Notification',
                'content' => '<h1>Hi {username}!</h1>
                                <br><br>
                                <b> {by_username} </b>
                                has restore
                                <b>{learning_path_title} </b>
                                learning path
                                <br><br>
                                ',
                'enable' => '1',
                'display_order' => 32,
            ],

            [
                'name' => 'user_duplicate_learning_path_notification',
                'subject' => 'Duplicate Learning Path Notification',
                'content' => '<h1>Hi {username}!</h1>
                                <br><br>
                                <b> {by_username} </b>
                                has duplicate
                                <b>{learning_path_title} </b>
                                learning path
                                <br><br>
                                ',
                'enable' => '1',
                'display_order' => 33,
            ],

            [
                'name' => 'user_update_review_module_notification',
                'subject' => 'Update Review Module Notification',
                'content' => '<h1>Hi {username}!</h1>
                                <br><br>
                                <b> {by_username} </b>
                                has update
                                <b>{module_name} </b>
                                review module
                                <br><br>
                                ',
                'enable' => '1',
                'display_order' => 34,
            ],

            [
                'name' => 'user_update_review_module_date_notification',
                'subject' => 'Update Review Module Date Notification',
                'content' => '<h1>Hi {username}!</h1>
                                <br><br>
                                <b> {by_username} </b>
                                has update review
                                <b>{module_name} </b>
                                module date
                                <br><br>
                                ',
                'enable' => '1',
                'display_order' => 35,
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