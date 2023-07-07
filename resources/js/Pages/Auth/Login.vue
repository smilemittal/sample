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
            <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
                {{ status }}
            </div>

            <div class="flex flex-col">
                <div class="mb-24">
                    <h3 class="text-gray-400 text-center">"The secret of getting ahead is getting started"</h3>
                    <h4 class="text-gray-400 mt-5 text-center">~ Mark Twain</h4>
                </div>

                <div>
                    <div class="">
                        <p class="text-neutral-400 text-lg mt-3 mb-1">Dashboard Login</p>
                    </div>
                    <span v-if="errors.email_psw" class="mt-2 text-xs text-red-600 theme-error-message">{{
                        errors.email_psw[0] }}</span>
                    <div class="mt-4">
                        <label for="email" class="text-sm text-gray-500">
                            E-Mail
                        </label>
                        <div class="relative mt-1">
                            <input type="text" id="email"
                                class="block px-2.5 pb-3 pt-3 w-full text-sm text-neutral-100 bg-sidebar rounded-lg border-1 border-transparent appearance-none dark:focus:border-amber-500 focus:outline-none focus:ring-0 focus:border-amber-500 peer"
                                placeholder="Enter email" v-model="formLogin.email" />
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
                                placeholder="Enter password" v-model="formLogin.password" />
                        </div>
                        <span v-if="errors.password" class="mt-2 text-xs text-red-600 theme-error-message">{{
                            errors.password[0] }}</span>
                    </div>
                    <div class="flex items-center flex-wrap gap-1 justify-between mt-8 mb-3">
                        <div class="flex items-center">
                            <input id="remeber_login" type="checkbox" value="" v-model="formLogin.remember"
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600
                                                                            dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                            <label for="remeber_login"
                                class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">Remember me</label>
                        </div>
                        <inertia-link :href="route('password.request')"
                            class="text-sm text-neutral-400 hover:text-amber-500 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Forgot your password?
                        </inertia-link>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div class="col-span-2 block mt-4">
                            <button @click="submitLogin()"
                                class="transition-colors duration-200 text-sm bg-btnSubmitBg text-btnSubmitText hover:bg-amber-500 hover:text-white w-full justify-center px-3 py-3 text-center rounded-lg cursor-pointer">
                                Log in
                            </button>
                        </div>
                        <div class="col-span-2 sm:col-span-1 block mt-4">
                            <inertia-link :href="route('company-register')"
                                class="transition-colors duration-200 text-sm block bg-amber-500 text-white hover:bg-amber-600 hover:text-white w-full justify-center px-3 py-3 text-center rounded-lg cursor-pointer">
                                Register
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

export default {
    name: 'Login',
    components: {
        GuestLayout,
    },
    data() {
        return {
            formLogin: {
                email: '',
                password: '',
                remember: '',
            },
            errors: {
                email: '',
                password: '',
                email_psw: ''
            }
        }
    },
    methods: {
        submitLogin() {
            let that = this;
            that.appStore.custom_menu = null;
            axios.post('/api/login', {
                email: this.formLogin.email,
                password: this.formLogin.password
            })
                .then(function (response) {
                    if (response.status == 200) {
                        let notification = {
                            heading: 'success',
                            subHeading: response.data.message,
                            type: "success",
                        };
                        that.appStore.setNotification(notification);
                        that.appStore.initialData();
                        that.$inertia.visit(route(response.data.data[1]));
                    }
                })
                .catch(function (error) {
                    that.errors = error.response.data.errors;
                });
        }
    },
    computed: {
        ...mapStores(useAppStore),
    },
}
</script>
