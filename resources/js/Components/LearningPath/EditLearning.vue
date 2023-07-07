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
                        <h3 v-if="heading !='view'" class="text-xl font-semibold text-gray-300">
                            {{ trans('lang.labels.edit_learning_path') }}
                        </h3>
                        <h3 v-if="heading =='view'" class="text-xl font-semibold text-gray-300">
                            {{ trans('lang.labels.learning_path_description') }}
                        </h3>
                    </div>
                    <button type="button" @click="isClose()" class="">
                        <XCircleIcon class="h-9 w-9 text-white" aria-hidden="true" />
                    </button>
                </div>
                    <!-- Form start here -->
                    <div class="p-6 space-y-6 h-fit overflow-y-auto theme-modal-body">
                        <div class="grid md:grid-cols-1 sm:grid-cols-1 gap-3 mb-2">
                            <div class="theme-input" v-if="heading !='view'">
                                <h5 class="text-base text-neutral-400 mb-3">{{ trans('lang.labels.please_enter_the_learning_path_title') }}</h5>
                                <div class="relative">
                                    <input type="text" id="title_path"
                                        class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-neutral-100 bg-sidebar rounded-lg border-1 border-transparent appearance-none
                                                                            dark:focus:border-amber-500 focus:outline-none focus:ring-0 focus:border-amber-500 peer"
                                        placeholder=" " v-model="form.title" :disabled="form.is_lock" />
                                    <label for="title_path" class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75
                                                                            top-2 z-10 origin-[0] bg-sidebar px-2 peer-focus:px-2 peer-focus:text-amber-500 peer-focus:dark:text-amber-500
                                                                            peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75
                                                                            peer-focus:-translate-y-4 left-1">
                                        {{ trans('lang.labels.title') }}
                                    </label>
                                </div>
                                <span v-if="errors.title" class="mt-2 text-sm text-red-600 theme-error-message">{{ errors.title[0] }}</span>
                            </div>
                            <div class="relative">
                                <ckeditor class="identify_desc" :editor="editor" v-model="form.description" :disabled="(user_role != 'employee') ? true : false"
                                    :config="editorConfig" tag-name="textarea"
                                    placeholder="What is the current situation/issue?"></ckeditor>
                            </div>
                        </div>
                    </div>
                    <!-- Modal footer -->
                    <div class="flex items-center justify-end p-6 space-x-2 rounded-b dark:border-gray-600" v-if="userRole !='employee'">
                        <button @click="isClose()"
                            class="text-btnCancelText bg-btnCancelBg rounded-lg text-sm font-medium px-5 py-2.5">
                             {{ trans('lang.modal.cancel') }}
                        </button>
                    </div>
            </div>
        </div>
    </div>
</template>
<script>
import ClassicEditor from '@ckeditor/ckeditor5-build-classic';
import axios from 'axios';
import { mapStores } from 'pinia'
import { useAppStore } from "@/store";
import {
   XCircleIcon
} from '@heroicons/vue/24/solid'
export default {
    name: 'DuplicatePath',
    props: ['data','learningPathId','userRole','heading'],
    components:{
        XCircleIcon
    },
    data() {
        return {
            addPath: {
                title: '',
                desc:''
            },
            form: {
            },
            errors: {
                title: '',
            },
            selectedForms: [],
            formProcess : false,
            readonly :false,
            editor: ClassicEditor,
            editorData: '<p>Content of the editor.</p>',
            editorConfig: {
                toolbar: ['heading', 'bold', 'italic', '|', 'NumberedList', 'BulletedList']
            },
        }
    },
    methods: {
        isClose() {
            this.$emit("closeEdit");
        },
        submitForm() {
            this.formProcess = true;
            let formData = new FormData();
            formData.append('title', this.form.title);
            formData.append('description', this.form.description);
            axios.post('/api/update-learning-path/'+this.learningPathId,formData)
                .then((res) => {
                    if (res.status == 200) {
                        this.form = {};
                        this.isClose();
                        let notification = {
                        heading: 'success',
                        subHeading: res.data.message,
                        type: "success",
                        };
                        this.appStore.setNotification(notification);
                    }
                })
                .catch((error) => {
                    this.errors = error.response.data.errors;
                    // error.response.status Check status code
                }).finally(() => {
                    this.processing = false;
                    //Perform action in always
                    document.body.style.overflow = '';
                });
        },
        edit() {
            axios.get('/api/edit-learning-path/' + this.learningPathId)
                .then((response) => {
                    this.form = response.data.data;
                })
        },
    },
    created: function () {
        this.edit();
    },
    computed: {
        ...mapStores(useAppStore),
    },
}
</script>
