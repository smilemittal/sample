<template>
    <!-- Main modal -->
    <div id="deleteCompany"
        class="bg-bgModal fixed top-0 flex items-center justify-center left-0 right-0 z-50 w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-full md:h-full">
        <div class="relative w-full h-auto max-w-xl md:h-auto">
            <!-- Modal content -->
            <div class="relative rounded-lg shadow bg-body">
                <!-- Modal header -->
                <div class="flex items-start justify-between p-4 rounded-t">
                    <div>
                        <h3 class="text-xl font-semibold text-gray-300">
                            {{ trans('lang.labels.subject') }}
                        </h3>
                        <h3 class="text-base font-semibold text-gray-400 mt-2">
                            {{ data.modalText }}
                        </h3>
                        <h3 class="text-xl font-semibold text-gray-300">{{ data.permanantTitle }}</h3>
                        <h3 class="text-base font-semibold text-gray-400 mt-2">{{ data.permanantSubTitle }}</h3>

                    </div>
                    <button type="button" @click="isCloseModal()" class="">
                        <XCircleIcon class="h-9 w-9 text-white" aria-hidden="true" />
                    </button>
                </div>
                <!-- Modal footer -->
                <div class="flex items-center justify-end p-6 space-x-2 rounded-b dark:border-gray-600 mt-3">
                    <button @click="isCloseModal()" type="button" class="text-btnCancelText bg-btnCancelBg font-medium rounded-lg
                                                                    text-sm px-5 py-2.5 text-center">{{ trans('lang.modal.no_cancel') }}</button>
                    <button :disabled="formProcess" @click="submitClick()" type="button"
                        class="text-btnSubmitText bg-btnSubmitBg rounded-lg text-sm font-medium px-5 py-2.5">{{
                            data.buttonText }}</button>
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
    name: 'MultipleArchive',
    props: ['data'],
    components:{
        XCircleIcon
    },
    data() {
        return {
            formProcess: false,
        }
    },
    methods: {
        isCloseModal() {
            this.$emit('cancelMultipleArchive');
        },
        submitClick() {
            this.formProcess = true;
            this.$emit('multipleDelAction');
        },
        processingFunc(){
            this.formProcess = false; 
        }
    }
}
</script>
