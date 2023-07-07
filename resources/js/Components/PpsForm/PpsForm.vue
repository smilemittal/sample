<template>
    <div class="h-full drop-shadow-md rounded-3xl mt-5">
        <div class="relative shadow-md sm:rounded-lg mt-5">
            <div class="px-5 py-6 rounded-t-2xl bg-sidebar mt-8">
                <h3
                    class="text-xl text-gray-200 w-fit p-2 outline-0 focus:outline-0 focus:border-amber-500 border-1 rounded-lg">
                    {{ section.title }}</h3>
                <span class="text-base text-gray-500 focus:outline-0 outline-0 p-2" v-html="section.sub_heading"></span>
            </div>
            <div class="overflow-x-auto">
                <table class="theme-table themePpsTable w-full text-sm text-left text-gray-500 dark:text-gray-400 mt-0">
                    <tbody>
                        <tr v-for="(data, index) in section.section_detail" :key="index"
                            class="border-b dark:border-gray-700 text-base text-neutral-300 hover:text-white">
                            <td class="p-3">
                                <div class="grid grid-cols-4 gap-3 items-center">
                                    <div>
                                        <span class="text-gray-400">{{ trans('lang.labels.shot') }}</span>
                                        <input type="text" id=""
                                            class="mt-2 block px-2.5 pb-2.5 pt-2.5 w-full text-sm text-neutral-100 bg-sidebar rounded-lg border-1 border-gray-600 appearance-none dark:focus:border-amber-500 focus:outline-none focus:ring-0 focus:border-amber-500"
                                            placeholder="Enter Shot Name" v-model="data.shot"
                                            :disabled="(user_role == 'superadmin') ? true : false" />
                                    </div>
                                    <div>
                                        <span class="text-gray-400">{{ trans('lang.labels.type') }}</span>
                                        <select id="countries" v-model="data.type"
                                            class="mt-2 w-full text-sm text-neutral-100 bg-sidebar rounded-lg border-1 border-gray-600 appearance-none dark:focus:border-amber-500 focus:outline-none focus:ring-0 focus:border-amber-500"
                                            :disabled="(user_role == 'superadmin') ? true : false">
                                            <option>Choose an option</option>
                                            <option value="1">A Roll (Main Video)</option>
                                            <option value="2">B Roll (Different angle)</option>
                                            <option value="3">Voice over (Audio file only)</option>
                                            <option value="4">Photo (Still image)</option>
                                        </select>
                                    </div>
                                    <div>
                                        <span class="text-gray-400">{{ trans('lang.labels.actions') }}</span>
                                        <div class="mt-2 flex gap-3 items-center">
                                            <div class="relative" v-if="index != 0 && user_role != 'superadmin'">
                                                <button type="button" @click="deleteRow(index)"
                                                    class="group px-3 py-2 bg-btnCancelBg rounded-lg hover:bg-amber-500 themeCamera">
                                                    <MinusCircleIcon class="h-6 w-6 text-btnCancelText m-auto"
                                                        aria-hidden="true" />
                                                    <span class="absolute -left-4 -top-6 z-30 text-xs themePPSTags">{{ trans('lang.labels.delete_row') }}</span>
                                                </button>
                                            </div>
                                            <div class="relative" v-if="index == 0 && user_role != 'superadmin'">
                                                <button type="button" @click="addRow(index)"
                                                    class="group px-3 py-2 bg-btnSubmitBg rounded-lg hover:bg-amber-500 themeCamera">
                                                    <PlusCircleIcon class="h-6 w-6 text-btnSubmitText m-auto"
                                                        aria-hidden="true" />
                                                    <span class="absolute -left-4 -top-6 z-30 text-xs themePPSTags">{{ trans('lang.labels.add_row') }}</span>
                                                </button>
                                            </div>
                                            <div class="relative">
                                                <button type="button" @click="openCamera(section.id, index)"
                                                    class="group px-3 py-2 bg-btnSubmitBg rounded-lg hover:bg-amber-500 themeCamera">
                                                    <CameraIcon class="h-6 w-6 text-btnSubmitText m-auto"
                                                        aria-hidden="true" />
                                                    <span class="absolute -left-4 -top-6 z-30 text-xs themePPSTags">{{ trans('lang.labels.open_camera') }}</span>
                                                </button>
                                            </div>
                                            <div class="relative">
                                                <input @change="onFileChange($event, index)" class="themePpsImg opacity-0" type="file"
                                                    :id="'file_' + section.id + index" multiple />
                                                <label type="button"
                                                    class="group px-3 py-2 bg-btnSubmitBg rounded-lg hover:bg-amber-500 themeCamera"
                                                    :for="'file_' + section.id + index">
                                                    <PhotoIcon class="h-6 w-6 text-btnSubmitText m-auto"
                                                        aria-hidden="true" />
                                                    <span class="absolute -left-4 -top-6 z-30 text-xs themePPSTags">{{ trans('lang.labels.upload_images') }}</span>
                                                </label>
                                            </div>
                                            <div v-if="data.newImages || (data.images && data.images.length > 0)" class="relative">
                                                <button type="button" @click="openGallery(data)"
                                                    class="group px-3 py-2 bg-btnSubmitBg rounded-lg hover:bg-amber-500 themeCamera">
                                                    <EyeIcon class="h-6 w-6 text-btnSubmitText m-auto" aria-hidden="true" />
                                                    <span class="absolute -left-4 -top-6 z-30 text-xs themePPSTags">{{ trans('lang.labels.view_images') }}</span>
                                                </button>
                                            </div>
                                            <div v-if="data.videos != null && (Object.keys(data.videos).length > 0 || data.videos.length > 0)" class="relative">
                                                <button type="button" @click="openVideo(data.videos)"
                                                    class="group px-3 py-2 bg-btnSubmitBg rounded-lg hover:bg-amber-500 themeCamera themeVideo">
                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="h-6 w-6">
                                                        <path
                                                            d="M0 96C0 60.7 28.7 32 64 32H448c35.3 0 64 28.7 64 64V416c0 35.3-28.7 64-64 64H64c-35.3 0-64-28.7-64-64V96zM48 368v32c0 8.8 7.2 16 16 16H96c8.8 0 16-7.2 16-16V368c0-8.8-7.2-16-16-16H64c-8.8 0-16 7.2-16 16zm368-16c-8.8 0-16 7.2-16 16v32c0 8.8 7.2 16 16 16h32c8.8 0 16-7.2 16-16V368c0-8.8-7.2-16-16-16H416zM48 240v32c0 8.8 7.2 16 16 16H96c8.8 0 16-7.2 16-16V240c0-8.8-7.2-16-16-16H64c-8.8 0-16 7.2-16 16zm368-16c-8.8 0-16 7.2-16 16v32c0 8.8 7.2 16 16 16h32c8.8 0 16-7.2 16-16V240c0-8.8-7.2-16-16-16H416zM48 112v32c0 8.8 7.2 16 16 16H96c8.8 0 16-7.2 16-16V112c0-8.8-7.2-16-16-16H64c-8.8 0-16 7.2-16 16zM416 96c-8.8 0-16 7.2-16 16v32c0 8.8 7.2 16 16 16h32c8.8 0 16-7.2 16-16V112c0-8.8-7.2-16-16-16H416zM160 128v64c0 17.7 14.3 32 32 32H320c17.7 0 32-14.3 32-32V128c0-17.7-14.3-32-32-32H192c-17.7 0-32 14.3-32 32zm32 160c-17.7 0-32 14.3-32 32v64c0 17.7 14.3 32 32 32H320c17.7 0 32-14.3 32-32V320c0-17.7-14.3-32-32-32H192z" />
                                                    </svg>
                                                    <span class="absolute -left-4 -top-6 z-30 text-xs themePPSTags">{{ trans('lang.labels.view_video') }}</span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <ckeditor class="identify_desc" :editor="editor" v-model="data.content"
                                        :config="editorConfig" tag-name="textarea"
                                        :disabled="(user_role == 'superadmin') ? true : false"
                                        placeholder="Type Here"></ckeditor>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>
