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
                            {{ trans('lang.labels.add_member_to_learning_path') }}
                        </h3>
                    </div>
                    <button type="button" @click="isCloseMember()" class="">
                        <XCircleIcon class="h-9 w-9 text-white" aria-hidden="true" />
                    </button>
                </div>
                <!-- Form start here -->
                <div class="p-6 md:min-h-fit md:max-h-80 sm:max-h-80 sm:min-h-fit overflow-y-auto theme-modal-body">
                    <div
                        class="flex justify-between items-center flex-wrap p-4 bg-sidebar rounded-t-3xl border-b border-zinc-200">
                        <h4 class="text-xl text-neutral-300 font-semibold">{{ trans('lang.labels.add_member') }}</h4>
                        <div class="flex justify-between items-center flex-wrap">
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <svg aria-hidden="true" class="w-5 h-5 text-gray-500 dark:text-gray-400"
                                        fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
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
                                    <tr v-if="membersData.length == 0" class="themeNoFound">
                                        <td colspan="7">
                                            <div class="px-4 py-8 text-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="m-auto mb-3 h-8 w-8"
                                                    viewBox="0 0 576 512">
                                                    <path
                                                        d="M0 64C0 28.7 28.7 0 64 0H224V128c0 17.7 14.3 32 32 32H384v38.6C310.1 219.5 256 287.4 256 368c0 59.1 29.1 111.3 73.7 143.3c-3.2 .5-6.4 .7-9.7 .7H64c-35.3 0-64-28.7-64-64V64zm384 64H256V0L384 128zm48 96a144 144 0 1 1 0 288 144 144 0 1 1 0-288zm59.3 107.3c6.2-6.2 6.2-16.4 0-22.6s-16.4-6.2-22.6 0L432 345.4l-36.7-36.7c-6.2-6.2-16.4-6.2-22.6 0s-6.2 16.4 0 22.6L409.4 368l-36.7 36.7c-6.2 6.2-6.2 16.4 0 22.6s16.4 6.2 22.6 0L432 390.6l36.7 36.7c6.2 6.2 16.4 6.2 22.6 0s6.2-16.4 0-22.6L454.6 368l36.7-36.7z" />
                                                </svg>
                                                <p class="text-gray-400 text-base noFound">{{ trans('lang.modal.no_result_found') }}</p>
                                            </div>
                                        </td>
                                    </tr>
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
                    <button @click="isCloseMember()"
                        class="text-btnCancelText bg-btnCancelBg rounded-lg text-sm font-medium px-5 py-2.5">
                        {{ trans('lang.modal.cancel') }}
                    </button>
                    <button @click="isCloseMember()"
                        class="text-btnSubmitText bg-btnSubmitBg rounded-lg text-sm font-medium px-5 py-2.5">
                        {{ trans('lang.modal.done') }}
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
    components: {
        XCircleIcon
    },
    data() {
        return {
            search: '',
            membersData: [],
            processing: false,
            queryData: {
                search: '',
            },
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
            axios.get('/api/learning-path-pending-members/' + this.id, { params: this.queryData })
                .then((res) => {
                    this.membersData = res.data.data;
                })
                .catch((error) => {
                    // error.response.status Check status code
                }).finally(() => {
                    //Perform action in always
                });
        },
        addMember(memberId) {
            this.processing = true;
            axios.post('/api/add-learningpath-member/' + this.id, {'id' : memberId})
                .then((res) => {
                    if (res.data.status) {
                        this.members();
                        let notification = {
                            heading: 'success',
                            subHeading: res.data.message,
                            type: "success",
                        };
                        this.appStore.setNotification(notification);
                    }
                })
                .catch((error) => {
                    // error.response.status Check status code

                }).finally(() => {
                    this.processing = false;
                    //Perform action in always
                    document.body.style.overflow = '';
                });
        },
        isCloseMember() {
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
