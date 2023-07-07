<template>
    <!-- Main modal -->
    <div id="duplicatePath"
        class="bg-bgModal fixed top-0 flex items-center justify-center left-0 right-0 z-50 w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-modal md:h-full">
        <div class="relative w-full h-auto max-w-xl md:h-auto">
            <!-- Modal content -->
            <div class="relative rounded-lg shadow bg-body">
                <!-- Modal header -->
                <div class="flex items-start justify-between p-4 rounded-t">
                    <div>
                        <h3 class="text-xl font-semibold text-gray-300">
                            {{ trans('lang.labels.reassign_module_settings') }}
                        </h3>
                    </div>
                    <button type="button" @click="isClose()" class="">
                        <XCircleIcon class="h-9 w-9 text-white" aria-hidden="true" />
                    </button>
                </div>

                <div class="h-fit theme-modal-body">
                        <div
                            class="p-4 pt-1 min-h-auto sm:min-h-auto lg:min-h-auto max-h-96 sm:max-h-96 lg:max-h-96
                                                                                                                     overflow-y-auto theme-modal-body">
                            <div class="flex items-center mb-4">
                                <input id="default-checkbox" type="checkbox" value="" v-model="form.is_reassign"
                                    class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 
                                                                                                                                dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:border-gray-600">
                                <label for="default-checkbox"
                                    class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">
                                    {{ trans('lang.labels.reassign_module') }}
                                </label>
                            </div>
                            <div v-if="form.is_reassign == 1">
                                <div>
                                    <div class="flex items-center justify-start sm:justify-between">
                                        <label class="text-sm font-medium text-gray-900 dark:text-gray-300">
                                            {{ trans('lang.labels.reassign_interval') }}
                                        </label>
                                        <select v-model="form.reassign_interval"
                                            class="bg-body w-40 text-sm text-gray-300 ml-3 px-3 py-1 rounded-lg focus:outline-0 border border-gray-400">
                                            <option value="weekly">Weekly</option>
                                            <option value="monthly">Monthly</option>
                                            <option value="custom">Custom</option>
                                        </select>
                                    </div>
                                    <div class="text-end">
                                        <span v-if="errors.reassign_interval" class="mt-2 text-xs text-red-600">{{
                                            errors.reassign_interval[0]
                                        }}</span>
                                    </div>
                                </div>
                                <div>
                                    <div v-if="form.reassign_interval == 'weekly'"
                                        class="mt-5 flex items-center justify-start sm:justify-between">
                                        <label class="text-sm font-medium text-gray-900 dark:text-gray-300">
                                           {{ trans('lang.labels.select_day_of_the_week') }}
                                        </label>
                                        <select v-model="form.week_reassign_day"
                                            class="bg-body w-40 text-sm text-gray-300 ml-3 px-3 py-1 rounded-lg focus:outline-0 border border-gray-400">
                                            <option>Select</option>
                                            <option value="1">Sunday</option>
                                            <option value="2">Moday</option>
                                            <option value="3">Tuesday</option>
                                            <option value="4">Wednesday</option>
                                            <option value="5">Thursday</option>
                                            <option value="6">Friday</option>
                                            <option value="7">Saturday</option>
                                        </select>
                                    </div>
                                    <div class="text-end">
                                        <span v-if="errors.week_reassign_day" class="mt-2 text-xs text-red-600">{{
                                            errors.week_reassign_day[0]
                                        }}</span>
                                    </div>
                                </div>
                                <div>
                                    <div v-if="form.reassign_interval == 'monthly'"
                                        class="mt-5 flex items-center justify-start sm:justify-between">
                                        <label class="text-sm font-medium text-gray-900 dark:text-gray-300">
                                            {{ trans('lang.labels.select_day_of_the_month') }}
                                        </label>
                                        <select v-model="form.month_reassign_day"
                                            class="bg-body w-40 text-sm text-gray-300 ml-3 px-3 py-1 rounded-lg focus:outline-0 border border-gray-400">
                                            <option>Select</option>
                                            <option v-for="i in 31" :key="i" :value="i">{{ i }}</option>
                                        </select>
                                    </div>
                                    <div class="text-end">
                                        <span v-if="errors.month_reassign_day" class="mt-2 text-xs text-red-600">{{
                                            errors.month_reassign_day[0]
                                        }}</span>
                                    </div>
                                </div>
                                <div>
                                    <div v-if="form.reassign_interval == 'custom'"
                                        class="mt-5 flex items-center justify-start sm:justify-between">
                                        <label class="text-sm font-medium text-gray-900 dark:text-gray-300">
                                            {{ trans('lang.labels.enter_custom_interval_in_days') }}
                                        </label>
                                        <input type="text" v-model="form.custom_interval"
                                            class="bg-body text-sm w-40 text-gray-300 ml-3 px-3 py-1 rounded-lg focus:outline-0 border border-gray-400">
                                    </div>
                                    <div class="text-end">
                                        <span v-if="errors.custom_interval" class="mt-2 text-xs text-red-600">{{
                                            errors.custom_interval[0]
                                        }}</span>
                                    </div>
                                </div>
                                <div>
                                    <div class="mt-5 flex items-center justify-start sm:justify-between">
                                        <label class="text-sm font-medium text-gray-900 dark:text-gray-300">
                                            {{ trans('lang.labels.select_time') }}
                                        </label>
                                        <input type="time" v-model="form.reassign_time"
                                            class="bg-body w-40 text-sm text-gray-300 ml-3 px-3 py-1 rounded-lg focus:outline-0 border border-gray-400">
                                    </div>
                                    <div class="text-end">
                                        <span v-if="errors.reassign_time" class="mt-2 text-xs text-red-600">{{
                                            errors.reassign_time[0]
                                        }}</span>
                                    </div>
                                </div>
                                <div class="flex items-center mt-5">
                                    <input id="endsettings" type="checkbox" value="" v-model="form.end_point"
                                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 
                                                                                                                                dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:border-gray-600">
                                    <label for="endsettings"
                                        class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">
                                        {{ trans('lang.labels.need_to_have_any_end_settings') }}
                                    </label>
                                </div>
                                <div v-if="form.end_point == true" class="">
                                    <div>
                                        <div class="mt-5 flex items-center justify-start sm:justify-between">
                                            <label class="text-sm font-medium text-gray-900 dark:text-gray-300">
                                                {{ trans('lang.labels.end_type') }}
                                            </label>
                                            <select v-model="form.end_type"
                                                class="bg-body text-sm text-gray-300 ml-3 px-3 py-1 rounded-lg focus:outline-0 border border-gray-400">
                                                <option>Select</option>
                                                <option value="fix">End of Date</option>
                                                <option value="custom">End of X Times</option>
                                            </select>
                                        </div>
                                        <div class="text-end">
                                            <span v-if="errors.end_type" class="mt-2 text-xs text-red-600">{{
                                                errors.end_type[0]
                                            }}</span>
                                        </div>
                                    </div>
                                    <div>
                                        <div v-if="form.end_type == 'fix'"
                                            class="mt-5 flex items-center justify-start sm:justify-between">
                                            <label class="text-sm font-medium text-gray-900 dark:text-gray-300">
                                                {{ trans('lang.labels.enter_date_to_end_schedule') }}
                                            </label>
                                            <input type="date" v-model="form.end_date"
                                                class="bg-body w-40 text-sm text-gray-300 ml-3 px-3 py-1 rounded-lg focus:outline-0 border border-gray-400">
                                        </div>
                                        <div class="text-end">
                                            <span v-if="errors.end_date" class="mt-2 text-xs text-red-600">{{
                                                errors.end_date[0]
                                            }}</span>
                                        </div>
                                    </div>
                                    <div>
                                        <div v-if="form.end_type == 'custom'"
                                            class="mt-5 flex items-center justify-start sm:justify-between">
                                            <label class="text-sm font-medium text-gray-900 dark:text-gray-300">
                                                {{ trans('lang.labels.enter_time_to_end_schedule') }}
                                            </label>
                                            <input type="number" v-model="form.end_times"
                                                class="bg-body w-40 text-sm text-gray-300 ml-3 px-3 py-1 rounded-lg focus:outline-0 border border-gray-400">
                                        </div>
                                        <div class="text-end">
                                            <span v-if="errors.end_times" class="mt-2 text-xs text-red-600">{{
                                                errors.end_times[0]
                                            }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Modal footer -->
                        <div
                            class="flex items-center justify-end p-0 pr-3 pb-6 pt-6 space-x-2 rounded-b dark:border-gray-600">
                            <button @click="isClose()"
                                class="text-btnCancelText bg-btnCancelBg rounded-lg text-sm font-medium px-5 py-2.5">
                                {{ trans('lang.modal.cancel') }}
                            </button>
                            <button @click="submitForm()" :disabled="processing" class="text-btnSubmitText bg-btnSubmitBg rounded-lg text-sm font-medium px-5 py-2.5">
                                {{ trans('lang.modal.done') }}
                            </button>
                        </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import {
    XCircleIcon
} from '@heroicons/vue/24/solid';
import { mapStores } from 'pinia'
import { useAppStore } from "@/store";
export default {
    name: 'Reassign Module',
    props: ['id', 'formId'],
    components: {
        XCircleIcon
    },
    data() {
        return {
            form: {
                is_reassign: 0,
                reassign_interval: '',
                month_reassign_day: '',
                week_reassign_day: '',
                custom_interval: '',
                end_point: false,
                end_type: '',
                end_date: '',
                end_times: '',
                reassign_time: ''
            },
            errors: {
                is_reassign: '',
                reassign_interval: '',
                month_reassign_day: '',
                week_reassign_day: '',
                custom_interval: '',
                end_point: '',
                end_type: '',
                end_date: '',
                end_times: '',
                reassign_time: '',

            },
            processing : false
        }
    },
    watch: {
    },
    methods: {
        isClose() {
            this.$emit("closeAssign");
        },
        submitForm() {
            this.processing = true;
            let that = this;
            axios.post('/api/reassign-setting/' + that.id, that.form)
                .then((res) => {
                    if (res.status == 200) {
                        that.form = {};
                        that.isClose();
                        let notification = {
                            heading: 'success',
                            subHeading: res.data.message,
                            type: "success",
                        };
                        that.appStore.setNotification(notification);
                    }
                })
                .catch((error) => {
                    that.errors = error.response.data.errors;
                    // error.response.status Check status code
                }).finally(() => {
                    that.processing = false;
                    //Perform action in always
                });
        },
        assignModuleForm() {
            let that = this;
            axios.get('/api/group-form/' + that.id + '?form_id=' + that.formId)
                .then((res) => {
                    if (res.status == 200) {
                        this.form = res.data.data;
                        (this.form.is_reassign == 1) ?   this.form.is_reassign = true: this.form.is_reassign = false;
                        (this.form.end_point == 1) ?   this.form.end_point = true: this.form.end_point = false;
                    }
                })
                .catch((error) => {

                    // error.response.status Check status code
                }).finally(() => {
                    //Perform action in always
                });
        }
    },
    created() {
        this.assignModuleForm();
    },
    computed: {
        ...mapStores(useAppStore),
    },
}
</script>
