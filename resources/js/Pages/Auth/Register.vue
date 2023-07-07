<template>
    <div class="grid lg:grid-cols-3 md:grid-cols-1 sm:grid-cols-1">
        <div class="lg:col-span-2 md:col-span-1 sm:col-span-1 sm:hidden md:block">
            <div class="w-full h-full">
                <div class="w-full h-full bg-slate-50 multiplyMelogin">
                </div>
            </div>
        </div>
        <GuestLayout>
            <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
                {{ status }}
            </div>
            <div class="flex flex-col" v-if="tokenStatus">
                <div class="mb-24">
                    <h3 class="text-gray-400 text-center">"You have been invited to Sign up as a member of"</h3>
                    <h4 class="text-gray-400 mt-5 text-center">{{ companyName }}</h4>
                </div>
                <span v-if="errors.overlimit"  class="mt-2 text-sm text-red-600">
                {{ errors.overlimit[0] }}</span>

                <div>
                    <div class="">
                        <h5 class="text-gray-200 text-xl font-medium mb-1">Complete your signup</h5>
                    </div>

                        <div class="theme-input mt-6">
                            <div class="relative">
                                <input type="text" id="email" v-model="form.email"  readonly
                                    class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-neutral-100 bg-sidebar rounded-lg border-1 border-transparent appearance-none dark:focus:border-amber-500 focus:outline-none focus:ring-0 focus:border-amber-500 peer"
                                    placeholder=" " />
                                <label for="email"
                                    class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-sidebar px-2 peer-focus:px-2 peer-focus:text-amber-500 peer-focus:dark:text-amber-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 left-1">
                                    E-Mail
                                </label>
                            </div>
                            <span v-if="errors.email" class="mt-2 text-xs text-red-600">{{
                                errors.email[0]
                            }}</span>
                        </div>
                        <div class="theme-input mt-4">
                            <div class="relative">
                                <input type="text" id="firstname"
                                    class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-neutral-100 bg-sidebar rounded-lg border-1 border-transparent appearance-none dark:focus:border-amber-500 focus:outline-none focus:ring-0 focus:border-amber-500 peer"
                                    placeholder=" " v-model="form.firstname" />
                                <label for="firstname"
                                    class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-sidebar px-2 peer-focus:px-2 peer-focus:text-amber-500 peer-focus:dark:text-amber-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 left-1">
                                    First Name
                                </label>
                            </div>
                            <span v-if="errors.firstname"  class="mt-2 text-xs text-red-600">{{
                                errors.firstname[0]
                            }}</span>
                        </div>
                        <div class="theme-input mt-4">
                            <div class="relative">
                                <input type="text" id="lastname"
                                    class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-neutral-100 bg-sidebar rounded-lg border-1 border-transparent appearance-none dark:focus:border-amber-500 focus:outline-none focus:ring-0 focus:border-amber-500 peer"
                                    placeholder=" " v-model="form.lastname" />
                                <label for="lastname"
                                    class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-sidebar px-2 peer-focus:px-2 peer-focus:text-amber-500 peer-focus:dark:text-amber-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 left-1">
                                    Last Name
                                </label>
                            </div>
                            <span v-if="errors.lastname" class="mt-2 text-xs text-red-600">{{
                                errors.lastname[0]
                            }}</span>
                        </div>
                        <div class="theme-input mt-4">
                            <div class="relative">
                                <input type="password" id="password"
                                    class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-neutral-100 bg-sidebar rounded-lg border-1 border-transparent appearance-none dark:focus:border-amber-500 focus:outline-none focus:ring-0 focus:border-amber-500 peer"
                                    placeholder=" " v-model="form.password" />
                                <label for="password"
                                    class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-sidebar px-2 peer-focus:px-2 peer-focus:text-amber-500 peer-focus:dark:text-amber-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 left-1">
                                    Password
                                </label>
                            </div>
                            <span v-if="errors.password" class="mt-2 text-xs text-red-600">{{
                                errors.password[0]
                            }}</span>
                        </div>
                        <div class="theme-input mt-4">
                            <div class="relative">
                                <input type="text" id="phonenumber"
                                    class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-neutral-100 bg-sidebar rounded-lg border-1 border-transparent appearance-none dark:focus:border-amber-500 focus:outline-none focus:ring-0 focus:border-amber-500 peer"
                                    placeholder=" " v-model="form.phone_nr" />
                                <label for="phonenumber"
                                    class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-sidebar px-2 peer-focus:px-2 peer-focus:text-amber-500 peer-focus:dark:text-amber-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 left-1">
                                    Phone Number
                                </label>
                            </div>
                            <span v-if="errors.phone_nr" class="mt-2 text-xs text-red-600">{{
                                errors.phone_nr[0]
                            }}</span>
                        </div>

                        <div class="block mt-4">
                            <button @click="submitLogin()"
                                class="bg-btnSubmitBg text-btnSubmitText hover:bg-amber-500 hover:text-white w-full justify-center px-3 py-3 text-center rounded-lg cursor-pointer">
                                Register
                            </button>
                        </div>
                </div>
            </div>
            <div class="login-quote" v-else>
                <span class="bquote text-gray-200">Contact your System Administrator for Access to your business membership</span><br>
                <span class="register-caption font-normal text-gray-500 mt-3 block">Looking for business sign up instead?<inertia-link :href="route('company-register')" class="text-themeChartColorOne">
                    Register here</inertia-link></span>
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
        GuestLayout
    },
    props:['token'],
    data() {
        return {
            form: {
            },
            errors: {
                email: '',
                password: '',
                firstname: '',
                lastname: '',
                phone_nr: '',
                company_id: '',
                overlimit : false
            },
            member:{},
            companyName: '',
            tokenStatus: '',
            debugMessage: ''
        }
    },
    methods: {
        submitLogin() {
            let that = this;
            axios.post('/api/create-member', that.form)
                .then(function (response) {
                    console.log(response);
                    if (response.status == 200) {
                        let notification = {
                        heading: 'success',
                        subHeading: response.data.message,
                        type: "success",
                        };
                        that.appStore.setNotification(notification);
                        that.$inertia.visit(route('xme.overview'));
                    }
                })
                .catch(function (error) {
                    console.log(error.response.data.errors);
                    that.errors = error.response.data.errors;
                });
        },
        inviteMemberInfo(){
            let that = this;
            axios
                .get("/api/validate-token/" + that.token)
                .then((res) => {
                    if(res.status == 200){
                        that.member = res.data.data[2];
                        that.tokenStatus = res.data.data[1];
                        that.debugMessage = res.data.data[0];
                        if(that.member){
                            that.form.email = that .member.email;
                            that.form.company_id = that.member.company.id;
                            that.companyName = that.member.company.name;
                        }

                    }
                })
                .catch((error) => {
                    that.errors = error.response.data.errors;

                })
                .finally(() => {
                    //Perform action in always
                });
        }

    },
    created(){
        this.inviteMemberInfo();
    },
    computed: {
        ...mapStores(useAppStore),
    },
}
</script>
