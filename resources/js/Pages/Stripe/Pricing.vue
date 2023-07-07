<template>
    <InertiaHead>
        <title>{{ trans('lang.labels.pricing_plan') }} - InstaWP</title>
    </InertiaHead>
    <app-layout>
        <template #header>
            <h2 class="text-gray-900 text-3xl leading-9 font-bold">{{ trans('lang.labels.pricing_plan') }}</h2>
        </template>

        <div v-if="periodicDiscountAvailable">
            <div class="p-4 bg-secondary-800 custom-img" v-if="isCloseAlert">
                <div class="max-w-7xl mx-auto px-6 flex justify-between items-start md:items-center flex-col md:flex-row">
                    <div class="flex items-start md:items-center flex-col md:flex-row">
                        <img class="w-16 h-16" :src="unlockProgramImage" />
                        <div class="text-white text-2xl font-semibold md:ml-6">{{ unlockProgramTextBeforeUnlock }}</div>
                    </div>
                    <div class="flex justify-start  md:items-center ">
                        <button :disabled="unlockFreeCouponProcessing" @click="unlockFreeUserDiscount()"
                            class="flex items-center justify-center text-base font-medium px-6 h-12 w-32 text-primary-900 border bg-white border-primary-900 rounded-lg ">
                            <div v-if="!unlockFreeCouponProcessing" class="flex items-center">
                                <lockImg class="mr-2" :width="22" /> {{ trans('lang.labels.unlock') }}
                            </div>
                            <svg v-else width="18" height="14" role="status" class="inline text-primary animate-spin"
                                viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z"
                                    fill="#E5E7EB" />
                                <path
                                    d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z"
                                    fill="currentColor" />
                            </svg>
                        </button>
                        <button class="ml-6">
                            <crossImg @click="isCloseAlert = false" />
                        </button>
                    </div>
                </div>
            </div>

            <div class="custom-background py-4" v-if="isOpenAlert">
                <div class=" custom-gradient-img ">
                    <div class="custom-gradient-img2">
                        <div
                            class="max-w-7xl mx-auto px-6 flex justify-between items-start md:items-center flex-col md:flex-row">
                            <div class="flex items-start md:items-center flex-col md:flex-row">
                                <img class="w-16 h-16" :src="unlockProgramImage" />
                                <div class="text-white text-2xl font-semibold md:ml-6">{{ unlockProgramTextAfterUnlock }}
                                </div>
                            </div>
                            <div class="flex item-center mt-2 md:mt-0">
                                <TimerCount :countDownToTime="new Date(periodicDiscountData.validate_upto)"
                                    @timeExpires="onTimeExpires()" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>




        <div class="py-8">
            <div class="max-w-7xl bg-white py-8 sm:py-16 sm:px-6 lg:px-8 rounded-lg">

                <div class="flex items-center flex-col sm:flex-row flex-wrap lg:flex-nowrap">

                    <h3 class="text-xl leading-6 font-medium text-gray-800">
                        {{ trans('lang.labels.current_plan') }}
                    </h3>
                    <div v-if="activePlan" class="flex items-center flex-col md:flex-row ml-3 mt-2 sm:mt-0">
                        <button @click="highlightActivePlan"
                            class="focus:outline-none inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium leading-5 bg-indigo-100 label-style">
                            <svg class="-ml-1 mr-1.5 h-4 w-4 text-insta-primary" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M10,12 C13.3137085,12 16,9.3137085 16,6 C16,2.6862915 13.3137085,0 10,0 C6.6862915,0 4,2.6862915 4,6 C4,9.3137085 6.6862915,12 10,12 Z M10,9 C11.6568542,9 13,7.65685425 13,6 C13,4.34314575 11.6568542,3 10,3 C8.34314575,3 7,4.34314575 7,6 C7,7.65685425 8.34314575,9 10,9 Z M14,11.7453107 L14,20 L10,16 L6,20 L6,11.7453107 C7.13383348,12.5361852 8.51275186,13 10,13 C11.4872481,13 12.8661665,12.5361852 14,11.7453107 L14,11.7453107 Z">
                                </path>
                            </svg>
                            {{ activePlan.is_custom_plan ? activePlan.base_plan.name : activePlan.name }}
                        </button>
                        <span v-if="endsAt"
                            class="mt-2 md:mt-0 ml-4 inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium leading-5 bg-red-200 text-red-600">
                            {{ trans('lang.labels.will_expired') }} {{ endsAt }}
                        </span>
                    </div>
                    <div class="ml-auto" v-if="false">
                        <input v-if="couponApplied || showCodeInput" :readonly="couponApplied" :disabled="couponApplied"
                            :value="appStore.coupon.coupon_code" @input="updateCouponCode" type="text" class="mt-0 mr-2 px-2 py-2 bg-sidebar border-gray-500 focus:outline-none focus:ring-primary-900 
                            focus:border-primary-900 text-xs rounded-md">
                        <button v-if="!couponApplied && showCodeInput" @click="applyCoupon"
                            :disabled="!appStore.coupon.coupon_code" class="disabled:opacity-60 bg-green-800 border border-green-800 rounded-md px-2 py-2 text-xs 
                            font-semibold text-white text-center hover:bg-green-900">
                            {{ trans('lang.labels.apply_coupon') }}</button>
                        <button v-if="couponApplied" @click="removeCoupon" class="bg-red-800 border border-red-800 rounded-md px-2 py-2 text-xs font-semibold text-white 
                            text-center hover:bg-red-900">
                            {{ trans('lang.labels.remove_coupon') }}</button>
                        <button v-if="!couponApplied && !showCodeInput" @click="showCodeInput = false"
                            class="underline text-xs px-2 py-2 text-gray-800">
                            {{ trans('lang.labels.coupon_code') }}
                        </button>
                        <template
                            v-if="activeSubscription && !(activePlan.type !== freePlanType && activePlan.user_id && cardLastFour)">
                            <inertia-link :href="route('subscription-plan.customize')" class="underline text-xs px-2 py-2">
                                Buy Addons
                            </inertia-link>
                        </template>
                    </div>
                </div>
                <div class="rounded-md bg-yellow-50 p-4 mt-4" v-if="couponApplied && appStore.coupon.text">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-insta-primary" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3 flex-1 md:flex md:justify-between">
                            <p class="text-sm leading-5 text-yellow-700">
                                {{ appStore.coupon.text }}
                            </p>
                        </div>
                    </div>
                </div>
                <div v-if="endsAt" class="rounded-md bg-yellow-50 p-4 mt-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                <path clip-rule="evenodd"
                                    d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                    fill-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3 flex-1 md:flex md:justify-between">
                            <p class="text-sm leading-5 text-yellow-700">
                                {{ trans('lang.labels.content_subscription') }}
                                <inertia-link :href="route('subscription.resume')"
                                    class="text-indigo-600 hover:text-indigo-800 hover:underline cursor-pointer">
                                    {{ trans('lang.labels.resume_subscription') }}
                                </inertia-link>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="max-w-7xl mx-auto">
                    <!-- Heading Section -->
                    <div class="sm:flex sm:flex-col sm:align-center mb-6">
                        <div class="flex items-center md:justify-around mt-6 flex-col lg:flex-row ">
                            <span class="relative z-0 inline-flex rounded-md h-12" v-if="isYearMonthSwitchVisible">
                                <button type="button" :class="interval == 2 ? 'bg-green-500 text-white' : 'bg-white'"
                                    @click="setInterval()"
                                    class="relative inline-flex items-center px-4 py-2 rounded-l-md border-l border-t border-b border-gray-300  text-sm font-medium text-gray-700 focus:outline-none">
                                    {{ trans('lang.labels.yearly') }} (20% OFF)
                                </button>

                                <button type="button" :class="interval == 1 ? 'bg-red-500 text-white' : 'bg-white'"
                                    @click="setInterval()"
                                    class="relative inline-flex items-center px-4 py-2 rounded-r-md border-r border-t border-b border-gray-300  text-sm font-medium text-gray-700 focus:outline-none">
                                    {{ trans('lang.labels.monthly') }}
                                </button>
                            </span>
                        </div>
                    </div>

                    <div v-if="plans && plans.length > 0 && !verifying" class="max-w-7xl mx-auto pb-2">
                        <!-- xs to lg -->
                        <div class="max-w-2xl mx-auto space-y-12 lg:hidden">
                            <section git c class="py-2" :class="{
                                'border-2 border-green-300': plan.type === popular && interval === 2,
                                'border-2 border-red-300': plan.type === popular && interval === 1,
                                'blink-border border-transparent': show && plan.type === activePlan.type
                            }" v-for="(plan, planIdx) in visiblePlans" :key="plan.type">
                                <div class="px-4 mb-8 text-center">
                                    <div class="relative">
                                        <h2 class="text-lg leading-6 font-medium text-gray-900">
                                            {{ plan.name }}
                                        </h2>
                                        <svg v-if="plan.type === popular" class="w-12 h-12 absolute top-0 right-0"
                                            viewBox="0 0 65 68">
                                            <g fill="none" fill-rule="evenodd">
                                                <path class="js-svg-path svg-monthly-path"
                                                    :fill="popular === plan.type && interval === 1 ? '#FCA5A5' : popular === plan.type && interval === 2 ? '#6EE7B7' : '#fff'"
                                                    d="M0 0h80v68L30.5 54.273 0 68z"></path>
                                                <text fill="#212121" font-size="16" font-style="italic" font-weight="800">
                                                    <tspan x="8.591" y="26.2">{{ trans('lang.labels.popular') }}</tspan>
                                                </text>
                                            </g>
                                        </svg>
                                    </div>

                                    <p v-if="plan.type !== 'free'">
                                        <span class="text-4xl font-extrabold text-gray-300">
                                            ${{ couponApplied && (appliedCouponDetails.plan_ids.findIndex((planID) => planID
                                                == plan.stripe_plan_id) != -1 || appliedCouponDetails.plan_ids.length == 0) ?
                                                updatePriceBasedOnCoupon(plan.price, plan) : (plan.interval ===
                                                    2 ? plan.yearlyPrice : plan.price) }}
                                        </span>
                                        {{ ' ' }}
                                        <span v-if="plan.type !== 'free'" class="text-base font-medium text-gray-500">
                                            /{{ plan.interval === 2 ? trans('lang.labels.yr') : trans('lang.labels.mo') }}
                                        </span>
                                    </p>
                                    <!-- <p v-else-if="plan.type !== 'free' && plan.type === activePlan.type">
                                            <span class="text-4xl font-extrabold text-gray-900">
                                                ${{ plan.interval === 1 ?activeSubscriptionDetails.amount_charged:(activeSubscriptionDetails.amount_charged/12).toFixed(2) }}
                                            </span>
                                            {{ ' ' }}
                                            <span v-if="plan.type !== 'free'"
                                                class="text-base font-medium text-gray-500">
                                                /{{ trans('lang.labels.mo') }}
                                            </span>
                                        </p> -->
                                    <p v-if="plan.type !== 'free' && (plan.old_price || couponApplied)"
                                        class="plan-price line-through">
                                        <span class="text-xl font-medium text-gray-600">
                                            ${{ couponApplied && (appliedCouponDetails.plan_ids.findIndex((planID) => planID
                                                == plan.stripe_plan_id) != -1 || appliedCouponDetails.plan_ids.length == 0) ?
                                                (plan.interval === 2 ? plan.yearlyPrice : plan.price) : (plan.interval ===
                                                    2 ? plan.old_price * 12 : plan.old_price) }}
                                        </span>
                                        {{ ' ' }}
                                        <span class="text-base font-medium text-gray-400">
                                            /{{ plan.interval === 2 ? trans('lang.labels.yr') : trans('lang.labels.mo') }}
                                        </span>
                                    </p>
                                    <!-- <p v-else-if="plan.type !== 'free' && plan.type === activePlan.type && activeSubscriptionDetails.coupon_code" class="">
                                            <span class="text-xl font-medium text-gray-600 line-through">
                                                ${{ plan.interval === 1 ?activeSubscriptionDetails.plan_price:(activeSubscriptionDetails.plan_price/12).toFixed(2) }}
                                            </span>
                                            {{ ' ' }}
                                            <span class="text-base font-medium text-gray-400 line-through">
                                                /{{ trans('lang.labels.mo') }}
                                            </span>
                                        </p> -->
                                    <p v-if="plan.type !== 'free'" class="mt-4 text-sm text-gray-500">
                                        {{ trans('lang.labels.billed') }} {{ plan.interval === 1 ? 'monthly' : 'yearly' }}
                                    </p>
                                    <inertia-link :href="route('card.get')" @click="appStore.selectedPlan = plan"
                                        v-if="plan.type !== freePlanType && !cardLastFour && !(appliedCouponDetails && appliedCouponDetails.hasOwnProperty('allow_woc') && appliedCouponDetails.allow_woc)"
                                        aria-label="Add Card"
                                        class="mt-6 block border border-green-800 rounded-md bg-green-800 w-full py-2 text-sm font-semibold text-white text-center hover:bg-green-900">
                                        {{ trans('lang.labels.buy_plan') }}
                                    </inertia-link>

                                    <inertia-link :href="route('subscription.cancel')" v-if="plan.type !== freePlanType &&
                                        plan.type === activePlan.type && activeSubscription && endsAt === null"
                                        aria-label="Cancel Subscription"
                                        class="mt-6 block border border-red-800 rounded-md bg-red-800 w-full py-2 text-sm font-semibold text-white text-center hover:bg-red-900">
                                        {{ trans('lang.labels.cancel_plan') }}
                                    </inertia-link>

                                    <inertia-link :href="route('subscription.resume')" v-if="plan.type !== freePlanType &&
                                        plan.type === activePlan.type && !activeSubscription && endsAt !== null"
                                        aria-label="Resume Subscription"
                                        class="mt-6 block border border-indigo-800 rounded-md bg-indigo-800 w-full py-2 text-sm font-semibold text-white text-center hover:bg-indigo-900">
                                        {{ trans('lang.labels.resume_plan') }}
                                    </inertia-link>

                                    <button type="button" :class="{ 'opacity-25': loading === plan.type }"
                                        :disabled="loading === plan.type" @click="confirm(plan)"
                                        v-if="plan.type !== freePlanType && (cardLastFour || (appliedCouponDetails && appliedCouponDetails.hasOwnProperty('allow_woc') && appliedCouponDetails.allow_woc)) && plan.type !== activePlan.type"
                                        class="mt-6 block border border-green-800 rounded-md bg-green-800 w-full py-2 text-sm font-semibold text-white text-center hover:bg-green-900">
                                        {{ trans('lang.labels.choose_plan') }}
                                    </button>
                                </div>

                                <table v-for="(section) in sections" :key="section.name" class="w-full">
                                    <caption
                                        class="bg-gray-50 border-t border-gray-200 py-3 px-4 text-sm font-medium text-gray-900 text-center">
                                        {{
                                            section.name
                                        }}
                                    </caption>
                                    <thead>
                                        <tr>
                                            <th class="sr-only" scope="col">{{ trans('lang.labels.feature') }}</th>
                                            <th class="sr-only" scope="col">{{ trans('lang.labels.included') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200">
                                        <tr v-for="feature in section.features" :key="feature.name"
                                            class="border-t border-gray-200"
                                            :class="{ 'bg-primary-50 border-solid border-primary-900 blink-plan': feature.name == featureToHighLight }">
                                            <th class="py-5 px-4 text-sm font-normal text-gray-500 text-start" scope="row"
                                                :class="{ 'text-primary-900 font-bold': feature.name == featureToHighLight }">
                                                {{ feature.name }}
                                            </th>
                                            <td class="py-5 pr-4 text-center">
                                                <span
                                                    v-if="typeof plan.description[feature.key] === 'number' && plan.description[feature.key] > 0"
                                                    class="block text-sm text-gray-500 text-center">
                                                    {{ plan.description[feature.key] }}
                                                </span>
                                                <span v-else class="flex justify-center">
                                                    <svg class="h-6 w-6 text-insta-primary"
                                                        v-if="plan.description[feature.key] === true" aria-hidden="true"
                                                        fill="currentColor" viewBox="0 0 20 20">
                                                        <path clip-rule="evenodd"
                                                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                            fill-rule="evenodd">
                                                        </path>
                                                    </svg>
                                                    <svg v-else class="h-6 w-6 text-red-400" fill="none" viewBox="0 0 24 24"
                                                        stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                    </svg>
                                                </span>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>

                                <div
                                    :class="[planIdx < plan.length - 1 ? 'py-5 border-b' : 'pt-5', 'border-t border-gray-200 px-4']">
                                    <inertia-link :href="route('card.get')" @click="appStore.selectedPlan = plan"
                                        v-if="plan.type !== freePlanType && !cardLastFour && !(appliedCouponDetails && appliedCouponDetails.hasOwnProperty('allow_woc') && appliedCouponDetails.allow_woc)"
                                        aria-label="Add Card"
                                        class="block w-full bg-green-800 border border-green-800 rounded-md py-2 text-sm font-semibold text-white text-center hover:bg-green-900">
                                        {{ trans('lang.labels.buy_plan') }}
                                    </inertia-link>

                                    <inertia-link :href="route('subscription.cancel')" v-if="plan.type !== freePlanType &&
                                        plan.type === activePlan.type && activeSubscription && endsAt === null"
                                        aria-label="Cancel Subscription"
                                        class="block w-full bg-red-800 border border-red-800 rounded-md py-2 text-sm font-semibold text-white text-center hover:bg-red-900">
                                        {{ trans('lang.labels.cancel_plan') }}
                                    </inertia-link>

                                    <inertia-link :href="route('subscription.resume')" v-if="plan.type !== freePlanType &&
                                        plan.type === activePlan.type && !activeSubscription && endsAt !== null"
                                        aria-label="Resume Subscription"
                                        class="block w-full bg-indigo-800 border border-indigo-800 rounded-md py-2 text-sm font-semibold text-white text-center hover:bg-indigo-900">
                                        {{ trans('lang.labels.resume_plan') }}
                                    </inertia-link>

                                    <button type="button" :class="{ 'opacity-25': loading === plan.type }"
                                        :disabled="loading === plan.type" @click="confirm(plan)"
                                        v-if="plan.type !== freePlanType && (cardLastFour || (appliedCouponDetails && appliedCouponDetails.hasOwnProperty('allow_woc') && appliedCouponDetails.allow_woc)) && plan.type !== activePlan.type"
                                        class="block w-full bg-green-800 border border-green-800 rounded-md py-2 text-sm font-semibold text-white text-center hover:bg-green-900">
                                        {{ trans('lang.labels.choose_plan') }}
                                    </button>
                                </div>
                            </section>
                        </div>

                        <!-- lg+ -->
                        <div class="hidden lg:block">
                            <form id="plan-customizer-form" v-on:submit.prevent>
                                <table class="w-full h-px table-fixed bg-gray-200">
                                    <caption class="sr-only">
                                        {{ trans('lang.labels.pricing_plan_com') }}
                                    </caption>
                                    <thead>
                                        <tr>
                                            <th class="w-1/5 py-4 px-6 text-sm font-medium text-gray-800 text-start"
                                                scope="col">
                                                <span class="sr-only">{{ trans('lang.labels.feature_by') }}</span>
                                                <span class="text-gray-800">{{ trans('lang.labels.plans') }}</span>
                                            </th>
                                            <th v-for="(plan) in visiblePlans" :key="plan.type"
                                                style="height: 58px !important;box-sizing: border-box;" :class="{
                                                    'outline-green': plan.type === popular && interval === 2,
                                                    'border-l-2 border-r-2 border-t-2 border-red-300': plan.type === popular && interval === 1,
                                                    'blink-border': show && plan.type === activePlan.type
                                                }"
                                                class="w-1/5 py-4 px-6 text-lg header-outline  leading-6 font-medium whitespace-nowrap text-gray-200 relative"
                                                scope="col">
                                                <svg v-if="plan.type === popular" class="w-12 h-12 absolute top-0"
                                                    viewBox="0 0 65 68" style="left: 0.25rem">
                                                    <g fill="none" fill-rule="evenodd">
                                                        <path
                                                            :fill="popular === plan.type && interval === 1 ? '#FCA5A5' : popular === plan.type && interval === 2 ? '#6EE7B7' : '#fff'"
                                                            class="js-svg-path svg-monthly-path"
                                                            d="M0 0h80v68L30.5 54.273 0 68z"></path>
                                                        <text fill="#FFF" font-family="OpenSans-SemiBoldItalic, Open Sans"
                                                            font-size="16" font-style="italic" font-weight="800">
                                                            <tspan x="8.591" y="26.2">{{ trans('lang.labels.popular') }}
                                                            </tspan>
                                                        </text>
                                                    </g>
                                                </svg>

                                                <span>{{ plan.is_custom_plan ? 'Custom Plan' : plan.name }}</span>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="border-t border-gray-300 divide-y divide-gray-300">
                                        <tr>
                                            <th class="py-8 px-6 text-sm font-medium text-gray-200 text-start align-top"
                                                scope="row">
                                                {{ trans('lang.labels.pricing') }}
                                            </th>
                                            <td v-for="plan in visiblePlans" :key="plan.type" :class="{
                                                'border-l-2 border-r-2 border-green-300': plan.type === popular && interval === 2,
                                                'border-l-2 border-r-2 border-red-300': plan.type === popular && interval === 1,
                                                'blink-border-lr': show && plan.type === activePlan.type
                                            }" class="h-full py-8 px-6 align-top text-center">
                                                <div class="relative h-full table w-full">
                                                    <p v-if="plan.type !== 'free'">
                                                        <span class="text-4xl font-extrabold text-gray-800">
                                                            ${{ couponApplied &&
                                                                (appliedCouponDetails.plan_ids.findIndex((planID) => planID ==
                                                                    plan.stripe_plan_id) != -1 ||
                                                                    appliedCouponDetails.plan_ids.length == 0) ?
                                                                updatePriceBasedOnCoupon(plan.price, plan) : (plan.interval ===
                                                                    2 ? plan.yearlyPrice : plan.price) }}
                                                        </span>
                                                        {{ ' ' }}
                                                        <span v-if="plan.type !== 'free'"
                                                            class="text-base font-medium text-gray-500">
                                                            /{{ plan.interval ===
                                                                2 ? trans('lang.labels.yr') : trans('lang.labels.mo') }}
                                                        </span>
                                                    </p>
                                                    <!-- <p v-else-if="plan.type !== 'free' && plan.type === activePlan.type">
                                                        <span class="text-4xl font-extrabold text-gray-900">
                                                            ${{ plan.interval === 1 ?activeSubscriptionDetails.amount_charged:(activeSubscriptionDetails.amount_charged/12).toFixed(2) }}
                                                        </span>
                                                        {{ ' ' }}
                                                        <span v-if="plan.type !== 'free'"
                                                            class="text-base font-medium text-gray-500">
                                                            /{{ trans('lang.labels.mo') }}
                                                        </span>
                                                    </p> -->

                                                    <p v-if="plan.type !== 'free' && (plan.old_price || couponApplied)"
                                                        class="plan-price line-through">
                                                        <span class="text-xl font-medium text-gray-600">
                                                            ${{ couponApplied &&
                                                                (appliedCouponDetails.plan_ids.findIndex((planID) => planID ==
                                                                    plan.stripe_plan_id) != -1 ||
                                                                    appliedCouponDetails.plan_ids.length == 0) ? (plan.interval ===
                                                                        2 ? plan.yearlyPrice : plan.price) : (plan.interval ===
                                                                            2 ? plan.old_price * 12 : plan.old_price) }}
                                                        </span>
                                                        {{ ' ' }}
                                                        <span class="text-base font-medium text-gray-400">
                                                            / {{ plan.interval ===
                                                                2 ? trans('lang.labels.yr') : trans('lang.labels.mo') }}
                                                        </span>
                                                    </p>
                                                    <!-- <p v-else-if="plan.type !== 'free' && plan.type === activePlan.type && activeSubscriptionDetails.coupon_code" class="">
                                                        <span class="text-xl font-medium text-gray-600 line-through">
                                                            ${{ plan.interval === 1 ?activeSubscriptionDetails.plan_price:(activeSubscriptionDetails.plan_price/12).toFixed(2) }}
                                                        </span>
                                                        {{ ' ' }}
                                                        <span class="text-base font-medium text-gray-400 line-through">
                                                            /{{ trans('lang.labels.mo') }}
                                                        </span>
                                                    </p> -->
                                                    <div class="flex justify-center">
                                                        <p v-if="plan.type !== 'free'"
                                                            class="mt-4 mb-16 text-sm text-gray-500">
                                                            {{ trans('lang.labels.billed') }} {{ plan.interval === 1 ?
                                                                'monthly' : 'yearly' }}
                                                        </p>
                                                        <div class='has-tooltip mt-4 mb-16 text-sm text-gray-500'
                                                            v-if="plan.type === activePlan.type && activeSubscriptionDetails.coupon_code && activeSubscriptionDetails.hasOwnProperty('discount_text') && activeSubscriptionDetails.discount_text && plan.type !== 'free'">
                                                            <span
                                                                class='tooltip rounded shadow-lg p-1 bg-gray-100 text-red-500 -mt-8 w-56'
                                                                style="left: 90%;">{{
                                                                    activeSubscriptionDetails.discount_text }}</span>
                                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                                viewBox="0 0 24 24" stroke="currentColor"
                                                                class="w-5 ml-1 text-gray-400 cursor-pointer">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                                                </path>
                                                            </svg>
                                                        </div>
                                                    </div>

                                                    <div class="mt-2 w-full">
                                                        <inertia-link :href="route('card.get')"
                                                            @click="appStore.selectedPlan = plan"
                                                            v-if="plan.type !== freePlanType && !cardLastFour && !(appliedCouponDetails && appliedCouponDetails.hasOwnProperty('allow_woc') && appliedCouponDetails.allow_woc)"
                                                            aria-label="Add Card"
                                                            class="absolute bottom-0 flex-grow block w-full bg-green-800 border border-green-800 rounded-md 5 py-2 text-sm font-semibold text-white text-center hover:bg-green-900">
                                                            {{ trans('lang.labels.buy_plan') }}
                                                        </inertia-link>

                                                        <inertia-link :href="route('subscription.cancel')"
                                                            v-if="plan.type !== freePlanType && plan.type === activePlan.type && activeSubscription && endsAt === null"
                                                            aria-label="Cancel Subscription"
                                                            class="absolute bottom-0 flex-grow block w-full bg-red-800 border border-red-800 rounded-md 5 py-2 text-sm font-semibold text-white text-center hover:bg-red-900">
                                                            {{ trans('lang.labels.cancel_plan') }}
                                                        </inertia-link>



                                                        <inertia-link :href="route('subscription.resume')"
                                                            v-if="plan.type !== freePlanType && plan.type === activePlan.type && !activeSubscription && endsAt !== null"
                                                            aria-label="Resume Subscription"
                                                            class="absolute bottom-0 flex-grow block w-full bg-indigo-800 border border-indigo-800 rounded-md 5 py-2 text-sm font-semibold text-white text-center hover:bg-indigo-900">
                                                            {{ trans('lang.labels.resume_plan') }}
                                                        </inertia-link>

                                                        <button type="button"
                                                            :class="{ 'opacity-25': loading === plan.type }"
                                                            :disabled="loading === plan.type" @click="confirm(plan)"
                                                            v-if="plan.type !== freePlanType && (cardLastFour || (appliedCouponDetails && appliedCouponDetails.hasOwnProperty('allow_woc') && appliedCouponDetails.allow_woc)) && plan.type !== activePlan.type"
                                                            class="absolute bottom-0 flex-grow block w-full bg-green-800 border border-green-800 rounded-md 5 py-2 text-sm font-semibold text-white text-center hover:bg-green-900">
                                                            {{ trans('lang.labels.choose_plan') }}
                                                        </button>

                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <template v-for="section in sections" :key="section.name">
                                            <tr>
                                                <th class="bg-gray-200 py-3 pl-6 text-sm font-medium text-gray-800 text-left"
                                                    scope="colgroup">
                                                    {{ section.name }}
                                                </th>
                                                <th :class="{
                                                    'border-l-2 border-r-2 border-green-300': plan.type === popular && interval === 2,
                                                    'border-l-2 border-r-2 border-red-300': plan.type === popular && interval === 1,
                                                    'blink-border-lr': show && plan.type === activePlan.type
                                                }" v-for="plan in visiblePlans" :key="plan.type"
                                                    class="bg-gray-200 py-3 pl-6 text-sm font-medium text-gray-700 text-center">
                                                </th>
                                            </tr>
                                            <tr v-for="(feature, index) in section.features" :key="feature.name"
                                                :class="{ 'bg-primary-50 border-solid border-primary-900 blink-plan': feature.name == featureToHighLight }"
                                                :ref="'desktop-' + feature.name">
                                                <template v-if="!feature.hide">

                                                    <th class="py-5 px-6 text-sm font-normal text-gray-600 text-left"
                                                        scope="row">
                                                        <div class="inline-flex justify-center relative"
                                                            :class="{ 'text-primary-900 font-bold': feature.name == featureToHighLight }">
                                                            <span class="whitespace-nowrap" :class="{'font-bold text-black' : index == 0}">{{ feature.name }}</span>
                                                            <div class='has-tooltip'
                                                                v-if="feature.hasOwnProperty('helpText') && feature.helpText">
                                                                <span
                                                                    class='tooltip rounded shadow-lg p-1 bg-gray-100 text-insta-primary -mt-8 plan-tooltip'
                                                                    v-html="feature.helpText"></span>
                                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                                    class="text-gray-400 h-6 w-6 ml-2 cursor-pointer"
                                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        stroke-width="2"
                                                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                                </svg>
                                                            </div>
                                                        </div>
                                                    </th>
                                                    <td :class="{
                                                        'border-l-2 border-r-2 border-green-300': plan.type === popular && interval === 2,
                                                        'border-l-2 border-r-2 border-red-300': plan.type === popular && interval === 1,
                                                        'blink-border-lr': show && plan.type === activePlan.type,
                                                    }" v-for="plan in visiblePlans" :key="plan.type"
                                                        class="py-5 px-6">
                                                        <template v-if="!plan.user_id">
                                                            <span
                                                                v-if="typeof plan.description[feature.key] === 'number' && plan.description[feature.key] > 0"
                                                                class="block text-center" :class="{'font-extrabold text-base' : index == 0,'font-normal text-sm' : index != 0}">
                                                                {{ plan.description[feature.key] }}
                                                            </span>
                                                            <span v-else class="flex justify-center">
                                                                <svg class="h-6 w-6 text-insta-primary"
                                                                    v-if="plan.description[feature.key] === true"
                                                                    aria-hidden="true" fill="currentColor"
                                                                    viewBox="0 0 20 20">
                                                                    <path clip-rule="evenodd"
                                                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                                        fill-rule="evenodd">
                                                                    </path>
                                                                </svg>
                                                                <svg v-else class="h-6 w-6 text-red-600" fill="none"
                                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                                </svg>
                                                            </span>
                                                        </template>
                                                        <template v-else>
                                                            <span v-if="feature.type == 'numeric'"
                                                                class="block text-sm text-gray-700 text-center">
                                                                <input style="width: 100%"
                                                                    class="focus:ring-1 focus:ring-primary-900 focus:border-primary-900 rounded"
                                                                    v-model.number="customPlanFeatures[feature.key]['value']"
                                                                    type="number" :id="feature.key" :name="feature.key"
                                                                    :step="feature.chunk" :min="feature.min"
                                                                    :max="feature.max">
                                                            </span>
                                                            <span v-if="feature.type == 'boolean'"
                                                                class="flex justify-center">
                                                                <Switch v-model="customPlanFeatures[feature.key]['value']"
                                                                    :class="[customPlanFeatures[feature.key]['value'] ? 'bg-insta-primary' : 'bg-gray-200', 'relative inline-flex flex-shrink-0 h-6 w-11 border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200']">
                                                                    <span class="sr-only">{{
                                                                        trans('lang.labels.use_setting') }}</span>
                                                                    <span
                                                                        :class="[customPlanFeatures[feature.key]['value'] ? 'translate-x-5' : 'translate-x-0', 'pointer-events-none relative inline-block h-5 w-5 rounded-full bg-white shadow transform ring-0 transition ease-in-out duration-200']">
                                                                        <span
                                                                            :class="[customPlanFeatures[feature.key]['value'] ? 'opacity-0 ease-out duration-100' : 'opacity-100 ease-in duration-200', 'absolute inset-0 h-full w-full flex items-center justify-center transition-opacity']"
                                                                            aria-hidden="true">
                                                                            <svg class="h-3 w-3 text-gray-400" fill="none"
                                                                                viewBox="0 0 12 12">
                                                                                <path d="M4 8l2-2m0 0l2-2M6 6L4 4m2 2l2 2"
                                                                                    stroke="currentColor" stroke-width="2"
                                                                                    stroke-linecap="round"
                                                                                    stroke-linejoin="round" />
                                                                            </svg>
                                                                        </span>
                                                                        <span
                                                                            :class="[customPlanFeatures[feature.key]['value'] ? 'opacity-100 ease-in duration-200' : 'opacity-0 ease-out duration-100', 'absolute inset-0 h-full w-full flex items-center justify-center transition-opacity']"
                                                                            aria-hidden="true">
                                                                            <svg class="h-3 w-3 text-primary-900"
                                                                                fill="currentColor" viewBox="0 0 12 12">
                                                                                <path
                                                                                    d="M3.707 5.293a1 1 0 00-1.414 1.414l1.414-1.414zM5 8l-.707.707a1 1 0 001.414 0L5 8zm4.707-3.293a1 1 0 00-1.414-1.414l1.414 1.414zm-7.414 2l2 2 1.414-1.414-2-2-1.414 1.414zm3.414 2l4-4-1.414-1.414-4 4 1.414 1.414z" />
                                                                            </svg>
                                                                        </span>
                                                                    </span>
                                                                </Switch>
                                                                <!-- <SwitchGroup
                                                                as="div"
                                                                class="flex justify-between"
                                                            >
                                                                <Switch
                                                                    v-model="customPlanFeatures[feature.key]['value']"
                                                                    :class="[customPlanFeatures[feature.key]['value'] ? 'bg-insta-primary' : 'bg-gray-200', 'relative inline-flex flex-shrink-0 h-6 w-11 border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 focus:outline-none']">
                                                                    <span aria-hidden="true" :class="[customPlanFeatures[feature.key]['value'] ? 'translate-x-5' : 'translate-x-0', 'pointer-events-none inline-block h-5 w-5 rounded-full bg-white shadow transform ring-0 transition ease-in-out duration-200']" />
                                                                </Switch>
                                                            </SwitchGroup>
                                                            <span
                                                                v-if="customPlanFeatures[feature.key]['value']"
                                                                class="cursor-pointer"
                                                                @click="toggleBooleanFeature(feature.key)"
                                                            >
                                                                <svg class="h-6 w-6 text-insta-primary" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20"><path clip-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" fill-rule="evenodd"></path></svg>
                                                            </span>
                                                            <span v-else
                                                                class="cursor-pointer"
                                                                @click="toggleBooleanFeature(feature.key)">
                                                                <svg class="h-6 w-6 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                                            </span> -->
                                                            </span>
                                                        </template>
                                                    </td>
                                                </template>

                                            </tr>
                                        </template>
                                    </tbody>
                                    <tfoot>
                                        <tr class="border-t border-gray-200">
                                            <th class="sr-only" scope="row">{{ trans('lang.labels.choose_your_plan') }}</th>
                                            <td :class="{
                                                'border-2 border-green-300': plan.type === popular && interval === 2,
                                                'border-2 border-red-300': plan.type === popular && interval === 1,
                                                'blink-border': show && plan.type === activePlan.type
                                            }" v-for="(plan) in visiblePlans" :key="plan.type" class="py-4 px-6">

                                                <inertia-link :href="route('card.get')"
                                                    @click="appStore.selectedPlan = plan"
                                                    v-if="plan.type !== freePlanType && !cardLastFour && !(appliedCouponDetails && appliedCouponDetails.hasOwnProperty('allow_woc') && appliedCouponDetails.allow_woc)"
                                                    aria-label="Add Card"
                                                    class="block w-full bg-green-800 border border-green-800 rounded-md py-2 text-sm font-semibold text-white text-center hover:bg-green-900">
                                                    {{ trans('lang.labels.buy_plan') }}
                                                </inertia-link>

                                                <inertia-link :href="route('subscription.cancel')"
                                                    v-if="plan.type !== freePlanType &&
                                                        plan.type === activePlan.type && activeSubscription && endsAt === null" aria-label="Cancel Subscription"
                                                    class="block w-full bg-red-800 border border-red-800 rounded-md py-2 text-sm font-semibold text-white text-center hover:bg-red-900">
                                                    {{ trans('lang.labels.cancel_plan') }}
                                                </inertia-link>

                                                <button type="submit" :class="{ 'opacity-25': loading === plan.type }"
                                                    @click="onUpdateCustomPlan"
                                                    v-if="plan.type !== freePlanType && (cardLastFour || (appliedCouponDetails && appliedCouponDetails.hasOwnProperty('allow_woc') && appliedCouponDetails.allow_woc)) && plan.type === activePlan.type && activeSubscription && endsAt === null && plan.user_id && companyCustomPlan"
                                                    class="block w-full border bg-insta-primary rounded-md py-2 text-sm font-semiboldtext-white text-center bottom-0 flex-grow focus:outline-none focus:ring-2 focus:ring-offset-2">
                                                    {{ trans('lang.labels.update_plan') }}
                                                </button>

                                                <inertia-link :href="route('subscription.resume')"
                                                    v-if="plan.type !== freePlanType &&
                                                        plan.type === activePlan.type && !activeSubscription && endsAt !== null" aria-label="Resume Subscription"
                                                    class="block w-full bg-indigo-800 border border-indigo-800 rounded-md py-2 text-sm font-semibold text-white text-center hover:bg-indigo-900">
                                                    {{ trans('lang.labels.resume_plan') }}
                                                </inertia-link>

                                                <button type="button" :class="{ 'opacity-25': loading === plan.type }"
                                                    :disabled="loading === plan.type" @click="confirm(plan)"
                                                    v-if="plan.type !== freePlanType && (cardLastFour || (appliedCouponDetails && appliedCouponDetails.hasOwnProperty('allow_woc') && appliedCouponDetails.allow_woc)) && plan.type !== activePlan.type"
                                                    class="block w-full bg-green-800 border border-green-800 rounded-md py-2 text-sm font-semibold text-white text-center hover:bg-green-900">
                                                    {{ trans('lang.labels.choose_plan') }}
                                                </button>


                                            </td>

                                        </tr>
                                    </tfoot>
                                </table>
                            </form>
                        </div>

                    </div>
                </div>
                <app-faq></app-faq>
            </div>
        </div>
    </app-layout>

    <!-- Subscribe Current Plan -->
    <jet-confirmation-modal :show="showUpdateSubscription" alertType="info" :closeable="false"
        @close="showUpdateSubscription = false">
        <template #title>
            {{ trans('lang.labels.are_you_sure') }}
        </template>

        <template #content>
            <span v-html="upgradeMessage"></span>
        </template>

        <template #footer>
            <jet-secondary-button @click="showUpdateSubscription = false">
                <span class="bg-btnCancelBg text-btnCancelText px-4 py-2 cursor-pointer rounded-lg">
                    {{ trans('lang.labels.cancel') }}
                </span>
            </jet-secondary-button>

            <jet-primary-button class="ml-2" @click="updateSubscription" :class="{ 'opacity-25': loading }"
                :disabled="loading">
                <span class="bg-btnSubmitBg text-btnSubmitText px-4 py-2 cursor-pointer rounded-lg">
                    {{ trans('lang.labels.confirm') }}
                </span>
                <!-- <span v-if="selectedPlan.price > activePlan.price">Upgrade</span> -->
                <!-- <span v-else-if="selectedPlan.price < activePlan.price">Downgrade</span> -->
            </jet-primary-button>
        </template>
    </jet-confirmation-modal>

    <jet-dialog-modal :show="errorMessage" @close="errorMessage = null">
        <template #title>
            {{ errorMessage.title }}
        </template>

        <template #content>
            <span v-html="errorMessage.message"></span>
        </template>

        <template #footer>
            <jet-primary-button @click="errorMessage = null">
                {{ trans('lang.labels.ok') }}
            </jet-primary-button>
        </template>
    </jet-dialog-modal>

    <jet-confirmation-modal :show="retryPayment" alertType="info" :closeable="false" @close="retryPayment = false">
        <template #title>
            {{ trans('lang.labels.retry_payment') }}
        </template>

        <template #content>
            {{ trans('lang.labels.last_time') }}
        </template>

        <template #footer>
            <jet-secondary-button @click="retryPayment = false; deletePayment = true;">
                {{ trans('lang.labels.cancel') }}
            </jet-secondary-button>

            <jet-primary-button class="ml-2" @click="goToRetryPayment" :class="{ 'opacity-25': retrying }"
                :disabled="retrying">
                {{ trans('lang.labels.retry_payment') }}
            </jet-primary-button>
        </template>
    </jet-confirmation-modal>

    <jet-confirmation-modal :show="deletePayment" alertType="info" :closeable="false" @close="deletePayment = false">
        <template #title>
            {{ trans('lang.labels.cancel_retry_payment') }}
        </template>

        <template #content>
            {{ trans('lang.labels.sure_cancel') }}
        </template>

        <template #footer>
            <jet-secondary-button @click="deletePayment = false; retryPayment = true;">
                {{ trans('lang.labels.go_back') }}
            </jet-secondary-button>

            <jet-danger-button class="ml-2" @click="cancelIncompletePayment" :class="{ 'opacity-25': retrying }"
                :disabled="retrying">
                {{ trans('lang.labels.yes_cancel') }}
            </jet-danger-button>
        </template>
    </jet-confirmation-modal>
