import {
    reactive
} from "vue";

export default {
    data() {
        return reactive({
            NOTIFICATION_TYPES: {
                userAddedToGroup: "App\\Notifications\\UserAddedToGroupNotification",
                assestAddedToGroup: "App\\Notifications\\AssestAddedToGroupNotification",
                newAddedToGroup: "App\\Notifications\\AddedGroupNotification",
                archiveFromToLibrary: "App\\Notifications\\ArchiveFormLibraryNotification",
                unarchiveFormToLibrary: "App\\Notifications\\UnarchiveFormLibraryNotification",
                removeMemberToGroup: "App\\Notifications\\DeleteMemberNotification",
                removeSubjectToIdentify: "App\\Notifications\\DeleteSubjectNotification",
                changeStatusToIdentify: "App\\Notifications\\SubjectStatusNotification",
                userCompleteTrainingHistory: "App\\Notifications\\UserTrainingCompleteNotification",
                adminArchiveToForm: "App\\Notifications\\AdminArchiveNotification",
                adminUnarchiveToForm: "App\\Notifications\\AdminUnarchiveNotification",
                finalisesubmitTosubject: "App\\Notifications\\FinaliseSubmitNotification",
                userbackburnerTosubject: "App\\Notifications\\BackBurnerNotification",
                assignUserPreviligesAdmin: "App\\Notifications\\AssignUserPrevilegesNotification",
                newModuleAssignToUser: "App\\Notifications\\UserAssignFormNotification",
                adminAssignFormToCompany: "App\\Notifications\\AdminAssignFormToCompanyNotification",
                revokeUserPreviligesAdmin: "App\\Notifications\\RevokeUserPrevilegesNotification",
                addSubjectToIdentify: "App\\Notifications\\AddSubjectNotification",
                finalSubmitSubjectDevelop: "App\\Notifications\\ThankYouFinalSubmitNotification",
                addDirectory: "App\\Notifications\\AddDirectoryNotification",
                updateDirectory: "App\\Notifications\\UpdateDirectoryNotification",
                addModuleToDirectory: "App\\Notifications\\AddModuleToDirectoryNotification",
                deleteDirectoryModule: "App\\Notifications\\DeleteDirectoryModuleNotification",
                addLearningPath: "App\\Notifications\\AddLearningPathNotification",
                updateLearningPath: "App\\Notifications\\UpdateLearningPathNotification",
                lockLearningPath: "App\\Notifications\\LockLearningPathNotification",
                deleteLearningPath: "App\\Notifications\\DeleteLearningPathNotification",
                addModuleToLearningpath: "App\\Notifications\\AddModuleToLearningPathNotification",
                removeLearningPathModule: "App\\Notifications\\RemoveLearningPathModuleNotification",
                addMemberToLearningpath: "App\\Notifications\\AddMemberToLearningPathNotification",
                removeLearningPathMember: "App\\Notifications\\RemoveLearningPathMemberNotification",
                completeLearningPathModule: "App\\Notifications\\CompletedLearningPathModuleNotification",
                archiveLearningPath: "App\\Notifications\\ArchiveLearningPathNotification",
                unarchiveLearningPath: "App\\Notifications\\UnarchiveLearningPathNotification",
                restoreLearningPath: "App\\Notifications\\RestoreLearningPathNotification",
                duplicateLearningPath: "App\\Notifications\\DuplicateLearningPathNotification",
                updateReviewModule: "App\\Notifications\\UpdateReviewModuleNotification",
                updateReviewModuleDate: "App\\Notifications\\UpdateReviewModuleDateNotification",
                reviewReminder: "App\\Notifications\\ReviewReminderNotification",
                updateCompanyModule: "App\\Notifications\\UpdateCompanyModuleNotification",
                requestToUpdateModule: "App\\Notifications\\RequestToUpdateModuleNotification",
                moveToLibrary: "App\\Notifications\\MoveToLibraryNotification",
                deleteDirectory: "App\\Notifications\\DeleteDirectoryNotification",
                moduleExpiry: "App\\Notifications\\ModuleExpiryNotification",
                newAssignFormReminder: "App\\Notifications\\NewAssignedFormReminderNotification",
                subjectArchive: "App\\Notifications\\SubjectArchiveNotification",
                formArchive: "App\\Notifications\\FormArchiveNotification",

            },
        });
    },
    methods: {
        isNumeric(n) {
            return !isNaN(parseFloat(n)) && isFinite(n);
        },
        getURLParams(key) {
            let uri = window.location.search.substring(1);
            let params = new URLSearchParams(uri);
            return params.get(key);
        },
        cdn(fileurl) {
            let url = fileurl;
            if (url.match(/^http[s]{0,1}:/g)) {
                return url;
            }
            if (!url.match(/^[/]/)) {
                url = "/" + url;
            }
            if (
                this.$page.props.APP_ENV == "local" ||
                !this.$page.props.ASSET_URL
            ) {
                return url;
            }
            return this.$page.props.ASSET_URL + url;
        },
        usedPercentage(key, used, allow) {
            if (used[key] < 0) {
                return 0;
            }

            if (!isNaN(allow[key]) && !isNaN(used[key])) {
                return Math.round((used[key] * 100) / allow[key]);
            }
            return 0;
        },
        isPlanLimitOver(features, can) {
            let is_over = false;
            features.forEach(function (feature) {
                //console.log([feature.key,can[feature.key]]);
                if (
                    can.hasOwnProperty(feature.key) &&
                    can[feature.key] === false
                ) {
                    is_over = true;
                    if (
                        feature.key == "team_member" &&
                        can["teams"] === false
                    ) {
                        is_over = false;
                    }
                }
            });
            return is_over;
        },
        numberShortFormatter(num, digits) {
            let lookup = [{
                    value: 1,
                    symbol: ""
                },
                {
                    value: 1e3,
                    symbol: "k"
                },
                {
                    value: 1e6,
                    symbol: "M"
                },
                {
                    value: 1e9,
                    symbol: "G"
                },
                {
                    value: 1e12,
                    symbol: "T"
                },
                {
                    value: 1e15,
                    symbol: "P"
                },
                {
                    value: 1e18,
                    symbol: "E"
                },
            ];
            let rx = /\.0+$|(\.[0-9]*[1-9])0+$/;
            let item = lookup
                .slice()
                .reverse()
                .find(function (item) {
                    return num >= item.value;
                });
            return item ?
                (num / item.value).toFixed(digits).replace(rx, "$1") +
                item.symbol :
                "0";
        },
        getNotificationText(notification) {
            let text = "";
            if (
                notification.type === this.NOTIFICATION_TYPES.userAddedToGroup
            ) {
                const notifiable_id = notification.notifiable_id;
                const to_user_id = notification.data.to_user_id;
                const to_name = notification.data.to_user_name;
                const from_name = notification.data.from_user_name;
                const group_name = notification.data.group_name;
                const is_support = notification.data.is_support;
                if (to_user_id == notifiable_id) {
                    if (is_support) {
                        text +=
                            "<div class='notify-block'><h4 class='text-gray-300 text-sm'>Add Member in Group</h4> <span class='notification-text'><span>XME Support(logged as <b>" +
                            from_name +
                            "</b>) added <b>" +
                            to_name +
                            "</b> to group <b>" +
                            group_name +
                            "</b></span></span> </div>";
                    } else {
                        text +=
                            "<div class='notify-block'><h4 class='text-gray-300 text-sm'>Add Member in Group</h4> <span class='notification-text'><span><b>" +
                            from_name +
                            "</b> added <b>" +
                            to_name +
                            "</b> to group <b>" +
                            group_name +
                            "</b></span> </span>";
                    }
                } else {
                    if (is_support) {
                        text +=
                            "<div class='notify-block'><h4 class='text-gray-300 text-sm'>Add Member in Group</h4> <span class='notification-text'><span>XME Support(logged as <b>" +
                            from_name +
                            "</b>) added <b>" +
                            to_name +
                            "</b> to group <b>" +
                            group_name +
                            "</b></span></span> </div>";
                    } else {
                        text +=
                            "<div class='notify-block'><h4 class='text-gray-300 text-sm'>Add Member in Group</h4> <span class='notification-text'><span><b>" +
                            from_name +
                            "</b> added <b>" +
                            to_name +
                            "</b> to group <b>" +
                            group_name +
                            "</b></span></span> ";
                    }
                }
            } else if (
                notification.type === this.NOTIFICATION_TYPES.assestAddedToGroup
            ) {
                const from_name = notification.data.from_user_name;
                const group_name = notification.data.group_name;
                const form_name = notification.data.form_name;
                const is_support = notification.data.is_support;
                if (is_support) {
                    text +=
                        "<div class='notify-block'><h4 class='text-gray-300 text-sm'>  New Module Added in Group </h4> <span class='notification-text'><span>XME Support(logged as <b>" +
                        from_name +
                        "</b>)  added <b>" +
                        form_name +
                        "</b>  to group <b>" +
                        group_name +
                        "</b></span></span> </div>";
                } else {
                    text +=
                        "<div class='notify-block'><h4 class='text-gray-300 text-sm'>  New Module Added in Group </h4> <span class='notification-text'><span><b>" +
                        from_name +
                        "</b>  added <b>" +
                        form_name +
                        "</b> to group <b>" +
                        group_name +
                        "</b></span></span> </div>";
                }
            } else if (
                notification.type === this.NOTIFICATION_TYPES.newAddedToGroup
            ) {
                const from_name = notification.data.from_user_name;
                const group_name = notification.data.group_name;
                const company_name = notification.data.company_name;
                const is_support = notification.data.is_support;
                if (is_support) {
                    text +=
                        "<div class='notify-block'><h4 class='text-gray-300 text-sm'> New Group Added </h4> <span class='notification-text'><span> XME Support(logged as <b>" +
                        from_name +
                        "</b>) created group <b>" +
                        group_name +
                        "</b> in company <b>" +
                        company_name +
                        "</b></span></span></div>";
                } else {
                    text +=
                        "<div class='notify-block'><h4 class='text-gray-300 text-sm'> New Group Added </h4><span class='notification-text'><span><b>" +
                        from_name +
                        "</b> created group " +
                        group_name +
                        " in company " +
                        company_name +
                        "</span></span></div>";
                }
            } else if (
                notification.type ===
                this.NOTIFICATION_TYPES.archiveFromToLibrary
            ) {
                const from_name = notification.data.from_user_name;
                const form_name = notification.data.form_name;
                const is_support = notification.data.is_support;
                if (is_support) {
                    text +=
                        "<div class='notify-block'><h4 class='text-gray-300 text-sm'> Archive Library Module</h4> <span class='notification-text'> <span> XME Support(logged as <b>" +
                        from_name +
                        "</b>) Aarchive Module <b>" +
                        form_name +
                        "</b></span></span> </div>";
                } else {
                    text +=
                        "<div class='notify-block'><h4 class='text-gray-300 text-sm'> Archive Library Module</h4> <span class='notification-text'><span><b>" +
                        from_name +
                        "</b>  Archive Module <b>" +
                        form_name +
                        "</b></span></span> </div>";
                }
            } else if (
                notification.type ===
                this.NOTIFICATION_TYPES.unarchiveFormToLibrary
            ) {
                const from_name = notification.data.from_user_name;
                const form_name = notification.data.form_name;
                const is_support = notification.data.is_support;

                if (is_support) {
                    text +=
                        "<div class='notify-block'><h4 class='text-gray-300 text-sm'> Unarchive Library Module</h4> <span class='notification-text'><span>XME Support(logged as <b>" +
                        from_name +
                        "</b>) Unarchive Module <b>" +
                        form_name +
                        "</b></span> </span></div>";
                } else {
                    text +=
                        "<div class='notify-block'><h4 class='text-gray-300 text-sm'> Unarchive Library Module</h4> <span class='notification-text'><span> <b>" +
                        from_name +
                        "</b>  Unarchive Module <b>" +
                        form_name +
                        "</b></span> </span> </div>";
                }
            }
            if (
                notification.type ===
                this.NOTIFICATION_TYPES.removeMemberToGroup
            ) {
                const from_name = notification.data.from_user_name;
                const group_name = notification.data.group_name;
                const member_name = notification.data.member_name;
                const is_support = notification.data.is_support;
                if (is_support) {
                    text +=
                        "<div class='notify-block'><h4 class='text-gray-300 text-sm'> Remove Member in Group</h4><span class='notification-text'> <span> XME Support(logged as <b>" +
                        from_name +
                        "</b>) Remove Member <b>" +
                        member_name +
                        "</b> to group <b>" +
                        group_name +
                        "</b></span></span> </div>";
                } else {
                    text +=
                        "<div class='notify-block'><h4 class='text-gray-300 text-sm'> Remove Member in Group</h4><span class='notification-text'> <span> <b>" +
                        from_name +
                        "</b> Remove Member <b>" +
                        member_name +
                        "</b> to group <b>" +
                        group_name +
                        "</b> </span> </span> </div>";
                }
            } else if (
                notification.type ===
                this.NOTIFICATION_TYPES.removeSubjectToIdentify
            ) {
                const from_name = notification.data.from_user_name;
                const subject_name = notification.data.subject_name;
                const is_support = notification.data.is_support;

                if (is_support) {
                    text +=
                        "<div class='notify-block'><h4 class='text-gray-300 text-sm'>Remove Subject in Identify</h4><span class='notification-text'><span>XME Support(logged as <b>" +
                        from_name +
                        "</b>) Remove Subject   <b>" +
                        subject_name +
                        "</b></span></span></div>";
                } else {
                    text +=
                        "<div class='notify-block'><h4 class='text-gray-300 text-sm'>Remove Subject in Identify</h4><span class='notification-text'><span> <b>" +
                        from_name +
                        " </b> Remove Subject <b>" +
                        subject_name +
                        "</b></span></span> </div>";
                }
            } else if (
                notification.type ===
                this.NOTIFICATION_TYPES.changeStatusToIdentify
            ) {
                const from_name = notification.data.from_user_name;
                const subject_name = notification.data.subject_name;
                const is_support = notification.data.is_support;
                if (is_support) {
                    text +=
                        "<div class='notify-block'><h4 class='text-gray-300 text-sm'>Send To Develop </h4><span class='notification-text'><span>XME Support(logged as <b>" +
                        from_name +
                        "</b>)Send to Subject  <b> " +
                        subject_name +
                        "</b></span></span></div>";
                } else {
                    text +=
                        "<div class='notify-block'><h4 class='text-gray-300 text-sm'>Send To Develop </h4><span class='notification-text'><span> <b>" +
                        from_name +
                        "</b> Send to Subject <b>" +
                        subject_name +
                        " </b></span></span></div>";
                }
            } else if (
                notification.type ===
                this.NOTIFICATION_TYPES.userCompleteTrainingHistory
            ) {
                const from_name = notification.data.from_user_name;
                const group_name = notification.data.group_name;
                const form_name = notification.data.form_name;
                const is_support = notification.data.is_support;

                if (is_support) {
                    text +=
                        "<div class='notify-block'><h4 class='text-gray-300 text-sm'>User Training Completed</h4><span class='notification-text'><span> XME Support(logged as <b>" +
                        from_name +
                        "</b>)  has been completed <b> " +
                        form_name +
                        "</b> training";
                    if (group_name != "")
                        text += " to group <b>" + group_name + " </b> ";
                    text += "</span></span></div>";
                } else {
                    text +=
                        "<div class='notify-block'><h4 class='text-gray-300 text-sm'>User Training Completed</h4><span class='notification-text'><span> <b>" +
                        from_name +
                        "</b> has been completed <b> " +
                        form_name +
                        "</b> training";
                    if (group_name != "")
                        text += " to group <b>" + group_name + " </b> ";
                    text += "</span></span></div>";
                }
            } else if (
                notification.type === this.NOTIFICATION_TYPES.adminArchiveToForm
            ) {
                const from_name = notification.data.from_user_name;
                const form_name = notification.data.form_name;

                text +=
                    "<div class='notify-block'><h4 class='text-gray-300 text-sm'> Admin Archive Module </h4><span class ='notification-text'> <span> <b>" +
                    from_name +
                    "</b> Archive Module <b>" +
                    form_name +
                    "</b> </span></span></div>";
            } else if (
                notification.type ===
                this.NOTIFICATION_TYPES.adminUnarchiveToForm
            ) {
                const from_name = notification.data.from_user_name;
                const form_name = notification.data.form_name;

                text +=
                    "<div class='notify-block'><h4 class='text-gray-300 text-sm'> Admin Unarchive Module </h4><span class ='notification-text'><span> <b>" +
                    from_name +
                    "</b> Unarchive Module <b> " +
                    form_name +
                    "</b> </span> </span></div>";
            } else if (
                notification.type ===
                this.NOTIFICATION_TYPES.finalisesubmitTosubject
            ) {
                const from_name = notification.data.from_user_name;
                const subject_name = notification.data.subject_name;
                const is_support = notification.data.is_support;

                if (is_support) {
                    text +=
                        "<div class='notify-block'><h4 class='text-gray-300 text-sm'>User Final Submit Subject in Develop </h4> <span class ='notification-text'><span> XME Support(logged as <b>" +
                        from_name +
                        "</b>) Final Submited Subject <b>" +
                        subject_name +
                        " </b> </span></span></div>";
                } else {
                    text +=
                        "<div class='notify-block'><h4 class='text-gray-300 text-sm'> User Final Submit Subject in Develop </h4><span class ='notification-text'><span> <b>" +
                        from_name +
                        "</b>  Final Submited Subject <b> " +
                        subject_name +
                        "</b> </span></span></div>";
                }
            } else if (
                notification.type ===
                this.NOTIFICATION_TYPES.userbackburnerTosubject
            ) {
                const from_name = notification.data.from_user_name;
                const subject_name = notification.data.subject_name;
                const is_support = notification.data.is_support;

                if (is_support) {
                    text +=
                        "<div class='notify-block'> <h4 class='text-gray-300 text-sm'>Send to Backburner</h4><span class ='notification-text'><span>XME Support(logged as <b>" +
                        from_name +
                        "</b>) Send to  Subject  <b>" +
                        subject_name +
                        "</b>  in backburner </span></span></div>";
                } else {
                    text +=
                        "<div class='notify-block'> <h4 class='text-gray-300 text-sm'>Send to Backburner</h4><span class ='notification-text'><span> <b>" +
                        from_name +
                        "</b> Send to Subject <b>" +
                        subject_name +
                        "</b> in Backburner </span></span></div>";
                }
            } else if (
                notification.type ===
                this.NOTIFICATION_TYPES.assignUserPreviligesAdmin
            ) {
                const from_name = notification.data.from_user_name;
                const user_name = notification.data.user_name;
                const is_support = notification.data.is_support;

                if (is_support) {
                    text +=
                        "<div class='notify-block'><h4 class='text-gray-300 text-sm'>User Assign Admin Previliges</h4><span class='notification-text'>Congratulation <span> <b>" +
                        user_name +
                        "</b> now  You  are Business Administrator by XME Support(logged as <b>" +
                        from_name +
                        "</b>)</span></span></div>";
                } else {
                    text +=
                        "<div class='notify-block'><h4 class='text-gray-300 text-sm'>User Assign Admin Previliges</h4><span class='notification-text'>Congratulation <span> <b>" +
                        user_name +
                        "</b>  now  You are Business Administrator by <b>" +
                        from_name +
                        "</b></span></span></div>";
                }
            } else if (
                notification.type ===
                this.NOTIFICATION_TYPES.newModuleAssignToUser
            ) {
                const from_name = notification.data.from_user_name;
                const user_name = notification.data.user_name;
                const form_name = notification.data.form_name;

                text +=
                    "<div class='notify-block'><h4 class='text-gray-300 text-sm'>New User Assign Module</h4> <span class='notification-text'> <span> <b>" +
                    from_name +
                    "</b> Assign Module <b>" +
                    form_name +
                    "</b>  to user <b>" +
                    user_name +
                    "</b> </span></span></div>";
            } else if (
                notification.type ===
                this.NOTIFICATION_TYPES.adminAssignFormToCompany
            ) {
                // console.log(form_name);
                const from_name = notification.data.from_user_name;
                const form_name = notification.data.form_name;
                const company_name = notification.data.company_name;

                text +=
                    "<div class='notify-block'><h4 class='text-gray-300 text-sm'>Admin Module Assign To Company </h4> <span class='notification-text'> <span> <b>" +
                    from_name +
                    "</b> Assign  <b>" +
                    form_name +
                    "</b>  to <b>" +
                    company_name +
                    "</b> </span></span></div>";
            } else if (
                notification.type ===
                this.NOTIFICATION_TYPES.revokeUserPreviligesAdmin
            ) {
                const from_name = notification.data.from_user_name;
                const user_name = notification.data.user_name;
                const is_support = notification.data.is_support;

                if (is_support) {
                    text +=
                        "<div class='notify-block'><h4 class='text-gray-300 text-sm'>User Revoke Admin Previliges</h4><span class='notification-text'>Hi! <span> <b>" +
                        user_name +
                        "</b> now You have became member and made by XME Support(logged as <b>" +
                        from_name +
                        "</b>)</span></span></div>";
                } else {
                    text +=
                        "<div class='notify-block'><h4 class='text-gray-300 text-sm'>User Revoke Admin Previliges</h4><span class='notification-text'>Hi! <span> <b>" +
                        user_name +
                        "</b> now You have became member and made by <b>" +
                        from_name +
                        "</b></span></span></div>";
                }
            } else if (
                notification.type ===
                this.NOTIFICATION_TYPES.addSubjectToIdentify
            ) {
                const from_name = notification.data.from_user_name;
                const subject_name = notification.data.subject_name;
                const is_support = notification.data.is_support;

                if (is_support) {
                    text +=
                        "<div class='notify-block'><h4 class='text-gray-300 text-sm'>Add Subject in Identify</h4><span class='notification-text'><span>XME Support(logged as <b>" +
                        from_name +
                        "</b>) Add Subject   <b>" +
                        subject_name +
                        "</b></span></span></div>";
                } else {
                    text +=
                        "<div class='notify-block'><h4 class='text-gray-300 text-sm'>Add Subject in Identify</h4><span class='notification-text'><span> <b>" +
                        from_name +
                        " </b> Add Subject <b>" +
                        subject_name +
                        "</b></span></span></div>";
                }
            } else if (
                notification.type ===
                this.NOTIFICATION_TYPES.finalSubmitSubjectDevelop
            ) {
                const from_name = notification.data.from_user_name;
                const subject_name = notification.data.subject_name;
                const is_support = notification.data.is_support;

                if (is_support) {
                    text +=
                        "<div class='notify-block'><h4 class='text-gray-300 text-sm'>User Completed Subject  </h4> <span class ='notification-text'><span> XME Support(logged as <b>" +
                        from_name +
                        "</b>) Thank You For the  Completed Subject <b>" +
                        subject_name +
                        " </b> </span></span></div>";
                } else {
                    text +=
                        "<div class='notify-block'><h4 class='text-gray-300 text-sm'> User Completed Subject  </h4><span class ='notification-text'><span> <b>" +
                        from_name +
                        "</b> Thank You For the  Completed Subject <b> " +
                        subject_name +
                        "</b> </span></span></div>";
                }

            } else if (notification.type === this.NOTIFICATION_TYPES.addDirectory) {
                const from_name = notification.data.from_user_name;
                const directory_name = notification.data.directory_name;
                const company_name = notification.data.company_name;
                const is_support = notification.data.is_support;

                if (is_support) {
                    text += "<div class='notify-block'><h4 class='text-gray-300 text-sm'>User Create Directory </h4><span class ='notification-text'><span> XME Support(logged as <b>" + from_name + "</b>) Created Directory <b>" + directory_name + " </b> in company <b>" + company_name + "</b> </span></span></div>";
                } else {
                    text += "<div class='notify-block'><h4 class='text-gray-300 text-sm'> User Create Directory </h4><span class ='notification-text'><span> <b>" + from_name + "</b> Created Directory <b> " + directory_name + "</b> in company <b>" + company_name + "</b> </span></span></div>";
                }
            } else if (notification.type === this.NOTIFICATION_TYPES.updateDirectory) {
                const from_name = notification.data.from_user_name;
                const directory_name = notification.data.directory_name;
                const company_name = notification.data.company_name;
                const is_support = notification.data.is_support;

                if (is_support) {
                    text += "<div class='notify-block'><h4 class='text-gray-300 text-sm'>User Update Directory </h4><span class ='notification-text'><span> XME Support(logged as <b>" + from_name + "</b>) Updated Directory <b>" + directory_name + " </b> in company <b>" + company_name + "</b> </span></span></div>";
                } else {
                    text += "<div class='notify-block'><h4 class='text-gray-300 text-sm'>User Update Directory </h4><span class ='notification-text'><span> <b>" + from_name + "</b> Updated Directory <b> " + directory_name + "</b> in company <b>" + company_name + "</b> </span></span></div>";
                }
            } else if (notification.type === this.NOTIFICATION_TYPES.addModuleToDirectory) {
                const from_name = notification.data.from_user_name;
                const directory_name = notification.data.directory_name;
                const module_name = notification.data.module_name;
                const company_name = notification.data.company_name;
                const is_support = notification.data.is_support;

                if (is_support) {
                    text += "<div class='notify-block'><h4 class='text-gray-300 text-sm'>User Add Module To Directory </h4><span class ='notification-text'><span> XME Support(logged as <b>" + from_name + "</b>) Add <b>" + module_name + " </b> Module To Directory <b>" + directory_name + " </b> in company <b>" + company_name + "</b> </span></span></div>";
                } else {
                    text += "<div class='notify-block'><h4 class='text-gray-300 text-sm'>User Add Module To Directory </h4><span class ='notification-text'><span> <b>" + from_name + "</b> Add <b>" + module_name + " </b> Module To Directory <b> " + directory_name + "</b> in company <b>" + company_name + "</b> </span></span></div>";
                }
            } else if (notification.type === this.NOTIFICATION_TYPES.deleteDirectoryModule) {
                const from_name = notification.data.from_user_name;
                const directory_name = notification.data.directory_name;
                const company_name = notification.data.company_name;
                const is_support = notification.data.is_support;

                if (is_support) {
                    text += "<div class='notify-block'><h4 class='text-gray-300 text-sm'>User Add Module To Directory </h4><span class ='notification-text'><span> XME Support(logged as <b>" + from_name + "</b>) delete Module <b>" + directory_name + " </b> Directory in company <b>" + company_name + "</b> </span></span></div>";
                } else {
                    text += "<div class='notify-block'><h4 class='text-gray-300 text-sm'>User Add Module To Directory </h4><span class ='notification-text'><span> <b>" + from_name + "</b> delete Module   <b> " + directory_name + "</b>Directory in company <b>" + company_name + "</b> </span></span></div>";
                }
            } else if (notification.type === this.NOTIFICATION_TYPES.addLearningPath) {
                const from_name = notification.data.from_user_name;
                const learning_path_title = notification.data.learning_path_title;
                const is_support = notification.data.is_support;

                if (is_support) {
                    text += "<div class='notify-block'><h4 class='text-gray-300 text-sm'>User Create Learning Path </h4><span class ='notification-text'><span> XME Support(logged as <b>" + from_name + "</b>) has been created new <b>" + learning_path_title + " </b> Learning Path </span></span></div>";
                } else {
                    text += "<div class='notify-block'><h4 class='text-gray-300 text-sm'>User Create Learning Path </h4><span class ='notification-text'><span> <b>" + from_name + "</b> has been created new <b> " + learning_path_title + "</b> Learning Path </span></span></div>";
                }
            } else if (notification.type === this.NOTIFICATION_TYPES.updateLearningPath) {
                const from_name = notification.data.from_user_name;
                const learning_path_title = notification.data.learning_path_title;
                const is_support = notification.data.is_support;

                if (is_support) {
                    text += "<div class='notify-block'><h4 class='text-gray-300 text-sm'>User Update Learning Path </h4><span class ='notification-text'><span> XME Support(logged as <b>" + from_name + "</b>) has been updated <b>" + learning_path_title + " </b> Learning Path </span></span></div>";
                } else {
                    text += "<div class='notify-block'><h4 class='text-gray-300 text-sm'>User Update Learning Path </h4><span class ='notification-text'><span> <b>" + from_name + "</b> has been updated <b> " + learning_path_title + "</b> Learning Path </span></span></div>";
                }
            } else if (notification.type === this.NOTIFICATION_TYPES.lockLearningPath) {
                const from_name = notification.data.from_user_name;
                const learning_path_title = notification.data.learning_path_title;
                const is_support = notification.data.is_support;

                if (is_support) {
                    text += "<div class='notify-block'><h4 class='text-gray-300 text-sm'>User Lock Learning Path </h4><span class ='notification-text'><span> XME Support(logged as <b>" + from_name + "</b>) has locked <b>" + learning_path_title + " </b> Learning Path </span></span></div>";
                } else {
                    text += "<div class='notify-block'><h4 class='text-gray-300 text-sm'>User Lock Learning Path </h4><span class ='notification-text'><span> <b>" + from_name + "</b> has locked <b> " + learning_path_title + "</b> Learning Path </span></span></div>";
                }
            } else if (notification.type === this.NOTIFICATION_TYPES.deleteLearningPath) {
                const from_name = notification.data.from_user_name;
                const learning_path_title = notification.data.learning_path_title;
                const is_support = notification.data.is_support;

                if (is_support) {
                    text += "<div class='notify-block'><h4 class='text-gray-300 text-sm'>User Delete Learning Path </h4><span class ='notification-text'><span> XME Support(logged as <b>" + from_name + "</b>) has deleted <b>" + learning_path_title + " </b> Learning Path </span></span></div>";
                } else {
                    text += "<div class='notify-block'><h4 class='text-gray-300 text-sm'>User Delete Learning Path </h4><span class ='notification-text'><span> <b>" + from_name + "</b> has deleted <b> " + learning_path_title + "</b> Learning Path </span></span></div>";
                }
            } else if (notification.type === this.NOTIFICATION_TYPES.addModuleToLearningpath) {
                const from_name = notification.data.from_user_name;
                const learning_path_title = notification.data.learning_path_title;
                const form_name = notification.data.form_name;
                const is_support = notification.data.is_support;

                if (is_support) {
                    text += "<div class='notify-block'><h4 class='text-gray-300 text-sm'>User Add Module To Learning Path </h4><span class ='notification-text'><span> XME Support(logged as <b>" + from_name + "</b>) has added <b>" + form_name + " </b> module to <b>" + learning_path_title + " </b> Learning Path </span></span></div>";
                } else {
                    text += "<div class='notify-block'><h4 class='text-gray-300 text-sm'>User Add Module To Learning Path </h4><span class ='notification-text'><span> <b>" + from_name + "</b> has added <b>" + form_name + " </b> module to <b> " + learning_path_title + "</b> Learning Path </span></span></div>";
                }
            } else if (notification.type === this.NOTIFICATION_TYPES.removeLearningPathModule) {
                const from_name = notification.data.from_user_name;
                const learning_path_title = notification.data.learning_path_title;
                const form_name = notification.data.form_name;
                const is_support = notification.data.is_support;

                if (is_support) {
                    text += "<div class='notify-block'><h4 class='text-gray-300 text-sm'>User Remove Learning Path Module </h4><span class ='notification-text'><span> XME Support(logged as <b>" + from_name + "</b>) has removed <b>" + form_name + " </b> module from <b>" + learning_path_title + " </b> Learning Path </span></span></div>";
                } else {
                    text += "<div class='notify-block'><h4 class='text-gray-300 text-sm'>User Remove Learning Path Module </h4><span class ='notification-text'><span> <b>" + from_name + "</b> has removed <b>" + form_name + " </b> module from <b> " + learning_path_title + "</b> Learning Path </span></span></div>";
                }
            } else if (notification.type === this.NOTIFICATION_TYPES.addMemberToLearningpath) {
                const from_name = notification.data.from_user_name;
                const learning_path_title = notification.data.learning_path_title;
                const member_name = notification.data.member_name;
                const is_support = notification.data.is_support;

                if (is_support) {
                    text += "<div class='notify-block'><h4 class='text-gray-300 text-sm'>User Add member To Learning Path </h4><span class ='notification-text'><span> XME Support(logged as <b>" + from_name + "</b>) has added <b>" + member_name + " </b> member to <b>" + learning_path_title + " </b> Learning Path </span></span></div>";
                } else {
                    text += "<div class='notify-block'><h4 class='text-gray-300 text-sm'>User Add member To Learning Path </h4><span class ='notification-text'><span> <b>" + from_name + "</b> has added <b>" + member_name + " </b> member to <b> " + learning_path_title + "</b> Learning Path </span></span></div>";
                }
            } else if (notification.type === this.NOTIFICATION_TYPES.removeLearningPathMember) {
                const from_name = notification.data.from_user_name;
                const learning_path_title = notification.data.learning_path_title;
                const member_name = notification.data.member_name;
                const is_support = notification.data.is_support;

                if (is_support) {
                    text += "<div class='notify-block'><h4 class='text-gray-300 text-sm'>User Remove Learning Path Member </h4><span class ='notification-text'><span> XME Support(logged as <b>" + from_name + "</b>) has remove <b>" + member_name + " </b> member from <b>" + learning_path_title + " </b> Learning Path </span></span></div>";
                } else {
                    text += "<div class='notify-block'><h4 class='text-gray-300 text-sm'>User Remove Learning Path Member </h4><span class ='notification-text'><span> <b>" + from_name + "</b> has remove <b>" + member_name + " </b> member from <b> " + learning_path_title + "</b> Learning Path </span></span></div>";
                }
            } else if (notification.type === this.NOTIFICATION_TYPES.completeLearningPathModule) {
                const from_name = notification.data.from_user_name;
                const learning_path_title = notification.data.learning_path_title;
                const form_name = notification.data.form_name;
                const is_support = notification.data.is_support;

                if (is_support) {
                    text += "<div class='notify-block'><h4 class='text-gray-300 text-sm'>User complete Learning Path Module </h4><span class ='notification-text'><span> XME Support(logged as <b>" + from_name + "</b>) has completed <b>" + form_name + " </b> module from <b>" + learning_path_title + " </b> Learning Path </span></span></div>";
                } else {
                    text += "<div class='notify-block'><h4 class='text-gray-300 text-sm'>User complete Learning Path Module </h4><span class ='notification-text'><span> <b>" + from_name + "</b> has completed <b>" + form_name + " </b> module from <b> " + learning_path_title + "</b> Learning Path </span></span></div>";
                }
            } else if (notification.type === this.NOTIFICATION_TYPES.archiveLearningPath) {
                const from_name = notification.data.from_user_name;
                const learning_path_title = notification.data.learning_path_title;
                const is_support = notification.data.is_support;

                if (is_support) {
                    text += "<div class='notify-block'><h4 class='text-gray-300 text-sm'>User Archive Learning Path </h4><span class ='notification-text'><span> XME Support(logged as <b>" + from_name + "</b>) has archive <b>" + learning_path_title + " </b> Learning Path </span></span></div>";
                } else {
                    text += "<div class='notify-block'><h4 class='text-gray-300 text-sm'>User Archive Learning Path </h4><span class ='notification-text'><span> <b>" + from_name + "</b> has archive <b> " + learning_path_title + "</b> Learning Path </span></span></div>";
                }
            } else if (notification.type === this.NOTIFICATION_TYPES.unarchiveLearningPath) {
                const from_name = notification.data.from_user_name;
                const learning_path_title = notification.data.learning_path_title;
                const is_support = notification.data.is_support;

                if (is_support) {
                    text += "<div class='notify-block'><h4 class='text-gray-300 text-sm'>User Unarchive Learning Path </h4><span class ='notification-text'><span> XME Support(logged as <b>" + from_name + "</b>) has unarchive <b>" + learning_path_title + " </b> Learning Path </span></span></div>";
                } else {
                    text += "<div class='notify-block'><h4 class='text-gray-300 text-sm'>User Unarchive Learning Path </h4><span class ='notification-text'><span> <b>" + from_name + "</b> has unarchive <b> " + learning_path_title + "</b> Learning Path </span></span></div>";
                }
            } else if (notification.type === this.NOTIFICATION_TYPES.restoreLearningPath) {
                const from_name = notification.data.from_user_name;
                const learning_path_title = notification.data.learning_path_title;
                const is_support = notification.data.is_support;

                if (is_support) {
                    text += "<div class='notify-block'><h4 class='text-gray-300 text-sm'>User Restore Learning Path </h4><span class ='notification-text'><span> XME Support(logged as <b>" + from_name + "</b>) has restore <b>" + learning_path_title + " </b> Learning Path </span></span></div>";
                } else {
                    text += "<div class='notify-block'><h4 class='text-gray-300 text-sm'>User Restore Learning Path </h4><span class ='notification-text'><span> <b>" + from_name + "</b> has restore <b> " + learning_path_title + "</b> Learning Path </span></span></div>";
                }
            } else if (notification.type === this.NOTIFICATION_TYPES.duplicateLearningPath) {
                const from_name = notification.data.from_user_name;
                const learning_path_title = notification.data.learning_path_title;
                const is_support = notification.data.is_support;

                if (is_support) {
                    text += "<div class='notify-block'><h4 class='text-gray-300 text-sm'>User Duplicate Learning Path </h4><span class ='notification-text'><span> XME Support(logged as <b>" + from_name + "</b>) has duplicate <b>" + learning_path_title + " </b> Learning Path </span></span></div>";
                } else {
                    text += "<div class='notify-block'><h4 class='text-gray-300 text-sm'>User Duplicate Learning Path </h4><span class ='notification-text'><span> <b>" + from_name + "</b> has duplicate <b> " + learning_path_title + "</b> Learning Path </span></span></div>";
                }
            } else if (notification.type === this.NOTIFICATION_TYPES.updateReviewModule) {
                const from_name = notification.data.from_user_name;
                const form_name = notification.data.form_name;
                const is_support = notification.data.is_support;

                if (is_support) {
                    text += "<div class='notify-block'><h4 class='text-gray-300 text-sm'>User Update Review Module </h4><span class ='notification-text'><span> XME Support(logged as <b>" + from_name + "</b>) has updated <b>" + form_name + " </b> review module </span></span></div>";
                } else {
                    text += "<div class='notify-block'><h4 class='text-gray-300 text-sm'>User Update Review Module </h4><span class ='notification-text'><span> <b>" + from_name + "</b> has updated <b> " + form_name + "</b> review module </span></span></div>";
                }
            } else if (notification.type === this.NOTIFICATION_TYPES.updateReviewModuleDate) {
                const from_name = notification.data.from_user_name;
                const form_name = notification.data.form_name;
                const is_support = notification.data.is_support;

                if (is_support) {
                    text += "<div class='notify-block'><h4 class='text-gray-300 text-sm'>User Update Review Module Date</h4><span class ='notification-text'><span> XME Support(logged as <b>" + from_name + "</b>) has updated review <b>" + form_name + " </b> module date </span></span></div>";
                } else {
                    text += "<div class='notify-block'><h4 class='text-gray-300 text-sm'>User Update Review Module Date</h4><span class ='notification-text'><span> <b>" + from_name + "</b> has updated review <b> " + form_name + "</b> module date </span></span></div>";
                }
            } else if (notification.type === this.NOTIFICATION_TYPES.reviewReminder) {
                const from_name = notification.data.from_user_name;
                const form_name = notification.data.form_name;
                const days = notification.data.days;
                const is_support = notification.data.is_support;

                if (is_support) {
                    text += "<div class='notify-block'><h4 class='text-gray-300 text-sm'>User Review Reminder</h4><span class ='notification-text'><span> XME Support(logged as <b>" + from_name + "</b>) your <b>" + form_name + " </b> module is coming  for review after <b>"+ days +"</b> days </span></span></div>";
                } else {
                    text += "<div class='notify-block'><h4 class='text-gray-300 text-sm'>User Review Reminder</h4><span class ='notification-text'><span> <b>" + from_name + "</b> your <b>" + form_name + " </b> module is coming  for review after <b>"+ days +"</b> </span></span></div>";
                }
            } else if (notification.type === this.NOTIFICATION_TYPES.updateCompanyModule) {
                const from_name = notification.data.from_user_name;
                const form_name = notification.data.form_name;
                const is_support = notification.data.is_support;

                if (is_support) {
                    text += "<div class='notify-block'><h4 class='text-gray-300 text-sm'>User Update Comany Module </h4><span class ='notification-text'><span> XME Support(logged as <b>" + from_name + "</b>) has update <b>" + form_name + " </b> company module </span></span></div>";
                } else {
                    text += "<div class='notify-block'><h4 class='text-gray-300 text-sm'>User Update Comany Module </h4><span class ='notification-text'><span> <b>" + from_name + "</b> has update <b>" + form_name + " </b> company module </span></span></div>";
                }
            } else if (notification.type === this.NOTIFICATION_TYPES.requestToUpdateModule) {
                const from_name = notification.data.from_user_name;
                const form_name = notification.data.form_name;
                const is_support = notification.data.is_support;

                if (is_support) {
                    text += "<div class='notify-block'><h4 class='text-gray-300 text-sm'>User Send Request To Admin </h4><span class ='notification-text'><span> XME Support(logged as <b>" + from_name + "</b>) has send request to admin for update <b>" + form_name + " </b> module </span></span></div>";
                } else {
                    text += "<div class='notify-block'><h4 class='text-gray-300 text-sm'>User Send Request To Admin </h4><span class ='notification-text'><span> <b>" + from_name + "</b> has send request to admin for update <b>" + form_name + " </b> module </span></span></div>";
                }
            } else if (notification.type === this.NOTIFICATION_TYPES.moveToLibrary) {
                const from_name = notification.data.from_user_name;
                const form_name = notification.data.form_name;
                const is_support = notification.data.is_support;

                if (is_support) {
                    text += "<div class='notify-block'><h4 class='text-gray-300 text-sm'>User Move To Library </h4><span class ='notification-text'><span> XME Support(logged as <b>" + from_name + "</b>) has move <b>" + form_name + " </b> module  to library</span></span></div>";
                } else {
                    text += "<div class='notify-block'><h4 class='text-gray-300 text-sm'>User Move To Library </h4><span class ='notification-text'><span> <b>" + from_name + "</b> has move <b>" + form_name + " </b> module to library </span></span></div>";
                }
            } else if (notification.type === this.NOTIFICATION_TYPES.deleteDirectory) {
                const from_name = notification.data.from_user_name;
                const directory_name = notification.data.directory_name;
                const is_support = notification.data.is_support;

                if (is_support) {
                    text += "<div class='notify-block'><h4 class='text-gray-300 text-sm'>User Delete Directory </h4><span class ='notification-text'><span> XME Support(logged as <b>" + from_name + "</b>) has deleted <b>" + directory_name + " </b> directory </span></span></div>";
                } else {
                    text += "<div class='notify-block'><h4 class='text-gray-300 text-sm'>User Delete Directory </h4><span class ='notification-text'><span> <b>" + from_name + "</b> has deleted <b>" + directory_name + " </b> directory </span></span></div>";
                }
            } else if (notification.type === this.NOTIFICATION_TYPES.moduleExpiry) {
                const from_name = notification.data.from_user_name;
                const form_name = notification.data.form_name;
                const days = notification.data.days;
                const is_support = notification.data.is_support;

                if (is_support) {
                    text += "<div class='notify-block'><h4 class='text-gray-300 text-sm'>User Module Expiry</h4><span class ='notification-text'><span> XME Support(logged as <b>" + from_name + "</b>) your <b>" + form_name + " </b> module will expiry in <b>"+ days +"</b> days </span></span></div>";
                } else {
                    text += "<div class='notify-block'><h4 class='text-gray-300 text-sm'>User Module Expiry</h4><span class ='notification-text'><span> <b>" + from_name + "</b> your <b>" + form_name + " </b> module will expiry in <b>"+ days +"</b>  days </span></span></div>";
                }
            } else if (notification.type === this.NOTIFICATION_TYPES.newAssignFormReminder) {
                const from_name = notification.data.from_user_name;
                const form_name = notification.data.form_name;
                const days = notification.data.days;
                const is_support = notification.data.is_support;

                if (is_support) {
                    text += "<div class='notify-block'><h4 class='text-gray-300 text-sm'>User New Assigned Form Reminder</h4><span class ='notification-text'><span> XME Support(logged as <b>" + from_name + "</b>)  have not checked  <b>" + form_name + " </b> module past  <b>"+ days +"</b> days </span></span></div>";
                } else {
                    text += "<div class='notify-block'><h4 class='text-gray-300 text-sm'>User New Assigned Form Reminder</h4><span class ='notification-text'><span> <b>" + from_name + "</b> have not checked <b>" + form_name + " </b> module past <b>"+ days +"</b>  days </span></span></div>";
                }
            } else if (notification.type === this.NOTIFICATION_TYPES.subjectArchive) {
                const from_name      = notification.data.from_user_name;
                const subject_name   = notification.data.subject_name;
                const action_type    = notification.data.actionType;
                const archive_reason = notification.data.archiveReason;
                const action_page    = notification.data.actionPage;
                const is_support     = notification.data.is_support;

                if (is_support) {
                    text += "<div class='notify-block'><h4 class='text-gray-300 text-sm'>User Subject " + action_type + " </h4><span class ='notification-text'><span> XME Support(logged as <b>" + from_name + "</b>) <b>" + action_type + " </b> subject <b>" + subject_name + " </b> in  <b>"+ action_page +"</b> phase for reason <b>"+ archive_reason +"</b> </span></span></div>";
                } else {
                    text += "<div class='notify-block'><h4 class='text-gray-300 text-sm'>User Subject " + action_type + " </h4><span class ='notification-text'><span> <b>" + from_name + "</b>  <b>" + action_type + " </b> subject <b>" + subject_name + " </b> in  <b>"+ action_page +"</b> phase for reason <b>"+ archive_reason +"</b> </span></span></div>";
                }
            } else if (notification.type === this.NOTIFICATION_TYPES.formArchive) {
                const from_name      = notification.data.from_user_name;
                const form_name      = notification.data.form_name;
                const action_type    = notification.data.actionType;
                const is_support     = notification.data.is_support;

                if (is_support) {
                    text += "<div class='notify-block'><h4 class='text-gray-300 text-sm'>User Module " + action_type + " </h4><span class ='notification-text'><span> XME Support(logged as <b>" + from_name + "</b>) has <b>" + action_type + " </b> module <b>" + form_name + " </b> </span></span></div>";
                } else {
                    text += "<div class='notify-block'><h4 class='text-gray-300 text-sm'>User Module " + action_type + " </h4><span class ='notification-text'><span> <b>" + from_name + "</b>  has <b>" + action_type + " </b> module <b>" + form_name + " </b> </span></span></div>";
                }
            }
            return text;
        },
        getNotificationLink(notification, role) {
            let to = `?read=${notification.id}`;
            if (
                role.name == "superadmin" &&
                (notification.type ===
                    this.NOTIFICATION_TYPES.userAddedToGroup ||
                    notification.type ===
                    this.NOTIFICATION_TYPES.assestAddedToGroup ||
                    notification.type ===
                    this.NOTIFICATION_TYPES.removeMemberToGroup)
            ) {
                to = "edit-group/" + notification.data.group_id + to;
            } else if (
                role.name == "superadmin" &&
                notification.type === this.NOTIFICATION_TYPES.newAddedToGroup
            ) {
                to = "manage-groups" + to;
            } else if (
                notification.type ===
                this.NOTIFICATION_TYPES.archiveFromToLibrary
            ) {
                to = "library" + to;
            } else if (
                notification.type ===
                this.NOTIFICATION_TYPES.unarchiveFormToLibrary
            ) {
                to = "library/archive" + to;
            } else if (
                notification.type ===
                this.NOTIFICATION_TYPES.removeSubjectToIdentify ||
                notification.type ===
                this.NOTIFICATION_TYPES.userbackburnerTosubject ||
                notification.type ===
                this.NOTIFICATION_TYPES.addSubjectToIdentify
            ) {
                to = "identify" + to;
            } else if (
                notification.type ===
                this.NOTIFICATION_TYPES.changeStatusToIdentify
            ) {
                to = "develop" + to;
            } else if (
                notification.type ===
                this.NOTIFICATION_TYPES.userCompleteTrainingHistory
            ) {
                to = "my-trainings" + to;
            } else if (
                role.name == "superadmin" &&
                (notification.type ===
                    this.NOTIFICATION_TYPES.adminArchiveToForm ||
                    notification.type ===
                    this.NOTIFICATION_TYPES.adminAssignFormToCompany)
            ) {
                to = "manage-forms" + to;
            } else if (
                role.name == "superadmin" &&
                notification.type ===
                this.NOTIFICATION_TYPES.adminUnarchiveToForm
            ) {
                to = "manage-forms/archive" + to;
            } else if (
                notification.type ===
                this.NOTIFICATION_TYPES.finalisesubmitTosubject ||
                notification.type ===
                this.NOTIFICATION_TYPES.finalSubmitSubjectDevelop ||
                notification.type ===
                this.NOTIFICATION_TYPES.subjectArchive
            ) {
                to = "develop" + to;
            } else if (
                role.name == "superadmin" &&
                (notification.type ===
                    this.NOTIFICATION_TYPES.assignUserPreviligesAdmin ||
                    notification.type ===
                    this.NOTIFICATION_TYPES.newModuleAssignToUser ||
                    this.NOTIFICATION_TYPES.revokeUserPreviligesAdmin)
            ) {
                to = "manage-users" + to;
            } else if (
                notification.type ===
                this.NOTIFICATION_TYPES.addDirectory ||
                notification.type ===
                this.NOTIFICATION_TYPES.deleteDirectory
            ) {
                to = "directory" + to;
            } else if (
                notification.type ===
                this.NOTIFICATION_TYPES.addLearningPath ||
                notification.type ===
                this.NOTIFICATION_TYPES.updateLearningPath ||
                notification.type ===
                this.NOTIFICATION_TYPES.lockLearningPath ||
                notification.type ===
                this.NOTIFICATION_TYPES.deleteLearningPath ||
                notification.type ===
                this.NOTIFICATION_TYPES.archiveLearningPath ||
                notification.type ===
                this.NOTIFICATION_TYPES.unarchiveLearningPath ||
                notification.type ===
                this.NOTIFICATION_TYPES.restoreLearningPath ||
                notification.type ===
                this.NOTIFICATION_TYPES.duplicateLearningPath
                ) {
                to = "learning-path" + to;
            } else if (
                notification.type ===
                this.NOTIFICATION_TYPES.addModuleToLearningpath ||
                notification.type ===
                this.NOTIFICATION_TYPES.removeLearningPathModule ||
                notification.type ===
                this.NOTIFICATION_TYPES.addMemberToLearningpath ||
                notification.type ===
                this.NOTIFICATION_TYPES.removeLearningPathMember
            ) {
                to = "edit-learning/" + notification.data.learning_path_id + to;
            } else if (
                notification.type ===
                this.NOTIFICATION_TYPES.completeLearningPathModule
            ) {
                to = "complete-learning/"  + notification.data.learning_path_id + to;
            } else if (
                notification.type ===
                this.NOTIFICATION_TYPES.updateReviewModule ||
                notification.type ===
                this.NOTIFICATION_TYPES.updateReviewModuleDate ||
                notification.type ===
                this.NOTIFICATION_TYPES.requestToUpdateModule ||
                notification.type ===
                this.NOTIFICATION_TYPES.moveToLibrary ||
                notification.type ===
                this.NOTIFICATION_TYPES.moduleExpiry ||
                notification.type ===
                this.NOTIFICATION_TYPES.newAssignFormReminder
            ) {
                to = "review-forms/"  + to;
            } else {
                to = "notification/list" + to; //+ notification.data.from_user_id
            }
            return "/" + to;
        },
    },
};
