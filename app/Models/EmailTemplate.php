<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailTemplate extends Model
{
    protected $table = 'email_templates';

    public const ENABLE_STATUS = 1;
    public const DISABLE_STATUS = 0;

    private $format_fields = [
        'date', 'time',
        'username', 'password', 'verification_url', 'date_time', 'company_name', 'invite_link', 'module_name', 'group_name',
        'by_username', 'new_username', 'subject_name', 'module_data', 'directory_name', 'learning_path_title', 'member_name',
        'days',
    ];

    public static function getGlobalParamters()
    {
        return [
            ['name' => 'username']
        ];
    }

    public static function getTemplateLocalData($name = null)
    {
        $data = [
            'new_user_notification' => [
                'title' => __('email.New User Notification'),
                'template_variables' => [
                    ['name' => 'password'],
                    // ['name' => 'verification_url'],
                ],
                'usage' => __('email.Sent to newly created/registered user'),
            ],
            'password_reset_link_notification' => [
                'title' => __('email.Password reset link notification'),
                'template_variables' => [
                    ['name' => 'reset_password_link'],
                ],
                'usage' => __('email.Whenever user forget their password, a link to reset their password will be sent by this email upon request.'),
            ],

            'user_invite_notification' => [
                'title' => __('email.User Invite Mail Notification'),
                'template_variables' => [
                    ['name' => 'company_name'],
                    ['name' => 'invite_link'],
                ],
                'usage' => __('email.Send Invite link to the user.'),
            ],
            'user_reinvite_notification' => [
                'title' => __('email.User ReInvite Mail Notification'),
                'template_variables' => [
                    ['name' => 'company_name'],
                    ['name' => 'invite_link'],
                ],
                'usage' => __('email.Send ReInvite link to the user.'),
            ],

            'user_assign_admin_privileges_notification' => [
                'title' => __('email.User Assign Admin Privileges Notification'),
                'template_variables' => [
                    ['name' => 'company_name'],
                    ['name' => 'username'],
                ],
                'usage' => __('email.Assigns admin privileges to a company user'),
            ],

            'user_group_added_notification' => [
                'title' => __('email.User Group Added Notification'),
                'template_variables' => [
                    ['name' => 'username'],
                    ['name' => 'group_name'],
                ],
                'usage' => __('email.Send alert to users when they are added to a group.'),
            ],
            'new_module_assigned_user_notification' => [
                'title' => __('email.New Module Assigned User Notification'),
                'template_variables' => [
                    ['name' => 'username'],
                    ['name' => 'module_data'],
                    ['name' => 'by_username'],
                ],
                'usage' => __('email.Send when a new module has been assigned to user by admin/company admin.'),
            ],


            'new_member_added_group_notification' => [
                'title' => __('email.New Member Added in Group Notification'),
                'template_variables' => [
                    ['name' => 'username'],
                    ['name' => 'by_username'],
                    ['name' => 'new_username'],
                    ['name' => 'group_name'],
                ],
                'usage' => __('email.Send alert to users when they are company added to user a group.'),
            ],



            'add_module_group_notification' => [
                'title' => __('email.Add Module Group Notification'),
                'template_variables' => [
                    ['name' => 'username'],
                    ['name' => 'by_username'],
                    ['name' => 'module_name'],
                    ['name' => 'group_name'],
                ],
                'usage' => __('email.Send alert to users when they are company added Module to a group.'),
            ],

            'register_new_user_company_notification' => [
                'title' => __('email. New User Register Company Notification'),
                'template_variables' => [
                    ['name' => 'username'],
                    ['name' => 'new_username'],
                    ['name' => 'company_name'],
                ],
                'usage' => __('email.Sent company to newly created/registered user.'),
            ],

            'user_assign_admin_privileges_company_notification' => [
                'title' => __('email.User Assign Admin Privileges Company Notification'),
                'template_variables' => [
                    ['name' => 'username'],
                    ['name' => 'new_username'],
                    ['name' => 'by_username'],
                ],
                'usage' => __('email.Sent company when user assign admin priviliges in company.'),
            ],

            'user_revoke_admin_privileges_company_notification' => [
                'title' => __('email.User Revoke Admin Privileges Company Notification'),
                'template_variables' => [
                    ['name' => 'username'],
                    ['name' => 'new_username'],
                    ['name' => 'by_username'],
                ],
                'usage' => __('email.Sent company when user revoke admin priviliges in company.'),
            ],


            'user_completed_module_training_notification' => [
                'title' => __('email.User Completed Module Training Notification'),
                'template_variables' => [
                    ['name' => 'username'],
                    ['name' => 'module_name'],
                    ['name' => 'by_username'],
                ],
                'usage' => __('email.Sent all when user completed training'),
            ],


            'members_delete_notification' => [
                'title' => __('email.Members Delete Notification'),
                'template_variables' => [
                    ['name' => 'username'],
                    ['name' => 'new_username'],
                    ['name' => 'group_name'],
                    ['name' => 'by_username'],
                ],
                'usage' => __('email.Sent all when member remove in group'),
            ],


            'subject_delete_notification' => [
                'title' => __('email.Subject Remove Notification'),
                'template_variables' => [
                    ['name' => 'username'],
                    ['name' => 'subject_name'],
                    ['name' => 'by_username'],
                ],
                'usage' => __('email.Sent all when subject remove'),
            ],


            'new_group_added_notification' => [
                'title' => __('email.New Group Added Notification'),
                'template_variables' => [
                    ['name' => 'username'],
                    ['name' => 'group_name'],
                    ['name' => 'company_name'],
                    ['name' => 'by_username'],
                ],
                'usage' => __('email.Sent all when add group in company'),
            ],



            'library_archive_module_notification' => [
                'title' => __('email.library Archive Module Notification'),
                'template_variables' => [
                    ['name' => 'username'],
                    ['name' => 'module_name'],
                    ['name' => 'by_username'],
                ],
                'usage' => __('email.Sent all when archive module'),
            ],
            'library_Unarchive_module_notification' => [
                'title' => __('email.library Unarchive Module Notification'),
                'template_variables' => [
                    ['name' => 'username'],
                    ['name' => 'module_name'],
                    ['name' => 'by_username'],
                ],
                'usage' => __('email.Sent all when Unarchive module'),
            ],

            'User_final_submit_subject_Notification' => [
                'title' => __('email.User Final Submited Subject'),
                'template_variables' => [
                    ['name' => 'username'],
                    ['name' => 'subject_name'],
                    ['name' => 'by_username'],
                ],
                'usage' => __('email.Sent all when user final submited subject'),
            ],

            'user_submit_subject_identify_company_notification' => [
                'title' => __('email.User Submit Subject Identify'),
                'template_variables' => [
                    ['name' => 'username'],

                ],
                'usage' => __('email.Sent  when user submited subject in identify'),
            ],

            'new_module_assigned_company_notification' => [
                'title' => __('email.New Module Assigned Company Notification'),
                'template_variables' => [
                    ['name' => 'username'],
                    ['name' => 'module_data'],
                    ['name' => 'company_name'],
                    ['name' => 'by_username'],
                ],
                'usage' => __('email.Send when a new module has been assigned to company by admin.'),
            ],
            'user_final_submit_subject_devlop_company_notification' => [
                'title' => __('email.User Final Submit Subject Develop'),
                'template_variables' => [
                    ['name' => 'username'],

                ],
                'usage' => __('email.Sent  when user final submited subject in develop'),
            ],


            'user_create_subject_identify_notification' => [
                'title' => __('email.Subject Created Notification'),
                'template_variables' => [
                    ['name' => 'username'],
                    ['name' => 'subject_name'],

                ],
                'usage' => __('email.Sent company admin when subject created '),
            ],


            'user_add_directory_notification' => [
                'title' => __('email.User Directory Created Notification'),
                'template_variables' => [
                    ['name' => 'username'],
                    ['name' => 'directory_name'],
                    ['name' => 'company_name'],
                    ['name' => 'by_username'],
                ],
                'usage' => __('email.Sent when user created directory '),
            ],


            'user_update_directory_notification' => [
                'title' => __('email.User Directory Updated Notification'),
                'template_variables' => [
                    ['name' => 'username'],
                    ['name' => 'directory_name'],
                    ['name' => 'company_name'],
                    ['name' => 'by_username'],
                ],
                'usage' => __('email.Sent when user updated directory '),
            ],

            'user_add_module_to_directory_notification' => [
                'title' => __('email.User Directory Add Module Notification'),
                'template_variables' => [
                    ['name' => 'username'],
                    ['name' => 'directory_name'],
                    ['name' => 'company_name'],
                    ['name' => 'module_name'],
                    ['name' => 'by_username'],
                ],
                'usage' => __('email.Sent when user add module to directory '),
            ],

            'user_delete_directory_module_notification' => [
                'title' => __('email.User Deleted Directory Module Notification'),
                'template_variables' => [
                    ['name' => 'username'],
                    ['name' => 'directory_name'],
                    ['name' => 'company_name'],
                    ['name' => 'form_name'],
                    ['name' => 'by_username'],
                ],
                'usage' => __('email.Sent when user delete directory module '),
            ],

            'user_created_learning_path_notification' => [
                'title' => __('email.User Created Learning Path Notification'),
                'template_variables' => [
                    ['name' => 'username'],
                    ['name' => 'learning_path_title'],
                    ['name' => 'by_username'],
                ],
                'usage' => __('email.Sent when user create learning path'),
            ],

            'user_updated_learning_path_notification' => [
                'title' => __('email.User Updated Learning Path Notification'),
                'template_variables' => [
                    ['name' => 'username'],
                    ['name' => 'learning_path_title'],
                    ['name' => 'by_username'],
                ],
                'usage' => __('email.Sent when user update learning path'),
            ],

            'user_locked_learning_path_notification' => [
                'title' => __('email.User Locked Learning Path Notification'),
                'template_variables' => [
                    ['name' => 'username'],
                    ['name' => 'learning_path_title'],
                    ['name' => 'by_username'],
                ],
                'usage' => __('email.Sent when user lock learning path'),
            ],

            'user_deleted_learning_path_notification' => [
                'title' => __('email.User Delete Learning Path Notification'),
                'template_variables' => [
                    ['name' => 'username'],
                    ['name' => 'learning_path_title'],
                    ['name' => 'by_username'],
                ],
                'usage' => __('email.Sent when user delete learning path'),
            ],

            'user_add_module_to_learning_path_notification' => [
                'title' => __('email.User Add Module To Learning Path Notification'),
                'template_variables' => [
                    ['name' => 'username'],
                    ['name' => 'learning_path_title'],
                    ['name' => 'module_name'],
                    ['name' => 'by_username'],
                ],
                'usage' => __('email.Sent when user add module to learning path'),
            ],

            'user_remove_learning_path_module_notification' => [
                'title' => __('email.User Remove Learning Path Module Notification'),
                'template_variables' => [
                    ['name' => 'username'],
                    ['name' => 'learning_path_title'],
                    ['name' => 'module_name'],
                    ['name' => 'by_username'],
                ],
                'usage' => __('email.Sent when user remove learning path module'),
            ],

            'user_add_member_to_learning_path_notification' => [
                'title' => __('email.User Add Member To Learning Path Notification'),
                'template_variables' => [
                    ['name' => 'username'],
                    ['name' => 'learning_path_title'],
                    ['name' => 'member_name'],
                    ['name' => 'by_username'],
                ],
                'usage' => __('email.Sent when user add member to learning path'),
            ],

            'user_remove_learning_path_member_notification' => [
                'title' => __('email.User Remove Learning Path Member Notification'),
                'template_variables' => [
                    ['name' => 'username'],
                    ['name' => 'learning_path_title'],
                    ['name' => 'member_name'],
                    ['name' => 'by_username'],
                ],
                'usage' => __('email.Sent when user remove learning path member'),
            ],

            'user_complete_learning_path_module_notification' => [
                'title' => __('email.User Complete Learning Path Module Notification'),
                'template_variables' => [
                    ['name' => 'username'],
                    ['name' => 'learning_path_title'],
                    ['name' => 'module_name'],
                    ['name' => 'by_username'],
                ],
                'usage' => __('email.Sent when user complete learning path module'),
            ],

            'user_archive_learning_path_notification' => [
                'title' => __('email.User Archive Learning Path Notification'),
                'template_variables' => [
                    ['name' => 'username'],
                    ['name' => 'learning_path_title'],
                    ['name' => 'by_username'],
                ],
                'usage' => __('email.Sent when user archive learning path'),
            ],

            'user_unarchive_learning_path_notification' => [
                'title' => __('email.User Unarchive Learning Path Notification'),
                'template_variables' => [
                    ['name' => 'username'],
                    ['name' => 'learning_path_title'],
                    ['name' => 'by_username'],
                ],
                'usage' => __('email.Sent when user unarchive learning path'),
            ],

            'user_restore_learning_path_notification' => [
                'title' => __('email.User Restore Learning Path Notification'),
                'template_variables' => [
                    ['name' => 'username'],
                    ['name' => 'learning_path_title'],
                    ['name' => 'by_username'],
                ],
                'usage' => __('email.Sent when user restore learning path'),
            ],

            'user_duplicate_learning_path_notification' => [
                'title' => __('email.User Duplicate Learning Path Notification'),
                'template_variables' => [
                    ['name' => 'username'],
                    ['name' => 'learning_path_title'],
                    ['name' => 'by_username'],
                ],
                'usage' => __('email.Sent when user duplicate learning path'),
            ],

            'user_update_review_module_notification' => [
                'title' => __('email.User Update Review module Notification'),
                'template_variables' => [
                    ['name' => 'username'],
                    ['name' => 'module_name'],
                    ['name' => 'by_username'],
                ],
                'usage' => __('email.Sent when user update review module'),
            ],

            'user_update_review_module_date_notification' => [
                'title' => __('email.User Update Review module Date Notification'),
                'template_variables' => [
                    ['name' => 'username'],
                    ['name' => 'module_name'],
                    ['name' => 'by_username'],
                ],
                'usage' => __('email.Sent when user update review module date'),
            ],

            'user_review_reminder_notification' => [
                'title' => __('email.User Review Reminder Notification'),
                'template_variables' => [
                    ['name' => 'username'],
                    ['name' => 'module_name'],
                    ['name' => 'days'],
                    ['name' => 'by_username'],
                ],
                'usage' => __('email.Sent when user review reminder'),
            ],

            'user_update_company_module_notification' => [
                'title' => __('email.User Update Company Module Notification'),
                'template_variables' => [
                    ['name' => 'username'],
                    ['name' => 'module_name'],
                    ['name' => 'by_username'],
                ],
                'usage' => __('email.Sent when user update company module'),
            ],

            'user_request_to_update_module_notification' => [
                'title' => __('email.User Request To Update Module Notification'),
                'template_variables' => [
                    ['name' => 'username'],
                    ['name' => 'module_name'],
                    ['name' => 'by_username'],
                ],
                'usage' => __('email.Sent when user request to update module'),
            ],

            'user_move_to_library_notification' => [
                'title' => __('email.User Move To Library Notification'),
                'template_variables' => [
                    ['name' => 'username'],
                    ['name' => 'module_name'],
                    ['name' => 'by_username'],
                ],
                'usage' => __('email.Sent when user move to library'),
            ],

            'user_delete_directory_notification' => [
                'title' => __('email.User Delete Directory Notification'),
                'template_variables' => [
                    ['name' => 'username'],
                    ['name' => 'directory_name'],
                    ['name' => 'by_username'],
                ],
                'usage' => __('email.Sent when user delete directory'),
            ],

            'user_module_expiry_notification' => [
                'title' => __('email.User Module Expiry Notification'),
                'template_variables' => [
                    ['name' => 'username'],
                    ['name' => 'module_name'],
                    ['name' => 'days'],
                    ['name' => 'by_username'],
                ],
                'usage' => __('email.Sent when user module expiry'),
            ],

            'user_new_assigned_form_reminder_notification' => [
                'title' => __('email.User New Assigned Form Reminder Notification'),
                'template_variables' => [
                    ['name' => 'username'],
                    ['name' => 'module_name'],
                    ['name' => 'days'],
                    ['name' => 'by_username'],
                ],
                'usage' => __('email.Sent when user assingned form reminder'),
            ],

        ];

        if ($name) {
            return $data[$name];
        } else {
            return $data;
        }
    }

    public $timestamps = false;

    protected $fillable = [
        'name',
        'subject',
        'content',
        'display_order',
        'enable'
    ];

    public static function get_by_name($name)
    {
        $email_templates = self::where('name', $name);
        if ($email_templates->count() <= 0) {
            throw new \Exception(__('email.Empty Email Template', ['email_template' => $name]));
        }

        return $email_templates->first();
    }

    public function is_enable()
    {
        return $this->enable == 1 ? true : false;
    }

    public function set_date($date = '')
    {
        $this->date = $date;
        return $this;
    }

    public function set_time($time = '')
    {
        $this->time = $time;
        return $this;
    }

    public function set_weekday($weekday = '')
    {
        $this->class_weekday = $weekday;
        return $this;
    }

    public function set_username($username = '')
    {
        $this->username = $username;
        return $this;
    }

    public function set_password($password = '')
    {
        $this->password = $password;
        return $this;
    }

    public function set_verification_url($verification_url = '')
    {
        $this->verification_url = $verification_url;
        return $this;
    }

    public function set_signin_btn($signin_btn = '')
    {
        $this->signin_btn = $signin_btn;
        return $this;
    }

    public function get_format($field)
    {
        $format_content = $this->$field;

        foreach ($this->format_fields as $field) {
            if (strpos($this->$field, '{' . $field . '}') != -1) {
                $format_content = str_replace('{' . $field . '}', $this->$field, $format_content);
            }
        }

        return $format_content;
    }

    public static function get_header()
    {
        return Setting::get_value('email_header_text');
    }

    public static function get_footer()
    {
        return Setting::get_value('email_footer_text');
    }

    public function set_action_btns($action_btns = '')
    {
        $this->action_btns = $action_btns;
        return $this;
    }

    public function set_reset_password_link($link = '')
    {
        $this->reset_password_link = $link;
        return $this;
    }

    public function set_loginlink($link = '')
    {
        $this->loginlink = $link;
        return $this;
    }

    public function set_company_name($company_name = '')
    {
        $this->company_name = $company_name;
        return $this;
    }

    public function set_invite_link($invite_link = '')
    {
        $this->invite_link = $invite_link;
        return $this;
    }

    public function set_module_name($module_name = '')
    {
        $this->module_name = $module_name;
        return $this;
    }

    public function set_group_name($group_name = '')
    {
        $this->group_name = $group_name;
        return $this;
    }

    public function set_by_username($by_username = '')
    {
        $this->by_username = $by_username;
        return $this;
    }

    public function set_new_username($new_username = '')
    {
        $this->new_username = $new_username;
        return $this;
    }

    public function set_subject_name($subject_name = '')
    {
        $this->subject_name = $subject_name;
        return $this;
    }

    public function set_module_data($module_data = '')
    {
        $this->module_data = $module_data;
        return $this;
    }

    public function set_directory_name($directory_name = '')
    {
        $this->directory_name = $directory_name;
        return $this;
    }

    public function set_learning_path_title($learning_path_title = '')
    {
        $this->learning_path_title = $learning_path_title;
        return $this;
    }

    public function set_member_name($member_name = '')
    {
        $this->member_name = $member_name;
        return $this;
    }

    public function set_days($days = '')
    {
        $this->days = $days;
        return $this;
    }
}
