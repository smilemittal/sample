<template>
    <div class="relative shadow-md sm:rounded-lg mt-5">
        <div class="flex justify-between items-center flex-wrap p-4 bg-sidebar rounded-t-3xl border-b border-zinc-200">
            <h4 class="text-xl text-neutral-300 font-semibold">{{ trans('lang.labels.members') }}</h4>
            <div class="filterBtn">
                <button type="button" @click="openFilter()"
                    class="btn h-8 w-8 theme-dropdown-btn bg-amber-500 rounded-lg flex justify-center items-center">
                    <FunnelIcon class="h-5 w-5 text-white" aria-hidden="true" />
                </button>
            </div>
            <div class="desktopFilters flex justify-start items-center flex-wrap gap-3 mt-3 sm:mt-0 md:mt-0">
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
                <button @click="isLock()" v-if="lock.is_lock == 0"
                    class="flex items-center justify-center h-10 sm:w-fit text-sm text-neutral-400 px-2 rounded-lg border-solid border border-zinc-300 hover:bg-amber-500 hover:text-white">
                    {{ trans('lang.labels.add_member') }}
                </button>
                <button @click="isMember()" v-else
                    class="flex items-center justify-center sm:w-fit h-10 text-sm text-neutral-400 px-2 rounded-lg border-solid border border-zinc-300 hover:bg-amber-500 hover:text-white">
                    {{ trans('lang.labels.add_member') }}
                </button>
                <button @click="$emit('openDate', '')" v-if="learningMembers != ''"
                    class="flex items-center justify-center w-30 sm:w-fit h-10 text-sm text-neutral-400 px-2 rounded-lg border-solid border border-zinc-300 hover:bg-amber-500 hover:text-white">
                    {{ trans('lang.labels.schedule_all_user') }}
                </button>
            </div>
            <div v-if="mobileFilters" class="mobileFilters flex flex-wrap gap-1 mt-4">
                <div class="relative searchTable">
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
                <button @click="isLock()" v-if="lock.is_lock == 0"
                    class="flex items-center justify-center h-10 sm:w-fit text-sm text-neutral-400 px-2 rounded-lg border-solid border border-zinc-300 hover:bg-amber-500 hover:text-white">
                    {{ trans('lang.labels.add_member') }}
                </button>
                <button @click="isMember()" v-else
                    class="flex items-center justify-center sm:w-fit h-10 text-sm text-neutral-400 px-2 rounded-lg border-solid border border-zinc-300 hover:bg-amber-500 hover:text-white">
                    {{ trans('lang.labels.add_member') }}
                </button>
                <button @click="$emit('openDate', '')" v-if="learningMembers != ''"
                    class="flex items-center justify-center w-30 sm:w-fit h-10 text-sm text-neutral-400 px-2 rounded-lg border-solid border border-zinc-300 hover:bg-amber-500 hover:text-white">
                    {{ trans('lang.labels.schedule_all_user') }}
                </button>
            </div>
        </div>
        <div class="themeOverflowCustom themeOverflowTable">
            <div v-if="learningMembers.length == 0" class="themeNoFound bg-body">
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
            <table v-else
                class="theme-table w-full text-sm text-left text-gray-500 dark:text-gray-400 themeTablePathMembers">
                <thead class="text-xs text-gray-700 uppercase bg-sidebar dark:text-gray-400">
                    <tr>
                        <th scope="col" class="text-neutral-300 font-semibold text-base p-4 tableMultiActions"></th>
                        <th scope="col" class="text-neutral-300 font-semibold text-base p-4 tableImage">{{
                            trans('lang.labels.image') }}</th>
                        <th scope="col" class="text-neutral-300 font-semibold text-base p-4 tableCompanyName">{{
                            trans('lang.labels.name') }}</th>
                        <th scope="col" class="text-neutral-300 font-semibold text-base p-4 tableIdentifySubject">{{
                            trans('lang.labels.email') }}</th>
                        <th scope="col" class="text-neutral-300 font-semibold text-base p-4">{{
                            trans('lang.labels.schedule_date') }}</th>
                        <th scope="col" class="text-neutral-300 font-semibold text-base p-4">{{
                            trans('lang.labels.member_create_at') }}
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="learningMember in learningMembers" :key="learningMember.id"
                        class="border-b dark:border-gray-700 text-base text-neutral-300 hover:text-white">
                        <td class="p-4">
                            <!-- Default dropstart button -->
                            <div class="btn-group dropstart">
                                <button type="button" class="btn btn-secondary h-10 w-10 theme-dropdown-btn"
                                    data-bs-toggle="dropdown" aria-expanded="false" data-dropdown-trigger="hover">
                                    <EllipsisVerticalIcon class="h-8 w-8 text-white m-auto" aria-hidden="true" />
                                </button>
                                <div class="dropdown-menu theme-dropdown-menu bg-card rounded-2xl p-0.5"
                                    v-if="learningMember.archived_at == null">
                                    <ul class="py-0">
                                        <li class="px-4 py-3 bg-card text-white rounded-t-2xl">{{
                                            trans('lang.labels.options') }}</li>
                                        <li class="">
                                            <a @click="$emit('openDate', learningMember.userId)"
                                                class="cursor-pointer flex items-center bg-body w-full px-4 py-3 hover:bg-card text-white">
                                                <PencilIcon class="h-4 w-4 text-white mr-3" aria-hidden="true" />
                                                {{ trans('lang.labels.edit_schedule') }}
                                            </a>
                                        </li>
                                        <li class="">
                                            <a @click="$emit('openDeleteMember', learningMember.userId)"
                                                class="cursor-pointer flex items-center bg-body w-full px-4 py-3 hover:bg-card text-red-600 rounded-b-2xl">
                                                <UserMinusIcon class="h-4 w-4 text-red-600 mr-3" aria-hidden="true" />
                                                {{ trans('lang.labels.remove_member') }}
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </td>
                        <td class="p-4">
                            <span v-if="learningMember.archived_at != null"> {{ trans('lang.modal.archived') }}</span>
                            <img :src="mediaPath + learningMember.image" alt="member"
                                class="w-14 h-14 rounded-lg object-cover">
                        </td>
                        <td class="p-4 breakWord">
                            <span>{{ learningMember.userName }}</span>
                        </td>
                        <td class="p-4">
                            <span>{{ learningMember.userEmail }}</span>
                        </td>
                        <td class="p-4">
                            <button v-if="learningMember.scheduleDate == null"
                                @click="$emit('openDate', learningMember.userId)" class="px-3 py-2 rounded-lg text-sm text-btnSubmitText bg-btnSubmitBg 
                                        hover:bg-amber-500 hover:text-white">{{ trans('lang.labels.schedule_date')
                                        }}</button>
                            <span v-else>{{ learningMember.scheduleDate }}</span>
                        </td>
                        <td class="p-4">
                            <span>{{ learningMember.created_at }}</span>
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
    EllipsisVerticalIcon, PencilIcon, UserMinusIcon,
    FunnelIcon
} from '@heroicons/vue/24/solid'

