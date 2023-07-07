<template>
    <div class="themeSideBar relative py-4 pl-4">
        <div id="sideMenu" class="themeSideMenu bg-sidebar theme-scrollbar h-full rounded-xl" tabindex="-1"
            aria-labelledby="drawer-navigation-label">
            <div class="themeXmeLogo theme-side-nav flex items-center justify-center px-5 pt-5 pb-4 relative">
                <div>
                    <div class="theme-menu-logo">
                    </div>
                </div>
                <div>
                    <button @click="closeSideBar()"
                        class="absolute top-5 -right-4 themeMenuCloseButton bg-sidebar rounded-tr-xl rounded-br-xl">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" height="36" width="36">
                            <path fill="#1ae5ae"
                                d="M5,13L9,17L7.6,18.42L1.18,12L7.6,5.58L9,7L5,11H21V13H5M21,6V8H11V6H21M21,16V18H11V16H21Z" />
                        </svg>
                    </button>
                </div>
            </div>
            <div class="themeMenuList px-3 pt-6">
                <div class="sideBarMenu">
                    <ul class="pb-10" v-if="openedSidebar == true">
                        <li class="relative themeMenuItem openItem"
                            :class="(index > 0 ? ' mt-4' : '') + (openedSidebar == true ? ' showMenu' : '')"
                            v-for="(menuItem, index) in menus" :key="menuItem">
                            <inertia-link v-if="menuItem.isDropdown == false" :href="route(menuItem.link)" class="themeMenuLink flex items-center bg-cardtop p-2 text-base 
                                font-normal text-gray-300 rounded-lg px-3 py-4 hover:bg-amber-500 hover:text-white">
                                <component v-bind:is="menuItem.iconClass" class="h-5 w-5 text-white" />
                                <span class="themeOpenMenu currentColor ml-3 text-sm w-76">{{ menuItem.title }}
                                </span>
                                <span class="themeMenuTitle currentColor ml-3 text-sm w-40">{{ menuItem.title }}
                                </span>
                            </inertia-link>
                            <div @click="openMultiLevelMenu(index)" v-else
                                class="menuOpened flex items-center justify-between bg-cardtop hover:bg-amber-500
                                 hover:text-white p-2 text-base font-normal text-gray-300 rounded-lg px-3 py-4"
                                :class="{ opened: index === showMenu }">
                                <span class="flex">
                                    <component v-bind:is="menuItem.iconClass" class="h-5 w-5 text-white" />
                                    <span class="themeOpenMenu currentColor ml-3 text-sm w-76">{{ menuItem.title }}
                                    </span>
                                    <span class="themeMenuTitle currentColor ml-3 text-sm w-40">{{ menuItem.title }}
                                    </span>
                                </span>
                                <span class="themeMultiIcon">
                                    <ChevronUpIcon v-if="index == showMenu" class="h-5 w-5 text-gray-300"
                                        aria-hidden="true" />
                                    <ChevronDownIcon v-else class="h-5 w-5 text-gray-300" aria-hidden="true" />
                                </span>
                            </div>
                            <div class="hidden multiMenu" :class="{ opened: index === showMenu }">
                                <ul class="themeMultiList rounded-xl" v-if="menuItem.children">
                                    <li class="flex items-center bg-neutral-700 p-2 text-base font-normal 
                                    text-gray-300 px-3 py-4 rounded-t-xl themeMenuHide">
                                        {{ menuItem.title }}</li>
                                    <li v-for="(child, cindex) in menuItem.children" :key="cindex">
                                        <inertia-link :href="route(child.link)"
                                            :class="cindex == menuItem.children.length - 1 ? 'rounded-b-xl' : ''" class="themeMenuLink themeLinkMulti flex items-center bg-cardtop p-2 text-base font-normal 
                                        text-gray-900 px-3 py-4 hover:bg-neutral-700" preserve-state replace>
                                            <component v-bind:is="child.iconClass" class="h-5 w-5 text-white" />
                                            <span class="text-gray-300 ml-3 text-sm w-40">{{ child.title }}</span>
                                        </inertia-link>
                                    </li>
                                </ul>
                            </div>
                        </li>
                    </ul>
                    <ul class="pb-10" v-else>
                        <li class="relative themeMenuItem"
                            :class="(menuItem.isDropdown == true ? 'themeMultiMenu' : '') + (index > 0 ? ' mt-4' : '')"
                            v-for="(menuItem, index) in menus" :key="menuItem">
                            <inertia-link v-if="menuItem.isDropdown == false" :href="route(menuItem.link)" class="themeMenuLink flex items-center bg-cardtop p-2 text-base 
                                font-normal text-gray-900 rounded-lg px-3 py-4 hover:bg-amber-500 transition">
                                <component v-bind:is="menuItem.iconClass" class="h-5 w-5 text-white" />
                                <span class="themeOpenMenu text-gray-300 ml-3 text-sm w-76">{{ menuItem.title }}
                                </span>
                                <span class="themeMenuTitle text-gray-300 ml-3 text-sm w-40">{{ menuItem.title }}
                                </span>
                            </inertia-link>
                            <div v-else
                                class="themeMenuLink flex items-center justify-between bg-cardtop hover:bg-amber-500 p-2 text-base font-normal text-gray-900 rounded-lg px-3 py-4">
                                <span class="flex">
                                    <component v-bind:is="menuItem.iconClass" class="h-5 w-5 text-white" />
                                    <span class="themeOpenMenu text-gray-300 ml-3 text-sm w-76">{{ menuItem.title }}
                                    </span>
                                    <span class="themeMenuTitle text-gray-300 ml-3 text-sm w-40">{{ menuItem.title }}
                                    </span>
                                </span>
                                <span class="themeMultiIcon">
                                    <ChevronDownIcon class="h-5 w-5 text-gray-300" aria-hidden="true" />
                                </span>
                            </div>
                            <ul class="themeMultiList rounded-xl" :class="{ hidden: openMultiMenu }"
                                v-if="menuItem.children">
                                <li
                                    class="flex items-center bg-neutral-700 p-2 text-base font-normal text-gray-300 px-3 py-4 rounded-t-xl themeMenuHide">
                                    {{ menuItem.title }}</li>
                                <li v-for="(child, cindex) in menuItem.children" :key="cindex">
                                    <inertia-link :href="route(child.link)"
                                        :class="cindex == menuItem.children.length - 1 ? 'rounded-b-xl' : ''" class="themeMenuLink themeLinkMulti flex items-center bg-cardtop p-2 text-base font-normal 
                                        text-gray-900 px-3 py-4 hover:bg-neutral-700" preserve-state replace>
                                        <component v-bind:is="child.iconClass" class="h-5 w-5 text-white" />
                                        <span class="text-gray-300 ml-3 text-sm w-40">{{ child.title }}</span>
                                    </inertia-link>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

