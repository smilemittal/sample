<template>
    <div class="themeDashboard mt-2">
        <div class="grid grid-cols-10 gap-8">
            <div class="themeDashCard themeDashLogo col-span-10 sm:col-span-10 md:col-span-5 lg:col-span-5 xl:col-span-4
            p-4 border border-neutral-700 rounded-xl h-52 flex items-center justify-center">
                <img class="w-fit h-full" src="./../../../img/brand_new/dash_logo.png">
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
            <div class="themeDashCard themeDashCounter col-span-10 sm:col-span-10 md:col-span-5 lg:col-span-5 xl:col-span-2
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
                        <span class="text-themeChartColorOne text-lg">{{ counters.totalDelivered }} Modules</span>
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
            <div class="themeDashCard themeDashCounter themeDashModule col-span-10 sm:col-span-10 md:col-span-5 lg:col-span-5 xl:col-span-2
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
                        <span class="text-themeChartColorOne text-lg">0 Modules</span>
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
            <div class="themeDashCard themeChartDash col-span-10 sm:col-span-10 md:col-span-5 lg:col-span-5 xl:col-span-2 p-4 bg-sidebar 
                h-80 rounded-xl flex flex-col justify-between">
                <div class="flex items-center justify-start">
                    <span class="text-neutral-300 text-base">Total Companies</span>
                    <span class="text-themeChartColorOne text-base ml-3">{{ counters.companies + counters.archiveCompany
                    }}</span>
                </div>
                <div class="themeChart h-48">
                    <div class="relative h-full mt-2 noChartFound"
                        v-if="counters.companies == 0 && counters.archiveCompany == 0">
                        <Doughnut :data="notFoundResult" :options="chartOptions" />
                    </div>
                    <div v-else class="h-40">
                        <Doughnut v-if="loaded" :data="comapniesChart" :options="chartOptions" />
                    </div>
                </div>
                <div class="flex justify-center flex-wrap">
                    <div class="flex items-center">
                        <div class="rounded-lg bg-themeChartColorOne h-3 w-3"></div>
                        <span class="text-neutral-300 text-xs ml-2">Active<span class="ml-1">({{ counters.companies
                        }})</span></span>
                    </div>
                    <div class="flex items-center ml-4">
                        <div class="rounded-lg bg-themeChartColorTwo h-3 w-3"></div>
                        <span class="text-neutral-300 text-xs ml-2">Inactive<span class="ml-1">({{ counters.archiveCompany
                        }})</span></span>
                    </div>
                </div>
            </div>
            <div class="themeDashCard themeChartModules col-span-10 sm:col-span-10 md:col-span-5 lg:col-span-5 xl:col-span-3 
                p-4 h-80 bg-sidebar rounded-xl">
                <div class="flex flex-col justify-between h-full">
                    <div class="col-span-2 flex items-center justify-between">
                        <div>
                            <span class="text-neutral-300 text-base">Total Modules</span>
                            <span class="text-themeChartColorOne text-base ml-3">{{ counters.forms + counters.xmeAreaForms
                            }}</span>
                        </div>
                        <select v-model="selectModulesChart"
                            class="px-2 py-1 text-neutral-400 text-xs bg-sidebar rounded-sm focus:border-amber-500 focus:ring-0">
                            <option value="library_m">Library Modules</option>
                            <option value="xme_m">XME Modules</option>
                        </select>
                    </div>
                    <div v-if="selectModulesChart == 'library_m'" class="flex flex-col justify-end h-full">
                        <div class="themeChart h-48 mb-5">
                            <div class="relative h-full mt-2 noChartFound"
                                v-if="counters.activeForms == 0 && counters.inActiveForms == 0">
                                <Doughnut :data="notFoundResult" :options="chartOptions" />
                            </div>
                            <div v-else class="h-40">
                                <Doughnut v-if="loaded" :data="libraryModulesData" :options="chartOptions" />
                            </div>
                        </div>
                        <div class="flex flex-col align-center justify-center">
                            <div class="flex justify-center mt-2 flex-wrap">
                                <div class="flex items-center">
                                    <div class="rounded-lg bg-themeChartColorOne h-3 w-3"></div>
                                    <span class="text-neutral-300 text-xs ml-2">Active<span class="ml-1">({{
                                        counters.activeForms }})</span></span>
                                </div>
                                <div class="flex items-center ml-4">
                                    <div class="rounded-lg bg-themeChartColorTwo h-3 w-3"></div>
                                    <span class="text-neutral-300 text-xs ml-2">Inactive<span class="ml-1">({{
                                        counters.inActiveForms }})</span></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div v-if="selectModulesChart == 'xme_m'" class="flex flex-col justify-end h-full">
                        <div class="themeChart h-48 mb-5">
                            <div class="relative h-full mt-2 noChartFound"
                                v-if="counters.activeXmeArea == 0 && counters.inActiveXmeArea == 0">
                                <Doughnut :data="notFoundResult" :options="chartOptions" />
                            </div>
                            <div v-else class="h-40">
                                <Doughnut v-if="loaded" :data="xmeModulesData" :options="chartOptions" />
                            </div>
                        </div>
                        <div class="flex flex-col align-center justify-center">
                            <div class="flex justify-center flex-wrap mt-2">
                                <div class="flex items-center">
                                    <div class="rounded-lg bg-themeChartColorOne h-3 w-3"></div>
                                    <span class="text-neutral-300 text-xs ml-2">Active<span class="ml-1">({{
                                        counters.activeXmeArea }})</span></span>
                                </div>
                                <div class="flex items-center ml-4">
                                    <div class="rounded-lg bg-themeChartColorTwo h-3 w-3"></div>
                                    <span class="text-neutral-300 text-xs ml-2">Inactive<span class="ml-1">({{
                                        counters.inActiveXmeArea }})</span></span>
                                </div>
                            </div>
                        </div>
                    </div>
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
                                <h5 class="text-themeChartColorOne">{{ counters.identifies }}</h5>
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
            <div
                class="themeDashCard themeChartMember col-span-10 sm:col-span-5 md:col-span-5 lg:col-span-5 xl:col-span-4 p-4 h-80 bg-sidebar rounded-xl">
                <div class="grid grid-cols-1 h-full">
                    <div class="flex flex-col justify-between h-full">
                        <div class="flex items-center justify-start justify-between">
                            <div>
                                <span class="text-neutral-300 text-base">Total Members</span>
                                <span class="text-themeChartColorOne text-base ml-3">{{ counters.users +
                                    counters.archiveUsers
                                }}</span>
                            </div>
                            <select v-model="selectMemberChart"
                                class="px-2 py-1 text-neutral-400 text-xs bg-sidebar rounded-sm focus:border-amber-500 focus:ring-0">
                                <option value="active_m">Active Members</option>
                                <option value="inactive_m">Inactive Members</option>
                            </select>
                        </div>
                        <div v-if="selectMemberChart == 'active_m'" class="flex flex-col justify-end h-full">
                            <div class="themeChart h-48 mb-5">
                                <div class="relative h-full mt-2 noChartFound"
                                    v-if="counters.users == 0 && counters.archiveUsers == 0">
                                    <Doughnut :data="notFoundResult" :options="chartOptions" />
                                </div>
                                <div v-else class="h-40">
                                    <Doughnut v-if="loaded" :data="activeMembers" :options="chartOptions" />
                                </div>
                            </div>
                            <div class="flex flex-col align-center justify-center">
                                <div class="flex justify-center flex-wrap mt-2">
                                    <div class="flex justify-center">
                                        <div class="flex items-center">
                                            <div class="rounded-lg bg-themeChartColorOne h-3 w-3"></div>
                                            <span class="text-neutral-300 text-xs ml-2">Active<span class="ml-1">({{
                                                counters.users
                                            }})</span></span>
                                        </div>
                                        <div class="flex items-center ml-4">
                                            <div class="rounded-lg bg-themeChartColorTwo h-3 w-3"></div>
                                            <span class="text-neutral-300 text-xs ml-2">Inactive<span class="ml-1">({{

                                                counters.archiveUsers }})</span></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div v-if="selectMemberChart == 'inactive_m'" class="flex flex-col justify-end h-full">
                            <div class="themeChart h-48 mb-5">
                                <div class="relative h-full mt-2 noChartFound"
                                    v-if="counters.users == 0 && counters.archiveUsers == 0">
                                    <Doughnut :data="notFoundResult" :options="chartOptions" />
                                </div>
                                <div v-else class="h-40">
                                    <Doughnut v-if="loaded" :data="inactiveMembers" :options="chartOptions" />
                                </div>
                            </div>
                            <div class="flex flex-col align-center justify-center">
                                <div class="flex justify-center mt-2">
                                    <div class="flex justify-center flex-wrap">
                                        <div class="flex items-center">
                                            <div class="rounded-lg bg-themeChartColorOne h-3 w-3"></div>
                                            <span class="text-neutral-300 text-xs ml-2">Active<span class="ml-1">({{
                                                counters.users
                                            }})</span></span>
                                        </div>
                                        <div class="flex items-center ml-4">
                                            <div class="rounded-lg bg-themeChartColorTwo h-3 w-3"></div>
                                            <span class="text-neutral-300 text-xs ml-2">Inactive<span class="ml-1">({{

                                                counters.archiveUsers }})</span></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div
                class="themeDashCard themeChartXme col-span-10 sm:col-span-5 md:col-span-5 lg:col-span-5 xl:col-span-3 p-4 h-80 bg-sidebar rounded-xl">
                <div class="flex flex-col justify-between h-full">
                    <div class="flex items-center justify-between">
                        <span v-if="modulesChartHistory == 'xmereview_m'" class="text-neutral-300 text-base">XME Review
                            Area Modules</span>
                        <span v-if="modulesChartHistory == 'reviewcompany_m'" class="text-neutral-300 text-base">Review
                            Company Modules</span>
                        <select v-model="modulesChartHistory"
                            class="px-2 py-1 text-neutral-400 text-xs bg-sidebar rounded-sm focus:border-amber-500 focus:ring-0">
                            <option value="xmereview_m">XME Review Area</option>
                            <option value="reviewcompany_m">Review Company</option>
                        </select>
                    </div>
                    <div v-if="modulesChartHistory == 'xmereview_m'" class="flex flex-col justify-end h-full">
                        <div class="themeChart h-48 mb-5">
                            <div class="relative h-full mt-2 noChartFound"
                                v-if="counters.users == 0 && counters.archiveUsers == 0">
                                <Doughnut :data="notFoundResult" :options="chartOptions" />
                            </div>
                            <div v-else class="h-40">
                                <Doughnut v-if="loaded" :data="xmeModuleHistoryChart" :options="chartOptions" />
                            </div>
                        </div>
                        <div class="flex flex-col align-center justify-center">
                            <div class="flex justify-center mt-2">
                                <div class="flex justify-center flex-wrap">
                                    <div class="flex items-center">
                                        <div class="rounded-lg bg-themeChartColorOne h-3 w-3"></div>
                                        <span class="text-neutral-300 text-xs ml-2">Total Modules<span class="ml-1">({{
                                            counters.users
                                        }})</span></span>
                                    </div>
                                    <div class="flex items-center ml-4">
                                        <div class="rounded-lg bg-themeChartColorTwo h-3 w-3"></div>
                                        <span class="text-neutral-300 text-xs ml-2">Pending<span class="ml-1">({{
                                            counters.users +
                                            counters.archiveUsers }})</span></span>
                                    </div>
                                    <div class="flex items-center ml-4">
                                        <div class="rounded-lg bg-themeChartColorThree h-3 w-3"></div>
                                        <span class="text-neutral-300 text-xs ml-2">History<span class="ml-1">({{
                                            counters.users +
                                            counters.archiveUsers }})</span></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div v-if="modulesChartHistory == 'reviewcompany_m'" class="flex flex-col justify-end h-full">
                        <div class="themeChart h-48 mb-5">
                            <div class="relative h-full mt-2 noChartFound"
                                v-if="counters.pendingModule == 0 && counters.historyModule == 0">
                                <Doughnut :data="notFoundResult" :options="chartOptions" />
                            </div>
                            <div v-else class="h-40">
                                <Doughnut v-if="loaded" :data="reviewModuleHistoryChart" :options="chartOptions" />
                            </div>
                        </div>
                        <div class="flex flex-col align-center justify-center">
                            <div class="flex justify-center mt-2">
                                <div class="flex justify-center flex-wrap">
                                    <div class="flex items-center">
                                        <div class="rounded-lg bg-themeChartColorOne h-3 w-3"></div>
                                        <span class="text-neutral-300 text-xs ml-2">Total Modules<span class="ml-1">({{
                                            counters.pendingModule + counters.historyModule
                                        }})</span></span>
                                    </div>
                                    <div class="flex items-center ml-4">
                                        <div class="rounded-lg bg-themeChartColorTwo h-3 w-3"></div>
                                        <span class="text-neutral-300 text-xs ml-2">Pending<span class="ml-1">({{
                                            counters.pendingModule }})</span></span>
                                    </div>
                                    <div class="flex items-center ml-4">
                                        <div class="rounded-lg bg-themeChartColorThree h-3 w-3"></div>
                                        <span class="text-neutral-300 text-xs ml-2">History<span class="ml-1">({{
                                            counters.historyModule }})</span></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- <div class="flex flex-col justify-between mt-4">
                        <div class="themeXmeArea">
                            <ul class="mt-2 pl-4">
                                <li class="relative flex items-center justify-between text-neutral-400 text-xs"><span
                                        class="block w-28">Total Modules</span><span class="ml-6 flex items-center">
                                        <ChevronRightIcon class="h-4 w-4 text-white mr-2" aria-hidden="true" />
                                        <span class="text-amber-500">({{ counters.pendingModule + counters.historyModule
                                        }})</span>
                                    </span>
                                </li>
                                <li class="relative flex items-center justify-between text-neutral-400 text-xs mt-2"><span
                                        class="block w-28">History</span><span class="ml-6 flex items-center">
                                        <ChevronRightIcon class="h-4 w-4 text-white mr-2" aria-hidden="true" />
                                        <span class="text-amber-500">({{ counters.historyModule }})</span>
                                    </span>
                                </li>
                                <li class="relative flex items-center justify-between text-neutral-400 text-xs mt-2"><span
                                        class="block w-28">Pending</span><span class="ml-6 flex items-center">
                                        <ChevronRightIcon class="h-4 w-4 text-white mr-2" aria-hidden="true" />
                                        <span class="text-amber-500">({{ counters.pendingModule }})</span>
                                    </span>
                                </li>
                            </ul>
                        </div>
                    </div> -->
                </div>
            </div>
            <div
                class="themeDashCard themeChartDevelop col-span-10 sm:col-span-5 md:col-span-5 lg:col-span-5 xl:col-span-3 p-4 h-80 bg-sidebar rounded-xl">
                <div class="flex flex-col justify-between h-full">
                    <div class="flex flex-col justify-between h-full">
                        <div class="flex items-center justify-between">
                            <span class="text-neutral-300 text-base">Total Develop Modules ({{ counters.adminDevelopCount +
                                counters.adminArchiveDevelopCount
                            }})</span>
                            <select v-model="developChartselect"
                                class="px-2 py-1 text-neutral-400 text-xs bg-sidebar rounded-sm focus:border-amber-500 focus:ring-0">
                                <option value="total_develop">Total Modules</option>
                                <option value="priority_develop">Priority</option>
                                <option value="status_develop">Status</option>
                            </select>
                        </div>
                        <div v-if="developChartselect == 'total_develop'" class="flex flex-col justify-end h-full">
                            <div class="themeChart h-48 mb-5">
                                <div class="relative h-full mt-2 noChartFound"
                                    v-if="counters.adminDevelopCount == 0 && counters.adminArchiveDevelopCount == 0">
                                    <Doughnut :data="notFoundResult" :options="chartOptions" />
                                </div>
                                <div v-else class="h-40">
                                    <Doughnut v-if="loaded" :data="developTotalModules" :options="chartOptions" />
                                </div>
                            </div>
                            <div class="flex flex-col align-center justify-center">
                                <div class="flex justify-center mt-2">
                                    <div class="flex justify-center flex-wrap">
                                        <div class="flex items-center">
                                            <div class="rounded-lg bg-themeChartColorOne h-3 w-3"></div>
                                            <span class="text-neutral-300 text-xs ml-2">Active Modules<span class="ml-1">({{
                                                counters.adminDevelopCount
                                            }})</span></span>
                                        </div>
                                        <div class="flex items-center ml-4">
                                            <div class="rounded-lg bg-themeChartColorTwo h-3 w-3"></div>
                                            <span class="text-neutral-300 text-xs ml-2">Inactive Modules<span
                                                    class="ml-1">({{
                                                        counters.adminArchiveDevelopCount }})</span></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div v-if="developChartselect == 'priority_develop'" class="flex flex-col justify-end h-full">
                            <div class="themeChart h-48 mb-5">
                                <div class="relative h-full mt-2 noChartFound"
                                    v-if="counters.lowPriorityDevelopCount == 0 && counters.mediumPriortyDevelopCount == 0 && counters.highPriorityDevelopCount == 0">
                                    <Doughnut :data="notFoundResult" :options="chartOptions" />
                                </div>
                                <div v-else class="h-40">
                                    <Doughnut v-if="loaded" :data="developPriorityModules" :options="chartOptions" />
                                </div>
                            </div>
                            <div class="flex flex-col align-center justify-center">
                                <div class="flex justify-center mt-2">
                                    <div class="flex justify-center flex-wrap">
                                        <div class="flex items-center">
                                            <div class="rounded-lg bg-themeChartColorOne h-3 w-3"></div>
                                            <span class="text-neutral-300 text-xs ml-2">Low<span class="ml-1">({{
                                                counters.lowPriorityDevelopCount
                                            }})</span></span>
                                        </div>
                                        <div class="flex items-center ml-4">
                                            <div class="rounded-lg bg-themeChartColorTwo h-3 w-3"></div>
                                            <span class="text-neutral-300 text-xs ml-2">Medium<span class="ml-1">({{
                                                counters.mediumPriortyDevelopCount }})</span></span>
                                        </div>
                                        <div class="flex items-center ml-4">
                                            <div class="rounded-lg bg-themeChartColorThree h-3 w-3"></div>
                                            <span class="text-neutral-300 text-xs ml-2">High<span class="ml-1">({{
                                                counters.highPriorityDevelopCount }})</span></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div v-if="developChartselect == 'status_develop'" class="flex flex-col justify-end h-full">
                            <div class="themeChart h-48 mb-5">
                                <div class="relative h-full mt-2 noChartFound"
                                    v-if="counters.submittedDevelopCount == 0 && counters.reOpenDevelopCount == 0">
                                    <Doughnut :data="notFoundResult" :options="chartOptions" />
                                </div>
                                <div v-else class="h-40">
                                    <Doughnut v-if="loaded" :data="developStatusModules" :options="chartOptions" />
                                </div>
                            </div>
                            <div class="flex flex-col align-center justify-center">
                                <div class="flex justify-center mt-2">
                                    <div class="flex justify-center flex-wrap">
                                        <div class="flex items-center">
                                            <div class="rounded-lg bg-themeChartColorOne h-3 w-3"></div>
                                            <span class="text-neutral-300 text-xs ml-2">Submitted Modules<span
                                                    class="ml-1">({{
                                                        counters.submittedDevelopCount
                                                    }})</span></span>
                                        </div>
                                        <div class="flex items-center ml-4">
                                            <div class="rounded-lg bg-themeChartColorTwo h-3 w-3"></div>
                                            <span class="text-neutral-300 text-xs ml-2">Reopen Modules<span class="ml-1">({{
                                                counters.reOpenDevelopCount }})</span></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- <div class="flex flex-col justify-between themeXmeArea h-full pl-4">
                            <div class="mt-3">
                                <ul>
                                    <li class="relative flex items-center text-neutral-400 text-xs"><span
                                            class="block w-28">Status</span>
                                    </li>
                                </ul>
                                <div class="flex justify-between mt-2">
                                    <span class="text-neutral-400 text-xs">Submitted<span>({{ counters.submittedDevelopCount
                                    }})</span></span>
                                    <span class="text-neutral-400 text-xs">Reopen<span>({{ counters.reOpenDevelopCount
                                    }})</span></span>
                                </div>
                            </div>
                            <div class="mt-3">
                                <ul>
                                    <li class="relative flex items-center text-neutral-400 text-xs"><span
                                            class="block w-36">Reopen Modules</span><span class="ml-6 flex items-center">
                                            <ChevronRightIcon class="h-4 w-4 text-white mr-2" aria-hidden="true" />
                                            <span class="text-amber-500">(10)</span>
                                        </span>
                                    </li>
                                    <li class="relative flex items-center text-neutral-400 text-xs mt-2"><span
                                            class="block w-36">Reopen Request</span><span class="ml-6 flex items-center">
                                            <ChevronRightIcon class="h-4 w-4 text-white mr-2" aria-hidden="true" />
                                            <span class="text-amber-500">(10)</span>
                                        </span>
                                    </li>
                                </ul>
                            </div>
                        </div> -->
                    </div>
                </div>
            </div>
            <div
                class="themeDashCard themeLibraryCard col-span-10 sm:col-span-5 md:col-span-5 lg:col-span-5 xl:col-span-3 p-4 h-80 bg-sidebar rounded-xl">
                <div class="flex flex-col justify-between h-full">
                    <div class="flex flex-col justify-between h-full">
                        <div class="flex items-center justify-between">
                            <span class="text-neutral-300 text-base">Library Activity</span>
                            <a :href="route('response-view')"
                                class="w-18 bg-transparent rounded-lg text-gray-400 text-sm
                             text-xs px-2 py-1 text-center hover:bg-amber-500 hover:text-white border border-gray-400">View
                                More
                            </a>
                        </div>
                        <div class="flex flex-col justify-between themeLibraryActivity h-full">
                            <div class="mt-4 h-56 overflow-y-auto">
                                <ul class="relative themeLibraryUl">
                                    <li v-for="response in counters.responses" :key="response.id"
                                        class="relative flex items-center text-neutral-400 text-xs">
                                        <span
                                            class="w-6 h-6 bg-themeChartColorOne rounded-full mr-3 flex items-center justify-center">
                                            <ChevronRightIcon class="h-4 w-4 text-black" aria-hidden="true" />
                                        </span>
                                        <div class="flex flex-col items-start">
                                            <a :href="route('library-view', response.typeform_id) + '?response=' + response.id">
                                                <span class="block break-all">{{ response.email }} left a response</span>
                                                <span class="block w-full text-xs flex items-center">
                                                    {{ response.submittedAt }}
                                                </span>
                                            </a>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div
                class="themeDashCard themeMemberInvitesCard col-span-10 sm:col-span-5 md:col-span-5 lg:col-span-5 xl:col-span-4 h-80 bg-sidebar rounded-xl">
                <div class="grid grid-cols-2">
                    <div class="flex justify-between items-center col-span-2 bg-cardtop p-4 rounded-t-xl">
                        <span class="text-neutral-300">Pending Member Invites</span>
                        <InertiaLink :href="route('admin.manage-users')" class="w-18 bg-transparent rounded-lg text-gray-400 text-sm
                             text-xs px-2 py-1 text-center hover:bg-amber-500 hover:text-white border border-gray-400">
                            View
                            More
                        </InertiaLink>
                    </div>
                </div>
                <div class="flex flex-col justify-between p-4 overflow-auto themeScrollList h-56">
                    <ul class="grid grid-cols-1 gap-4">
                        <li v-for="member in counters.pendingMembers" :key="member.id">
                            <div class="flex w-full items-center">
                                <div
                                    class="w-2 h-2 rounded-full text-white bg-themeChartColorOne themeInvitesCircle flex items-center justify-center">
                                    <ChevronDoubleRightIcon class="h-4 w-4 text-black" aria-hidden="true" />
                                </div>
                                <div class="w-4/5 ml-2 flex justify-between">
                                    <h5 class="text-neutral-300 text-sm flex break-all">
                                        {{ member.email }} was invited
                                    </h5>
                                    <h6 class="text-neutral-500 text-xs text-end w-44"> ({{ member.created_at }})</h6>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
            <div
                class="themeDashCard themeGroupCard col-span-10 sm:col-span-5 md:col-span-5 lg:col-span-5 xl:col-span-3 h-80 bg-sidebar rounded-xl">
                <div class="grid grid-cols-2">
                    <div class="flex justify-between col-span-2 bg-cardtop p-4 rounded-t-xl">
                        <span class="text-neutral-300">Created Groups</span>
                        <InertiaLink :href="route('admin.manage-groups')" class="w-18 bg-transparent rounded-lg text-gray-400 text-sm
                             text-xs px-2 py-1 text-center hover:bg-amber-500 hover:text-white border border-gray-400">
                            View
                            More
                        </InertiaLink>
                    </div>
                </div>
                <div class="flex flex-col justify-between p-4 overflow-auto themeScrollList h-56">
                    <ul class="grid grid-cols-1 gap-3">
                        <li v-for="group in counters.groups" :key="group.id">
                            <div class="flex w-full">
                                <div
                                    class="w-6 h-6 rounded-full text-white bg-themeChartColorOne themeInvitesCircle flex items-center justify-center">
                                    <ChevronDoubleRightIcon class="h-4 w-4 text-black" aria-hidden="true" />
                                </div>
                                <div class="w-11/12 ml-2">
                                    <h5 class="text-neutral-300 text-sm flex break-all">
                                        {{ group.name }}
                                    </h5>
                                    <h6 class="text-neutral-500 text-xs text-end">{{ group.created_at }}</h6>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
            <div
                class="themeDashCard themeActivityLogs col-span-10 sm:col-span-5 md:col-span-5 lg:col-span-5 xl:col-span-4 h-80 bg-sidebar rounded-xl">
                <div class="grid grid-cols-2">
                    <div class="flex justify-between col-span-2 bg-cardtop p-4 rounded-t-xl">
                        <span class="text-neutral-300">Activity Logs</span>
                        <InertiaLink :href="route('activity.logs')" class="w-18 bg-transparent rounded-lg text-gray-400 text-sm
                             text-xs px-2 py-1 text-center hover:bg-amber-500 hover:text-white border border-gray-400">
                            View
                            More
                        </InertiaLink>
                    </div>
                </div>
                <div class="flex flex-col justify-between p-4 overflow-auto themeScrollList h-56">
                    <ul class="grid grid-cols-1 gap-3">
                        <li v-for="activityLog in counters.activityLogs" :key="activityLog.id">
                            <div class="flex w-full">
                                <div
                                    class="w-6 h-6 rounded-full text-white bg-themeChartColorOne themeInvitesCircle flex items-center justify-center">
                                    <ChevronDoubleRightIcon class="h-4 w-4 text-black" aria-hidden="true" />
                                </div>
                                <div class="w-4/5 ml-2 flex justify-between">
                                    <h5 class="text-neutral-300 text-sm flex break-all">
                                        {{ activityLog.subject }}
                                    </h5>
                                    <h6 class="text-neutral-500 text-xs text-end w-44">{{ activityLog.createdAt }}</h6>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="grid grid-cols-10 gap-8 mt-8">

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
import { useAppStore } from "@/store";

