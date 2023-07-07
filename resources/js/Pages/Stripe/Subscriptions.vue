<template>
    <InertiaHead>
        <title>{{ trans("lang.labels.subscription") }} - xme</title>
    </InertiaHead>
    <AuthenticatedLayout>
        <template #header>
            <h3 class="font-semibold text-2xl text-gray-800 leading-tight">
                {{ trans("lang.labels.subscription") }}
            </h3>
        </template>
        <div class="mt-5">
      <div class="mx-auto rounded-lg pt-1 grid grid-cols-1 md:grid-cols-6 gap-6">
        <profile-nav-bar :activeTabName="activeTabName" />
        <div class="w-full col-span-4">
          <!-- personal plan -->
          <div class="bg-sidebar rounded-md p-6 custom-box-shadow">
            <div class="flex justify-between items-center flex-wrap">
              <div class="mb-3 sm2:mb-0">
                <h3 class="font-bold text-gray-300 text-2xl">
                  {{ company.active_plan.is_custom_plan ? company.active_plan.base_plan.name : company.active_plan.name }}
                </h3>
                <p class="font-medium text-sm text-gray-500">
                  <span>$</span
                  >{{
                      company.active_plan.is_custom_plan
                          ? company.active_plan.base_plan
                                .price.toFixed(2)
                          : company.active_plan.price.toFixed(2)
                  }}
                  <span>
                      {{
                          company.active_plan.interval ==
                          1
                              ? "/ mo"
                              : "/ yr"
                      }}
                  </span>
                  <template
                      v-if="
                          company.active_plan.is_custom_plan
                      "
                  >
                      <span>
                          +
                      </span>
                          <span>
                              {{
                                  company.active_plan
                                      .number_of_addons
                              }}
                              {{
                                  trans(
                                      "lang.labels.addons"
                                  )
                              }}
                          </span>
                          <span>
                              $</span
                          >{{
                              (company.active_plan.price -
                              company.active_plan.base_plan
                                  .price).toFixed(2)
                          }}
                          <span>
                              {{
                                  company.active_plan
                                      .interval == 1
                                      ? "/ mo"
                                      : "/ yr"
                              }}
                          </span>
                  </template>
                </p>
              </div>
              <div class="flex items-center flex-wrap gap-3">
                <inertia-link v-if="false && company.active_plan.type != 'free' && planActive"  :href="route('subscription-plan.customize')" class="sm2:mb-0 text-white px-4 py-2 rounded-md text-sm font-medium flex items-center bg-amber-500 hover:bg-amber-600">
                  <new-browser-addon-icon class="mr-2" />
                  {{ trans("lang.labels.browse_addons") }}
                </inertia-link>
                <inertia-link :href="route('pricing')" class="text-white bg-themeChartColorOne hover:bg-themeChartColorTwo px-4 py-2 rounded-md text-sm font-medium flex items-center sm2:ml-4">
                  <change-plan-icon class="mr-2" />
                  {{ trans("lang.labels.change_plan") }}
                </inertia-link>
              </div>
            </div>
          </div>
          <div class="grid grid-cols-4 bg-cardtop rounded-b-md mt-4" v-if="false">
            <button class="text-gray-400 text-sm font-medium border-r border-gray-500 px-6 py-5"> {{ companyAllow.members }} {{ trans("lang.labels.days") }}  <span class="text-grayCust-600">{{ trans("lang.labels.site_lifetime") }}</span></button>
            <button class="text-gray-400 text-sm font-medium border-r border-gray-500 px-6 py-5"> {{ companyAllow.members }} <span class="text-grayCust-600">{{ trans("lang.labels.sites") }}</span>
            </button>
            <button class="text-gray-400 text-sm font-medium border-r border-gray-500 px-6 py-5"> {{ companyAllow.disk_storage }}MB <span class="text-grayCust-600">{{ trans("lang.labels.disk") }}</span>
            </button>
            <button class="text-gray-400 text-sm font-medium px-6 py-5"> {{ companyAllow.members }} <span class="text-grayCust-600">{{ trans("lang.labels.templates") }}</span>
            </button>
          </div>
          <!-- Upgrade to Pro Plan -->
          <div v-if="company.active_plan.type == 'free'" class="bg-sidebar rounded-md p-6 mb-6 custom-box-shadow w-full mt-6">
            <div class="flex justify-between items-start flex-wrap lg:flex-nowrap">
              <div>
                <h3 class="font-medium text-gray-300 text-lg"> {{ trans("lang.labels.upgrade_to_pro_plan") }} </h3>
                <p class="font-medium text-sm mt-2 text-gray-500"> {{ trans("lang.labels.upgrade_free_acc") }} </p>
              </div>
              <div class="flex items-center">
                <inertia-link :href="route('pricing')" class="text-white bg-themeChartColorOne hover:bg-themeChartColorTwo px-4 py-2 rounded-md text-sm font-medium ml-4">
                  {{ trans("lang.labels.upgrade") }}
                </inertia-link>
              </div>
            </div>
          </div>
          <!-- card section -->
          <div class="mb-6 grid grid-cols-1 sm:grid-col-2 md:grid-cols-1 lg:grid-cols-2 2xl:grid-cols-3 gap-8 w-full">
            <div class="subscription-box" >
              <div>
                <div class="bg-sidebar flex custom-box-shadow rounded-t-md w-full h-24 p-6">
                  <div class="bg-grayCust-50 rounded-md p-4">
                    <RenewOnIcon aria-hidden="true" class="text-gray-300" />
                  </div>
                  <div class="ml-4" >
                    <h2 class="text-sm text-gray-300 font-medium">
                      {{ trans('lang.labels.renews_on') }}
                    </h2>
                    <p class="xl:text-xl md:text-base text-sm font-semibold text-gray-500">
                      <span v-if="upcomingInvoiceTable">{{ upcomingInvoiceTable.date }}</span>
                      <span v-if="upcomingInvoiceTable == false">NA</span>
                    </p>
                  </div>
                </div>
                <div class="bg-cardtop rounded-b-md px-4 py-3 w-full h-12 custom-box-shadow">
                  <inertia-link :href="route('invoices.index')" class="text-sm text-white font-medium bg-themeChartColorOne hover:bg-themeChartColorTwo px-4 py-1 rounded-lg">
                    {{ trans('lang.labels.view_receipts') }}
                  </inertia-link>
                </div>
              </div>
            </div>
            <div class="subscription-box">
              <div>
                <div class="bg-sidebar flex custom-box-shadow rounded-t-md w-full h-24 p-6">
                  <div class="bg-grayCust-50 rounded-md p-4">
                    <MonthlyPlanIcon aria-hidden="true" class="text-gray-300" />
                  </div>
                  <div class="ml-4">
                    <h2 v-if=" company.active_plan.interval == 1" class="text-sm text-gray-300 font-medium">
                      {{ trans('lang.labels.monthly_plan_price') }}
                    </h2>
                    <h2 v-else class="text-sm text-gray-300 font-medium">
                      {{ trans('lang.labels.yearly_plan_price') }}
                    </h2>
                    <p class="xl:text-xl md:text-base text-sm font-semibold text-gray-500" v-if="upcomingInvoiceTable">
                      {{upcomingInvoiceTable.total}}
                    </p>
                    <p class="text-xl font-semibold text-gray-500" v-if="upcomingInvoiceTable == false">
                      NA
                    </p>
                  </div>
                </div>
                <div class="bg-cardtop flex justify-between items-center rounded-b-md px-4 py-3 w-full h-12 custom-box-shadow">
                   <div v-tooltip="trans('lang.labels.your_credits_available')" class="text-xs text-secondary-800 font-bold cursor-pointer credits bg-white border border-grayCust-900 rounded-full px-3 py-1.5" v-show="credits != 0">
                    <p>${{credits}}</p>
                  </div>
                </div>
              </div>
            </div>
            <div class="subscription-box">
              <div>
                <div class="bg-sidebar flex custom-box-shadow rounded-t-md w-full h-24 p-6">
                  <div class="bg-grayCust-50 rounded-md p-4">
                    <PrimaryPaymentCardIcon aria-hidden="true" />
                  </div>
                  <div class="ml-4">
                    <h2 class="text-sm text-gray-300 font-medium">
                      {{ trans('lang.labels.primary_payment_card') }}
                    </h2>
                    <p class="xl:text-xl md:text-base text-sm font-semibold text-gray-500">
                      <span v-if="defaultPaymentMethod && defaultPaymentMethod.card_last_four">**** {{defaultPaymentMethod.card_last_four}}</span>
                      <span v-if="defaultPaymentMethod && defaultPaymentMethod.card_last_four === null">NA</span>
                    </p>
                  </div>
                </div>
                <div class="bg-cardtop rounded-b-md px-4 py-3 w-full h-12 custom-box-shadow">
                  <inertia-link :href="route('card.get')" class="text-sm text-white font-medium bg-themeChartColorOne hover:bg-themeChartColorTwo px-4 py-1 rounded-lg">
                    {{ trans('lang.labels.update_card') }}
                  </inertia-link>
                </div>
              </div>
            </div>
          </div>
          <!-- Usages Exceeded -->
          <div v-if="isPlanLimitOver(flatNumeric.filter((feature) => companyAllow[feature.key] && companyCan.hasOwnProperty(feature.key)),companyCan) && company.active_plan.type != 'free'" class="bg-white rounded-md p-6 mb-6 custom-box-shadow common-width">
            <div class="flex justify-between items-start flex-wrap md:flex-nowrap">
              <div class="bg-grayCust-50 rounded-md p-4 mb-4 lg:mb-0">
                <usages-exceeded-icon />
              </div>
              <div class="md:ml-6">
                <h3 class="font-medium text-warning-1300 text-lg"> {{ trans("lang.labels.usages_exceeded") }} </h3>
                <p class="text-sm mt-2 text-grayCust-500"> {{ trans("lang.labels.usages_exceeded_subtext") }} </p>
              </div>
              <div class="flex items-center md:ml-6 md:mt-0 mt-4">
                <inertia-link :href="route('subscription-plan.customize')" class="text-white hover:bg-primary-200 bg-primary-900 px-4 py-2 rounded-md text-sm font-medium" style="min-width: 126px"> Increase Limit </inertia-link>
              </div>
            </div>
          </div>
          <!-- Subscription Details -->
          <div class="w-full bg-sidebar rounded-md p-6 mb-6 custom-box-shadow" v-if="company.active_plan.type != 'free' && couponText">
            <div>
              <p class="font-medium text-sm text-blueCust-600 bg-blueCust-75 flex items-center rounded-md pl-4 py-1">
                <span class="mr-2">
                  <svg width="14" height="15" viewBox="0 0 14 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M8 1.95139L7.50017 2.39255C7.50933 2.40292 7.51881 2.41301 7.5286 2.4228L8 1.95139ZM12.5486 8.5L12.1075 8.00017C12.0971 8.00933 12.087 8.01881 12.0772 8.0286L12.5486 8.5ZM12.6667 6.61806L13.1665 6.1769C13.1573 6.16653 13.1479 6.15644 13.1381 6.14665L12.6667 6.61806ZM6.11806 13.1667L5.64665 13.6381C5.65644 13.6479 5.66653 13.6573 5.6769 13.6665L6.11806 13.1667ZM7.88194 13.1667L8.3231 13.6665C8.33347 13.6573 8.34356 13.6479 8.35335 13.6381L7.88194 13.1667ZM1.45139 8.5L1.9228 8.0286L1.90811 8.01391L1.89255 8.00017L1.45139 8.5ZM3.66667 3.5C3.29848 3.5 3 3.79848 3 4.16667C3 4.53486 3.29848 4.83333 3.66667 4.83333V3.5ZM3.67333 4.83333C4.04152 4.83333 4.34 4.53486 4.34 4.16667C4.34 3.79848 4.04152 3.5 3.67333 3.5V4.83333ZM1.66667 4.16667C1.66667 3.0621 2.5621 2.16667 3.66667 2.16667V0.833333C1.82572 0.833333 0.333333 2.32572 0.333333 4.16667H1.66667ZM1.66667 7.5V4.16667H0.333333V7.5H1.66667ZM3.66667 2.16667H7V0.833333H3.66667V2.16667ZM7 2.16667C7.19902 2.16667 7.37704 2.25304 7.50017 2.39255L8.49983 1.51024C8.13433 1.09613 7.59744 0.833333 7 0.833333V2.16667ZM12.3333 7.5C12.3333 7.69902 12.247 7.87704 12.1075 8.00017L12.9898 8.99983C13.4039 8.63433 13.6667 8.09744 13.6667 7.5H12.3333ZM12.1668 7.05921C12.2709 7.17714 12.3333 7.33047 12.3333 7.5H13.6667C13.6667 6.99323 13.4773 6.52907 13.1665 6.1769L12.1668 7.05921ZM7 12.8333C6.83047 12.8333 6.67714 12.7709 6.55921 12.6668L5.6769 13.6665C6.02907 13.9773 6.49323 14.1667 7 14.1667V12.8333ZM7.44079 12.6668C7.32286 12.7709 7.16953 12.8333 7 12.8333V14.1667C7.50677 14.1667 7.97093 13.9773 8.3231 13.6665L7.44079 12.6668ZM1.89255 8.00017C1.75304 7.87704 1.66667 7.69902 1.66667 7.5H0.333333C0.333333 8.09744 0.596131 8.63433 1.01024 8.99983L1.89255 8.00017ZM7.5286 2.4228L12.1953 7.08946L13.1381 6.14665L8.4714 1.47999L7.5286 2.4228ZM6.58946 12.6953L1.9228 8.0286L0.979987 8.9714L5.64665 13.6381L6.58946 12.6953ZM12.0772 8.0286L7.41054 12.6953L8.35335 13.6381L13.02 8.9714L12.0772 8.0286ZM3.66667 4.83333H3.67333V3.5H3.66667V4.83333Z" fill="#0070F0"/>
                  </svg>
                </span> 
                {{couponText}}
              </p>               
            </div>
          </div>
          <!-- Statistics -->
          <div v-if="false" class="w-full bg-sidebar rounded-md p-6 mb-6 custom-box-shadow">
            <div>
              <h3 class="font-medium text-gray-300 text-lg"> {{ trans("lang.labels.statistics") }} </h3>
              <p class="font-medium text-sm mt-1 text-gray-500"> {{ trans("lang.labels.information_from_personal") }} </p>
            </div>
            <statistics :allow="companyAllow" :used="companyUsed" :can="companyCan" :addons_description="company.active_plan.addons_description" />
          </div>
          <!-- Cancel Subscription -->
          <div class="custom-box-shadow rounded-md mt-5" v-if="company.active_plan.type != 'free' && planActive == true">
            <div class="common-width bg-white rounded-t-md p-6">
              <div>
                <div>
                  <h3 class="text-lg font-medium leading-6 text-gray-900">
                    {{ trans( "lang.labels.cancel_subscription") }}
                  </h3>
                  <p class="mt-2 text-sm text-gray-500">
                    {{ trans( "lang.labels.cancel_subscription_content" )}}
                  </p>
                </div>
              </div>
            </div>
            <div class="bg-grayCust-50 flex justify-end rounded-b-md px-6 py-3 mb-6">
              <inertia-link :href="route('subscription.cancel')" aria-label="Cancel Subscription" class="text-redCust-850 hover:bg-redCust-100 bg-redCust-50 px-4 py-2 rounded-md text-sm font-medium ml-4">
                {{ trans("lang.labels.cancel_subscription") }}
              </inertia-link>
            </div>
          </div>
          <div class="custom-box-shadow rounded-md mt-5" v-else-if="company.active_plan.type != 'free' && planActive == false">
            <div class="common-width bg-white rounded-t-md p-6">
              <div>
                <div>
                  <h3 class="text-lg font-medium leading-6 text-gray-900">
                    {{ trans( "lang.labels.resume_subscription") }}
                  </h3>
                  <p class="mt-2 text-sm text-gray-500">
                    {{ trans( "lang.labels.content_subscription" )}}  {{ trans( "lang.labels.resume_subscription") }}
                  </p>
                </div>
              </div>
            </div>
            <div class="bg-grayCust-50 flex justify-end rounded-b-md px-6 py-3 mb-6">
              <inertia-link :href="route('subscription.resume')" aria-label="Resume Subscription" class="text-redCust-850 hover:bg-redCust-100 bg-redCust-50 px-4 py-2 rounded-md text-sm font-medium ml-4">
                {{ trans("lang.labels.resume_subscription") }}
              </inertia-link>
            </div>
          </div>
        </div>
      </div>
    </div>
    </AuthenticatedLayout>
