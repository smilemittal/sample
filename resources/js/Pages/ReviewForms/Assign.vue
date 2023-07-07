<template>
    <div>

        <InertiaHead title="In Production" />
        <AuthenticatedLayout>
            <div class="flex items-center justify-between flex-wrap mb-4">
                <div class="page-header">
                    <inertia-link :href="route('app.in-production-modules')"
                        class="text-neutral-300 font-bold text-xl sm:text-2xl md:text-4xl">{{ checkAdmin ? 'XME Review Area'
                            : 'In Production' }}</inertia-link>
                </div>
                <!-- <div class="flex items-center flex-wrap">
                    <a class="flex items-center px-3 py-2 bg-sidebar text-neutral-400 hover:bg-amber-500 hover:text-white rounded-lg border border-neutral-500 ml-3"
                        :href="route('review-formshistory')">
                        <ArrowPathIcon class="h-4 w-4 text-white m-auto" aria-hidden="true" />
                        <span class="ml-2">{{ trans("lang.labels.review_history") }}</span>
                    </a>
                </div> -->
            </div>
            <div class="dark:bg-gray-700 drop-shadow-lg h-full drop-shadow-md rounded-3xl mt-5">
                <div class="relative shadow-md sm:rounded-lg mt-5">
                    <div
                        class="flex justify-between items-center flex-wrap p-4 bg-sidebar rounded-t-3xl border-b border-zinc-200">
                        <h4 class="text-xl text-neutral-300 font-semibold">{{ trans("lang.labels.new_assigned_module") }}
                        </h4>
                        <div class="filterBtn">
                            <button type="button" @click="openFilter()"
                                class="btn h-8 w-8 theme-dropdown-btn bg-amber-500 rounded-lg flex justify-center items-center">
                                <FunnelIcon class="h-5 w-5 text-white" aria-hidden="true" />
                            </button>
                        </div>
                        <div class="flex justify-start items-center flex-wrap gap-3">
                            <label for="review_status" class="desktopFilters block w-full sm:w-fit text-sm font-medium dark:text-gray-400">
                                {{ trans("lang.labels.filter_by_status") }}
                            </label>
                            <select id="review_type" v-model="status" @change="changeStatus($event)"
                                class="desktopFilters bg-sidebar border-1 border-zinc-300 text-gray-400 text-sm rounded-lg focus:ring-0 focus:border-amber-500 block p-2.5">
                                <option>Module Status</option>
                                <option value="new">New</option>
                                <option value="update">Support reply</option>
                                <option value="adminrequest">Request send to Support</option>
                            </select>
                            <div class="desktopFilters relative">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <svg aria-hidden="true" class="w-5 h-5 text-gray-500 dark:text-gray-400"
                                        fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd"
                                            d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <input type="text" id="simple-search" v-model="search"
                                    class="h-10 bg-sidebar border border-gray-300 text-sm text-neutral-400 rounded-lg focus:ring-0 
                                                                                  focus:border-amber-500 block w-full pl-10 p-2.5" placeholder="Search by title">
                            </div>
                        </div>
                        <div v-if="mobileFilters" class="mobileFilters flex flex-wrap gap-1 mt-4">
                            <label for="review_status" class="block w-full sm:w-fit text-sm font-medium dark:text-gray-400">
                                {{ trans("lang.labels.filter_by_status") }}
                            </label>
                            <select id="review_type" v-model="status" @change="changeStatus($event)"
                                class="bg-sidebar border-1 border-zinc-300 text-gray-400 text-sm rounded-lg focus:ring-0 focus:border-amber-500 block p-2.5">
                                <option>Module Status</option>
                                <option value="new">New</option>
                                <option value="update">Support reply</option>
                                <option value="adminrequest">Request send to Support</option>
                            </select>
                            <div class="relative searchTable">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <svg aria-hidden="true" class="w-5 h-5 text-gray-500 dark:text-gray-400"
                                        fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd"
                                            d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <input type="text" id="simple-search" v-model="search"
                                    class="h-10 bg-sidebar border border-gray-300 text-sm text-neutral-400 rounded-lg focus:ring-0 
                                    focus:border-amber-500 block w-full pl-10 p-2.5" placeholder="Search by title">
                            </div>
                        </div>
                    </div>
                    <div class="themeOverflowCustom themeReviewOverflow themeOverflowTable">
                        <div v-if="forms.length == 0" class="themeNoFound bg-body">
                            <div>
                                <div class="px-4 py-8 text-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="m-auto mb-3 h-8 w-8"
                                        viewBox="0 0 576 512">
                                        <path
                                            d="M0 64C0 28.7 28.7 0 64 0H224V128c0 17.7 14.3 32 32 32H384v38.6C310.1 219.5 256 287.4 256 368c0 59.1 29.1 111.3 73.7 143.3c-3.2 .5-6.4 .7-9.7 .7H64c-35.3 0-64-28.7-64-64V64zm384 64H256V0L384 128zm48 96a144 144 0 1 1 0 288 144 144 0 1 1 0-288zm59.3 107.3c6.2-6.2 6.2-16.4 0-22.6s-16.4-6.2-22.6 0L432 345.4l-36.7-36.7c-6.2-6.2-16.4-6.2-22.6 0s-6.2 16.4 0 22.6L409.4 368l-36.7 36.7c-6.2 6.2-6.2 16.4 0 22.6s16.4 6.2 22.6 0L432 390.6l36.7 36.7c6.2 6.2 16.4 6.2 22.6 0s6.2-16.4 0-22.6L454.6 368l36.7-36.7z" />
                                    </svg>
                                    <p class="text-gray-400 text-base noFound">
                                        {{ trans("lang.modal.no_result_found") }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        <table v-else
                            class="theme-table w-full text-sm text-left text-gray-500 dark:text-gray-400 themeTableReviewArea">
                            <thead class="text-xs text-gray-700 uppercase bg-sidebar dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="text-neutral-300 font-semibold text-base p-4 tableMultiActions">
                                    </th>
                                    <th scope="col" class="p-4 tableMultiActions">
                                        <div class="flex items-center">
                                            <input id="checkbox-all-search" type="checkbox"
                                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600" />
                                            <label for="checkbox-all-search" class="sr-only">checkbox</label>
                                        </div>
                                    </th>
                                    <th scope="col" class="text-neutral-300 font-semibold text-base p-4 tableTagImages">{{
                                        trans("lang.labels.image") }}</th>
                                    <th scope="col" class="text-neutral-300 font-semibold text-base p-4 tableTfId">{{
                                        trans("lang.labels.tf_id") }}</th>
                                    <th scope="col" class="text-neutral-300 font-semibold text-base p-4 tableTitle">{{
                                        trans("lang.labels.title") }}</th>
                                    <th scope="col" class="text-neutral-300 font-semibold text-base p-4 tableType">{{
                                        trans("lang.labels.type") }}</th>
                                    <th scope="col" class="text-neutral-300 font-semibold text-base p-4 tableStatus">{{
                                        trans("lang.labels.status") }}</th>
                                    <th scope="col" class="text-neutral-300 font-semibold text-base p-4 tableCreatedAt">{{
                                        trans("lang.labels.created_at") }}</th>
                                    <th scope="col" class="text-neutral-300 font-semibold text-base p-4">{{
                                        trans("lang.labels.reviewed_date") }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="form in forms" :key="form.id"
                                    class="border-b dark:border-gray-700 text-base text-neutral-300 hover:text-white">
                                    <td class="p-4">
                                        <!-- Default dropstart button -->
                                        <div class="btn-group dropstart">
                                            <button type="button" class="btn btn-secondary h-10 w-10 theme-dropdown-btn"
                                                data-bs-toggle="dropdown" aria-expanded="false"
                                                data-dropdown-trigger="hover">
                                                <EllipsisVerticalIcon class="h-8 w-8 text-white m-auto"
                                                    aria-hidden="true" />
                                            </button>
                                            <div class="dropdown-menu theme-dropdown-menu bg-card rounded-2xl p-1"
                                                v-if="(form.company_archived_at == null && form.archived_at == null)">
                                                <ul class="py-0">
                                                    <li class="px-4 py-3 bg-card text-white rounded-t-2xl">{{
                                                        trans("lang.labels.options") }}</li>
                                                    <li class="">
                                                        <inertia-link
                                                            :href="route('digital-asset', { 'id': form.typeform_id, 'type_module': 'assign','company_id' : form.enctyptCompanyFormId,'form_id':form.encryptId })"
                                                            class="cursor-pointer flex items-center bg-body w-full px-4 py-3 hover:bg-card text-white">
                                                            <ClipboardDocumentCheckIcon class="h-4 w-4 text-white mr-3"
                                                                aria-hidden="true" />
                                                            {{ trans("lang.labels.view_module") }}
                                                        </inertia-link>

                                                    </li>
                                                    <li class="" v-if="form.companyModuleStatus != 'admin request'">
                                                        <a @click="moveToLibraray(form.encryptId)"
                                                            class="cursor-pointer flex items-center bg-body w-full px-4 py-3 hover:bg-card text-white rounded-b-2xl">
                                                            <BarsArrowUpIcon class="h-4 w-4 text-white mr-3"
                                                                aria-hidden="true" />
                                                            {{ trans("lang.modal.move_to_library") }}
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="w-4 p-4">
                                        <div class="flex items-center">
                                            <input id="checkbox-table-search-1" type="checkbox"
                                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600" />
                                            <label for="checkbox-table-search-1" class="sr-only">checkbox</label>
                                        </div>
                                    </td>
                                    <td class="p-4">
                                        <span class="bg-amber-500 text-white text-xs px-2 py-0.5 rounded-full" v-if="form.companyModuleStatus == 'admin request'">{{
                                            trans("lang.labels.request_send_to_support") }}</span>
                                        <span class="bg-amber-500 text-white text-xs px-2 py-0.5 rounded-full" v-if="form.companyModuleStatus == 'new'">{{ trans("lang.labels.new") }}</span>
                                        <span class="bg-amber-500 text-white text-xs px-2 py-0.5 rounded-full" v-if="form.companyModuleStatus == 'update'">{{ trans("lang.modal.support_reply")
                                        }}</span>

                                        <span
                                            v-if="(form.company_archived_at != null || form.archived_at != null)">Archive{{
                                                trans("lang.modal.archive") }}</span>

                                        <img :src="mediaPath + form.image" class="w-14 h-14 rounded-lg object-cover">
                                    </td>
                                    <td class="p-4">
                                        <span>{{ form.typeform_id }} </span>
                                    </td>
                                    <td class="p-4">
                                        <span>{{ form.display_title }}</span>
                                    </td>
                                    <td class="p-4">
                                        <span class="text-xs" :class="form.moduleClass">{{ form.moduleType }}</span>
                                    </td>
                                    <td class="p-4">
                                        <span class="text-xs" :class="form.statusClass">{{ form.status }}</span>
                                    </td>
                                    <td class="p-4">
                                        <span>{{ form.created_at }}</span>
                                    </td>
                                    <td class="p-4">
                                        <span>{{ form.adminReviewDate }}</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="bg-sidebar px-4 py-5 rounded-b-3xl">
                <Pagination v-if="pagination.lastPage != 1" @refreshTable="assignTable"
                    :currentPage="pagination.currentPage" :lastPage="pagination.lastPage" :total="pagination.total" />
            </div>
            <update @closeUpdate="isCloseUpdate()" v-if="isUpdateReview" :data="modalData"></update>
            <review @closeReview="isCloseReview()" v-if="isReviewDate" :data="modalData"></review>
            <move-library @closeMove="isCloseMove()" @moveToLibrary="library()" v-if="isMove"
                :formId="formId"></move-library>
        </AuthenticatedLayout>
    </div>
</template>
<script>
import Update from '@/Components/ReviewModule/Update.vue'
import Review from '@/Components/ReviewModule/Review.vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Pagination from "@/Components/Pagination.vue";
import MoveLibrary from '@/Components/ReviewModule/MoveLibrary.vue'
import { mapStores } from 'pinia'
import { useAppStore } from "@/store";


import {
    BarsArrowUpIcon, ClipboardDocumentCheckIcon, EllipsisVerticalIcon,
    ArrowPathIcon, ArchiveBoxArrowDownIcon, FunnelIcon
} from '@heroicons/vue/24/solid'
export default {
    name: 'Assign Module',
    props: ['userRole'],
    components: {
        AuthenticatedLayout,
        Update,
        Review,
        Pagination,
        MoveLibrary,
        BarsArrowUpIcon,
        ClipboardDocumentCheckIcon,
        EllipsisVerticalIcon, ArrowPathIcon,
        ArchiveBoxArrowDownIcon, FunnelIcon
    },
    data() {
        return {
            mobileFilters: false,
            forms: [],
            isUpdateReview: false,
            isReviewDate: false,
            isMove: false,
            loggedUserRole: 'superadmin',
            type: 'Type',
            status: 'Module Status',
            modalData: {
                formId: '',
                reviewDate: '',
            },
            searchTimeout: null,
            search: '',

            queryData: {
                search: '',
                isDeleted: 0,
                page: 1,
                type: null,
                status: null,
                sortField: 'forms.id',
                orderDir: 'ASC'

            },
            formId: '',
            pagination: {
                currentPage: 1,
                lastPage: 1,
                total: 0,
            },
        }
    },
    watch: {
        search: function (value) {
            let that = this;
            clearTimeout(that.searchTimeout);
            that.searchTimeout = setTimeout(function () {
                that.queryData.search = that.search;
                that.assignTable();
            }, 700);
        },
        isUpdateReview: function () {
            document.body.style.overflow = this.isUpdateReview ? 'hidden' : ''
        },
        isReviewDate: function () {
            document.body.style.overflow = this.isReviewDate ? 'hidden' : ''
        },
        isMove: function () {
            document.body.style.overflow = this.isMove ? 'hidden' : ''
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
        assignTable(page) {
            this.queryData.page = page;
            //for table data is loading after fetch ==>
            axios.get('/api/new-assigned-modules', { params: this.queryData })
                .then((response) => {
                    if (response.status == 200) {
                        this.forms = response.data.data;
                        this.setPagination(response)
                    }
                })
                .catch((error) => {

                })
                .finally(() => {
                });
        },
        updateModule(id) {
            this.modalData.formId = id;
            this.isUpdateReview = true;
        },
        nextReviewDate(id, reviewDate) {
            this.modalData.formId = id;
            this.isReviewDate = true;
            this.modalData.reviewDate = reviewDate;
        },
        isCloseReview() {
            this.isReviewDate = false;
        },

        isCloseUpdate() {
            this.isUpdateReview = false;
        },
        changeStatus(event) {
            let that = this;
            if (event.target.value != 'Module Status') {
                that.queryData.status = event.target.value;
            } else {
                that.queryData.status = null;
            }
            that.assignTable();
        },
        isCloseMove() {
            this.isMove = false;
        },
        moveToLibraray(id) {
            this.isMove = true;
            this.formId = id;

        },
        library() {
            axios.put('/api/move-to-library/' + this.formId)
                .then((res) => {
                    if (res.status == 200) {
                        this.isCloseMove();
                        let notification = {
                            heading: 'success',
                            subHeading: res.data.message,
                            type: "success",
                        };
                        this.appStore.setNotification(notification);
                        this.assignTable();

                    }
                })
                .catch((error) => {
                    // error.response.status Check status code
                    let that = this;
                    that.errors = error.response.data.errors;
                }).finally(() => {
                    //Perform action in always
                });

        },
        refreshTable() {
            this.$emit('refreshTable');
        }
    },
    created: function () {
        this.assignTable(1);
    },
    unmounted() {
        clearTimeout(this.searchTimeout);
    },
    computed: {
        ...mapStores(useAppStore),
    },

}
</script>
