<template>
    <!-- Main modal -->
    <div id="createMembers" class="bg-bgModal fixed top-0 flex items-center justify-center left-0 right-0 z-50 w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 
            h-full md:h-full">
        <div class="relative w-full h-auto max-w-4xl md:h-auto">
            <!-- Modal content -->
            <div class="relative rounded-lg shadow bg-body">
                <!-- Modal header -->
                <div class="flex items-start justify-between p-4 rounded-t">
                    <div>
                        <h3 class="text-xl font-semibold text-gray-300">
                            {{ trans("lang.labels.add_new_group") }}
                        </h3>
                    </div>
                    <button type="button" @click="isCloseAdd()" class="">
                        <XCircleIcon class="h-9 w-9 text-white" aria-hidden="true" />
                    </button>
                </div>
                <!-- Form start here -->
                <div class="p-6 space-y-6 h-full sm:h-full lg:h-full overflow-y-auto theme-modal-body">
                    <div class="grid md:grid-cols-2 sm:grid-cols-1 gap-3">
                        <div class="relative">
                            <label for="business_member"
                                class="block mb-2 text-sm font-medium text-neutral-100 dark:text-white"> {{
                                    trans("lang.labels.group_name") }}
                            </label>
                            <input type="text" id="business_name"
                                class="block px-2.5 pb-2.5 pt-2.5 w-full text-sm text-neutral-100 bg-sidebar rounded-lg border-1 border-gray-600 appearance-none
                                                                                        dark:focus:border-amber-500 focus:outline-none focus:ring-0 focus:border-amber-500 peer"
                                placeholder=" " v-model="form.name" />
                        </div>
                        <div class="relative">
                            <label for="business_member" class="block mb-2 text-sm font-medium text-neutral-100">
                                {{ trans("lang.labels.select") }}
                            </label>
                            <select id="business_member"
                                class="bg-sidebar border border-gray-600 text-neutral-100 text-sm rounded-lg block w-full p-2.5
                                                dark:focus:border-amber-500 focus:outline-none focus:ring-0 focus:border-amber-500">
                                <option selected>{{ company.name }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="mt-4">
                        <div class="flex items-center mb-4 mt-2">
                            <input id="belong_xme_check" type="checkbox" v-model="form.is_assigned_default" @change="changeDefaultType"
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                            <label for="belong_xme_check" class="ml-2 text-sm font-medium text-amber-500">
                                Default Assigned New Group
                            </label>
                        </div>
                        <!-- <div v-if="form.is_assigned_default">
                            <div class="flex items-center mb-4 mt-2">
                                <input id="belong_xme_check_ba" type="checkbox" value="7" v-model="form.default_assignned_roles"
                                    class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="belong_xme_check_ba" class="ml-2 text-sm font-medium text-amber-500">
                                    Business Admin
                                </label>
                            </div>
                            <div class="flex items-center mb-4 mt-2">
                                <input id="belong_xme_check_ma" type="checkbox" value="5" v-model="form.default_assignned_roles"
                                    class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="belong_xme_check_ma" class="ml-2 text-sm font-medium text-amber-500">
                                    Member
                                </label>
                            </div>
                        </div> -->
                        
                        <assign-admin :form="form" :add="isAdd"></assign-admin>
                    </div>
                </div>
                <!-- Modal footer -->
                <div class="flex items-center justify-end p-6 space-x-2 rounded-b dark:border-gray-600">
                    <button type="button" @click="isCloseAdd()"
                        class="text-btnCancelText bg-btnCancelBg font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                        {{ trans("lang.modal.cancel") }}</button>
                    <button :disabled="processing" @click="createGroup()"
                        class="text-btnSubmitText bg-btnSubmitBg rounded-lg text-sm font-medium px-5 py-2.5">
                        {{ trans("lang.labels.create_group") }}</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
import { mapStores } from 'pinia'
import { useAppStore } from "@/store";
import AssignAdmin from '@/Components/Module/DefaultAssign.vue'
import {
    XCircleIcon
} from '@heroicons/vue/24/solid'
export default {
    name: 'CreateCompany',
    components: {
        XCircleIcon,
        AssignAdmin
    },
    data() {
        return {
            errors: {
                name: "",
            },
            form: {
                name: '',
                is_assigned_default: false,
                default_assignned_roles: [],
            },
            processing: false,
            company: {}

        }
    },
    methods: {
        changeDefaultType(){
            if(!this.form.is_assigned_default) {
                this.form.default_assignned_roles = [];
            }
        },
        createGroup() {
            const config = {
                headers: {
                    'content-type': 'multipart/form-data'
                }
            }
            this.processing = true;
            axios.post('/api/create-group', this.form, config)
                .then((res) => {
                    console.log(res);
                    if (res.status == 200) {
                        this.form = {};
                        this.isCloseAdd();
                        let notification = {
                            heading: 'success',
                            subHeading: res.data.message,
                            type: "success",
                        };
                        this.appStore.setNotification(notification);
                    }
                })
                .catch((error) => {
                    let that = this;
                    console.log(error.response);
                    // that.errors = error.response.messages;
                    // error.response.status Check status code
                }).finally(() => {
                    this.processing = false;
                    //Perform action in always
                });
        },
        closeAddNew() {
            this.$emit('cancel');
        },
        currentCompany() {
            let that = this;
            axios
                .get("/api/companies/" + this.$page.props.auth.user.company_id)
                .then((res) => {
                    that.company = res.data.data;
                })
                .catch((error) => {
                })
                .finally(() => {
                    //Perform action in always
                });
        },
        isCloseAdd() {
            this.$emit("closeAdd");
        },
    },
    created() {
        this.currentCompany();
    },
    computed: {
        ...mapStores(useAppStore),
    },
}
</script>
