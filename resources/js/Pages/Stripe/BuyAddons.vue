<template>
    <div>
        <InertiaHead>
            <title>{{ trans('lang.labels.plan_addons') }} - xme</title>
        </InertiaHead>
        <AuthenticatedLayout>
            <template #header>
                <h2 class="text-gray-900 text-2xl leading-9 font-semibold">
                  {{ trans('lang.labels.addons_customizer') }}
                </h2>
            </template>
          
            <div class="py-8" v-if="activePlan.description">
                <div class="max-w-7xl mx-auto sm:px-6 rounded-lg lg:px-8 pt-1 bg-white flex justify-between items-start flex-wrap lg:flex-nowrap">
                    <div>
                        <div id="scroll" style="height: 840px" class="overflow-y-scroll pb-5" ref="addOnContainer">
                            <div v-for="(section,index) in sections" :key="index">
                                <h2 class="text-grayCust-1600 text-xl font-bold mt-8 -mb-8">
                                  {{ section.name }}
                                </h2>
                                <div class="flex flex-wrap justify-content-center items-start">
                                    <div v-for="(feature,index) in section.features" :key="'feature'+index" :ref="feature.name" :id="feature.name">
                                        <div :class="setBorder(feature.type=='boolean' && (basePlan[feature.key] || activePlan.description[feature.key] ), feature.name)" class="border mt-10 mr-8 rounded-xl bg-grayCust-50 py-6 mx-auto relative" style="width: 255px" v-if="!feature.hide">
                                            <div class="bg-primary-175 rounded-full w-14 h-14 cursor-pointer flex justify-center items-center mx-auto">
                                                <component v-bind:is="feature.icon ? feature.icon : 'CursorArrow'" />
                                            </div>
                                            <div class="flex justify-center items-center mt-4">
                                                <h3 class="text-grayCust-800 text-sm font-semibold cursor-pointer text-center mr-2">
                                                    {{feature.name}}
                                                </h3>
                                              <information :key="'fhelp'+index" v-tooltip="feature.helpText ? feature.helpText : feature.name" class="cursor-pointer"/>
                                            </div>
                                            <div v-if="feature.type=='boolean'">
                                                <div class="flex justify-center mt-4 py-1.5">
                                                 <Switch :disabled="basePlan[feature.key]" v-model="activePlan.description[feature.key]" :class="[activePlan.description[feature.key] ? 'bg-primary-900' : 'bg-gray-200', 'relative inline-flex flex-shrink-0 h-6 w-11 border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-900']">
                                                    <span aria-hidden="true" :class="[activePlan.description[feature.key] ? 'translate-x-5' : 'translate-x-0', 'pointer-events-none inline-block h-5 w-5 rounded-full bg-white shadow transform ring-0 transition ease-in-out duration-200']" />
                                                </Switch>
                                                </div>
                                                <div v-if="basePlan[feature.key]" class="flex justify-center mt-4 py-1.5">
                                                  <span class="flex text-gray-600 text-sm text-uppercase">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 mr-1">
                                                      <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    {{ trans('lang.labels.included_in_base_plan') }}
                                                  </span>
                                                </div>
                                            </div>
                                            <span v-else class="relative z-0 flex justify-center mt-4 rounded-md">
                                                <button @click="removeFromCart(feature)" type="button" class="relative w-9 h-9 inline-flex items-center justify-center rounded-l-md border border-grayCust-250 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:z-10 focus:outline-none focus:ring-1 focus:ring-primary-900 focus:border-primary-900">
                                                  <component v-bind:is="'Minus'" />
                                                </button>
                                                <input v-model="activePlan.description[feature.key]" class="text-center disabled relative w-14 h-9 inline-flex items-center justify-center border border-grayCust-250 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:z-10 focus:outline-none focus:ring-none focus:border-none" readonly>
                                                <button @click="addToCart(feature)" type="button" class="relative w-9 h-9 inline-flex items-center justify-center rounded-r-md border border-grayCust-250 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:z-10 focus:outline-none focus:ring-1 focus:ring-primary-900 focus:border-primary-900">
                                                  <component v-bind:is="'Plus'" />
                                                </button>
                                            </span>
                                            <div v-if="activePlan.description[feature.key] !== basePlan[feature.key]" class="flex justify-center items-center mt-4">
                                              <span v-if="feature.type=='numeric'" class="flex text-gray-600 text-sm text-uppercase">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 mr-1">
                                                  <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                                </svg>
                                                {{ activePlan.description[feature.key] - basePlan[feature.key] }} {{ trans('lang.labels.addons') }}
                                              </span>
                                              <span v-if="feature.type=='boolean' && !basePlan[feature.key] && activePlan.description[feature.key] && (cart.length == 0 || cart.filter((cpt)=>cpt.key == feature.key).length == 0)" class="flex text-gray-600 text-sm text-uppercase">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 mr-1">
                                                  <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                {{ trans('lang.labels.bought_as_addon') }}
                                              </span>
                                            </div>
                                            <div v-if="cart.some((cpt)=>cpt.key == feature.key)" class="absolute top-2.5 right-2.5">
                                              <svg width="21" height="20" viewBox="0 0 21 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M8.24205 19.2743C7.14393 19.2743 6.25049 18.3809 6.25049 17.2828C6.25049 16.1846 7.14393 15.2915 8.24205 15.2915C9.34018 15.2915 10.2336 16.1846 10.2336 17.2828C10.2336 18.3809 9.34018 19.2743 8.24205 19.2743ZM8.24205 16.5415C7.8333 16.5415 7.50049 16.874 7.50049 17.2828C7.50049 17.6918 7.83299 18.0243 8.24205 18.0243C8.65111 18.0243 8.98361 17.6918 8.98361 17.2828C8.98361 16.874 8.65111 16.5415 8.24205 16.5415Z" fill="#005E54"/>
                                                <path d="M15.1652 19.2743C14.0671 19.2743 13.174 18.3809 13.174 17.2828C13.174 16.1846 14.0671 15.2915 15.1652 15.2915C16.2633 15.2915 17.1568 16.1846 17.1568 17.2828C17.1568 18.3809 16.263 19.2743 15.1652 19.2743ZM15.1652 16.5415C14.7565 16.5415 14.424 16.874 14.424 17.2828C14.424 17.6918 14.7565 18.0243 15.1652 18.0243C15.5743 18.0243 15.9068 17.6918 15.9068 17.2828C15.9068 16.874 15.574 16.5415 15.1652 16.5415Z" fill="#005E54"/>
                                                <path d="M16.6232 13.7479H6.80481C5.8495 13.7479 5.03356 13.0976 4.82043 12.1663L2.78262 3.26728C2.75575 3.14978 2.6745 3.04916 2.56512 2.99853L0.874808 2.2151C0.483558 2.03385 0.313246 1.56947 0.494496 1.17791C0.675746 0.786659 1.14043 0.616347 1.53168 0.797597L3.222 1.58103C3.76668 1.83322 4.172 2.33322 4.30575 2.91853L6.34356 11.8182C6.39293 12.0345 6.58262 12.1857 6.80481 12.1857H16.6232C16.8445 12.1857 17.0339 12.0354 17.0845 11.8198L18.6689 5.00322C18.7142 4.80947 18.6317 4.66853 18.5789 4.60197C18.5257 4.5351 18.407 4.42322 18.2082 4.42322H6.64575C6.21418 4.42322 5.8645 4.07353 5.8645 3.64197C5.8645 3.21041 6.21418 2.86072 6.64575 2.86072H18.2082C18.8329 2.86072 19.4136 3.14103 19.8023 3.6301C20.1911 4.11916 20.3323 4.74853 20.1911 5.35697L18.6064 12.1738C18.3904 13.1004 17.5751 13.7479 16.6232 13.7479Z" fill="#005E54"/>
                                                <path d="M13.5042 6.30518C13.2936 6.30518 13.0826 6.38268 12.928 6.53768L11.128 8.33768L10.5386 7.7483C10.2333 7.44361 9.69952 7.44424 9.39515 7.7483C9.08202 8.06111 9.08358 8.57924 9.39515 8.89174L10.5614 10.0574C10.7136 10.2102 10.917 10.2942 11.133 10.2942C11.3567 10.2942 11.5648 10.2096 11.718 10.0552C11.718 10.0552 14.0736 7.69924 14.0811 7.69174C14.3951 7.37768 14.3945 6.85049 14.0811 6.53799C13.9261 6.38268 13.7151 6.30518 13.5042 6.30518Z" fill="#11BF85"/>
                                              </svg>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <div class="border border-grayCust-900 p-4 rounded-t-xl mt-20" style="width: 342px;" >
                            <h2 class="text-base text-purpleCust-1500 font-semibold mb-2" >
                              {{ trans('lang.labels.cart') }}
                            </h2>
                            <div id="cartScroll"  class="overflow-y-scroll mb-14" style="min-height: 150px; max-height: 428px;">
                                <div v-for="(product,index) in cart" :key="'cart'+index" >
                                    <div class="border-b border-grayCust-900 p-4 flex justify-between" >
                                        <div class="flex items-center">
                                            <div class="bg-primary-175 rounded-full w-14 h-14 cursor-pointer flex justify-center items-center" >
                                                <component v-bind:is="product.icon" />
                                            </div>
                                            <div class="ml-4">
                                                <h3 class="text-grayCust-800 text-sm font-semibold mb-1 cursor-pointer" >
                                                  {{product.name}}
                                                </h3>
                                                <p v-if="product.type=='numeric'" class="text-grayCust-500 text-sm">
                                                  x {{ product.unit * product.chunk }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="flex mt-1">
                                            <h2 class="text-sm font-semibold text-primary-900 mr-5" >
                                              ${{ product.amount }}
                                            </h2>
                                            <div @click="destroyProduct(index, product.key)">
                                              <delete-icon class="cursor-pointer" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-4 flex justify-between items-center">
                              <h4 class="text-grayCust-800 text-sm font-medium">
                                {{ trans('lang.labels.current_subscription') }}
                              </h4>
                              <p class="text-base text-primary-900 font-semibold">
                                  ${{ activePlan.amount_charged }}/{{ activePlan.interval == 1 ? 'mo' : 'yearly' }}
                              </p>
                            </div>
                            <div class="mt-4 flex justify-between items-center">
                              <h4 class="text-grayCust-800 text-sm font-medium">
                                {{ trans('lang.labels.changes') }}
                              </h4>
                              <p class="text-base text-primary-900 font-semibold">
                                  ${{ addOnValue }}/{{ activePlan.interval == 1 ? 'mo' : 'yearly' }}
                              </p>
                            </div>
                            <div class="rounded-md bg-yellow-50 p-4 mt-4" v-if="activePlan.coupon_code !=''">
                              <div class="flex">
                                  <div class="flex-shrink-0">
                                      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-insta-primary" viewBox="0 0 20 20" fill="currentColor">
                                      <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                      </svg>
                                  </div>
                                  <div class="ml-3 flex-1 md:flex md:justify-between">
                                      <p class="text-sm leading-5 text-yellow-700">
                                          
                                      {{trans('lang.labels.cart_coupon_code')}} <b>{{activePlan.coupon_code}}</b> {{trans('lang.message.will_be_applied_upon_checkout')}}
                                      </p>
                                  </div>
                              </div>
                            </div>
                            
                            <!-- <div class="mt-4 flex justify-between items-center">
                              <h4 class="text-grayCust-800 text-sm font-medium">
                                {{ trans('lang.labels.new_pricing') }}
                              </h4>
                              <p class="text-base text-primary-900 font-semibold">
                                  ${{ activePlan.amount_charged + addOnValue }}/{{ activePlan.interval == 1 ? 'mo' : 'yearly' }}
                              </p>
                            </div> -->
                        </div>
                        <div class="rounded-b-lg border border-grayCust-900 border-t-0 bg-grayCust-50 p-4">
                            <button 
                                :disabled="btnstatus" 
                                :class="btnstatus? 'disabled' : ''" 
                                @click="openConfirmBox" 
                                class="rounded-lg bg-primary-900 flex justify-center items-center py-3 w-full disabled:opacity-75">
                              <p class="ml-2 text-sm font-semibold text-white">
                                {{ trans('lang.labels.update_subscription') }}
                              </p>
                            </button>
                            <inertia-link href="/subscriptions" class="rounded-lg mt-4 bg-white text-sm font-semibold text-primary-900 cursor-pointer flex justify-center items-center py-3 w-full border border-grayCust-350">
                              {{ trans('lang.labels.back') }}
                            </inertia-link>
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    </div>

  <!-- Subscribe Current Plan -->
  <jet-confirmation-modal :show="showConfirmation" alertType="info" :closeable="false"
                          @close="showConfirmation = false">
    <template #title>
      {{ trans('lang.labels.are_you_sure') }}
    </template>

    <template #content>
      {{ trans('lang.message.you_are_updating_addons_subscriptions') }} <br/>
      {{ trans('lang.message.your_subscription_will_be_updated_from') }} <b>${{ activePlan.amount_charged }}/{{ activePlan.interval == 1 ? 'mo' : 'yearly' }}</b> {{ trans('lang.labels.to') }} <b>${{ fullAmount }}/{{ activePlan.interval == 1 ? 'mo' : 'yearly' }}</b>.
      <br/>{{currentCharge > 0 ? trans('lang.message.we_will_charge_your_card') : trans('lang.message.you_account_will_be_credited_with')}} <b>${{ Math.abs(currentCharge) }}</b>. {{ trans('lang.message.do_you_wish_to_proceed') }}
    </template>

    <template #footer>
      <jet-secondary-button @click="showConfirmation = false">
        {{ trans('lang.labels.cancel') }}
      </jet-secondary-button>

      <jet-primary-button class="ml-2" @click="buySubscription" :class="{ 'opacity-25': loading }"
                          :disabled="loading">
        <span>{{ trans('lang.labels.confirm') }}</span>
      </jet-primary-button>
    </template>
  </jet-confirmation-modal>
  
</template>

<script>

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Secure from "@/ImageComponents/CurrentPlan/Secure.vue";
import AccountIcon from "@/ImageComponents/Profile/AccountIcon.vue";
import CursorArrow from "@/ImageComponents/CurrentPlan/CursorArrow.vue";
import Graph from "@/ImageComponents/CurrentPlan/Graph.vue";
import DeleteIcon from "@/ImageComponents/CurrentPlan/DeleteIcon.vue";
import Information from "@/ImageComponents/CurrentPlan/Information.vue";
import Minus from "@/ImageComponents/CurrentPlan/Minus.vue";
import Plus from "@/ImageComponents/CurrentPlan/Plus.vue";
import ArrowCircle from "@/ImageComponents/CurrentPlan/ArrowCircle.vue";
import {Switch} from '@headlessui/vue'
import {sections, features} from '@/features.js';
import JetConfirmationModal from "@/Common/ConfirmationModal.vue";
import JetSecondaryButton from '@/Components/SecondaryButton.vue';
import JetPrimaryButton from "@/Components/PrimaryButton.vue";
import AdvanceOptions from "@/ImageComponents/Features/AdvanceOptions.vue";
import ApiRateLimit from "@/ImageComponents/Features/ApiRateLimit.vue";
import DiskStorage from "@/ImageComponents/Features/DiskStorage.vue";
import WhiteLabel from "@/ImageComponents/Features/WhiteLabel.vue";
import Team from "@/ImageComponents/Features/Team.vue";
import TeamMembers from "@/ImageComponents/Features/TeamMembers.vue";
import { mapState, mapStores } from 'pinia';
import { useAppStore } from '@/store';

export default {
    props:{
      activePlan: {
        type: Object,
        default: null
      },
      user:{
        type: Object,
        default: null
      },
      basePlan:{
        type: Object,
        default: null
      }
    },
    name: "buy-addons",
    components: {
      AuthenticatedLayout,
        Secure,
        CursorArrow,
        Graph,
        DeleteIcon,
        Information,
        Minus,
        Plus,
        ArrowCircle,
        Switch,
        JetConfirmationModal,
        JetSecondaryButton,
        JetPrimaryButton,
        AccountIcon,
        ActiveSites,
        AdvanceConfigurations,
        AdvanceDeployments,
        AdvanceOptions,
        ApiRateLimit,
        CloneSites,
        DedicatedServer,
        DiskStorage,
        ExpiryTime,
        FTPAccess,
        GitOperations,
        InstantTemplate,
        MapDomain,
        ReserveSites,
        RestoreSites,
        SandboxSites,
        SSHAccess,
        Templates,
        TemplateBranding,
        TemplateSitesExport,
        WhiteLabel,
        Team,
        TeamMembers,
    },
    data() {
        return {
            sections: sections,
            flatFeatures: features,
            // activePlan: this.activePlan,
            enabled:true,
            cart:[],
            showConfirmation: false,
            loading: false,
            fullAmount: 0,
            currentCharge: 0,
            priceID: '',
            btnstatus:true,
            featureToHighLight: null
        };
    },
    mounted() {
      if (this.route().params.feature) {
          for (let index = 0; index < this.sections.length; index++) {
              const section = this.sections[index]
              let flag = false
              for (let j = 0; j < section.features.length; j++) {
                  const feature = section.features[j];
                  if (feature.key == this.route().params.feature) {
                    flag = true
                    this.featureToHighLight = this.sections[index].features[j].name
                    this.scrollToElement(this.sections[index].features[j].name)
                    break
                  }
              }
              if (flag)
                break
          }
      }
    },
    methods: {
       scrollToElement(scrollToMe) {
          const el = this.$refs[scrollToMe];
          const addOnContainer = this.$refs.addOnContainer
          console.log(addOnContainer.pageYOffset)
          if (el && el[0]) {
              setTimeout(() => {
                  let element = el[0]
                  let headerOffset = 148;
                  let elementPosition = element.getBoundingClientRect().top;
                  let offsetPosition = elementPosition - headerOffset;
                  addOnContainer.scrollTo({
                      top: offsetPosition,
                      behavior: "smooth"
                  });
              }, 500);
          }
      },
      showNotification(notification) {
            this.appStore.setNotification(notification);
        },
      addToCart(feature){
        if(! ((this.activePlan.description[feature.key] + feature.chunk) > feature.max)) {
          this.activePlan.description[feature.key] += feature.chunk
        }
      },
      removeFromCart(feature){
        if((this.activePlan.description[feature.key] - feature.chunk) > this.basePlan[feature.key]) {
          this.activePlan.description[feature.key] -= feature.chunk
        } else {
          if(this.activePlan.description[feature.key] != this.basePlan[feature.key])
          {
            this.activePlan.description[feature.key] = this.basePlan[feature.key]
            this.cart.forEach((citem,index)=>{
              if(citem.key == feature.key){
                this.cart.splice(index, 1)
              }
            })
          }
        }
      },
      
      destroyProduct(index, pkey){
        this.activePlan.description[pkey] = this.user.active_plan.description[pkey]
        this.cart.splice(index, 1);
      },
      openConfirmBox() {
        this.btnstatus = true;
        axios.post('/api/stripe-prices', {
          features: this.cart,
          planPrice: this.addOnValue,
          flatFeatures: this.flatFeatures
        })
        .then((response) => {
          this.fullAmount = response.data.data.full;
          this.currentCharge = response.data.data.current_charge;
          this.priceID = response.data.data.price_id;
          this.showConfirmation = true;
          this.btnstatus=false
        })
        .catch((error) => {
          this.btnstatus=false
        })
      },
      buySubscription() {
        let that = this;
        that.loading = true;
        axios.post('/subscription/addons', {
          features: that.cart,
          planPrice: that.addOnValue,
          flatFeatures: that.flatFeatures, 
          stripePriceOD: that.priceID
        })
        .then((response) => {
          if (response.data.link != undefined && response.data.link != null) {
            window.location.href = response.data.link;
          } else {
            window.location.href = '/subscriptions';
          }
          // that.$inertia.visit('/subscriptions');
          that.loading=false
        })
        .catch((error) => {
          this.showConfirmation = false;
          let message = error.response.data.error || error.message;
          let notification = {
            heading: that.trans('lang.labels.failed') + ' !',
            subHeading: message,
            type: "error",
          };
          that.appStore.showNotification(notification);
          that.loading=false
        })
      },
      setBorder(is_plan_feature, name){
        let blinkClass = name == this.featureToHighLight ? ' blink-plan' : ''
        return (is_plan_feature ? 'border-green-800' : 'border-grayCust-900') + blinkClass;
      }
    },
    watch: {
      'activePlan.description': {
            handler: function(customized) {
              let basePlan = this.user.active_plan.description
              for (let feature in customized){
                let cartProduct = this.cart.find(product=>product.key == feature);
                let baseFeature = this.flatFeatures.find(ffeature=>ffeature.key==feature);
               
                /*this.cart.forEach((citem,index)=>{
                  if(this.activePlan.description[citem.key] == false){
                    this.cart.splice(index, 1)
                  }
                })
                */
                // If disabled value is toggled back to disabled.
                if(baseFeature && this.activePlan.description[feature] === basePlan[feature]) {
                  this.cart.forEach((citem,index)=>{
                    if(citem.key == feature){
                      this.cart.splice(index, 1)
                    }
                  })
                } else if(this.activePlan.description[feature] !== basePlan[feature]) {
                  
                  if(baseFeature && baseFeature.type == 'numeric') {
                    let unit = (this.activePlan.description[feature] - basePlan[feature]) / baseFeature.chunk;
                    if (cartProduct) {
                      cartProduct.unit = unit;
                      cartProduct.amount = unit * baseFeature.per_chunk_price * (this.activePlan.interval == 1 ? 1 : 10);
                      cartProduct.value = unit * baseFeature.chunk;
                    } else {
                      this.cart.push({
                        key: feature,
                        name: baseFeature.name,
                        type: baseFeature.type,
                        unit: unit,
                        chunk: baseFeature.chunk,
                        per_chunk_price: baseFeature.per_chunk_price,
                        amount: unit * baseFeature.per_chunk_price * (this.activePlan.interval == 1 ? 1 : 10),
                        value: unit * baseFeature.chunk,
                        icon: baseFeature.icon ? baseFeature.icon :'CursorArrow',
                      })
                    }
                  }
        
                  if(baseFeature && baseFeature.type == 'boolean') {
                    if (cartProduct) {
                      cartProduct.amount = (this.activePlan.description[feature] ? baseFeature.per_chunk_price : -Math.abs(baseFeature.per_chunk_price)) * (this.activePlan.interval == 1 ? 1 : 10);
                      cartProduct.value = this.activePlan.description[feature]
                    } else {
                      this.cart.push({
                        key: feature,
                        name: baseFeature.name,
                        type: baseFeature.type,
                        unit: 1,
                        per_chunk_price: baseFeature.per_chunk_price,
                        amount: (this.activePlan.description[feature] ? baseFeature.per_chunk_price : -Math.abs(baseFeature.per_chunk_price)) * (this.activePlan.interval == 1 ? 1 : 10),
                        value: this.activePlan.description[feature],
                        icon: baseFeature.icon ? baseFeature.icon :'CursorArrow',
                      })
                    }
                  }
                }
              }
              if(this.cart.length < 1) {
                this.btnstatus = true;
              } else {
                this.btnstatus = false;
              }
            },
            deep: true
        }
    },
    computed:{
      ...mapStores(useAppStore),
        addOnValue(){
          return this.cart.reduce(function (acc, obj) { return acc + obj.amount; }, 0)
        }
    },
  
}
</script>

<style scoped>
#scroll::-webkit-scrollbar {
    display: none;
}

#cartScroll::-webkit-scrollbar {
    width: 3px;
}

/* Track */
#cartScroll::-webkit-scrollbar-track {
    background: transparent;
    border-radius: 8px;
}

/* Handle */
#cartScroll::-webkit-scrollbar-thumb {
    background: #005E54;
    border-radius: 8px;
}

.shadow {
    box-shadow: 0px 2px 4px rgba(97, 108, 134, 0.06);
}

:deep(.data-v-tooltip::after ){
  width: 175px !important;
  display: flex;
  justify-content: center;
  right: -20px !important;
  left: unset;
  transform: translateY(-100%);
  top: -10px;
}
:deep(.data-v-tooltip::before){
  bottom: 21px;
}

.blink-plan {
    animation: blink-element 2s;
    animation-iteration-count: 2;
}
@keyframes blink-element {
    0%,
    50%,
    100% {
        opacity: 1;
    }
    25%,
    75% {
        opacity: 0;
    }
}
</style>
