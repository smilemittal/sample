<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

</script>

<template>
    <InertiaHead title="Dashboard" />

    <AuthenticatedLayout>

        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Activity Logs</h2>
        </template>

    </AuthenticatedLayout>
</template>
<script>
import AssestAddedToGroup from '@/Components/Notification/AssestAddedToGroup.vue';
import { TrashIcon, DocumentArrowUpIcon, EyeIcon } from "@heroicons/vue/24/solid";
export default {
        
    components: {
        AuthenticatedLayout,
        
        TrashIcon,
        DocumentArrowUpIcon,
        EyeIcon,
        AssestAddedToGroup
    },
    props: {
        user: {
            type: Object
        }
    },
    data() {
        return {
            logs:[],
            userRole: '',
        };
    },
    methods: {
        activityLogs() {
            axios.get('/api/notifications-list')
                .then((response) => {
                    if(response.data.status == 200) {
                        this.logs = response.data.data;
                    }
                    // this.setPagination(response)
                })
                .catch((error) => {

                })
                .finally(() => {

                });
        },
    },
    created(){
        this.activityLogs();
        this.userRole = this.user.role.name;

    }
};
</script>
