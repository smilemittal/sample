<template>
    <!-- Main modal -->
    <div id="deleteCompany"
        class="bg-bgModal fixed top-0 flex items-center justify-center left-0 right-0 z-50 w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-modal md:h-full">
        <div class="relative w-full h-auto max-w-xl md:h-auto">
            <!-- Modal content -->
            <div class="relative rounded-lg shadow bg-body">
                <!-- Modal header -->
                <div class="flex items-start justify-between p-4 rounded-t">
                    <div>
                        <h3 class="text-xl font-semibold text-gray-300">
                            {{ trans("lang.modal.update") }}
                        </h3>
                        <h3 class="text-base text-gray-400 mt-2">
                            {{ trans("lang.labels.enter_the_comments") }}
                        </h3>
                    </div>
                    <button type="button" @click="close()" class="">
                        <XCircleIcon class="h-9 w-9 text-white" aria-hidden="true" />
                    </button>
                </div>

                    <div class="p-6 space-y-6 overflow-y-auto theme-modal-body">
                        <div>
                            <textarea type="text" id="from_name" rows="7"
                                class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-neutral-100 bg-sidebar rounded-lg border-1
                                                                        border-transparent appearance-none dark:focus:border-amber-500 focus:outline-none focus:ring-0 focus:border-amber-500 peer"
                                placeholder="" v-model="form.commentedText"></textarea>
                            <span v-if="errors.commentedText"
                                                class="mt-2 text-sm text-red-600 theme-error-message">{{
                                                    errors.commentedText[0]
                                                }}</span>
                        </div>
                        <div class="flex items-center mt-3">
                            <input id="checkbox-all-search" type="checkbox" v-model="form.isupdate"
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded 
                                focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 
                                dark:focus:ring-offset-gray-800 focus:ring-2 dark:border-gray-600" />
                            <label for="checkbox-all-search" class="ml-3 text-gray-400">{{ trans("lang.labels.update_status") }}</label>
                        </div>
                    </div>

                    <!-- Modal footer -->
                    <div class="flex items-center justify-end p-6 space-x-2 rounded-b dark:border-gray-600 mt-3">
                        <button @click="close()" type="button"
                            class="text-btnCancelText bg-btnCancelBg font-medium rounded-lg
                                                                                                    text-sm px-5 py-2.5 text-center">
                            {{ trans("lang.modal.cancel") }}</button>
                        <button type="submit" @click="updateModule()"
                            class="text-btnSubmitText bg-btnSubmitBg rounded-lg text-sm font-medium px-5 py-2.5">{{ trans("lang.modal.update") }}</button>
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
    name: 'ReviewModal',
    props: ['data','id','form_id'],
    components:{
        XCircleIcon
    },
    data() {
        return {
            form: {
                commentedText: ''
            },
            errors: {
                commentedText: ""
            }
        }
    },
    methods: {
        close() {
            this.$emit('closeUpdate');
        },
        refresh() {
            this.$emit('refreshTable');
        },
        updateModule() {
            axios.put('/api/updated-company-form/' + this.id, { 
                'commentedText': this.form.commentedText,
                'isUpdateStatus' : this.form.isupdate,
                'form_id' :this.form_id
            })
                .then((res) => {
                    if (res.data.status == true) {
                        this.close();
                        let notification = {
                        heading: 'success',
                        subHeading: res.data.message,
                        type: "success",
                        };
                        this.appStore.setNotification(notification);
                        this.refresh();
                        
                    }
                })
                .catch((error) => {
                    this.errors = error.response.data.errors;
                }).finally(() => {
                    //Perform action in always
                });
        },
    },
    computed: {
        ...mapStores(useAppStore),
    },
}
</script>
