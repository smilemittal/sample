<template>
    <!-- Main modal -->
    <div id="assignCompany"
        class="bg-bgModal fixed top-0 flex items-center justify-center left-0 right-0 z-50 w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-modal md:h-full">
        <div class="relative w-full h-auto max-w-3xl md:h-auto">
            <!-- Modal content -->
            <div class="relative rounded-lg shadow bg-body">
                <!-- Modal header -->
                <div class="flex items-start justify-between p-6 rounded-t">
                    <div>
                        <h3 class="text-xl font-semibold text-gray-300">
                            {{ trans("lang.labels.training_history") }}
                        </h3>
                        <h5 class="text-base text-gray-400 mt-2 breakWord libraryCardTitleOverflow">{{ data.title }}</h5>
                    </div>
                    <button type="button" @click="isCloseTraining()" class="">
                        <XCircleIcon class="h-9 w-9 text-white" aria-hidden="true" />
                    </button>
                </div>
                <!-- Form start here -->
                <div class="p-6 space-y-6 h-96 sm:h-96 lg:h-96 overflow-y-auto theme-modal-body">
                    <div class="mt-1 mb-3">
                        <div class="flex items-center flex-wrap">
                            <label for="review_status" class="block text-sm font-medium dark:text-gray-400">
                                {{ trans("lang.labels.type") }}:
                            </label>
                            <select id="review_status" v-model="trainings" @change="type($event)"
                                class="bg-sidebar border-1 border-zinc-300 text-gray-400 text-sm rounded-lg focus:ring-0 focus:border-amber-500 block ml-2 p-2.5">
                                <option>Select</option>
                                <option value="completed">Completed</option>
                                <option value="skipped">Skipped</option>
                            </select>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="theme-table w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-sidebar dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="text-neutral-300 font-semibold text-base p-4 w-96">{{
                                        trans("lang.labels.group") }}</th>
                                    <th scope="col" class="text-neutral-300 font-semibold text-base p-4">{{
                                        trans("lang.labels.type") }}</th>
                                    <th scope="col" class="text-neutral-300 font-semibold text-base p-4">{{
                                        trans("lang.labels.time") }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="trainingsHistory in trainingHistory" :key="trainingsHistory.id"
                                    class="border-b dark:border-gray-700 text-base text-neutral-300 hover:text-white">
                                    <td class="p-4 breakWord">
                                        <span>{{ trainingsHistory.group }}</span>
                                    </td>
                                    <td class="p-4">
                                        <span>{{ trainingsHistory.status }}</span>
                                    </td>
                                    <td class="p-4">
                                        <span>{{ trainingsHistory.completedDate }}</span>
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
    props: ['data'],
    components: {
        XCircleIcon
    },
    data() {
        return {
            trainingHistory: [
            ],
            queryData: {
                type: ''
            }
        }
    },
    methods: {
        history() {
            axios.get('/api/view-training-history/' + this.data.id, { params: this.queryData })
                .then((res) => {
                    this.trainingHistory = res.data.data;
                })
                .catch((error) => {
                    // error.response.status Check status code
                }).finally(() => {
                    //Perform action in always
                });
        },
        type(event) {
            let that = this;
            if (event.target.value != "Select") {
                that.queryData.type = event.target.value;
            } else {
                that.queryData.type = null;
            }
            this.history();
        },
        isCloseTraining() {
            this.$emit("closeTraining");
        },
    },
    created() {
        this.history();
    }
}
</script>
