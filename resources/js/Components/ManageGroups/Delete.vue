<template>
    <!-- Main modal -->
    <div id="archiveForm"
        class="bg-bgModal fixed top-0 flex items-center justify-center left-0 right-0 z-50 w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-modal md:h-full">
        <div class="relative w-full h-auto max-w-xl md:h-auto">
            <!-- Modal content -->
            <div class="relative rounded-lg shadow bg-body">
                <!-- Modal header -->
                <div class="flex items-start justify-between p-6 rounded-t">
                    <div>
                        <h3 class="text-xl font-semibold text-gray-300">
                            {{ trans("lang.labels.manage_groups") }}
                        </h3>
                        <h3 class="text-base text-gray-400 mt-2">
                            {{ data.modalText }}
                        </h3>
                    </div>
                    <button type="button"
                        class="text-gray-400 bg-transparent hover:text-gray-900 rounded-lg 
                        text-sm inline-flex items-center dark:hover:text-white"
                        @click="isClose()">
                        <XCircleIcon class="h-8 w-8 text-white" aria-hidden="true" />
                    </button>
                </div>
                <!-- Modal footer -->
                <div class="flex items-center justify-end p-6 space-x-2 rounded-b dark:border-gray-600 mt-3">
                    <button @click="isClose()" type="button" class="text-btnCancelText bg-btnCancelBg font-medium rounded-lg
                                                                text-sm px-5 py-2.5 text-center">{{ trans("lang.modal.cancel") }}</button>
                    <button :disabled="processing" @click="submitClick()" type="button"
                        class="text-btnSubmitText bg-btnSubmitBg rounded-lg text-sm font-medium px-5 py-2.5">{{
                            data.buttonText }}</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
import { XCircleIcon
} from '@heroicons/vue/24/solid'

export default {
    name: 'Delete',
    props: ['data'],
    components:{
        XCircleIcon
    },
    data() {
        return {
            processing: false,
        }
    },
    methods: {
        isClose() {
            this.$emit("closeDelete");
        },
        submitClick() {
            this.processing = true;
            this.$emit('deleteSubmitAction');
        },
        processingFunc(){
            this.processing = false; 
        }
    }
}
</script>