</template>
    
<script>
import AppLayout from "@/Layouts/AuthenticatedLayout.vue";
import { getItemCost } from "@/helpers";
import { sections } from "@/features";
import JetConfirmationModal from '@/Common/ConfirmationModal.vue'
import JetDialogModal from '@/Common/DialogModal.vue'
import Url from "@/url";
import AppFaq from "@/Components/AppFaq.vue";
import SubscriptionMixin from "@/Mixins/SubscriptionMixin";
import { mapState, mapStores } from 'pinia';
import { useAppStore } from '@/store/index';
import { featuresObj, getPlanPriceObj, mapFeaturesWithPlanDescObj, getPlanDescObj } from '@/features.js';
import { Switch, SwitchGroup, SwitchLabel } from '@headlessui/vue';
import GeneralMixin from "@/Mixins/GeneralMixin";
import lockImg from "@/ImageComponents/pricing-alert/lock-img.vue";
import crossImg from "@/ImageComponents/pricing-alert/cross-img.vue";
import TimerCount from "@/Common/TimerCount.vue"

export default {
    mixins: [GeneralMixin, SubscriptionMixin],
    props: {
        activePlan: {
            type: Object,
            default: null
        },
        popularPlanType: {
            type: Object,
            default: null
        },
        plans: {
            default: []
        },
        cardLastFour: {
            type: String,
            default: null
        },
        activeSubscription: {
            type: Boolean,
            default: false
        },
        endsAt: {
            type: Number,
            default: null
        },
        incompletePaymentUrl: {
            type: String,
            default: ''
        },
        companyCustomPlan: {
            type: Object,
            default: null
        },
        activeSubscriptionDetails: {
            type: Object,
            default: null
        },
        user: {
            type: Object,
            default: null
        },
        periodicDiscount: {
            type: Object,
            default: null
        },
        periodicDiscountAvailable: {
            type: Boolean,
            default: false
        },
        unlockProgramCouponCode: {
            type: Object,
            default: {}
        },
    },
    components: {
        AppLayout,
        JetConfirmationModal,
        JetDialogModal,
        AppFaq,
        Switch,
        SwitchGroup,
        SwitchLabel,
        lockImg,
        crossImg,
        TimerCount
    },
    inheritAttrs: false,
    data: () => ({
        app: {},
        sections,
        freePlanType: 'free',
        visiblePlans: [],
        interval: 2, // yearly
        show: false,
        showUpdateSubscription: false,
        loading: null,
        message: `<p>You are upgrading your plan <strong>from <source_plan> to <destination_plan></strong>, you will be charged <strong>$<plan_price> on monthly</strong> basis. You can cancel the plan any time you wish. Do you wish to continue?</p>`,
        errorMessage: null,
        retryCount: 0,
        selectedPlan: null,
        verifying: false,
        retryPayment: false,
        retrying: false,
        deletePayment: false,
        showCodeInput: false,
        selectedCouponCode: '',
        processing: false,
        customPlanName: '',
        customPlanFeatures: [],
        popular: null,
        isYearMonthSwitchVisible: true,
        featureToHighLight: null,
        currency: import.meta.env.VITE_CASHIER_CURRENCY,
        isOpenAlert: false,
        isCloseAlert: true,
        unlockFreeCouponProcessing: false,
        periodicDiscountData: null,
        unlockProgramTextBeforeUnlock: import.meta.env.VITE_UNLOCK_PROGRAM_TEXT_BEFORE,
        unlockProgramTextAfterUnlock: import.meta.env.VITE_UNLOCK_PROGRAM_TEXT_AFTER,
        unlockProgramImage: import.meta.env.VITE_UNLOCK_PROGRAM_IMAGE,
    }),
    mounted() {
        this.appStore.userLogin(this.user);
        this.app = window.APP;

        if (!this.companyCustomPlan) {
            this.plans.sort((a, b) => (a.price > b.price) ? 1 : -1);
        }
        this.yearlyPlanMonthlyCost();
        this.categorizePlans();

        if (this.activePlan && this.activePlan.type !== this.freePlanType) {
            this.highlightActivePlan();
        }

        this.periodicDiscountData = this.$props.periodicDiscount
        if (this.periodicDiscountAvailable && this.periodicDiscountData) {
            this.isOpenAlert = true,
                this.isCloseAlert = false;

            setTimeout(() => {
                this.showCodeInput = true
                this.appStore.changeCouponCode(this.$props.unlockProgramCouponCode.coupon_code)
                this.applyCoupon()
            }, 500);
        }

        if (this.appStore.selectedPlan && this.appStore.cardIsActivated) {
            this.selectedPlan = this.appStore.selectedPlan;
            this.appStore.selectedPlan = null;
            this.updateMessage(this.selectedPlan);
            this.showUpdateSubscription = true;
            delete (this.appStore.cardIsActivated);
        }

        // if user already have custom plan then replace features.
        if (this.companyCustomPlan) {
            this.plans.filter(plan => plan.type === this.freePlanType || plan.interval === this.interval);
            this.popular = null;
            mapFeaturesWithPlanDescObj(featuresObj, this.companyCustomPlan.description);
            this.customPlanName = this.companyCustomPlan.name;
        } else {
            mapFeaturesWithPlanDescObj(featuresObj, this.activePlan.description);
        }

        this.customPlanFeatures = featuresObj;


        this.errorMessage = null;

        if (this.endsAt === null) {
            this.url = new Url();
            const message = this.url.queryParams('message');
            const success = this.url.queryParams('success');
            if (message === 'The payment was successful.' && success) {
                this.verifySuccessPayment();
            }
        }

        if (this.incompletePaymentUrl !== '') {
            this.retryPayment = true;
        }

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
    computed: {
        couponCode() {
            return this.appStore.coupon.coupon_code;
        },
        couponName() {
            return this.appStore.coupon.name;
        },
        isPlanCustomizerAllowed() {
            return this.$page.props.auth.user.allow_plan_customizer
        },
        ...mapStores(useAppStore),
        ...mapState(useAppStore, { "couponApplied": "shouldCouponApply", "appliedCouponDetails": "appliedCoupon" }),
        customPlanPrice() {
            return 0
        }
    },
    watch: {
        appliedCouponDetails(value) {
            if (value.hasOwnProperty('interval') && value.interval) {
                this.interval = value.interval
                this.categorizePlans();
            }
        }
    },
    methods: {
        onTimeExpires() {
            this.removeCoupon()
            this.isOpenAlert = false;
        },
        scrollToElement(scrollToMe) {
            const el = this.$refs['desktop-' + scrollToMe];
            if (el && el[0]) {
                setTimeout(() => {
                    let element = el[0]
                    let headerOffset = 100;
                    let elementPosition = element.getBoundingClientRect().top;
                    let offsetPosition = elementPosition + window.pageYOffset - headerOffset;

                    window.scrollTo({
                        top: offsetPosition,
                        behavior: "smooth"
                    });
                }, 500);
            }
        },
        toggleBooleanFeature(featureKey) {
            this.customPlanFeatures[featureKey]['value'] = !(this.customPlanFeatures[featureKey]['value']);
        },
        onUpdateCustomPlan() {
            if (!this.customPlanName) {
                let notification = {
                    heading: this.trans('lang.labels.failed') + '!',
                    subHeading: this.trans('lang.message.please_enter_name_the_plan'),
                    type: "error",
                };
                this.appStore.setNotification(notification);
                return false;
            } else if (this.customPlanName.length < 3) {
                let notification = {
                    heading: this.trans('lang.labels.failed') + '!',
                    subHeading: this.trans('lang.message.the_plan_name_should_be_characters_long', { planCharacters: 3 }),
                    type: "error",
                };
                this.appStore.setNotification(notification);
                return false;
            }
            let plan = this.mockPlan();
            plan.name = this.customPlanName;
            plan.description = this.customPlanFeatures;
            this.confirm(plan);
            return false;
        },
        updatePlanNameAndDesc() {
            let that = this;
            axios.put('/plan-customizer', {
                features: this.customPlanFeatures,
                plan_name: this.customPlanName
            }).then((response) => {
                let plan = response.data.plan;
                that.confirm(plan);
            }).catch((error) => {
                if (error.response.status === 404 && error.response.data && error.response.data.message) {
                    this.error = error.response.data.message;
                } else if (error.response.status === 400 && error.response.data) {
                    if (error.response.data.message) {
                        this.error = error.response.data.message;
                    }
                    if (error.response.data.resume) {
                        this.error = error.response.data.resume;
                    }
                } else {
                    console.log(error.response);
                }
            });

        },
        onCreateCustomPlan() {
            if (!this.customPlanName) {
                let notification = {
                    heading: this.trans('lang.labels.failed') + '!',
                    subHeading: this.trans('lang.message.please_enter_name_the_plan'),
                    type: "error",
                };
                this.appStore.setNotification(notification);
                return false;
            } else if (this.customPlanName.length < 3) {
                let notification = {
                    heading: this.trans('lang.labels.failed') + '!',
                    subHeading: this.trans('lang.message.the_plan_name_should_be_characters_long', { planCharacters: 3 }),
                    type: "error",
                };
                this.appStore.setNotification(notification);
                return false;
            }

            let plan = this.mockPlan();
            plan.name = this.customPlanName;
            plan.description = this.customPlanFeatures;
            this.confirm(plan);
        },
        updateCouponCode(e) {
            this.appStore.changeCouponCode(e.target.value)
        },
        updatePriceBasedOnCoupon(price, plan) {
            if (plan.interval == 1) {
                return Math.max(0, getItemCost(price, this.appStore.coupon.discount_type, this.appStore.coupon.discount_value));
            } else {
                let yearlyCost = getItemCost(plan.yearlyPrice, this.appStore.coupon.discount_type, this.appStore.coupon.discount_value);
                return Math.max(0, yearlyCost).toFixed(2);
            }
        },
        toggleApplyCoupon() {
            this.appStore.toggleCouponApplied();
        },
        applyCoupon() {
            let self = this
            let postData = {
                coupon_code: self.couponCode,
            }
            self.processing = true
            axios
                .post("/api/validate-coupon", postData)
                .then(response => {
                    let coupon = response.data.coupon
                    self.appStore.applyCoupon(coupon);
                }).catch(function (error) {
                    if (error.response.status != 422) {
                        let message = error.response.data.message || error.message
                        self.errorMessage = {
                            title: self.trans('lang.message.coupon_error'),
                            message: message
                        }
                    }
                })
                .finally(() => {
                    self.processing = false
                });
        },
        removeCoupon() {
            this.selectedCouponCode = ''
            this.appStore.removeCoupon();
        },
        yearlyPlanMonthlyCost() {
            this.plans.forEach(plan => {
                if (plan.interval === 2 && !plan.yearlyPlanMonthlyCostCalculated) {
                    const monthly = plan.price / 12;
                    plan.yearlyPrice = plan.price;
                    plan.price = Math.round(monthly * 100) / 100;
                    plan.yearlyPlanMonthlyCostCalculated = true
                    if (plan.old_price) {
                        const monthlyOld = plan.old_price / 12;
                        plan.old_price = Math.round(monthlyOld * 100) / 100;
                    }

                }
            });

            if (this.active && this.active.type !== this.freePlanType && this.active.interval === 2) {
                const monthly = this.active.price / 12;
                this.active.yearlyPrice = this.active.price;
                this.active.price = Math.round(monthly * 100) / 100;
            }
        },
        closeModal() {
            this.confirmingUserDeletion = false
        },
        goToRetryPayment() {
            this.retrying = true;
            location.href = this.incompletePaymentUrl;
        },
        cancelIncompletePayment() {
            this.retrying = true;
            axios.delete(`${this.app.domain}/subscription/incomplete`).then((response) => {
                this.retrying = false;
                this.$inertia.visit(this.app.domain + '/subscription/plans');
            }).catch((error) => {
                this.retrying = false;
                this.deletePayment = true;
                if (error.response.status === 404 && error.response.data && error.response.data.message) {
                    this.errorMessage = {
                        title: this.trans('lang.message.subscription_error'),
                        message: error.response.data.message
                    }
                } else {
                    console.log(error.response);
                }
            });
        },
        verifySuccessPayment() {
            this.errorMessage = null;
            this.verifying = true;
            axios.get(`${this.app.domain}/subscription/verify`).then((response) => {
                const status = response.data.status;
                this.verifying = false;
                if (status) {
                    this.removeCoupon()
                    this.$inertia.visit(this.app.domain + '/subscription/plans');
                    this.retryCount = 0;
                } else {
                    this.retryCount++;
                    if (this.retryCount <= 10) {
                        setTimeout(() => this.verifySuccessPayment(), 1000);
                    } else {
                        this.errorMessage = {
                            title: this.trans('lang.message.transaction_failed'),
                            message: this.trans('lang.message.any_amount_deducted_refunded')
                        }
                    }
                }
            }).catch((error) => {
                this.verifying = false;
                if (error.response.status === 404 && error.response.data && error.response.data.message) {
                    this.errorMessage = {
                        title: this.trans('lang.message.subscription_error'),
                        message: error.response.data.message
                    }
                } else {
                    console.log(error.response);
                }
            });
        },
        highlightActivePlan() {
            this.interval = this.activePlan.interval;
            this.categorizePlans();
            this.show = true;
            setTimeout(() => this.show = false, 5000);
        },
        mockPlan() {
            let user = this.$page.props.auth.user;
            let customPrice = getPlanPriceObj(this.customPlanFeatures)
            return {
                "user_id": user.id,
                "type": `custom-${user.id}`,
                "interval": this.interval,
                'name': `${user.name}'s custom plan`,
                'description': getPlanDescObj(this.customPlanFeatures),
                'yearlyPrice': this.interval == 2 ? (((customPrice * 12) - (customPrice * 2))).toFixed(2) : undefined,
                'price': customPrice
            }
        },
        categorizePlans() {
            this.visiblePlans = this.plans.filter(plan => plan.type === this.freePlanType || plan.interval === this.interval);
            this.popular = this.popularPlanType[this.interval + '_0'];
        },
        updateMessage(plan) {
            let message = this.message.replace('<source_plan>', `${this.activePlan.name}`);
            message = message.replace('<destination_plan>', `${plan.name}`);

            if (typeof plan.yearlyPrice !== 'undefined') {
                let planPrice = ((this.couponApplied && (this.appliedCouponDetails.plan_ids.findIndex((planID) => planID == plan.stripe_plan_id) != -1 || this.appliedCouponDetails.plan_ids.length == 0)) ? getItemCost(plan.yearlyPrice, this.appStore.coupon.discount_type, this.appStore.coupon.discount_value) : plan.yearlyPrice);
                message = message.replace('<plan_price>', `${planPrice}`);
                message = message.replace('monthly', 'yearly');
            } else {
                let planPrice = (this.couponApplied && (this.appliedCouponDetails.plan_ids.findIndex((planID) => planID == plan.stripe_plan_id) != -1 || this.appliedCouponDetails.plan_ids.length == 0) ? getItemCost(plan.price, this.appStore.coupon.discount_type, this.appStore.coupon.discount_value) : plan.price);
                message = message.replace('<plan_price>', `${planPrice}`);
            }

            this.upgradeMessage = message;
        },
        setInterval() {
            this.interval = this.interval === 1 ? 2 : 1;
            this.categorizePlans();
        },
        confirm(plan) {
            this.selectedPlan = plan
            this.updateMessage(plan);
            this.showUpdateSubscription = true;
        },
        updateSubscription() {
            const plan = this.selectedPlan;
            this.loading = plan.type;
            this.errorMessage = null;
            let referral = this.getClientReferenceId();
            axios.patch(this.app.domain + '/subscription/plans', {
                plan: plan.type,
                interval: plan.interval,
                is_custom_plan: false,
                coupon_code: this.couponApplied && (this.appliedCouponDetails.plan_ids.findIndex((planID) => planID == plan.stripe_plan_id) != -1) ? this.couponCode : null,
                referral
            }).then((response) => {
                this.loading = null;
                this.showUpdateSubscription = false;
                this.removeCoupon()
                if (response.data.link) {
                    window.location.href = response.data.link;
                } else {
                    let price = ''
                    if (typeof plan.yearlyPrice !== 'undefined') {
                        price = this.couponApplied && (this.appliedCouponDetails.plan_ids.findIndex((planID) => planID == plan.stripe_plan_id) != -1) ? getItemCost(plan.yearlyPrice, this.appStore.coupon.discount_type, this.appStore.coupon.discount_value) : plan.yearlyPrice;
                    } else {
                        price = this.couponApplied && (this.appliedCouponDetails.plan_ids.findIndex((planID) => planID == plan.stripe_plan_id) != -1) ? getItemCost(plan.price, this.appStore.coupon.discount_type, this.appStore.coupon.discount_value) : plan.price;
                    }
                    // let gtmData = {
                    //     'event': 'subscription_purchase',
                    //     'plan_type': plan.interval == 1 ? 'monthly' : 'yearly',
                    //     'coupon': this.couponApplied && (this.appliedCouponDetails.plan_ids.findIndex((planID) => planID == plan.stripe_plan_id) != -1) ? this.couponCode : '',
                    //     'plan_change_from': this.activePlan.name,
                    //     'currency': this.currency,
                    //     'value': price,
                    //     'items': [
                    //         {
                    //             'item_name': plan.name,
                    //             'price': price
                    //         }
                    //     ]
                    // }
                    // window.dataLayer.push(gtmData)
                    window.location.href = '/subscriptions';
                    // this.$inertia.visit(this.app.domain + '/subscriptions');
                }
            })
                .catch((error) => {
                    this.loading = null;
                    this.showUpdateSubscription = false;
                    if (error.response.status === 422 && error.response.data && error.response.data.errors) {
                        const messages = error.response.data.errors;
                        if (messages.plan && messages.plan.length > 0) {
                            this.error = messages.plan[0];
                            this.errorMessage = {
                                title: this.trans('lang.message.payment_error'),
                                message: error.response.data.message
                            }
                        } else if (messages.link) {
                            this.errorMessage = messages
                        }
                    }
                });
        },
        unlockAlert() {
            this.isOpenAlert = true,
                this.isCloseAlert = false;
        },
        unlockFreeUserDiscount() {
            this.unlockFreeCouponProcessing = true
            let self = this
            axios.post('/api/v2/periodic-discounts', {
                coupon_id: this.$props.unlockProgramCouponCode.id
            }).then(function (response) {
                self.unlockFreeCouponProcessing = false;
                self.periodicDiscountData = response.data.data
                self.isOpenAlert = true;
                self.isCloseAlert = false;

                self.showCodeInput = true
                self.appStore.changeCouponCode(self.$props.unlockProgramCouponCode.coupon_code)
                self.applyCoupon()
            }).catch(function (error) {
                self.unlockFreeCouponProcessing = false;
            })
        }
    }
}
</script>
<style scoped>
.blink-plan {
    animation: blink-element 2s;
    animation-iteration-count: 2;
}

.custom-img {
    background-image: url(../../images/pricing-alert/background1.png), url(../../images/pricing-alert/background2.png);
    background-repeat: no-repeat;
    background-position: 100%;

}

.custom-background {
    background-image: linear-gradient(90deg, #FE8551 0%, #ED618E 100%);
}

.custom-gradient-img {
    background-image: url(../../images/pricing-alert/background4.png);
    background-repeat: no-repeat;
    background-position: center;
}

.custom-gradient-img2 {
    background-image: url(../../images/pricing-alert/background3.png);
    background-repeat: no-repeat;
    background-position: right;
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


.header-outline {
    outline: 2px solid transparent;
}

.outline-green {
    outline: 2px solid rgba(134, 239, 172, 1) !important;
}

.outline-red {
    outline: 2px solid rgba(252, 165, 165, 1) !important;
}
</style>    