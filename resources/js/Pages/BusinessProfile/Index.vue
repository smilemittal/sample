<template>
    <div class="profile-wrapper">

        <div class="page-container">
            <div class="flex items-center justify-start flex-wrap">
                <div class="page-header">
                    <h4 class="text-neutral-300 font-bold text-xl sm:text-2xl md:text-4xl">Business Profile</h4>
                </div>
                <button @click="isEdit != isEdit" class="flex items-center bg-sidebar text-gray-400 hover:bg-amber-500 hover:text-white border border-gray-400
                    rounded-lg px-4 py-3 text-center ml-3">
                    <LockClosedIcon class="h-5 w-5 text-white mr-2" aria-hidden="true" />
                    Lock Editing
                </button>
            </div>
        </div>
        <div class="p-0 mt-5">
            <div class="">
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

                                                <input id="dropzone-file" type="file" class="hidden" @change="previewImage"
                                                    :disabled="isEdit" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </label>
                            <template v-if="businessProfile.logo && businessProfile.logo !== '' || preview != null">
                                <div class="flex flex-col items-center justify-center w-full xs:w-28 sm:w-30 h-64 p-5">
                                    <div class="flex flex-col items-center justify-center pt-5 pb-6 w-full xs:w-28 sm:w-30">
                                        <img :src="preview != null ? preview : (mediaPath + '/' + businessProfile.logo)"
                                            alt="logo" class="h-56 object-cover w-full rounded-lg" />
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>

                <div class="grid md:grid-cols-2 sm:grid-cols-1 gap-4 mt-3">
                    <div class="theme-input">
                        <div class="relative">
                            <input type="text" id="company_name"
                                class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-neutral-100 bg-sidebar rounded-lg border-1 border-transparent appearance-none dark:focus:border-amber-500 focus:outline-none focus:ring-0 focus:border-amber-500 peer"
                                placeholder=" " v-model="businessProfile.name" :disabled="isEdit" />
                            <label for="company_name"
                                class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-sidebar px-2 peer-focus:px-2 peer-focus:text-amber-500 peer-focus:dark:text-amber-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 left-1">
                                Company Name
                            </label>
                        </div>
                    </div>
                    <div class="theme-input">
                        <div class="relative">
                            <input type="text" id="abn"
                                class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-neutral-100 bg-sidebar rounded-lg border-1 border-transparent appearance-none dark:focus:border-amber-500 focus:outline-none focus:ring-0 focus:border-amber-500 peer"
                                placeholder=" " v-model="businessProfile.abn" />
                            <label for="abn"
                                class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-sidebar px-2 peer-focus:px-2 peer-focus:text-amber-500 peer-focus:dark:text-amber-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 left-1">
                                ABN
                            </label>
                        </div>
                    </div>
                </div>
                <div class="mt-4 mb-4">
                    <span class="text-lg font-extrabold text-neutral-300">Address</span>
                </div>
                <div class="grid md:grid-cols-2 sm:grid-cols-1 gap-4">
                    <div class="theme-input">
                        <div class="relative">
                            <input type="text" id="street"
                                class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-neutral-100 bg-sidebar rounded-lg border-1 border-transparent appearance-none dark:focus:border-amber-500 focus:outline-none focus:ring-0 focus:border-amber-500 peer"
                                placeholder=" " v-model="businessProfile.street" />
                            <label for="street"
                                class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-sidebar px-2 peer-focus:px-2 peer-focus:text-amber-500 peer-focus:dark:text-amber-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 left-1">
                                Street
                            </label>
                        </div>
                    </div>
                    <div class="theme-input">
                        <div class="relative">
                            <input type="text" id="city"
                                class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-neutral-100 bg-sidebar rounded-lg border-1 border-transparent appearance-none dark:focus:border-amber-500 focus:outline-none focus:ring-0 focus:border-amber-500 peer"
                                placeholder=" " v-model="businessProfile.city" />
                            <label for="city"
                                class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-sidebar px-2 peer-focus:px-2 peer-focus:text-amber-500 peer-focus:dark:text-amber-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 left-1">
                                City
                            </label>
                        </div>
                    </div>
                    <div class="theme-input">
                        <div class="relative">
                            <input type="text" id="state"
                                class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-neutral-100 bg-sidebar rounded-lg border-1 border-transparent appearance-none dark:focus:border-amber-500 focus:outline-none focus:ring-0 focus:border-amber-500 peer"
                                placeholder=" " v-model="businessProfile.state" />
                            <label for="state"
                                class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-sidebar px-2 peer-focus:px-2 peer-focus:text-amber-500 peer-focus:dark:text-amber-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 left-1">
                                State
                            </label>
                        </div>
                    </div>
                    <div class="theme-input">
                        <div class="relative">
                            <input type="text" id="postcode"
                                class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-neutral-100 bg-sidebar rounded-lg border-1 border-transparent appearance-none dark:focus:border-amber-500 focus:outline-none focus:ring-0 focus:border-amber-500 peer"
                                placeholder=" " v-model="businessProfile.postcode" />
                            <label for="postcode"
                                class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-sidebar px-2 peer-focus:px-2 peer-focus:text-amber-500 peer-focus:dark:text-amber-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 left-1">
                                Postcode
                            </label>
                        </div>
                    </div>
                </div>
                <div class="mt-4 mb-4">
                    <span class="text-lg font-extrabold text-neutral-300">Contact</span>
                </div>
                <div class="grid md:grid-cols-2 sm:grid-cols-1 gap-4">
                    <div class="theme-input">
                        <div class="relative">
                            <input type="text" id="website"
                                class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-neutral-100 bg-sidebar rounded-lg border-1 border-transparent appearance-none dark:focus:border-amber-500 focus:outline-none focus:ring-0 focus:border-amber-500 peer"
                                placeholder=" " v-model="businessProfile.website" />
                            <label for="website"
                                class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-sidebar px-2 peer-focus:px-2 peer-focus:text-amber-500 peer-focus:dark:text-amber-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 left-1">
                                Website
                            </label>
                        </div>
                    </div>
                    <div class="theme-input">
                        <div class="relative">
                            <input type="text" id="phone"
                                class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-neutral-100 bg-sidebar rounded-lg border-1 border-transparent appearance-none dark:focus:border-amber-500 focus:outline-none focus:ring-0 focus:border-amber-500 peer"
                                placeholder=" " v-model="businessProfile.phone" />
                            <label for="phone"
                                class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-sidebar px-2 peer-focus:px-2 peer-focus:text-amber-500 peer-focus:dark:text-amber-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 left-1">
                                Phone
                            </label>
                        </div>
                    </div>
                    <div class="theme-input">
                        <div class="relative">
                            <input type="text" id="email"
                                class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-neutral-100 bg-sidebar rounded-lg border-1 border-transparent appearance-none dark:focus:border-amber-500 focus:outline-none focus:ring-0 focus:border-amber-500 peer"
                                placeholder=" " v-model="businessProfile.email" />
                            <label for="email"
                                class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-sidebar px-2 peer-focus:px-2 peer-focus:text-amber-500 peer-focus:dark:text-amber-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 left-1">
                                Email
                            </label>
                        </div>
                    </div>
                    <div class="theme-input">
                        <div class="relative">
                            <input type="text" id="years"
                                class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-neutral-100 bg-sidebar rounded-lg border-1 border-transparent appearance-none dark:focus:border-amber-500 focus:outline-none focus:ring-0 focus:border-amber-500 peer"
                                placeholder=" " v-model="businessProfile.years_trading" />
                            <label for="years"
                                class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-sidebar px-2 peer-focus:px-2 peer-focus:text-amber-500 peer-focus:dark:text-amber-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 left-1">
                                Years Trading
                            </label>
                        </div>
                    </div>
                </div>
                <div class="flex items-center justify-end mt-4 mb-6">
                    <button class="text-sm bg-btnCancelBg text-btnCancelText px-6 py-2 rounded-lg">Cancel</button>
                    <button @click="update()"
                        class="text-sm bg-btnSubmitBg text-btnSubmitText px-6 py-2 rounded-lg ml-3">Save</button>
                </div>

                <hr>

                <logout-other-browser-sessions-form :sessions="sessions" class="mt-5" />
            </div>
        </div>
        <!-- <two-factor-authentication-form class="mt-5" :user="profile" /> -->
    </div>
