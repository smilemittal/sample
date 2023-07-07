<template>
    <!-- Main modal -->
    <div id="duplicatePath"
        class="bg-bgModal fixed top-0 flex items-center justify-center left-0 right-0 z-50 w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-modal md:h-full">
        <div class="relative w-full h-auto max-w-2xl md:h-auto">
            <!-- Modal content -->
            <div class="relative rounded-lg shadow bg-body">
                <!-- Modal header -->
                <div class="flex items-start justify-between p-4 rounded-t">
                    <div>
                        <h3 class="text-xl font-semibold text-gray-300">
                            {{ trans('lang.labels.learning_path') }}
                        </h3>
                        <h5 class="text-base text-gray-400">{{ trans('lang.labels.are_you_sure_you_want_to_delete_this_module') }}</h5>
                    </div>
                    <button type="button" @click="isCloseDelete()" class="">
                        <XCircleIcon class="h-9 w-9 text-white" aria-hidden="true" />
                    </button>
                </div>
                    <!-- Modal footer -->
                    <div class="flex items-center justify-end p-6 space-x-2 rounded-b dark:border-gray-600">
                        <button @click="isCloseDelete()" class="text-btnCancelText bg-btnCancelBg rounded-lg text-sm font-medium px-5 py-2.5">
                            {{ trans('lang.modal.cancel') }}
                        </button>
                        <button @click="submitForm()" class="text-btnSubmitText bg-btnSubmitBg rounded-lg text-sm font-medium px-5 py-2.5">
                            {{ trans('lang.modal.done') }}
                        </button>
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
    name: 'DeleteAction',
    props: ['formId', 'learningPathId'],
    components:{
        XCircleIcon
    },
    data() {
        return {
          
        }
    },
    methods: {
        isCloseDelete() {
            this.$emit("closeDeleteModule");
        },
        submitForm() {
            let formData = new FormData();
            formData.append('formId', this.formId)
            axios.post('/api/remove-learning-Path-module/' + this.learningPathId, formData)
                .then((res) => {
                    this.isCloseDelete();
                    let notification = {
                        heading: 'success',
                        subHeading: res.data.message,
                        type: "success",
                        };
                        this.appStore.setNotification(notification);
                })
                .catch((error) => {
                    // error.response.status Check status code
                }).finally(() => {
                    //Perform action in always
                    document.body.style.overflow = '';
                });
        },
        
    },
    created() {
    },
    computed: {
        ...mapStores(useAppStore),
    },
}
</script>
