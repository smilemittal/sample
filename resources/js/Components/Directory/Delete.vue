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
                            {{ trans('lang.labels.are_you_sure_you_want_to_delete_this_folder') }}{{ id }}
                        </h3>
                    </div>
                    <button type="button" @click="isCloseDeleteModal()" class="">
                        <XCircleIcon class="h-9 w-9 text-white" aria-hidden="true" />
                    </button>
                </div>
                <!-- Modal footer -->
                <div class="flex items-center justify-end p-6 space-x-2 rounded-b dark:border-gray-600 mt-3">
                    <button @click="isCloseDeleteModal()" type="button" class="text-btnCancelText bg-btnCancelBg font-medium rounded-lg
                                                                    text-sm px-5 py-2.5 text-center">{{ trans('lang.modal.no_cancel') }}</button>
                    <button @click="submitClick()" type="button" :disabled="processing"
                        class="text-btnSubmitText bg-btnSubmitBg rounded-lg text-sm font-medium px-5 py-2.5">
                             {{ trans('lang.modal.delete') }}</button>
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
    name: 'DeleteCompany',
    props: ['id'],
    components:{
        XCircleIcon
    },
    data() {
        return {
            processing:false
        }
    },
    methods: {
        isCloseDeleteModal() {
            this.$emit('closeDelete');
        },
        submitClick() {
            this.processing = true;
            axios.delete(route('delete-directory',this.id))
                .then((res) => {

                    this.isCloseDeleteModal();
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
                    this.processing = false;
                    //Perform action in always
                    document.body.style.overflow = '';
                });
        },

    },
    computed: {
        ...mapStores(useAppStore),
    },
}
</script>
