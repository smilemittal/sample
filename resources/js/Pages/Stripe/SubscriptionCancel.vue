<template>
<InertiaHead>
    <title>{{ trans('lang.labels.cancel_subscription') }} - xme</title>
</InertiaHead>
    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-gray-900 text-3xl leading-9 font-bold">{{ trans('lang.labels.cancel_my_subscription') }}</h2>
        </template>
        <div class="py-8">
            <div class="max-w-7xl mx-auto bg-white py-8 sm:px-6 lg:px-8">
                <div class="flex flex-col">
                    <p class="font-base text-sm text-gray-800 mb-1 ml-1">
                        {{ trans('lang.labels.specific_reason_behind_cancellation') }}
                    </p>
                    <form
                        id="cancel-subscription-form"
                        :action="app.domain + '/subscription/cancel'"
                        @submit.prevent="cancel">
                        <div class="my-4">
                            <div class="rounded-md shadow-sm">
                                <select
                                    v-model="reason"
                                    class="block form-select transition duration-150 ease-in-out text-sm leading-5 focus:ring-1 focus:ring-primary-900 focus:outline-none focus:border-primary-900 rounded">
                                    <option :value="reason.id"
                                            v-for="reason in reasons" :key="reason.id">
                                        {{ reason.message }}
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="mt-1" v-if="reason === 3">
                            <div class="rounded-md shadow-sm">
                                <textarea
                                    v-model="otherReason"
                                    class="form-textarea mt-1 block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5"
                                    :placeholder="trans('lang.labels.i_am_canceling_because')"
                                    rows="5"
                                    style="resize: none;">
                                </textarea>
                            </div>
                        </div>
                        <div class="mt-5 sm:mt-6 sm:grid sm:grid-cols-2 sm:gap-3 sm:grid-flow-row-dense sm:w-1/2 w-full">
                            <div class="relative">
                                <button
                                    :class="{'opacity-25': cancelling}"
                                    :disabled="cancelling"
                                    @click.prevent="nextStep"
                                    type="button"
                                    class="w-full inline-flex items-center justify-center px-10 py-2 border border-transparent text-sm leading-5 font-medium rounded text-white bg-red-600 hover:bg-red-500 focus:ring-red-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:border-red-700 focus:shadow-outline-red active:bg-red-700 transition ease-in-out duration-150">
                                    {{ trans('lang.labels.proceed_cancellation') }}
                                </button>
                            </div>

                            <inertia-link
                                :href="route('pricing')"
                                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:ring-red-600 focus:outline-none focus:ring-2 focus:ring-offset-2 sm:mt-0 sm:w-auto sm:text-sm">
                                {{ trans('lang.labels.do_not_want_to_cancel') }}
                            </inertia-link>
                        </div>
                        <p v-show="error.length > 0" class="ml-1 text-xs text-red-600">
                            {{ error }}
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>

    <jet-confirmation-modal :show="cancellationFlow" :closeable="false" alertType="warning"
                            @close="cancellationFlow = false">
        <template #title>
            {{ trans('lang.labels.Were_sorry_see_you') }}
        </template>

        <template #content>
            <span v-html="warningMessage()"></span>
        </template>

        <template #footer>
            <jet-secondary-button
                @click="$inertia.visit(this.app.domain + '/subscription/plans'); cancellationFlow = false;">
                {{ trans('lang.labels.go_back') }}
            </jet-secondary-button>

            <jet-danger-button class="ml-2" @click="cancel" :class="{ 'opacity-25': cancelling }"
                               :disabled="cancelling">
                {{ trans('lang.labels.cancel_subscription') }}
            </jet-danger-button>
        </template>
    </jet-confirmation-modal>

</template>
<script>


import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {sections} from "@/features";
import JetConfirmationModal from '@/Common/ConfirmationModal.vue'
import JetDangerButton from '@/Components/DangerButton.vue'
import JetDialogModal from '@/Common/DialogModal.vue'
import JetSecondaryButton from '@/Components/SecondaryButton.vue'
import JetPrimaryButton from '@/Components/PrimaryButton.vue';

