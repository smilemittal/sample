<template>
    <div class="themeDashboard mt-2 h-screen">
        <div class="flex flex-col-reverse md:flex-row items-center justify-center h-screen">
            <div class="pr-4">
                <h4 class="text-3xl text-gray-400">Website is under <span class="text-amber-500">Maintenance</span> process.</h4>
                <h5 class="text-gray-300 text-6xl font-extrabold">We are <span class="text-amber-500">launching</span> soon!
                </h5>
                <vue-countdown :time="5 * 24 * 60 * 60 * 1000" v-slot="{ days, hours, minutes, seconds }">
                    <div class="w-96">
                        <!-- <h3 class="text-4xl text-gray-300">Time Remaining： days, {{ hours }} hours, {{ minutes }}
                            minutes, {{ seconds }} seconds.</h3> -->
                        <div class="grid grid-cols-4 gap-10 mt-6">
                            <div class="bg-cardtop w-24 h-24 flex items-center justify-center rounded-xl">
                                <h4 class="text-4xl text-amber-500 flex flex-col justify-center text-center">
                                    {{ days }}
                                    <span class="text-sm text-neutral-400">Days</span>
                                </h4>
                            </div>
                            <div class="bg-cardtop w-24 h-24 flex items-center justify-center rounded-xl">
                                <h4 class="text-4xl text-amber-500 flex flex-col justify-center text-center">
                                    {{ hours }}
                                    <span class="text-sm text-neutral-400">Hours</span>
                                </h4>
                            </div>
                            <div class="bg-cardtop w-24 h-24 flex items-center justify-center rounded-xl">
                                <h4 class="text-4xl text-amber-500 flex flex-col justify-center text-center">
                                    {{ minutes }}
                                    <span class="text-sm text-neutral-400">Minutes</span>
                                </h4>
                            </div>
                            <div class="bg-cardtop w-24 h-24 flex items-center justify-center rounded-xl">
                                <h4 class="text-4xl text-amber-500 flex flex-col justify-center text-center">
                                    {{ seconds }}
                                    <span class="text-sm text-neutral-400">Seconds</span>
                                </h4>
                            </div>
                        </div>
                    </div>
                </vue-countdown>
            </div>
            <div class="bg-cardtop p-8 w-96 h-96 rounded-2xl">
                <img src="./../../../public/images/brand/comingsoon-img.svg" class="h-80 w-80">
            </div>
        </div>
    </div>
</template>
<script>
import VueCountdown from '@chenfengyuan/vue-countdown';
export default {
    props: ["user"],
    created() {

    },
    components: {
        VueCountdown
    },
    mounted() {
        this.timer = setInterval(this.countdown, 1000);
    },
    data() {
        return {
            time: 50000
        }
    },
    methods: {
        countdown() {
            this.time--;
            if (this.time == 0) {
                clearInterval(this.timer)
            }
        }
    },
    beforeDestroy() {
        clearInterval(this.timer);
    }
};
</script>