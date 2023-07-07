<template>
    <div class="themeDashboard mt-2 h-screen">
        <div class="flex flex-col-reverse md:flex-row items-center justify-center h-screen">
            <div class="bg-cardtop rounded-lg flex justify-between align-center px-10 py-10 w-2/4">
                <div class="text-start flex flex-col justify-start align-start">
                    <h1 class="text-8xl text-amber-500 mb-5">404</h1>
                    <h1 class="text-6xl text-neutral-300 font-bold mb-5">
                        Something Went <br>
                        <span class="text-amber-500">WRONG!</span>
                    </h1>
                    <div class="text-start">
                        <inertia-link :href="route('xme.overview')"
                            class="bg-amber-500 rounded-lg text-white px-5 py-2 text-center w-36 flex items-center">
                        <ArrowUturnLeftIcon class="h-6 w-6 text-white mr-2" aria-hidden="true" /> Go Back
                        </inertia-link>
                    </div>
                </div>
                <div class="h-72">
                    <img src="./../../../public/images/brand/error.svg" class="w-72 h-72">
                </div>
            </div>
        </div>
    </div>
</template>
<script>

import {
    ArrowUturnLeftIcon
} from "@heroicons/vue/24/solid";
export default {
    props: ["user"],
    created() {

    },
    components: {
        
        ArrowUturnLeftIcon
    },
    data() {
        return {

        }
    }
};
</script>