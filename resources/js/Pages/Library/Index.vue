<template>
    <div>

        <InertiaHead title="Library" />
        <AuthenticatedLayout>
            <div class="">
                <div class="flex items-center justify-between flex-wrap">
                    <div class="page-header">
                        <inertia-link :href="route('app.library')"
                            class="text-neutral-300 font-bold text-xl sm:text-2xl md:text-4xl">Your Library</inertia-link>
                        <h5 class="text-base text-neutral-400" v-if="!checkMember">{{
                            trans('lang.labels.a_collection_of_all_video_modules_in_your_company') }}</h5>
                        <h5 class="text-base text-neutral-400" v-if="checkMember">{{
                            trans('lang.labels.modules_you_have_access_too') }}</h5>
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
                            <input type="text" id="simple-search" v-model="search"
                                class="bg-sidebar border-transparent text-sm text-neutral-400 rounded-lg focus:ring-0 
                                                                                                                                                                                                              focus:border-amber-500 block w-full pl-10 p-3"
                                placeholder="Search">
                        </div>
                        <inertia-link v-if="!checkMember"
                            class="px-3 py-3 bg-sidebar text-neutral-100 hover:bg-amber-500 hover:text-white rounded-lg border-1 border-neutral-100"
                            :href="route('library-archive')">{{ trans('lang.modal.archived') }}</inertia-link>
                    </div>
                </div>
            </div>
            <div class="p-0">
                <div class="mt-5">
                    <div class="themeNoFound" v-if="libraries.length == 0">
                        <div class="px-4 py-8 text-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="m-auto mb-3 h-10 w-10" viewBox="0 0 576 512">
                                <path
                                    d="M0 64C0 28.7 28.7 0 64 0H224V128c0 17.7 14.3 32 32 32H384v38.6C310.1 219.5 256 287.4 256 368c0 59.1 29.1 111.3 73.7 143.3c-3.2 .5-6.4 .7-9.7 .7H64c-35.3 0-64-28.7-64-64V64zm384 64H256V0L384 128zm48 96a144 144 0 1 1 0 288 144 144 0 1 1 0-288zm59.3 107.3c6.2-6.2 6.2-16.4 0-22.6s-16.4-6.2-22.6 0L432 345.4l-36.7-36.7c-6.2-6.2-16.4-6.2-22.6 0s-6.2 16.4 0 22.6L409.4 368l-36.7 36.7c-6.2 6.2-6.2 16.4 0 22.6s16.4 6.2 22.6 0L432 390.6l36.7 36.7c6.2 6.2 16.4 6.2 22.6 0s6.2-16.4 0-22.6L454.6 368l36.7-36.7z" />
                            </svg>
                            <p class="text-gray-400 text-base noFound">{{ trans('lang.modal.no_result_found') }}</p>
                        </div>
                    </div>
                    <draggable :list="libraries" tag="div" @end="updateLibraryOrder" item-key="display_title"
                        class="themeLibraryCards libraryDesktop" ghost-class="ghost" :move="checkMove"
                        @start="dragging = true">
                        <template #item="{ element: library }">
                            <div class="bg-cardtop rounded-lg">
                                <div class="px-3 py-5 h-28 text-center">
                                    <span class="text-neutral-400 breakWord libraryCardTitleOverflow">
                                        {{ library.display_title }}
                                    </span>
                                </div>
                                <div class="flex items-center justify-between bg-sidebar py-3 px-3">
                                    <span class="text-sm text-gray-400">{{ library.formattedCreated_at }}</span>
                                    <button v-if="!checkMember && !library.isFormUnderReview"
                                        @click="nextReviewDate(library.id, library.companyFormReviewDate)" type="button"
                                        class="text-sm text-gray-400 hover:bg-amber-500 
                                        hover:text-white px-3 py-1 border border-neutral-400 rounded-lg">{{
                                            library.companyFormReviewDate }}</button>
                                    <span v-if="!checkMember && library.isFormUnderReview" class="text-gray-400 text-sm font-bold">Form In-Process</span>
                                </div>
                                <div class="pb-5">
                                    <img :src="mediaPath + library.image" alt="" class="h-60 w-full object-cover">
                                </div>
                                <div>
                                    <div class="flex items-center justify-between py-4 px-3" v-if="checkCompanyAdmin">
                                        <span class="theme-eye-svg text-neutral-400 flex items-center">
                                            <EyeIcon class="h-5 w-5 text-white" aria-hidden="true" />
                                            <span class="text-gray-400 text-sm ml-2">{{ library.responsesCount }}</span>
                                        </span>
                                        <div class="flex items-center">
                                            <inertia-link :href="route('app.learning-path')"
                                                class="relative bg-sidebar p-2 rounded-lg hover:bg-amber-500 themeBtn">
                                                <span class="text-gray-300 text-xs tooltip">{{
                                                    trans('lang.labels.learning_path') }}</span>
                                                <AcademicCapIcon class="h-5 w-5 text-white" aria-hidden="true" />
                                            </inertia-link>
                                            <button @click="libraryArchived(library.id)"
                                                class="relative bg-sidebar p-2 rounded-lg hover:bg-amber-500 ml-2 themeBtn">
                                                <span class="text-gray-300 text-xs tooltip">{{ trans('lang.modal.archive')
                                                }}</span>
                                                <ArchiveBoxIcon class="h-5 w-5 text-white" aria-hidden="true" />
                                            </button>
                                            <button @click="openDetails(library.id)"
                                                class="relative bg-sidebar p-2 rounded-lg hover:bg-amber-500 ml-2 themeBtn">
                                                <span class="text-gray-300 text-xs tooltip">{{ trans('lang.labels.details')
                                                }}</span>
                                                <IdentificationIcon class="h-5 w-5 text-white" aria-hidden="true" />
                                            </button>
                                        </div>

                                    </div>
                                    <hr class="h-px bg-neutral-500" v-if="checkCompanyAdmin">
                                    <div class="mt-4 mb-3 flex items-center px-3" v-if="checkCompanyAdmin">
                                        <inertia-link :href="route('library-view', library.typeform_id)"
                                            class="bg-amber-500 px-4 py-2 text-white rounded-lg w-full text-center">
                                            {{ trans('lang.labels.responses') }}
                                        </inertia-link>
                                    </div>
                                    <div class="flex items-center justify-between pt-4 pb-1 px-3 mb-2"
                                        v-if="checkCompanyAdmin">

                                        <inertia-link
                                            :href="route('digital-asset', { 'id': library.typeform_id, 'form_id': library.encryptId, 'redirect_url': route('app.library') })"
                                            class="flex items-center justify-center text-neutral-400 hover:text-amber-500 theme-link text-sm">
                                            <EyeIcon class="h-3.5 w-3.5 text-neutral-400" aria-hidden="true" />
                                            <span class="ml-1">{{ trans('lang.labels.open_module') }}</span>
                                        </inertia-link>
                                        <inertia-link :href="route('admin.manage-groups')"
                                            class="flex items-center justify-center text-neutral-400 hover:text-amber-500 theme-link text-sm">
                                            <UserGroupIcon class="h-3.5 w-3.5 text-neutral-400" aria-hidden="true" />
                                            <span class="ml-1">{{ trans('lang.labels.manage_groups') }}</span>
                                        </inertia-link>
                                    </div>
                                    <input type="text" name="" :id="'copy_link_public_' + library.id"
                                        :value="route('public.digital-asset', library.typeform_id)"
                                        :data-typeform_id="library.typeform_id"
                                        style="position: absolute; visibility: hidden">
                                    <div class="pt-4 pb-1 px-3 mb-6" v-if="checkMember">
                                        <hr class="border-0 bg-gray-300 mb-4 mt-2 w-full h-px">
                                        <inertia-link
                                            :href="route('digital-asset', { 'id': library.typeform_id, 'form_id': library.encryptId, 'redirect_url': route('app.library') })"
                                            class="cursor-pointer block bg-amber-500 w-full px-4 rounded-lg py-3 hover:bg-card text-sm text-white text-center mt-6">
                                            {{ trans('lang.labels.go_to_training') }}
                                        </inertia-link>
                                    </div>
                                    <div class="flex items-center justify-between px-3 pb-4" v-if="checkCompanyAdmin">
                                        <button type="text" @click="copyLinkPrivate(library)"
                                            class="bg-sidebar text-sm border border-neutral-400 rounded-lg px-2 py-1 hover:bg-amber-500 flex items-center">
                                            <template v-if="!library.copiedPrivate">
                                                <ClipboardDocumentIcon class="h-3.5 w-3.5 text-white" aria-hidden="true" />
                                                <span class="ml-1 text-white">{{ trans('lang.labels.private_link') }}</span>
                                            </template>
                                            <template v-else>
                                                <span class="flex text-white items-center">{{ trans('lang.labels.copied') }}
                                                    <CheckIcon class="h-3.5 w-3.5 text-white ml-2" aria-hidden="true" />
                                                </span>
                                            </template>
                                        </button>
                                        <button type="text" @click="copyLink(library)"
                                            class="bg-sidebar text-sm border border-neutral-400 rounded-lg px-2 py-1  hover:bg-amber-500 flex items-center">
                                            <template v-if="!library.copied">
                                                <ClipboardDocumentIcon class="h-3.5 w-3.5 text-white" aria-hidden="true" />
                                                <span class="ml-1 text-white">{{ trans('lang.labels.public_link') }}</span>
                                            </template>
                                            <template v-else>
                                                <span class="flex text-white items-center">{{ trans('lang.labels.copied') }}
                                                    <CheckIcon class="h-3.5 w-3.5 text-white ml-2" aria-hidden="true" />
                                                </span>
                                            </template>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </draggable>
                    <div class="libraryMobileCards libraryMobile">
                        <div v-for="library in libraries" :key="library" class="bg-cardtop rounded-lg">
                            <div class="px-3 py-5 h-28">
                                <span class="text-neutral-400 breakWord libraryCardTitleOverflow">
                                    {{ library.display_title }}
                                </span>
                            </div>
                            <div class="flex items-center justify-between bg-sidebar py-3 px-3">
                                <span class="text-sm text-gray-400">{{ library.formattedCreated_at }}</span>
                                <button v-if="!checkMember"
                                    @click="nextReviewDate(library.id, library.companyFormReviewDate)" type="button" class="text-sm text-gray-400 hover:bg-amber-500 
                                        hover:text-white px-3 py-1 border border-neutral-400 rounded-lg">{{
                                            library.companyFormReviewDate }}</button>
                            </div>
                            <div class="pb-5">
                                <img :src="mediaPath + library.image" alt="" class="h-60 w-full object-cover">
                            </div>
                            <div>
                                <div class="flex items-center justify-between py-4 px-3" v-if="checkCompanyAdmin">
                                    <span class="theme-eye-svg text-neutral-400 flex items-center">
                                        <EyeIcon class="h-5 w-5 text-white" aria-hidden="true" />
                                        <span class="text-gray-400 text-sm ml-2">{{ library.responsesCount }}</span>
                                    </span>
                                    <div class="flex items-center">
                                        <inertia-link :href="route('app.learning-path')"
                                            class="relative bg-sidebar p-2 rounded-lg hover:bg-amber-500 themeBtn">
                                            <span class="text-gray-300 text-xs tooltip">{{
                                                trans('lang.labels.learning_path') }}</span>
                                            <AcademicCapIcon class="h-5 w-5 text-white" aria-hidden="true" />
                                        </inertia-link>
                                        <button @click="libraryArchived(library.id)"
                                            class="relative bg-sidebar p-2 rounded-lg hover:bg-amber-500 ml-2 themeBtn">
                                            <span class="text-gray-300 text-xs tooltip">{{ trans('lang.modal.archive')
                                            }}</span>
                                            <ArchiveBoxIcon class="h-5 w-5 text-white" aria-hidden="true" />
                                        </button>
                                        <button @click="openDetails(library.id)"
                                            class="relative bg-sidebar p-2 rounded-lg hover:bg-amber-500 ml-2 themeBtn">
                                            <span class="text-gray-300 text-xs tooltip">{{ trans('lang.labels.details')
                                            }}</span>
                                            <IdentificationIcon class="h-5 w-5 text-white" aria-hidden="true" />
                                        </button>
                                    </div>

                                </div>
                                <hr class="h-px bg-neutral-500" v-if="checkCompanyAdmin">
                                <div class="mt-4 mb-3 flex items-center px-3" v-if="checkCompanyAdmin">
                                    <inertia-link :href="route('library-view', library.typeform_id)"
                                        class="bg-amber-500 px-4 py-2 text-white rounded-lg w-full text-center">
                                        {{ trans('lang.labels.responses') }}
                                    </inertia-link>
                                </div>
                                <div class="flex items-center justify-between pt-4 pb-1 px-3 mb-2" v-if="checkCompanyAdmin">

                                    <inertia-link
                                        :href="route('digital-asset', { 'id': library.typeform_id, 'redirect_url': route('app.library') })"
                                        class="flex items-center justify-center text-neutral-400 hover:text-amber-500 theme-link text-sm">
                                        <EyeIcon class="h-3.5 w-3.5 text-neutral-400" aria-hidden="true" />
                                        <span class="ml-1">{{ trans('lang.labels.open_module') }}</span>
                                    </inertia-link>
                                    <inertia-link :href="route('admin.manage-groups')"
                                        class="flex items-center justify-center text-neutral-400 hover:text-amber-500 theme-link text-sm">
                                        <UserGroupIcon class="h-3.5 w-3.5 text-neutral-400" aria-hidden="true" />
                                        <span class="ml-1">{{ trans('lang.labels.manage_groups') }}</span>
                                    </inertia-link>
                                </div>
                                <input type="text" name="" :id="'copy_link_public_' + library.id"
                                    :value="route('public.digital-asset', library.typeform_id)"
                                    :data-typeform_id="library.typeform_id" style="position: absolute; visibility: hidden">
                                <div class="pt-4 pb-1 px-3 mb-6" v-if="checkMember">
                                    <hr class="border-0 bg-gray-300 mb-4 mt-2 w-full h-px">
                                    <inertia-link
                                        :href="route('digital-asset', { 'id': library.typeform_id, 'redirect_url': route('app.library') })"
                                        class="cursor-pointer block bg-amber-500 w-full px-4 rounded-lg py-3 hover:bg-card text-sm text-white text-center mt-6">
                                        {{ trans('lang.labels.go_to_training') }}
                                    </inertia-link>
                                </div>
                                <div class="flex items-center justify-between px-3 pb-4" v-if="checkCompanyAdmin">
                                    <button type="text" @click="copyLinkPrivate(library)"
                                        class="bg-sidebar text-sm border border-neutral-400 rounded-lg px-2 py-1 hover:bg-amber-500 flex items-center">
                                        <template v-if="!library.copiedPrivate">
                                            <ClipboardDocumentIcon class="h-3.5 w-3.5 text-white" aria-hidden="true" />
                                            <span class="ml-1 text-white">{{ trans('lang.labels.private_link') }}</span>
                                        </template>
                                        <template v-else>
                                            <span class="flex text-white items-center">{{ trans('lang.labels.copied') }}
                                                <CheckIcon class="h-3.5 w-3.5 text-white ml-2" aria-hidden="true" />
                                            </span>
                                        </template>
                                    </button>
                                    <button type="text" @click="copyLink(library)"
                                        class="bg-sidebar text-sm border border-neutral-400 rounded-lg px-2 py-1  hover:bg-amber-500 flex items-center">
                                        <template v-if="!library.copied">
                                            <ClipboardDocumentIcon class="h-3.5 w-3.5 text-white" aria-hidden="true" />
                                            <span class="ml-1 text-white">{{ trans('lang.labels.public_link') }}</span>
                                        </template>
                                        <template v-else>
                                            <span class="flex text-white items-center">{{ trans('lang.labels.copied') }}
                                                <CheckIcon class="h-3.5 w-3.5 text-white ml-2" aria-hidden="true" />
                                            </span>
                                        </template>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="px-4 py-5 rounded-b-3xl">
                <Pagination v-if="pagination.lastPage != 1" @refreshTable="createLibrary"
                    :currentPage="pagination.currentPage" :lastPage="pagination.lastPage" :total="pagination.total" />
            </div>
            <review @closeReview="isCloseReview()" v-if="isReviewDate" :data="modalData"></review>
            <details-view v-if="isDetails" @closeDesc="closeDesc()" :id="formId"></details-view>
        </AuthenticatedLayout>
    </div>
