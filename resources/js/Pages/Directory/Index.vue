<template>
    <div>
        <InertiaHead title="Folders" />
        <AuthenticatedLayout>
            <div class="mt-2">
                <inertia-link :href="route('app.directory')"
                    class="text-neutral-300 font-bold text-xl sm:text-2xl md:text-4xl">{{
                        trans('lang.site.folders') }}
                </inertia-link>
            </div>
            <div class="h-full mt-5">
                <div class="relative sm:rounded-lg mt-5">
                    <div
                        class="flex justify-end items-center flex-wrap p-4 bg-sidebar rounded-t-3xl border-b border-zinc-200 gap-3">
                        <div class="flex items-center">
                            <button @click="openAdd()" class="h-10 text-sm w-32 text-neutral-400 px-2 rounded-lg border-solid border
                                 border-zinc-300 hover:bg-amber-500 hover:text-white">
                                {{ trans('lang.site.add_new') }}
                            </button>
                            <button @click="isModule = true" v-if="queryData.parent_id != ''" class="h-10 text-sm w-32 text-neutral-400 px-2 rounded-lg border-solid border
                                 border-zinc-300 hover:bg-amber-500 hover:text-white ml-2">
                                {{ trans('lang.site.add_module') }}
                            </button>
                        </div>
                        <div class="relative">
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
                                                                            focus:border-amber-500 block w-full pl-10 p-2.5"
                                :placeholder="trans('lang.site.search_folder')">
                        </div>
                    </div>
                    <!-- <div  class="text-white"  v-if="currentDirectory">
                        <button type="button" @click="back()">Back</button>
                    
                    </div> -->
                    <div class="bg-directoryBg rounded-b-3xl">
                        <div class="themeDirectory px-3 py-4">
                            <div class="theme-dir p-3 bg-sidebar rounded-md" v-for="directory in directories"
                                :key="directory">
                                <div class="text-sm text-btnSubmitText hover:text-amber-500"
                                    @click="changeDirector(directory)">{{ directory.name }}
                                </div>
                                <div class="grid grid-cols-2 items-center mt-4">
                                    <div @click="changeDirector(directory)">
                                        <div
                                            class="bg-btnSubmitBg theme-folder p-3 text-center rounded-md flex items-center py-5 px-3">
                                            <FolderIcon class="h-10 w-10 text-white mr-3" aria-hidden="true" />
                                            <span class="theme-dir-name ml-4">{{ directory.name }}</span>
                                        </div>
                                    </div>
                                    <div class="theme-dir-btns bg-sidebar rounded-md p-3">
                                        <button @click="isEditDirectory(directory.id)"
                                            class="theme-rename-btn flex items-center justify-center rounded-md px-4 py-3 
                                                                                                                                    bg-btnSubmitBg text-btnSubmitText hover:bg-amber-500 hover:text-white text-sm w-full">
                                            <PencilIcon class="h-4 w-4 text-white mr-3" aria-hidden="true" />
                                            {{ trans('lang.site.rename_folder') }}
                                        </button>
                                        <button @click="openDelete(directory.id)"
                                            class="theme-delete-btn flex items-center justify-center rounded-md mt-3 px-4 py-3
                                                                                                                                     bg-btnCancelBg text-btnCancelText hover:bg-amber-500 hover:text-white text-sm w-full">
                                            <TrashIcon class="h-4 w-4 text-white mr-3" aria-hidden="true" />
                                            {{ trans('lang.site.delete_folder') }}
                                        </button>
                                    </div>
                                </div>
                                <div class="theme-dir-info rounded-md bg-bgAmberTag px-2 py-1">
                                    <h5 class="theme-dir-name text-amber-500">{{ directory.name }}</h5>
                                    <h5 class="theme-dir-name text-amber-500">{{ directory.createdAt }}</h5>
                                    <h5 class="theme-dir-name text-amber-500">{{ trans('lang.labels.folder') }} {{
                                        directory.folderCount }} {{ trans('lang.labels.documents') }}
                                        :
                                        {{ directory.moduleCount }} </h5>
                                </div>
                            </div>
                        </div>
                        <div v-if="directories.length == 0" class="themeNoFound">
                            <div class="px-4 py-8 text-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="m-auto mb-3 h-8 w-8" viewBox="0 0 576 512">
                                    <path
                                        d="M0 64C0 28.7 28.7 0 64 0H224V128c0 17.7 14.3 32 32 32H384v38.6C310.1 219.5 256 287.4 256 368c0 59.1 29.1 111.3 73.7 143.3c-3.2 .5-6.4 .7-9.7 .7H64c-35.3 0-64-28.7-64-64V64zm384 64H256V0L384 128zm48 96a144 144 0 1 1 0 288 144 144 0 1 1 0-288zm59.3 107.3c6.2-6.2 6.2-16.4 0-22.6s-16.4-6.2-22.6 0L432 345.4l-36.7-36.7c-6.2-6.2-16.4-6.2-22.6 0s-6.2 16.4 0 22.6L409.4 368l-36.7 36.7c-6.2 6.2-6.2 16.4 0 22.6s16.4 6.2 22.6 0L432 390.6l36.7 36.7c6.2 6.2 16.4 6.2 22.6 0s6.2-16.4 0-22.6L454.6 368l36.7-36.7z" />
                                </svg>
                                <p class="text-gray-400 text-sm noFound">{{ trans('lang.modal.no_result_found') }}</p>
                            </div>
                        </div>
                        <div class="bg-sidebar px-4 py-5 rounded-b-3xl">
                            <Pagination v-if="pagination.lastPage != 1" @refreshTable="directory"
                                :currentPage="pagination.currentPage" :lastPage="pagination.lastPage"
                                :total="pagination.total" />
                        </div>
                    </div>
                    <div class="bg-directoryBg rounded-t-3xl mt-3"
                        v-if="queryData.parent_id != '' && directoryModule.length > 0">
                        <div class="themeLibraryCards mt-5 p-3">
                            <div class="bg-cardtop rounded-lg relative" v-for="form in directoryModule" :key="form">
                                <div class="text-end absolute right-0">
                                    <button @click="deleteModule(form.id)">
                                        <XCircleIcon class="h-8 w-8 text-white" aria-hidden="true" />
                                    </button>
                                </div>
                                <div class="px-3 py-5 pr-7 h-28">
                                    <span class="text-neutral-400 breakWord libraryCardTitleOverflow">{{ form.display_title
                                    }}</span>
                                </div>
                                <div class="flex items-center justify-between bg-sidebar py-3 px-3">
                                    <span class="text-sm text-gray-400">{{ form.formattedCreated_at }}</span>
                                    <span class="text-sm text-gray-400">{{ form.companyFormReviewDate }}</span>
                                </div>
                                <div>
                                    <img :src="mediaPath + form.image" alt="" class="h-60 w-full object-cover">
                                </div>
                                <div>
                                    <hr class="h-px bg-neutral-500">
                                    <div>
                                        <div class="flex items-center justify-between py-4 px-3">
                                            <span class="theme-eye-svg text-neutral-400 flex items-center">
                                                <EyeIcon class="h-5 w-5 text-white" aria-hidden="true" />
                                                <span class="text-gray-400 text-sm ml-2">{{ form.responsesCount }}</span>
                                            </span>
                                            <div class="flex items-center">
                                                <inertia-link :href="route('app.learning-path')"
                                                    class="relative bg-sidebar p-2 rounded-lg hover:bg-amber-500 themeBtn">
                                                    <AcademicCapIcon class="h-5 w-5 text-white" aria-hidden="true" />
                                                    <span class="text-gray-300 text-xs tooltip">{{
                                                        trans('lang.labels.learning_path') }}</span>
                                                </inertia-link>
                                                <button @click="libraryArchived(form.id)"
                                                    class="relative bg-sidebar p-2 rounded-lg hover:bg-amber-500 ml-2 themeBtn">
                                                    <ArchiveBoxIcon class="h-5 w-5 text-white" aria-hidden="true" />
                                                    <span class="text-gray-300 text-xs tooltip">{{
                                                        trans('lang.modal.archived') }}</span>
                                                </button>
                                                <button
                                                    class="relative bg-sidebar p-2 rounded-lg hover:bg-amber-500 ml-2 themeBtn">
                                                    <IdentificationIcon class="h-5 w-5 text-white" aria-hidden="true" />
                                                    <span class="text-gray-300 text-xs tooltip">{{
                                                        trans('lang.labels.details') }}</span>
                                                </button>
                                            </div>
                                        </div>
                                        <hr class="h-px bg-neutral-500">
                                        <div class="mt-4 mb-3 flex items-center px-3">
                                            <a :href="route('library-view', form.typeform_id)"
                                                class="bg-amber-500 px-4 py-2 text-white rounded-lg w-full text-center">{{
                                                    trans('lang.labels.responses') }}</a>
                                        </div>
                                        <div class="flex items-center justify-between pt-4 pb-1 px-3 mb-3">
                                            <inertia-link
                                                :href="route('digital-asset', { 'id': form.typeform_id, 'form_id': form.encryptId, 'redirect_url': route('app.library') })"
                                                class="cursor-pointer flex items-center justify-center text-white hover:text-amber-500 theme-link text-sm">
                                                <EyeIcon class="h-3.5 w-3.5 text-neutral-400" aria-hidden="true" />
                                                <span class="ml-1">{{ trans('lang.labels.open_module') }}</span>
                                            </inertia-link>
                                            <inertia-link :href="route('admin.manage-groups')"
                                                class="cursor-pointer flex items-center justify-center text-white hover:text-amber-500 theme-link text-sm">
                                                <UserGroupIcon class="h-3.5 w-3.5 text-neutral-400" aria-hidden="true" />
                                                <span class="ml-1">{{ trans('lang.labels.manage_groups') }}</span>
                                            </inertia-link>
                                        </div>
                                        <div class="flex items-center justify-between px-3 pb-4">
                                            <button type="text" @click="copyLinkPrivate(form)"
                                                class="bg-sidebar text-sm border border-neutral-400 rounded-lg px-2 py-1 hover:bg-amber-500 flex items-center">
                                                <template v-if="!form.copiedPrivate">
                                                    <ClipboardDocumentIcon class="h-3.5 w-3.5 text-white"
                                                        aria-hidden="true" /><span class="ml-1 text-white">{{
                                                            trans('lang.labels.private_link') }}</span>
                                                </template>
                                                <template v-else>
                                                    <span class="flex text-white items-center">{{
                                                        trans('lang.labels.copied') }}
                                                        <CheckIcon class="h-3.5 w-3.5 text-white ml-2" aria-hidden="true" />
                                                    </span>
                                                </template>
                                            </button>
                                            <button type="text" @click="copyLink(form)"
                                                class="bg-sidebar text-sm border border-neutral-400 rounded-lg px-2 py-1  hover:bg-amber-500 flex items-center">
                                                <template v-if="!form.copied">
                                                    <ClipboardDocumentIcon class="h-3.5 w-3.5 text-white"
                                                        aria-hidden="true" /><span class="ml-1 text-white">{{
                                                            trans('lang.labels.public_link') }}</span>
                                                </template>
                                                <template v-else>
                                                    <span class="flex text-white items-center">{{
                                                        trans('lang.labels.copied') }}
                                                        <CheckIcon class="h-3.5 w-3.5 text-white ml-2" aria-hidden="true" />
                                                    </span>
                                                </template>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-sidebar px-4 py-5 mt-3 rounded-b-3xl">
                            <Pagination v-if="dirModulePagination.lastPage != 1" @refreshTable="directoryModules"
                                :currentPage="dirModulePagination.currentPage" :lastPage="dirModulePagination.lastPage"
                                :total="dirModulePagination.total" />
                        </div>
                    </div>
                </div>
            </div>
            <add @resfreshTable="directory()" @closeAdd="isCloseAdd()" v-if="isAdd"
                :directoryId="(this.currentDirectory != null) ? this.currentDirectory.id : ''"></add>
            <edit @resfreshTable="directory()" @closeEdit="isCloseEdit()" v-if="isEdit" :directoryData="directoryData">
            </edit>
            <add-module @closeModule="isCloseModule()" v-if="isModule" :id="currentDirectory.id"></add-module>
            <delete-module v-if="isDeleteModule" :id="deleteModuleId" :directory_id="currentDirectory.id"
                @closeDelete="closeDeleteModule()"></delete-module>
            <delete @closeDelete="closeDelete()" :id="directoryData.directoryId" v-if="isDelete"></delete>
        </AuthenticatedLayout>
    </div>
