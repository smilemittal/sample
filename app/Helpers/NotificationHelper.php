<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Log;
use App\Models\EmailTemplate;

class NotificationHelper
{
    public $user;
    public $lang;
    public $emailTemplate;
    public $lineMessageBulderData;


    public $emailsToBeAlwaysSent = [
        'password_reset_link_notification',
        'new_user_notification',
        'user_invite_notification',
        'new_module_assigned_user_notification',
        'add_module_group_notification',
        'user_assign_admin_privileges_company_notification',
        'user_completed_module_training_notification',
        // 'members_delete_notification',
        'subject_delete_notification',
        'new_group_added_notification',
        // 'library_archive_module_notification',
        // 'library_Unarchive_module_notification',
        'User_final_submit_subject_Notification',
        // 'user_group_added_notification',
        'user_reinvite_notification',
        'user_revoke_admin_privileges_company_notification',
        'user_submit_subject_identify_company_notification',
        'user_final_submit_subject_develop_company_notification',
        'new_module_assigned_company_notification',
        'user_create_subject_identify_notification',
        'user_add_directory_notification',
        'user_update_directory_notification',
        'user_add_module_to_directory_notification',
        'user_delete_directory_module_notification',
        'user_created_learning_path_notification',
        'user_updated_learning_path_notification',
        'user_locked_learning_path_notification',
        'user_deleted_learning_path_notification',
        'user_add_module_to_learning_path_notification',
        'user_remove_learning_path_module_notification',
        'user_add_member_to_learning_path_notification',
        'user_remove_learning_path_member_notification',
        'user_complete_learning_path_module_notification',
        'user_archive_learning_path_notification',
        'user_unarchive_learning_path_notification',
        'user_restore_learning_path_notification',
        'user_duplicate_learning_path_notification',
        'user_update_review_module_notification',
        'user_update_review_module_date_notification',
        'user_review_reminder_notification',
        'user_update_company_module_notification',
        'user_request_to_update_module_notification',
        'user_move_to_library_notification',
        'user_module_expiry_notification',
        'user_new_assigned_form_reminder_notification'
    ];

    public function __construct($user)
    {
        $this->user = $user;
        // if (class_basename($this->user) == 'User') {
        //     $this->lang = $user->get_lang();
        // }
    }

    public function loadTemplate($name)
    {
        $this->emailTemplate = EmailTemplate::get_by_name($name);
        if (class_basename($this->user) == 'User') {
            // Set Global Variables
            $this->emailTemplate->set_username($this->user->name);
        }

        return $this->emailTemplate;
    }

    public function getLang()
    {
        return $this->lang;
    }

    public function setTemplate($emailTemplate)
    {
        $this->emailTemplate = $emailTemplate;
    }

    public function shouldSendEmail()
    {
        if (in_array($this->emailTemplate->name, $this->emailsToBeAlwaysSent)) {
            return true;
        }
        return $this->user->receive_emails == 1;
    }

    public function send($email = null)
    {
        if ($email == null) {
            $email = $this->user->email;
        }
        // Send Email
        if ($this->shouldSendEmail()) {
            Log::info('Send email request ' . $email);
            MailHelper::sendMail(
                $email,
                $this->emailTemplate->get_format('content'),
                $this->emailTemplate->get_format('subject'),
                $this->emailTemplate->get_header(),
                $this->emailTemplate->get_footer()
            );
            Log::info('End email request ' . $email);
        }
    }

    public static function sendNewUserNotification($user, $password)
    {
        // $role = $user->get_role();
        // if (!($role && $role->send_login_details == 1)) {
        //     return;
        // }

        $notificationHelper = new NotificationHelper($user);
        $emailTemplate = $notificationHelper->loadTemplate('new_user_notification');
        if ($emailTemplate->enable == 1) {
            $emailTemplate
                ->set_password($password);
            // ->set_verification_url($user->getVerificationURL())

            $notificationHelper->setTemplate($emailTemplate);
            $notificationHelper->send();
        }
    }

    public static function sendResetPasswordLinkNotification($token, $user, $array)
    {
        $notificationHelper = new NotificationHelper($token);
        $emailTemplate = $notificationHelper->loadTemplate('password_reset_link_notification');
        if ($emailTemplate->enable == 1) {
            $emailTemplate
                ->set_username($user->name)
                ->set_reset_password_link($array['link']);
            $notificationHelper->setTemplate($emailTemplate);
            $notificationHelper->send($user->email);
        }
    }

    public static function sendUserInviteNotification($invite)
    {
        $notificationHelper = new NotificationHelper($invite['invite_by']);
        $emailTemplate = $notificationHelper->loadTemplate('user_invite_notification');
        if ($emailTemplate->enable == 1) {
            Log::info('user_invite_notification', $invite);
            $emailTemplate
                ->set_company_name($invite['company_name'])
                ->set_invite_link($invite['invite_link']);
            $notificationHelper->setTemplate($emailTemplate);

            $notificationHelper->send($invite['user_email']);
        }
    }

