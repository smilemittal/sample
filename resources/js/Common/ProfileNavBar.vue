<template>
    <div class="col-span-4 md:col-span-2 2xl:col-span-1 flex flex-col overflow-y-auto pb-4 bg-sidebar rounded-lg themePaymentBar">
        <div class="flex flex-grow flex-col h-screen themePaymentBar">
            <nav class="flex-1 space-y-1 pl-5 pr-5 h-screen themePaymentBar" aria-label="Sidebar">
                <div class="mb-3.5 ml-3.5 pt-5">
                    <h5 class="text-xs font-semibold text-neutral-500 uppercase cursor-pointer">My Account</h5>
                </div>
                <template v-for="item in profileSidebar" :key="item.name">
                    <div v-if="!item.children && !item.hide">
                            <a :href="item.link" :class="
                                                            activeTabName == item.tabName
                            ? 'bg-themeChartColorOne text-white'
                            : 'text-neutral-500 bg-cardtop'"
                                class="flex items-center text-sm font-medium cursor-pointer py-2.5 px-2 mt-1 rounded-md">
                                <component :class="activeTabName == item.tabName ? 'text-primary-900' : 'text-grayCust-550'" :is="item.iconName" class="mr-4" aria-hidden="true" />
                                {{ item.title }}
                            </a>
                    </div>
                    <div v-else-if="!item.hide">
                        <div class="mt-3" @click="isPlanBillingEnabled = !isPlanBillingEnabled" :class="[
                            activeTabName == item.tabName
                                ? 'bg-themeChartColorOne text-white'
                                : 'text-neutral-500 bg-cardtop',
                            'group w-full flex items-center px-2 py-2 text-left text-sm font-medium rounded-md focus:outline-none focus:ring-none',
                        ]">
                            <component :class="isPlanBillingEnabled ? 'text-themeChartColorOne' : 'text-neutral-500'" :is="item.iconName"
                                class="mr-4 flex-shrink-0"
                                aria-hidden="true" />
                            <span class="flex-1 cursor-pointer" :class="isPlanBillingEnabled ? 'text-themeChartColorOne' : 'text-neutral-500'">{{ item.title }}</span>
                            <svg :class="[
                                isPlanBillingEnabled
                                    ? 'rotate-0 text-themeChartColorOne'
                                    : 'rotate-180 text-neutral-500',
                            ]" class="duration-150 ease-in-out transform" width="10" height="6" viewBox="0 0 10 6"
                                fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M0.292893 5.70711C0.683416 6.09763 1.31658 6.09763 1.7071 5.70711L4.99999 2.41421L8.29288 5.70711C8.6834 6.09763 9.31657 6.09763 9.70709 5.70711C10.0976 5.31658 10.0976 4.68342 9.70709 4.29289L5.7071 0.292893C5.31657 -0.097631 4.68341 -0.097631 4.29289 0.292893L0.292893 4.29289C-0.0976309 4.68342 -0.0976309 5.31658 0.292893 5.70711Z"
                                    fill="currentColor" />
                            </svg>
                        </div>
                        <div v-if="isPlanBillingEnabled" class="mt-2 grid grid-cols-1 gap-3">
                            <a v-for="subItem in item.children" :key="subItem.id" :href="subItem.link"
                                :class="activeTabName == subItem.tabName
                                ? 'bg-themeChartColorOne text-white'
                                : 'bg-neutral-800'"
                                class="group cursor-pointer flex w-full items-center rounded-md py-2 pl-11 px-2">
                                <span
                                    :class="activeTabName == subItem.tabName ? 'text-white text-sm font-medium' : 'text-sm font-medium text-gray-400 hover:text-themeChartColorOne'">{{
                                    subItem.title
                                    }}</span>
                            </a>
                        </div>
                    </div>
                </template>
            </nav>
        </div>
    </div>
</template>
<script>
import AccountIcon from "@/ImageComponents/Profile/AccountIcon.vue";
import PlanBilling from "@/ImageComponents/Profile/PlanBilling.vue";
import ApiToken from "@/ImageComponents/Profile/ApiToken.vue";
import Notifications from "@/ImageComponents/Profile/Notifications.vue";

import {
    Disclosure,
    DisclosureButton,
    DisclosurePanel
} from "@headlessui/vue";
export default {
    name: "profile-nav-bar",
    props: ["activeTabName"],
    methods: {
        changeTabName(tabName) {
            if (tabName != "dropdown") this.activeTabName = tabName;
        },
    },
    components: {
        AccountIcon,
        PlanBilling,
        ApiToken,
        Notifications,
        Disclosure,
        DisclosureButton,
        DisclosurePanel,
    },
    created() {
        if (this.$page.url === '/card' || this.$page.url === '/subscriptions' || this.$page.url === '/invoices' || this.$page.url === '/new-subscriptions') this.isPlanBillingEnabled = true
    },
    data() {
        return {
            isPlanBillingEnabled: false,
            isBadgeShow: false,
            profileSidebar: [
                {
                    id: 1,
                    iconName: "AccountIcon",
                    title: this.trans("lang.labels.my_account"),
                    tabName: "BusinessProfile",
                    link: route("profile.show"),
                },
                {
                    id: 2,
                    iconName: "PlanBilling",
                    title: this.trans("lang.labels.plan_billing"),
                    tabName: "dropdown",
                    link: "#",
                    children: [
                        {
                            id: 5,
                            title: this.trans("lang.labels.subscription"),
                            tabName: "subscriptions",
                            link: route("subscriptions.index"),
                        },
                        {
                            id: 6,
                            title: this.trans("lang.labels.payment_method"),
                            tabName: "payment-methods",
                            link: route("card.get"),
                        },
                        {
                            id: 7,
                            title: this.trans("lang.labels.payment_history"),
                            tabName: "payment-history",
                            link: route("invoices.index"),
                        }
                    ],
                },
                // {
                //     id: 3,
                //     iconName: "ApiToken",
                //     title: this.trans("lang.labels.api_token"),
                //     tabName: "api-token-manager",
                //     link: route("api-tokens.index"),
                //     hide: !this.$page.props.jetstream.hasApiFeatures
                // },
                // {
                //     id: 4,
                //     iconName: "Notifications",
                //     title: this.trans("lang.labels.notification"),
                //     tabName: "notifications",
                //     link: route("notifications.index"),
                // },
              
            ],
            
        };
    },
};
</script>
<style scoped>
::-webkit-scrollbar {
    display: none;
}

::-webkit-scrollbar {
    width: 3px;
}

/* Track */
::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 8px;
}

/* Handle */
::-webkit-scrollbar-thumb {
    background: #005e54;
    border-radius: 8px;
}
</style>