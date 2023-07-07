<template>
    <InertiaHead :title='trans("lang.labels.payment_method")' />
    <AuthenticatedLayout>
        <template #header>
            <h3 class="font-semibold text-2xl text-gray-800 leading-tight">
                {{ trans("lang.labels.payment_method") }}
            </h3>
        </template>
        <div class="mt-5">
            <div class="mx-auto rounded-lg pt-1 grid grid-cols-1 md:grid-cols-6 gap-6">
                <profile-nav-bar :activeTabName="activeTabName" />
                <div class="w-full col-span-4">
                    <div class="custom-width bg-sidebar rounded-t-md p-6 shadow">
                        <div>
                            <div class="mb-6">
                                <h3 class="text-lg font-medium leading-6 text-neutral-400">{{
                                    trans('lang.labels.payment_method') }}</h3>
                                <p class="mt-1 text-sm text-gray-500">{{
                                    trans('lang.labels.the_default_payment_method_will_be_any_billing_purposes') }}</p>
                            </div>
                            <div v-for="item in list" :key="item.id">
                                <div class="mt-6">
                                    <div class="bg-cardtop w-full px-6 py-5 rounded-md">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center flex-wrap">

                                                <component v-if="item.card.brand != 'American Express'"
                                                    v-bind:is="item.card.brand" />
                                                <component
                                                    v-if="item.card.brand == 'American Express' || item.card.brand == 'amex'"
                                                    v-bind:is="'Americanexpress'" />
                                                <div class="sm2:ml-4 mt-3 lg:mt-0 ml-5">
                                                    <div class="flex items-center mb-3 lg:mb-0">
                                                        <h4 class="text-sm font-medium text-gray-300 ">
                                                            {{ item.card.brand }}
                                                            **** {{ item.card.last4 }}
                                                        </h4>
                                                        <button v-if="default_source == item.id"
                                                            class="bg-primary-100 ml-2 py-0.5 px-3 text-gray-300 rounded-full text-xs font-medium">{{
                                                                trans('lang.labels.primary') }}</button>
                                                    </div>
                                                    <div class="flex items-center">
                                                        <div class="text-sm text-gray-300">{{
                                                            trans('lang.labels.expires') }}
                                                            {{ item.card.exp_month }}/{{ item.card.exp_year }}
                                                        </div>
                                                        <div class="text-sm text-gray-300 mx-2 mb-2">.</div>
                                                        <div class="text-sm text-gray-300">{{
                                                            trans('lang.labels.last_updated_on') }} {{ item.date }}</div>
                                                    </div>
                                                </div>
                                            </div>


                                            <div class="-ml-px relative block" v-if="default_source != item.id">
                                                <DropdownMenu align="right" width="48">
                                                    <template #trigger>
                                                        <button type="button" class="
                                                                                        relative
                                                                                        inline-flex
                                                                                        items-center
                                                                                        px-2
                                                                                        py-2
                                                                                        rounded-lg
                                                                                        border border-gray-400
                                                                                        bg-sidbar
                                                                                        text-sm
                                                                                        font-medium
                                                                                        text-gray-300
                                                                                        hover:bg-amber-500
                                                                                        focus:z-10
                                                                                        focus:outline-none
                                                                                        focus:ring-1
                                                                                        focus:ring-primary-900
                                                                                        focus:border-primary-900"
                                                            v-tooltip="trans('lang.labels.more_act')">
                                                            <span class="sr-only">{{ trans('lang.labels.more_act')
                                                            }}</span>
                                                            <Dots />
                                                            <!-- <p
                                                                class="sm:hidden md:block lg:hidden text-xs text-grayCust-800">
                                                                {{ trans('lang.labels.more_act') }}
                                                            </p> -->
                                                        </button>
                                                    </template>

                                                    <template #content>
                                                        <div class="block px-4 py-2 text-xs text-gray-600">
                                                            {{ trans('lang.labels.actions') }}
                                                        </div>

                                                        <template v-if="action">
                                                            <button class=" flex items-center w-full text-left
                                                                        block
                                                                        px-4
                                                                        py-2
                                                                        text-sm
                                                                        leading-5
                                                                        text-gray-700
                                                                        hover:bg-gray-100
                                                                        focus:outline-none focus:bg-gray-100
                                                                        transition" @click="setDefaultCard(item.id)"
                                                                v-if="default_source != item.id">
                                                                <svg class="h-4 w-4 mr-1" viewBox="0 0 512 512"
                                                                    xmlns="http://www.w3.org/2000/svg" fill="currentColor">
                                                                    <path
                                                                        d="M32,376a56,56,0,0,0,56,56H424a56,56,0,0,0,56-56V222H32Zm66-76a30,30,0,0,1,30-30h48a30,30,0,0,1,30,30v20a30,30,0,0,1-30,30H128a30,30,0,0,1-30-30Z" />
                                                                    <path
                                                                        d="M424,80H88a56,56,0,0,0-56,56v26H480V136A56,56,0,0,0,424,80Z" />
                                                                </svg>
                                                                <svg v-if="btnLoading && processing && item.id == currentItemID"
                                                                    role="status" class="inline mr-3 w-4 h-4 animate-spin"
                                                                    viewBox="0 0 100 101" fill="none"
                                                                    xmlns="http://www.w3.org/2000/svg">
                                                                    <path
                                                                        d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z"
                                                                        fill="#E5E7EB" />
                                                                    <path
                                                                        d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z"
                                                                        fill="currentColor" />
                                                                </svg>
                                                                <span class="ml-2"> {{ trans('lang.labels.set_as_primary')
                                                                }}</span>
                                                            </button>
                                                            <button class=" flex
                                                                                          w-full
                                                                                          text-left
                                                                                          block
                                                                                          px-4
                                                                                          py-2
                                                                                          text-sm
                                                                                          leading-5
                                                                                          text-gray-700
                                                                                          hover:bg-gray-100
                                                                                          focus:outline-none focus:bg-gray-100
                                                                                          transition"
                                                                @click="onDeletePaymentMethod(item.id)">
                                                                <svg width="14" height="16" viewBox="0 0 14 16" fill="none"
                                                                    xmlns="http://www.w3.org/2000/svg" class="mr-1 w-4 h-4">
                                                                    <path
                                                                        d="M12.25 4.25L11.5997 13.3565C11.5728 13.7349 11.4035 14.0891 11.1258 14.3477C10.8482 14.6063 10.4829 14.75 10.1035 14.75H3.8965C3.5171 14.75 3.1518 14.6063 2.87416 14.3477C2.59653 14.0891 2.42719 13.7349 2.40025 13.3565L1.75 4.25M5.5 7.25V11.75M8.5 7.25V11.75M9.25 4.25V2C9.25 1.80109 9.17098 1.61032 9.03033 1.46967C8.88968 1.32902 8.69891 1.25 8.5 1.25H5.5C5.30109 1.25 5.11032 1.32902 4.96967 1.46967C4.82902 1.61032 4.75 1.80109 4.75 2V4.25M1 4.25H13"
                                                                        stroke="currentColor" stroke-width="1.5"
                                                                        stroke-linecap="round" stroke-linejoin="round" />
                                                                </svg>
                                                                <span class="ml-2">{{ trans('lang.labels.delete') }}</span>
                                                            </button>
                                                        </template>
                                                    </template>
                                                </DropdownMenu>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div style="w-full" class="bg-cardtop flex justify-end rounded-b-md px-6 py-3 mb-6 shadow">
                        <button @click="isOpenPaymentModal = true"
                            class="text-white hover:bg-themeChartColorTwo bg-themeChartColorOne px-4 py-2 rounded-md text-sm font-medium">{{
                                trans('lang.labels.add_payment_method') }}</button>
                    </div>


                    <TransitionRoot as="template" :show="isOpenPaymentModal">
                        <Dialog as="div" class="relative z-10" @close="isOpenPaymentModal = false">
                            <TransitionChild as="template" enter="ease-out duration-300" enter-from="opacity-0"
                                enter-to="opacity-100" leave="ease-in duration-200" leave-from="opacity-100"
                                leave-to="opacity-0">
                                <div class="fixed inset-0 bg-bgModal bg-opacity-75 transition-opacity" />
                            </TransitionChild>

                            <div class="fixed inset-0 z-10 overflow-y-auto">
                                <div
                                    class="flex min-h-full items-center justify-center p-4 text-center sm:items-center sm:p-0 ">
                                    <TransitionChild as="template" enter="ease-out duration-300"
                                        enter-from="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                                        enter-to="opacity-100 translate-y-0 sm:scale-100" leave="ease-in duration-200"
                                        leave-from="opacity-100 translate-y-0 sm:scale-100"
                                        leave-to="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                                        <DialogPanel
                                            class="relative transform overflow-hidden rounded-lg bg-white pb-0 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                                            <div
                                                class="flex pt-6 px-6 justify-between items-center pb-4 border-b border-grayCust-900 bg-sidebar">
                                                <h3 class="font-semibold text-neutral-300" style="font-size: 22px;">
                                                    {{ trans('lang.labels.new_credit_debit_card') }} </h3>
                                                <div>
                                                    <close-gray class="cursor-pointer"
                                                        @click="isOpenPaymentModal = false" />
                                                </div>
                                            </div>
                                            <form id="billing-form" :action="route('card.update')"
                                                @submit.prevent="onSubmit">
                                                <div
                                                    class="rounded-md w-full flex flex-col items-center mt-8 px-4 sm2:mx-0 ">
                                                    <div class="mb-6">
                                                        <div class="flex flex-row items-center mb-1">
                                                            <a href="https://stripe.com/en-in" target="_blank">
                                                                <img :src="cdn('images/payment-gateway.svg')"
                                                                    alt="Powered By Stripe" class="" />
                                                            </a>
                                                        </div>
                                                        <fieldset>
                                                            <div>
                                                                <div ref="cards" id="card-element"
                                                                    class="border border-solid border-gray-400 px-5 py-2 mt-2 rounded-lg">
                                                                </div>
                                                                <p v-show="errors.payment_method.length > 0"
                                                                    class="mt-1 ml-1 text-xs text-red-600">
                                                                    {{ errors.payment_method }}
                                                                </p>
                                                                <p class="text-xs mt-2 text-gray-700">
                                                                    {{ trans('lang.labels.your_card_information') }}
                                                                </p>
                                                            </div>
                                                        </fieldset>
                                                    </div>
                                                </div>

                                                <!-- <div class="mt-5 px-6">
                                                <label for="name"
                                                    class="block text-sm font-medium text-grayCust-800">Holder
                                                    Name</label>
                                                <div class="mt-1">
                                                    <input type="name" name="name" id="name"
                                                        class="block border pl-4 w-full h-10 rounded-md border-gray-300 shadow-sm focus:border-primary-900 focus:outline-none focus:ring-1 focus:ring-primary-900 sm:text-sm"
                                                        placeholder="Enter Holder Name" />
                                                </div>
                                            </div>
                                            <div class="mt-5 px-6">
                                                <label for="number"
                                                    class="block text-sm font-medium text-grayCust-800">Card
                                                    Number</label>
                                                <div class="mt-1">
                                                    <input type="number" name="number" id="number"
                                                        class="block border pl-4 w-full h-10 rounded-md border-gray-300 shadow-sm focus:border-primary-900 focus:outline-none focus:ring-1 focus:ring-primary-900 sm:text-sm"
                                                        placeholder="Enter Card Number" />
                                                </div>
                                            </div>
                                            <div class="mt-5 px-6 flex justify-center items-center">
                                                <div>
                                                    <label for="exp"
                                                        class="block text-sm font-medium text-grayCust-800">Expiration
                                                        Date</label>
                                                    <div class="mt-1">
                                                        <input type="exp" name="exp" id="exp"
                                                            class="block border pl-4 w-full h-10 rounded-md border-gray-300 shadow-sm focus:border-primary-900 focus:outline-none focus:ring-1 focus:ring-primary-900 sm:text-sm"
                                                            placeholder="Enter Expiration Date" />
                                                    </div>
                                                </div>
                                                <div class="ml-3">
                                                    <label for="cvc_cvv"
                                                        class="block text-sm font-medium text-grayCust-800">CVC/CVV</label>
                                                    <div class="mt-1">
                                                        <input type="cvc_cvv" name="cvc_cvv" id="cvc_cvv"
                                                            class="block border pl-4 w-full h-10 rounded-md border-gray-300 shadow-sm focus:border-primary-900 focus:outline-none focus:ring-1 focus:ring-primary-900 sm:text-sm"
                                                            placeholder="124****" />
                                                    </div>
                                                </div>
                                            </div> -->
                                                <div class="bg-sidebar pt-3 pb-5 px-6 sm:mt-4 sm:flex sm:flex-row-reverse">
                                                    <button :class="{ 'opacity-25': loading }" :disabled="loading"
                                                        class="inline-flex w-full justify-center items-center rounded-md bg-btnSubmitBg border border-transparent 
                                                        px-4 py-2 text-base font-medium text-btnSubmitText shadow-sm hover:bg-primary-200 focus:outline-none 
                                                        focus:ring-2 focus:ring-primary-900 focus:ring-offset-2 sm:ml-3 sm:w-auto sm:text-sm" type="submit">
                                                        <svg class="h-4 w-4 text-btnSubmitText" viewBox="0 0 20 20">
                                                            <g fill="currentColor" fill-rule="evenodd" stroke="none"
                                                                stroke-width="1">
                                                                <path
                                                                    d="M0,3.99406028 C0,2.8927712 0.898212381,2 1.99079514,2 L18.0092049,2 C19.1086907,2 20,2.89451376 20,3.99406028 L20,16.0059397 C20,17.1072288 19.1017876,18 18.0092049,18 L1.99079514,18 C0.891309342,18 0,17.1054862 0,16.0059397 L0,3.99406028 Z M2,4 L18,4 L18,16 L2,16 L2,4 Z M2,6 L18,6 L18,10 L2,10 L2,6 Z M4,12 L8,12 L8,14 L4,14 L4,12 Z">
                                                                </path>
                                                            </g>
                                                        </svg>
                                                        <span class="ml-1.5">
                                                            <span>{{ trans('lang.labels.add') }}</span>
                                                            {{ trans('lang.labels.card') }}
                                                        </span>
                                                    </button>
                                                    <button type="button"
                                                        class="mt-3 inline-flex w-full justify-center rounded-md bg-btnCancelBg px-4 py-2 text-base font-medium text-btnCancelText 
                                                        shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-900 focus:ring-offset-2 sm:mt-0 sm:w-auto sm:text-sm"
                                                        @click="isOpenPaymentModal = false">
                                                        {{ trans('lang.labels.cancel') }}
                                                    </button>
                                                </div>
                                            </form>
                                        </DialogPanel>
                                    </TransitionChild>
                                </div>
                            </div>
                        </Dialog>
                    </TransitionRoot>

                    <TransitionRoot as="template" :show="confirmingPaymentDeletion">
                        <Dialog as="div" class="relative z-10" @close="confirmingPaymentDeletion = false">
                            <TransitionChild as="template" enter="ease-out duration-300" enter-from="opacity-0"
                                enter-to="opacity-100" leave="ease-in duration-200" leave-from="opacity-100"
                                leave-to="opacity-0">
                                <div class="fixed inset-0 bg-cardtop bg-opacity-75 transition-opacity" />
                            </TransitionChild>

                            <div class="fixed z-10 inset-0 overflow-y-auto">
                                <div
                                    class="flex items-end sm:items-center justify-center min-h-full p-4 text-center sm:p-0">
                                    <TransitionChild as="template" enter="ease-out duration-300"
                                        enter-from="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                                        enter-to="opacity-100 translate-y-0 sm:scale-100" leave="ease-in duration-200"
                                        leave-from="opacity-100 translate-y-0 sm:scale-100"
                                        leave-to="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                                        <DialogPanel
                                            class="relative bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:max-w-lg sm:w-full sm:p-6">
                                            <div>
                                                <div
                                                    class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                                                    <TrashIcon class="h-6 w-6 text-red-600" aria-hidden="true" />
                                                </div>
                                                <div class="mt-3 text-center sm:mt-5">
                                                    <DialogTitle as="h3"
                                                        class="text-lg leading-6 font-medium text-gray-900"> {{
                                                            trans('lang.labels.are_you_sure') }} </DialogTitle>
                                                    <div class="mt-2">
                                                        <p class="text-sm text-gray-500">
                                                            {{ trans('lang.labels.you_are_about_delete') }} <b>{{
                                                                trans('lang.labels.payment_method') }}</b>.
                                                            {{
                                                                trans('lang.labels.the_payment_method_will_permanently_removed')
                                                            }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div
                                                class="mt-5 sm:mt-6 sm:grid sm:grid-cols-2 sm:gap-3 sm:grid-flow-row-dense">
                                                <button @click="deleteSource()" type="button"
                                                    class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 text-base font-medium text-white focus:outline-none focus:ring-2 focus:ring-offset-2 sm:col-start-2 sm:text-sm focus:ring-red-200 active:bg-red-600 focus:border-red-700 hover:bg-red-500 bg-red-600">
                                                    <svg v-if="btnLoading && processing" role="status"
                                                        class="inline mr-3 w-4 h-4 animate-spin" viewBox="0 0 100 101"
                                                        fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path
                                                            d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z"
                                                            fill="#E5E7EB" />
                                                        <path
                                                            d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z"
                                                            fill="currentColor" />
                                                    </svg>
                                                    {{ trans('lang.labels.delete') }}
                                                </button>
                                                <button @click="confirmingPaymentDeletion = false" type="button"
                                                    class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-900 sm:mt-0 sm:col-start-1 sm:text-sm"
                                                    ref="cancelButtonRef">
                                                    {{ trans('lang.labels.cancel') }}
                                                </button>
                                            </div>
                                        </DialogPanel>
                                    </TransitionChild>
                                </div>
                            </div>
                        </Dialog>
                    </TransitionRoot>

                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script>

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import ProfileNavBar from "@/Common/ProfileNavBar.vue";
import Visa from '@/ImageComponents/Profile/Visa.vue';
import Mastercard from '@/ImageComponents/Profile/MasterCard.vue';
import Americanexpress from '@/ImageComponents/Profile/AmericanExpress.vue';
import Discover from '@/ImageComponents/Profile/Discover.vue';
import Dots from '@/ImageComponents/Profile/Dots.vue';
import CloseGray from '@/ImageComponents/Profile/CloseGray.vue';
import { Dialog, DialogPanel, DialogTitle, TransitionChild, TransitionRoot } from '@headlessui/vue';
import StripePayment from "@/StripePayment";
import { mapState, mapStores } from 'pinia';
import { useAppStore } from '@/store/index';
import GeneralMixin from "@/Mixins/GeneralMixin";
import DropdownMenu from "@/Common/DropdownMenu.vue";
import { TrashIcon } from '@heroicons/vue/24/solid'
import { ref } from 'vue'

export default {
    name: 'payment-methods',
    mixins: [GeneralMixin],
    props: {
        card: {
            type: Object,
            default: {
                clientSecret: null,
                cardLastFour: null
            }
        },
        user: {
            type: Object,
            default: {
                email: null,
                name: null
            }
        },
    },
    components: {
        AuthenticatedLayout,
        Visa,
        Mastercard,
        Americanexpress,
        Discover,
        Dots,
        ProfileNavBar,
        Dialog,
        DialogPanel,
        DialogTitle,
        TransitionChild,
        TransitionRoot,
        CloseGray,
        // AddCartIcon,
        DropdownMenu,
        TrashIcon
    },
    data() {
        return {
            app: {},
            stripe: null,
            loading: false,
            radiomodel: false,
            list: null,
            processing: false,
            btnLoading: false,
            confirmingPaymentDeletion: false,
            action: true,
            default_source: null,
            currentItemID: null,
            currentDeleteItemID: null,
            form: {
                payment_method: ''
            },
            errors: {
                payment_method: '',
            },
            activeTabName: "payment-methods",
            isOpenPaymentModal: false,
            warning: false,
            message: {
                heading: this.trans('lang.labels.are_you_sure'),
                subHeading: this.trans('lang.labels.the_payment_method_will_be_removed'),
            },
        }
    },
    mounted() {
        this.appStore.userLogin(this.user);
        this.app = window.APP;
        this.listCard();
    },
    computed: {
        ...mapStores(useAppStore),
        ...mapState(useAppStore, ["user"]),
    },
    watch: {
        // whenever question changes, this function will run
        isOpenPaymentModal(value) {
            if (value) {
                this.loadStripe();
                this.getIntentKey();
                this.form.payment_method = '';
            }
        }
    },
    methods: {
        loadStripe() {
            const url = `https://js.stripe.com/v3?callback=initStripe`;
            const script = document.createElement('script');
            if (document.querySelectorAll(`script[src="${url}"]`).length == 0) {
                script.setAttribute("src", url);
                document.head.appendChild(script);
                script.onload = () => this.stripeInit();
            } else {
                setTimeout(() => {
                    this.stripeInit();
                }, 30);
            }
        },
        stripeInit() {
            try {
                this.stripe = new StripePayment;
                this.stripe.mountTo("#card-element").onChange((ex) => {
                    this.errors.payment_method = ex.error ? ex.error.message : '';
                });
            } catch (error) {
                console.log(error);
            }
        },
        onSubmit() {
            this.confirmCardSetup();
        },
        update() {
            axios.patch(document.getElementById('billing-form').action, this.form).then((response) => {
                this.loading = false;
                this.card.cardLastFour = response.data.card_last_four;
                if (this.appStore.selectedPlan) {
                    this.appStore.cardIsActivated = true;
                    this.$inertia.visit(this.app.domain + '/subscription/plans');
                }
                this.listCard();
                this.isOpenPaymentModal = false;
            }).catch((error) => {
                this.loading = false;
                if (error.response.status === 422 && error.response.data && error.response.data.errors) {
                    const messages = error.response.data.errors;
                    if (messages.payment_method && messages.payment_method.length > 0) {
                        this.errors.payment_method = messages.payment_method[0];
                        return;
                    }
                    console.log(messages);
                }
                console.log(error.response.data);
            });
        },
        confirmCardSetup() {
            this.loading = true;

            this.stripe.confirmCardSetup(this.user.email, this.user.name, this.stripe.getCardElement(), this.card.clientSecret, (result) => {
                if (result.setupIntent && result.setupIntent.payment_method) {
                    this.form.payment_method = result.setupIntent.payment_method;
                    this.update();
                    return;
                }
                if (result.error) {
                    this.loading = false;
                    this.errors.payment_method = result.error ? result.error.message : '';
                    return;
                }
                // console.log(result);
            });
        },
        setDefaultCard(id) {
            let that = this;
            that.processing = true;
            that.btnLoading = true;
            that.currentItemID = id;
            axios
                .post("/api/update-default-source", { id: id })
                .then((response) => {
                    this.listCard();
                })
                .catch((error) => {
                })
                .finally(() => {
                    that.processing = false;
                    that.btnLoading = false;
                    that.currentItemID = null;
                });
        },
        listCard() {
            let that = this;

            axios
                .get("/api/list-source")
                .then((response) => {
                    this.list = response.data.data.card.list;
                    this.default_source = response.data.data.card.default_source;
                })
                .catch((error) => {
                })
                .finally(() => {
                });
        },
        onDeletePaymentMethod(id) {
            this.confirmingPaymentDeletion = true;
            this.currentDeleteItemID = id;
        },
        deleteSource(id) {
            let that = this;
            that.processing = true;
            that.btnLoading = true;
            axios
                .get("/api/delete-source/" + that.currentDeleteItemID)
                .then((response) => {
                    this.listCard();
                })
                .catch((error) => {
                })
                .finally(() => {
                    that.processing = false;
                    that.btnLoading = false;
                    that.currentDeleteItemID = null;
                    that.confirmingPaymentDeletion = false;
                });
        },
        getIntentKey() {
            let that = this;

            axios
                .get("/api/get-intent-key")
                .then((response) => {
                    this.card.clientSecret = response.data.data.data.clientSecret;
                    this.card.cardLastFour = response.data.data.data.cardLastFour;
                })
                .catch((error) => {
                })
                .finally(() => {
                });
        },
        // change() {
        //     let iframe = document.getElementsByTagName('iframe')
        //     let style = document.createElement('style')
        //     style.textContent =
        //         'body {' +
        //         '  background-color: lime;' +
        //         '  color: pink;' +
        //         '}'
        //         ;
        //     iframe.contentDocument.head.appendChild(style)
        // }
    }
}
</script>
<style scoped>
.custom-width-main {
    width: 900px;
}

@media (min-width: 1200px) {
    .common-width {
        width: 900px;
    }

}

@media (max-width: 991px) {
    .custom-width-main {
        width: 100%;
    }

}
</style>