    public static function sendUserReInviteNotification($invite)
    {
        $notificationHelper = new NotificationHelper($invite['invite_by']);
        $emailTemplate = $notificationHelper->loadTemplate('user_reinvite_notification');
        if ($emailTemplate->enable == 1) {
            $emailTemplate
                ->set_company_name($invite['company_name'])
                ->set_invite_link($invite['invite_link']);
            $notificationHelper->setTemplate($emailTemplate);
            $notificationHelper->send($invite['user_email']);
        }
    }

    // public static function sendAssignAdminPrivilegesNotification($user, $company_name, $by_user)
    // {
    //     $notificationHelper = new NotificationHelper($user);
    //     $emailTemplate = $notificationHelper->loadTemplate('user_assign_admin_privileges_notification');
    //     if ($emailTemplate->enable == 1) {
    //         $emailTemplate
    //             ->set_username($user->name)
    //             ->set_company_name($company_name);
    //         $notificationHelper->setTemplate($emailTemplate);
    //         $notificationHelper->send($user->email);
    //     }
    // }

    public static function sendNewModuleAddedNotification($user, $form, $group)
    {
        $notificationHelper = new NotificationHelper($user);
        $emailTemplate = $notificationHelper->loadTemplate('group_new_module_added_notification');
        if ($emailTemplate->enable == 1) {
            $emailTemplate
                ->set_username($user->name)
                ->set_module_name($form->display_title)
                ->set_group_name($group->name);
            $notificationHelper->setTemplate($emailTemplate);
            $notificationHelper->send($user->email);
        }
    }

    // public static function sendUserAddedToGroupNotification($user, $group)
    // {
    //     $notificationHelper = new NotificationHelper($user);
    //     $emailTemplate = $notificationHelper->loadTemplate('user_group_added_notification');
    //     if ($emailTemplate->enable == 1) {
    //         Log::info("Email-sent: user_group_added_notification - User:" . $user->email);
    //         $emailTemplate
    //             ->set_username($user->name)
    //             ->set_group_name($group->name);
    //         $notificationHelper->setTemplate($emailTemplate);
    //         $notificationHelper->send($user->email);
    //     }
    // }

    public static function sendNewModuleAssignedNotification($users, $data)
    {
        $notificationHelper = new NotificationHelper($data['loggedUser']);
        $emailTemplate = $notificationHelper->loadTemplate('new_module_assigned_user_notification');
        if ($emailTemplate->enable == 1) {
            foreach ($users  as  $user) {
                Log::info("Email-sent: new_module_assigned_user_notification - User:" . $user->email);

                $emailTemplate
                    ->set_username($user->name)
                    ->set_module_data($data['moduleData'])
                    ->set_by_username($data['loggedUser']->name);
                $notificationHelper->setTemplate($emailTemplate);
                $notificationHelper->send($user->email);
            }
        }
    }


    // public static function sendCompanyAddedGroupNotification($user, $group, $by_user, $new_user)
    // {
    //     // dd($user,$group,$by_user);
    //     $notificationHelper = new NotificationHelper($user);
    //     $emailTemplate = $notificationHelper->loadTemplate('new_member_added_group_notification');
    //     if ($emailTemplate->enable == 1) {
    //         \Log::info("Email-sent:new_member_added_group_notification - User:" . $user->email);
    //         $emailTemplate
    //             ->set_username($user->name)
    //             ->set_group_name($group->name)
    //             ->set_by_username($by_user->name)
    //             ->set_new_username($new_user->name);
    //         $notificationHelper->setTemplate($emailTemplate);
    //         $notificationHelper->send($user->email);
    //     }
    // }

    public static function sendCompanyAddedModuleGroupNotification($users, $data)
    {
        // dd($user,$group,$by_user);
        $notificationHelper = new NotificationHelper($data['loggedUser']);
        $emailTemplate = $notificationHelper->loadTemplate('add_module_group_notification');
        if ($emailTemplate->enable == 1) {
            foreach ($users as $user) {
                Log::info("Email-sent:add_module_group_notification - User:" . $user->email);
                $emailTemplate
                    ->set_username($user->name)
                    ->set_group_name($data['group']->name)
                    ->set_by_username($data['loggedUser']->name)
                    ->set_module_name($data['form']->display_title);
                $notificationHelper->setTemplate($emailTemplate);
                $notificationHelper->send($user->email);
            }
        }
    }

    // public static function sendNewUserRegisterCompanyNotification($user, $new_user, $company_name)
    // {
    //     $notificationHelper = new NotificationHelper($user);
    //     $emailTemplate = $notificationHelper->loadTemplate('new_user_register_company_notification');
    //     if ($emailTemplate->enable == 1) {
    //         \Log::info("Email-sent:new_user_register_company_notification' - User:" . $user->email);
    //         $emailTemplate
    //             ->set_username($user->name)
    //             ->set_new_username($new_user->name)
    //             ->set_company_name($company_name->name);
    //         $notificationHelper->setTemplate($emailTemplate);
    //         $notificationHelper->send($user->email);
    //     }
    // }

