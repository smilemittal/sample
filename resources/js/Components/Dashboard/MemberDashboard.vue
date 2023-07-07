<template>
    <div class="themeDashboard mt-2">
        <div class="grid grid-cols-8 gap-8">
            <div class="col-span-8 sm:col-span-8 md:col-span-4 lg:col-span-4 xl:col-span-3 
                p-4 border border-neutral-700 rounded-xl h-72 flex items-center justify-center">
                <img class="w-full sm:w-96 md:w-96 m-auto" src="./../../../img/brand_new/dash_logo.png">
            </div>
            <div class="col-span-8 sm:col-span-4 md:col-span-4 lg:col-span-4 xl:col-span-3 
                p-4 bg-sidebar h-72 rounded-xl flex flex-col justify-between">
                <div class="flex items-center justify-start">
                    <span class="text-neutral-300 text-base">My Trainings</span>
                    <span class="text-themeChartColorOne text-base ml-3">{{ learningPathTrainings + upcomingTrainings +
                        completedTrainings }}</span>
                </div>
                <div class="themeChart h-full">
                    <div class="relative h-48 mt-2 noChartFound"
                        v-if="upcomingTrainings == 0 && completedTrainings == 0 && learningPathTrainings == 0">
                        <Doughnut :data="notFoundResult" :options="chartOptions" />
                    </div>
                    <div v-else class="h-48">
                        <Doughnut v-if="loaded" :data="trainingChartData" :options="chartOptions" />
                    </div>
                </div>
                <div class="flex justify-center flex-wrap">
                    <div class="flex items-center">
                        <div class="rounded-lg bg-themeChartColorOne h-3 w-3"></div>
                        <span class="text-neutral-300 text-xs ml-1">Upcoming Trainings<span class="ml-1">({{
                            upcomingTrainings }})</span></span>
                    </div>
                    <div class="flex items-center ml-4">
                        <div class="rounded-lg bg-themeChartColorTwo h-3 w-3"></div>
                        <span class="text-neutral-300 text-xs ml-1">Completed Trainings<span class="ml-1">({{
                            completedTrainings }})</span></span>
                    </div>
                    <div class="flex items-center ml-4">
                        <div class="rounded-lg bg-themeChartColorThree h-3 w-3"></div>
                        <span class="text-neutral-300 text-xs ml-1">Learning Path<span class="ml-1">({{
                            learningPathTrainings }})</span></span>
                    </div>
                </div>
            </div>
            <div class="col-span-8 sm:col-span-4 md:col-span-4 lg:col-span-4 xl:col-span-2 
            p-4 h-72 bg-sidebar rounded-xl">
                <div class="flex flex-col justify-between h-full">
                    <div class="col-span-2 flex items-center justify-start">
                        <span class="text-neutral-300 text-base">Library</span>
                        <span class="text-themeChartColorOne text-base ml-3">{{ libraries }}</span>
                    </div>
                    <div class="themeChart h-full">
                        <div class="relative h-48 mt-2 noChartFound"
                            v-if="libraries == 0">
                            <Doughnut :data="notFoundResult" :options="chartOptions" />
                        </div>

                        <div class="h-48" v-else>
                            <Doughnut v-if="loaded" :data="libraryChartData" :options="chartOptions" />
                        </div>
                    </div>
                    <div class="flex justify-center">
                        <div class="flex items-center">
                            <div class="rounded-lg bg-themeChartColorOne h-3 w-3"></div>
                            <span class="text-neutral-300 text-xs ml-2">Active<span class="ml-1">({{ libraries
                            }})</span></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-span-8 sm:col-span-4 md:col-span-4 lg:col-span-4 xl:col-span-3 
            p-4 h-72 bg-sidebar rounded-xl">
                <div class="flex flex-col justify-between h-full">
                    <div class="col-span-2 flex items-center justify-start">
                        <span class="text-neutral-300 text-base">XME Area</span>
                        <span class="text-themeChartColorOne text-base ml-3">{{ xmeAreas }}</span>
                    </div>
                    <div class="themeChart h-full">
                        <div class="relative h-48 mt-2 noChartFound" v-if="xmeAreas == 0">
                            <Doughnut :data="notFoundResult" :options="chartOptions" />
                        </div>
                        <div class="h-48" v-else>
                            <Doughnut v-if="loaded" :data="xmeChartData" :options="chartOptions" />
                        </div>
                    </div>
                    <div class="flex justify-center">
                        <div class="flex items-center">
                            <div class="rounded-lg bg-themeChartColorOne h-3 w-3"></div>
                            <span class="text-neutral-300 text-xs ml-2">Active<span class="ml-1">({{ xmeAreas
                            }})</span></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-span-8 sm:col-span-4 md:col-span-4 lg:col-span-4 xl:col-span-3 
            p-4 h-72 bg-sidebar rounded-xl">
                <div class="grid grid-cols-2 h-full">
                    <div class="col-span-2 flex items-center justify-between">
                        <div class="col-span-2 flex items-center justify-start">
                            <span class="text-neutral-300 text-base">Identify</span>
                            <span class="text-themeChartColorOne text-base ml-3">{{ counters.totalCounts }}</span>
                        </div>
                        <div>
                            <select v-model="selectIdentifyChart"
                                class="px-2 py-1 text-neutral-400 text-xs bg-sidebar rounded-sm focus:border-amber-500 focus:ring-0">
                                <option value="identifyprioity">Priority</option>
                                <option value="identifystatus">Status</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-span-2 flex flex-col justify-between h-full"
                        v-if="selectIdentifyChart == 'identifyprioity'">
                        <div class="themeChart h-40">
                            <div class="relative h-full mt-2 noChartFound"
                                v-if="counters.lowPriorityCount == 0 && counters.mediumPriorityCount == 0 && counters.highPriorityCount == 0">
                                <Doughnut :data="notFoundResult" :options="chartOptions" />
                            </div>
                            <div v-else class="h-40">
                                <Doughnut v-if="loaded" :data="identifyPriorityData" :options="chartOptions" />
                            </div>
                        </div>
                        <div class="flex flex-col justify-center">
                            <div class="text-center mb-2">
                                <span class="text-neutral-300 text-sm">Priority</span>
                            </div>
                            <div class="flex justify-center flex-wrap">
                                <div class="flex items-center">
                                    <div class="rounded-lg bg-themeChartColorOne h-3 w-3"></div>
                                    <span class="text-neutral-300 text-xs ml-2">Low<span class="ml-1">( {{
                                        counters.lowPriorityCount }})</span></span>
                                </div>
                                <div class="flex items-center ml-4">
                                    <div class="rounded-lg bg-themeChartColorTwo h-3 w-3"></div>
                                    <span class="text-neutral-300 text-xs ml-2">Medium<span class="ml-1">({{
                                        counters.mediumPriorityCount }})</span></span>
                                </div>
                                <div class="flex items-center ml-4">
                                    <div class="rounded-lg bg-themeChartColorThree h-3 w-3"></div>
                                    <span class="text-neutral-300 text-xs ml-2">High<span class="ml-1">({{
                                        counters.highPriorityCount }})</span></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-2 flex flex-col justify-between h-full"
                        v-if="selectIdentifyChart == 'identifystatus'">
                        <div class="themeChart h-40">
                            <div class="relative h-full mt-2 noChartFound" v-if="counters.draft == 0">
                                <Doughnut :data="notFoundResult" :options="chartOptions" />
                            </div>
                            <div v-else class="h-40">
                                <Doughnut v-if="loaded" :data="identifyStatusData" :options="chartOptions" />
                            </div>
                        </div>
                        <div class="flex flex-col justify-center">
                            <div class="text-center mb-2">
                                <span class="text-neutral-300 text-sm">Status</span>
                            </div>
                            <div class="flex justify-center">
                                <div class="flex items-center">
                                    <div class="rounded-lg bg-themeChartColorOne h-3 w-3"></div>
                                    <span class="text-neutral-300 text-xs ml-2">Draft<span class="ml-1">({{ counters.draft
                                    }})</span></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {
    ChevronRightIcon
} from '@heroicons/vue/24/solid'
import GeneralMixin from "@/Mixins/GeneralMixin";
import { mapState, mapStores } from 'pinia'
import { useAppStore } from '@/store'
import { Bar, Doughnut, PolarArea } from 'vue-chartjs'
import { Chart as ChartJS, Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale, ArcElement, RadialLinearScale } from 'chart.js'

