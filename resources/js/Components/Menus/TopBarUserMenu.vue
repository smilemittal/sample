<template>
    <ul class="flex items-center">
        <li class="block sm:hidden relative rounded-full ml-1 h-12 w-12 theme-btn flex items-center theme-user-alert border-2 
            border-transparent hover:border-themeChartColorOne rounded-lg">
            <inertia-link :href="route('app.notification-list')"
                class="relative rounded-full sidebarMenu h-10 w-10 flex items-center justify-center p-1">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 bellIcon"
                    :class="{ 'bell-shake': unreadNotificationCount != 0 }" viewBox="0 0 448 512">
                    <path
                        d="M224 0c-17.7 0-32 14.3-32 32V51.2C119 66 64 130.6 64 208v18.8c0 47-17.3 92.4-48.5 127.6l-7.4 8.3c-8.4 9.4-10.4 22.9-5.3 34.4S19.4 416 32 416H416c12.6 0 24-7.4 29.2-18.9s3.1-25-5.3-34.4l-7.4-8.3C401.3 319.2 384 273.9 384 226.8V208c0-77.4-55-142-128-156.8V32c0-17.7-14.3-32-32-32zm45.3 493.3c12-12 18.7-28.3 18.7-45.3H224 160c0 17 6.7 33.3 18.7 45.3s28.3 18.7 45.3 18.7s33.3-6.7 45.3-18.7z" />
                </svg>
                <div class="absolute -top-2 -right-1 bg-themeChartColorOne w-7 h-7 rounded-full flex items-center justify-center"
                    v-if="unreadNotificationCount > 0">
                    <span class="text-white text-sm text-bold" v-if="unreadNotificationCount <= 50">{{
                        unreadNotificationCount }}</span>
                    <span class="text-white text-xs font-bold flex" v-else>50</span>
                </div>
            </inertia-link>
        </li>
        <li
            class="hidden sm:block relative rounded-full ml-1 h-12 w-12 theme-btn flex items-center theme-user-alert border-2 
                                                                    border-transparent hover:border-themeChartColorOne rounded-lg">
            <button class="relative rounded-full sidebarMenu h-10 w-10 flex items-center justify-center p-1">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 bellIcon"
                    :class="{ 'bell-shake': unreadNotificationCount != 0 }" viewBox="0 0 448 512">
                    <path
                        d="M224 0c-17.7 0-32 14.3-32 32V51.2C119 66 64 130.6 64 208v18.8c0 47-17.3 92.4-48.5 127.6l-7.4 8.3c-8.4 9.4-10.4 22.9-5.3 34.4S19.4 416 32 416H416c12.6 0 24-7.4 29.2-18.9s3.1-25-5.3-34.4l-7.4-8.3C401.3 319.2 384 273.9 384 226.8V208c0-77.4-55-142-128-156.8V32c0-17.7-14.3-32-32-32zm45.3 493.3c12-12 18.7-28.3 18.7-45.3H224 160c0 17 6.7 33.3 18.7 45.3s28.3 18.7 45.3 18.7s33.3-6.7 45.3-18.7z" />
                </svg>
                <div class="absolute -top-2 -right-1 bg-themeChartColorOne w-7 h-7 rounded-full flex items-center justify-center"
                    v-if="unreadNotificationCount > 0">
                    <span class="text-white text-sm text-bold" v-if="unreadNotificationCount <= 50">{{
                        unreadNotificationCount }}</span>
                    <span class="text-white text-xs font-bold flex" v-else>50</span>
                </div>
            </button>
            <div id="dropdownOffset"
                class="z-10 rounded-xl theme-top-menu bg-card border-2 border-amber-500 theme-user-menualert">
                <div class="flex items-center bg-card w-full px-4 py-3 rounded-t-2xl text-white">
                    <span>Notifications</span>
                </div>
                <!-- alert lists  -->
                <div class="min-h-fit max-h-44 overflow-y-auto bg-body themeUlAlert">
                    <ul class="themeAlerts" v-if="unreadNotificationCount > 0">
                        <li class="py-3 px-2" v-for="notification in recentNotifications" :key="notification.id">
                            <a class="flex" :href="getNotificationLink(notification, userRole)">
                                <div class='flex items-center justify-center h-6 w-6 bg-amber-500 rounded-full mr-2'>
                                    <span class='notify-icon'>
                                        <component v-bind:is="notification.iconClass" class="h-4 w-4 text-white" />
                                    </span>
                                </div>
                                <div class="themeNotify">
                                    <div class="flex text-xs text-gray-500 justify-between"
                                        v-html="getNotificationText(notification)"></div>
                                    <div class="text-end"><span class="text-xs text-gray-400">{{ notification.differenceDate
                                    }}</span>
                                    </div>
                                </div>
                            </a>
                        </li>
                    </ul>

                    <div class="bg-body flex items-center justify-center px-2 py-4" v-else>
                        <span class="text-gray-500">No Notifications</span>
                    </div>
                </div>
                <div class="flex items-center justify-center text-center">
                    <inertia-link :href="route('app.notification-list')"
                        class="bg-amber-500 w-full px-4 py-3 rounded-b-xl text-white"><span>See All</span></inertia-link>
                </div>
            </div>
        </li>
        <li
            class="relative rounded-full h-12 w-12 theme-btn theme-user-btn border-2 border-transparent hover:border-themeChartColorOne rounded-lg ml-3">
            <button id="" class="w-full h-full rounded-lg bg-btnSubmitBg p-1" type="button">
                <img src="./../../../img/placeholder-logo.jpeg" class="rounded-lg h-full w-full object-cover">
            </button>
            <!-- Dropdown menu -->
            <div id="dropdownOffset"
                class="z-10 rounded-xl theme-top-menu bg-card border-2 border-amber-500 theme-user-menu">
                <ul class="text-sm text-gray-700 dark:text-gray-200" aria-labelledby="dropdownDefault">
                    <li class="px-4 py-3 bg-card text-white rounded-t-2xl">Options</li>
                    <li class="">

                        <inertia-link :href="route('member-profile')"
                            class="cursor-pointer flex items-center bg-body w-full px-4 py-3 hover:bg-card text-white">
                            <UserIcon class="h-5 w-5 text-white mr-2" aria-hidden="true" />
                            My Profile
                        </inertia-link>
                    </li>
                    <li class="rounded-b-2xl" v-if="!checkMember && !checkAdmin">
                        <inertia-link :href="route('profile.show')"
                            class="cursor-pointer flex items-center bg-body w-full px-4 py-3 hover:bg-card text-white">
                            <BriefcaseIcon class="h-5 w-5 text-white mr-2" aria-hidden="true" />
                            My Business
                        </inertia-link>
                    </li>
                    <li v-if="!checkAdmin">
                        <inertia-link :href="route('app.my-trainings')"
                            class="cursor-pointer flex items-center bg-body w-full px-4 py-3 hover:bg-card text-white">
                            <AcademicCapIcon class="h-5 w-5 text-white mr-2" aria-hidden="true" />
                            My Training
                        </inertia-link>
                    </li>
                    <li>
                        <inertia-link
                            class="cursor-pointer flex rounded-b-xl items-center bg-body w-full px-4 py-3 hover:bg-card text-red-600"
                            :href="route('logout')" method="post" as="button">
                            <ArrowLeftOnRectangleIcon class="h-5 w-5 text-red-600 mr-2" aria-hidden="true" />
                            Log Out
                        </inertia-link>
                    </li>
                </ul>
            </div>
        </li>
    </ul>