</template>
<script>
import draggable from 'vuedraggable'

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { ArchiveBoxIcon, EyeIcon, AcademicCapIcon, ClipboardDocumentIcon, IdentificationIcon, UserGroupIcon, CheckIcon } from "@heroicons/vue/24/solid";
import Pagination from "@/Components/Pagination.vue";
import Review from '@/Components/ReviewModule/Review.vue'
import DetailsView from '@/Components/Library/Details.vue'
import { mapStores, mapState } from "pinia";
import { useAppStore } from "@/store";
import { isMember, isCompanyAdmin } from "@/helpers";
export default {
    display: "Table",
    order: 8,
    components: {

        AuthenticatedLayout,
        AcademicCapIcon,
        EyeIcon,
        ArchiveBoxIcon, UserGroupIcon,
        ClipboardDocumentIcon,
        IdentificationIcon,
        Pagination,
        Review,
        CheckIcon,
        draggable,
        DetailsView
    },
    props: {
        user: {
            type: Object
        }
    },
    data() {
        return {
            dragging: false,
            search: "",
            isReviewDate: false,
            isDetails: false,
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
                isArchived: 0
            },
            modalData: {
                formId: '',
                reviewDate: '',
            },
        }
    },
    computed: {
        ...mapStores(useAppStore),
        ...mapState(useAppStore, [
            "unreadNotificationCount",
            "recentNotifications",
            "user", 'userRole'
        ]),
        checkMember() {
            let role = this.appStore.userRole;
            return isMember(role);
        },
        checkCompanyAdmin() {
            let role = this.appStore.userRole;
            return isCompanyAdmin(role);
        },

    },
    watch: {
        search: function (value) {
            let that = this;
            clearTimeout(that.searchTimeout);
            that.searchTimeout = setTimeout(function () {
                that.queryData.search = that.search;
                that.createLibrary();
            }, 700);
        },
        isReviewDate: function () {
            document.body.style.overflow = this.isReviewDate ? "hidden" : "";
        },
        isDetails: function () {
            document.body.style.overflow = this.isDetails ? "hidden" : "";
        },
    },
    methods: {
        setPagination(response) {
            this.pagination.total = response.data.meta.total;
            this.pagination.lastPage = response.data.meta.last_page;
            this.pagination.currentPage = response.data.meta.current_page;
        },
        createLibrary(page) {
            this.queryData.page = page;
            const headers = { "Content-Type": "application/json" };
            //for table data is loading after fetch ==>
            axios.get("/api/library", { params: this.queryData }, headers)
                .then((response) => {
                    this.libraries = response.data.data;
                    this.setPagination(response);
                })
        },
        libraryArchived(id) {
            let that = this;
            const headers = { "Content-Type": "application/json" };
            axios.put("/api/archived-module/" + id, headers)
                .then((response) => {
                    if (response.status == 200) {
                        that.createLibrary(1);
                        let notification = {
                            heading: 'success',
                            subHeading: response.data.message,
                            type: "success",
                        };
                        that.appStore.setNotification(notification);
                    }
                })
        },
        nextReviewDate: function (id, reviewDate) {
            this.modalData.formId = id;
            this.isReviewDate = true;
            this.modalData.reviewDate = reviewDate;
            document.body.style.overflow = 'hidden';
        },
        copyLink(item) {
            const textToCopy = route('public.digital-asset', item.typeform_id);
            this.copyText(textToCopy);
            item.copied = true;
            setTimeout(() => {
                item.copied = false;
            }, 2000);
        },
        copyLinkPrivate(item) {
            const textToCopy = route('digital-asset', { 'id': item.typeform_id, 'form_id': item.encryptId, 'redirect_url': route('app.library') });
            this.copyText(textToCopy);
            item.copiedPrivate = true;
            setTimeout(() => {
                item.copiedPrivate = false;
            }, 2000);
        },
        copyText(textToCopy) {
            // if (window.isSecureContext === false) {
            //     alert('Please use a secure connection to copy the link');
            //     return;
            // }
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
        isCloseReview() {
            this.isReviewDate = false;
            this.createLibrary(1);
            document.body.style.overflow = '';
        },
        refreshLibrary() {
            this.createLibrary(1);
        },
        updateLibraryOrder: function () {
            let updatedData = [];
            this.libraries.map(library => {
                updatedData.push(library.id);
            })
            axios.post("/api/update-order-action", updatedData)
                .then((response) => {
                    if (response.status == 200) {
                        this.createLibrary(1);
                        let notification = {
                            heading: 'success',
                            subHeading: response.data.message,
                            type: "success",
                        };
                        this.appStore.setNotification(notification);;
                    }
                })
        },
        openDetails(id) {
            this.formId = id;
            this.isDetails = true;
        },
        closeDesc() {
            this.isDetails = false;
        }
    },
    created() {
        this.createLibrary(1);
    },
    unmounted() {
        clearTimeout(this.searchTimeout);
    },

}
</script>