    public static function sendUserAssignAdminPrivilegesCompanyNotification($users, $data)
    {
        $notificationHelper = new NotificationHelper($data['loggedUser']);
        $emailTemplate = $notificationHelper->loadTemplate('user_assign_admin_privileges_company_notification');
        if ($emailTemplate->enable == 1) {
            foreach ($users  as  $user) {
                Log::info("Email-sent:user_assign_admin_privileges_company_notification' - User:" . $user->email);
                $emailTemplate
                    ->set_username($user->name)
                    // ->set_new_username($new_user->name)
                    ->set_by_username($data['loggedUser']->name);
                $notificationHelper->setTemplate($emailTemplate);
                $notificationHelper->send($user->email);
            }
        }
    }

    public static function sendUserRevokeAdminPrivilegesCompanyNotification($users, $data)
    {
        $notificationHelper = new NotificationHelper($data['loggedUser']);
        $emailTemplate = $notificationHelper->loadTemplate('user_revoke_admin_privileges_company_notification');
        if ($emailTemplate->enable == 1) {
            foreach ($users  as  $user) {
                Log::info("Email-sent:user_revoke_admin_privileges_company_notification' - User:" . $user->email);
                $emailTemplate
                    ->set_username($user->name)
                    // ->set_new_username($new_user->name)
                    ->set_by_username($data['loggedUser']->name);
                $notificationHelper->setTemplate($emailTemplate);
                $notificationHelper->send($user->email);
            }
        }
    }

    public static function sendUserCompletedModuleTrainingNotification($users, $data)
    {
        $notificationHelper = new NotificationHelper($data['loggedUser']);
        $emailTemplate = $notificationHelper->loadTemplate('user_completed_module_training_notification');
        if ($emailTemplate->enable == 1) {
            foreach ($users as $user) {
                if (!empty($user)) {
                    Log::info("Email-sent: user_completed_module_training_notification - User:" . $user->email);
                    $emailTemplate
                        ->set_username($user->name)
                        ->set_by_username($data['loggedUser']->name)
                        ->set_module_name($data['form']->display_title);
                    $notificationHelper->setTemplate($emailTemplate);
                    $notificationHelper->send($user->email);
                }
            }
        }
    }


    // public static function sendMembersDeleteNotification($members, $group, $new_user, $by_user)
    // {
    //     $notificationHelper = new NotificationHelper($by_user);
    //     $emailTemplate = $notificationHelper->loadTemplate('members_delete_notification');
    //     if ($emailTemplate->enable == 1) {
    //         foreach ($members  as   $member) {
    //             Log::info("Email-sent:members_delete_notification - User:" . $member->email);
    //             $emailTemplate
    //                 ->set_username($member->name)
    //                 ->set_group_name($group->name)
    //                 ->set_new_username($new_user->name)
    //                 ->set_by_username($by_user->name);
    //             $notificationHelper->setTemplate($emailTemplate);
    //             $notificationHelper->send($member->email);
    //         }
    //     }
    // }
    public static function sendRemoveSubjectNotification($users, $data)
    {
        $notificationHelper = new NotificationHelper($data['loggedUser']);
        $emailTemplate = $notificationHelper->loadTemplate('subject_delete_notification');
        if ($emailTemplate->enable == 1) {
            foreach ($users  as  $user) {
                Log::info("Email-sent: subject_delete_notification - User:" . $user->email);
                $emailTemplate
                    ->set_username($user->name)
                    ->set_by_username($data['loggedUser']->name)
                    ->set_subject_name($data['subject']->subject);
                $notificationHelper->setTemplate($emailTemplate);
                $notificationHelper->send($user->email);
            }
        }
    }


    public static function sendNewAddGroupNotification($users, $data)
    {
        $notificationHelper = new NotificationHelper($data['loggedUser']);
        $emailTemplate = $notificationHelper->loadTemplate('new_group_added_notification');
        if ($emailTemplate->enable == 1) {
            foreach ($users  as  $user) {
                Log::info("Email-sent: new_group_added_notification - User:" . $user->email);
                $emailTemplate
                    ->set_username($user->name)
                    ->set_group_name($data['group']->name)
                    ->set_company_name($data['company']->name)
                    ->set_by_username($data['loggedUser']->name);
                $notificationHelper->setTemplate($emailTemplate);
                $notificationHelper->send($user->email);
            }
        }
    }


    public static function sendArchiveModuleNotification($members, $form, $by_user)
    {
        $notificationHelper = new NotificationHelper($by_user);
        $emailTemplate = $notificationHelper->loadTemplate('library_archive_module_notification');
        if ($emailTemplate->enable == 1) {
            foreach ($members  as  $user) {
                Log::info("Email-sent: library_archive_module_notification - User:" . $user->email);
                $emailTemplate
                    ->set_username($user->name)
                    ->set_module_name($form->display_title)
                    ->set_by_username($by_user->name);
                $notificationHelper->setTemplate($emailTemplate);
                $notificationHelper->send($user->email);
            }
        }
    }


