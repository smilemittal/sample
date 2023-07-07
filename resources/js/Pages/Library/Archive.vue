<template>
    <div>

        <InertiaHead title="Library" />
        <AuthenticatedLayout>
            <div class="">
                <div class="flex items-center justify-between flex-wrap">
                    <div class="page-header">
                        <h4 class="text-neutral-300 font-bold text-xl sm:text-2xl md:text-4xl">
                            <inertia-link :href="route('app.library')">{{ trans('lang.labels.library_archive_heading') }}</inertia-link>
                        </h4>
                        <h5 class="text-base text-neutral-400"> {{ trans('lang.labels.library_archived_sub_heading') }}
                            </h5>
                    </div>
                    <div class="flex items-center flex-wrap mt-3 sm:mt-3 md:mt-0 gap-3">
                        <div class="relative w-full sm:w-fit">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <svg aria-hidden="true" class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="currentColor"
                                    viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd"
                                        d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                        clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <input type="text" id="simple-search" v-model="search" class="bg-sidebar border-transparent text-sm text-neutral-400 rounded-lg focus:ring-0 
                                                                          focus:border-amber-500 block w-full pl-10 p-3"
                                placeholder="Search">
                        </div>
                        <inertia-link
                            class="px-3 py-3 bg-sidebar text-neutral-100 hover:bg-amber-500 hover:text-white rounded-lg border-1 border-neutral-100"
                            :href="route('app.library')">{{ trans('lang.labels.library') }}</inertia-link>
                    </div>
                </div>
            </div>
            <div class="p-0">
                <div class="themeNoFound" v-if="libraries.length == 0">
                    <div class="px-4 py-8 text-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="m-auto mb-3 h-10 w-10" viewBox="0 0 576 512">
                            <path
                                d="M0 64C0 28.7 28.7 0 64 0H224V128c0 17.7 14.3 32 32 32H384v38.6C310.1 219.5 256 287.4 256 368c0 59.1 29.1 111.3 73.7 143.3c-3.2 .5-6.4 .7-9.7 .7H64c-35.3 0-64-28.7-64-64V64zm384 64H256V0L384 128zm48 96a144 144 0 1 1 0 288 144 144 0 1 1 0-288zm59.3 107.3c6.2-6.2 6.2-16.4 0-22.6s-16.4-6.2-22.6 0L432 345.4l-36.7-36.7c-6.2-6.2-16.4-6.2-22.6 0s-6.2 16.4 0 22.6L409.4 368l-36.7 36.7c-6.2 6.2-6.2 16.4 0 22.6s16.4 6.2 22.6 0L432 390.6l36.7 36.7c6.2 6.2 16.4 6.2 22.6 0s6.2-16.4 0-22.6L454.6 368l36.7-36.7z" />
                        </svg>
                        <p class="text-gray-400 text-base noFound">No Result Found</p>
                    </div>
                </div>
                <div class="themeLibraryCards mt-5">
                    <div class="bg-cardtop rounded-lg" v-for="library in libraries" :key="library.id">
                        <div class="px-3 py-5 h-28">
                            <span class="text-neutral-400 breakWord">
                                {{ library.display_title }}
                            </span>
                        </div>
                        <div class="flex items-center justify-between bg-sidebar py-3 px-3">
                            <span class="text-sm text-gray-400">12/03/2023</span>
                            <button
                                class="text-sm text-gray-400 hover:bg-amber-500 hover:text-white px-3 py-1">12/03/2023</button>
                        </div>
                        <div>
                            <img :src="mediaPath + library.image" class="h-60 w-full object-cover">
                        </div>
                        <div>
                            <div class="flex items-center justify-between py-4 px-3">
                                <span class="theme-eye-svg text-neutral-400 flex items-center">
                                    <EyeIcon class="h-5 w-5 text-white" aria-hidden="true" />
                                    <span class="text-gray-400 text-sm ml-2">{{ library.responsesCount }}</span>
                                </span>
                                <div class="flex items-center">
                                    <inertia-link :href="route('app.library')"
                                        class="relative bg-sidebar p-2 rounded-lg hover:bg-amber-500 themeBtn">
                                    <span class="text-gray-300 text-xs tooltip">Learning Path</span>
                                    <AcademicCapIcon class="h-5 w-5 text-white" aria-hidden="true" />
                                    </inertia-link>
                                    <button @click="libraryArchived(library.id)"
                                        class="relative bg-sidebar p-2 rounded-lg hover:bg-amber-500 ml-2 themeBtn">
                                        <span class="text-gray-300 text-xs tooltip">Unarchive</span>
                                        <ArchiveBoxIcon class="h-5 w-5 text-white" aria-hidden="true" />
                                    </button>
                                    <button class="relative bg-sidebar p-2 rounded-lg hover:bg-amber-500 ml-2 themeBtn">
                                        <span class="text-gray-300 text-xs tooltip">Details</span>
                                        <IdentificationIcon class="h-5 w-5 text-white" aria-hidden="true" />
                                    </button>
                                </div>
                            </div>
                            <hr class="h-px bg-neutral-500">
                            <div class="mt-4 mb-3 flex items-center px-3">
                                <a class="bg-amber-500 px-4 py-2 text-white rounded-lg w-full text-center"
                                    href="#/">{{ trans('lang.labels.responses') }}</a>
                            </div>
                            <div class="flex items-center justify-between pt-4 pb-1 px-3 mb-3">
                                <a href="#/"
                                    class="flex items-center text-neutral-400 hover:text-amber-500 theme-link text-sm">
                                    <EyeIcon class="h-3.5 w-3.5 text-neutral-400" aria-hidden="true" />
                                    <span class="ml-1">
                                        {{ trans('lang.labels.open_module') }}
                                    </span>
                                </a>
                                <a href="#/"
                                    class="flex items-center text-neutral-400 hover:text-amber-500 theme-link text-sm">
                                    <UserGroupIcon class="h-3.5 w-3.5 text-neutral-400" aria-hidden="true" />
                                    <span class="ml-1">{{ trans('lang.labels.manage_groups') }}</span>
                                </a>
                            </div>
                            <div class="flex items-center justify-between px-3 pb-4">
                                <a href="#/" class="text-sm border border-neutral-400 rounded-lg px-2 py-1 
                                                            hover:bg-amber-500 flex items-center">
                                    <ClipboardDocumentIcon class="h-3.5 w-3.5 text-white" aria-hidden="true" />
                                    <span class="ml-1 text-white">
                                        {{ trans('lang.labels.private_link') }}
                                    </span>
                                </a>
                                <a href="#/" class="text-sm border border-neutral-400 rounded-lg px-2 py-1 
                                                            hover:bg-amber-500 flex items-center">
                                    <ClipboardDocumentIcon class="h-3.5 w-3.5 text-white" aria-hidden="true" />
                                    <span class="ml-1 text-white">{{ trans('lang.labels.public_link') }}</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="px-4 py-5 rounded-b-3xl">
                <Pagination v-if="pagination.lastPage != 1" @refreshTable="createLibrary"
                    :currentPage="pagination.currentPage" :lastPage="pagination.lastPage" :total="pagination.total" />
            </div>
        </AuthenticatedLayout>
    </div>
