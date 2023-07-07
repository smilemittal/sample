<template>
    <div class="topBarMenu bg-body pt-1 pb-1 px-4 theme-top-blur relative top-1">
        <div class="w-full rounded-lg flex items-center justify-between py-3">
            <div class="theme-nav-top flex items-center">
                <button class="themeMobileMenuBtn sidebarMenu h-10 w-10 mr-3 rounded-full" type="button" @click="toggle()">
                    <span class="sideMenuOpen" :class="{ 'menuOpenIcon' : openedBar == true}">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                            <path
                                d="M5,13L9,17L7.6,18.42L1.18,12L7.6,5.58L9,7L5,11H21V13H5M21,6V8H11V6H21M21,16V18H11V16H21Z" />
                        </svg>
                    </span>
                </button>
            </div>
            <div>
                <h3 class="text-slate-400" v-if="userCompany">{{ userCompany.name }}</h3>
            </div>
            <div>
                <TopBarUserMenu></TopBarUserMenu>
            </div>
        </div>
    </div>
</template>

<script>

import TopBarUserMenu from '@/Components/Menus/TopBarUserMenu.vue'
import {
    Bars3BottomLeftIcon,
    Bars3BottomRightIcon
} from '@heroicons/vue/24/solid'
import { mapState, mapStores } from 'pinia'
import { useAppStore } from '@/store'
export default {
    name: 'TopBar',
    components: {
        TopBarUserMenu,
        Bars3BottomRightIcon,
        Bars3BottomLeftIcon
    },
    props:["openedBar"],
    methods: {
        toggle() {
            this.$emit("toggleMenu");
        },
    }, 
    computed: {
        ...mapStores(useAppStore),
        ...mapState(useAppStore,[
            "userCompany",
            "user",'userRole'
        ]),
    },
}

</script>