    public static function sendUnarchiveModuleNotification($members, $form, $by_user)
    {
        $notificationHelper = new NotificationHelper($by_user);
        $emailTemplate = $notificationHelper->loadTemplate('library_Unarchive_module_notification');
        if ($emailTemplate->enable == 1) {
            foreach ($members  as  $user) {
                Log::info("Email-sent: library_Unarchive_module_notification - User:" . $user->email);
                $emailTemplate
                    ->set_username($user->name)
                    ->set_module_name($form->display_title)
                    ->set_by_username($by_user->name);
                $notificationHelper->setTemplate($emailTemplate);
                $notificationHelper->send($user->email);
            }
        }
    }


    public static function sendUserFinalSubmitSubjectNotification($users, $data)
    {
        $notificationHelper = new NotificationHelper($data['loggedUser']);
        $emailTemplate = $notificationHelper->loadTemplate('User_final_submit_subject_Notification');
        if ($emailTemplate->enable == 1) {
            foreach ($users  as  $user) {
                if (!empty($user)) {
                    Log::info("Email-sent: User_final_submit_subject_Notification - User:" . $user->email);
                    $emailTemplate
                        ->set_username($user->name)
                        ->set_subject_name($data['subject']->subject)
                        ->set_by_username($data['loggedUser']->name);
                    $notificationHelper->setTemplate($emailTemplate);
                    $notificationHelper->send($user->email);
                }
            }
        }
    }


    public static function sendUserSubmitSubjectIdentifyNotification($user)
    {
        $notificationHelper = new NotificationHelper($user);
        $emailTemplate = $notificationHelper->loadTemplate('user_submit_subject_identify_company_notification');
        if ($emailTemplate->enable == 1) {
            Log::info("Email-sent: user_submit_subject_identify_company_notification - User:" . $user->email);
            $emailTemplate
                ->set_username($user->name);
            $notificationHelper->setTemplate($emailTemplate);
            $notificationHelper->send($user->email);
        }
    }


    public static function sendNewModuleAssignedToCompanyNotification($users, $data)
    {
        // dd($users, $form, $company, $by_user);
        $notificationHelper = new NotificationHelper($data['loggedUser']);
        $emailTemplate = $notificationHelper->loadTemplate('new_module_assigned_company_notification');
        if ($emailTemplate->enable == 1) {
            foreach ($users  as  $user) {
                if (!empty($user)) {
                    // dd($user->email);
                    Log::info("Email-sent: new_module_assigned_company_notification - User:" . $user->email);
                    $emailTemplate
                        ->set_username($user->name)
                        ->set_module_name($data['form']->display_title)
                        ->set_company_name($user->company->name)
                        ->set_by_username($data['loggedUser']->name);
                    $notificationHelper->setTemplate($emailTemplate);
                    $notificationHelper->send($user->email);
                }
            }
        }
    }

    public static function sendUserSubmitSubjectDevelopNotification($user)
    {
        $notificationHelper = new NotificationHelper($user);
        $emailTemplate = $notificationHelper->loadTemplate('user_final_submit_subject_devlop_company_notification');
        if ($emailTemplate->enable == 1) {
            if (!empty($user)) {
                Log::info("Email-sent: user_final_submit_subject_devlop_company_notification - User:" . $user->email);
                $emailTemplate
                    ->set_username($user->name);
                $notificationHelper->setTemplate($emailTemplate);
                $notificationHelper->send($user->email);
            }
        }
    }

    public static function sendCreatedSubjectNotification($users, $data)
    {
        $notificationHelper = new NotificationHelper($data['loggedUser']);
        $emailTemplate = $notificationHelper->loadTemplate('user_create_subject_identify_notification');
        if ($emailTemplate->enable == 1) {
            foreach ($users  as  $user) {
                if (!empty($user)) {
                    Log::info("Email-sent: user_create_subject_identify_notification - User:" . $user->email);
                    $emailTemplate
                        ->set_username($user->name)
                        ->set_subject_name($data['subject']->subject)
                        ->set_by_username($data['loggedUser']->name);
                    $notificationHelper->setTemplate($emailTemplate);
                    $notificationHelper->send($user->email);
                }
            }
        }
    }


    public static function sendCreatedDirectoryNotification($users, $data)
    {

        $notificationHelper = new NotificationHelper($data['loggedUser']);
        $emailTemplate = $notificationHelper->loadTemplate('user_add_directory_notification');
        if ($emailTemplate->enable == 1) {

            foreach ($users as $user) {
                Log::info("Email-sent: user_add_directory_notification - User:" . $user->email);
                $emailTemplate
                    ->set_username($user->name)
                    ->set_directory_name($data['directory']->name)
                    ->set_company_name($data['company']->name)
                    ->set_by_username($data['loggedUser']->name);
                $notificationHelper->setTemplate($emailTemplate);
                $notificationHelper->send($user->email);
            }
        }
    }



