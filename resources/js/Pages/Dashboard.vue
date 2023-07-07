<template>
    <InertiaHead title="Overview" />

    <AuthenticatedLayout>

        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>
        </template>
        <superadmin-dashboard v-if="user.role.name == 'superadmin'"></superadmin-dashboard>
        <companyadmin-dashboard v-if="user.role.name == 'businessadmin' || user.role.name == 'company'"></companyadmin-dashboard>
        <member-dashboard v-if="user.role.name == 'employee'"></member-dashboard>
    </AuthenticatedLayout>
</template>
<script>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

import {
    ChevronRightIcon
} from '@heroicons/vue/24/solid'
import { mapStores } from 'pinia'
import { useAppStore } from '@/store'
import GeneralMixin from "@/Mixins/GeneralMixin";
import SuperadminDashboard from '@/Components/Dashboard/SuperAdminDashboard.vue'
import CompanyadminDashboard from '@/Components/Dashboard/CompanyAdminDashboard.vue'
import MemberDashboard from '@/Components/Dashboard/MemberDashboard.vue'
export default {
    mixins: [GeneralMixin],
    props: ["user"],
    created() {
        this.appStore.userLogin(this.user);
    },
    components: {
        ChevronRightIcon,
        AuthenticatedLayout,
         
        SuperadminDashboard,
        CompanyadminDashboard,
        MemberDashboard
    },
    computed: {
        ...mapStores(useAppStore),
    },
};
</script>


