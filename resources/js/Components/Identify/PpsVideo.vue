<template>
    <!-- Main modal -->
    <div id="createCompany" class="modal-open" data-open="modal">
        <div class="bg-bgModal fixed top-0 flex items-center justify-center left-0 right-0 z-50 w-full p-4 overflow-x-hidden overflow-y-auto inset-0
                                                                                 h-full h-full">
            <div class="relative w-full h-auto max-w-4xl md:h-auto">
                <div class="relative rounded-lg shadow bg-body">
                    <!-- Modal header -->
                    <div class="flex items-start justify-between p-4 rounded-t">
                        <h3 class="text-xl font-semibold text-gray-300">
                            Preview Video
                        </h3>
                        <button type="button" @click="isClose()" class="">
                            <XCircleIcon class="h-9 w-9 text-white" aria-hidden="true" />
                        </button>
                    </div>
                    <div class="mb-5 p-5">
                        <!-- <video id="myPPsVideo" class="video-js" controls preload="auto" width="640" height="264">
                            <source :src="videoLink" type="video/mp4" />
                            <p class="vjs-no-js">
                                To view this video please enable JavaScript, and consider upgrading to a
                                web browser that
                            </p>
                        </video> -->
                        <video id="myPPsVideo" class="video-js" controls preload="auto" width="640" height="264" v-if="videoLink.length > 0"
                            :src="videoLink">
                        </video>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import {
    TrashIcon, XCircleIcon
} from '@heroicons/vue/24/solid'
export default {
    name: "PpsGallery",
    props: ['ppsVideo'],
    components: {
        TrashIcon, XCircleIcon,
    },
    data() {
        return {
            videoLink: ''
        };
    },
    methods: {
        isClose() {
            this.$emit("closeVideo");
        },
    },
    mounted() {
        if( this.ppsVideo instanceof Blob ) {
            this.videoLink = URL.createObjectURL( this.ppsVideo );
        } else {
            this.videoLink = this.ppsVideo;
        }
    },
};
</script>