    public static function sendUpdatedDirectoryNotification($users, $data)
    {

        $notificationHelper = new NotificationHelper($data['loggedUser']);
        $emailTemplate = $notificationHelper->loadTemplate('user_update_directory_notification');
        if ($emailTemplate->enable == 1) {

            foreach ($users as $user) {
                Log::info("Email-sent: user_update_directory_notification - User:" . $user->email);
                $emailTemplate
                    ->set_username($user->name)
                    ->set_directory_name($data['directory']->name)
                    ->set_company_name($data['company']->name)
                    ->set_by_username($data['loggedUser']->name);
                $notificationHelper->setTemplate($emailTemplate);
                $notificationHelper->send($user->email);
            }
        }
    }

    public static function sendAddModuleToDirectoryNotification($users, $data)
    {
        $notificationHelper = new NotificationHelper($data['loggedUser']);
        $emailTemplate = $notificationHelper->loadTemplate('user_add_module_to_directory_notification');
        if ($emailTemplate->enable == 1) {

            foreach ($users as $user) {
                Log::info("Email-sent: user_add_module_to_directory_notification - User:" . $user->email);
                $emailTemplate
                    ->set_username($user->name)
                    ->set_directory_name($data['directoryModule']->directory->name)
                    ->set_module_name($data['directoryModule']->directoryModules->display_title)
                    ->set_company_name($data['company']->name)
                    ->set_by_username($data['loggedUser']->name);
                $notificationHelper->setTemplate($emailTemplate);
                $notificationHelper->send($user->email);
            }
        }
    }

    public static function sendDeleteDirectoryModuleNotification($users, $data)
    {
        $notificationHelper = new NotificationHelper($data['loggedUser']);
        $emailTemplate = $notificationHelper->loadTemplate('user_delete_directory_module_notification');
        if ($emailTemplate->enable == 1) {

            foreach ($users as $user) {
                Log::info("Email-sent: user_delete_directory_module_notification - User:" . $user->email);
                $emailTemplate
                    ->set_username($user->name)
                    ->set_directory_name($data['directoryModule']->directory->name)
                    ->set_company_name($data['company']->name)
                    ->set_module_name($data['directoryModule']->directoryModules->display_title)
                    ->set_by_username($data['loggedUser']->name);
                $notificationHelper->setTemplate($emailTemplate);
                $notificationHelper->send($user->email);
            }
        }
    }

    /**create learning path NotificationHelper function */
    public static function sendAddLearningPathNotification($users, $data)
    {
        $notificationHelper = new NotificationHelper($data['loggedUser']);
        $emailTemplate = $notificationHelper->loadTemplate('user_created_learning_path_notification');
        if ($emailTemplate->enable == 1) {

            foreach ($users as $user) {
                Log::info("Email-sent: user_created_learning_path_notification - User:" . $user->email);
                $emailTemplate
                    ->set_username($user->name)
                    ->set_learning_path_title($data['learningPath']->title)
                    ->set_by_username($data['loggedUser']->name);
                $notificationHelper->setTemplate($emailTemplate);
                $notificationHelper->send($user->email);
            }
        }
    }

    /**update learning path NotifictionHelper function */
    public static function sendUpdateLearningPathNotification($users, $data)
    {
        $notificationHelper = new NotificationHelper($data['loggedUser']);
        $emailTemplate = $notificationHelper->loadTemplate('user_updated_learning_path_notification');
        if ($emailTemplate->enable == 1) {

            foreach ($users as $user) {
                Log::info("Email-sent: user_updated_learning_path_notification - User:" . $user->email);
                $emailTemplate
                    ->set_username($user->name)
                    ->set_learning_path_title($data['learningPath']->title)
                    ->set_by_username($data['loggedUser']->name);
                $notificationHelper->setTemplate($emailTemplate);
                $notificationHelper->send($user->email);
            }
        }
    }

    /**Lock learning path NotifictionHelper function */
    public static function sendLockLearningPathNotification($users, $data)
    {
        $notificationHelper = new NotificationHelper($data['loggedUser']);
        $emailTemplate = $notificationHelper->loadTemplate('user_locked_learning_path_notification');
        if ($emailTemplate->enable == 1) {

            foreach ($users as $user) {
                Log::info("Email-sent: user_locked_learning_path_notification - User:" . $user->email);
                $emailTemplate
                    ->set_username($user->name)
                    ->set_learning_path_title($data['learningPath']->title)
                    ->set_by_username($data['loggedUser']->name);
                $notificationHelper->setTemplate($emailTemplate);
                $notificationHelper->send($user->email);
            }
        }
    }

