<template>
    <!-- Main modal -->
    <div id="editMembers"
        class="bg-bgModal fixed top-0 flex items-center justify-center left-0 right-0 z-50 w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-modal md:h-full">
        <div class="relative w-full h-auto max-w-4xl md:h-auto">
            <!-- Modal content -->
            <div class="relative rounded-lg shadow bg-body">
                <!-- Modal header -->
                <div class="flex items-start justify-between p-4">
                    <div>
                        <h3 class="text-xl font-semibold text-gray-300">
                            {{ trans('lang.labels.edit_user') }}
                        </h3>
                    </div>
                    <button type="button" @click="isCloseEdit()" class="">
                        <XCircleIcon class="h-9 w-9 text-white" aria-hidden="true" />
                    </button>
                </div>
                <!-- Form start here -->
                    <div class="p-6 space-y-6 h-96 sm:h-96 lg:h-96 overflow-y-auto theme-modal-body">
                        <div class="mb-3">
                            <div class="w-full">
                                <div
                                    class="theme-upload-img block sm:flex md:flex  items-center justify-between border-2 border-gray-500 border-dashed rounded-lg cursor-pointer bg-sidebar">
                                    <label for="dropzone-file" class="w-full h-64">
                                        <div class="flex flex-col h-full items-center justify-center pt-5 pb-6">
                                            <div>
                                                <div>
                                                    <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                                        <svg aria-hidden="true" class="w-10 h-10 mb-3 text-gray-400"
                                                            fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                                            xmlns="http://www.w3.org/2000/svg">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12">
                                                            </path>
                                                        </svg>
                                                        <p class="mb-2 text-sm text-gray-500 dark:text-gray-400">
                                                            <span class="font-semibold">{{
                                                                trans('lang.labels.click_to_upload') }}</span>
                                                            or drag and drop
                                                        </p>
                                                        <p class="text-xs text-gray-500 dark:text-gray-400">
                                                            SVG, PNG, JPG or GIF (MAX. 800x400px)
                                                        </p>

                                                        <input id="dropzone-file" type="file" class="hidden"
                                                            @change="previewImage" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </label>
                                    <template v-if="editMember.avatar != '' || preview != null">
                                        <div
                                            class="flex flex-col items-center justify-center w-full xs:w-28 sm:w-30 h-64 p-5">
                                            <div
                                                class="flex flex-col items-center justify-center pt-5 pb-6 w-full xs:w-28 sm:w-30">
                                                <img :src="preview != null ? preview : (editMember.avatar)" alt="logo"
                                                    class="h-56 object-cover w-full rounded-lg" />
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>
                        <div class="grid md:grid-cols-2 sm:grid-cols-1 gap-3">
                            <div class="relative">
                                <input type="text" id="business_name"
                                    class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-neutral-100 bg-sidebar rounded-lg border-1 border-transparent appearance-none
                                                                                                    dark:focus:border-amber-500 focus:outline-none focus:ring-0 focus:border-amber-500 peer"
                                    placeholder=" " v-model="editMember.firstname" />
                                <label for="business_name"
                                    class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75
                                                                                                    top-2 z-10 origin-[0] bg-sidebar px-2 peer-focus:px-2 peer-focus:text-amber-500 peer-focus:dark:text-amber-500
                                                                                                    peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75
                                                                                                    peer-focus:-translate-y-4 left-1">
                                    {{ trans('lang.labels.first_name') }}
                                </label>
                                <!-- <span v-if="errors.firstname" class="mt-2">{{
                                    errors.firstname[0]
                                }}</span> -->
                            </div>
                            <div class="relative">
                                <input type="text" id="business_name"
                                    class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-neutral-100 bg-sidebar rounded-lg border-1 border-transparent appearance-none
                                                                                                    dark:focus:border-amber-500 focus:outline-none focus:ring-0 focus:border-amber-500 peer"
                                    placeholder=" " v-model="editMember.lastname" />
                                <label for="business_name"
                                    class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75
                                                                                                    top-2 z-10 origin-[0] bg-sidebar px-2 peer-focus:px-2 peer-focus:text-amber-500 peer-focus:dark:text-amber-500
                                                                                                    peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75
                                                                                                    peer-focus:-translate-y-4 left-1">
                                    {{ trans('lang.labels.last_name') }}
                                </label>
                                <!-- <span v-if="errors.lastname" class="mt-2">{{
                                    errors.lastname[0]
                                }}</span> -->
                            </div>
                            <div class="relative">
                                <input type="text" id="business_name"
                                    class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-neutral-100 bg-sidebar rounded-lg border-1 border-transparent appearance-none
                                                                                                    dark:focus:border-amber-500 focus:outline-none focus:ring-0 focus:border-amber-500 peer"
                                    placeholder=" " v-model="editMember.email" />
                                <label for="business_name"
                                    class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75
                                                                                                    top-2 z-10 origin-[0] bg-sidebar px-2 peer-focus:px-2 peer-focus:text-amber-500 peer-focus:dark:text-amber-500
                                                                                                    peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75
                                                                                                    peer-focus:-translate-y-4 left-1">
                                    {{ trans('lang.labels.email_address') }}
                                </label>
                                <span v-if="errors.email" class="mt-2">{{
                                    errors.email[0]
                                }}</span>
                            </div>
                            <div class="relative">
                                <input type="text" id="business_name"
                                    class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-neutral-100 bg-sidebar rounded-lg border-1 border-transparent appearance-none dark:focus:border-amber-500 focus:outline-none focus:ring-0 focus:border-amber-500 peer"
                                    placeholder=" " v-model="editMember.phone_nr" />
                                <label for="business_name"
                                    class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-sidebar px-2 peer-focus:px-2 peer-focus:text-amber-500 peer-focus:dark:text-amber-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 left-1">
                                    {{ trans('lang.labels.phone_no') }}
                                </label>
                                <span v-if="errors.phone_nr" class="mt-2">{{
                                    errors.phone_nr[0]
                                }}</span>
                            </div>
                        </div>
                    </div>
                    <!-- Modal footer -->
                    <div class="flex items-center justify-end p-6 space-x-2 rounded-b dark:border-gray-600">
                        <button @click="isCloseEdit()" type="button"
                            class="text-btnCancelText bg-btnCancelBg font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                            {{ trans('lang.modal.cancel') }}</button>
                        <button :disabled="processing" @click="updateMemberDetail()"
                            class="text-btnSubmitText bg-btnSubmitBg rounded-lg text-sm font-medium px-5 py-2.5">{{
                                trans('lang.modal.update') }}</button>
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
    name: 'CreateCompany',
    props: ['data'],
    components: {
        XCircleIcon
    },
    data() {
        return {
            editMember: {},
            formProcess: false,
            errors: {
                email: '',
                // firstname: '',
                // lastname: '',
            },
            preview: null,
            image: null,

        }
    },
    methods: {
        memberDetail() {
            let that = this;
            axios.get('/api/members/' + that.data.memberId)
                .then((res) => {
                    if (res.status == 200) {
                        that.editMember = res.data.data;
                        let avatarurl = that.mediaPath + '/';
                        if (that.editMember.avatar != null) {
                            that.editMember.avatar = avatarurl + that.editMember.avatar;
                        }
                    }

                })
                .catch((error) => {
                    // error.response.status Check status code
                }).finally(() => {
                    //Perform action in always
                });
        },
        updateMemberDetail() {
            let that = this;
            that.formProcess = true;
            const config = {
                headers: {
                    'content-type': 'multipart/form-data'
                }
            }
            let formData = that.convertJsonToFormData(that.editMember);
            formData.append("_method", "PUT")
            if (that.image) {
                formData.append('file', that.image);
            }
            axios.post('/api/update-member/' + that.data.memberId, formData, config)
                .then((res) => {
                    if (res.status == 200) {
                        that.isCloseEdit();
                        let notification = {
                            heading: 'success',
                            subHeading: res.data.message,
                            type: "success",
                        };
                        that.appStore.setNotification(notification);
                    }
                    that.$emit('refreshTable');
                })
                .catch((error) => {
                    that.errors = error.response.data.errors;
                    // error.response.status Check status code

                }).finally(() => {
                    //submit button enabled after form submitted
                    that.formProcess = false;
                });

        },
        isCloseEdit() {
            this.$emit("closeEdit");
        },
        previewImage: function (event) {
            let input = event.target;
            if (input.files) {
                let reader = new FileReader();
                reader.onload = (e) => {
                    this.preview = e.target.result;
                }
                reader.readAsDataURL(input.files[0]);
                this.image = input.files[0];
            }
        },
        deletePreviewImg() {
            this.preview = null;
        },
        deleteImg() {
            let that = this;
            axios.delete("/api/delete-member-image/" + that.data.memberId)
                .then((res) => {
                    if (res.status == 200) {

                    }
                })
                .catch((error) => {
                    // error.response.status Check status code
                    
                })
                .finally(() => {
                    //Perform action in always
                });

        },

    },
    created() {
        this.memberDetail();
    },
    computed: {
        ...mapStores(useAppStore),
    },
}
</script>
