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
                            {{ data.title }}
                        </h3>
                        <h3 class="text-base text-gray-400 mt-2" v-html="data.subTitle">
                        </h3>
                    </div>
                    <button type="button" @click="isClose()" class="">
                        <XCircleIcon class="h-9 w-9 text-white" aria-hidden="true" />
                    </button>
                </div>
                <!-- Modal footer -->
                <div class="flex items-center justify-end p-6 space-x-2 rounded-b dark:border-gray-600 mt-3">
                    <button @click="isClose()" type="button" class="text-btnCancelText bg-btnCancelBg font-medium rounded-lg
                    text-sm px-5 py-2.5 text-center">{{ trans('lang.modal.no_cancel') }}</button>
                    <button :disabled="formProcess" @click="submitClick()" type="button"
                        class="text-btnSubmitText bg-btnSubmitBg rounded-lg text-sm font-medium px-5 py-2.5">
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
    name: 'Invite',
    props: ['data'],
    components: {
        XCircleIcon
    },
    data() {
        return {
            formProcess: false,
        }
    },
    methods: {
        isClose() {
            this.$emit('closeInvite');
        },
        submitClick() {
            let that = this;
            that.formProcess = true;
            axios.post('/api'+that.data.actionUrl)
                .then((res) => {
                    console.log(res);
                    if (res.status == 200) {
                        that.isClose();
                        let notification = {
                        heading: 'success',
                        subHeading: res.data.message,
                        type: "success",
                        };
                        that.appStore.setNotification(notification);
                    }
                })
                .catch((error) => {
                    that.errors = error.response.data.errors;
                    console.log(that.errors);
                    // error.response.status Check status code

                    //if any error happened remove modal and overflow itself
                    that.isClose();
                })
                .finally(() => {
                    that.formProcess = false;
                    //Perform action in always
                });
        },
    },
    computed: {
        ...mapStores(useAppStore),
    },
}
</script>
