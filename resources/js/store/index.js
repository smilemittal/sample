import { defineStore } from 'pinia'
import {useNotification} from "@kyvg/vue3-notification";
const {notify} = useNotification();

// Create a new store instance.
export const useAppStore = defineStore('app', {
    state: () => ({
            user: {},
            configs: false,
            user_authorization: {},
            notification: {},
            warning: {},
            modal: {},
            stripeLoaded: false,
            refresh_table: false,
            callback: {},
            new_version: false,
            notifications: [],
            ui: {
                popup_inside_modal: false,
                edit_custom_plan: false,
            },
            selectedPlan:null,
            cardIsActivated:false,
            coupon_applied: false,
            coupon: null,
            coupon_banner_text: false,
            upgrade_warning: {},
            feature_available_from: false,
        }),
    actions: {
        closewindow(value) {
            this.callback = value;
        },
        userLogin(data) {
            this.user = data ;
        },
        userLogout() {
            this.user = {};
            this.user_authorization = {};
        },
        async getComapnyAuth() {
            let response = await axios.get("/api/user-authorization");
            let responseData = response.data.data;
            this.user_authorization = responseData;
        },
        subscribeAppVersionReleaseEvent() {
            window.Echo.channel(`app.version.release`)
            .listen('AppVersionRelease', (e) => {
                this.new_version = e.version;
            });
        },
        fetchNotifications() {
            if (this.user) {
                axios
                .get("/api/notifications").then((res) => {
                    this.notifications = res.data.data;
                })
                .catch((error) => {
                })
                .finally(() => {
                    window.Echo.private(`App.Models.User.${parseInt(this.user.id)}`).notification(
                        notification => {
                            this.notifications.concat([notification]);
                        }
                    );
                });
              
            }
        },

        async getDefaultCoupon() {
            let response = await axios.get("/api/default-coupon");
            let coupon = response.data.data.coupon;
            let coupon_banner_text = response.data.data.coupon_banner_text;
            if (
                this.user !== null &&
                this.user.hasOwnProperty("active_plan") &&
                this.user.active_plan.type != "free" &&
                response.data.data.coupon_only_for_free
            ) {
                coupon = {
                    name: null,
                    coupon_code: null,
                    discount_value: null,
                };
                coupon_banner_text = null;
            }
            if (coupon.coupon_code) {
                this.coupon = coupon;
                this.coupon_applied = true;
            }
            if (coupon_banner_text) {
                this.coupon_banner_text = coupon_banner_text;
            }
            this.coupon = coupon;
            if (coupon.coupon_code) {
                this.coupon_applied = true;
            }
            return true
        },
        async initialData() {            
            if(this.user !== null && Object.keys(this.user).length > 0) {
                let response = await axios.get("/api/initial-data");
                let responseData = response.data.data;
                let coupon = responseData.default_coupon.coupon;
                let coupon_banner_text = responseData.default_coupon.coupon_banner_text;
                if (
                    this.user !== null && this.user.company != null &&
                    this.user.company.hasOwnProperty("active_plan") &&
                    this.user.company.active_plan.type != "free" &&
                    responseData.default_coupon.coupon_only_for_free
                ) {
                    coupon = {
                        name: null,
                        coupon_code: null,
                        discount_value: null,
                    };
                    coupon_banner_text = null;
                }
                if (coupon.coupon_code) {
                    this.coupon = coupon;
                    this.coupon_applied = true;
                }
                if (coupon_banner_text) {
                    this.coupon_banner_text = coupon_banner_text;
                }
                this.coupon = coupon;
                if (coupon.coupon_code) {
                    this.coupon_applied = true;
                }

                if (responseData.feature_availability) {
                    this.feature_available_from = responseData.feature_availability
                }
            }
        },
        async getFeatureAvailability() {
            let response = await axios.get("/api/get-feature-availability");
            let responseData = response.data.data;
            this.feature_available_from = responseData
        },
        setNotification(notification) {
            if (notification.hasOwnProperty('heading') && notification.hasOwnProperty('subHeading') ) { //&& notification.subHeading.length > 0
                notify({
                    title: notification.heading,
                    text: notification.subHeading,
                    type: notification.type
                });
                this.notification = notification;
            }
        },
        changeCouponCode(coupon_code) {
            this.coupon.coupon_code = coupon_code;
        },
        toggleCouponApplied() {
            this.coupon_applied = !this.coupon_applied;
        },
        applyCoupon(coupon) {
            this.coupon = coupon;
            this.coupon_applied = true;
        },
        removeCoupon() {
            this.coupon = {
                coupon_code: null,
                name: null,
                percentage: null,
                text: null,
            };
            this.coupon_applied = false;
        },
        setWarning(warning) {
            this.warning = warning;
        },
      
    },
    getters: {
        unreadNotificationCount(state) {
            return state.notifications.filter(notification => notification.read_at === null).length;
        },
        recentNotifications(state) {
            return state.notifications.slice(0, 5)
        },
        getCallback(state) {
            return state.callback;
        },
        userRole: (state) => {
            return state.user_authorization.role;
        },
        userCompany: (state) => {
            return state.user_authorization.userCompany;
        },
        companyCan: (state) => {
            return state.user_authorization.companyCan ?? {};
        },
        companyUsed: (state) => {
            return state.user_authorization.companyUsed ?? {};
        },
        companyAllow: (state) => {
            return state.user_authorization.companyAllow ?? {};
        },
        userActivePlan: (state) => {
            return state.user.active_plan;
        },      
        getUser: (state) => {
            return state.user ?? state.user_authorization.user;
        },
        getcallback: (state) => {
            return state;
        },
        getRefreshTable: (state) => {
            return state.refresh_table;
        },
        userBaseActivePlan: (state) => {
            return state.user.active_plan.is_custom_plan ? state.user.active_plan.base_plan : state.user.active_plan
        },
        getNewVersion: (state) => {
            return state.new_version;
        },
        appliedCoupon: (state) => {
            return state.coupon;
        },
        shouldCouponApply: (state) => {
            return state.coupon_applied;
        },
    },
    share: {
        // An array of fields that the plugin will ignore.
        omit: [],
        // Override global config for this store.
        enable: true,
        initialize: true,
    },
    strict: process.env.NODE_ENV !== "production",
});