    /**Delete learning path NotifictionHelper function */
    public static function sendDeleteLearningPathNotification($users, $data)
    {
        $notificationHelper = new NotificationHelper($data['loggedUser']);
        $emailTemplate = $notificationHelper->loadTemplate('user_deleted_learning_path_notification');
        if ($emailTemplate->enable == 1) {

            foreach ($users as $user) {
                Log::info("Email-sent: user_deleted_learning_path_notification - User:" . $user->email);
                $emailTemplate
                    ->set_username($user->name)
                    ->set_learning_path_title($data['learningPath']->title)
                    ->set_by_username($data['loggedUser']->name);
                $notificationHelper->setTemplate($emailTemplate);
                $notificationHelper->send($user->email);
            }
        }
    }

    /**add module to learning path NotifictionHelper function */
    public static function sendAddModuleToLearningPathNotification($users, $data)
    {
        $notificationHelper = new NotificationHelper($data['loggedUser']);
        $emailTemplate = $notificationHelper->loadTemplate('user_add_module_to_learning_path_notification');
        if ($emailTemplate->enable == 1) {

            foreach ($users as $user) {
                Log::info("Email-sent: user_add_module_to_learning_path_notification - User:" . $user->email);
                $emailTemplate
                    ->set_username($user->name)
                    ->set_learning_path_title($data['learningPath']->title)
                    ->set_module_name($data['xmeForm']->form->display_title)
                    ->set_by_username($data['loggedUser']->name);
                $notificationHelper->setTemplate($emailTemplate);
                $notificationHelper->send($user->email);
            }
        }
    }

    /**remove learning path module NotifictionHelper function */
    public static function sendRemoveLearningPathModuleNotification($users, $data)
    {
        $notificationHelper = new NotificationHelper($data['loggedUser']);
        $emailTemplate = $notificationHelper->loadTemplate('user_remove_learning_path_module_notification');
        if ($emailTemplate->enable == 1) {

            foreach ($users as $user) {
                Log::info("Email-sent: user_remove_learning_path_module_notification - User:" . $user->email);
                $emailTemplate
                    ->set_username($user->name)
                    ->set_learning_path_title($data['learningPath']->title)
                    ->set_module_name($data['xmeForm']->form->display_title)
                    ->set_by_username($data['loggedUser']->name);
                $notificationHelper->setTemplate($emailTemplate);
                $notificationHelper->send($user->email);
            }
        }
    }

    /**add member to learning path NotifictionHelper function */
    public static function sendAddMemberToLearningPathNotification($users, $data)
    {
        $notificationHelper = new NotificationHelper($data['loggedUser']);
        $emailTemplate = $notificationHelper->loadTemplate('user_add_member_to_learning_path_notification');
        if ($emailTemplate->enable == 1) {

            foreach ($users as $user) {
                Log::info("Email-sent: user_add_member_to_learning_path_notification - User:" . $user->email);
                $emailTemplate
                    ->set_username($user->name)
                    ->set_learning_path_title($data['learningPath']->title)
                    ->set_member_name($data['member']->user->name)
                    ->set_by_username($data['loggedUser']->name);
                $notificationHelper->setTemplate($emailTemplate);
                $notificationHelper->send($user->email);
            }
        }
    }

    /**remove learning path member NotifictionHelper function */
    public static function sendRemoveLearningPathMemberNotification($users, $data)
    {
        $notificationHelper = new NotificationHelper($data['loggedUser']);
        $emailTemplate = $notificationHelper->loadTemplate('user_remove_learning_path_member_notification');
        if ($emailTemplate->enable == 1) {

            foreach ($users as $user) {
                Log::info("Email-sent: user_remove_learning_path_member_notification - User:" . $user->email);
                $emailTemplate
                    ->set_username($user->name)
                    ->set_learning_path_title($data['learningPath']->title)
                    ->set_member_name($data['member']->user->name)
                    ->set_by_username($data['loggedUser']->name);
                $notificationHelper->setTemplate($emailTemplate);
                $notificationHelper->send($user->email);
            }
        }
    }

    /**completed learning path module NotifictionHelper function */
    public static function sendCompletedLearningpathModuleNotification($users, $data)
    {
        $notificationHelper = new NotificationHelper($data['loggedUser']);
        $emailTemplate = $notificationHelper->loadTemplate('user_complete_learning_path_module_notification');
        if ($emailTemplate->enable == 1) {

            foreach ($users as $user) {
                if (!empty($user)) {
                    Log::info("Email-sent: user_complete_learning_path_module_notification - User:" . $user->email);
                    $emailTemplate
                        ->set_username($user->name)
                        ->set_learning_path_title($data['learningPath']->title)
                        ->set_module_name($data['xmeForm']->display_title)
                        ->set_by_username($data['loggedUser']->name);
                    $notificationHelper->setTemplate($emailTemplate);
                    $notificationHelper->send($user->email);
                }
            }
        }
    }

