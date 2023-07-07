<template>
    <div>

        <InertiaHead title="Identify" />
        <AuthenticatedLayout>
            <div class="mt-2">
                <div class="flex items-center flex-wrap">
                    <h4 class="text-neutral-300 font-bold text-xl sm:text-2xl md:text-4xl" v-if="is_develop == 0">{{
                        trans('lang.labels.identify_new_situation') }}
                    </h4>
                    <h4 class="text-neutral-300 font-bold text-xl sm:text-2xl md:text-4xl" v-if="is_develop == 1">{{
                        trans('lang.labels.develop') }}
                    </h4>
                    <div class="flex items-center">
                        <div class="ml-3">
                            <span class="text-base text-neutral-400">{{ trans('lang.labels.priority') }}</span>
                        </div>
                        <select id="form_status"
                            class="bg-sidebar border border-gray-300 text-neutral-400 focus:border-amber-500 focus:ring-0 text-sm rounded-lg p-2.5 ml-2"
                            v-model="form.priority" :disabled="(checkAdmin) ? true : false">
                            <option>Status</option>
                            <option value="0">Low</option>
                            <option value="1">Medium</option>
                            <option value="2">High</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="h-full drop-shadow-md rounded-3xl mt-5">
                <div class="relative shadow-md sm:rounded-lg mt-5">
                    <div class="grid md:grid-cols-2 sm:grid-cols-1 gap-3">
                        <div>
                            <div class="theme-input">
                                <div class="relative">
                                    <input type="text" id="business_name"
                                        class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-neutral-100 bg-sidebar rounded-lg border-1 border-transparent appearance-none dark:focus:border-amber-500 focus:outline-none focus:ring-0 focus:border-amber-500 peer"
                                        placeholder=" " v-model="form.subject" :disabled="(checkAdmin) ? true : false" />
                                    <label for="business_name"
                                        class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-sidebar px-2 peer-focus:px-2 peer-focus:text-amber-500 peer-focus:dark:text-amber-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 left-1">
                                        {{ trans('lang.labels.enter_the_title_of_the_activity') }}
                                    </label>
                                    <span v-if="errors.subject" class="mt-2 text-sm text-red-600">{{ errors.subject[0]
                                    }}</span>
                                </div>
                            </div>
                            <div class="theme-input mt-3">
                                <div class="relative">
                                    <ckeditor class="identify_desc" :editor="editor" v-model="form.description"
                                        :config="editorConfig" tag-name="textarea" :disabled="(checkAdmin) ? true : false"
                                        placeholder="What is the current situation/issue?"></ckeditor>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="w-full">
                                <div class="theme-upload-img">
                                    <!--  -->
                                    <div class="border-2 border-gray-500 border-dashed rounded-lg bg-sidebar">
                                        <label for="dropzone-file"
                                            class="flex flex-col items-center justify-center w-full cursor-pointer">
                                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                                    <svg aria-hidden="true" class="w-10 h-10 mb-3 text-gray-400" fill="none"
                                                        stroke="currentsColor" viewBox="0 0 24 24"
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
                                                    <input id="dropzone-file" type="file" class="hidden" multiple
                                                        :disabled="(checkAdmin) ? true : false"
                                                        @change="onFileChange" /><br>
                                                </div>
                                            </div>
                                        </label>
                                        <div v-if="images" class="w-full p-3">
                                            <div
                                                class="min-h-auto max-h-24 overflow-y-auto grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-3 xl:grid-cols-4 gap-3">
                                                <div v-for="(image, key) in images" :key="image" class="w-full h-24">
                                                    <div class="relative theme-img-hover 4" :id="key">
                                                        <div
                                                            class="absolute top-0 left-0 w-full h-full bg-bgImgOverlay z-10 flex gap-1 items-center justify-center rounded-lg theme-delete-img">
                                                            <button @click="removeImage(key)" type="button"
                                                                class="rounded-lg bg-btnCancelBg hover:bg-amber-500 px-4 py-2 text-center cursor-pointer theme-btn-delete">
                                                                <TrashIcon class="h-5 w-5 text-white m-auto"
                                                                    aria-hidden="true" />
                                                            </button>
                                                            <!-- <a :href="'image' + parseInt(key)"
                                                                    class="rounded-lg bg-btnSubmitBg px-4 py-2 text-center cursor-pointer"
                                                                    download>
                                                                    <CloudArrowDownIcon
                                                                        class="h-5 w-5 text-btnSubmitText m-auto"
                                                                        aria-hidden="true" />
                                                                </a> -->
                                                        </div>
                                                        <img v-bind:ref="'image' + parseInt(key)" alt=""
                                                            class="h-24 w-full object-cover rounded-lg" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!----------Edit images form----------------------->
                                        <div v-if="this.editimages.length > 0" class="w-full p-3">
                                            <div class="themeGalleryBtns flex justify-between flex-wrap mb-4">
                                                <span class="text-base text-gray-400">{{ trans('lang.labels.gallery')
                                                }}</span>
                                                <div class="flex">
                                                    <button class="rounded-sm bg-amber-500 text-xs text-white px-3 py-2"
                                                        v-if="!checkAdmin" type="button" @click="downloadZip()">{{
                                                            trans('lang.labels.download_zip') }}</button>
                                                    <button
                                                        class="rounded-sm bg-btnSubmitBg text-btnSubmitText text-xs px-3 py-2 ml-2"
                                                        type="button" @click="openGallery()">{{
                                                            trans('lang.labels.open_gallery') }}</button>
                                                </div>
                                            </div>
                                            <div
                                                class="min-h-auto max-h-24 overflow-y-auto grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-3 xl:grid-cols-4 gap-3">
                                                <div v-for="(editimage, key) in editimages" :key="editimage">
                                                    <div class="relative theme-img-hover" :id="key">
                                                        <div v-if="!checkAdmin"
                                                            class="absolute top-0 left-0 w-full h-full bg-bgImgOverlay z-10 flex gap-1 items-center justify-center rounded-lg theme-delete-img">
                                                            <button type="button" @click="deleteAttachement(editimage.id)"
                                                                class="rounded-lg bg-btnCancelBg hover:bg-amber-500 px-4 py-2 text-center cursor-pointer theme-btn-delete">
                                                                <TrashIcon class="h-5 w-5 text-white m-auto"
                                                                    aria-hidden="true" />
                                                            </button>
                                                        </div>
                                                        <img class="h-24 w-full object-cover rounded-lg"
                                                        :src="(mediaPath + '/' + editimage.path)" alt="">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div v-if="is_develop == 1">
                        <!-----------Video Form Details------------------------->
                        <div class="mt-6">
                            <div class="grid md:grid-cols-2 sm:grid-cols-1 gap-3">
                                <div class="theme-input">
                                    <label for="business_name" class="text-sm text-gray-500 dark:text-gray-400 ml-2">
                                        {{ trans('lang.labels.video_title') }}
                                    </label>
                                    <div class="relative mt-2">
                                        <input type="text" id="business_name"
                                            class="block px-2.5 pb-2.5 pt-2.5 w-full text-sm text-neutral-100 bg-sidebar rounded-lg border-1 border-transparent appearance-none dark:focus:border-amber-500 focus:outline-none focus:ring-0 focus:border-amber-500 peer"
                                            placeholder=" " v-model="form.video_title"
                                            :disabled="(checkAdmin) ? true : false" />

                                    </div>
                                    <span v-if="errors.video_title" class="mt-2 text-sm text-red-600">{{
                                        errors.video_title[0]
                                    }}</span>
                                </div>
                                <div class="theme-input">
                                    <label for="shoot_location" class="text-sm text-gray-500 dark:text-gray-400 ml-2">
                                        {{ trans('lang.labels.shoot_location') }}
                                    </label>
                                    <div class="relative mt-2">
                                        <input type="text" id="shoot_location"
                                            class="block px-2.5 pb-2.5 pt-2.5 w-full text-sm text-neutral-100 bg-sidebar rounded-lg border-1 border-transparent appearance-none dark:focus:border-amber-500 focus:outline-none focus:ring-0 focus:border-amber-500 peer"
                                            placeholder="" v-model="form.video_location"
                                            :disabled="(checkAdmin) ? true : false" />
                                    </div>
                                    <span v-if="errors.video_location" class="mt-2 text-sm text-red-600">{{
                                        errors.video_location[0]
                                    }}</span>
                                </div>
                                <div class="theme-input">
                                    <label for="shoot_location" class="text-sm text-gray-500 dark:text-gray-400 ml-2">
                                        {{ trans('lang.labels.people_involved') }}
                                    </label>
                                    <div class="relative mt-2">
                                        <input type="number" id="people_involve"
                                            class="block px-2.5 pb-2.5 pt-2.5 w-full text-sm text-neutral-100 bg-sidebar rounded-lg border-1 border-transparent appearance-none dark:focus:border-amber-500 focus:outline-none focus:ring-0 focus:border-amber-500 peer"
                                            placeholder=" " v-model="form.people_involved"
                                            :disabled="(checkAdmin) ? true : false" />
                                    </div>
                                    <span v-if="errors.people_involved" class="mt-2 text-sm text-red-600">{{
                                        errors.people_involved[0]
                                    }}</span>
                                </div>
                                <div class="theme-input">
                                    <label for="shoot_location" class="text-sm text-gray-500 dark:text-gray-400 ml-2">
                                        {{ trans('lang.labels.camera_style') }}
                                    </label>
                                    <div class="relative mt-2">
                                        <input type="text" id="camera_style"
                                            class="block px-2.5 pb-2.5 pt-2.5 w-full text-sm text-neutral-100 bg-sidebar rounded-lg border-1 border-transparent appearance-none dark:focus:border-amber-500 focus:outline-none focus:ring-0 focus:border-amber-500 peer"
                                            placeholder=" " v-model="form.camera_style"
                                            :disabled="(checkAdmin) ? true : false" />
                                    </div>
                                    <span v-if="errors.camera_style" class="mt-2 text-sm text-red-600">{{
                                        errors.camera_style[0]
                                    }}</span>
                                </div>
                                <div class="theme-input">
                                    <label for="shoot_location" class="text-sm text-gray-500 dark:text-gray-400 ml-2">
                                        {{ trans('lang.labels.equipment') }}
                                    </label>
                                    <div class="relative mt-2">
                                        <input type="text" id="equipment"
                                            class="block px-2.5 pb-2.5 pt-2.5 w-full text-sm text-neutral-100 bg-sidebar rounded-lg border-1 border-transparent appearance-none dark:focus:border-amber-500 focus:outline-none focus:ring-0 focus:border-amber-500 peer"
                                            placeholder=" " v-model="form.equipment"
                                            :disabled="(checkAdmin) ? true : false" />
                                    </div>
                                    <span v-if="errors.equipment" class="mt-2 text-sm text-red-600">{{
                                        errors.equipment[0]
                                    }}</span>
                                </div>
                                <div class="theme-input">
                                    <label for="shoot_location" class="text-sm text-gray-500 dark:text-gray-400 ml-2">
                                        {{ trans('lang.labels.video_service_level') }}
                                    </label>
                                    <div class="relative mt-2">
                                        <select v-model="form.video_service_level" id="video_service"
                                            class="videoServiceLevel block px-2.5 pb-2.5 pt-2.5 w-full text-sm text-neutral-100 bg-sidebar 
                                                                                                                                rounded-lg border-1 border-transparent appearance-none dark:focus:border-amber-500 focus:outline-none 
                                                                                                                                focus:ring-0 focus:border-amber-500"
                                            :disabled="(checkAdmin) ? true : false">
                                            <option value="1">Impact (Recommended)</option>
                                            <option disabled>(1). Impact Features List.</option>
                                            <option disabled>(2). Logo on top right</option>
                                            <option disabled>(3). Animated logo top and tail.</option>
                                            <option disabled>(4). Animated logo top and tail.</option>
                                            <option disabled>(5). Bullet Points.</option>
                                            <option disabled>(6). Title screen following animated logo.</option>
                                            <option disabled>(7). Music.</option>
                                            <option disabled>(8). Segment video into stages with seperate title screen.
                                            </option>
                                            <option disabled>(9). Arrange and edit for comfortable viewing.</option>
                                            <option value="2">Arrange & Branding</option>
                                            <option disabled>(1). Arrange & join Individual video clips for comfortable
                                                viewing only + logo top right.</option>
                                            <option value="3">No Service Required</option>
                                            <option disabled>(1). For videos that require no service work by XME. Upload to
                                                platform only.</option>
                                        </select>
                                    </div>
                                    <span v-if="errors.video_service_level" class="mt-2 text-sm text-red-600">{{
                                        errors.video_service_level[0] }}</span>
                                </div>
                                <div class="theme-input">
                                    <label for="shoot_location" class="text-sm text-gray-500 dark:text-gray-400 ml-2">
                                        {{ trans('lang.labels.video_category') }}
                                    </label>
                                    <div class="relative mt-2">
                                        <select v-model="form.video_category" :disabled="(checkAdmin) ? true : false" class="block px-2.5 pb-2.5 pt-2.5 w-full text-sm text-neutral-100 bg-sidebar 
                                                                                                                                rounded-lg border-1 border-transparent appearance-none dark:focus:border-amber-500 focus:outline-none 
                                                                             focus:ring-0 focus:border-amber-500">
                                            <option value="">Video Category</option>
                                            <option value="1">Recruiting</option>
                                            <option value="2">Onboarding</option>
                                            <option value="3">Company Culture</option>
                                            <option value="4">Onboarding</option>
                                            <option value="5">Training</option>
                                            <option value="6">Focus Module</option>
                                            <option value="7">Something else</option>


                                        </select>
                                    </div>
                                    <span v-if="errors.video_category" class="mt-2 text-sm text-red-600">{{
                                        errors.video_category[0] }}</span>
                                </div>
                                <div class="theme-input">
                                    <label for="shoot_location" class="text-sm text-gray-500 dark:text-gray-400 ml-2">
                                        {{ trans('lang.labels.select_develop_type') }}
                                    </label>
                                    <select v-model="form.develop_type" :disabled="(checkAdmin) ? true : false"
                                        class="mt-2 block px-2.5 pb-2.5 pt-2.5 w-full text-sm text-neutral-100 bg-sidebar 
                                                                                                                                rounded-lg border-1 border-transparent appearance-none dark:focus:border-amber-500 focus:outline-none 
                                                                                                                                focus:ring-0 focus:border-amber-500">
                                        <option value="1">Need video editing</option>
                                        <option value="2">Direct upload video</option>
                                        <option value="3">Upload link</option>
                                    </select>
                                    <span v-if="errors.develop_type" class="mt-2 text-sm text-red-600">{{
                                        errors.develop_type[0] }}</span>
                                    <div class="mt-3" v-if="form.develop_type == 2 && !checkAdmin">
                                        <input id="filevideo" class="hidden" type="file" @change="uploadDirectVideo">
                                        <label
                                            class="block px-2.5 pb-2.5 pt-2.5 w-full text-sm text-neutral-100 bg-sidebar 
                                                                                                                            rounded-lg border-1 border-transparent appearance-none dark:focus:border-amber-500 focus:outline-none 
                                                                                                                            focus:ring-0 focus:border-amber-500"
                                            for="filevideo">{{ trans('lang.labels.choose_file') }}<br>
                                            <span class="text-xs">{{ fileName }}</span>
                                        </label>
                                        <span v-if="errors.direct_upload" class="mt-2 text-sm text-red-600">{{
                                            errors.direct_upload[0]
                                        }}</span>
                                    </div>
                                    <div class="mt-3" v-if="directUploadLink != null && form.develop_type == 2">
                                        <input type="text" :value="directUploadLink" readonly
                                            class="block px-2.5 pb-2.5 pt-2.5 w-full text-sm text-neutral-100 bg-sidebar 
                                                                                                                            rounded-lg border-1 border-transparent appearance-none dark:focus:border-amber-500 focus:outline-none 
                                                                                                                            focus:ring-0 focus:border-amber-500">
                                    </div>
                                    <div class="mt-3" v-if="form.develop_type == 3">
                                        <input type="text" v-model="form.upload_link" placeholder="Enter Video Link"
                                            class="block px-2.5 pb-2.5 pt-2.5 w-full text-sm text-neutral-100 bg-sidebar 
                                                                                                                            rounded-lg border-1 border-transparent appearance-none dark:focus:border-amber-500 focus:outline-none 
                                                                                                                            focus:ring-0 focus:border-amber-500">
                                        <span v-if="errors.upload_link" class="mt-2 text-sm text-red-600">{{
                                            errors.upload_link[0]
                                        }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="grid md:grid-cols-2 sm:grid-cols-1 gap-3 mt-4">
                                <div class="theme-input">
                                    <label for="shoot_location" class="text-sm text-gray-500 dark:text-gray-400 ml-2">
                                        {{ trans('lang.labels.video_description') }}
                                    </label>
                                    <div class="relative mt-3">
                                        <ckeditor class="identify_desc" :editor="editor" v-model="form.video_description"
                                            :config="editorConfig" tag-name="textarea"
                                            :disabled="(checkAdmin) ? true : false"
                                            placeholder="Write a short description that will be used as a preview to outline what is in the video content.">
                                        </ckeditor>
                                    </div>
                                    <span v-if="errors.video_description" class="mt-2 text-sm text-red-600">{{
                                        errors.video_description[0] }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-----------Video Form Details------------------------->
            <div v-show="form.develop_type == 1">
                <h5 class="text-white text-2xl font-bold mt-6">Welcome to the Positive Prompt System</h5>
                <ul class="text-gray-500 text-base font-normal space-y-2 mt-4">
                    <li>The PPS builder to guide you through creating your new video.</li>
                    <li>Fill out each step and make videos as you go.</li>
                    <li>See Tutorial here. (Provide link to XME Support and show tutorial)</li>
                </ul>
                <div class="manage-member-table table-manageforms" v-for="(section, index) in form.sections"
                    :key="section.id" :data-user="userIdentity">
                    <pps-form @openCamera="openCamera" @openPpsGallery="openPpsGallery" @openPpsVideo="openPpsVideo"
                        :section="section" ref="ppsform" :key="index" :user_role="this.user.role.name"></pps-form>
                </div>
                <div class="videoCamera" v-if="isCamera">
                    <video-record @closeCamera="closeCamera()" @videoPath="videoPath" :id="sectionId" :form="form"
                        :rowId="sectionRowId"></video-record>
                </div>
            </div>
            <div v-if="!checkAdmin" class="flex items-center justify-end p-6 space-x-2 rounded-b dark:border-gray-600">
                <button type="button" @click="cancel()"
                    class="text-btnCancelText bg-btnCancelBg font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                    {{ trans('lang.modal.cancel') }}
                </button>
                <button :disabled="processing" @click="submitForm()"
                    class="text-btnSubmitText flex items-center bg-btnSubmitBg rounded-lg text-sm font-medium px-5 py-2.5"> 
                    <span v-if="processing" class="animate-spin mr-2">
                        <loading-icon />
                    </span>
                    {{ id != null ? trans('lang.modal.update') : trans('lang.modal.add') }}
                </button>
            </div>
            <gallery-view v-if="isGallery" :imagesData="editimages" :user="user" @deleteImage="deleteAttachement"
                @closeGallery="closeGallery()"></gallery-view>
            <pps-gallery v-if="isPpsGallery" :ppsImages="ppsImages" :section="section"
                @closeImages="closeImages()"></pps-gallery>
            <pps-video v-if="isPpsVideo" :ppsVideo="ppsVideo" @closeVideo="closeVideo()"></pps-video>
        </AuthenticatedLayout>
    </div>
</template>
<script>
import { saveAs } from 'file-saver';
import LoadingIcon from '@/Common/LoadingIcon.vue'
import PpsForm from '@/Components/PpsForm/PpsForm.vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { useAppStore } from "@/store";
import ClassicEditor from '@ckeditor/ckeditor5-build-classic';
import { mapState, mapStores } from "pinia";
import VideoRecord from '@/Components/VideoRecord/VideoRecord.vue'
import GalleryView from '@/Components/Identify/Gallery.vue'
import PpsGallery from '@/Components/Identify/PpsGallery.vue'
import PpsVideo from '@/Components/Identify/PpsVideo.vue'
import GeneralMixin from "@/Mixins/GeneralMixin";
import { isAdmin } from "@/helpers";
import {
    TrashIcon, CloudArrowDownIcon
} from '@heroicons/vue/24/solid'
export default {
    props: ['is_develop', 'id', 'user'],
    mixins: [GeneralMixin],
    components: {
        AuthenticatedLayout,
        PpsForm,
        TrashIcon, CloudArrowDownIcon,
        VideoRecord, GalleryView, PpsGallery,
        PpsVideo,LoadingIcon
    },
    watch: {
        isGallery: function () {
            document.body.style.overflow = this.isGallery ? "hidden" : "";
        },
        isPpsGallery: function () {
            document.body.style.overflow = this.isPpsGallery ? "hidden" : "";
        },
        isPpsVideo: function () {
            document.body.style.overflow = this.isPpsVideo ? "hidden" : "";
        },
        isCamera: function () {
            document.body.style.overflow = this.isCamera ? "hidden" : "";
        }
    },
    data() {
        return {
            isGallery: false,
            isPpsGallery: false,
            isPpsVideo: false,
            form: {
                sections: [],
                subject: '',
                priority: '0',
                develop_type: '',

            },
            fileName: '',
            processing: false,
            ppsForm: [],
            editimages: [],
            preview: null,
            developSubject: [],
            formUrl: "",

            editor: ClassicEditor,
            editorData: '<p>Content of the editor.</p>',
            editorConfig: {
                toolbar: ['heading', 'bold', 'italic', '|', 'NumberedList', 'BulletedList', 'resize'],
                resize_dir: 'both'
            },
            images: [],
            ppsImages: [],
            ppsVideo: '',
            errors: {
                subject: "",
                video_title: "",
                video_location: "",
                people_involved: "",
                camera_style: "",
                equipment: "",
                video_service_level: "",
                video_category: '',
                video_description: '',
                develop_type: '',
                upload_link: '',
            },
            direct_upload: '',
            directUploadLink: null,
            isCamera: false,
            editImagesPath: [],
            sectionId: null,
            sectionRowId: null,
            imagevideo: [],

            /* dropdown */
            serviceValue: 'Select',
            list: ["Orange", "Apple", "Kiwi", "Lemon", "Pineapple"],
            visible: false,
            // list: [
            //     {title:'Impact (Recommended)', desc:''},
            //     {title:'Arrange & Branding', desc:''},
            //     {title:'Impact (Recommended)', desc:''}
            // ],

            value: '',
            options: ['<p>Arrange & Branding</p><span>Arrange & join Individual video clips for comfortable viewing only + logo top right.</span>']
        }
    },
    computed: {
        ...mapStores(useAppStore),
        ...mapState(useAppStore, [
            "unreadNotificationCount",
            "recentNotifications",
            "user", 'userRole', 'userCompany'
        ]),
        checkAdmin() {
            let role = this.appStore.userRole;
            return isAdmin(role);
        },
    },
    mounted() {
        this.getSection();
        if (this.id != null) {
            this.editData();
            this.formUrl = "/api/update-subject/" + this.id;
        }
        else {
            this.formUrl = "/api/create-subject";
        }
        // Do something useful with the data in the template
    },
    methods: {
        toggle() {
            this.visible = !this.visible;
        },
        select(option) {
            this.serviceValue = option;
        },

        getSection() {
            let that = this;
            that.form.sections = [];
            const headers = { "Content-Type": "application/json" };
            fetch('/api/pps-sections', headers)
                .then(response => response.json())
                .then(data => {
                    that.form.sections = data.data;
                    that.form.sections.forEach(element => {
                        if (!element.section_detail) {
                            let row = {
                                shot: '',
                                content: '',
                                type: '',
                                images: null,
                                newImages: null,
                                videos: null
                            };
                            element.section_detail = [];
                            element.section_detail.push(row);
                        }
                    });
                    console.log(that.form.sections);
                })
        },
        async submitForm() {
            this.processing = true;
            let that = this;
            let fullFormData = new FormData();
            for (let i = 0; i < that.images.length; i++) {
                let file = that.images[i];
                fullFormData.append('files[' + i + ']', file);
            }
            let developType = ((that.form.develop_type == 0) ? '' : that.form.develop_type);
            let videoCategory = ((that.form.video_category == 0) ? '' : that.form.video_category);

            fullFormData.append('priority', that.form.priority);
            fullFormData.append('description', that.form.description ?? '');
            fullFormData.append('subject', that.form.subject);
            if (that.is_develop == 1) {
                fullFormData.append('video_title', that.form.video_title);
                fullFormData.append('video_location', that.form.video_location);
                fullFormData.append('people_involved', that.form.people_involved);
                fullFormData.append('equipment', that.form.equipment);
                fullFormData.append('video_service_level', that.form.video_service_level);
                fullFormData.append('camera_style', that.form.camera_style);
                fullFormData.append('video_category', videoCategory);
                fullFormData.append('video_description', that.form.video_description);
                fullFormData.append('develop_type', developType);
                if (developType == 1) {
                    fullFormData = await this.createSectionsFormData(fullFormData);
                } else if (developType == 2) {
                    fullFormData.append('direct_upload', that.direct_upload);
                    fullFormData.append('upload_link', '');
                } else if (developType == 3) {
                    fullFormData.append('upload_link', that.form.upload_link);
                    fullFormData.append('direct_upload', '');
                }
            } else {
                fullFormData = await this.createSectionsFormData(fullFormData);
            }
            fullFormData.append('develop', that.is_develop);
            axios.post(that.formUrl, fullFormData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            })
                .then((res) => {
                    //Perform Success Action
                    if (res.status == 200) {
                        if (that.is_develop == 1) {
                            that.$inertia.visit(route('app.develop'));
                        }
                        else if (that.is_develop == 0) {
                            that.$inertia.visit(route('app.identify'));
                        }
                        let notification = {
                            heading: 'success',
                            subHeading: res.data.message,
                            type: "success",
                        };
                        that.appStore.setNotification(notification);
                    }
                })
                .catch((error) => {
                    // error.response.status Check status code
                    that.errors = error.response.data.errors;
                }).finally(() => {
                    that.processing = false;
                    //Perform action in always
                });
        },
        async createSectionsFormData(formData) {
            const promises = this.form.sections.map(async (element) => {
                if (Array.isArray(element.section_detail)) {
                    for (let i = 0; i < element.section_detail.length; i++) {
                        formData.append('section_data[' + element.id + '][' + i + '][data]', JSON.stringify(element.section_detail[i]));
                        if (element.section_detail[i].newImages) {
                            for (let j = 0; j < element.section_detail[i].newImages.length; j++) {
                                formData.append('section_data[' + element.id + '][' + i + '][newImages][' + j + ']', element.section_detail[i].newImages[j]);
                            }
                        }
                        if (element.section_detail[i].videos) {
                            if (typeof element.section_detail[i].videos != "string") {
                                await this.uploadFile(element.section_detail[i].videos, 'video_' + element.id + '_' + i + '.mp4');
                            }   
                            formData.append('section_data[' + element.id + '][' + i + '][videos]', 'company/' + this.appStore.userCompany.id + '/media/subject/' + this.form.id + '/video_' + element.id + '_' + i + '.mp4');
                        }
                    }
                }
            });
            await Promise.all(promises);
            return formData;
        },
        async uploadFile(file, name) {
            const uploadPromises = [];
            const chunkSize = 1024 * 1024 * 5.5; // 5.5MB
            const totalChunks = Math.ceil(file.size / chunkSize);
            let response = await this.createMultipartUpload(name);
            const uploadId = response.data.uploadId;
            const parts = [];
            console.log(chunkSize, file.size, totalChunks);
               
            // Create multiple Web Workers for concurrent chunk upload
            for (let i = 0; i < totalChunks; i++) {
                const start = i * chunkSize;
                const end = Math.min(start + chunkSize, file.size);
                const chunk = file.slice(start, end);
                const partNumber = i + 1;
                const formData = new FormData();
                formData.append('file', chunk);
                formData.append('partNumber', partNumber);
                formData.append('uploadId', uploadId);
                formData.append('name', name);

                // send the chunk data to the server
                const part = await this.uploadChunk(formData);
                
                parts.push({ PartNumber: partNumber, ETag: part.data.ETag });
            }
            uploadPromises.push(this.completeMultipartUpload(uploadId, parts, name));
           
            // for (let i = 0; i < chunks; i++) {
            //     const start = i * chunkSize;
            //     const end = Math.min(start + chunkSize, file.size);
            //     const chunk = file.slice(start, end);
            //     const formData = new FormData();
            //     formData.append('file', chunk);
            //     formData.append('chunk', i);
            //     formData.append('chunks', chunks);
            //     formData.append('name', name + '.mp4');
            //     // send the chunk data to the server
            //     uploadPromises.push(this.uploadChunk(formData));
            // }
            return Promise.all(uploadPromises);
        },
        async uploadChunk(formData) {
            try {
                const url = route('upload-chunk');
                formData.append('subject_id', this.id);
                const response = await axios.post(url, formData, {
                    headers: { 'Content-Type': 'multipart/form-data' }
                });
                return response.data;
            } catch (error) {
                console.error(error);
            }
        },
        
        async createMultipartUpload(filename) {
            const response = await axios.post('/api/s3/multipart-upload', { 'name' : filename, 'subject_id': this.id });
            return response.data;
        },

        async completeMultipartUpload(uploadId, parts, filename) {
            await axios.post('/api/s3/complete-upload', { uploadId, parts, 'name' : filename,  'subject_id': this.id });
        },
        editData() {
            axios
                .get("/api/edit-identify/" + this.id)
                .then((res) => {
                    this.form = res.data.data.subject;
                    this.editimages = res.data.data.subject.attachements;
                    if (res.data.data.subject.directUploadLink != '') {
                        this.directUploadLink = this.mediaPath + '/' + res.data.data.subject.directUploadLink
                    }
                    this.form.sections = res.data.data.sections;
                    this.form.sections.forEach(element => {
                        if (!element.section_detail || (element.section_detail && element.section_detail.length == 0)) {
                            let row = {
                                shot: '',
                                content: '',
                                type: '',
                                images: null,
                                video: null,
                            };
                            element.section_detail = [];
                            element.section_detail.push(row);
                        }
                    });
                })
                .catch((error) => {
                    // error.response.status Check status code
                })
                .finally(() => {
                    //Perform action in always
                });
        },
        onFileChange(e) {
            this.images = [];
            var selectedFiles = e.target.files;
            for (let i = 0; i < selectedFiles.length; i++) {
                this.images.push(selectedFiles[i]);
                let reader = new FileReader(); //instantiate a new file reader
                reader.addEventListener('load', function () {
                    this.$refs['image' + parseInt(i)][0].src = reader.result;
                }.bind(this), false);  //add event listener

                reader.readAsDataURL(this.images[i]);
            }
        },
        videoPath: function (path) {
            console.log(path)
        },
        uploadDirectVideo(event) {
            let input = event.target;
            if (input.files) {
                let reader = new FileReader();
                reader.onload = (e) => {
                    this.preview = e.target.result;
                }
                reader.readAsDataURL(input.files[0]);
                this.direct_upload = input.files[0];
                this.fileName = input.files[0].name;
            }
        },
        deleteAttachement: function (id) {
            let that = this;
            axios.delete('/api/delete-attachment/' + id)
                .then((res) => {
                    // assign state users with response data
                    that.editData();
                })
                .catch((error) => {
                });
        },
        removeImage(i) {
            let that = this;
            that.images.splice(i, 1);
        },
        cancel() {
            this.form = {};
        },
        openCamera: function (args) {
            this.sectionId = args.sectionId;
            this.sectionRowId = args.rowId;
            this.isCamera = true;
        },
        closeCamera() {
            this.sectionId = null;
            this.sectionRowId = null;
            this.isCamera = false;
        },
        openGallery() {
            this.isGallery = true;
        },
        closeGallery() {
            this.isGallery = false;
        },
        closeVideo() {
            this.isPpsVideo = false;
        },
        changeVideoLevel(e) {

        },
        closeImages() {
            this.isPpsGallery = false;
        },
        openPpsGallery: function (imgs) {
            this.isPpsGallery = true;
            this.ppsImages = imgs;
        },
        openPpsVideo: function (video) {
            this.isPpsVideo = true;
            this.ppsVideo = video;
        },
        async downloadZip() {
            axios({
                url: "/api/download-images/" + this.form.id,
                method: "GET",
                responseType: "blob", // important
            }).then((response) => {
                // Service that handles ajax call
                const url = window.URL.createObjectURL(new Blob([response.data]));
                const link = document.createElement("a");
                link.href = url;
                link.setAttribute("download", this.document.name);
                document.body.appendChild(link);
                link.click();
                link.remove();
            });
        }
    },
    created() {
        this.appStore.userLogin(this.user);
    },

}
</script>