</template>
<script>

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { ArchiveBoxIcon, EyeIcon, AcademicCapIcon, ClipboardDocumentIcon, IdentificationIcon, UserGroupIcon } from "@heroicons/vue/24/solid";
import Pagination from "@/Components/Pagination.vue";
export default {
    components: {
        
        
        AuthenticatedLayout,
        AcademicCapIcon,
        EyeIcon,
        ArchiveBoxIcon, UserGroupIcon,
        ClipboardDocumentIcon,
        IdentificationIcon,
        Pagination
    },
    data() {
        return {
            search: "",
            libraries: [],
            loggedUserRole: "",
            pagination: {
                currentPage: 1,
                lastPage: 1,
                total: 0,
            },
            queryData: {
                search: '',
                page: 1,
                isArchived: 1
            },
        }
    },
    watch: {
        search: function (value) {
            let that = this;
            clearTimeout(that.searchTimeout);
            that.searchTimeout = setTimeout(function () {
                that.queryData.search = that.search;
                that.createLibrary();
            }, 700);
        }
    },
    methods: {
        setPagination(response) {
            this.pagination.total = response.data.meta.total;
            this.pagination.lastPage = response.data.meta.last_page;
            this.pagination.currentPage = response.data.meta.current_page;
        },
        createLibrary(page) {
            this.queryData.page = page;
            console.log(this.queryData);
            const headers = { "Content-Type": "application/json" };
            //for table data is loading after fetch ==>
            axios.get("/api/library", { params: this.queryData }, headers)
                .then((response) => {
                    this.libraries = response.data.data;
                    this.setPagination(response);
                })
        },
        libraryArchived(id) {
            const headers = { "Content-Type": "application/json" };
            axios.put("/api/unarchived-module/" + id, headers)
                .then((response) => {
                    this.createLibrary(1);
                })
        }
    },
    created: function () {
        this.createLibrary(1);
    },
    unmounted() {
        clearTimeout(this.searchTimeout);
    },
}
</script>
