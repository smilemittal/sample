<template>
    <div>

        <InertiaHead title="XME Support" />
        <AuthenticatedLayout>
            <div class="">
                <div class="flex items-center justify-between flex-wrap">
                    <div class="page-header">
                        <inertia-link :href="route('app.xme-area')"
                            class="text-neutral-300 font-bold text-xl sm:text-2xl md:text-4xl">{{
                                trans("lang.labels.xme_support") }}</inertia-link>
                        <h5 class="text-neutral-400 text-sm sm:text-sm md:text-base" v-if="!checkMember">
                            {{
                                trans("lang.labels.here_are_your_system_tutorials_power_partner_modules_to_grow_your_business")
                            }}</h5>
                        <h5 class="text-neutral-400 text-sm sm:text-sm md:text-base" v-if="checkMember">
                            {{
                                trans("lang.labels.here_are_your_system_tutorials_to_help_you_on_your_journey")
                            }}</h5>
                        <h5 class="text-neutral-400 text-sm sm:text-sm md:text-base">
                            {{
                                trans("lang.labels.any_support_you_need_please_email_us")
                            }} <a class="text-amber-500" href="mailto:hello@multiplyme.com.au">hello@multiplyme.com.au</a></h5>
                    </div>
                    <div class="flex items-center flex-wrap md:mt-3 mt-3 lg:mt-0 gap-3">

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
                                                                                  focus:border-amber-500 block w-full pl-10 p-2.5" placeholder="Search">
                        </div>
                        <div v-if="!checkMember">
                            <a v-if="is_archive == 1"
                                class="px-3 py-2 bg-sidebar text-gray-400 border border-gray-400 hover:bg-amber-500 hover:text-white hover-border-white rounded-lg"
                                href="/xme-area/">{{ trans("lang.labels.xme_support") }}</a>

                            <a v-else
                                class="px-3 py-2 bg-sidebar text-gray-400 border border-gray-400 hover:bg-amber-500 hover:text-white hover-border-white rounded-lg"
                                href="/xme-area/archive">{{ trans("lang.modal.archived") }}</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="p-0">
                <div class="themeNoFound" v-if="xmeAreas.length == 0">
                    <div class="px-4 py-8 text-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="m-auto mb-3 h-10 w-10" viewBox="0 0 576 512">
                            <path
                                d="M0 64C0 28.7 28.7 0 64 0H224V128c0 17.7 14.3 32 32 32H384v38.6C310.1 219.5 256 287.4 256 368c0 59.1 29.1 111.3 73.7 143.3c-3.2 .5-6.4 .7-9.7 .7H64c-35.3 0-64-28.7-64-64V64zm384 64H256V0L384 128zm48 96a144 144 0 1 1 0 288 144 144 0 1 1 0-288zm59.3 107.3c6.2-6.2 6.2-16.4 0-22.6s-16.4-6.2-22.6 0L432 345.4l-36.7-36.7c-6.2-6.2-16.4-6.2-22.6 0s-6.2 16.4 0 22.6L409.4 368l-36.7 36.7c-6.2 6.2-6.2 16.4 0 22.6s16.4 6.2 22.6 0L432 390.6l36.7 36.7c6.2 6.2 16.4 6.2 22.6 0s6.2-16.4 0-22.6L454.6 368l36.7-36.7z" />
                        </svg>
                        <p class="text-gray-400 text-base noFound">{{ trans("lang.modal.no_result_found") }}</p>
                    </div>
                </div>
                <div class="xmeAreaCards mt-5">
                    <div class="bg-cardtop rounded-lg" v-for="xmeArea in xmeAreas" :key="xmeArea">
                        <div class="px-3 py-5 h-28 breakWord">
                            <span class="text-neutral-400">{{ xmeArea.display_title }}</span>
                        </div>
                        <div>
                            <img :src="mediaPath + xmeArea.image" class="h-60 w-full object-cover">
                        </div>
                        <div>
                            <div class="flex py-4 px-3">
                                <span class="theme-eye-svg text-neutral-400 flex items-center">
                                    <EyeIcon class="h-4 w-4 text-white" aria-hidden="true" />
                                    <span class="ml-2 text-sm">{{ xmeArea.responsesCount }}</span>
                                </span>
                            </div>
                            <hr class="h-px bg-neutral-500">
                            <div class="flex items-center justify-between py-4 px-3">

                                <inertia-link
                                    :href="route('digital-asset', { 'id': xmeArea.typeform_id,'form_id': xmeArea.encryptId, 'redirect_url': route('app.xme-area') })"
                                    class="flex items-center text-neutral-400 hover:text-amber-500 theme-link text-sm"
                                    :style="xmeArea.isArcheivedClass">
                                    <EyeIcon class="h-4 w-4 text-white" aria-hidden="true" />
                                    <span class="ml-1">
                                        {{ trans("lang.labels.open_modules") }}
                                    </span>
                                </inertia-link>
                                <inertia-link :href="route('admin.manage-groups')"
                                    class="flex items-center text-neutral-400 hover:text-amber-500 theme-link text-sm"
                                    v-if="checkCompany">
                                    <UserGroupIcon class="h-4 w-4 text-white" aria-hidden="true" />
                                    <span class="ml-1">{{ trans("lang.labels.manage_groups") }}</span>
                                </inertia-link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-sidebar px-4 py-5 rounded-b-3xl mt-4">
                <Pagination v-if="pagination.lastPage != 1" @refreshTable="xmeArea" :currentPage="pagination.currentPage"
                    :lastPage="pagination.lastPage" :total="pagination.total" />
            </div>
        </AuthenticatedLayout>
    </div>