    /**Archive learning path NotifictionHelper function */
    public static function sendArchiveLearningPathNotification($users, $data)
    {
        $notificationHelper = new NotificationHelper($data['loggedUser']);
        $emailTemplate = $notificationHelper->loadTemplate('user_archive_learning_path_notification');
        if ($emailTemplate->enable == 1) {

            foreach ($users as $user) {
                Log::info("Email-sent: user_archive_learning_path_notification - User:" . $user->email);
                $emailTemplate
                    ->set_username($user->name)
                    ->set_learning_path_title($data['learningPath']->title)
                    ->set_by_username($data['loggedUser']->name);
                $notificationHelper->setTemplate($emailTemplate);
                $notificationHelper->send($user->email);
            }
        }
    }

    /**Unarchive learning path notifictionHelper function */
    public static function sendUnarchiveLearningPathNotification($users, $data)
    {
        $notificationHelper = new NotificationHelper($data['loggedUser']);
        $emailTemplate = $notificationHelper->loadTemplate('user_unarchive_learning_path_notification');
        if ($emailTemplate->enable == 1) {

            foreach ($users as $user) {
                Log::info("Email-sent: user_unarchive_learning_path_notification - User:" . $user->email);
                $emailTemplate
                    ->set_username($user->name)
                    ->set_learning_path_title($data['learningPath']->title)
                    ->set_by_username($data['loggedUser']->name);
                $notificationHelper->setTemplate($emailTemplate);
                $notificationHelper->send($user->email);
            }
        }
    }

    /**restore learning path notifictionHelper function */
    public static function sendRestoreLearningPathNotification($users, $data)
    {
        $notificationHelper = new NotificationHelper($data['loggedUser']);
        $emailTemplate = $notificationHelper->loadTemplate('user_restore_learning_path_notification');
        if ($emailTemplate->enable == 1) {

            foreach ($users as $user) {
                Log::info("Email-sent: user_restore_learning_path_notification - User:" . $user->email);
                $emailTemplate
                    ->set_username($user->name)
                    ->set_learning_path_title($data['learningPath']->title)
                    ->set_by_username($data['loggedUser']->name);
                $notificationHelper->setTemplate($emailTemplate);
                $notificationHelper->send($user->email);
            }
        }
    }

    /**restore learning path notifictionHelper function */
    public static function sendDuplicateLearningPathNotification($users, $data)
    {
        $notificationHelper = new NotificationHelper($data['loggedUser']);
        $emailTemplate = $notificationHelper->loadTemplate('user_duplicate_learning_path_notification');
        if ($emailTemplate->enable == 1) {

            foreach ($users as $user) {
                Log::info("Email-sent: user_duplicate_learning_path_notification - User:" . $user->email);
                $emailTemplate
                    ->set_username($user->name)
                    ->set_learning_path_title($data['learningPath']->title)
                    ->set_by_username($data['loggedUser']->name);
                $notificationHelper->setTemplate($emailTemplate);
                $notificationHelper->send($user->email);
            }
        }
    }

    /**update review module notifictionHelper function */
    public static function sendUpdateReviewModuleNotification($users, $data)
    {
        $notificationHelper = new NotificationHelper($data['loggedUser']);
        $emailTemplate = $notificationHelper->loadTemplate('user_update_review_module_notification');
        if ($emailTemplate->enable == 1) {

            foreach ($users as $user) {
                Log::info("Email-sent: user_update_review_module_notification - User:" . $user->email);
                $emailTemplate
                    ->set_username($user->name)
                    ->set_module_name($data['xmeForm']->display_title)
                    ->set_by_username($data['loggedUser']->name);
                $notificationHelper->setTemplate($emailTemplate);
                $notificationHelper->send($user->email);
            }
        }
    }

    /**update review module Date notifictionHelper function */
    public static function sendUpdateReviewModuleDateNotification($users, $data)
    {
        $notificationHelper = new NotificationHelper($data['loggedUser']);
        $emailTemplate = $notificationHelper->loadTemplate('user_update_review_module_date_notification');
        if ($emailTemplate->enable == 1) {

            foreach ($users as $user) {
                Log::info("Email-sent: user_update_review_module_date_notification - User:" . $user->email);
                $emailTemplate
                    ->set_username($user->name)
                    ->set_module_name($data['xmeForm']->display_title)
                    ->set_by_username($data['loggedUser']->name);
                $notificationHelper->setTemplate($emailTemplate);
                $notificationHelper->send($user->email);
            }
        }
    }

    /**review reminder notifictionHelper function */
    public static function sendReviewReminderNotification($users, $data)
    {
        $notificationHelper = new NotificationHelper($data['loggedUser']);
        $emailTemplate = $notificationHelper->loadTemplate('user_review_reminder_notification');
        if ($emailTemplate->enable == 1) {

            foreach ($users as $user) {
                Log::info("Email-sent: user_review_reminder_notification - User:" . $user->email);
                $emailTemplate
                    ->set_username($user->name)
                    ->set_module_name($data['xmeForm']->display_title)
                    ->set_days($data['xmeForm']->days)
                    ->set_by_username($data['loggedUser']->name);
                $notificationHelper->setTemplate($emailTemplate);
                $notificationHelper->send($user->email);
            }
        }
    }

