<template>
    <div>
        <InertiaHead title="Member Profile" />
        <AuthenticatedLayout>
            <div class="page-container">
                <div class="flex items-center justify-start flex-wrap">
                    <div class="page-header">
                        <h4 class="text-neutral-300 font-bold text-xl sm:text-2xl md:text-4xl">Member Profile</h4>
                    </div>
                    <button v-if="lockEdit == 0" @click="lockEdit = 1"
                        class="flex items-center bg-sidebar text-gray-400 hover:bg-amber-500 hover:text-white rounded-lg px-4 py-2 text-center border border-gray-400 rounded-lg ml-3">
                        <LockClosedIcon class="h-5 w-5 text-white mr-2" aria-hidden="true" />
                        Unlock Editing
                    </button>
                    <button v-if="lockEdit == 1" @click="lockEdit = 0"
                        class="flex items-center bg-sidebar text-gray-400 hover:bg-amber-500 hover:text-white rounded-lg px-4 py-2 text-center border border-gray-400 rounded-lg ml-3">
                        <LockClosedIcon class="h-5 w-5 text-white mr-2" aria-hidden="true" />
                        Lock
                        Editing
                    </button>
                </div>
            </div>
            <div class="p-0 mt-5 w-full md:w-full lg:w-3/4">
                <div class="mb-3">
                    <div class="w-full">
                        <div
                            class="theme-upload-img block sm:flex md:flex  items-center justify-between border-2 border-gray-500 border-dashed rounded-lg cursor-pointer bg-sidebar">
                            <label for="dropzone-file" class="w-full h-64">
                                <div class="flex flex-col h-full items-center justify-center pt-5 pb-6">
                                    <div>
                                        <div>
                                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                                <svg aria-hidden="true" class="w-10 h-10 mb-3 text-gray-400" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12">
                                                    </path>
                                                </svg>
                                                <p class="mb-2 text-sm text-gray-500 dark:text-gray-400">
                                                    <span class="font-semibold">Click to upload</span>
                                                    or drag and drop
                                                </p>
                                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                                    SVG, PNG, JPG or GIF (MAX. 800x400px)
                                                </p>

                                                <input :disabled="lockEdit == 0" id="dropzone-file" type="file"
                                                    class="hidden" @change="previewImage" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </label>
                            <template v-if="profile.avatar != '' || preview != null">
                                <div class="flex flex-col items-center justify-center w-full xs:w-28 sm:w-30 h-64 p-5">
                                    <div class="flex flex-col items-center justify-center pt-5 pb-6 w-full xs:w-28 sm:w-30">
                                        <img :src="preview != null ? preview : (mediaPath + '/' + profile.avatar)"
                                            alt="logo" class="h-56 object-cover w-full rounded-lg" />
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>

                <div class="grid md:grid-cols-2 sm:grid-cols-1 gap-4">
                    <div class="relative">
                        <label for="first_name" class="text-gray-400 text-sm">
                            First Name
                        </label>
                        <input :disabled="lockEdit == 0" type="text" id="first_name"
                            class="mt-2 block px-2.5 pb-2.5 pt-4 w-full text-sm text-neutral-100 bg-sidebar rounded-lg border-1 border-transparent appearance-none dark:focus:border-amber-500 focus:outline-none focus:ring-0 focus:border-amber-500 peer"
                            placeholder=" " v-model="profile.firstname" />
                    </div>
                    <div class="relative">
                        <label for="last_name" class="text-gray-400 text-sm">
                            Last Name
                        </label>
                        <input :disabled="lockEdit == 0" type="text" id="last_name"
                            class="mt-2 block px-2.5 pb-3 pt-3 w-full text-sm text-neutral-100 bg-sidebar rounded-lg border-1 border-transparent appearance-none dark:focus:border-amber-500 focus:outline-none focus:ring-0 focus:border-amber-500 peer"
                            placeholder=" " v-model="profile.lastname" />
                    </div>
                    <div class="relative">
                        <label for="email" class="text-gray-400 text-sm">
                            Email
                        </label>
                        <input :disabled="lockEdit == 0" type="text" id="email"
                            class="mt-2 block px-2.5 pb-3 pt-3 w-full text-sm text-neutral-100 bg-sidebar rounded-lg border-1 border-transparent appearance-none dark:focus:border-amber-500 focus:outline-none focus:ring-0 focus:border-amber-500 peer"
                            placeholder=" " v-model="profile.email" />
                    </div>
                    <div class="relative">
                        <label for="phone_number" class="text-gray-400 text-sm">
                            Phone Number
                        </label>
                        <input :disabled="lockEdit == 0" type="text" id="phone_number"
                            class="mt-2 block px-2.5 pb-3 pt-3 w-full text-sm text-neutral-100 bg-sidebar rounded-lg border-1 border-transparent appearance-none dark:focus:border-amber-500 focus:outline-none focus:ring-0 focus:border-amber-500 peer"
                            placeholder=" " v-model="profile.phone_nr" />
                    </div>
                </div>
                <div class="flex items-center justify-end mt-4 mb-6">
                    <button class="text-sm bg-btnCancelBg text-btnCancelText px-6 py-2 rounded-lg">Cancel</button>
                    <button class="text-sm bg-btnSubmitBg text-btnSubmitText px-6 py-2 rounded-lg ml-3"
                        @click="update()">Save</button>
                </div>
            </div>
            <hr class="w-full md:w-full lg:w-3/4 border-0 bg-gray-400 h-px mt-8">

            <update-password-form class="mt-5" />

            <hr class="w-full md:w-full lg:w-3/4 border-0 bg-gray-400 h-px my-8">

            <beta-program class="mt-5" />

            <!--<two-factor-authentication-form class="mt-5" :user="profile" />

         <logout-other-browser-sessions-form :sessions="sessions" class="mt-5" /> -->

        </AuthenticatedLayout>
    </div>
