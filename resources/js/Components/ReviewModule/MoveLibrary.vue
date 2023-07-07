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
                            {{ trans("lang.labels.are_you_sure_you_want_to_move_this_module_to_library") }}
                        </h3>
                    </div>
                    <button type="button" @click="close()" class="">
                        <XCircleIcon class="h-9 w-9 text-white" aria-hidden="true" />
                    </button>
                </div>
                <!-- Modal footer -->
                <div class="flex items-center justify-end p-6 space-x-2 rounded-b dark:border-gray-600 mt-3">
                    <button @click="close()" type="button"
                        class="text-btnCancelText bg-btnCancelBg font-medium rounded-lg
                                                                                            text-sm px-5 py-2.5 text-center">
                        {{ trans("lang.modal.no_cancel") }}</button>
                    <button :disabled="processing" @click="upadte()" type="button"
                        class="text-btnSubmitText bg-btnSubmitBg rounded-lg text-sm font-medium px-5 py-2.5">{{ trans("lang.modal.move_to_library") }}</button>
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
    name: 'MoveLibrary',
    props: ['formId'],
    components:{
        XCircleIcon
    },
    data() {
        return {
            processing: false,
        }
    },
    methods: {
        close() {
            this.$emit('closeMove');
        },
        upadte(){
            this.processing = true;
            this.$emit('moveToLibrary');
        },
        processingFunc(){
            this.processing = false; 
        }
        
    }
}
</script>
