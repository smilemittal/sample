<template>
    <!-- Main modal -->
    <div id="duplicatePath"
        class="bg-bgModal fixed top-0 flex items-center justify-center left-0 right-0 z-50 w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-modal md:h-full">
        <div class="relative w-full h-auto max-w-xl md:h-auto">
            <!-- Modal content -->
            <div class="relative rounded-lg shadow bg-body">
                <!-- Modal header -->
                <div class="flex items-start justify-between p-4 rounded-t">
                    <div>
                        <h3 class="text-xl font-semibold text-gray-300">
                            {{ trans('lang.labels.Once_locked_you_can_not_unlock_or_modify') }}
                        </h3>
                    </div>
                    <button type="button" @click="isClose()" class="">
                        <XCircleIcon class="h-9 w-9 text-white" aria-hidden="true" />
                    </button>
                </div>
                <!-- Modal footer -->
                <div class="flex items-center justify-end p-6 space-x-2 rounded-b dark:border-gray-600">
                    <button @click="isClose()"
                        class="text-btnCancelText bg-btnCancelBg rounded-lg text-sm font-medium px-5 py-2.5">
                        {{ trans('lang.modal.cancel') }}
                    </button>
                    <button @click="lockPath()" :disabled="formProcess"
                        class="text-btnSubmitText bg-btnSubmitBg rounded-lg text-sm font-medium px-5 py-2.5">
                        {{ trans('lang.labels.lock') }}
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
    name: 'DuplicatePath',
    props: ['data', 'id'],
    components:{
        XCircleIcon
    },
    data() {
        return {
            duplicatePaths: {
                title: '',
            },
            forms: [],
            selectedForms: [],
            formProcess:false
        }
    },
    methods: {
        isClose() {
            this.$emit("closeLock");
        },
        lockPath() {
            this.formProcess = true;
            axios.put('/api/lock-learning-path/' + this.id)
                .then((response) => {
                    console.log(response);
                    if(response.data.status == true){
                        this.isClose();
                        let notification = {
                        heading: 'success',
                        subHeading: response.data.message,
                        type: "success",
                        };
                        this.appStore.setNotification(notification);
                    }
                })
                .catch((error) => {
                    // error.response.status Check status code

                }).finally(() => {
                    this.formProcess = false;
                    //Perform action in always
                    document.body.style.overflow = '';
                });
        }
    },
    created() {
    },
    computed: {
        ...mapStores(useAppStore),
    },
}
</script>