</template>
<script>

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { mapState, mapStores } from 'pinia';
import { useAppStore } from '@/store'
import GeneralMixin from "@/Mixins/GeneralMixin";
import { isCompany, isMember } from "@/helpers";
import Pagination from "@/Components/Pagination.vue";
import {
    EyeIcon, UserGroupIcon
} from '@heroicons/vue/24/solid'
export default {
    props: ['is_archive', 'user'],
    mixins: [GeneralMixin],
    components: {
        AuthenticatedLayout,
        EyeIcon,
        UserGroupIcon,
        Pagination
    },
    computed: {
        ...mapStores(useAppStore),
        ...mapState(useAppStore, [
            "unreadNotificationCount",
            "recentNotifications",
            "user", 'userRole'
        ]),
        checkCompany() {
            let role = this.appStore.userRole;
            return isCompany(role);
        },
        checkMember() {
            let role = this.appStore.userRole;
            return isMember(role);
        }

    },
    data() {
        return {
            searchTimeout: null,
            xmeAreas: "",
            search : '',
            loggedUserRole: "",
            pagination: {
                currentPage: 1,
                lastPage: 1,
                total: 0,
            },
            queryData: {
                page: 1,
                search: this.search,
                is_archive:this.is_archive
            },
        }
    },
    watch: {
        search: function (value) {
            let self = this;
            clearTimeout(self.searchTimeout);
            self.searchTimeout = setTimeout(function () {
                self.queryData.search = value; 
                self.xmeArea();
            }, 700);
        }
    },
    methods: {
        setPagination(response) {
            this.pagination.total = response.data.meta.total;
            this.pagination.lastPage = response.data.meta.last_page;
            this.pagination.currentPage = response.data.meta.current_page;
        },
        xmeArea(page) {
            this.queryData.page = page;
            const headers = { "Content-Type": "application/json" };
            //for table data is loading after fetch ==>
            axios.get("/api/xme-area", { params: this.queryData }, headers)
                .then((response) => {
                    
                    this.xmeAreas = response.data.data;
                    this.setPagination(response);
                })
        }
    }, created: function () {
        this.xmeArea(1);
        this.loggedUserRole = this.user.role.name;
    },
    unmounted() {
        clearTimeout(this.searchTimeout);
    },
}
</script>
