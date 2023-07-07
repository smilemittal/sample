<template>
    <div class="grid lg:grid-cols-3 md:grid-cols-1 sm:grid-cols-1">
        <div class="lg:col-span-2 md:col-span-1 sm:col-span-1 sm:hidden md:block">
            <div class="relative w-full h-full themeLogoOverlay">
                <div class="relative w-full h-full bg-slate-50 multiplyMelogin">
                    <div class="absolute bottom-8 left-6 z-40 themeLoginLogo">
                        <img src="./../../../img/logo.png" alt="logo">
                        <span class="text-white">Imagine what you could do if you multiply you</span>
                    </div>
                </div>
            </div>
        </div>
        <GuestLayout>
            <div class="flex flex-col overflow-y-auto">
                <div class="mb-10">
                    <h3 class="text-gray-400 text-center">"The secret of getting ahead is getting started"</h3>
                    <h4 class="text-gray-400 mt-5 text-center">~ Mark Twain</h4>
                </div>
                <div class="">
                    <p class="text-neutral-400 text-lg mb-1">Register</p>
                </div>

                <div class="themeRegisterForm">
                    <div class="mt-4">
                        <label for="email" class="text-sm text-gray-500">
                            Company Name
                        </label>
                        <div class="relative mt-1">
                            <input type="text" id="company_name"
                                class="block px-2.5 pb-3 pt-3 w-full text-sm text-neutral-100 bg-sidebar rounded-lg border-1 border-transparent appearance-none dark:focus:border-amber-500 focus:outline-none focus:ring-0 focus:border-amber-500 peer"
                                placeholder="Enter Company name" v-model="form.company_name" />
                        </div>
                        <span v-if="errors.company_name" class="mt-2 text-xs text-red-600 theme-error-message">{{
                            errors.company_name[0] }}</span>
                    </div>
                    <div class="mt-2">
                        <label for="email" class="text-sm text-gray-500">
                            abn
                        </label>
                        <div class="relative mt-1">
                            <input type="text" id="abn"
                                class="block px-2.5 pb-3 pt-3 w-full text-sm text-neutral-100 bg-sidebar rounded-lg border-1 border-transparent appearance-none dark:focus:border-amber-500 focus:outline-none focus:ring-0 focus:border-amber-500 peer"
                                placeholder="Enter abn number" v-model="form.abn" />
                        </div>
                        <span v-if="errors.abn" class="mt-2 text-xs text-red-600 theme-error-message">{{
                            errors.abn[0] }}</span>
                    </div>

                    <div class="mt-2">
                        <label for="email" class="text-sm text-gray-500">
                            First name
                        </label>
                        <div class="relative mt-1">
                            <input type="text" id="first_name"
                                class="block px-2.5 pb-3 pt-3 w-full text-sm text-neutral-100 bg-sidebar rounded-lg border-1 border-transparent appearance-none dark:focus:border-amber-500 focus:outline-none focus:ring-0 focus:border-amber-500 peer"
                                placeholder="Enter first name" v-model="form.first_name" />
                        </div>
                        <span v-if="errors.first_name" class="mt-2 text-xs text-red-600 theme-error-message">{{
                            errors.first_name[0] }}</span>
                    </div>

                    <div class="mt-2">
                        <label for="email" class="text-sm text-gray-500">
                            Last Name
                        </label>
                        <div class="relative mt-1">
                            <input type="text" id="last_name"
                                class="block px-2.5 pb-3 pt-3 w-full text-sm text-neutral-100 bg-sidebar rounded-lg border-1 border-transparent appearance-none dark:focus:border-amber-500 focus:outline-none focus:ring-0 focus:border-amber-500 peer"
                                placeholder="Enter last name" v-model="form.last_name" />
                        </div>
                        <span v-if="errors.last_name" class="mt-2 text-xs text-red-600 theme-error-message">{{
                            errors.last_name[0] }}</span>
                    </div>

                    <div class="mt-2">
                        <label for="email" class="text-sm text-gray-500">
                            Email
                        </label>
                        <div class="relative mt-1">
                            <input type="email" id="email"
                                class="block px-2.5 pb-3 pt-3 w-full text-sm text-neutral-100 bg-sidebar rounded-lg border-1 border-transparent appearance-none dark:focus:border-amber-500 focus:outline-none focus:ring-0 focus:border-amber-500 peer"
                                placeholder="Enter email" v-model="form.email" />
                        </div>
                        <span v-if="errors.email" class="mt-2 text-xs text-red-600 theme-error-message">{{
                            errors.email[0] }}</span>
                    </div>

                    <div class="mt-2">
                        <label for="email" class="text-sm text-gray-500">
                            Password
                        </label>
                        <div class="relative mt-1">
                            <input type="password" id="password"
                                class="block px-2.5 pb-3 pt-3 w-full text-sm text-neutral-100 bg-sidebar rounded-lg border-1 border-transparent appearance-none dark:focus:border-amber-500 focus:outline-none focus:ring-0 focus:border-amber-500 peer"
                                placeholder="Enter password" v-model="form.password" />
                        </div>
                        <span v-if="errors.password" class="mt-2 text-xs text-red-600 theme-error-message">{{
                            errors.password[0] }}</span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div class="col-span-2 block mt-4">
                            <button @click="createForm()"
                                class="transition-colors duration-200 bg-amber-500 text-white hover:bg-amber-500 hover:text-white w-full justify-center px-3 py-3 text-center rounded-lg cursor-pointer">
                                Register
                            </button>
                        </div>
                        <div class="col-span-2 sm:col-span-1 block mt-4">
                            <inertia-link :href="route('login')"
                                class="transition-colors duration-200 w-full block bg-btnSubmitBg text-btnSubmitText hover:bg-amber-500 hover:text-white w-full justify-center px-3 py-3 text-center rounded-lg cursor-pointer">
                                Login
                            </inertia-link>
                        </div>
                    </div>
                </div>
            </div>
        </GuestLayout>
    </div>
</template>

<script>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import axios from 'axios';
import { mapStores } from 'pinia'
import { useAppStore } from "@/store";
import BetaProgram from "@/Pages/Profile/Partials/BetaProgram.vue";

export default {
    name: 'Company Register',
    components: {
        GuestLayout,
        BetaProgram
    },
    data() {
        return {
            form: {},

            errors: {
                company_name: "",
                abn: "",
                first_name: '',
                last_name: '',
                password: '',
                email: ''

            },
            processing: false
        }
    },
    methods: {
        createForm() {
            this.processing = true;
            let that = this;
            axios
                .post("/api/company-register", that.form)
                .then((res) => {
                    console.log(res)
                    if (res.status == 200) {
                        that.form = {};
                        that.$inertia.visit(route("xme.overview"));
                        let notification = {
                            heading: "success",
                            subHeading: res.data.message,
                            type: "success",
                        };
                        that.appStore.setNotification(notification);
                    }
                })
                .catch((error) => {
                    that.errors = error.response.data.errors;
                    // error.response.status Check status code
                })
                .finally(() => {
                    that.processing = false;
                    //Perform action in always
                });
        },
    },
    computed: {
        ...mapStores(useAppStore),
    },
}
</script>