</template>
<script>

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import ProfileNavBar from "@/Common/ProfileNavBar.vue";
import NewRightSecondaryIcon from "@/ImageComponents/Profile/NewRightSecondaryIcon.vue";
import ChangePlanIcon from "@/ImageComponents/Profile/ChangePlanIcon.vue";
import UsagesExceededIcon from "@/ImageComponents/Profile/UsagesExceededIcon.vue";
import RenewOnIcon from "@/ImageComponents/Profile/RenewOnIcon.vue";
import MonthlyPlanIcon from "@/ImageComponents/Profile/MonthlyPlanIcon.vue";
import PrimaryPaymentCardIcon from "@/ImageComponents/Profile/PrimaryPaymentCardIcon.vue";
import NewCloseRedIcon from "@/ImageComponents/Profile/NewCloseRedIcon.vue";
import NewBrowserAddonIcon from "@/ImageComponents/Profile/NewBrowserAddonIcon.vue";
import { featuresBoolean, featuresNumeric } from "@/features";
import { mapState,mapStores } from 'pinia';
import { useAppStore } from '@/store/index';
import GeneralMixin from "@/Mixins/GeneralMixin";
import Statistics from "@/Components/Statistics/Statistics.vue";
import Information from "@/ImageComponents/CurrentPlan/Information.vue";
export default {
    name: "subscription",
    props: ["company"],
    mixins: [GeneralMixin],
    components: {
        AuthenticatedLayout,
        NewRightSecondaryIcon,
        NewCloseRedIcon,
        Statistics,
        ProfileNavBar,
        ChangePlanIcon,
        UsagesExceededIcon,
        RenewOnIcon,
        MonthlyPlanIcon,
        PrimaryPaymentCardIcon,
        NewBrowserAddonIcon,
        Information
    },
    computed: {
      ...mapStores(useAppStore),
      ...mapState(useAppStore, ["companyCan","companyUsed","companyAllow",]),
      
    },
    methods: {
      getUpcomingInvoice() {
          let that = this;
          axios.get("/api/upcoming-invoice").then((res) => {
              that.upcomingInvoiceTable = res.data.data;
          });
      },
      getDefaultPaymentMethod() {
          let that = this;
          axios.get("/api/deafult-payment-method").then((res) => {
              that.defaultPaymentMethod = res.data.data;
          });
      },
      getSubscriptionDetails() {
          let that = this;
          axios.get("/subscriptions/details").then((res) => {
            if(res.data.data.coupon && res.data.data.coupon.text)
            {
              that.couponText = res.data.data.coupon.text;
            }
            if(res.data.data.credits)
            {
              that.credits = res.data.data.credits;
            }
            that.planActive = res.data.data.planActive;
          });
      },
    },
    mounted() {
        this.appStore.userLogin(this.user);
        this.getUpcomingInvoice();
        this.getDefaultPaymentMethod();
        this.getSubscriptionDetails();
    },
    data() {
        return {
            progress: 30,
            activeTabName: "subscriptions",
            upcomingInvoiceTable: false,
            defaultPaymentMethod:null,
            flatFeatures: featuresBoolean,
            flatNumeric: featuresNumeric,
            couponText: null,
            credits: 0,
            planActive: null,
        };
    },
};
</script>
<style scoped>
.custom-box-shadow {
    box-shadow: 0px 1px 3px rgba(0, 0, 0, 0.1), 0px 1px 2px rgba(0, 0, 0, 0.06);
}
.data-v-tooltip::before {
  top: -10px !important;
}
.custom-width-main{
    width: 900px;
}
@media (min-width: 1200px) {
  .common-width{
    width: 900px;
  }
  
}
@media (max-width: 991px) {
  .subscription-box{
    width: 100%;
    margin-bottom: 15px;
  }
  .custom-width-main{
    width: 100%;
  }
  
}


</style>
