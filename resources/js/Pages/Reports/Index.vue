<template>
    <div>
        <InertiaHead title="Document/Reports" />
        <AuthenticatedLayout>
            <div class="mt-2">
                <h4 class="text-neutral-300 font-bold text-xl sm:text-2xl md:text-4xl">{{
                    trans("lang.labels.document_reports") }}</h4>
                <h5 class="text-base text-neutral-400">{{ trans("lang.labels.gathering_data_come_back_later") }}</h5>
            </div>
            <div class="themeDashboard mt-10">
                <div class="flex flex-col-reverse md:flex-row items-center justify-center">
                    <div class="pr-4">
                        <h5 class="text-gray-300 mt-3 text-3xl lg:text-6xl font-extrabold">We are <span
                                class="text-amber-500">launching</span> soon!
                        </h5>
                    </div>
                    <div class="bg-cardtop p-8 w-96 h-96 rounded-2xl">
                        <img src="./../../../../public/images/brand/comingsoon-img.svg" class="h-80 w-80">
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    </div>
</template>
<script>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';


export default {
    components: {
        AuthenticatedLayout,

    },
    data() {
        return {

        }
    },
    methods: {

    },
}
</script>