import { mapState, mapStores } from 'pinia'
import { useAppStore } from '@/store/index'
import {
    HomeIcon, BuildingOfficeIcon, ArrowPathRoundedSquareIcon,
    FingerPrintIcon, UserGroupIcon, ClipboardDocumentListIcon,
    EnvelopeIcon, EnvelopeOpenIcon, InboxStackIcon, InboxArrowDownIcon,
    WrenchIcon, AcademicCapIcon, BeakerIcon, PhotoIcon, QueueListIcon, AdjustmentsVerticalIcon,
    UserCircleIcon, RectangleGroupIcon, FolderOpenIcon, UserIcon, Bars3BottomLeftIcon, ChevronDownIcon, IdentificationIcon,
    CreditCardIcon, DocumentTextIcon, XMarkIcon, ChevronUpIcon
} from '@heroicons/vue/24/solid'
export default {
    name: 'SideMenu',
    props: ['method', 'openedSidebar'],
    components: {
        HomeIcon, BuildingOfficeIcon, ArrowPathRoundedSquareIcon,
        FingerPrintIcon, UserGroupIcon, ClipboardDocumentListIcon,
        EnvelopeIcon, EnvelopeOpenIcon, InboxStackIcon, InboxArrowDownIcon,
        WrenchIcon, AcademicCapIcon, BeakerIcon, PhotoIcon, QueueListIcon, AdjustmentsVerticalIcon,
        UserCircleIcon, RectangleGroupIcon, FolderOpenIcon, UserIcon, Bars3BottomLeftIcon, ChevronDownIcon, IdentificationIcon,
        CreditCardIcon, DocumentTextIcon, XMarkIcon, ChevronUpIcon
    },
    computed: {
        ...mapStores(useAppStore),
        ...mapState(useAppStore, ["notification", "warning"]),
        // showNavbar() {
        //     if (this.hiddenNavbarRoutes.findIndex( i => i == route().current()) != -1) {
        //         return false
        //     }
        //     return true
        // }
    },
    data() {
        return {
            openMultiMenu: true,
            border: true,
            menus: [],
            showMenu: null
        }
    },
    methods: {
        openMultiLevelMenu(index) {
            console.log("your index is " + index);
            this.showMenu === index ? (this.showMenu = null) : (this.showMenu = index);
        },
        createMenu() {
            if (this.appStore.custom_menu == 'undefined' || this.appStore.custom_menu == undefined || this.appStore.custom_menu == null) {
                axios.get('/api/menus')
                    .then((response) => {
                        this.menus = response.data.data;
                        this.appStore.custom_menu = response.data.data;
                    })
                    .catch((error) => {

                    })
                    .finally(() => {
                    });
            }
            else {
                this.menus = this.appStore.custom_menu;
            }
        },
        closeSideBar() {
            this.$emit("closeSideBar");
        },
        toggleMultiMenu() {
            this.openMultiMenu = !this.openMultiMenu;
            this.border = !this.border;
        }
    },
    created: function () {
        this.createMenu();
    }
}
</script>