import { Bar, Doughnut, PolarArea } from 'vue-chartjs'
import { Chart as ChartJS, Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale, ArcElement, RadialLinearScale } from 'chart.js'

ChartJS.register(Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale, ArcElement, RadialLinearScale)

export default {
    mixins: [GeneralMixin],
    props: ["user"],
    computed: {
        ...mapStores(useAppStore),
    },
    components: {
        ChevronRightIcon, ChevronDoubleRightIcon,
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
                        backgroundColor: ['#151515'],
                        data: [.1]
                    }
                ]
            },
            loaded: false,
            //company chart start 
            comapniesChart: {
                labels: ['Active', 'Inactive'],
                datasets: [
                    {
                        backgroundColor: ['#1ae5ae', '#1faa9c'],
                        data: [10, 10],
                        borderWidth: 0,
                        cutout: '60%'
                    }
                ]
            },
            //company chart end

            // library modules and xme modules chart start
            selectModulesChart: 'library_m',
            libraryModulesData: {
                labels: ['Library Modules'],
                datasets: [
                    {
                        borderWidth: 0,
                        cutout: '60%',
                        backgroundColor: ['#1ae5ae', '#1faa9c'],
                        data: [21, 79]
                    }
                ]
            },
            xmeModulesData: {
                labels: ['XME Modules'],
                datasets: [
                    {
                        borderWidth: 0,
                        cutout: '60%',
                        backgroundColor: ['#1ae5ae', '#1faa9c'],
                        data: [21, 20]
                    }
                ]
            },
            // library modules and xme modules chart end

            identifyModulesData: {
                labels: ['Library Modules', 'XME Modules'],
                datasets: [
                    {
                        borderWidth: 0,
                        cutout: '40%',
                        label: 'Active',
                        backgroundColor: ['#1ae5ae', '#1faa9c', '#0581a8', '#2f5f98'],
                        data: [21, 79]
                    },
                    {
                        borderWidth: 0,
                        cutout: '40%',
                        label: 'Inactive',
                        backgroundColor: ['#1ae5ae', '#1faa9c', '#0581a8', '#2f5f98'],
                        data: [33, 67]
                    }
                ]
            },
            //active inactive members chart data start
            selectMemberChart: 'active_m',
            inactiveMembers: {
                labels: ['Invite Member', 'Invite Company Account', 'Invite Business Admin', 'Pending Invite'],
                datasets: [
                    {
                        borderWidth: 0,
                        cutout: '60%',
                        label: 'Inactive',
                        backgroundColor: ['#1ae5ae', '#1faa9c', '#0581a8', '#2f5f98'],
                        data: [33, 67, 40, 20]
                    }
                ]
            },
            activeMembers: {
                labels: ['Invite Member', 'Invite Company Account', 'Invite Business Admin', 'Pending Invite'],
                datasets: [
                    {
                        borderWidth: 0,
                        cutout: '60%',
                        label: 'Active',
                        backgroundColor: ['#1ae5ae', '#1faa9c', '#0581a8', '#2f5f98'],
                        data: [21, 79, 40, 40]
                    }
                ]
            },
            //active inactive members chart data end

            //xme modules and review company modules chart data start
            modulesChartHistory: 'xmereview_m',
            xmeModuleHistoryChart: {
                labels: ['Total Modules', 'History', 'Pending'],
                datasets: [
                    {
                        borderWidth: 0,
                        cutout: '60%',
                        label: 'XME Review Area',
                        backgroundColor: ['#1ae5ae', '#1faa9c', '#0581a8'],
                        data: [21, 79, 40]
                    }
                ]
            },
            reviewModuleHistoryChart: {
                labels: ['FTotal Modules', 'History', 'Pending'],
                datasets: [
                    {
                        borderWidth: 0,
                        cutout: '60%',
                        label: 'Review Company',
                        backgroundColor: ['#1ae5ae', '#1faa9c', '#0581a8'],
                        data: [21, 79, 40]
                    }
                ]
            },
            //xme modules and review company modules chart data end

            // develop charts start 
            developChartselect: 'total_develop',
            developTotalModules: {
                labels: ['Active Modules', 'Inactive Modules'],
                datasets: [
                    {
                        borderWidth: 0,
                        cutout: '60%',
                        label: 'Develop Modules',
                        backgroundColor: ['#1ae5ae', '#1faa9c'],
                        data: [21, 79]
                    }
                ]
            },
            developPriorityModules: {
                labels: ['Low', 'Medium', 'High'],
                datasets: [
                    {
                        borderWidth: 0,
                        cutout: '60%',
                        label: 'Develop Modules',
                        backgroundColor: ['#1ae5ae', '#1faa9c', '#0581a8'],
                        data: [21, 79, 40]
                    }
                ]
            },
            developStatusModules: {
                labels: ['Submitted Modules', 'Reopen Modules'],
                datasets: [
                    {
                        borderWidth: 0,
                        cutout: '60%',
                        label: 'Develop Modules',
                        backgroundColor: ['#1ae5ae', '#1faa9c'],
                        data: [21, 79]
                    }
                ]
            },
            developReopenModules: {
                labels: ['Reopen Modules', 'Reopen Request'],
                datasets: [
                    {
                        borderWidth: 0,
                        cutout: '60%',
                        label: 'Develop Modules',
                        backgroundColor: ['#1ae5ae', '#1faa9c'],
                        data: [21, 79]
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
            counters: {},
        }
    },
    methods: {
        adminCounters() {
            axios.get('/api/admin-dashboard-counters')
                .then((response) => {
                    this.counters = response.data.data;
                    this.comapniesChart.datasets[0].data = [this.counters.companies, this.counters.archiveCompany];
                    this.libraryModulesData.datasets[0].data = [this.counters.activeForms, this.counters.inActiveForms];
                    this.xmeModulesData.datasets[0].data = [this.counters.activeXmeArea, this.counters.inActiveXmeArea];
                    this.developTotalModules.datasets[0].data = [this.counters.adminDevelopCount, this.counters.adminArchiveDevelopCount];
                    this.developPriorityModules.datasets[0].data = [this.counters.lowPriorityDevelopCount, this.counters.mediumPriortyDevelopCount, this.counters.highPriorityDevelopCount];
                    this.developStatusModules.datasets[0].data = [this.this.counters.submittedDevelopCount, this.counters.highPriorityDevelopCount]
                })
                .catch((error) => {

                })
                .finally(() => {
                    this.loaded = true;
                });
        },

    },
    created: function () {
        this.appStore.userLogin(this.user);
        this.adminCounters();
    }
};
</script>