export default {
    props: {
        activePlan: {
            type: Object,
            default: null
        },
        activeSubscription: {
            type: Boolean,
            default: false
        },
        endsAt: {
            type: String,
            default: null
        }
    },
    components: {
        AuthenticatedLayout,
        JetConfirmationModal,
        JetDangerButton,
        JetPrimaryButton,
        JetSecondaryButton,
        JetDialogModal
    },
    inheritAttrs: false,
    data: () => ({
        app: {},
        error: '',
        reason: 0,
        otherReason: null,
        reasons: [
            {id: 0, message: '--Select A Reason--'},
            {id: 1, message: 'Not using anymore'},
            {id: 2, message: 'Expensive'},
            {id: 3, message: 'Others'}
        ],
        sections: sections,
        cancelling: false,
        cancellationFlow: false,
    }),
    mounted() {
        this.app = window.APP;
        if (!this.activeSubscription) {
            this.$inertia.visit(this.app.domain + '/subscription/plans');
        }
    },
    methods: {
        validate() {
            this.error = '';
            if (this.reason === 0) {
                this.error = this.trans('lang.message.validate_error_fields_required')
                return false;
            }
            if (this.reason === 3 && this.otherReason === null) {
                this.error = this.trans('lang.message.please_write_reason_text_field')
                return false;
            }
            if (this.activePlan === null) {
                this.error = this.trans('lang.messages.something_went_wrong_later')
                return false;
            }
            return true;
        },
        nextStep() {
            const status = this.validate();
            if (!status || this.activePlan === null) {
                return;
            }
            this.cancellationFlow = true;
        },
        warningMessage() {
            let warning = "<div class='text-justify text-gray-600'>" +
                "   Your monthly subscription is paid until " + this.endsAt + ". If you would like to proceed with cancelling your subscription, " +
                "   please select \"Cancel Subscription\" below." +
                "</div>" +
                "<div class=\"mt-6 text-justify rounded-md bg-yellow-50 p-4\">" +
                "  <div class=\"flex\">" +
                "    <div class=\"flex-shrink-0\">" +
                "      <svg class=\"h-5 w-5 text-yellow-400\" viewBox=\"0 0 20 20\" fill=\"currentColor\" aria-hidden=\"true\">\n" +
                "        <path fill-rule=\"evenodd\" d=\"M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z\" clip-rule=\"evenodd\" />\n" +
                "      </svg>" +
                "    </div>" +
                "    <div class=\"ml-3\">" +
                "      <h3 class=\"text-sm font-medium text-yellow-800\">" +
                "        Are you sure? After " + this.endsAt + " you will loose access to following features." +
                "      </h3>" +
                "      <div class=\"mt-2 text-sm text-yellow-700\">" +
                "        <ul class=\"list-disc pl-5 space-y-1\">";

            if (this.activePlan.deleted_at !== null) {
                warning += '<li>Loose access to early bird `' + this.activePlan.name + ' Plan` at $' + this.activePlan.price + '</li>';
            }

            const features = [];
            this.sections.forEach((section) => {
                section.features.forEach((feature) => {
                    if (typeof this.activePlan.description[feature.key] === 'number') {
                        features.push(`Up to ${this.activePlan.description[feature.key]} ${feature.name}`);
                    } else if (this.activePlan.description[feature.key]) {
                        features.push(feature.name);
                    }
                });
            });

            warning += '<li>' + features.join('</li><li>') + '</li>';
            warning += '</ul></div></div></div></div>';

            return warning;
        },
        cancel() {
            let that = this
            this.cancelling = true;
            axios.put(document.getElementById('cancel-subscription-form').action, {
                reason: this.reasons[this.reason].message,
                other: this.otherReason
            }).then((response) => {
                window.dataLayer.push({'event': 'subscription_cancel','plan_name':that.activePlan.name,'cancel_reason':(that.reason == 3)?that.otherReason:that.reasons[that.reason].message})
                this.cancelling = false;
                this.$inertia.visit(this.app.domain + '/subscription/plans');
            }).catch((error) => {
                this.cancelling = false;
                this.cancellationFlow = false;
                if (error.response.status === 404 && error.response.data && error.response.data.message) {
                    this.error = error.response.data.message;
                } else if (error.response.status === 400 && error.response.data) {
                    if (error.response.data.message) {
                        this.error = error.response.data.message;
                    }
                    if (error.response.data.cancel) {
                        this.error = error.response.data.cancel;
                    }
                } else {
                    if (error.response.data.message) {
                        this.error = error.response.data.message;
                    }
                    console.log(error.response);
                }
            });
        },
    }
}
</script>
