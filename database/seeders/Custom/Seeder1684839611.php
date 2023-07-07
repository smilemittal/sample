<?php

namespace Database\Seeders\Custom;

use App\Models\EmailTemplate;
use Illuminate\Database\Seeder;

class Seeder1684839611 extends Seeder
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
                'subject' => 'User Invite Mail Not  ification',
                'content' => '<h1>Good news!</h1>
                                <br><br>
                                You have been invited by {company_name} to create a Multiply Me member account
                                <br><br>
                                <a href="{invite_link}" style="background-color:#000;padding: 0.9rem 2rem;font-size: 20px;color:#fff;border-radius: .2rem;display: block;text-align: center;max-width: 270px;margin: auto;" target="_blank">Create your member account</a><br><br>
                                Please do not share your invitation link with anyone    
                                ',
                'enable' => '1',
                'display_order' => 3
            ],
            [
                'name' => 'new_module_assigned_user_notification',
                'subject' => 'New Module Assigned Notification',
                'content' => 'Hi, {username}<br>
                              A New  <b> {module_data}</b> Module has been assigned to you by <b>{by_username} </b>',
                'enable' => '1',
                'display_order' => 4,
            ],
            [
                'name' => 'add_module_group_notification',
                'subject' => 'Add Module Group Notification',
                'content' => '<h1>Hi {username}!</h1>
                                <br><br>
                               <b> {by_username} </b> added <b>{module_name} </b> to group - <b>{group_name}</b>
                                <br><br>
                                ',
                'enable' => '1',
                'display_order' => 5,
            ],
            [
                'name' => 'user_assign_admin_privileges_company_notification',
                'subject' => 'User Assign Admin Privileges Company Notification',
                'content' => '<h1>Hi {username}!</h1>
                                <br><br>
                                You has been granted Business Admin Privileges by <b> {by_username} </b> 
                                <br><br>',
                'enable' => '1',
                'display_order' => 6,

            ],
            [
                'name' => 'user_completed_module_training_notification',
                'subject' => 'User Completed Module Training Notification',
                'content' => '<h1>Hi {username}!</h1>
                            <br><br> 
                            <b> {new_username}</b> has been completed training <b> {module_name}</b> by <b> {by_username} </b>
                            <br><br>',
                'enable' => '1',
                'display_order' => 7,

            ],
            [
                'name' => 'subject_delete_notification',
                'subject' => 'Subject Delete Notification',
                'content' => '<h1>Hi {username}!</h1>
            <br><br> 
            <b> {by_username} </b> Remove subject <b>{subject_name} </b> 
            <br><br>',
                'enable' => '1',
                'display_order' => 8,

            ],
            [
                'name' => 'new_group_added_notification',
                'subject' => 'New Group Added Notification',
                'content' => '<h1>Hi {username}!</h1>
            <br><br> 
           <b> {by_username} </b> added <b> {group_name} </b> in company <b>{company_name}</b>
            <br><br>',
                'enable' => '1',
                'display_order' => 9,

            ],
            [
                'name' => 'User_final_submit_subject_Notification',
                'subject' => 'User Final Submit Subject Notification',
                'content' => '<h1>Hi {username}!</h1>
            <br><br> 
            <b> {by_username} </b> final submited subject <b>{subject_name} </b> 
            <br><br>',
                'enable' => '1',
                'display_order' => 10,

            ],
            [
                'name' => 'user_reinvite_notification',
                'subject' => 'User ReInvite Mail ',
                'content' => '<h1>Good news!</h1>
                                <br><br>
                                You have been reinvited by {company_name} to create a Multiply Me member account
                                <br><br>
                                <a href="{invite_link}" style="background-color:#000;padding: 0.9rem 2rem;font-size: 20px;color:#fff;border-radius: .2rem;display: block;text-align: center;max-width: 270px;margin: auto;" target="_blank">Create your member account</a><br><br>
                                Please do not share your invitation link with anyone
                                ',
                'enable' => '1',
                'display_order' => 11
            ],
            [
                'name' => 'user_revoke_admin_privileges_company_notification',
                'subject' => 'User Revoke Admin Privileges Company Notification',
                'content' => '<h1>Hi {username}!</h1>
                                <br><br>
                                Now You have became member made by <b> {by_username} </b> 
                                <br><br>',
                'enable' => '1',
                'display_order' => 12

            ],
            [
                'name' => 'user_submit_subject_identify_company_notification',
                'subject' => 'User Submit Subject Identify Company Notification',
                'content' => '<h1>Hi {username}!</h1>
                                <br><br>
                                  Thank You For Submitted the Subject  
                                <br><br>',
                'enable' => '1',
                'display_order' => 13

            ],
            [
                'name' => 'new_module_assigned_company_notification',
                'subject' => 'New Module Assigned Company Notification',
                'content' => 'Hi, {username}<br>
         A New  <b> {module_data}</b> Module has been assigned to company {company_name} by <b>{by_username} </b>',
                'enable' => '1',
                'display_order' => 14,
            ],
            [
                'name' => 'user_final_submit_subject_devlop_company_notification',
                'subject' => 'User Final Submit Subject Develop Company Notification',
                'content' => '<h1>Hi {username}!</h1>
                                <br><br>
                                  Thank You For Final Submitted the Subject.  
                                <br><br>',
                'enable' => '1',
                'display_order' => 15

            ],
            [
                'name' => 'user_create_subject_identify_notification',
                'subject' => 'User Create Subject Identify Notification',
                'content' => '<h1>Hi {username}!</h1>
                                <br><br>
                            {by_username} created subject {subject_name} in Identify.
                                <br><br>',
                'enable' => '1',
                'display_order' => 16
            ],
            [
                'name' => 'user_add_directory_notification',
                'subject' => 'Add Directory Notification',
                'content' => '<h1>Hi {username}!</h1>
                                <br><br>
                               <b> {by_username} </b> added <b>{directory_name} </b> in company - <b>{company_name}</b>
                                <br><br>
                                ',
                'enable' => '1',
                'display_order' => 17,
            ],
            [
                'name' => 'user_update_directory_notification',
                'subject' => 'Update Directory Notification',
                'content' => '<h1>Hi {username}!</h1>
                                <br><br>
                               <b> {by_username} </b> update <b>{directory_name} </b> in company - <b>{company_name}</b>
                                <br><br>
                                ',
                'enable' => '1',
                'display_order' => 18,
            ],
            [
                'name' => 'user_add_module_to_directory_notification',
                'subject' => 'Add Module To Directory Notification',
                'content' => '<h1>Hi {username}!</h1>
                                <br><br>
                               <b> {by_username} </b>
                                Add module to <b>{directory_name} </b> in company - <b>{company_name}</b>
                                <br><br>
                                ',
                'enable' => '1',
                'display_order' => 19,
            ],
            [
                'name' => 'user_delete_directory_module_notification',
                'subject' => 'Delete Directory Module Notification',
                'content' => '<h1>Hi {username}!</h1>
                                <br><br>
                                <b> {by_username} </b>
                                delete <b>{module_name} </b>
                                module from <b>{directory_name} </b> directory
                                in company - <b>{company_name}</b>
                                <br><br>
                                ',
                'enable' => '1',
                'display_order' => 20,
            ],
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
            [
                'name' => 'user_update_company_module_notification',
                'subject' => 'Update Company Module Notification',
                'content' => '<h1>Hi {username}!</h1>
                                <br><br>
                                <b> {by_username} </b>
                                has update
                                <b>{module_name} </b>
                                company
                                module
                                <br><br>
                                ',
                'enable' => '1',
                'display_order' => 37,
            ],
            [
                'name' => 'user_request_to_update_module_notification',
                'subject' => 'Request To Update Module Notification',
                'content' => '<h1>Hi {username}!</h1>
                                <br><br>
                                <b> {by_username} </b>
                                has send request to admin to
                                update
                                <b>{module_name} </b>
                                module
                                <br><br>
                                ',
                'enable' => '1',
                'display_order' => 38,
            ],
            [
                'name' => 'user_move_to_library_notification',
                'subject' => 'Move To Library Notification',
                'content' => '<h1>Hi {username}!</h1>
                                <br><br>
                                <b> {by_username} </b>
                                has move
                                <b>{module_name} </b>
                                module to library
                                <br><br>
                                ',
                'enable' => '1',
                'display_order' => 39,
            ],
            [
                'name' => 'user_delete_directory_notification',
                'subject' => 'Delete Directory Notification',
                'content' => '<h1>Hi {username}!</h1>
                                <br><br>
                                <b> {by_username} </b>
                                has deleted
                                <b>{directory_name} </b>
                                directory
                                <br><br>
                                ',
                'enable' => '1',
                'display_order' => 40,
            ],
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
            [
                'name' => 'user_new_assigned_form_reminder_notification',
                'subject' => 'New Assigned Form Reminder Notification',
                'content' => '<h1>Hi {username}!</h1>
                                <br><br>
                                <b> {by_username} </b>
                                have not checked
                                <b>{module_name} </b>
                                module past
                                <b> {days}</b> days
                                <br><br>
                                ',
                'enable' => '1',
                'display_order' => 42,
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