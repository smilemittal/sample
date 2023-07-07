<template>
    <!-- Main modal -->
    <div id="editModule"
        class="bg-bgModal fixed top-0 flex items-center justify-center left-0 right-0 z-50 w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-modal md:h-full">
        <div class="relative w-full h-auto max-w-5xl md:h-auto">
            <!-- Modal content -->
            <div class="relative rounded-lg shadow bg-body">
                <!-- Modal header -->
                <div class="flex items-start justify-between p-4 rounded-t">
                    <div>
                        <h3 class="text-xl font-semibold text-gray-300">
                            {{ trans('lang.labels.edit_module') }}
                        </h3>
                    </div>
                    <button type="button"
                        class="text-gray-400 bg-transparent hover:text-gray-900 rounded-lg text-sm inline-flex items-center dark:hover:text-white"
                        @click="isClose()">
                        <XCircleIcon class="h-8 w-8 text-white" aria-hidden="true" />
                    </button>
                </div>
                <!-- Form start here -->
                <div class="p-6 space-y-6 h-96 sm:h-96 lg:h-96 overflow-y-auto theme-modal-body">
                    <div class="grid md:grid-cols-2 sm:grid-cols-1 gap-3 mb-3">
                        <div class="relative">
                            <input type="text" id="module_id"
                                class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-neutral-100 bg-sidebar rounded-lg border-1 border-transparent appearance-none dark:focus:border-amber-500 focus:outline-none focus:ring-0 focus:border-amber-500 peer"
                                placeholder=" " v-model="form.typeform_id" />
                            <label for="module_id"
                                class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-sidebar px-2 peer-focus:px-2 peer-focus:text-amber-500 peer-focus:dark:text-amber-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 left-1">
                                {{ trans('lang.labels.module_id') }}
                            </label>
                            <span v-if="errors.typeform_id" class="mt-2 text-sm text-red-600 theme-error-message">{{
                                errors.typeform_id[0] }}</span>
                        </div>
                        <div class="relative">
                            <input type="text" id="module_data"
                                class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-neutral-100 bg-sidebar rounded-lg border-1 border-transparent appearance-none dark:focus:border-amber-500 focus:outline-none focus:ring-0 focus:border-amber-500 peer"
                                placeholder=" " v-model="form.display_title" readonly />
                            <label for="module_data"
                                class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-sidebar px-2 peer-focus:px-2 peer-focus:text-amber-500 peer-focus:dark:text-amber-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 left-1">
                                {{ trans('lang.labels.waiting_for_data_validation') }}
                            </label>
                            <span v-if="errors.display_title" class="mt-2 text-sm text-red-600 theme-error-message">{{
                                errors.display_title[0] }}</span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="w-full">
                            <div
                                class="theme-upload-img block sm:flex md:flex  items-center justify-between border-2 border-gray-500 border-dashed rounded-lg cursor-pointer bg-sidebar">
                                <label for="dropzone-file" class="w-full h-64">
                                    <div class="flex flex-col h-full items-center justify-center pt-5 pb-6">
                                        <div>
                                            <div>
                                                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                                    <svg aria-hidden="true" class="w-10 h-10 mb-3 text-gray-400" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12">
                                                        </path>
                                                    </svg>
                                                    <p class="mb-2 text-sm text-gray-500 dark:text-gray-400">
                                                        <span class="font-semibold">{{ trans('lang.labels.click_to_upload')
                                                        }}</span>
                                                        or drag and drop
                                                    </p>
                                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                                        SVG, PNG, JPG or GIF (MAX. 800x400px)
                                                    </p>

                                                    <input id="dropzone-file" type="file" class="hidden"
                                                        @change="previewImage" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </label>
                                <template v-if="editImage != null || preview != null">
                                    <div class="flex flex-col items-center justify-center w-full xs:w-28 sm:w-30 h-64 p-5">
                                        <div
                                            class="flex flex-col items-center justify-center pt-5 pb-6 w-full xs:w-28 sm:w-30">
                                            <img alt="form" :src="preview != null ? preview : (mediaPath  + editImage)"
                                                class="h-56 object-cover w-full rounded-lg" />
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                    <div class="grid md:grid-cols-5 sm:grid-cols-2 gap-3 mt-5 mb-3"
                        v-for="(formTime, index) in form.descTime" :key="formTime.id">
                        <div class="relative theme-time-picker" :id="index">
                            <vue-timepicker :format="formTime.timeFormat"
                                v-model="form.descTime[index].start_time"></vue-timepicker>
                        </div>
                        <div class="relative theme-time-picker">
                            <vue-timepicker :format="formTime.timeFormat"
                                v-model="form.descTime[index].end_time"></vue-timepicker>
                        </div>
                        <div class="relative col-span-2">
                            <input type="text" :id="'description'+index"
                                class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-neutral-100 bg-sidebar rounded-lg border-1 border-transparent appearance-none
                                        dark:focus:border-amber-500 focus:outline-none focus:ring-0 focus:border-amber-500 peer" placeholder=" "
                                v-model="form.descTime[index].description" />
                            <label :for="'description'+index" class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75
                                        top-2 z-10 origin-[0] bg-sidebar px-2 peer-focus:px-2 peer-focus:text-amber-500 peer-focus:dark:text-amber-500
                                        peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75
                                        peer-focus:-translate-y-4 left-1">
                                {{ trans('lang.labels.description') }}
                            </label>
                        </div>
                        <div class="mt-2">
                            <button @click="remove(index)" type="button" v-if="index != 0"
                                class="bg-btnCanceltBg text-btnCancelText px-8 py-2 text-sm rounded-lg">
                                <MinusCircleIcon class="h-6 w-6 text-btnCancelText" aria-hidden="true" />
                            </button>
                        </div>
                    </div>

                    <div class="mt-2">
                        <button @click="repeatForm()" type="button"
                            class="bg-btnSubmitBg text-btnSubmitText px-8 py-2 text-sm rounded-lg">
                            <PlusCircleIcon class="h-6 w-6 text-btnSubmitText" aria-hidden="true" />
                        </button>
                    </div>
                    <div class="flex items-center mb-4 mt-4">
                        <label for="next_date" class="ml-2 text-sm font-medium text-amber-500">
                            {{ trans('lang.labels.current_review_date_is') }} {{ currentReviewDate }}
                        </label>
                    </div>
                    <div class="my-2">
                        <div class="grid grid-cols-3 gap-3">
                            <select id="form_time"
                                class="bg-sidebar border-1 border-transparent focus:border-amber-500 text-neutral-400 text-sm rounded-lg p-2.5"
                                v-model="form.review_category">
                                <option selected>Select next review Date</option>
                                <option value="1">After 6 Months</option>
                                <option value="2">After 12 Months</option>
                                <option value="3">Custom Date</option>
                            </select>
                            <span v-if="errors.review_category" class="mt-2 text-sm text-red-600 theme-error-message">{{
                                errors.review_category[0] }}</span>
                            <div v-if="form.review_category == 3">
                                <input type="date" placeholder="Is Review"
                                    class="bg-sidebar border-1 border-transparent focus:border-amber-500 text-neutral-400 text-sm rounded-lg p-2.5"
                                    id="customize_review_date" name="customize_review_date" v-model="form.review_date">
                            </div>
                        </div>
                    </div>
                    <div class="mb-3 mt-4">
                        <div>
                            <ckeditor class="identify_desc" :editor="editor" v-model="form.description"
                                :config="editorConfig" tag-name="textarea"
                                placeholder="What is the current situation/issue?"></ckeditor>
                        </div>
                    </div>
                    <div class="flex items-center mb-4 mt-2">
                        <input id="belong_xme" type="checkbox" v-model="form.xme_area" @change="changeXmeType"
                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label for="belong_xme" class="ml-2 text-sm font-medium text-amber-500">{{
                            trans('lang.labels.belongs_to_xme_area') }}</label>
                    </div>
                    <div v-if="form.xme_area">
                        <div class="flex items-center mb-4 mt-2">
                            <input id="belong_xme_module" type="checkbox" v-model="form.is_assigned_default" @change="changeDefaultType"
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                            <label for="belong_xme_module" class="ml-2 text-sm font-medium text-amber-500">{{
                                trans('lang.labels.default_assigned_xme_module') }}</label>
                        </div>
                        <assign-admin :form="form"></assign-admin>
                    </div>
                </div>
                <div class="grid grid-cols-3 gap-3 p-6 space-x-2">
                    <button type="button" :class="form.tagClass">
                        {{ form.responsesCount }}
                    </button>
                    <button type="button" @click="checkResponse()"
                        class="col-span-2 text-sm bg-btnSubmitBg text-btnSubmitText hover:bg-amber-500 hover:text-white rounded-lg py-3 px-6">
                        {{ trans('lang.labels.check_for_responses') }}
                    </button>
                </div>
                <div class="grid grid-cols-3 gap-3 p-6 space-x-2">
                    <button type="button" @click="validateForm()" :disabled="formProcess"
                        class="text-sm bg-bgAmberTag text-amber-500 hover:bg-amber-500 hover:text-white rounded-lg py-3 px-6">
                        {{ trans('lang.labels.validate') }}
                    </button>
                    <button type="submit" :disabled="formProcess" v-if="isValidated" @click="update()"
                        class="col-span-2 text-sm bg-btnSubmitBg text-btnSubmitText hover:bg-amber-500 hover:text-white rounded-lg py-3 px-6">
                        {{ trans('lang.labels.update_module') }}
                    </button>
                </div>
                <!-- Modal footer -->
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
import Datepicker from '@/Datepicker/Datepicker.vue'
import VueTimePicker from "vue3-timepicker";
import "vue3-timepicker/dist/VueTimepicker.css";
import AssignAdmin from '@/Components/Module/DefaultAssign.vue'

