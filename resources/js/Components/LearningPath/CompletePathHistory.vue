<template>
    <!-- Main modal -->
    <div id="assignCompany"
        class="themeModal bg-bgModal fixed top-0 flex items-center justify-center left-0 right-0 z-50 w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-modal md:h-full">
        <div class="relative w-full h-auto max-w-3xl md:h-auto">
            <!-- Modal content -->
            <div class="relative rounded-lg shadow bg-body">
                <!-- Modal header -->
                <div class="flex items-center justify-between p-6 rounded-t">
                    <div>
                        <h3 class="text-xl font-semibold text-gray-300">
                            {{ trans("lang.labels.completed_learning_path_history") }}
                        </h3>
                    </div>
                    <button type="button" @click="isCloseTraining()" class="">
                        <XCircleIcon class="h-9 w-9 text-white" aria-hidden="true" />
                    </button>
                </div>
                <!-- Form start here -->
                <div class="p-6 space-y-6 h-96 sm:h-96 lg:h-96 overflow-y-auto theme-modal-body">
                    <div class="overflow-x-auto">
                        <table class="theme-table w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-sidebar dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="text-neutral-300 font-semibold text-base p-4">{{ trans("lang.labels.module") }}</th>
                                    <!-- <th scope="col" class="text-neutral-300 font-semibold text-base p-4">Type</th> -->
                                    <th scope="col" class="text-neutral-300 font-semibold text-base p-4">{{ trans("lang.labels.completed_date") }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="trainingsHistory in trainingHistory" :key="trainingsHistory.id"
                                    class="border-b dark:border-gray-700 text-base text-neutral-300 hover:text-white">
                                    <td class="p-4">
                                        <span>{{ trainingsHistory.form_display_title }}</span>
                                    </td>
                                    <!-- <td class="p-4">
                                        <span>{{ trainingsHistory.type }}</span>
                                    </td> -->
                                    <td class="p-4">
                                        <span>{{ trainingsHistory.created_at }}</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
import {
    XCircleIcon
} from '@heroicons/vue/24/solid'
export default {
    name: 'TrainingHistory',
    props: ['learningPathID', 'formId'],
    components: {
        XCircleIcon
    },
    data() {
        return {
            trainingHistory: [],
            trainings: '',
            queryData: {
                form_id: this.formId,
            },
        }
    },
    methods: {
        history() {
            let formData = new FormData();
            formData.append('form_id', this.formId);
            axios.get('/api/learning-path-module-history/' + this.learningPathID, { params: this.queryData })
                .then((res) => {
                    this.trainingHistory = res.data.data;
                })
                .catch((error) => {
                    // error.response.status Check status code
                }).finally(() => {
                    //Perform action in always
                });
        },
        isCloseTraining() {
            this.$emit("closeHistory");
        },
    },
    created() {
        this.history();
    }
}
</script>
