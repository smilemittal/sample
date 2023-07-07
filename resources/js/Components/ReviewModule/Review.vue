<template>
    <!-- Main modal -->
    <div id="deleteCompany"
        class="bg-bgModal fixed top-0 flex items-center justify-center left-0 right-0 z-50 w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-modal md:h-full">
        <div class="relative w-full h-auto max-w-xl md:h-auto">
            <!-- Modal content -->
            <div class="relative rounded-lg shadow bg-body">
                <!-- Modal header -->
                <div class="flex items-start justify-between p-4 rounded-t">
                    <div>
                        <h3 class="text-xl font-semibold text-gray-300">
                            {{ trans('lang.labels.review_date') }}
                        </h3>
                        <h3 class="text-base text-gray-400 mt-2">
                            {{ trans('lang.labels.select_the_next_review_date') }}
                        </h3>
                    </div>
                    <button type="button" @click="closeReviewDate()" class="">
                        <XCircleIcon class="h-9 w-9 text-white" aria-hidden="true" />
                    </button>
                </div>

                    <div class="py-6 px-4 space-y-6 overflow-y-auto theme-modal-body">
                        <p class="text-base text-neutral-300">{{ trans('lang.labels.current_review_date') }} {{ data.reviewDate }} </p>

                        <div class="grid md:grid-cols-2 sm:grid-cols-1 gap-3">
                            <div class="relative">
                                <label for="business_member"
                                    class="block mb-2 text-sm font-medium text-neutral-100 dark:text-white">
                                    {{ trans('lang.labels.choose_review_date') }}
                                </label>
                                <select id="business_member" v-model="reviewDate"
                                    class="bg-sidebar border border-neutral-300 text-neutral-100 text-sm rounded-lg block w-full p-2.5
                                            dark:focus:border-amber-500 focus:outline-none focus:ring-0 focus:border-amber-500">
                                    <option selected>Select Next Review Date</option>
                                    <option value="1">For next 6 months</option>
                                    <option value="2">For next 12 months</option>
                                    <option value="3">Custom Date</option>
                                </select>
                                <span v-if="errors.review_date" class="mt-2 text-sm text-red-600 theme-error-message">{{ trans('lang.labels.please_select_the_review_date') }}</span>
                            </div>
                           
                            <div class="relative" v-if="reviewDate == 3">
                                <label for="business_member"
                                    class="block mb-2 text-sm font-medium text-neutral-100 dark:text-white">
                                    {{ trans('lang.labels.review_date') }}
                                </label>
                                <input type="date" id="review_date"
                                    class="block px-2.5 pb-2.5 pt-2.5 w-full text-sm text-neutral-100 bg-sidebar rounded-lg border-1 border-neutral-300 appearance-none
                                        dark:focus:border-amber-500 focus:outline-none focus:ring-0 focus:border-amber-500" placeholder=" "
                                    v-model="customDate" />

                                <span v-if="errors.customDate" class="mt-2 text-sm text-red-600 theme-error-message">
                                    {{ trans('lang.labels.please_select_the_next_review_date') }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Modal footer -->
                    <div class="flex items-center justify-end p-6 space-x-2 rounded-b dark:border-gray-600 mt-3">
                        <button @click="closeReviewDate()" type="button"
                            class="text-btnCancelText bg-btnCancelBg font-medium rounded-lg
                                                                                        text-sm px-5 py-2.5 text-center">
                            {{ trans('lang.modal.cancel') }}</button>
                        <button :disabled="formProcess" @click="updateForm()"
                            class="text-btnSubmitText bg-btnSubmitBg rounded-lg text-sm font-medium px-5 py-2.5">{{ trans('lang.modal.update') }}</button>
                    </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
import { mapStores } from 'pinia'
import { useAppStore } from "@/store";
import {
    XCircleIcon
} from '@heroicons/vue/24/solid'

export default {
    name: 'ReviewModal',
    props: ['data'],
    components: {
        XCircleIcon
    },
    data() {
        return {
            reviewDate: '',
            customDate: '',
            formProcess: false,
            errors: {
                review_date: '',
                customDate: '',
            }
        }
    },
    methods: {
        closeReviewDate() {
            this.$emit('closeReview');
        },
        updateForm() {
            let that = this;
            // that.formProcess = true;
            axios.put('/api/update-review-date/' + that.data.formId, {
                'review_date': that.reviewDate,
                'customDate': that.customDate,
                'userRole': 'company'

            })
                .then((res) => {
                    if (res.status == 200) {
                        that.closeReviewDate();
                        let notification = {
                            heading: 'success',
                            subHeading: res.data.message,
                            type: "success",
                        };
                        that.appStore.setNotification(notification);
                    }
                })
                .catch((error) => {
                    // error.response.status Check status code
                    that.errors = error.response.data.errors;

                    document.body.style.overflow = ''
                })
                .finally(() => {
                    // that.formProcess = true;
                    //Perform action in always
                });
        }
    },
    computed: {
        ...mapStores(useAppStore),
    },
}
</script>
