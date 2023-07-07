<template>
    <!-- Main modal -->
    <div id="duplicatePath"
        class="bg-bgModal fixed top-0 flex items-center justify-center left-0 right-0 z-50 w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-modal md:h-full">
        <div class="relative w-full h-auto max-w-2xl md:h-auto">
            <!-- Modal content -->
            <div class="relative rounded-lg shadow bg-body">
                <!-- Modal header -->
                <div class="flex items-start justify-between p-4 rounded-t">
                    <div>
                        <h3 class="text-xl font-semibold text-gray-300">
                            {{ trans("lang.labels.edit_group") }}
                        </h3>
                    </div>
                    <button type="button" @click="isClose()" class="">
                        <XCircleIcon class="h-9 w-9 text-white" aria-hidden="true" />
                    </button>
                </div>
                <!-- Form start here -->
                <div class="p-6 space-y-6 h-fit overflow-y-auto theme-modal-body">
                    <div class="grid md:grid-cols-1 sm:grid-cols-1 gap-3 mb-2">
                        <div class="theme-input">
                            <h5 class="text-base text-neutral-400 mb-3">{{ trans("lang.labels.please_enter_the_group_title")
                            }}</h5>
                            <div class="relative">
                                <input type="text" id="title_path"
                                    class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-neutral-100 bg-sidebar rounded-lg border-1 border-transparent appearance-none dark:focus:border-amber-500 focus:outline-none focus:ring-0 focus:border-amber-500 peer"
                                    placeholder=" " v-model="form.name" />
                                <label for="title_path"
                                    class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-sidebar px-2 peer-focus:px-2 peer-focus:text-amber-500 peer-focus:dark:text-amber-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 left-1">
                                    {{ trans("lang.labels.title") }}
                                </label>
                            </div>
                            <span v-if="errors.name" class="mt-2 text-sm text-red-600 theme-error-message">{{
                                errors.name[0] }}</span>
                        </div>
                    </div>
                    <div class="mt-4">
                        <div class="flex items-center mb-4 mt-2">
                            <input id="belong_xme_check" type="checkbox" v-model="form.is_assigned_default"
                                @change="changeDefaultType"
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                            <label for="belong_xme_check" class="ml-2 text-sm font-medium text-amber-500">
                                Default Assigned New Group
                            </label>
                        </div>
                        <assign-admin :form="form"></assign-admin>
                    </div>
                </div>
                <!-- Modal footer -->
                <div class="flex items-center justify-end p-6 space-x-2 rounded-b dark:border-gray-600">
                    <button @click="isClose()"
                        class="text-btnCancelText bg-btnCancelBg rounded-lg text-sm font-medium px-5 py-2.5">
                        {{ trans("lang.modal.cancel") }}
                    </button>
                    <button @click="submitForm()" :disabled="processing"
                        class="text-btnSubmitText bg-btnSubmitBg rounded-lg text-sm font-medium px-5 py-2.5">
                        {{ trans("lang.modal.add") }}
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
import AssignAdmin from '@/Components/Module/DefaultAssign.vue'
export default {
    props: ['groupId'],
    components: {
        XCircleIcon,
        AssignAdmin
    },
    data() {
        return {
            form: {
                is_assigned_default: false,
                default_assignned_roles: [],
            },
            errors: {
                name: '',
            },
            selectedForms: [],
            processing: false
        }
    },
    methods: {
        changeDefaultType() {
            if (!this.form.is_assigned_default) {
                this.form.default_assignned_roles = [];
            }
        },
        isClose() {
            this.$emit("closeEdit");
        },
        submitForm() {
            this.processing = true;
            axios.post('/api/update-group/' + this.groupId, this.form)
                .then((res) => {
                    if (res.status == 200) {
                        this.form = {};
                        this.isClose();
                        let notification = {
                            heading: 'success',
                            subHeading: res.data.message,
                            type: "success",
                        };
                        this.appStore.setNotification(notification);
                    }
                })
                .catch((error) => {
                    this.errors = error.response.data.errors;
                    // error.response.status Check status code
                }).finally(() => {
                    this.processing = false;
                    //Perform action in always
                });
        },
        edit() {
            axios.get('/api/edit-group/' + this.groupId)
                .then((response) => {
                    if (response.status == 200) {
                        this.form = response.data.data;
                        this.form.is_assigned_default = (this.form.is_assigned_default ) ? true: false;
                    }
                })
        },
    },
    created: function () {
        this.edit();
    },
    computed: {
        ...mapStores(useAppStore),
    },
}
</script>
