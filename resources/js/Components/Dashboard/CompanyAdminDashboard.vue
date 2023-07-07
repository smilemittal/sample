<template>
    <div class="themeDashboard mt-2">
        <div class="grid grid-cols-10 gap-8">
            <div class="themeDashCard themeDashLogo col-span-10 sm:col-span-10 md:col-span-5 lg:col-span-5 xl:col-span-4
             p-4 border border-neutral-700 rounded-xl h-52 flex items-center justify-center">
                <img class="w-fit h-full xl:h-full m-auto" src="./../../../img/brand_new/dash_logo.png">
            </div>
            <div class="themeDashCard themeDashCounter col-span-10 sm:col-span-10 md:col-span-5 lg:col-span-5 xl:col-span-2 
                p-4 bg-sidebar h-52 rounded-xl grid grid-cols-2">
                <div class="flex flex-col justify-between">
                    <div class="flex items-center justify-start bg-amber-500 rounded-lg p-2 w-fit h-fit themeDashIcon">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" viewBox="0 0 512 512">
                            <path
                                d="M228.3 469.1L47.6 300.4c-4.2-3.9-8.2-8.1-11.9-12.4h87c22.6 0 43-13.6 51.7-34.5l10.5-25.2 49.3 109.5c3.8 8.5 12.1 14 21.4 14.1s17.8-5 22-13.3L320 253.7l1.7 3.4c9.5 19 28.9 31 50.1 31H476.3c-3.7 4.3-7.7 8.5-11.9 12.4L283.7 469.1c-7.5 7-17.4 10.9-27.7 10.9s-20.2-3.9-27.7-10.9zM503.7 240h-132c-3 0-5.8-1.7-7.2-4.4l-23.2-46.3c-4.1-8.1-12.4-13.3-21.5-13.3s-17.4 5.1-21.5 13.3l-41.4 82.8L205.9 158.2c-3.9-8.7-12.7-14.3-22.2-14.1s-18.1 5.9-21.8 14.8l-31.8 76.3c-1.2 3-4.2 4.9-7.4 4.9H16c-2.6 0-5 .4-7.3 1.1C3 225.2 0 208.2 0 190.9v-5.8c0-69.9 50.5-129.5 119.4-141C165 36.5 211.4 51.4 244 84l12 12 12-12c32.6-32.6 79-47.5 124.6-39.9C461.5 55.6 512 115.2 512 185.1v5.8c0 16.9-2.8 33.5-8.3 49.1z" />
                        </svg>
                    </div>
                    <div class="flex justify-start">
                        <span class="text-white">Health Check</span>
                    </div>
                </div>
                <div class="flex flex-col justify-center">
                    <span class="text-xs text-neutral-300">
                        "The right information brings knowledge. Knowledge is power Sharing it brings empowerment"
                    </span>
                    <div class="text-end">
                        <span class="text-xs text-themeChartColorOne">
                            Seth Godin
                        </span>
                    </div>
                </div>
            </div>
            <div class="themeDashCard themeDashCounter col-span-10 sm:col-span-5 md:col-span-5 lg:col-span-5 xl:col-span-2 
                p-4 h-52 bg-sidebar grid grid-cols-2 rounded-xl">
                <div class="flex flex-col justify-between">
                    <div class="flex items-center justify-start bg-emerald-500 rounded-lg p-2 w-fit h-fit themeDashIcon">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" viewBox="0 0 512 512">
                            <path
                                d="M228.3 469.1L47.6 300.4c-4.2-3.9-8.2-8.1-11.9-12.4h87c22.6 0 43-13.6 51.7-34.5l10.5-25.2 49.3 109.5c3.8 8.5 12.1 14 21.4 14.1s17.8-5 22-13.3L320 253.7l1.7 3.4c9.5 19 28.9 31 50.1 31H476.3c-3.7 4.3-7.7 8.5-11.9 12.4L283.7 469.1c-7.5 7-17.4 10.9-27.7 10.9s-20.2-3.9-27.7-10.9zM503.7 240h-132c-3 0-5.8-1.7-7.2-4.4l-23.2-46.3c-4.1-8.1-12.4-13.3-21.5-13.3s-17.4 5.1-21.5 13.3l-41.4 82.8L205.9 158.2c-3.9-8.7-12.7-14.3-22.2-14.1s-18.1 5.9-21.8 14.8l-31.8 76.3c-1.2 3-4.2 4.9-7.4 4.9H16c-2.6 0-5 .4-7.3 1.1C3 225.2 0 208.2 0 190.9v-5.8c0-69.9 50.5-129.5 119.4-141C165 36.5 211.4 51.4 244 84l12 12 12-12c32.6-32.6 79-47.5 124.6-39.9C461.5 55.6 512 115.2 512 185.1v5.8c0 16.9-2.8 33.5-8.3 49.1z" />
                        </svg>
                    </div>
                    <div class="flex flex-col justify-start">
                        <span class="text-neutral-400 text-xs">Total Delivered</span>
                        <span class="text-themeChartColorOne text-lg">{{ counters.documented }} Modules</span>
                    </div>
                </div>
                <div class="flex flex-col items-center justify-center">
                    <!-- <span class="text-xs text-neutral-300">
                        "The right information brings knowledge. Knowledge is power Sharing it brings empowerment"
                    </span> -->
                    <img src="./../../../img/brand_new/logo-banner.png" class="w-20 h-20">
                    <!-- <div class="text-end">
                        <span class="text-xs text-themeChartColorOne">
                            Seth Godin
                        </span>
                    </div> -->
                </div>
            </div>
            <div class="themeDashCard themeDashCounter themeDashModule col-span-10 sm:col-span-5 md:col-span-5 lg:col-span-5 xl:col-span-2
                 p-4 h-52 bg-sidebar grid grid-cols-2 rounded-xl">
                <div class="flex flex-col justify-between">
                    <div class="flex items-center justify-start bg-indigo-500 rounded-lg p-2 w-fit h-fit themeDashIcon">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" viewBox="0 0 512 512">
                            <path
                                d="M228.3 469.1L47.6 300.4c-4.2-3.9-8.2-8.1-11.9-12.4h87c22.6 0 43-13.6 51.7-34.5l10.5-25.2 49.3 109.5c3.8 8.5 12.1 14 21.4 14.1s17.8-5 22-13.3L320 253.7l1.7 3.4c9.5 19 28.9 31 50.1 31H476.3c-3.7 4.3-7.7 8.5-11.9 12.4L283.7 469.1c-7.5 7-17.4 10.9-27.7 10.9s-20.2-3.9-27.7-10.9zM503.7 240h-132c-3 0-5.8-1.7-7.2-4.4l-23.2-46.3c-4.1-8.1-12.4-13.3-21.5-13.3s-17.4 5.1-21.5 13.3l-41.4 82.8L205.9 158.2c-3.9-8.7-12.7-14.3-22.2-14.1s-18.1 5.9-21.8 14.8l-31.8 76.3c-1.2 3-4.2 4.9-7.4 4.9H16c-2.6 0-5 .4-7.3 1.1C3 225.2 0 208.2 0 190.9v-5.8c0-69.9 50.5-129.5 119.4-141C165 36.5 211.4 51.4 244 84l12 12 12-12c32.6-32.6 79-47.5 124.6-39.9C461.5 55.6 512 115.2 512 185.1v5.8c0 16.9-2.8 33.5-8.3 49.1z" />
                        </svg>
                    </div>
                    <div class="flex flex-col justify-start">
                        <span class="text-neutral-400 text-xs">Delivered Today</span>
                        <span class="text-themeChartColorOne text-lg">{{ counters.responsesToday }} Modules</span>
                    </div>
                </div>
                <div class="flex flex-col justify-center items-end">
                    <!-- <span class="text-xs text-neutral-300">
                        "The right information brings knowledge. Knowledge is power Sharing it brings empowerment"
                    </span>
                    <div class="text-end">
                        <span class="text-xs text-themeChartColorOne">
                            Seth Godin
                        </span>
                    </div> -->
                    <img src="./../../../img/brand_new/logo-banner.png" class="w-20 h-20">
                </div>
            </div>
            <div class="themeDashCard themeLogoIddr col-span-10 sm:col-span-10 md:col-span-10 lg:col-span-10 xl:col-span-5 
            h-fit md:h-80 lg:h-80 bg-sidebar rounded-xl">
                <div class="grid grid-cols-2">
                    <div class="flex justify-between col-span-2 bg-cardtop p-4 rounded-t-xl">
                        <span class="text-neutral-300">Library Activity</span>
                        <InertiaLink :href="route('app.library')" class="w-18 bg-transparent rounded-lg text-gray-400 text-sm
                             text-xs px-2 py-1 text-center hover:bg-amber-500 hover:text-white border border-gray-400">
                            Open Library
                        </InertiaLink>
                        <InertiaLink :href="route('response-view')" class="w-18 bg-transparent rounded-lg text-gray-400 text-sm
                             text-xs px-2 py-1 text-center hover:bg-amber-500 hover:text-white border border-gray-400">
                            View More
                        </InertiaLink>
                    </div>
                </div>
                <div class="flex flex-col justify-between p-4 overflow-auto themeScrollList h-56">
                    <div class="h-full flex flex-col items-center justify-center text-themeChartNoColor"
                        v-if=" counters.documented ==0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16" viewBox="0 0 576 512">
                            <path fill="currentColor"
                                d="M0 64C0 28.7 28.7 0 64 0H224V128c0 17.7 14.3 32 32 32H384v38.6C310.1 219.5 256 287.4 256 368c0 59.1 29.1 111.3 73.7 143.3c-3.2 .5-6.4 .7-9.7 .7H64c-35.3 0-64-28.7-64-64V64zm384 64H256V0L384 128zm48 96a144 144 0 1 1 0 288 144 144 0 1 1 0-288zm59.3 107.3c6.2-6.2 6.2-16.4 0-22.6s-16.4-6.2-22.6 0L432 345.4l-36.7-36.7c-6.2-6.2-16.4-6.2-22.6 0s-6.2 16.4 0 22.6L409.4 368l-36.7 36.7c-6.2 6.2-6.2 16.4 0 22.6s16.4 6.2 22.6 0L432 390.6l36.7 36.7c6.2 6.2 16.4 6.2 22.6 0s6.2-16.4 0-22.6L454.6 368l36.7-36.7z">
                            </path>
                        </svg>
                        <span class="mt-5 text-themeChartNoColor">Nothing to display</span>
                    </div>
                    <ul class="text-gray-400 text-xs grid grid-cols-1 gap-5" v-if="counters.companyResponses">
                        <li v-for="response in counters.companyResponses" :key="response">
                            <div class="flex w-full">
                                <div
                                    class="w-6 h-6 rounded-full text-white bg-themeChartColorOne themeInvitesCircle flex items-center justify-center">
                                    <ChevronDoubleRightIcon class="h-4 w-4 text-black" aria-hidden="true" />
                                </div>
                                <div class="w-11/12 ml-2 flex items-center">
                                    <a :href="route('library-view', response.typeform_id) + '?response=' + response.id">
                                        <h5 class="text-neutral-300 text-sm flex break-all">
                                            {{ response.email }}
                                            response
                                        </h5>
                                    </a>
                                    <h6 class="text-neutral-500 text-xs text-end ml-8">
                                        {{ response.submittedAt }}
                                    </h6>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="themeDashCard themeLogoIddr col-span-10 sm:col-span-10 md:col-span-10 lg:col-span-10 xl:col-span-5
                 h-fit md:h-80 lg:h-80 bg-sidebar rounded-xl">
                <div class="grid grid-cols-2">
                    <div class="col-span-2 bg-cardtop p-4 rounded-t-xl">
                        <span class="text-neutral-300">IDDDR</span>
                    </div>
                </div>
                <div class="block sm:flex justify-between h-fit sm:h-64 md:h-64 lg:h-64 xl:h-64">
                    <div class="flex items-center justify-center">
                        <img class="themeIddrImg w-96 object-cover" src="./../../../img/idddrmindset.png">
                    </div>
                    <div
                        class="col-span-2 sm:col-span-1 md:col-span-1 lg:col-span-1 relative flex flex-col justify-between p-4 themeIddrLogo">
                        <div class="flex justify-between">
                            <div class="w-32">
                                <span class="text-themeChartColorOne text-base">Developed</span><br>
                                <h6 class="text-neutral-400 text-xs">Modules</h6>
                                <h5 class="text-themeChartColorOne">{{ counters.developedModule }}</h5>
                            </div>
                            <div class="w-32 text-end">
                                <span class="text-themeChartColorOne text-base">Delivered</span><br>
                                <h6 class="text-neutral-500 text-xs">Modules</h6>
                                <h5 class="text-themeChartColorOne">2</h5>
                            </div>
                        </div>
                        <div class="flex justify-between mt-8 sm:mt-0">
                            <div class="w-32">
                                <span class="text-themeChartColorOne text-base">Identified</span><br>
                                <h6 class="text-neutral-500 text-xs">Opportunities</h6>
                                <h5 class="text-themeChartColorOne">{{ counters.identifyCount }}</h5>
                            </div>
                            <div class="w-32 text-end">
                                <span class="text-themeChartColorOne text-base">Documented</span><br>
                                <h6 class="text-neutral-500 text-xs">Modules</h6>
                                <h5 class="text-themeChartColorOne">{{ counters.documented }}</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="themeDashCard themeChartMember col-span-10 sm:col-span-5 md:col-span-5 lg:col-span-5 xl:col-span-4 p-4 
                bg-sidebar h-full xl:h-80 rounded-xl flex flex-col justify-start">
                <div class="flex items-center justify-start">
                    <span class="text-neutral-300 text-base">My Training</span>
                    <span class="text-themeChartColorOne text-base ml-3">{{ upcomingTrainings + completedTrainings +
                        learningPathTrainings }}</span>
                </div>
                <div class="grid grid-cols-2 mt-10">
                    <div class="col-span-2 xl:col-span-1 themeChart h-full">
                        <div class="relative h-48 mt-2 noChartFound"
                            v-if="upcomingTrainings == 0 && learningPathTrainings == 0 && learningPathTrainings == 0">
                            <Doughnut :data="notFoundResult" :options="chartOptions" />
                        </div>
                        <div v-else class="h-48">
                            <Doughnut v-if="loaded" :data="trainingChartData" :options="chartOptions" />
                        </div>
                    </div>
                    <div
                        class="themeMyTraining col-span-2 xl:col-span-1 flex xl:grid  flex-col justify-center text-center mt-4">
                        <div class="flex items-center justify-center xl:justify-start">
                            <div class="rounded-lg bg-themeChartColorOne h-3 w-3"></div>
                            <span class="text-neutral-300 text-xs ml-1">Upcoming Training<span class="ml-1">({{
                                upcomingTrainings }})</span></span>
                        </div>
                        <div class="flex items-center justify-center xl:justify-start mt-2 trainingCard">
                            <div class="rounded-lg bg-themeChartColorTwo h-3 w-3"></div>
                            <span class="text-neutral-300 text-xs ml-1">Completed Training<span class="ml-1">({{
                                completedTrainings }})</span></span>
                        </div>
                        <div class="flex items-center justify-center xl:justify-start mt-2 trainingCard">
                            <div class="rounded-lg bg-themeChartColorThree h-3 w-3"></div>
                            <span class="text-neutral-300 text-xs ml-1">Learning Path<span class="ml-1">({{
                                learningPathTrainings }})</span></span>
                        </div>
                        <div class="flex items-center justify-center xl:justify-start mt-2 trainingCard">
                            <div class="rounded-lg bg-themeChartColorFour h-3 w-3"></div>
                            <span class="text-neutral-300 text-xs ml-1">Completed Learning Path<span class="ml-1">({{
                                learningPathTrainings }})</span></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="themeDashCard themeChartDash col-span-10 sm:col-span-5 md:col-span-5 lg:col-span-5 xl:col-span-3 
                p-4 h-90 bg-sidebar rounded-xl">
                <div class="grid grid-cols-1 h-full">
                    <div class="col-span-2 flex items-center justify-between">
                        <div class="col-span-2 flex items-center justify-start">
                            <span class="text-neutral-300 text-base">Identify</span>
                            <!-- <span class="text-amber-500 text-base ml-3">{{ counters.identifyCount }}</span> -->
                        </div>
                        <div>
                            <select v-model="selectIdentifyChart"
                                class="px-2 py-1 text-neutral-400 text-xs bg-sidebar rounded-sm focus:border-amber-500 focus:ring-0">
                                <option value="priority">Priority</option>
                                <option value="status">Status</option>
                            </select>
                        </div>
                    </div>
                    <div class="flex flex-col justify-between h-full">
                        <div v-if="selectIdentifyChart == 'priority'" class="h-full flex flex-col justify-between">
                            <div class="relative h-48 mt-2 noChartFound" v-if="counters.lowPriorityIdentifyCount == 0 && counters.mediumPriorityIdentifyCount == 0 &&
                                counters.highPriorityIdentifyCount == 0">
                                <Doughnut :data="notFoundResult" :options="chartOptions" />
                            </div>
                            <div v-else class="h-48">
                                <Doughnut v-if="loaded" :data="identifyPriorityChartData" :options="chartOptions" />
                            </div>
                            <div class="flex flex-col justify-center mt-4">
                                <div class="text-center mb-2">
                                    <span class="text-neutral-300 text-sm">Priority</span>
                                </div>
                                <div class="flex justify-center">
                                    <div class="flex items-center">
                                        <div class="rounded-lg bg-themeChartColorOne h-3 w-3"></div>
                                        <span class="text-neutral-300 text-xs ml-2">Low<span class="ml-1">({{
                                            counters.lowPriorityIdentifyCount }})</span></span>
                                    </div>
                                    <div class="flex items-center ml-4">
                                        <div class="rounded-lg bg-themeChartColorFour h-3 w-3"></div>
                                        <span class="text-neutral-300 text-xs ml-2">Medium<span class="ml-1">({{
                                            counters.mediumPriorityIdentifyCount }})</span></span>
                                    </div>
                                    <div class="flex items-center ml-4">
                                        <div class="rounded-lg bg-themeChartColorFive h-3 w-3"></div>
                                        <span class="text-neutral-300 text-xs ml-2">High<span class="ml-1">({{
                                            counters.highPriorityIdentifyCount }})</span></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div v-if="selectIdentifyChart == 'status'">
                            <div class="relative h-48 mt-2 noChartFound" v-if="counters.draft == 0">
                                <Doughnut :data="notFoundResult" :options="chartOptions" />
                            </div>
                            <div v-else class="h-48">
                                <Doughnut v-if="loaded" :data="identifyStatusChartData" :options="chartOptions" />
                            </div>
                            <div class="flex flex-col justify-center mt-4">
                                <div class="text-center mb-2">
                                    <span class="text-neutral-300 text-sm">Status</span>
                                </div>
                                <div class="flex justify-center">
                                    <div class="flex items-center">
                                        <div class="rounded-lg bg-themeChartColorTwo h-3 w-3"></div>
                                        <span class="text-neutral-300 text-xs ml-2">Draft<span class="ml-1">({{
                                            counters.draft }})</span></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="themeDashCard themeChartDevelop col-span-10 sm:col-span-5 md:col-span-5 lg:col-span-5 xl:col-span-3
                 p-4 h-80 bg-sidebar rounded-xl">
                <div class="flex flex-col justify-between h-full">
                    <div class="flex items-center justify-between">
                        <span class="text-neutral-300 text-base">Develop ({{ counters.developCount +
                            counters.inActiveDevelopCount }})</span>
                        <select v-model="developChartselect"
                            class="px-2 py-1 text-neutral-400 text-xs bg-sidebar rounded-sm focus:border-amber-500 focus:ring-0">
                            <option value="total_develop">Total Modules</option>
                            <option value="priority_develop">Priority</option>
                            <option value="status_develop">Status</option>
                        </select>
                    </div>
                    <div v-if="developChartselect == 'total_develop'" class="h-full flex flex-col justify-between pt-5">
                        <div class="relative h-48 mt-2 noChartFound"
                            v-if="counters.developCount == 0 && counters.inActiveDevelopCount == 0">
                            <Doughnut :data="notFoundResult" :options="chartOptions" />
                        </div>
                        <div v-else class="h-48">
                            <Doughnut v-if="loaded" :data="developTotalChart" :options="chartOptions" />
                        </div>
                        <div class="flex justify-center flex-wrap">
                            <div class="flex items-center">
                                <div class="rounded-lg bg-themeChartColorOne h-3 w-3"></div>
                                <span class="text-neutral-400 text-xs ml-3">Active Modules<span>({{
                                    counters.developCount
                                }})</span>
                                </span>
                            </div>
                            <div class="flex items-center ml-4">
                                <div class="rounded-lg bg-themeChartColorTwo h-3 w-3"></div>
                                <span class="text-neutral-400 text-xs ml-3">Inactive Modules<span>({{
                                    counters.inActiveDevelopCount }})</span></span>
                            </div>
                        </div>
                    </div>
                    <div v-if="developChartselect == 'priority_develop'" class="h-full flex flex-col justify-between pt-5">
                        <div class="relative h-48 noChartFound"
                            v-if="counters.lowPriorityDevelopCount == 0 && counters.mediumPriorityDevelopCount == 0 && counters.highPriorityDevelopCount == 0">
                            <Doughnut :data="notFoundResult" :options="chartOptions" />
                        </div>
                        <div v-else class="h-48">
                            <Doughnut v-if="loaded" :data="developPriorityChart" :options="chartOptions" />
                        </div>
                        <div class="flex justify-center flex-wrap mt-6">
                            <div class="flex items-center">
                                <div class="rounded-lg bg-themeChartColorOne h-3 w-3"></div>
                                <span class="text-neutral-300 text-xs ml-2">Low
                                    <span class="ml-1">(
                                        {{ counters.lowPriorityDevelopCount
                                        }})
                                    </span>
                                </span>
                            </div>
                            <div class="flex items-center ml-4">
                                <div class="rounded-lg bg-themeChartColorTwo h-3 w-3"></div>
                                <span class="text-neutral-300 text-xs ml-2">Medium
                                    <span class="ml-1">({{
                                        counters.mediumPriorityDevelopCount }})
                                    </span>
                                </span>
                            </div>
                            <div class="flex items-center ml-4">
                                <div class="rounded-lg bg-themeChartColorThree h-3 w-3"></div>
                                <span class="text-neutral-300 text-xs ml-2">High<span class="ml-1">({{
                                    counters.highPriorityDevelopCount }})</span></span>
                            </div>
                        </div>
                    </div>
                    <div v-if="developChartselect == 'status_develop'" class="h-full flex flex-col justify-between pt-5">
                        <div class="relative h-48 noChartFound"
                            v-if="counters.approved == 0 && counters.submitted == 0 && counters.incompleted == 0">
                            <Doughnut :data="notFoundResult" :options="chartOptions" />
                        </div>
                        <div v-else class="h-48">
                            <Doughnut v-if="loaded" :data="developStatusChart" :options="chartOptions" />
                        </div>
                        <div class="flex justify-center flex-wrap mt-6">
                            <div class="flex items-center">
                                <div class="rounded-lg bg-themeChartColorOne h-3 w-3"></div>
                                <span class="text-neutral-300 text-xs ml-2">Approval<span class="ml-1">(
                                        {{ counters.approved
                                        }})</span></span>
                            </div>
                            <div class="flex items-center ml-4">
                                <div class="rounded-lg bg-themeChartColorTwo h-3 w-3"></div>
                                <span class="text-neutral-300 text-xs ml-2">Submitted<span class="ml-1">({{
                                    counters.submitted }})</span></span>
                            </div>
                            <div class="flex items-center ml-4">
                                <div class="rounded-lg bg-themeChartColorThree h-3 w-3"></div>
                                <span class="text-neutral-300 text-xs ml-2">Incomplete<span class="ml-1">({{
                                    counters.incompleted }})</span></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="themeDashCard themeChartDevelop col-span-10 sm:col-span-5 md:col-span-5 lg:col-span-5 xl:col-span-4 
                p-4 h-80 bg-sidebar rounded-xl">
                <div class="flex flex-col justify-start h-full">
                    <div class="flex items-center justify-between">
                        <span class="text-neutral-300 text-base">Groups</span>
                        <!-- <span class="text-amber-500 text-base ml-3">{{ counters.groupCount + counters.archiveGroupCount
                        }}</span> -->
                        <inertia-link :href="route('admin.manage-groups')" class="w-18 bg-transparent rounded-lg text-gray-400 text-sm text-xs px-2 py-1 
                            text-center hover:bg-amber-500 hover:text-white border border-gray-400">View
                            More</inertia-link>
                    </div>
                    <!-- {{ counters.groups.length }} -->
                    <div class="h-full flex flex-col items-center justify-center text-themeChartNoColor"
                        v-if="counters.groupCount ==0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16" viewBox="0 0 576 512">
                            <path fill="currentColor"
                                d="M0 64C0 28.7 28.7 0 64 0H224V128c0 17.7 14.3 32 32 32H384v38.6C310.1 219.5 256 287.4 256 368c0 59.1 29.1 111.3 73.7 143.3c-3.2 .5-6.4 .7-9.7 .7H64c-35.3 0-64-28.7-64-64V64zm384 64H256V0L384 128zm48 96a144 144 0 1 1 0 288 144 144 0 1 1 0-288zm59.3 107.3c6.2-6.2 6.2-16.4 0-22.6s-16.4-6.2-22.6 0L432 345.4l-36.7-36.7c-6.2-6.2-16.4-6.2-22.6 0s-6.2 16.4 0 22.6L409.4 368l-36.7 36.7c-6.2 6.2-6.2 16.4 0 22.6s16.4 6.2 22.6 0L432 390.6l36.7 36.7c6.2 6.2 16.4 6.2 22.6 0s6.2-16.4 0-22.6L454.6 368l36.7-36.7z">
                            </path>
                        </svg>
                        <span class="mt-5 text-themeChartNoColor">Nothing to display</span>
                    </div>
                    <div class="themeChart themeScrollList overflow-y-auto">
                        <!-- <Doughnut v-if="loaded" :data="groupsChartData" :options="chartOptions" /> -->
                        <ul class="text-gray-400 text-xs mt-5 grid grid-cols-1 gap-3" v-if="counters.groups">
                            <li v-for="group in counters.groups" :key="group">
                                <div class="flex w-full">
                                    <div
                                        class="w-6 h-6 rounded-full text-white bg-themeChartColorOne themeInvitesCircle flex items-center justify-center">
                                        <ChevronDoubleRightIcon class="h-4 w-4 text-black" aria-hidden="true" />
                                    </div>
                                    <div class="w-11/12 ml-2 flex items-center flex-wrap justify-between">
                                        <h5 class="text-neutral-300 text-sm flex break-all">
                                            {{ group.name }}
                                        </h5>
                                        <h6 class="text-neutral-500 text-xs text-end ml-8">
                                            (Members - {{ group.members }}), (Modules - {{ group.forms }})
                                        </h6>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="themeDashCard themeChartModules col-span-10 sm:col-span-5 md:col-span-5 lg:col-span-5 xl:col-span-3 
                p-4 h-80 bg-sidebar rounded-xl">
                <div class="grid grid-cols-1 h-full">
                    <div class="flex flex-col justify-between h-full">
                        <div class="flex items-center justify-between flex-wrap">
                            <div class="flex" v-if="selectChart == 'learning'">
                                <span class="text-neutral-300 text-base">Learning Path</span>
                                <span class="text-themeChartColorOne text-base ml-3">{{ counters.learningPathConter +
                                    counters.archiveLearningPathConter }}</span>
                            </div>
                            <div class="flex" v-if="selectChart == 'library'">
                                <span class="text-neutral-300 text-base">Library</span>
                                <span class="text-themeChartColorOne text-base ml-3">{{ counters.activelibraryCollection +
                                    counters.inActiveLibraryCollection }}</span>
                            </div>
                            <div class="flex" v-if="selectChart == 'xme'">
                                <span class="text-neutral-300 text-base">XME Area</span>
                                <span class="text-themeChartColorOne text-base ml-3">{{ counters.activeXmeAreaCollection +
                                    counters.inActiveXmeAreaCollection }}</span>
                            </div>
                            <div>
                                <select v-model="selectChart"
                                    class="px-2 py-1 text-neutral-400 text-xs bg-sidebar rounded-sm focus:border-amber-500 focus:ring-0">
                                    <option value="learning">Learning Path</option>
                                    <option value="library">Library</option>
                                    <option value="xme">XME Area</option>
                                </select>
                            </div>
                        </div>
                        <div v-if="selectChart == 'learning'" class="h-full flex flex-col justify-between pt-5">
                            <div class="relative h-48 noChartFound"
                                v-if="counters.learningPathConter == 0 && counters.archiveLearningPathConter == 0">
                                <Doughnut :data="notFoundResult" :options="chartOptions" />
                            </div>
                            <div v-else class="h-48">
                                <Doughnut v-if="loaded" :data="learningChartData" :options="chartOptions" />
                            </div>
                            <div class="flex justify-center mt-4">
                                <div class="flex items-center">
                                    <div class="rounded-lg bg-themeChartColorOne h-3 w-3"></div>
                                    <span class="text-neutral-300 text-xs ml-2">Active<span class="ml-1">({{
                                        counters.learningPathConter }})</span></span>
                                </div>
                                <div class="flex items-center ml-4">
                                    <div class="rounded-lg bg-themeChartColorTwo h-3 w-3"></div>
                                    <span class="text-neutral-300 text-xs ml-2">Archive<span class="ml-1">({{
                                        counters.archiveLearningPathConter }})</span></span>
                                </div>
                            </div>
                        </div>
                        <div v-if="selectChart == 'library'" class="h-full flex flex-col justify-between pt-5">
                            <div class="relative h-48 noChartFound"
                                v-if="counters.activelibraryCollection == 0 && counters.inActiveLibraryCollection == 0">
                                <Doughnut :data="notFoundResult" :options="chartOptions" />
                            </div>
                            <div v-else class="h-48">
                                <Doughnut v-if="loaded" :data="libraryChartData" :options="chartOptions" />
                            </div>
                            <div class="flex justify-center mt-4">
                                <div class="flex items-center">
                                    <div class="rounded-lg bg-themeChartColorOne h-3 w-3"></div>
                                    <span class="text-neutral-300 text-xs ml-2">Active<span class="ml-1">({{
                                        counters.activelibraryCollection }})</span></span>
                                </div>
                                <div class="flex items-center ml-4">
                                    <div class="rounded-lg bg-themeChartColorTwo h-3 w-3"></div>
                                    <span class="text-neutral-300 text-xs ml-2">Archive<span class="ml-1">({{
                                        counters.inActiveLibraryCollection }})</span></span>
                                </div>
                            </div>
                        </div>
                        <div v-if="selectChart == 'xme'" class="h-full flex flex-col justify-between pt-5">
                            <div class="relative h-48 mt-2 noChartFound"
                                v-if="counters.activeXmeAreaCollection == 0 && counters.inActiveXmeAreaCollection == 0">
                                <Doughnut :data="notFoundResult" :options="chartOptions" />
                            </div>
                            <div v-else class="h-48">
                                <Doughnut v-if="loaded" :data="XmeChartData" :options="chartOptions" />
                            </div>
                            <div class="flex justify-center mt-4">
                                <div class="flex items-center">
                                    <div class="rounded-lg bg-themeChartColorOne h-3 w-3"></div>
                                    <span class="text-neutral-300 text-xs ml-2">Active<span class="ml-1">({{
                                        counters.activeXmeAreaCollection }})</span></span>
                                </div>
                                <div class="flex items-center ml-4">
                                    <div class="rounded-lg bg-themeChartColorTwo h-3 w-3"></div>
                                    <span class="text-neutral-300 text-xs ml-2">Archive<span class="ml-1">({{
                                        counters.inActiveXmeAreaCollection }})</span></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="themeDashCard themeChartDevelop col-span-10 sm:col-span-5 md:col-span-5 lg:col-span-5 
                xl:col-span-3 p-4 h-90 bg-sidebar rounded-xl">
                <div class="grid grid-cols-1 h-full">
                    <div class="col-span-2 flex items-center justify-between flex-wrap">
                        <div class="col-span-2 flex items-center justify-start">
                            <span class="text-neutral-300 text-base">Review Area</span>
                            <span class="text-themeChartColorOne text-base ml-3">{{ counters.reviewFormCounter +
                                counters.assignedModulesCouter }}</span>
                        </div>
                        <div>
                            <select v-model="selectReviewChart"
                                class="px-2 py-1 text-neutral-400 text-xs bg-sidebar rounded-sm focus:border-amber-500 focus:ring-0">
                                <option value="livemodule">Live Module</option>
                                <option value="assignmodule">Assigned Module</option>
                            </select>
                        </div>
                    </div>
                    <div v-if="selectReviewChart == 'livemodule'" class="flex flex-col justify-between h-full">
                        <div class="relative h-48 mt-2 noChartFound"
                            v-if="counters.reviewFormLibraryCounter == 0 && counters.reviewFormXmeAreaCounter == 0">
                            <Doughnut :data="notFoundResult" :options="chartOptions" />
                        </div>
                        <div v-else class="h-48">
                            <Doughnut v-if="loaded" :data="reviewAreaChartData" :options="chartOptions" />
                        </div>
                        <div class="flex flex-col justify-center">
                            <div class="text-center mb-2">
                                <span class="text-neutral-300 text-sm">Review area for live module
                                    <span class="text-themeChartColorOne text-sm ml-1">({{ counters.reviewFormCounter
                                    }})</span>
                                </span>
                            </div>
                            <div class="flex justify-center flex-wrap">
                                <div class="flex items-center">
                                    <div class="rounded-lg bg-themeChartColorOne h-3 w-3"></div>
                                    <span class="text-neutral-300 text-xs ml-2">Library Modules<span class="ml-1">({{
                                        counters.reviewFormLibraryCounter }})</span></span>
                                </div>
                                <div class="flex items-center ml-4">
                                    <div class="rounded-lg bg-themeChartColorTwo h-3 w-3"></div>
                                    <span class="text-neutral-300 text-xs ml-2">XME Modules<span class="ml-1">({{
                                        counters.reviewFormXmeAreaCounter }})</span></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div v-if="selectReviewChart == 'assignmodule'" class="flex flex-col justify-between h-full">
                        <div class="relative h-48 mt-2 noChartFound"
                            v-if="counters.newAssignedModuleCounter == 0 && counters.updateAssignedModuleCounter == 0 && counters.adminRequestAssignedModuleCounter == 0">
                            <Doughnut :data="notFoundResult" :options="chartOptions" />
                        </div>
                        <div v-else class="h-48">
                            <Doughnut v-if="loaded" :data="reviewModulesChartData" :options="chartOptions" />
                        </div>
                        <div class="flex flex-col justify-center">
                            <div class="text-center mb-2">
                                <span class="text-neutral-300 text-sm">New assigned module
                                    <span class="text-themeChartColorOne text-sm ml-1">({{ counters.assignedModulesCouter
                                    }})</span>
                                </span>
                            </div>
                            <div class="flex justify-center flex-wrap">
                                <div class="flex items-center">
                                    <div class="rounded-lg bg-themeChartColorOne h-3 w-3"></div>
                                    <span class="text-neutral-300 text-xs ml-2">New Modules<span class="ml-1">({{
                                        counters.newAssignedModuleCounter }})</span></span>
                                </div>
                                <div class="flex items-center ml-4">
                                    <div class="rounded-lg bg-themeChartColorTwo h-3 w-3"></div>
                                    <span class="text-neutral-300 text-xs ml-2">Support Reply<span class="ml-1">({{
                                        counters.updateAssignedModuleCounter }})</span></span>
                                </div>
                                <div class="flex items-center ml-4">
                                    <div class="rounded-lg bg-themeChartColorThree h-3 w-3"></div>
                                    <span class="text-neutral-300 text-xs ml-2">Request Send to Reply<span class="ml-1">({{
                                        counters.adminRequestAssignedModuleCounter }})</span></span>
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
    ChevronRightIcon, ChevronDoubleRightIcon
} from '@heroicons/vue/24/solid'
import GeneralMixin from "@/Mixins/GeneralMixin";
import { mapStores } from 'pinia'
import { useAppStore } from '@/store'

