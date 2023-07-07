<template>
    <div class="relative shadow-md sm:rounded-lg mt-5">
        <div class="flex justify-between items-center flex-wrap p-4 bg-sidebar rounded-t-3xl border-b border-zinc-200">
            <h4 class="text-xl text-neutral-300 font-semibold">{{ trans("lang.labels.learning_path") }}</h4>
            <div class="filterBtn">
                <button type="button" @click="openFilter()"
                    class="btn h-8 w-8 theme-dropdown-btn bg-amber-500 rounded-lg flex justify-center items-center">
                    <FunnelIcon class="h-5 w-5 text-white" aria-hidden="true" />
                </button>
            </div>
            <div class="desktopFilters relative mobileInputSpace">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <svg aria-hidden="true" class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="currentColor"
                        viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd"
                            d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                            clip-rule="evenodd"></path>
                    </svg>
                </div>
                <input type="text" id="simple-search" v-model="search"
                    class="h-10 bg-sidebar border border-gray-300 text-sm text-neutral-400 rounded-lg focus:ring-0 
                                                                              focus:border-amber-500 block w-full pl-10 p-2.5" placeholder="Search">
            </div>
            <div v-if="mobileFilters" class="mobileFilters flex flex-wrap gap-1 mt-4">
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
            </div>
        </div>
        <div class="themeOverflowCustom themeOverflowTable">
            <div v-if="learningPath.length == 0" class="themeNoFound bg-body">
                <div>
                    <div class="px-4 py-8 text-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="m-auto mb-3 h-8 w-8" viewBox="0 0 576 512">
                            <path
                                d="M0 64C0 28.7 28.7 0 64 0H224V128c0 17.7 14.3 32 32 32H384v38.6C310.1 219.5 256 287.4 256 368c0 59.1 29.1 111.3 73.7 143.3c-3.2 .5-6.4 .7-9.7 .7H64c-35.3 0-64-28.7-64-64V64zm384 64H256V0L384 128zm48 96a144 144 0 1 1 0 288 144 144 0 1 1 0-288zm59.3 107.3c6.2-6.2 6.2-16.4 0-22.6s-16.4-6.2-22.6 0L432 345.4l-36.7-36.7c-6.2-6.2-16.4-6.2-22.6 0s-6.2 16.4 0 22.6L409.4 368l-36.7 36.7c-6.2 6.2-6.2 16.4 0 22.6s16.4 6.2 22.6 0L432 390.6l36.7 36.7c6.2 6.2 16.4 6.2 22.6 0s6.2-16.4 0-22.6L454.6 368l36.7-36.7z" />
                        </svg>
                        <p class="text-gray-400 text-base noFound">
                            {{ trans("lang.modal.no_result_found") }}
                        </p>
                    </div>
                </div>
            </div>
            <table v-else class="theme-table w-full text-sm text-left text-gray-500 dark:text-gray-400 themeTableLearning">
                <thead class="text-xs text-gray-700 uppercase bg-sidebar dark:text-gray-400">
                    <tr>
                        <th scope="col" class="text-neutral-300 font-semibold text-base p-4 tableMultiActions"></th>
                        <th scope="col" class="text-neutral-300 font-semibold text-base p-4 tableTrainingTitle">{{
                            trans("lang.labels.title") }}</th>
                        <th scope="col" class="text-neutral-300 font-semibold text-base p-4 tableCompanyName">{{
                            trans("lang.labels.assign_module") }}</th>
                        <th scope="col" class="text-neutral-300 font-semibold text-base p-4">{{
                            trans("lang.labels.laerning_path_created_at") }}</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="path in learningPath" :key="path.id"
                        class="border-b dark:border-gray-700 text-base text-neutral-300 hover:text-white">
                        <td class="p-4">
                            <!-- Default dropstart button -->
                            <div class="btn-group dropstart">
                                <button type="button" class="btn btn-secondary h-10 w-10 theme-dropdown-btn"
                                    data-bs-toggle="dropdown" aria-expanded="false" data-dropdown-trigger="hover">
                                    <EllipsisVerticalIcon class="h-8 w-8 text-white m-auto" aria-hidden="true" />
                                </button>
                                <div class="dropdown-menu theme-dropdown-menu bg-card rounded-2xl p-0.5">
                                    <ul class="py-0">
                                        <li class="px-4 py-3 bg-card text-white rounded-t-2xl">{{
                                            trans("lang.labels.options") }}</li>
                                        <li class="">
                                            <inertia-link :href="route('complete-learning', path.encryptId)"
                                                :class="(path.isLearningTraining == false && (path.archived_at != null)) ? 'cursor-disabled' : ''"
                                                class="cursor-pointer flex items-center bg-body w-full px-4 py-3 hover:bg-card text-white rounded-b-2xl">
                                                <ArrowPathIcon class="h-4 w-4 text-white mr-3" aria-hidden="true" />
                                                {{ trans("lang.labels.view_path") }}
                                            </inertia-link>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </td>
                        <td class="p-4 p-4 breakWord">
                            <span v-if="path.archived_at != null">{{ trans("lang.labels.archive") }}</span>
                            <span class="themeManageTitle">{{ path.title }}</span>
                        </td>
                        <td class="p-4">
                            <span class="rounded-full bg-btnCancelBg text-btnCancelText px-3 py-1 text-xs"
                                v-if="path.countModule == 0">{{ path.countModule }}</span>
                            <span class="rounded-full bg-bgAmberTag text-amber-500 px-3 py-1 text-xs" v-else>{{
                                path.countModule }}</span>
                        </td>
                        <td class="p-4">
                            <span>{{ path.created_at }}</span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="bg-sidebar px-4 py-5 rounded-b-3xl">
        <Pagination v-if="pagination.lastPage != 1" @refreshTable="createTable" :currentPage="pagination.currentPage"
            :lastPage="pagination.lastPage" :total="pagination.total" />
    </div>
</template>
<script>

import Pagination from "@/Components/Pagination.vue";
import {
    EllipsisVerticalIcon, PencilIcon, TrashIcon, ArrowPathIcon,
    FunnelIcon
} from '@heroicons/vue/24/solid'
export default {
    components: {
        Pagination,

        EllipsisVerticalIcon, PencilIcon, ArrowPathIcon, FunnelIcon
    },
    data() {
        return {
            mobileFilters: false,
            learningPath: [],
            search: '',
            searchTimeout: null,

            queryData: {
                search: '',
                page: 1,
                sortField: 'learning_paths.id',
                orderDir: 'DESC'
            },

            pagination: {
                currentPage: 1,
                lastPage: 1,
                total: 0,
            },
        }
    },
    watch: {
        search: function (value) {
            let self = this;
            clearTimeout(this.searchTimeout);
            this.searchTimeout = setTimeout(function () {
                self.queryData.search = value;
                self.createTable();
            }, 700);
        }
    },
    methods: {
        openFilter() {
            this.mobileFilters = !this.mobileFilters;
        },
        setPagination(response) {
            this.pagination.total = response.data.meta.total;
            this.pagination.lastPage = response.data.meta.last_page;
            this.pagination.currentPage = response.data.meta.current_page;
        },
        createTable(page) {
            this.queryData.page = page;
            //for table data is loading after fetch ==>
            axios.get('/api/learning-path-trainings', { params: this.queryData })
                .then((response) => {
                    this.learningPath = response.data.data;
                    this.setPagination(response)
                })
                .catch((error) => {

                })
                .finally(() => {
                });
        },
    },
    created: function () {
        this.createTable(1);
    },
}
</script>
