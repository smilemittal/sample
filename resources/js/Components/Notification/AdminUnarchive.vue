<template>
    <div class="notification-data">
        <div class="flex justify-between p-3">
            <div class='notification-text-block flex w-full'>
                <div class="bg-amber-500 w-10 h-10 rounded-full flex items-center justify-center">
                    <ArchiveBoxArrowDownIcon class="h-6 w-6 text-white" aria-hidden="true" />
                </div>
                <div class="ml-6 w-full">
                    <div>
                        <h5 class="text-gray-400 text-lg"> Admin Unarchive Module </h5>
                        <div class="flex justify-between">
                            <div class="themeNotifyName">
                                <span class="text-gray-600 text-base breakWord themeManageTitle">
                                    <b>{{ notification.data.from_user_name }}</b>
                                    Unarchive Module <b>
                                        {{ notification.data.form_name }}</b>
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
import {
    ArchiveBoxArrowDownIcon
} from '@heroicons/vue/24/solid'
export default {
    name: 'AdminUnarchive',
    props: ['data', 'differenceDate', 'userRole', 'notification', 'user'],
    components: {
        ArchiveBoxArrowDownIcon
    },
}
</script>