import { mapStores } from 'pinia'
import { useAppStore } from "@/store";

import ClassicEditor from '@ckeditor/ckeditor5-build-classic';
import {
    TrashIcon,
    PlusCircleIcon, MinusCircleIcon, XCircleIcon
} from '@heroicons/vue/24/solid'
export default {
    name: 'CreateForm',
    components: {
        Datepicker,
        "vue-timepicker": VueTimePicker,
        TrashIcon,
        PlusCircleIcon, MinusCircleIcon, XCircleIcon,
        AssignAdmin
    },
    props: ['id'],
    data() {
        return {
            isValidated: false,
            form: {
                status: '1',
                json: '',
                descTime: [
                    {
                        timeFormat: "hh:mm",
                        start_time: '',
                        end_time: '',
                        description: ''
                    },
                ],
                is_assigned_default: false,
                default_assignned_roles:[],
                review_category: ''
            },
            errors: {
                typeform_id: '',
                display_title: '',
                review_category: ''

            },
            formProcess: false,
            preview: null,
            editImage: '',
            currentReviewDate: '',
            editor: ClassicEditor,
            editorData: '<p>Content of the editor.</p>',
            editorConfig: {
                toolbar: ['heading', 'bold', 'italic', '|', 'NumberedList', 'BulletedList']
            },
           
        }
    },
    methods: {
        changeDefaultType(){
            if(!this.form.is_assigned_default) {
                this.form.default_assignned_roles = [];
            }
        },
        changeXmeType(){
            if(!this.form.xmeArea) {
                this.form.default_assignned_roles = [];
                this.form.is_assigned_default= false;
            }
        },
        repeatForm() {
            let row = {
                timeFormat: "hh:mm",
                start_time: '',
                end_time: '',
                description: '',
            }
            this.form.descTime.push(row);

        },
        remove(index) {
            this.form.descTime.splice(index, 1);
        },
        validateForm() {
            this.formProcess = true;
            let formData = new FormData();
            formData.append('typeform_id', this.form.typeform_id);
            axios.post('/api/validate-form', formData)
                .then((res) => {
                    if (res.data.status == true) {
                        this.form.display_title = res.data.data.title;
                        this.form.json = JSON.stringify(res.data.data);
                        this.isValidated = true;
                    }
                })
                .catch((error) => {
                    this.errors = error.response.data.errors;
                    // error.response.status Check status code
                }).finally(() => {
                    this.formProcess = false;
                    //Perform action in always
                });

        },
        checkResponse() {
            axios.post('/api/pull-response/' + this.form.typeform_id)
                .then((res) => {
                    if (res.data.status == true) {

                    }
                })
                .catch((error) => {
                    this.errors = error.response.data.errors;
                    // error.response.status Check status code
                }).finally(() => {
                    this.formProcess = false;
                    //Perform action in always
                });

        },
        editField() {
            axios
                .get("/api/forms/" + this.id)
                .then((res) => {
                    this.form = res.data.data.form;
                    this.currentReviewDate = res.data.data.form.reviewFormattedDate;
                    if (!res.data.data.time_detail) {
                        let row = {
                            timeFormat: "hh:mm",
                            start_time: '',
                            end_time: '',
                            description: '',
                        }
                        this.form.descTime = [];
                        this.form.descTime.push(row);
                    } else {
                        this.form.descTime = res.data.data.time_detail;
                    }
                    this.editImage = res.data.data.form.image;
                })
                .catch((error) => {
                    // error.response.status Check status code
                })
                .finally(() => {
                    //Perform action in always
                });
        },
        update() {
            const config = {
                headers: {
                    'content-type': 'multipart/form-data'
                }
            }
            this.formProcess = true;
            let that = this;
            let formData = that.convertJsonToFormData(that.form);
            if (that.form.file) {
                formData.append('file', that.form.file);
            }
            let xmeArea = that.form.xme_area ? 1 : 0;
            formData.append('xme_area', xmeArea);
            formData.append('descTime', JSON.stringify(that.form.descTime));
            formData.append('default_assignned_roles', that.form.default_assignned_roles);
            formData.append('review_category', that.form.review_category);
            axios.post('/api/update-form/' + that.id, formData, config)
                .then((res) => {
                    if (res.data.status) {
                        that.form = {};
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
                    // error.response.status Check status code

                    that.isClose();
                }).finally(() => {
                    that.formProcess = false;
                    //Perform action in always
                });
        },
        isClose() {
            this.$emit("closeEdit");
            this.form = {};
        },
        previewImage: function (event) {
            let input = event.target;
            if (input.files) {
                let reader = new FileReader();
                reader.onload = (e) => {
                    this.preview = e.target.result;
                }
                reader.readAsDataURL(input.files[0]);
                this.form.file = input.files[0];
            }
        },
        deletePreviewImg() {
            this.preview = null;
        },
    },
    created() {
        this.editField();
    },
    computed: {
        ...mapStores(useAppStore),
    },
}
</script>