</template>
<script>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Add from '@/Components/Directory/Add.vue'
import Edit from '@/Components/Directory/Edit.vue'
import AddModule from '@/Components/Directory/AddModule.vue'
import DeleteModule from '@/Components/Directory/DeleteModule.vue';
import Delete from '@/Components/Directory/Delete.vue'

import Pagination from "@/Components/Pagination.vue";
import {
    FolderIcon, PencilIcon, TrashIcon, ArchiveBoxIcon, EyeIcon, AcademicCapIcon, ClipboardDocumentIcon, IdentificationIcon,
    UserGroupIcon, XCircleIcon, CheckIcon
} from '@heroicons/vue/24/solid'
export default {
    name: "Directory",
    components: {
        Add,
        Edit,
        AuthenticatedLayout,
        Pagination,
        AddModule, Delete,
        DeleteModule, XCircleIcon,
        FolderIcon, PencilIcon, TrashIcon, AcademicCapIcon,
        EyeIcon, UserGroupIcon,
        ArchiveBoxIcon,
        ClipboardDocumentIcon,
        IdentificationIcon, CheckIcon
    },
    data() {
        return {
            currentDirectory: null,
            directories: [],
            directoryModule: [],
            search: "",
            isAdd: false,
            isEdit: false,
            isModule: false,
            isDeleteModule: false,
            queryData: {
                page: 1,
                sortField: "id",
                orderDir: "ASC",
                search: '',
                parent_id: (this.currentDirectory != null) ? this.currentDirectory.id : ''
            },
            pagination: {
                currentPage: 1,
                lastPage: 1,
                total: 0,
            },
            dirModulePagination: {
                currentPage: 1,
                lastPage: 1,
                total: 0,
            },
            directoryData: {
                directoryId: "",
            },
            deleteModuleId: '',
            isDelete: false
        };
    },
    watch: {
        search: function (value) {
            let self = this;
            clearTimeout(self.searchTimeout);
            self.searchTimeout = setTimeout(function () {
                self.queryData.search = self.search;
                self.directory();
            }, 700);
        },
        /* modal overflow hidden */
        isDeleteModule: function () {
            document.body.style.overflow = this.isDeleteModule ? 'hidden' : ''
        },
        isAdd: function () {
            document.body.style.overflow = this.isAdd ? 'hidden' : ''
        },
        isEdit: function () {
            document.body.style.overflow = this.isEdit ? 'hidden' : ''
        },
        isModule: function () {
            document.body.style.overflow = this.isModule ? 'hidden' : ''
        },
        isDelete: function () {
            document.body.style.overflow = this.isDelete ? 'hidden' : ''
        },
    },
    methods: {
        changeDirector(directory) {
            this.currentDirectory = directory;
            this.directory();
            if (this.queryData.parent_id != '') {
                this.directoryModules();
            }
        },
        setPagination(response) {
            this.pagination.total = response.data.meta.total;
            this.pagination.lastPage = response.data.meta.last_page;
            this.pagination.currentPage = response.data.meta.current_page;
        },
        setDirModulePagination(response) {
            this.dirModulePagination.total = response.data.meta.total;
            this.dirModulePagination.lastPage = response.data.meta.last_page;
            this.dirModulePagination.currentPage = response.data.meta.current_page;
        },
        directory() {
            this.queryData.parent_id = (this.currentDirectory != null) ? this.currentDirectory.id : '';
            axios.get('/api/directories', { params: this.queryData })
                .then((response) => {
                    this.directories = response.data.data;
                    this.setPagination(response)
                })
                .catch((error) => {
                    // error.response.status Check status code
                }).finally(() => {
                    //Perform action in always
                });
        },
        directoryModules() {
            this.queryData.parent_id = (this.currentDirectory != null) ? this.currentDirectory.id : '';
            axios.get('/api/directory-modules/' + this.queryData.parent_id, { params: this.queryData })
                .then((response) => {
                    this.directoryModule = response.data.data;
                    this.setDirModulePagination(response)
                })
                .catch((error) => {
                    // error.response.status Check status code
                }).finally(() => {
                    //Perform action in always
                });
        },
        libraryArchived(id) {
            const headers = { "Content-Type": "application/json" };
            axios.put("/api/archived-module/" + id, headers)
                .then((response) => {
                    if (response.status == 200) {
                        this.directoryModules(1);
                        let notification = {
                            heading: 'success',
                            subHeading: response.data.message,
                            type: "success",
                        };
                        this.appStore.setNotification(notification);;
                        this.assignTable();


                    }
                })
        },
        copyLink(item) {
            const textToCopy = route('public.digital-asset', item.typeform_id);
            this.copyText(textToCopy);
            item.copied = true;
            setTimeout(() => {
                item.copied = false;
            }, 1000);
        },
        copyLinkPrivate(item) {
            const textToCopy = route('digital-asset', { 'id': item.typeform_id, 'redirect_url': route('app.library') });
            this.copyText(textToCopy);
            item.copiedPrivate = true;
            setTimeout(() => {
                item.copiedPrivate = false;
            }, 1000);
        },
        copyText(textToCopy) {
            if (!navigator.clipboard) {
                // Create a temporary textarea element
                const textarea = document.createElement('textarea');
                textarea.value = textToCopy;
                textarea.style.position = 'fixed';
                textarea.style.opacity = '0';

                // Add the textarea element to the page
                document.body.appendChild(textarea);

                // Select and copy the text inside the textarea
                textarea.focus();
                textarea.select();
                document.execCommand('copy');

                // Remove the textarea element from the page
                document.body.removeChild(textarea);
            } else {
                navigator.clipboard.writeText(textToCopy).then(() => {
                    console.log('Text copied to clipboard');
                }).catch((error) => {
                    console.error('Failed to copy text: ', error);
                });
            }

        },
        openAdd() {
            this.isAdd = true;
        },
        isEditDirectory(directoryId) {
            this.directoryData.directoryId = directoryId
            this.isEdit = true;
        },
        isCloseAdd() {
            this.isAdd = false;
        },
        isCloseEdit() {
            this.isEdit = false;
        },
        isCloseModule() {
            this.isModule = false;
            this.directoryModules();
        },
        deleteModule(id) {
            this.isDeleteModule = true;
            this.deleteModuleId = id;
        },
        closeDeleteModule() {
            this.isDeleteModule = false;
            this.directoryModules();
        },
        openDelete(id) {
            this.isDelete = true;
            this.directoryData.directoryId = id;
        },
        closeDelete() {
            this.isDelete = false;
            this.directory();
        }
    },
    created: function () {
        this.directory();
        if (this.queryData.parent_id != '') {
            this.directoryModules();
        }
    },
    unmounted() {
        clearTimeout(this.searchTimeout);
    },
};
</script>
