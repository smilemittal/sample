<template>
    <!-- Main modal -->
    <div id="duplicatePath"
        class="bg-bgModal fixed top-0 flex items-center justify-center left-0 right-0 z-50 w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-modal md:h-full">
        <div class="relative w-full h-auto max-w-2xl md:h-auto">
            <!-- Modal content -->
            <div class="relative rounded-lg shadow bg-body">
                <!-- Modal header -->
                <div class="flex items-start justify-between p-4 rounded-t">
                    <div>
                        <h3 class="text-xl font-semibold text-gray-300">
                            {{ trans('lang.site.module_add') }}
                        </h3>
                    </div>
                    <button type="button" @click="isClose()" class="">
                        <XCircleIcon class="h-9 w-9 text-white" aria-hidden="true" />
                    </button>
                </div>

                <div class="p-6 h-fit theme-modal-body">
                    <!-- Form start here -->
                    <div class="mb-3 bg-sidebar rounded-t-2xl p-4">
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <svg aria-hidden="true" class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="currentColor"
                                    viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd"
                                        d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                        clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <input type="text" id="simple-search" v-model="search" class="h-10 bg-sidebar border border-gray-300 text-sm text-neutral-400 rounded-lg focus:ring-0 
                                                          focus:border-amber-500 block w-full pl-10 p-2.5"
                                placeholder="Search">
                        </div>
                        <div class="flex flex-wrap mt-4">
                            <button type="button" @click="library()" :class="librayClass">Library</button>
                            <button type="button" @click="xmeArea()" class="ml-3" :class="xmeClass">Xme
                                Area</button>
                            <select id="form_type" @change="createTable()"
                                class="bg-sidebar border border-gray-600 text-neutral-400 focus:border-amber-500 focus:ring-0 shadow-0 text-sm rounded-lg p-2.5 ml-3"
                                v-model="queryData.type">
                                <option>{{ trans('lang.modal.select_folder') }}</option>
                                <template v-for="directory in directoriesData" :key="directory">
                                    <option :value="directory.id">{{ directory.name }}</option>
                                    <select-option :options="directory.children" :level="1"></select-option>
                                </template>
                            </select>
                        </div>
                    </div>
                    <div class="theme-tableOverflow">
                        <div class="min-h-fit max-h-96 overflow-y-auto">
                            <table class="theme-table w-full text-sm text-left text-gray-500 dark:text-gray-400">
                                <thead class="text-xs text-gray-700 uppercase bg-sidebar dark:text-gray-400">
                                    <tr>
                                        <th scope="col" class="p-4">
                                        </th>
                                        <th scope="col" class="text-neutral-300 font-semibold text-base p-4">
                                            {{ trans('lang.site.image') }}</th>
                                        <th scope="col" class="text-neutral-300 font-semibold text-base p-4">
                                            {{ trans('lang.site.title') }}
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-if="modules.length == 0" class="themeNoFound">
                                        <td colspan="7">
                                            <div class="px-4 py-8 text-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="m-auto mb-3 h-8 w-8"
                                                    viewBox="0 0 576 512">
                                                    <path
                                                        d="M0 64C0 28.7 28.7 0 64 0H224V128c0 17.7 14.3 32 32 32H384v38.6C310.1 219.5 256 287.4 256 368c0 59.1 29.1 111.3 73.7 143.3c-3.2 .5-6.4 .7-9.7 .7H64c-35.3 0-64-28.7-64-64V64zm384 64H256V0L384 128zm48 96a144 144 0 1 1 0 288 144 144 0 1 1 0-288zm59.3 107.3c6.2-6.2 6.2-16.4 0-22.6s-16.4-6.2-22.6 0L432 345.4l-36.7-36.7c-6.2-6.2-16.4-6.2-22.6 0s-6.2 16.4 0 22.6L409.4 368l-36.7 36.7c-6.2 6.2-6.2 16.4 0 22.6s16.4 6.2 22.6 0L432 390.6l36.7 36.7c6.2 6.2 16.4 6.2 22.6 0s6.2-16.4 0-22.6L454.6 368l36.7-36.7z" />
                                                </svg>
                                                <p class="text-gray-400 text-base noFound">No Result Found</p>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr v-for="modulesItem in modules" :key="modulesItem.id"
                                        class="border-b dark:border-gray-700 text-base text-neutral-300 hover:text-white">
                                        <td class="w-4 p-4">
                                            <button :disabled="processing" @click="addModule(modulesItem.id,modulesItem.company_form.id)"
                                                class="bg-btnSubmitBg text-btnSubmitText hover:text-white hover:bg-amber-500 rounded-lg px-4 py-2">
                                                {{ trans('lang.modal.add') }}
                                            </button>
                                        </td>
                                        <td class="p-4">
                                            <img :src=" mediaPath + modulesItem.image" alt=""
                                                class="w-54 h-20 rounded-lg object-cover">
                                        </td>
                                        <td class="p-4 w-96">
                                            <span>{{ modulesItem.display_title }}</span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- Modal footer -->
                    <div class="flex items-center justify-end p-6 space-x-2 rounded-b dark:border-gray-600">
                        <button @click="isClose()"
                            class="text-btnCancelText bg-btnCancelBg rounded-lg text-sm font-medium px-5 py-2.5">
                            {{ trans('lang.modal.cancel') }}
                        </button>
                        <button @click="isClose()"
                            class="text-btnSubmitText bg-btnSubmitBg rounded-lg text-sm font-medium px-5 py-2.5">
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
    TrashIcon, XCircleIcon
} from '@heroicons/vue/24/solid'
import axios from 'axios';
import GeneralMixin from "@/Mixins/GeneralMixin";
import SelectOption from "@/Common/SelectOption.vue";
export default {
    mixins: [GeneralMixin],
    name: 'AddModule',
    components: {
        SelectOption,
        TrashIcon, XCircleIcon
    },
    props: ['id'],
    data() {
        return {
            searchTimeout: null,
            modules: [],
            directoriesData: [],
            search: '',
            queryData: {
                search: '',
                is_library: '1',
                type: '',
            },
            processing: false,
            dirQueryData: {
                sortField: "id",
                orderDir: "ASC",
                per_page: 'all'
            },
            addmoduleQueryData:{
                form_id: '',
                company_form_id : ''

            },
            xmeClass: 'bg-btnSubmitBg px-4 py-3  text-btnSubmitText rounded-lg',
            librayClass: 'bg-amber-500 px-4 py-3  rounded-lg text-white'
        }
    },
    watch: {
        search: function (value) {
            let self = this;
            clearTimeout(self.searchTimeout);
            self.searchTimeout = setTimeout(function () {
                self.queryData.search = self.search;
                self.createTable();
            }, 700);
        }
    },
    methods: {
        createTable() {
            axios.get('/api/learning-path-pending-modules/' + this.id, { params: this.queryData })
                .then((res) => {
                    this.modules = res.data.data.data;
                })
                .catch((error) => {
                    // error.response.status Check status code
                }).finally(() => {
                    //Perform action in always
                });
        },
        directories() {
            axios.get('/api/directories', { params: this.dirQueryData })
                .then((res) => {
                    this.directoriesData = res.data.data;
                })
                .catch((error) => {
                    // error.response.status Check status code
                }).finally(() => {
                    //Perform action in always
                });

        },
        addModule(form_id, company_form_id) {
            this.addmoduleQueryData.form_id = form_id;
            this.addmoduleQueryData.company_form_id = company_form_id;
            this.processing = true;
            axios.post('/api/add-learningpath-module/' + this.id, this.addmoduleQueryData )
                .then((res) => {
                    if (res.data.status == true) {
                        this.createTable();
                        let notification = {
                            heading: 'success',
                            subHeading: res.data.message,
                            type: "success",
                        };
                        this.appStore.setNotification(notification);;
                    }
                })
                .catch((error) => {
                    // error.response.status Check status code

                }).finally(() => {
                    this.processing = false;
                    //Perform action in always
                });
        },
        library() {
            this.queryData.is_library = 1;
            this.createTable();
            this.librayClass = 'bg-amber-500 px-4 py-3  rounded-lg text-white'
            this.xmeClass = 'bg-btnSubmitBg px-4 py-3  text-btnSubmitText rounded-lg'


        },
        xmeArea() {
            this.queryData.is_library = 0;
            this.createTable();
            this.xmeClass = 'bg-amber-500 px-4 py-3  rounded-lg text-white'
            this.librayClass = 'bg-btnSubmitBg px-4 py-3  text-btnSubmitText rounded-lg'

        },
        isClose() {
            this.$emit("closeModule");
        },
    },
    created() {
        this.createTable();
        this.directories();
    },
    unmounted() {
        clearTimeout(this.searchTimeout);
    },
}
</script>