</template>
<script>
import {
    BriefcaseIcon,
    AcademicCapIcon,
    UserIcon,
    ArrowLeftOnRectangleIcon,
    BellIcon, PlusIcon, PencilSquareIcon,
    UserGroupIcon, ArchiveBoxIcon, ArchiveBoxArrowDownIcon, TrashIcon, QuestionMarkCircleIcon, CheckCircleIcon, ClipboardDocumentCheckIcon,
    FireIcon, AdjustmentsVerticalIcon, UserPlusIcon
} from '@heroicons/vue/24/solid'
import { mapState, mapStores } from 'pinia'
import { useAppStore } from '@/store'
import GeneralMixin from "@/Mixins/GeneralMixin";
import { isAdmin, isMember } from "@/helpers";

export default {
    mixins: [GeneralMixin],
    name: 'TopBarUerMenu',
    components: {
        BriefcaseIcon,
        AcademicCapIcon,
        UserIcon, PlusIcon,
        ArrowLeftOnRectangleIcon,
        BellIcon, PencilSquareIcon,
        UserGroupIcon, ArchiveBoxIcon, ArchiveBoxArrowDownIcon, TrashIcon, QuestionMarkCircleIcon, CheckCircleIcon, ClipboardDocumentCheckIcon,
        FireIcon, AdjustmentsVerticalIcon, UserPlusIcon
    },
    computed: {
        ...mapStores(useAppStore),
        ...mapState(useAppStore, [
            "unreadNotificationCount",
            "recentNotifications",
            "user", 'userRole'
        ]),
        checkAdmin() {
            let role = this.appStore.userRole;
            return isAdmin(role);
        },
        checkMember() {
            let role = this.appStore.userRole;
            return isMember(role);
        }
    },
    created() {
        this.appStore.fetchNotifications()
    },
}

</script>