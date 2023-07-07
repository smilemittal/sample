<template>
    <div class="notification-data">
        <div class="flex justify-between p-3">
            <div class='notification-text-block flex w-full'>
                <div class="bg-amber-500 w-10 h-10 rounded-full flex items-center justify-center">
                    <AdjustmentsVerticalIcon class="h-6 w-6 text-white" aria-hidden="true" />
                </div>
                <div class="ml-6 w-full">
                    <div>
                        <h5 class="text-gray-400 text-lg">Subject {{ notification.data.actionType }}
                        </h5>
                        <div class="flex justify-between">
                            <div class="themeNotifyName" v-if="notification.is_support">
                                <span class="text-gray-600 text-base breakWord themeManageTitle">
                                    XME
                                    Support(logged as
                                    <b>{{ data.data.from_user_name }})</b>
                                    {{ data.data.actionType }} Subject
                                    "{{ data.data.subject_name }}"
                                    in {{ data.data.actionPage }} phase
                                    <span v-if="notification.data.actionType == 'Archive'">
                                        for reason "{{ data.archiveReason }}"
                                    </span>
                                </span>
                            </div>
                            <div class="themeNotifyName" v-else>
                                <span class="text-gray-600 text-base breakWord themeManageTitle">

                                    <b>{{ data.from_user_name }}</b>
                                    {{ data.actionType }} Subject
                                    "{{ data.subject_name }}"
                                    in {{ data.actionPage }} phase
                                    <span v-if="notification.data.actionType == 'Archive'">
                                        for reason "{{ data.archiveReason }}"
                                    </span>
                                </span>
                            </div>
                            <span class='text-gray-400 text-sm'>{{  notification.differenceDate }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import { AdjustmentsVerticalIcon } from '@heroicons/vue/24/solid';
export default {
    name: 'SubjectArchive',
    props: ['data', 'differenceDate', 'userRole', 'notification', 'user'],
    components: {
        AdjustmentsVerticalIcon
    },
}
</script>