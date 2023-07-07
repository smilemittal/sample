<template>
    <!-- Main modal -->
    <div id="assignModule" class="bg-bgModal fixed top-0 flex items-center justify-center left-0 right-0 z-50 w-full p-4 overflow-x-hidden overflow-y-hidden md:inset-0 
                            h-full md:h-full">
        <div class="relative w-full h-auto max-w-4xl md:h-auto">
            <!-- Modal content -->
            <div class="relative rounded-lg shadow bg-body">
                <!-- Modal header -->
                <div class="flex items-start justify-between p-4 rounded-t">
                    <div>
                        <h3 class="text-xl font-semibold text-gray-300">
                           {{ trans('lang.labels.assign_module') }}
                        </h3>
                    </div>
                    <button type="button" @click="isCloseAssign()" class="">
                        <XCircleIcon class="h-9 w-9 text-white" aria-hidden="true" />
                    </button>
                </div>
                    <!-- Form start here -->
                    <div class="p-6 space-y-6 h-96 sm:h-96 lg:h-96 overflow-y-auto theme-modal-body">
                        <div class="grid md:grid-cols-1 sm:grid-cols-1 gap-3 mb-2">
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <svg aria-hidden="true" class="w-5 h-5 text-gray-500 dark:text-gray-400"
                                        fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd"
                                            d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <input type="text" id="simple-search" v-model="search"
                                    class="bg-sidebar border border-gray-700 text-sm text-neutral-400 rounded-lg focus:ring-0 
                                                                          focus:border-amber-500 block w-full pl-10 p-3" placeholder="Search">
                            </div>
                        </div>
                        <div class="grid md:grid-cols-2 sm:grid-cols-1 gap-3">
                            <div class="relative">
                                <button id="business_name" type="button" @click="library()" class="w-full"
                                    :class="librayClass">
                                    {{ trans('lang.labels.library') }}
                                </button>
                            </div>
                            <div class="relative">
                                <button id="business_name" type="button" @click="xmeArea()" class="w-full"
                                    :class="xmeClass">{{ trans('lang.labels.xme_area') }}
                                </button>
                            </div>
                        </div>
                        <div class="overflow-x-auto mt-4">
                            <table class="theme-table w-full text-sm text-left text-gray-500 dark:text-gray-400">
                                <thead class="text-xs text-gray-700 uppercase bg-sidebar dark:text-gray-400">
                                    <tr>
                                        <th scope="col" class="p-4">
                                            <div class="flex items-center">
                                                <input id="checkbox-all-search" type="checkbox"
                                                    class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600" />
                                                <label for="checkbox-all-search" class="sr-only">checkbox</label>
                                            </div>
                                        </th>
                                        <th scope="col" class="text-neutral-300 font-semibold text-base p-4">{{ trans('lang.labels.module_name') }}
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-if="forms.length == 0" class="themeNoFound">
                                        <td colspan="5">
                                            <div class="px-4 py-8 text-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="m-auto mb-3 h-8 w-8"
                                                    viewBox="0 0 576 512">
                                                    <path
                                                        d="M0 64C0 28.7 28.7 0 64 0H224V128c0 17.7 14.3 32 32 32H384v38.6C310.1 219.5 256 287.4 256 368c0 59.1 29.1 111.3 73.7 143.3c-3.2 .5-6.4 .7-9.7 .7H64c-35.3 0-64-28.7-64-64V64zm384 64H256V0L384 128zm48 96a144 144 0 1 1 0 288 144 144 0 1 1 0-288zm59.3 107.3c6.2-6.2 6.2-16.4 0-22.6s-16.4-6.2-22.6 0L432 345.4l-36.7-36.7c-6.2-6.2-16.4-6.2-22.6 0s-6.2 16.4 0 22.6L409.4 368l-36.7 36.7c-6.2 6.2-6.2 16.4 0 22.6s16.4 6.2 22.6 0L432 390.6l36.7 36.7c6.2 6.2 16.4 6.2 22.6 0s6.2-16.4 0-22.6L454.6 368l36.7-36.7z" />
                                                </svg>
                                                <p class="text-gray-400 text-base noFound">{{ trans('lang.modal.no_result_found') }}</p>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr v-for="item in forms" :key="item.id"
                                        class="border-b dark:border-gray-700 text-base text-neutral-300 hover:text-white">
                                        <td class="w-4 p-4">
                                            <div class="flex items-center">
                                                <input id="checkbox-table-search-1" :value="item.form_id" type="checkbox"
                                                    v-model="selectedForms"
                                                    class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600" />
                                                <label for="checkbox-table-search-1" class="sr-only">checkbox</label>
                                            </div>
                                        </td>
                                        <td class="p-4">
                                            <span>{{ item.display_title }}</span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- Modal footer -->
                    <div class="flex items-center justify-end p-6 space-x-2 rounded-b dark:border-gray-600">
                        <button :disabled="formProcess" @click="assignModuleTouser()"
                            class="text-btnSubmitText bg-btnSubmitBg rounded-lg text-sm font-medium px-5 py-2.5">
                            {{ trans('lang.modal.save') }}
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
import {
    XCircleIcon
} from '@heroicons/vue/24/solid'
export default {
    name: 'AssignMember',
    components: {
        XCircleIcon
    },
    props: ['data'],
    data() {
        return {
            searchTimeout: null,
            search: '',
            forms: [],
            selectedForms: [],
            queryData: {
                is_library: 1,
                search: '',
            },
            xmeClass: 'bg-btnSubmitBg px-4 py-3  text-btnSubmitText rounded-lg',
            librayClass: 'bg-amber-500 px-4 py-3  rounded-lg text-white',
            formProcess: false
        }
    },
    watch: {
        search: function (value) {
            let self = this;
            clearTimeout(self.searchTimeout);
            self.searchTimeout = setTimeout(function () {
                self.queryData.search = self.search;
                self.assignModule();
            }, 700);
        },
    },
    methods: {
        assignModule() {
            let that = this;
            axios.get('/api/assign-form/' + that.data.memberId, { params: this.queryData })
                .then((res) => {
                    if (res.status == 200) {
                        that.forms = res.data.data.form;
                        that.selectedForms = res.data.data.selectedForm;
                    }
                })
                .catch((error) => {
                    // error.response.status Check status code
                }).finally(() => {
                    //Perform action in always
                });
        },
        assignModuleTouser() {
            let that = this;
            that.formProcess = true;
            let formData = new FormData();
            formData.append('selectedForms', that.selectedForms);
            axios.post('/api/assign-user-forms/' + that.data.memberId, formData)
                .then((res) => {
                    if (res.status == 200 && res.data.data != false) {
                        let notification = {
                            heading: 'success',
                            subHeading: res.data.message,
                            type: "success",
                        };
                        that.appStore.setNotification(notification);
                    }
                    that.isCloseAssign();

                })
                .catch((error) => {
                    that.errors = error.response.data.errors;
                    // error.response.status Check status code

                    //if any error happened remove modal and overflow itself
                    that.isCloseAssign();
                })
                .finally(() => {
                     //submit button enabled after form submitted
                     that.formProcess = false;
                });

        },
        isCloseAssign() {
            this.$emit("closeAssign");
        },
        library() {
            this.queryData.is_library = 1;
            this.assignModule();
            this.librayClass = 'bg-amber-500 px-4 py-3  rounded-lg text-white'
            this.xmeClass = 'bg-btnSubmitBg px-4 py-3  text-btnSubmitText rounded-lg'
        },
        xmeArea() {
            this.queryData.is_library = 0;
            this.assignModule();
            this.xmeClass = 'bg-amber-500 px-4 py-3  rounded-lg text-white'
            this.librayClass = 'bg-btnSubmitBg px-4 py-3  text-btnSubmitText rounded-lg'
        }
    },
    created() {
        this.assignModule();
    },
    unmounted() {
        clearTimeout(this.searchTimeout);
    },
    computed: {
        ...mapStores(useAppStore),
    },
}
</script>
