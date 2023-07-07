<template>
    <InertiaHead :title='trans("lang.labels.profile_settings")' />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
                {{ trans("lang.labels.profile") }}
            </h2>
        </template>

        <div class="mt-5">
            <div class="mx-auto rounded-lg pt-1 grid grid-cols-1 md:grid-cols-6 gap-6">
                <profile-nav-bar :activeTabName="activeTabName" />
                <div class="w-full col-span-4" v-if="
                    activeTabName == 'BusinessProfile' &&
                    $page.props.jetstream.canUpdateProfileInformation">
                    <component v-bind:is="activeTabName" :user="$page.props.user" :sessions="sessions"></component>
                </div>
                <div v-else-if="activeTabName == 'Subscription'">
                    <component v-bind:is="activeTabName" :user="$page.props.user"></component>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import ProfileNavBar from "@/Common/ProfileNavBar.vue";

import BusinessProfile from "@/Pages/BusinessProfile/Index.vue";

export default {
    props: ["sessions"],
    components: {
        AuthenticatedLayout,
        ProfileNavBar,
        BusinessProfile
    },
    data() {
        return {
            activeTabName: "BusinessProfile",
        };
    },
};
</script>
