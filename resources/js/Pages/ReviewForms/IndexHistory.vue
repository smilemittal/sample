<template>
    <div>

        <InertiaHead title="Review Forms" />
        <AuthenticatedLayout>
            <div class="flex items-center justify-between flex-wrap mb-4">
                <div class="page-header">
                    <inertia-link :href="route('admin.review-forms')"
                        class="text-neutral-300 font-bold text-xl sm:text-2xl md:text-4xl">{{ reviewMainText
                        }}</inertia-link>
                </div>
            </div>
            <div class="h-full drop-shadow-md rounded-3xl mt-5">
                <div class="relative shadow-md sm:rounded-lg mt-5">
                    <div
                        class="flex justify-between items-center flex-wrap p-4 bg-sidebar rounded-t-3xl border-b border-zinc-200">
                        <h4 class="text-xl text-neutral-300 font-semibold">{{ trans("lang.labels.review_history") }}</h4>
                        <div class="filterBtn">
                            <button type="button" @click="openFilter()"
                                class="btn h-8 w-8 theme-dropdown-btn bg-amber-500 rounded-lg flex justify-center items-center">
                                <FunnelIcon class="h-5 w-5 text-white" aria-hidden="true" />
                            </button>
                        </div>
                        <div class="flex justify-start items-center flex-wrap gap-3 mt-3 sm:mt-3 md:mt-0 multiplyMeTableActions">
                            <label for="review_status"
                                class="desktopFilters block text-sm font-medium dark:text-gray-400 w-full sm:w-fit md:w-fit">
                                {{ trans("lang.labels.filter_by_status") }}
                            </label>
                            <select id="review_status" @change="changeStatus($event)"
                                class="desktopFilters bg-sidebar border-1 border-zinc-300 text-gray-400 text-sm rounded-lg focus:ring-0 focus:border-amber-500 block p-2.5">
                                <option selected>Select</option>
                                <option value="active">Active</option>
                                <option value="inactive">In-Active</option>
                            </select>
                            <div class="desktopFilters relative searchTable">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <svg aria-hidden="true" class="w-5 h-5 text-gray-500 dark:text-gray-400"
                                        fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd"
                                            d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <input type="text" id="simple-search" v-model="search"
                                    class="h-10 bg-sidebar border border-gray-300 text-sm text-neutral-400 rounded-lg focus:ring-0 focus:border-amber-500 block w-full pl-10 p-2.5"
                                    placeholder="Search by title">
                            </div>
                        </div>
                        <div v-if="mobileFilters" class="mobileFilters flex flex-wrap gap-1 mt-4">
                            <label for="review_status"
                                class="block text-sm font-medium dark:text-gray-400 w-full md:w-fit lg:w-fit">
                                {{ trans("lang.labels.filter_by_status") }}
                            </label>
                            <select id="review_status" v-model="status"
                                class="bg-sidebar border-1 border-zinc-300 text-gray-400 text-sm rounded-lg focus:ring-0 focus:border-amber-500 block p-2.5">
                                <option>Status</option>
                                <option value="1">Active</option>
                                <option value="0">In-Active</option>
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
                                    class="h-10 bg-sidebar border border-gray-300 text-sm text-neutral-400 rounded-lg focus:ring-0 focus:border-amber-500 block w-full pl-10 p-2.5"
                                    placeholder="Search by title">
                            </div>
                        </div>
                    </div>
                    <div class="themeOverflowCustom themeReviewOverflow themeOverflowTable">
                        <div v-if="items.length == 0">
                            <div class="themeNoFound">
                                <div>
                                    <div class="px-4 py-8 text-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="m-auto mb-3 h-8 w-8"
                                            viewBox="0 0 576 512">
                                            <path
                                                d="M0 64C0 28.7 28.7 0 64 0H224V128c0 17.7 14.3 32 32 32H384v38.6C310.1 219.5 256 287.4 256 368c0 59.1 29.1 111.3 73.7 143.3c-3.2 .5-6.4 .7-9.7 .7H64c-35.3 0-64-28.7-64-64V64zm384 64H256V0L384 128zm48 96a144 144 0 1 1 0 288 144 144 0 1 1 0-288zm59.3 107.3c6.2-6.2 6.2-16.4 0-22.6s-16.4-6.2-22.6 0L432 345.4l-36.7-36.7c-6.2-6.2-16.4-6.2-22.6 0s-6.2 16.4 0 22.6L409.4 368l-36.7 36.7c-6.2 6.2-6.2 16.4 0 22.6s16.4 6.2 22.6 0L432 390.6l36.7 36.7c6.2 6.2 16.4 6.2 22.6 0s6.2-16.4 0-22.6L454.6 368l36.7-36.7z" />
                                        </svg>
                                        <p class="text-gray-400 text-base noFound">{{
                                            trans("lang.modal.no_result_found") }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <table v-else
                            class="theme-table w-full text-sm text-left text-gray-500 dark:text-gray-400 themeTableReviewArea">
                            <thead class="text-xs text-gray-700 uppercase bg-sidebar dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="text-neutral-300 font-semibold text-base p-4 tableMemberImage">{{
                                        trans("lang.labels.image") }}</th>
                                    <th scope="col" class="text-neutral-300 font-semibold text-base p-4 tableTfId">{{
                                        trans("lang.labels.tf_id") }}</th>
                                    <th scope="col" class="text-neutral-300 font-semibold text-base p-4 tableTitle">{{
                                        trans("lang.labels.title") }}</th>
                                    <th scope="col" class="text-neutral-300 font-semibold text-base p-4 tableType">{{
                                        trans("lang.labels.type") }}</th>
                                    <th scope="col" class="text-neutral-300 font-semibold text-base p-4 tableStatus">{{
                                        trans("lang.labels.status") }}</th>
                                    <th scope="col" class="text-neutral-300 font-semibold text-base p-4">{{
                                        trans("lang.labels.created_at") }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="item in items" :key="item.id"
                                    class="border-b dark:border-gray-700 text-base text-neutral-300 hover:text-white">
                                    <td class="p-4">
                                        <span v-if="item.archived_at != null || item.company_archived_at != null">{{
                                            trans("lang.modal.archive") }}</span>
                                        <img :src="mediaPath + item.image" alt="form"
                                            class="w-14 h-14 rounded-lg object-cover">
                                    </td>
                                    <td class="p-4">
                                        <span>{{ item.typeform_id }}</span>
                                    </td>
                                    <td class="p-4 breakWord">
                                        <span class="themeManageTitle">{{ item.display_title }}</span>
                                    </td>
                                    <td class="p-4">
                                        <div class="text-xs w-fit" :class="item.moduleClass">{{ item.moduleType }}</div>
                                    </td>
                                    <td class="p-4">
                                        <div class="text-xs w-fit" :class="item.statusClass">{{ item.status }}</div>
                                    </td>
                                    <td class="p-4">
                                        <span>{{ item.created_at }}</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="bg-sidebar px-4 py-5 rounded-b-3xl">
                <Pagination v-if="pagination.lastPage != 1" @refreshTable="ReviewFormTable"
                    :currentPage="pagination.currentPage" :lastPage="pagination.lastPage" :total="pagination.total" />
            </div>
            <update @closeUpdate="isCloseUpdate()" v-if="isUpdateReview"></update>
            <review @closeReview="isCloseReview()" v-if="isReviewDate"></review>
        </AuthenticatedLayout>
    </div>
</template>
<script>
import Update from '@/Components/ReviewModule/Update.vue'
import Review from '@/Components/ReviewModule/Review.vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {
    EllipsisVerticalIcon, BarsArrowUpIcon, ClipboardDocumentCheckIcon,
    FunnelIcon
} from '@heroicons/vue/24/solid'
import { mapStores, mapState } from "pinia";
import { useAppStore } from "@/store";
import { isAdmin } from '@/helpers';
export default {
    name: 'ReviewForms',
    components: {
        AuthenticatedLayout,
        Update,
        Review,
        EllipsisVerticalIcon,
        BarsArrowUpIcon, ClipboardDocumentCheckIcon,FunnelIcon
    },
    data() {
        return {
            mobileFilters: false,
            items: [],
            isUpdateReview: false,
            isReviewDate: false,
            searchTimeout: null,
            search: '',
            queryData: {
                page: 1,
                sortField: 'updated_at',
                orderDir: 'DESC',
                type: 1,
                status: '',
            },
            pagination: {
                currentPage: 1,
                lastPage: 1,
                total: 0,
            },
            reviewMainText: ''
        }
    },
    watch: {
        search: function (value) {
            let that = this;
            clearTimeout(that.searchTimeout);
            that.searchTimeout = setTimeout(function () {
                that.queryData.search = that.search;
                that.reviewHistory();
            }, 700);
        },
        isUpdateReview: function () {
            document.body.style.overflow = this.isUpdateReview ? 'hidden' : ''
        },
        isReviewDate: function () {
            document.body.style.overflow = this.isReviewDate ? 'hidden' : ''
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
        reviewHistory() {
            axios.get('/api/review-history', { params: this.queryData })
                .then((response) => {
                    if (response.status == 200) {
                        this.items = response.data.data;
                        this.setPagination(response)
                    }
                })
                .catch((error) => {

                })
                .finally(() => {
                });
        },
        changeStatus(event) {
            let that = this;
            that.queryData.status = event.target.value;
            this.reviewHistory();
        },

        isCloseUpdate() {
            this.isUpdateReview = false;
        }
    },
    created() {
        this.reviewMainText = 'Review Area';
        this.queryData.type = 0;
        if (isAdmin(this.appStore.userRole)) {
            this.reviewMainText = 'Xme Review Area';
            this.queryData.type = 1;
        }
        this.reviewHistory();
    },
    unmounted() {
        clearTimeout(this.searchTimeout);
    },
    computed: {
        ...mapStores(useAppStore),
        ...mapState(useAppStore, [
            'user'
        ]),
    },
}
</script>
