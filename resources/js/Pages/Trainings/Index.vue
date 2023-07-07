<template>
    <div>

        <InertiaHead title="My Training" />
        <AuthenticatedLayout>
            <div class="mb-4">
                <div class="flex items-center justify-between flex-wrap">
                    <div class="page-header">
                        <inertia-link :href="route('app.my-trainings')" class="text-neutral-300 font-bold text-2xl sm:text-2xl md:text-4xl">{{ trans("lang.labels.my_training") }}</inertia-link>
                        <h5 class="text-neutral-400 text-sm sm:text-sm md:text-base">{{ trans("lang.labels.your_completed_and_upcoming_training") }}</h5>
                    </div>
                </div>
            </div>
            <div class="h-full drop-shadow-md rounded-3xl mt-5">
                <!----Upcoming trainings------->
                <upcoming-table></upcoming-table>
                
                <!-----Completed Training------->
                <completed-table @openTraining="openTraining"></completed-table>

                <!-----Completed Training------->
                <learning-table></learning-table>

            </div>
            <training-history @closeTraining="isCloseTraining()" v-if="isTraining" :data="formData"></training-history>
        </AuthenticatedLayout>
    </div>
</template>
<script>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import TrainingHistory from '@/Components/Trainings/History.vue'
import UpcomingTable from "@/Components/Trainings/Tables/Upcoming.vue"
import CompletedTable from "@/Components/Trainings/Tables/Completed.vue"
import LearningTable from "@/Components/Trainings/Tables/Path.vue"

export default {
    components: {
        AuthenticatedLayout,
        TrainingHistory,
        UpcomingTable,
        CompletedTable,
        LearningTable
    },
    data() {
        return {
            isTraining : false,
            formData : {
                id: '',
                title : '',
            }
        }
    },
    watch: {
        isTraining: function () {
            document.body.style.overflow = this.isTraining ? 'hidden' : ''
        }
    },
    methods: {
        isCloseTraining() {
            this.isTraining = false;
        },
        openTraining(data){
            this.isTraining = true;
            this.formData.id = data.id;
            this.formData.title = data.title;
            console.log(this.formData);

        }
    },
}
</script>
