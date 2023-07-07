<template>
    <div id="createCompany" class="modal-open" data-open="modal">
        <div class="bg-bgModal fixed top-0 flex items-center justify-center left-0 right-0 z-50 w-full p-4 overflow-x-hidden overflow-y-auto inset-0
             h-full">
            <div class="relative videoRecordCamera w-full h-auto max-w-4xl md:h-auto">
                <div class="relative rounded-lg shadow bg-body">
                    <div class="">
                        <div class="relative themeCameraClose relative w-full pb-2">
                            <p class="text-xs md:text-base text-gray-400 pl-4 pr-8 pt-2">Click Record button to capture
                                video.</p>
                            <button type="button" @click="closeCamera()" class="absolute right-1 top-2">
                                <XCircleIcon class="h-7 w-7 text-white" aria-hidden="true" />
                            </button>
                        </div>
                        <div class="p-3 relative">
                            <video id="myVideo" class="video-js vjs-default-skin" playsinline></video>
                            <div v-if="isUpload"
                                class="absolute top-0 left-0 flex items-center justify-center w-full h-full z-10 bgVideoUpload">
                                <div role="status">
                                    <svg aria-hidden="true"
                                        class="inline w-14 h-14 mr-2 text-gray-200 animate-spin dark:text-gray-600 fill-yellow-400"
                                        viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z"
                                            fill="currentColor" />
                                        <path
                                            d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z"
                                            fill="currentFill" />
                                    </svg>
                                    <span class="sr-only">Loading...</span>
                                </div>
                            </div>
                        </div>
                        <div class="flex gap-3 bg-sidebar p-2">
                            <button type="button"
                                class="bg-bgAmberTag text-amber-500 text-sm px-2 rounded-sm btnPlayVideoRecord flex"
                                @click.prevent="startRecording()" v-bind:disabled="isStartRecording" id="btnStart">
                                <svg xmlns="http://www.w3.org/2000/svg" width="38" height="38" viewBox="0 0 24 24">
                                    <rect x="0" y="0" width="24" height="24" fill="none" stroke="none" />
                                    <path fill="currentColor"
                                        d="M12.5 5A7.5 7.5 0 0 0 5 12.5a7.5 7.5 0 0 0 7.5 7.5a7.5 7.5 0 0 0 7.5-7.5A7.5 7.5 0 0 0 12.5 5M7 10h2a1 1 0 0 1 1 1v1c0 .5-.38.9-.86.97L10.31 15H9.15L8 13v2H7m5-5h2v1h-2v1h2v1h-2v1h2v1h-2a1 1 0 0 1-1-1v-3a1 1 0 0 1 1-1m4 0h2v1h-2v3h2v1h-2a1 1 0 0 1-1-1v-3a1 1 0 0 1 1-1m-8 1v1h1v-1" />
                                </svg>
                            </button>
                            <button type="button" class="bg-btnSubmitBg cursor-pointer flex items-center text-white text-sm px-2 rounded-sm"
                                @click.prevent="submitVideo()" v-bind:disabled="isSaveDisabled" id="btnSave">
                                <CloudArrowUpIcon class="h-6 w-6 inline-block mr-1 text-btnSubmitText" aria-hidden="true" /> Save Video
                            </button>
                            <button type="button" v-if="videoDevices && videoDevices.length > 1"
                                class="bg-amber-500 text-white text-sm px-2 py-1 rounded-sm btnPlayVideoRecord"
                                @click="cameraSwitch()" v-bind:disabled="isSwitchCamera">
                                <ArrowPathRoundedSquareIcon class="h-6 w-6 text-white" aria-hidden="true" />
                            </button>
                            <!-- <button type="button"
                                class="bg-btnCancelBg text-btnCancelText text-sm px-2 rounded-sm flex items-center"
                                @click="closeCamera()">
                                <XCircleIcon class="h-6 w-6 text-btnCancelText mr-1" aria-hidden="true" />
                                Close Camera
                            </button> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import {
    XCircleIcon, PlayCircleIcon, ArrowPathRoundedSquareIcon,
    StopCircleIcon, CloudArrowUpIcon, CameraIcon
} from '@heroicons/vue/24/solid'
import 'video.js/dist/video-js.css'
import 'videojs-record/dist/css/videojs.record.css'
import videojs from 'video.js'

