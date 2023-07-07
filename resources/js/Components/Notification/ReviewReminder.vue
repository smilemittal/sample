<template>
    <div class="notification-data">
        <div class="flex justify-between p-3">
            <div class='notification-text-block flex w-full'>
                <div class="bg-amber-500 w-10 h-10 rounded-full flex items-center justify-center">
                    <ArchiveBoxIcon class="h-6 w-6 text-white" aria-hidden="true" />
                </div>
                <div class="ml-6 w-full">
                    <div>
                        <h5 class="text-gray-400 text-lg">Review Reminder</h5>
                        <div class="flex justify-between">
                            <div class="themeNotifyName" v-if="notification.data.is_support">
                                <span class="text-base text-gray-600 breakWord themeManageTitle">
                                    XME Support(logged as
                                    <b>{{ notification.data.from_user_name }})</b>
                                    your
                                    <b>{{ notification.data.form_name }}</b>
                                    module is coming for review after 
                                    <b>{{ notification.data.days }}</b> days
                                </span>
                            </div>
                            <div class="themeNotifyName" v-else>
                                <span class="text-base text-gray-600 breakWord themeManageTitle">
                                    <b>{{ notification.data.from_user_name }}</b>
                                    your
                                    <b>{{ notification.data.form_name }}</b>
                                    module is coming for review after 
                                    <b>{{ notification.data.days }}</b> days
                                </span>
                            </div>
                            <span class='text-gray-400 text-sm'>{{ notification.differenceDate }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import {
    ArchiveBoxIcon
} from '@heroicons/vue/24/solid'
export default {
    name: 'ReviewReminder',
    props: ['data', 'differenceDate', 'userRole','notification','user'],
    components: {
        ArchiveBoxIcon
    },
}
</script>