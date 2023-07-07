<template>
    <div class="notification-data">
        <div class="flex justify-between p-3">
            <div class='notification-text-block flex w-full'>
                <div class="bg-amber-500 w-10 h-10 rounded-full flex items-center justify-center">
                    <CheckBadgeIcon class="h-6 w-6 text-white" aria-hidden="true" />
                </div>
                <div class="ml-6 w-full">
                    <div>
                        <h5 class="text-gray-400 text-lg">User Training Completed</h5>
                        <div class="flex justify-between">
                            <div class="themeNotifyName" v-if="notification.data.is_support">
                                <span class="text-gray-600 text-base breakWord themeManageTitle">
                                    XME Support(logged as <b>
                                        {{ notification.data.from_user_name }})</b>
                                    has been completed
                                    <b>{{ notification.data.form_name }}</b>
                                    training
                                    <span v-if="notification.data.group_name != null">
                                        to group
                                        <b> {{ notification.data.group_name }}</b>
                                    </span>
                                </span>
                            </div>
                            <div class="themeNotifyName" v-else>
                                <span class="text-gray-400 text-base breakWord themeManageTitle">
                                    <b> {{ notification.data.from_user_name }} </b>
                                    has been completed <b>
                                        {{ notification.data.form_name }}</b>
                                    training
                                    <span v-if="notification.data.group_name != null">
                                        to group
                                        <b> {{ notification.data.group_name }}</b>
                                    </span>
                                </span>
                            </div>
                            <span class='text-gray-600 text-sm'>{{ notification.differenceDate }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import { CheckBadgeIcon } from '@heroicons/vue/24/solid';
export default {
    name: 'UserTrainingComplete',
    props: ['data', 'differenceDate', 'userRole', 'notification','user'],
    components: {
        CheckBadgeIcon
    },
}
</script>