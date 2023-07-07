<template>
    <!-- Main modal -->
    <div id="assignCompany" class="bg-bgModal fixed top-0 flex items-center justify-center left-0 right-0 z-50 w-full p-4 overflow-x-hidden 
        overflow-y-hidden md:inset-0 h-modal md:h-full">
        <div class="relative w-full h-auto max-w-3xl md:h-auto">
            <!-- Modal content -->
            <div class="relative rounded-lg shadow bg-body">
                <!-- Modal header -->
                <div class="flex items-start justify-between p-6 rounded-t">
                    <div>
                        <h3 class="text-xl font-semibold text-gray-300">
                            {{ trans('lang.labels.assign') }} {{ item.display_title }} {{ trans('lang.labels.to_company') }}
                        </h3>
                    </div>
                    <button type="button" class="text-gray-400 bg-transparent hover:text-gray-900 rounded-lg 
                            text-sm inline-flex items-center dark:hover:text-white" @click="isCloseAssign()">
                        <XCircleIcon class="h-8 w-8 text-white" aria-hidden="true" />
                    </button>
                </div>
                <!-- Form start here -->
                <div class="px-6">
                    <div class="relative searchTable">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <svg aria-hidden="true" class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="currentColor"
                                viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                    d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                    clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <input type="text" id="simple-search" v-model="search"
                            class="h-10 bg-sidebar border border-gray-300 text-sm text-neutral-400 rounded-lg focus:ring-0 focus:border-amber-500 block w-full pl-10 p-2.5"
                            placeholder="Search by company" />
                    </div>
                </div>
                <div class="p-6 space-y-6 h-96 sm:h-96 lg:h-96 overflow-y-auto theme-modal-body">
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
                                    <th scope="col" class="text-neutral-300 font-semibold text-base p-4">{{
                                        trans('lang.labels.company') }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="company in companies" :key="company.id"
                                    class="border-b dark:border-gray-700 text-base text-neutral-300 hover:text-white">
                                    <td class="w-4 p-4">
                                        <div class="flex items-center">
                                            <input id="checkbox-table-search-1" :value="company.id" type="checkbox"
                                                v-model="selectedCompanies"
                                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600" />
                                            <label for="checkbox-table-search-1" class="sr-only">checkbox</label>
                                        </div>
                                    </td>
                                    <td class="p-4">
                                        <span>{{ company.name }}</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- Modal footer -->
                <div class="flex items-center justify-end p-6 space-x-2 rounded-b dark:border-gray-600">
                    <button :disabled="formProcess" @click="assignFormToCompanies()"
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
    name: 'AssignCompany',
    components: {
        XCircleIcon
    },
    props: ['item'],
    data() {
        return {
            assignForm: {
                search: '',
            },
            companies: [
            ],
            selectedCompanies: [],
            formProcess: false,
            oldAssignedCompanies: [],
            searchTimeout: null,
            queryData: {
                search: "",
            },
            search: ''
        }
    },
    watch: {
        search: function (value) {
            let self = this;
            clearTimeout(self.searchTimeout);
            self.searchTimeout = setTimeout(function () {
                self.queryData.search = value;
                self.getCompanies();
            }, 700);
        },
    },
    methods: {
        assignCompanies() {
            let that = this;
            axios.get('/api/assigned-companies/' + that.item.id)
                .then((res) => {
                    if (res.status == 200) {
                        that.selectedCompanies = res.data.data;
                        that.oldAssignedCompanies = res.data.data;
                    }
                })
                .catch((error) => {
                    // error.response.status Check status code
                }).finally(() => {
                    //Perform action in always
                });
        },
        getCompanies() {
            axios.get('/api/companies?is_deleted=0&isArchived=0', { params: this.queryData })
                .then((res) => {
                    if (res.status == 200) {
                        this.companies = res.data.data;
                    }
                })
                .catch((error) => {
                    // error.response.status Check status code
                }).finally(() => {
                    //Perform action in always
                });
        },
        assignFormToCompanies() {
            let that = this;
            that.formProcess = true;
            axios.post('/api/assigned-company-form', {
                'companies': that.selectedCompanies,
                'form_id': that.item.id,
                'old_companies': that.oldAssignedCompanies
            })
                .then((res) => {
                    if (res.status == 200) {
                        that.isCloseAssign();
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

                    that.isCloseAssign();
                })
                .finally(() => {
                    that.formProcess = false;
                    //Perform action in always
                });

        },
        isCloseAssign() {
            this.$emit("closeAssign");
            this.selectedCompanies = [];
            this.companies = [];
        },
    },
    created() {
        this.getCompanies();
        this.assignCompanies();
    },
    unmounted() {
        clearTimeout(this.searchTimeout);
    },
    computed: {
        ...mapStores(useAppStore),
    },
}
</script>
