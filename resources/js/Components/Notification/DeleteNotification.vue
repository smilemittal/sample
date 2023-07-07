<template>
    <!-- Main modal -->
    <div id="deleteNotification"
        class="bg-bgModal fixed top-0 flex items-center justify-center left-0 right-0 z-50 w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-modal md:h-full">
        <div class="relative w-full h-auto max-w-2xl md:h-auto">
            <!-- Modal content -->
            <div class="relative rounded-lg shadow bg-body">
                <!-- Modal header -->
                <div class="flex items-start justify-between p-4 rounded-t">
                    <div>
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                            Notification
                        </h3>
                        <h5 class="text-base text-neutral-400">
                           {{ data.modalText }}
                        </h5>
                    </div>
                    <button type="button" @click="isCloseDelete()" class="">
                        <XCircleIcon class="h-9 w-9 text-white" aria-hidden="true" />
                    </button>
                </div>
                    <!-- Modal footer -->
                    <div class="flex items-center justify-end p-6 space-x-2 rounded-b dark:border-gray-600">
                        <button @click="isCloseDelete()" class="text-btnCancelText bg-btnCancelBg rounded-lg text-sm font-medium px-5 py-2.5">
                            Cancel
                        </button>
                        <button :disabled="processing" @click="submitClick()" type="button" class="text-btnSubmitText bg-btnSubmitBg rounded-lg text-sm font-medium px-5 py-2.5">
                            {{ data.buttonText }}
                        </button>
                    </div>
            </div>
        </div>
    </div>
</template>
<script>
import {
   XCircleIcon
} from '@heroicons/vue/24/solid'
export default {
    name: 'DeleteNotification',
    props: ['data'],
    components:{
        XCircleIcon
    },
    data() {
        return {

        }
    },
    methods: {
        isCloseDelete() {
            this.$emit("closeDeleteNotification");
        },
        submitClick() {
            this.$emit('deleteSubmitNotification');
        },
    },
    created() {
    }
}
</script>
