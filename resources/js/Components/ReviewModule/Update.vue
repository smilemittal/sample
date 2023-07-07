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
                            {{ trans("lang.modal.update") }}
                        </h3>
                        <h3 class="text-base text-gray-400 mt-2">
                            {{ trans("lang.labels.please_enter_the_module_updation_reason") }}
                        </h3>
                    </div>
                    <button type="button" @click="closeUpdate()" class="">
                        <XCircleIcon class="h-9 w-9 text-white" aria-hidden="true" />
                    </button>
                </div>

                    <div class="p-6 space-y-6 overflow-y-auto theme-modal-body">
                        <textarea type="text" id="from_name" rows="7"
                            class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-neutral-100 bg-sidebar rounded-lg border-1
                                                        border-transparent appearance-none dark:focus:border-amber-500 focus:outline-none focus:ring-0 focus:border-amber-500 peer"
                            placeholder="" v-model="updateRefiewForm.reason"></textarea>

                        <span v-if="errors.reason" class="mt-2 text-sm text-red-600 theme-error-message">{{ trans("lang.labels.reason_is_required") }}</span>
                    </div>


                    <!-- Modal footer -->
                    <div class="flex items-center justify-end p-6 space-x-2 rounded-b dark:border-gray-600 mt-3">
                        <button @click="closeUpdate()" type="button"
                            class="text-btnCancelText bg-btnCancelBg font-medium rounded-lg text-sm px-5 py-2.5 text-center">{{ trans("lang.modal.no_cancel") }}</button>
                        <button @click="updateModule()" :disabled="formProcess"
                            class="text-btnSubmitText bg-btnSubmitBg rounded-lg text-sm font-medium px-5 py-2.5">{{ trans("lang.modal.update") }}</button>
                    </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
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
            updateRefiewForm: {
                reason: ''
            },
            errors: {
                reason: ""
            },
            formProcess:false
        }
    },
    methods: {
        closeUpdate() {
            this.$emit('closeUpdate');
        },
        updateModule() {
            let that = this;
            that.formProcess = true;
            axios.put('/api/update-xmeform/' + that.data.formId, { 'reason': that.updateRefiewForm.reason })
                .then((res) => {
                    if (res.status == 200) {
                        window.location.href = 'review-forms';
                    }
                })
                .catch((error) => {
                    // error.response.status Check status code
                    that.errors = error.response.data.errors;

                    document.body.style.overflow = ''
                }).finally(() => {
                    //Perform action in always
                    that.formProcess = false;
                });
        },
    }
}
</script>