import { Bar, Doughnut, PolarArea } from 'vue-chartjs'
import { Chart as ChartJS, Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale, ArcElement, RadialLinearScale } from 'chart.js'

ChartJS.register(Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale, ArcElement, RadialLinearScale)

export default {
    mixins: [GeneralMixin],
    props: ["user"],
    components: {
        ChevronRightIcon, ChevronDoubleRightIcon,
        AuthenticatedLayout,
        Bar, Doughnut, PolarArea,
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
            selectChart: 'learning',
            selectReviewChart: 'assignmodule',
            selectIdentifyChart: 'priority',

            trainingChartData: {
                labels: [
                    'Upcoming Trainings',
                    'Completed Trainings',
                    'Learning Path',
                    'Completed Learning Path'
                ],
                datasets: [
                    {
                        cutout: '60%',
                        label: 'My Trainings',
                        borderWidth: 0,
                        backgroundColor: ['#1ae5ae', '#1faa9c', '#0581a8', '#2f5f98'],
                        data: [10, 5, 5, 5]
                    }
                ]
            },
            learningChartData: {
                labels: ['Active', 'Archive'],
                datasets: [
                    {
                        backgroundColor: ['#1ae5ae', '#1faa9c'],
                        data: [15, 10],
                        borderWidth: 0,
                        cutout: '60%'
                    }
                ]
            },
            libraryChartData: {
                labels: ['Active', 'Archive'],
                datasets: [
                    {
                        backgroundColor: ['#1ae5ae', '#1faa9c'],
                        data: [3, 10],
                        borderWidth: 0,
                        cutout: '60%'
                    }
                ]
            },
            XmeChartData: {
                labels: ['Active', 'Archive'],
                datasets: [
                    {
                        backgroundColor: ['#1ae5ae', '#1faa9c'],
                        data: [6, 10],
                        borderWidth: 0,
                        cutout: '60%'
                    }
                ]
            },
            identifyChartData: {
                labels: ['Low', 'Medium', 'High'],
                datasets: [
                    {
                        barPercentage: 0.5,
                        barThickness: 30,
                        minBarLength: 20,
                        borderRadius: 15,
                        label: ['Priority'],
                        backgroundColor: ['#f59e0b', '#10b981', '#6875f5'],
                        data: [50, 10, 10],
                        borderWidth: 0,
                        cutout: '60%'
                    }
                ]
            },
            groupsChartData: {
                labels: ['Active', 'Inactive'],
                datasets: [
                    {
                        label: ['Status'],
                        backgroundColor: ['#f59e0b', '#10b981'],
                        data: [5, 5],
                        borderWidth: 0,
                        cutout: '60%'
                    }
                ]
            },
            identifyStatusChartData: {
                labels: ['Draft', 'Backburner'],
                datasets: [
                    {
                        label: ['Status'],
                        backgroundColor: ['#1ae5ae', '#1faa9c'],
                        data: [5, 5],
                        borderWidth: 0,
                        cutout: '60%'
                    }
                ]
            },
            identifyPriorityChartData: {
                labels: ['Low', 'Medium', 'High'],
                datasets: [
                    {
                        label: ['Priority'],
                        backgroundColor: ['#1ae5ae', '#2f5f98', '#31356e'],
                        data: [5, 5, 5],
                        borderWidth: 0,
                        cutout: '60%'
                    }
                ]
            },
            reviewModulesChartData: {
                labels: ['New Modules', 'Update Modules', 'Admin Request'],
                datasets: [
                    {
                        label: ['New Assigned Module'],
                        backgroundColor: ['#1ae5ae', '#1faa9c', '#0581a8'],
                        data: [5, 5, 5],
                        borderWidth: 0,
                        cutout: '60%'
                    }
                ]
            },
            reviewAreaChartData: {
                labels: ['Library Modules', 'XME Modules'],
                datasets: [
                    {
                        backgroundColor: ['#1ae5ae', '#1faa9c'],
                        data: [10, 10],
                        borderWidth: 0,
                        cutout: '60%'
                    }
                ]
            },
            reviewAreaAssignChartData: {
                labels: ['New Modules', 'Update Modules', 'Admin Request'],
                datasets: [
                    {
                        barPercentage: 0.5,
                        barThickness: 30,
                        minBarLength: 20,
                        borderRadius: 15,
                        label: 'New Modules',
                        backgroundColor: ['#f59e0b', '#10b981', '#6875f5'],
                        data: [10, 10, 10],
                        borderWidth: 0
                    }
                ],
            },

            //develop modules chart start
            developChartselect: 'total_develop',
            developTotalChart: {
                labels: ['Active Modules', 'Inactive Modules'],
                datasets: [
                    {
                        backgroundColor: ['#1ae5ae', '#1faa9c'],
                        data: [10, 10],
                        borderWidth: 0,
                        cutout: '60%'
                    }
                ]
            },
            developPriorityChart: {
                labels: ['Low', 'Medium', 'High'],
                datasets: [
                    {
                        backgroundColor: ['#1ae5ae', '#1faa9c', '#0581a8'],
                        data: [10, 10, 20],
                        borderWidth: 0,
                        cutout: '60%'
                    }
                ]
            },
            developStatusChart: {
                labels: ['Approval', 'Submitted', 'Incomplete'],
                datasets: [
                    {
                        backgroundColor: ['#1ae5ae', '#1faa9c', '#0581a8'],
                        data: [10, 10, 10],
                        borderWidth: 0,
                        cutout: '60%'
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
            learningPathTrainings: 0,
            counters: {},
            upcomingQueryData: {
                sortField: 'assigned_date',
                upcomingStatus: null,
            },
            completedQueryData: {
                sortField: 'forms.id',
                orderDir: 'ASC',
            },
            LearninQueryData: {
                sortField: 'learning_paths.id',
                orderDir: 'DESC'
            },
        }
    },
    computed: {
        ...mapStores(useAppStore),
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
        companyCounters() {
            axios.get('/api/company-dashboard-counters')
                .then((response) => {
                    this.counters = response.data.data;
                    this.identifyPriorityChartData.datasets[0].data = [this.counters.lowPriorityIdentifyCount, this.counters.mediumPriorityIdentifyCount, this.counters.highPriorityIdentifyCount];
                    this.trainingChartData.datasets[0].data = [this.upcomingTrainings,
                    this.completedTrainings,
                    this.learningPathTrainings,
                    this.learningPathTrainings];
                    this.developTotalChart.datasets[0].data = [this.counters.developCount, this.counters.inActiveDevelopCount];
                    this.developPriorityChart.datasets[0].data = [this.counters.lowPriorityDevelopCount,
                    this.counters.mediumPriorityDevelopCount,
                    this.counters.highPriorityDevelopCount];
                    this.developStatusChart.datasets[0].data = [this.counters.approved,
                    this.counters.submitted,
                    this.counters.incompleted];
                    this.learningChartData.datasets[0].data = [this.counters.learningPathConter,
                    this.counters.archiveLearningPathConter];
                    this.libraryChartData.datasets[0].data = [this.counters.activelibraryCollection,
                    this.counters.inActiveLibraryCollection];
                    this.XmeChartData.datasets[0].data = [this.counters.activeXmeAreaCollection,
                    this.counters.inActiveXmeAreaCollection];
                    this.reviewAreaChartData.datasets[0].data = [this.counters.reviewFormLibraryCounter,
                    this.counters.reviewFormXmeAreaCounter];
                    this.reviewModulesChartData.datasets[0].data = [this.counters.newAssignedModuleCounter,
                    this.counters.updateAssignedModuleCounter,
                    this.counters.adminRequestAssignedModuleCounter];
                })
                .catch((error) => {

                })
                .finally(() => {
                    this.loaded = true;
                });

        },
    },
    created: function () {
        let that = this
        that.appStore.userLogin(that.user);
        that.upcomingTraingCounter();
        that.completedTrainingCounter();
        that.learningPathTrainingCounter();
        that.companyCounters();
    }
};
</script>