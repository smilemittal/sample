<template>
    <!-- Main modal -->
    <div id="assignCompany"
        class="bg-bgModal fixed top-0 flex items-center justify-center left-0 right-0 z-50 w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-modal md:h-full">
        <div class="relative w-full h-auto max-w-2xl md:h-auto">
            <!-- Modal content -->
            <div class="relative rounded-lg shadow bg-body">
                <!-- Modal header -->
                <div class="flex items-start justify-between p-6 rounded-t">
                    <div>
                        <h3 class="text-xl font-semibold text-gray-300">
                            {{ trans("lang.labels.select_the_schedule_date") }}
                        </h3>
                    </div>
                    <button type="button" @click="$emit('closeDate')" class="">
                        <XCircleIcon class="h-9 w-9 text-white" aria-hidden="true" />
                    </button>
                </div>
                <!-- Form start here -->
                    <div class="p-6 space-y-6 h-fit theme-modal-body">
                        <div class="flex justify-between items-center themeSchduleDate">
                            <VueDatePicker v-model="date" placeholder="Select Date" :min-date="new Date()" auto-apply="true"></VueDatePicker>
                        </div>
                    </div>
                    <!-- Modal footer -->
                    <div class="flex items-center justify-end p-6 space-x-2 rounded-b dark:border-gray-600">
                        <button @click="$emit('closeDate')"
                            class="text-btnCancelText bg-btnCancelBg rounded-lg text-sm font-medium px-5 py-2.5">
                            {{ trans("lang.modal.cancel") }}
                        </button>
                        <button :disabled="processing" @click="scheduleForm()"
                            class="text-btnSubmitText bg-btnSubmitBg rounded-lg text-sm font-medium px-5 py-2.5">
                            {{ trans("lang.modal.update") }}
                        </button>
                    </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
import { mapStores } from 'pinia'
import { useAppStore } from "@/store";
import VueDatePicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css'
import {
   XCircleIcon
} from '@heroicons/vue/24/solid'
import { dateTimeFormatServer } from "@/helpers";

export default {
    name: 'ScheduleDate',
    props: ['memberId', 'groupId'],
    components: {
        VueDatePicker,
        XCircleIcon
    },
    data() {
        return {
            reviewDateNew: new Date(),
            schedule:'',
            date: null,
            processing: false,
        }
    },
    methods: {
        scheduleForm() {
            this.processing = true;
            let formData = new FormData();
            let date = new Date(this.date);
            const formatDate = dateTimeFormatServer(date);
            formData.append("date",formatDate)
            formData.append('user', this.memberId)
            axios.post('/api/group-schedule-date/' + this.groupId, formData)
                .then((res) => {
                    if(res.status == 200){
                        this.$emit('closeDate');
                        let notification = {
                            heading: 'success',
                            subHeading: res.data.message,
                            type: "success",
                            };
                            this.appStore.setNotification(notification);
                    }
                })
                .catch((error) => {
                    // error.response.status Check status code
                }).finally(() => {
                    this.processing = false;
                    //Perform action in always
                });
        },
    },
    computed: {
        ...mapStores(useAppStore),
    },
}
</script>