</template>
<script>
//import TwoFactorAuthenticationForm from "@/Pages/Profile/Partials/TwoFactorAuthenticationForm.vue";
import LogoutOtherBrowserSessionsForm from "@/Pages/Profile/Partials/LogoutOtherBrowserSessionsForm.vue";
import {
    LockClosedIcon, LockOpenIcon
} from '@heroicons/vue/24/solid'
export default {
    name: 'BusinessProfile',
    components: {
        LockClosedIcon,
        LockOpenIcon,
        //TwoFactorAuthenticationForm,
        LogoutOtherBrowserSessionsForm
    },
    props: ['id', "sessions"],
    data() {
        return {
            businessProfile: {

            },
            errors: {
                name: '',
                phone_nr: '',
                file: '',
                status: '',
                email: '',
                abn: '',
                street: '',
                city: '',
                state: '',
                postcode: '',
                website: '',
                years_trading: ''

            },
            preview: null,
            image: null,

            isEdit: false,
        };
    },
    methods: {
        update() {
            const config = {
                headers: {
                    'content-type': 'multipart/form-data'
                }
            }
            let formData = new FormData();
            formData.append('name', this.businessProfile.name);
            formData.append('abn', this.businessProfile.abn);
            formData.append('street', this.businessProfile.street);
            formData.append('city', this.businessProfile.city);
            formData.append('state', this.businessProfile.state);
            formData.append('state', this.businessProfile.state);
            formData.append('postcode', this.businessProfile.postcode);
            formData.append('website', this.businessProfile.website);
            formData.append('phone_nr', this.businessProfile.phone_nr);
            formData.append('email', this.businessProfile.email);
            formData.append('years_trading', this.businessProfile.years_trading);
            formData.append("_method", "PUT")
            if (this.image) {
                formData.append('file', this.image);
            }
            axios.post('/api/companies/' + this.id, formData, config)
                .then((res) => {
                    if (res.data.status == true) {
                        let notification = {
                            heading: 'success',
                            subHeading: res.data.message,
                            type: "success",
                        };
                        this.appStore.setNotification(notification);;
                        this.$inertia.visit(route('xme.overview'));
                    }
                })
                .catch((error) => {
                    this.errors = error.response.data.errors;
                    // error.response.status Check status code
                }).finally(() => {
                    //Perform action in always
                });
        },
        bussinessProfile() {
            axios
                .get("/api/company-profile")
                .then((res) => {
                    if (res.status == 200) {
                        this.businessProfile = res.data.data;
                    }
                })
                .catch((error) => {
                    // error.response.status Check status code
                })
                .finally(() => {
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
    },
    created: function () {
        this.bussinessProfile();
    },
}
</script>