<script>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

import ClassicEditor from '@ckeditor/ckeditor5-build-classic';
import {
    PlusCircleIcon, MinusCircleIcon, CameraIcon, PhotoIcon, EyeIcon
} from '@heroicons/vue/24/solid'
export default {
    props: ['section', 'user_role'],
    components: {
        AuthenticatedLayout,
        
        MinusCircleIcon, PlusCircleIcon, CameraIcon,
        PhotoIcon, EyeIcon
    },
    data() {
        return {
            editor: ClassicEditor,
            editorData: '<p>Content of the editor.</p>',
            editorConfig: {
                toolbar: ['heading', 'bold', 'italic', '|', 'NumberedList', 'BulletedList'],
                resize_dir: 'both',
            },
        }
    },
    methods: {
        addRow() {
            let row = {
                shot: '',
                content: '',
                type: '',
                images: null,
                videos: null,
            };
            if (!this.section.section_detail) {
                this.section.section_detail = [];
            }
            this.section.section_detail.push(row);
        },
        deleteRow(index) {
            this.section.section_detail.splice(index, 1);
        },
        submitForm() {
            this.$validator.validateAll()
        },
        openCamera(sectionId, id) {
            this.$emit('openCamera', { sectionId: sectionId, rowId: id });
        },
        onFileChange: function (e, id) {
            let that = this;
            that.section['section_detail'][id]['newImages'] = e.target.files;
        },
        openGallery:function(data) {
            let imgs = [];
            if(data.newImages) {
                imgs = data.newImages;
            } else if(data.images && data.images.length > 0) {
                imgs = data.images;
            }
            
            this.$emit('openPpsGallery', imgs);
        },
        openVideo(video) {
            this.$emit('openPpsVideo', video);
            console.log(video);
        }
    },
    created(){
        // console.table(this.section);
    }
}
</script>
