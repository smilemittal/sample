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
                            {{ trans("lang.labels.reassign_module_settings") }}
                        </h3>
                        <h3 class="text-base text-gray-400 mt-2">{{ trans("lang.labels.are_you_sure_you_want_to_remove_reassign_settings_of_this_form") }}</h3>
                    </div>
                    <button type="button" @click="isClose()" class="">
                        <XCircleIcon class="h-9 w-9 text-white" aria-hidden="true" />
                    </button>
                </div>

                <div class="h-fit theme-modal-body">
                    <!-- Modal footer -->
                    <div class="flex items-center justify-end p-0 pr-3 pb-6 pt-6 space-x-2 rounded-b dark:border-gray-600">
                        <button @click="isClose()" 
                            class="text-btnCancelText bg-btnCancelBg rounded-lg text-sm font-medium px-5 py-2.5">
                            {{ trans('lang.modal.cancel') }}
                        </button>
                        <button :disabled="processing" type="button" @click="removeReassignSetting()"
                            class="text-btnSubmitText bg-btnSubmitBg rounded-lg text-sm font-medium px-5 py-2.5">
                            {{ trans('lang.modal.done') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import {
    XCircleIcon
} from '@heroicons/vue/24/solid';
import { mapStores } from 'pinia'
import { useAppStore } from "@/store";
export default {
    name: 'Reassign Settings',
    props: ['groupId', 'formId'],
    components: {
        XCircleIcon
    },
    data() {
        return {
            processing:false,
        }
    },
    watch: {
    },
    methods: {
        isClose() {
            this.$emit("closeAssignSetting");
        },
        removeReassignSetting() {
            this.processing = true;
            let formData = new FormData();
            formData.append('form_id', this.formId)
            axios.post('/api/remove-reassign-setting/' + this.groupId, formData)
                .then((res) => {
                    if(res.status == 200){
                        let notification = {
                            heading: 'success',
                            subHeading: res.data.message,
                            type: "success",
                        };
                        this.appStore.setNotification(notification);
                        this.isClose();
                    }
                })
                .catch((error) => {
                    // error.response.status Check status code
                }).finally(() => {
                    this.processing = false;
                    //Perform action in always
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
