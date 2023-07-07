<template>
    <!-- Main modal -->
    <div id="addDirectory"
        class="bg-bgModal fixed top-0 flex items-center justify-center left-0 right-0 z-50 w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-modal md:h-full">
        <div class="relative w-full h-auto max-w-xl md:h-auto">
            <!-- Modal content -->
            <div class="relative rounded-lg shadow bg-body">
                <!-- Modal header -->
                <div class="flex items-start justify-between p-6 rounded-t">
                    <div>
                        <h3 class="text-xl font-semibold text-gray-300">
                            {{ trans('lang.site.folder_name') }}
                        </h3>
                    </div>
                    <button type="button" @click="$emit('closeAdd')" class="">
                        <XCircleIcon class="h-9 w-9 text-white" aria-hidden="true" />
                    </button>
                </div>
                <!-- Form start here -->
                <div class="p-6 space-y-6 h-auto overflow-y-auto theme-modal-body">
                        <div class="grid md:grid-cols-2 sm:grid-cols-1 gap-y-8 gap-x-3">
                            <div class="theme-input">
                                <div class="relative">
                                    <input type="text" id="folder_name"
                                        class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-neutral-100 bg-sidebar rounded-lg border-1 border-transparent appearance-none dark:focus:border-amber-500 focus:outline-none focus:ring-0 focus:border-amber-500 peer"
                                        placeholder="" v-model="folderForm.name" />
                                    <label for="folder_name"
                                        class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-sidebar px-2 peer-focus:px-2 peer-focus:text-amber-500 peer-focus:dark:text-amber-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 left-1">
                                        {{ trans('lang.site.folder_name') }}
                                    </label>
                                </div>
                                <span v-if="errors.name" class="mt-2 text-sm text-red-600 theme-error-message">{{
                                            errors.name[0]
                                        }}</span>
                            </div>
                        </div>
                        <div class="flex items-center justify-end space-x-2 rounded-b dark:border-gray-600">
                            <button @click="$emit('closeAdd')"
                                class="text-btnCancelText bg-btnCancelBg rounded-lg text-sm font-medium px-5 py-2.5">
                                {{ trans('lang.modal.cancel') }}
                            </button>
                            <button type="submit" :disabled="formProcess" @click="addDirectory()"
                                class="text-btnSubmitText bg-btnSubmitBg rounded-lg text-sm font-medium px-5 py-2.5">
                                {{ trans('lang.modal.save') }}
                            </button>
                        </div>
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
    name: 'AddDirectory',
    props:['directoryId'],
    components:{
        XCircleIcon
    },
    data() {
        return {
            folderForm: {
                name: ''
            },
            errors: {
                name: "",
            },
            formProcess: false,
        }
    },
    methods: {
        addDirectory() {
            const config = {
                headers: {
                    'content-type': 'multipart/form-data'
                }
            }
            this.formProcess = true;
            let that = this;
            let formData = new FormData();
            formData.append("name",that.folderForm.name )
            if(that.directoryId){
                formData.append('parent_id', that.directoryId);
            }
            axios.post('/api/directories', formData, config)
                .then((res) => {
                    if(res.data.status == true){
                        that.folderForm = {};
                        that.isCloseAdd();
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
                    // error.response.status Check status code
                }).finally(() => {
                    that.formProcess = false;
                    //Perform action in always
                    document.body.style.overflow = '';
                });
        },
        isCloseAdd(){
            this.$emit('closeAdd');
           this.$emit("resfreshTable");
        }
        
    },
    computed: {
        ...mapStores(useAppStore),
    },
}
</script>
