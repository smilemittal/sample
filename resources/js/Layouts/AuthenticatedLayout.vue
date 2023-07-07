<template>
    <div class="p-0 h-full bg-body themeOverflow">
        <!-- hard reload please refresh design -->
        <div v-if="getNewVersion"
            class="absolute z-10 -translate-x-2/4 left-1/2 w-full sm:w-fit md:w-fit bg-body m-auto text-amber-500 p-4 bg-blueCust-300 rounded-md text-center text-blueCust-800 font-medium text-sm flex justify-center items-center">
            <img :src="cdn('images/info.svg')" alt="" srcset="" class="mr-3" />
            {{ trans("lang.labels.new_version_notice") }}
            <button @click="hardRefreshApp" class="ml-2 underline cursor-pointer">
                Refresh
            </button>
        </div>
        <!-- hard reload please refresh design -->
        <div class="lg:flex md:flex block h-full">
            <!-----------Sidebar menu--------------------------------->
            <div class="themeMenuOverlay themePosSticky modal-open" :class="{ openSidebar: isActive }" data-open="modal">
                <side-menu class="" :class="{ openSidebar: isActive }" @closeSideBar="toggleMenu()" :openedSidebar="isActive"></side-menu>
            </div>
            <div class="themeMainContent w-full px-4">
                <!------------Topbar menu---------------------------->
                <top-bar @toggleMenu="toggleMenu()" :openedBar="isActive"></top-bar>
                <div class="pt-5 themePageContent" :style="{minHeight : (windowHeight - 132) + 'px'}">
                    <div class="page-container">
                        <!-- <main> -->
                            <slot />
                        <!-- </main> -->
                    </div>
                </div>
            </div>
        </div>
        <footer-main></footer-main>
    </div>

    <Notifications />
</template>
<script>
import TopBar from '@/Components/Menus/TopBar.vue'
import SideMenu from '@/Components/Menus/SideMenu.vue'
import FooterMain from '@/Components/Menus/Footer.vue'
import { mapState, mapStores } from 'pinia';
import { useAppStore } from '@/store/index';

// import { methods } from 'vue3-timepicker'
import GeneralMixin from "@/Mixins/GeneralMixin";
export default {
    mixins: [GeneralMixin],
    name: 'Layout',
    components: {
        TopBar,
        SideMenu,
        FooterMain
    },
    data() {
        return {
            isActive: false,
            windowHeight: window.innerHeight
        }
    },
    watch: {
        isActive: function () {
            document.body.classList = this.isActive ? "openedSidebar" : "";
        }
    },
    computed: {
        ...mapStores(useAppStore),
        ...mapState(useAppStore, ["notification", "warning",
            "companyCan",
            "companyUsed",
            "companyAllow",
            "shouldCouponApply",
            "user",
            "getNewVersion",
        ]),
    },
    methods: {
        hardRefreshApp() {
            window.location.reload(true)
        },
        toggleMenu() {
            this.isActive = !this.isActive;
        }
    }
}
</script>