</template>
<script>

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { mapStores } from "pinia";
import { useAppStore } from "@/store";
import {
    LockClosedIcon, LockOpenIcon
} from '@heroicons/vue/24/solid'
import BetaProgram from "@/Pages/Profile/Partials/BetaProgram.vue";
import UpdatePasswordForm from "@/Pages/Profile/Partials/UpdatePasswordForm.vue";
//import TwoFactorAuthenticationForm from "@/Pages/Profile/Partials/TwoFactorAuthenticationForm.vue";
//import LogoutOtherBrowserSessionsForm from "@/Pages/Profile/Partials/LogoutOtherBrowserSessionsForm.vue";

export default {
    name: 'Profile',
    components: {

        AuthenticatedLayout,
        LockClosedIcon,
        LockOpenIcon, BetaProgram, UpdatePasswordForm,
        //TwoFactorAuthenticationForm, //LogoutOtherBrowserSessionsForm
    },
    props: ["sessions"],
    data() {
        return {
            profile: {
            },
            image: null,
            preview: null,
            lockEdit: 0,
        };
    },
    methods: {
        userProfile() {
            axios
                .get("/api/profile")
                .then((res) => {
                    if (res.status == 200) {
                        this.profile = res.data.data;
                    }
                })
                .catch((error) => {
                    // error.response.status Check status code
                })
                .finally(() => {
                    //Perform action in always
                });
        },
        update() {
            const config = {
                headers: {
                    'content-type': 'multipart/form-data'
                }
            }
            let that = this;
            let formData = this.convertJsonToFormData(that.profile);
            formData.append("_method", "PUT")
            if (that.image) {
                formData.append('file', this.image);
            }
            axios.post('/api/update-member/' + that.profile.id, formData, config)
                .then((res) => {
                    if (res.data.status) {
                        that.lockEdit = 0;
                        let notification = {
                            heading: 'success',
                            subHeading: res.data.message,
                            type: "success",
                        };
                        that.appStore.setNotification(notification);
                    }

                })
                .catch((error) => {
                    that.errors = error.response.data.errors;
                    // error.response.status Check status code
                }).finally(() => {
                    //Perform action in always
                });
        },
        previewImage: function (event) {
            let input = event.target;
            if (input.files) {
                let reader = new FileReader();
                reader.onload = (e) => {
                    this.preview = e.target.result;
                };
                reader.readAsDataURL(input.files[0]);
                this.image = input.files[0];
            }
        },
        deleteImg() {
            this.preview = null;
        },
    },
    created: function () {
        this.userProfile(1);
    },
    computed: {
        ...mapStores(useAppStore),
    },
}
</script>