ChartJS.register(Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale, ArcElement, RadialLinearScale)

export default {
    mixins: [GeneralMixin],
    props: ["user"],
    computed: {
        ...mapStores(useAppStore),
    },
    created() {
        this.appStore.userLogin(this.user);
    },
    components: {
        ChevronRightIcon,
        AuthenticatedLayout,
        Bar, Doughnut, PolarArea
    },
    data() {
        return {
            notFoundResult: {
                datasets: [
                    {
                        cutout: '60%',
                        label: 'No Values',
                        borderWidth: 0,
                        backgroundColor: ['#494f56'],
                        data: [.1]
                    }
                ]
            },
            loaded: false,
            selectIdentifyChart: 'identifyprioity',
            trainingChartData: {
                labels: [
                    'Upcoming Trainings',
                    'Completed Trainings',
                    'Learning Path'
                ],
                datasets: [
                    {
                        label: 'My Trainings',
                        backgroundColor: ['#1ae5ae', '#1faa9c', '#0581a8'],
                        data: [10, 5, 5],
                        borderWidth: 0,
                        cutout: '50%'
                    }
                ]
            },
            libraryChartData: {
                labels: ['Active', 'Inactive'],
                datasets: [
                    {
                        backgroundColor: ['#1ae5ae', '#1faa9c'],
                        data: [10],
                        borderWidth: 0,
                        cutout: '50%'
                    }
                ]
            },
            xmeChartData: {
                labels: ['Active', 'Inactive'],
                datasets: [
                    {
                        backgroundColor: ['#1ae5ae', '#1faa9c'],
                        data: [10],
                        borderWidth: 0,
                        cutout: '50%'
                    }
                ]
            },
            identifyPriorityData: {
                labels: ['Low', 'Medium', 'High'],
                datasets: [
                    {
                        backgroundColor: ['#1ae5ae', '#1faa9c', '#0581a8'],
                        data: [10, 10, 10],
                        borderWidth: 0,
                        cutout: '50%'
                    }
                ]
            },
            identifyStatusData: {
                labels: ['Draft', 'Backburner'],
                datasets: [
                    {
                        backgroundColor: ['#1ae5ae', '#1faa9c'],
                        data: [10, 10],
                        borderWidth: 0,
                        cutout: '50%'
                    }
                ]
            },
            chartOptions: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false,
                        labels: {
                            font: {
                                size: 11
                            },
                            color: 'white'
                        }
                    },
                }
            },
            trainingCount: 0,
            upcomingTrainings: 0,
            completedTrainings: 0,
            activelibraries: 0,
            learningPathTrainings: 0,
            libraries: 0,
            counters: {},
            upcomingQueryData: {
                sortField: 'assigned_date',
                upcomingStatus: null,
            },
            completedQueryData: {
                search: '',
                page: 1,
                sortField: 'forms.id',
                orderDir: 'ASC',
            },
            LearninQueryData: {
                search: '',
                page: 1,
                sortField: 'learning_paths.id',
                orderDir: 'DESC'
            },
            libraryQueryData: {
                search: '',
                page: 1,
                isArchived: 0
            },

        }
    },
    methods: {
        upcomingTraingCounter() {
            axios.get('/api/upcoming-trainings', { params: this.upcomingQueryData })
                .then((response) => {
                    this.upcomingTrainings = response.data.meta.total;

                })
                .catch((error) => {

                })
                .finally(() => {
                });
        },
        completedTrainingCounter() {
            axios.get('/api/completed-trainings', { params: this.completedQueryData })
                .then((response) => {

                    this.completedTrainings = response.data.meta.total;

                })
                .catch((error) => {

                })
                .finally(() => {
                });

        },
        learningPathTrainingCounter() {
            axios.get('/api/learning-path-trainings', { params: this.LearninQueryData })
                .then((response) => {

                    this.learningPathTrainings = response.data.meta.total;

                })
                .catch((error) => {

                })
                .finally(() => {
                });

        },
        libraryCounter() {
            const headers = { "Content-Type": "application/json" };
            //for table data is loading after fetch ==>
            axios.get("/api/library", { params: this.libraryQueryData }, headers)
                .then((response) => {
                    this.libraries = response.data.meta.total;
                })
        },
        xmeAreaCounter() {
            const headers = { "Content-Type": "application/json" };
            //for table data is loading after fetch ==>
            fetch("/api/xme-area", headers)
                .then((response) => response.json())
                .then((data) => {
                    this.xmeAreas = data.meta.total;
                });
        },
        memberCounters(priorityType = null) {
            axios.get('/api/member-dashboard-counters')
                .then((response) => {
                    this.counters = response.data.data;
                    this.trainingChartData.datasets[0].data = [this.upcomingTrainings,
                    this.completedTrainings,
                    this.learningPathTrainings];
                    this.identifyPriorityData.datasets[0].data = [this.counters.lowPriorityCount,
                    this.counters.mediumPriorityCount,
                    this.counters.highPriorityCount];
                })
                .catch((error) => {

                })
                .finally(() => {
                    this.loaded = true;
                });
        }
    },
    created: function () {
        this.upcomingTraingCounter();
        this.completedTrainingCounter();
        this.learningPathTrainingCounter();
        this.libraryCounter();
        this.xmeAreaCounter();
        this.memberCounters();

    },
};
</script>