<template>
    <!-- Main modal -->
    <div id="createMembers" class="bg-bgModal fixed top-0 flex items-center justify-center left-0 right-0 z-50 w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 
            h-full md:h-full">
        <div class="relative w-full h-auto max-w-4xl md:h-auto">
            <!-- Modal content -->
            <div class="relative rounded-lg shadow bg-body">
                <!-- Modal header -->
                <div class="flex items-start justify-between p-4 rounded-t">
                    <div>
                        <h3 class="text-xl font-semibold text-gray-300">
                           {{ trans('lang.labels.invite_new_user') }}
                        </h3>
                    </div>
                    <button type="button" @click="isCloseAdd()" class="">
                        <XCircleIcon class="h-9 w-9 text-white" aria-hidden="true" />
                    </button>
                </div>
                <!-- Form start here -->
                    <div class="p-6 space-y-6 h-full sm:h-full lg:h-full overflow-y-auto theme-modal-body">
                        <div class="grid md:grid-cols-2 sm:grid-cols-1 gap-3">
                            <div class="relative">
                                <label for="business_member"
                                    class="block mb-2 text-sm font-medium text-neutral-100 dark:text-white">{{ trans('lang.labels.email_address') }}
                                </label>
                                <input type="text" id="business_name"
                                    class="block px-2.5 pb-2.5 pt-2.5 w-full text-sm text-neutral-100 bg-sidebar rounded-lg border-1 border-gray-600 appearance-none dark:focus:border-amber-500 focus:outline-none focus:ring-0 focus:border-amber-500 peer"
                                    placeholder=" " v-model="email" />
                                <span v-if="errors.email" class="mt-2 text-sm text-red-600 theme-error-message">{{
                                    errors.email[0]
                                }}</span>
                            </div>
                            <div class="relative">
                                <label for="business_member" class="block mb-2 text-sm font-medium text-neutral-100">
                                    {{ trans('lang.labels.select_the_company') }}
                                </label>
                                <select id="business_member" v-model="company_id"
                                    class="bg-sidebar border border-gray-600 text-neutral-100 text-sm rounded-lg block w-full p-2.5 dark:focus:border-amber-500 focus:outline-none focus:ring-0 focus:border-amber-500">
                                    <option selected>Select</option>
                                    <option :value="company.id" v-for="company in companies" :key="company">{{ company.name
                                    }}</option>
                                </select>
                                <span v-if="errors.company_id" class="mt-2 text-sm text-red-600 theme-error-message">
                                {{ trans('lang.labels.please_select_the_company') }}</span>
                            </div>
                        </div>
                    </div>
                    <!-- Modal footer -->
                    <div class="flex items-center justify-end p-6 space-x-2 rounded-b dark:border-gray-600">
                        <button type="button" @click="isCloseAdd()"
                            class="text-btnCancelText bg-btnCancelBg font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                            {{ trans('lang.modal.cancel') }}</button>
                        <button :disabled="formProcess" @click="inviteMember()" class="text-btnSubmitText bg-btnSubmitBg rounded-lg text-sm font-medium px-5 py-2.5">
                            {{ trans('lang.modal.send_invite') }}</button>
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
    name: 'AddMember',
    components:{
        XCircleIcon
    },
    data() {
        return {
            email: '',
            company_id: '',
            companies: [],
            formProcess: false,
            errors: {
                email: "",
                company_id: ""
            }

        }
    },
    methods: {
        companiesSelection() {
            const headers = { "Content-Type": "application/json" };
            //for table data is loading after fetch ==>
            fetch("/api/companies?is_deleted=0&isArchived=0", headers)
                .then((response) => response.json())
                .then((data) => {
                    let that = this;
                    that.companies = data.data;
                });
        },
        inviteMember() {
            let that = this;
            that.formProcess = true;
            let formData = new FormData();
            formData.append('email', that.email);
            formData.append('company_id', that.company_id)
            axios.post('/api/members', formData)
                .then((res) => {
                   if(res.status == 200){
                        that.$emit('cancel');
                        that.email = '';
                        that.company_id = '';
                        that.isCloseAdd();
                        let notification = {
                        heading: 'success',
                        subHeading: res.data.message,
                        type: "success",
                        };
                        that.appStore.setNotification(notification);
                   }
                })
                .catch((error) => {
                    // error.response.status Check status code
                    that.errors = error.response.data.errors;
                })
                .finally(() => {
                    //submit button enabled after form submitted
                    that.formProcess = false;
                });

        },
        isCloseAdd() {
            this.$emit("closeAdd");
            this.$emit("refreshTable");
        },
    },
    created() {
        this.companiesSelection();
    },
    computed: {
        ...mapStores(useAppStore),
    },
}
</script>
