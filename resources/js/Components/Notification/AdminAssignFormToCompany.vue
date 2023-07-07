<template>
    <div class="notification-data">
        <div class="flex justify-between p-3">
            <div class='notification-text-block flex w-full'>
                <div class="bg-amber-500 w-10 h-10 rounded-full flex items-center justify-center">
                    <PencilSquareIcon class="h-6 w-6 text-white" aria-hidden="true" />
                </div>
                <div class="ml-6 w-full">
                    <div>
                        <h5 class="text-gray-400 text-lg"> Admin Module Assign To Company </h5>
                        <div class="flex justify-between">
                            <div class="themeNotifyName">
                                <span class="text-gray-600 text-base breakWord themeManageTitle">
                                    <b> {{ notification.data.from_user_name }}</b>
                                    Assign Module <b>
                                        {{ notification.data.form_name }}</b>
                                    to
                                    <b> {{ notification.data.company_name }}</b>
                                </span>
                            </div>
                            <span class='text-gray-400 text-xs'>{{ notification.differenceDate }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import {
    PencilSquareIcon
} from '@heroicons/vue/24/solid'
export default {
    name: 'AdminAssignFormToCompany',
    props: ['data', 'differenceDate', 'userRole', 'notification', 'user'],
    components: {
        PencilSquareIcon
    },
}
</script>