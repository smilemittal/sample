<template>
    <!-- Main modal -->
    <div id="createCompany" class="modal-open" data-open="modal">
        <div
            class="bg-bgModal fixed top-0 flex items-center justify-center left-0 right-0 z-50 w-full p-4 overflow-x-hidden overflow-y-auto inset-0
                                                                                                                                                                     h-full h-full">
            <div class="relative w-full h-auto max-w-xl md:h-auto">
                <div class="relative rounded-lg shadow bg-body">
                    <!-- Modal header -->
                    <div class="flex items-start justify-between p-4 rounded-t">
                        <h3 class="text-xl font-semibold text-gray-300">
                            Gallery
                        </h3>
                        <button type="button" @click="isClose()" class="">
                            <XCircleIcon class="h-9 w-9 text-white" aria-hidden="true" />
                        </button>
                    </div>
                    <div class="px-8">
                        <carousel class="w-full" :items-to-show="1">
                            <slide v-for="editimage in images" :key="editimage">
                                <div class="relative">
                                    <div v-if="this.user.role.name != 'superadmin'"
                                        class="absolute top-0 left-0 w-full h-full bg-bgImgOverlay z-10 flex gap-1 items-center justify-center rounded-lg theme-delete-img">
                                        <button type="button" @click="deleteAttachement(editimage.id)"
                                            class="rounded-lg bg-btnCancelBg hover:bg-amber-500 px-4 py-2 text-center cursor-pointer theme-btn-delete">
                                            <TrashIcon class="h-5 w-5 text-white m-auto" aria-hidden="true" />
                                        </button>
                                    </div>
                                    <img class="rounded-lg" :src="(mediaPath + '/' + editimage.path)" alt="">
                                </div>
                            </slide>

                            <template #addons>
                                <navigation />
                            </template>
                        </carousel>
                    </div>

                    <!-- Modal footer -->
                    <div class="flex items-center justify-end p-6 space-x-2 rounded-b dark:border-gray-600">
                        <button @click="isClose()" type="button"
                            class="text-btnCancelText bg-btnCancelBg font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

import {
    ExclamationTriangleIcon, XCircleIcon,
    ChevronDoubleLeftIcon, ChevronDoubleRightIcon,
    TrashIcon
} from '@heroicons/vue/24/solid'


import 'vue3-carousel/dist/carousel.css'
import { Carousel, Slide, Pagination, Navigation } from 'vue3-carousel'

export default {
    name: "Gallery",
    props: ['imagesData', 'user'],
    components: {
        ExclamationTriangleIcon,
        XCircleIcon,
        ChevronDoubleLeftIcon, ChevronDoubleRightIcon,
        TrashIcon,
        Carousel,
        Slide,
        Pagination,
        Navigation,
    },
    data() {
        return {
            formProcess: false,
            readonly: false,
            images: this.imagesData,
        };
    },
    methods: {
        isClose() {
            this.$emit("closeGallery");
        },
        processingFunc() {
            this.formProcess = false;
        },
        deleteAttachement(id) {
            this.$emit('deleteImage', id);
        }
    },
    created() {
    }
};
</script>