import 'webrtc-adapter'
import RecordRTC from 'recordrtc'


import Record from 'videojs-record/dist/videojs.record.js'

import '@/browser-workarounds.js'

export default {
    props: {
        id: String,
        form: Object,
        rowId: Number
    },
    components: {
        XCircleIcon,
        PlayCircleIcon,
        ArrowPathRoundedSquareIcon,
        StopCircleIcon,
        CloudArrowUpIcon,
        CameraIcon
    },
    data() {
        return {
            isUpload: false,
            videoDevices: null,
            devices: null,
            deviceId: '',
            url: '',
            blobFile: '',
            player: null,
            isSaveDisabled: true,
            isStartRecording: false,
            isSwitchCamera: false,
            submitText: 'Submit',
            options: {
                video: {
                    facingMode: 'user',
                },
                controls: true,
                autoplay: true,
                fluid: false,
                loop: false,
                width: 540,
                height: 360,
                DeviceButton: true,
                bigPlayButton: true,
                playbackRates: [0.5, 1, 1.5, 2],
                controlBar: {
                    deviceButton: false,
                    recordToggle: false,
                    pipToggle: false,
                    fullscreenToggle: true,
                },
                plugins: {
                    record: {
                        pip: false,
                        audio: true,
                        video: {
                            facingMode: 'user',
                        },
                        maxLength: 1500,
                        debug: true
                    }
                }
            },
        };
    },
    mounted() {
        /* eslint-disable no-console */
        this.player = videojs('#myVideo', this.options, () => {
            // print version information at startup
            let msg = 'Using video.js ' + videojs.VERSION +
                ' with videojs-record ' + videojs.getPluginVersion('record') +
                ' and recordrtc ' + RecordRTC.version;
            videojs.log(msg);
        });

        this.player.one('deviceReady', () => {
            this.player.record().enumerateDevices();
        });

        // this.player.on('startRecord', function () {
        //     console.log('started recording!');
        // });

        this.player.on('finishRecord', () => {
            console.log('finishRecord');
            this.submitVideo();
        });

        this.player.on('error', (element, error) => {
            console.log(element, error);
        });

        this.player.on('enumerateReady', () => {
            this.devices = this.player.record().devices;
            let deviceInfo, i;
            this.videoDevices = [];
            for (i = 0; i < this.devices.length; ++i) {
                deviceInfo = this.devices[i];
                if (deviceInfo.kind === 'videoinput') {
                    this.videoDevices.push(this.devices[i]);
                }
            }
            if (this.videoDevices.length > 1) {
                this.deviceId = this.videoDevices[0].deviceId;
                this.player.record().setVideoInput(this.deviceId);
                this.isSwitchCamera = false;
            }
        });
        this.player.record().getDevice();
    },
    beforeDestroy() {
        if (this.player) {
            this.player.dispose();
        }
    },
    methods: {
        startRecording() {
            console.log('startRecording');
            this.isStartRecording = true;
            this.isSaveDisabled = false;
            this.isSwitchCamera = true;
            this.player.record().start();
        },
        submitVideo() {
            let that = this;
            that.isSaveDisabled = true;
            that.isStartRecording = false;
            that.isSwitchCamera = false;
            that.player.record().stopDevice();
            that.form.sections.forEach(element => {
                if (element.id == that.id) {
                    element.section_detail[that.rowId].videos = this.player.recordedData;
                    console.log("Video record : " + this.player.recordedData);
                }
            });
        },
        closeCamera() {
            //this.submitVideo();
            this.player.record().stopDevice();
            this.player.reset();
            if (this.player) {
                this.player.dispose();
            }
            this.$emit('closeCamera');
        },
        cameraSwitch() {
            let that = this;
            that.videoDevices.every(function (element) {
                if (element.deviceId != that.deviceId) {
                    that.deviceId = element.deviceId;
                    return false;
                }
                return true;
            });
            try {
                // change video input device
                that.player.record().setVideoInput(that.deviceId);
            } catch (error) {
                console.warn("camera error" + error);
            }
        },
    },
}
</script>