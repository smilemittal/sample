<template>
    <div class="p-0 h-full bg-body">
        <div class="lg:flex md:block h-full overflow-y-auto">
            <!-----------Sidebar menu--------------------------------->
            <side-menu></side-menu>
            <div class="w-full overflow-y-auto">
                <!------------Topbar menu---------------------------->
                <top-bar></top-bar>
                <div class="px-5 py-5">
                    <!------router view layout------------>

                    <img src="./../../img/xme_logo.png">
                   
                </div>
            </div>
            <!-- <footer-main></footer-main> -->
        </div>
    </div>
</template>

<script>
import SideMenu from '@/Components/Menus/SideMenu.vue'
import TopBar from '@/Components/Menus/TopBar.vue'

export default {
    components: {
        SideMenu,
        TopBar,
    },
    props: ["user"],
    created() {
        console.log(this.user);
        this.appStore.userLogin(this.user);
    },
    data() {
        return {
            closeSideMenu: false,
            themeSideVisible: false
        }
    },
    methods: {
        toogleSideBar() {
            console.log("Parent");
        },
    }
}
</script>