export default {
    components: {
        Pagination,
        EllipsisVerticalIcon,
        PencilIcon,
        UserMinusIcon,
        FunnelIcon
    },
    props: ['id'],
    data() {
        return {
            mobileFilters: false,
            learningMembers: [],
            search: '',
            lock: '',
            queryData: {
                search: '',
                is_deleted: 0,
                page: 1,
                sortField: 'id',
                orderDir: 'ASC'
            },
            pagination: {
                currentPage: 1,
                lastPage: 1,
                total: 0,
            },
            modelData: {
                title: 'Are You Sure You want to delete this member?',
                memberId: '',
            }
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
        openFilter() {
            this.mobileFilters = !this.mobileFilters;
        },
        setPagination(response) {
            this.pagination.total = response.data.meta.total;
            this.pagination.lastPage = response.data.meta.last_page;
            this.pagination.currentPage = response.data.meta.current_page;
        },
        createTable(page) {
            this.queryData.page = page ?? 1;
            //for table data is loading after fetch ==>
            axios.get('/api/learning-path-members/' + this.id, { params: this.queryData })
                .then((response) => {
                    if (response.status = true) {
                        this.learningMembers = response.data.data;
                        this.setPagination(response)
                    }
                })
                .catch((error) => {

                })
                .finally(() => {
                    document.body.style.overflow = '';
                });
        },
        lockPath() {
            axios.get('/api/edit-learning-path/' + this.id)
                .then((response) => {
                    this.lock = response.data.data;
                })
                .catch((error) => {

                })
                .finally(() => {
                });
        },
        refresh() {
            this.lockPath();
            this.createTable(1);
        },
        isDelete() {
            this.$emit("openDelete");
        },
        isMember() {
            this.$emit("openMember");
        },
        isLock() {
            this.$emit("openMemberLock");
        }

    },
    created: function () {
        this.createTable();
        this.lockPath();
    },
    unmounted() {
        clearTimeout(this.searchTimeout);
    },
}
</script>