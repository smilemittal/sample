<template>
    <!-- Main modal -->
    <div id="assignCompany"
        class="bg-bgModal fixed top-0 flex items-center justify-center left-0 right-0 z-50 w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-modal md:h-full">
        <div class="relative w-full h-auto max-w-3xl md:h-auto">
            <!-- Modal content -->
            <div class="relative rounded-lg shadow bg-body">
                <!-- Modal header -->
                <div class="flex items-start justify-between p-6 rounded-t">
                    <div>
                        <h3 class="text-xl font-semibold text-gray-300">
                            {{ trans('lang.labels.add_member_to_group') }}
                        </h3>
                    </div>
                    <button type="button" @click="closeMember()" class="">
                        <XCircleIcon class="h-9 w-9 text-white" aria-hidden="true" />
                    </button>
                </div>
                <!-- Form start here -->
                <div class="p-6 md:min-h-fit md:max-h-80 sm:max-h-80 sm:min-h-fit overflow-y-auto theme-modal-body">
                    <div
                        class="flex justify-between items-center flex-wrap p-4 bg-sidebar rounded-t-3xl border-b border-zinc-200">
                        <h4 class="text-xl text-neutral-300 font-semibold">{{ trans('lang.labels.add_member') }}</h4>
                        <div class="flex justify-between items-center flex-wrap">
                            <input
                                class="h-10 text-sm bg-sidebar text-neutral-400 px-2 rounded-lg border-solid border border-zinc-300"
                                placeholder="Search" type="search" v-model="search">
                        </div>
                    </div>
                        <div class="overflow-x-auto">
                            <table class="theme-table w-full text-sm text-left text-gray-500 dark:text-gray-400">
                                <thead class="text-xs text-gray-700 uppercase bg-sidebar dark:text-gray-400">
                                    <tr>
                                        <th scope="col" class="p-4">
                                        </th>
                                        <th scope="col" class="text-neutral-300 font-semibold text-base p-4">{{ trans('lang.labels.member_name') }}
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="member in membersData" :key="member.id"
                                        class="border-b dark:border-gray-700 text-base text-neutral-300 hover:text-white">
                                        <td class="w-4 p-4">
                                            <button @click="addMember(member.id)" type="button" :disabled="processing"
                                                class="bg-btnSubmitBg text-btnSubmitText hover:text-white hover:bg-amber-500 rounded-lg px-4 py-2">{{ trans('lang.modal.add') }}</button>
                                        </td>
                                        <td class="p-4">
                                            <span>{{ member.name }}</span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                </div>
                <!-- Modal footer -->
                <div class="flex items-center justify-end p-6 space-x-2 rounded-b dark:border-gray-600">
                    <button @click="closeMember()"
                        class="text-btnCancelText bg-btnCancelBg rounded-lg text-sm font-medium px-5 py-2.5">
                        Close
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
    name: 'AddMember',
    props: ['id'],
    components:{
        XCircleIcon    
    },
    data() {
        return {
            search: '',
            membersData: [],
            queryData: {
                search: '',
            },
            processing: false,
            searchTimeout: null
        }
    },
    watch: {
        search: function (value) {
            let self = this;
            clearTimeout(self.searchTimeout);
            self.searchTimeout = setTimeout(function () {
                self.queryData.search = self.search;
                self.members();
            }, 700);
        }
    },
    methods: {
        members() {
            axios.get('/api/group-pending-members/' + this.id, { params: this.queryData })
                .then((res) => {
                    this.membersData = res.data.data;
                })  
        },
        addMember(memberId) {
            this.processing = true;
            axios.post('/api/add-group-member/' + this.id, {'user_id': memberId})
                .then((res) => {
                    if (res.status == 200) {
                        let notification = {
                            heading: 'success',
                            subHeading: res.data.message,
                            type: "success",
                        };
                        this.appStore.setNotification(notification);
                        this.members();
                    }
                })
                .catch((error) => {
                    // error.response.status Check status code
                }).finally(() => {
                    this.processing = false;
                    //Perform action in always
                });
        },
        closeMember() {
            this.$emit("closeMember");
        },
    },
    created: function () {
        this.members();
    },
    unmounted() {
        clearTimeout(this.searchTimeout);
    },
    computed: {
        ...mapStores(useAppStore),
    },
}
</script>