    /**update company module notifictionHelper function */
    public static function sendUpdateCompanyModuleNotification($users, $data)
    {
        $notificationHelper = new NotificationHelper($data['loggedUser']);
        $emailTemplate = $notificationHelper->loadTemplate('user_update_company_module_notification');
        if ($emailTemplate->enable == 1) {

            foreach ($users as $user) {
                Log::info("Email-sent: user_update_company_module_notification - User:" . $user->email);
                $emailTemplate
                    ->set_username($user->name)
                    ->set_module_name($data['xmeForm']->form->display_title)
                    ->set_by_username($data['loggedUser']->name);
                $notificationHelper->setTemplate($emailTemplate);
                $notificationHelper->send($user->email);
            }
        }
    }

    /**request to update module notifictionHelper function */
    public static function sendRequestToUpdateModuleNotification($users, $data)
    {
        $notificationHelper = new NotificationHelper($data['loggedUser']);
        $emailTemplate = $notificationHelper->loadTemplate('user_request_to_update_module_notification');
        if ($emailTemplate->enable == 1) {

            foreach ($users as $user) {
                Log::info("Email-sent: user_request_to_update_module_notification - User:" . $user->email);
                $emailTemplate
                    ->set_username($user->name)
                    ->set_module_name($data['xmeForm']->form->display_title)
                    ->set_by_username($data['loggedUser']->name);
                $notificationHelper->setTemplate($emailTemplate);
                $notificationHelper->send($user->email);
            }
        }
    }

    /**move to library notifictionHelper function */
    public static function sendMoveToLibraryNotification($users, $data)
    {
        $notificationHelper = new NotificationHelper($data['loggedUser']);
        $emailTemplate = $notificationHelper->loadTemplate('user_move_to_library_notification');
        if ($emailTemplate->enable == 1) {

            foreach ($users as $user) {
                Log::info("Email-sent: user_move_to_library_notification - User:" . $user->email);
                $emailTemplate
                    ->set_username($user->name)
                    ->set_module_name($data['xmeForm']->form->display_title)
                    ->set_by_username($data['loggedUser']->name);
                $notificationHelper->setTemplate($emailTemplate);
                $notificationHelper->send($user->email);
            }
        }
    }

    /**delete directory notifictionHelper function */
    public static function sendDeleteDirectoryNotification($users, $data)
    {
        $notificationHelper = new NotificationHelper($data['loggedUser']);
        $emailTemplate = $notificationHelper->loadTemplate('user_delete_directory_notification');
        if ($emailTemplate->enable == 1) {

            foreach ($users as $user) {
                if (!empty($user)) {
                    Log::info("Email-sent: user_delete_directory_notification - User:" . $user->email);
                    $emailTemplate
                        ->set_username($user->name)
                        ->set_directory_name($data['directory']->name)
                        ->set_by_username($data['loggedUser']->name);
                    $notificationHelper->setTemplate($emailTemplate);
                    $notificationHelper->send($user->email);
                }
            }
        }
    }

    /**module expirey notifictionHelper function */
    public static function sendModuleExpiryNotification($users, $data)
    {
        $notificationHelper = new NotificationHelper($data['loggedUser']);
        $emailTemplate = $notificationHelper->loadTemplate('user_module_expiry_notification');
        if ($emailTemplate->enable == 1) {

            foreach ($users as $user) {
                Log::info("Email-sent: user_module_expiry_notification - User:" . $user->email);
                $emailTemplate
                    ->set_username($user->name)
                    ->set_module_name($data['xmeForm']->display_title)
                    ->set_days($data['xmeForm']->days)
                    ->set_by_username($data['loggedUser']->name);
                $notificationHelper->setTemplate($emailTemplate);
                $notificationHelper->send($user->email);
            }
        }
    }

    /**new assigned form reminder notifictionHelper function */
    public static function sendNewAssignedFormReminderNotification($users, $data)
    {
        $notificationHelper = new NotificationHelper($data['loggedUser']);
        $emailTemplate = $notificationHelper->loadTemplate('user_new_assigned_form_reminder_notification');
        if ($emailTemplate->enable == 1) {

            foreach ($users as $user) {
                Log::info("Email-sent: user_new_assigned_form_reminder_notification - User:" . $user->email);
                $emailTemplate
                    ->set_username($user->name)
                    ->set_module_name($data['xmeForm']->form->display_title)
                    ->set_days($data['xmeForm']->days)
                    ->set_by_username($data['loggedUser']->name);
                $notificationHelper->setTemplate($emailTemplate);
                $notificationHelper->send($user->email);
            }
        }
    }
}
