<template>
    <!-- Main modal -->
    <div id="archiveForm"
        class="bg-bgModal fixed top-0 flex items-center justify-center left-0 right-0 z-50 w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-modal md:h-full">
        <div class="relative w-full h-auto max-w-xl md:h-auto">
            <!-- Modal content -->
            <div class="relative rounded-lg shadow bg-body">
                <!-- Modal header -->
                <div class="flex items-start justify-between p-6 rounded-t">
                    <div>
                        <h3 class="text-xl font-semibold text-gray-300">
                            {{ trans('lang.labels.subject') }}
                        </h3>
                        <h3 class="text-base text-gray-400 mt-2">
                            {{ data.modalText }} 
                        </h3>
                    </div>

                    <button type="button" class="text-gray-400 bg-transparent hover:text-gray-900 rounded-lg 
                                        text-sm inline-flex items-center dark:hover:text-white" @click="isClose()">
                        <XCircleIcon class="h-8 w-8 text-white" aria-hidden="true" />
                    </button>
                </div>
                <div class="p-6" v-if="data.reason">
                    <div class="theme-input">
                        <div class="relative">
                            <input type="text" id="abn_name"
                                class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-neutral-100 bg-sidebar rounded-lg border-1 border-transparent appearance-none dark:focus:border-amber-500 focus:outline-none focus:ring-0 focus:border-amber-500 peer"
                                placeholder=" " v-model="form.reasons" />
                            <label for="abn_name"
                                class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-sidebar px-2 peer-focus:px-2 peer-focus:text-amber-500 peer-focus:dark:text-amber-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 left-1">
                                {{ trans('lang.labels.reason') }}
                            </label>
                        </div>
                        <span v-if="errors.reasons" class="mt-2 text-sm text-red-600 theme-error-message">{{
                                errors.reasons[0]
                            }}</span>
                    </div>
                </div>
                <!-- Modal footer -->
                <div class="flex items-center justify-end p-6 space-x-2 rounded-b dark:border-gray-600 mt-3">
                    <button @click="isClose()" type="button"
                        class="text-btnCancelText bg-btnCancelBg font-medium rounded-lg
                        text-sm px-5 py-2.5 text-center">{{ trans('lang.modal.cancel') }}</button>
                    <button :disabled="formProcess" @click="submitDeleteModelAction()" type="button"
                        class="text-btnSubmitText bg-btnSubmitBg rounded-lg text-sm font-medium px-5 py-2.5">{{
                            data.buttonText }}</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
import { mapStores } from 'pinia'
import { useAppStore } from "@/store";

import {
    XCircleIcon
} from '@heroicons/vue/24/solid'

export default {
    name: 'Delete',
    props: ['data'],
    components: {
        XCircleIcon
    },
    data() {
        return {
            formProcess: false,
            form: {
                reasons: ''
            },
            errors:{
                reasons: ''
            }
        }
    },
    methods: {
        isClose() {
            this.$emit("closeArchive");
        },
        submitDeleteModelAction() {
            this.formProcess = true;
            let that = this;
            let formData = new FormData();
            formData.append('subject_id', that.data.id);
            formData.append('actionType', that.data.actionType);
            formData.append('actionPage',that.data.actionPage);
            if (that.data.reason) {
                formData.append('reasons', that.form.reasons);
            }
            axios.post('/api/delete-action', formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            })
                .then((res) => {
                    //Perform Success Action
                    if (res.data.status == true) {
                        let notification = {
                            heading: 'success',
                            subHeading: res.data.message,
                            type: "success",
                        };
                        that.appStore.setNotification(notification);
                        that.isClose();
                    }
                })
                .catch((error) => {
                    // error.response.status Check status code
                    that.errors = error.response.data.errors;

                    document.body.style.overflow = '';
                }).finally(() => {
                    that.formProcess = false;
                    //Perform action in always
                });
        },

    },
    computed: {
        ...mapStores(useAppStore),
    },
}
</script>
