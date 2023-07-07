<template>
    <div class="bg-gray-200 py-3 rounded-lg mt-10">
        <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-center flex-wrap lg:flex-nowrap">
            <span class="text-xl font-bold text-gray-900 mr-2">{{ trans('lang.labels.as_seen') }}</span>
            <div class="flex flex-row items-center justify-center">
                <a id="post-status" href="https://mailchi.mp/poststatus/453?e=73adb90c37" target="_blank" rel="noreferrer noopener"
                   class="flex justify-center items-center mx-2">
                    <svg x="0px" y="0px" viewBox="0 0 78 79.8" style="enable-background:new 0 0 78 79.8;">
                        <path fill="#fc781f" d="M23.6,76C14.7,76,5.3,69.7,5.3,58c0-5.5,2.5-10.6,6.8-14c4.4-3.5,10.1-4.9,15.7-3.6c1.6,0.4,2.6,2,2.3,3.6
                                c-0.4,1.6-2,2.6-3.6,2.3c-3.8-0.8-7.6,0.1-10.6,2.5c-2.9,2.3-4.5,5.7-4.5,9.3c0,5.8,3.9,12.1,12.4,12.1c5.7,0,12.4-3.5,12.4-13.5
                                V23.4c0-7.6,3.1-12.2,5.8-14.7c3.3-3.1,7.8-4.9,12.7-4.9c8.9,0,18.4,6.3,18.4,18.1c0,5.5-2.5,10.6-6.8,14
                                c-4.4,3.5-10.1,4.9-15.7,3.6c-1.6-0.4-2.6-2-2.3-3.6c0.4-1.6,2-2.6,3.6-2.3c3.8,0.8,7.6-0.1,10.6-2.5c2.9-2.3,4.5-5.7,4.5-9.3
                                c0-5.8-3.9-12.1-12.4-12.1c-5.7,0-12.4,3.5-12.4,13.5v33.1C42.1,69.9,32.5,76,23.6,76z"></path>
                    </svg>
                    <span class="text-sm text-gray-800 ml-2 font-medium">{{ trans('lang.labels.post_status') }}</span>
                </a>
                <a class="flex justify-center items-center mx-2"
                   target="_blank" rel="noreferrer noopener"
                   href="https://wptavern.com/instawp-launches-new-service-for-disposable-wordpress-testing-sites">
                   {{ trans('lang.labels.wp_tavern') }}
                </a>
                <a class="inline-flex items-center flex-row mx-2" href="https://www.reddit.com/r/Wordpress/comments/o8cxwj/instawp_launch_a_disposable_wp_instantly_free/" target="_blank"
                   rel="noreferrer noopener">
                    <svg viewBox="0 0 20 20" style="padding: 8px 8px 8px 0; width: 32px; height:36px;">
                        <g>
                            <circle fill="#FF4500" cx="10" cy="10" r="10"></circle>
                            <path fill="#FFF"
                                  d="M16.67,10A1.46,1.46,0,0,0,14.2,9a7.12,7.12,0,0,0-3.85-1.23L11,4.65,13.14,5.1a1,1,0,1,0,.13-0.61L10.82,4a0.31,0.31,0,0,0-.37.24L9.71,7.71a7.14,7.14,0,0,0-3.9,1.23A1.46,1.46,0,1,0,4.2,11.33a2.87,2.87,0,0,0,0,.44c0,2.24,2.61,4.06,5.83,4.06s5.83-1.82,5.83-4.06a2.87,2.87,0,0,0,0-.44A1.46,1.46,0,0,0,16.67,10Zm-10,1a1,1,0,1,1,1,1A1,1,0,0,1,6.67,11Zm5.81,2.75a3.84,3.84,0,0,1-2.47.77,3.84,3.84,0,0,1-2.47-.77,0.27,0.27,0,0,1,.38-0.38A3.27,3.27,0,0,0,10,14a3.28,3.28,0,0,0,2.09-.61A0.27,0.27,0,1,1,12.48,13.79Zm-0.18-1.71a1,1,0,1,1,1-1A1,1,0,0,1,12.29,12.08Z"></path>
                        </g>
                    </svg>
                    <svg viewBox="0 0 57 18" style="display: block; height: 18px; width: auto;">
                        <g fill="#1c1c1c">
                            <path
                                d="M54.63,16.52V7.68h1a1,1,0,0,0,1.09-1V6.65a1,1,0,0,0-.93-1.12H54.63V3.88a1.23,1.23,0,0,0-1.12-1.23,1.2,1.2,0,0,0-1.27,1.11V5.55h-1a1,1,0,0,0-1.09,1v.07a1,1,0,0,0,.93,1.12h1.13v8.81a1.19,1.19,0,0,0,1.19,1.19h0a1.19,1.19,0,0,0,1.25-1.12A.17.17,0,0,0,54.63,16.52Z"></path>
                            <circle fill="#FF4500" cx="47.26" cy="3.44" r="2.12"></circle>
                            <path d="M48.44,7.81a1.19,1.19,0,1,0-2.38,0h0v8.71a1.19,1.19,0,0,0,2.38,0Z"></path>
                            <path
                                d="M30.84,1.19A1.19,1.19,0,0,0,29.65,0h0a1.19,1.19,0,0,0-1.19,1.19V6.51a4.11,4.11,0,0,0-3-1.21c-3.1,0-5.69,2.85-5.69,6.35S22.28,18,25.42,18a4.26,4.26,0,0,0,3.1-1.23,1.17,1.17,0,0,0,1.47.8,1.2,1.2,0,0,0,.85-1.05ZM25.41,15.64c-1.83,0-3.32-1.77-3.32-4s1.48-4,3.32-4,3.31,1.78,3.31,4-1.47,3.95-3.3,3.95Z"></path>
                            <path
                                d="M43.28,1.19A1.19,1.19,0,0,0,42.09,0h0a1.18,1.18,0,0,0-1.18,1.19h0V6.51a4.15,4.15,0,0,0-3-1.21c-3.1,0-5.69,2.85-5.69,6.35S34.72,18,37.86,18A4.26,4.26,0,0,0,41,16.77a1.17,1.17,0,0,0,1.47.8,1.19,1.19,0,0,0,.85-1.05ZM37.85,15.64c-1.83,0-3.31-1.77-3.31-4s1.47-4,3.31-4,3.31,1.78,3.31,4-1.47,3.95-3.3,3.95Z"></path>
                            <path
                                d="M17.27,12.44a1.49,1.49,0,0,0,1.59-1.38v-.15a4.81,4.81,0,0,0-.1-.85A5.83,5.83,0,0,0,13.25,5.3c-3.1,0-5.69,2.85-5.69,6.35S10.11,18,13.25,18a5.66,5.66,0,0,0,4.39-1.84,1.23,1.23,0,0,0-.08-1.74l-.11-.09a1.29,1.29,0,0,0-1.58.17,3.91,3.91,0,0,1-2.62,1.12A3.54,3.54,0,0,1,10,12.44h7.27Zm-4-4.76a3.41,3.41,0,0,1,3.09,2.64H10.14A3.41,3.41,0,0,1,13.24,7.68Z"></path>
                            <path
                                d="M7.68,6.53a1.19,1.19,0,0,0-1-1.18A4.56,4.56,0,0,0,2.39,6.91V6.75A1.2,1.2,0,0,0,0,6.75v9.77a1.23,1.23,0,0,0,1.12,1.24,1.19,1.19,0,0,0,1.26-1.1.66.66,0,0,0,0-.14v-5A3.62,3.62,0,0,1,5.81,7.7a4.87,4.87,0,0,1,.54,0h.24A1.18,1.18,0,0,0,7.68,6.53Z"></path>
                        </g>
                    </svg>
                </a>
            </div>
        </div>
    </div>
</template>
<style scoped>
#post-status {
    font-family: Gotham Rounded SSm A, Gotham Rounded SSm B, sans-serif;
    letter-spacing: .05em;
    color: #f4f4f4;
}

#post-status svg {
    width: 1.5rem;
    height: 1.7rem;
}

</style>
<script>
export default {}
</script>
