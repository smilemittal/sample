/* eslint-disable */
/* workaround browser issues */

var isSafari = /^((?!chrome|android).)*safari/i.test(navigator.userAgent);
var isEdge = /Edge/.test(navigator.userAgent);
var isOpera = !!window.opera || navigator.userAgent.indexOf("OPR/") !== -1;

function applyAudioWorkaround() {
    if (isSafari || isEdge) {
        if (isSafari && window.MediaRecorder !== undefined) {
            // this version of Safari has MediaRecorder
            // but use the only supported mime type
            options.plugins.record.audioMimeType = "audio/mp4";
        } else {
            options.plugins.record.audioRecorderType = StereoAudioRecorder;
            options.plugins.record.audioSampleRate = 44100;
            options.plugins.record.audioBufferSize = 4096;
            options.plugins.record.audioChannels = 2;
        }

        console.log("applied audio workarounds for this browser");
    }
}

function applyVideoWorkaround() {
    // use correct video mimetype for opera
    if (isOpera) {
        options.plugins.record.videoMimeType = "video/webm;codecs=vp8"; 
    }
}

function applyScreenWorkaround() {
    // Polyfill in Firefox.
    // See https://blog.mozilla.org/webrtc/getdisplaymedia-now-available-in-adapter-js/
    if (adapter.browserDetails.browser == "firefox") {
        adapter.browserShim.shimGetDisplayMedia(window, "screen");
    }
}
