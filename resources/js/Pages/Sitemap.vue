<template>
    <div>

        <InertiaHead title="Learning Path" />
        <AuthenticatedLayout>
            <div class="mb-4">
                <div class="flex items-center flex-wrap">
                    <div class="page-header">
                        <h4 class="text-4xl text-neutral-300 font-bold">Sitemap</h4>
                    </div>
                </div>
            </div>
            <div class="h-full drop-shadow-md rounded-3xl mt-5">
                <div class="grid lg:grid-cols-3 md:grid-cols-2 sm:grid-cols-1 gap-5">
                    <div v-for="sitemap in sitemaps" :key="sitemap.id" class="rounded-lg bg-cardtop p-4">
                        <div class="">
                            <h6><a class="text-2xl font-semibold text-neutral-400 hover:text-amber-500" :href="sitemap.url">{{ sitemap.page_name }}</a></h6>
                            <p class="text-base text-neutral-500 mt-2">{{ sitemap.page_path }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    </div>
</template>
<script>

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
export default {
    components: {
        AuthenticatedLayout,
        
    },
    data() {
        return {
            sitemaps:[
                {'page_name':'Manage Companies', 'url': '/manage-companies', 'page_path': app.domain +'/manage-companies'},
                {'page_name':'Manage Members', 'url': '/manage-users', 'page_path':'http://multiplymenew.tech:8001/manage-users'},
                {'page_name':'Xme Area', 'url': '/xme-area', 'page_path':'http://multiplymenew.tech:8001/xme-area'},
                {'page_name':'Manage Forms', 'url': 'design/manage-forms/', 'page_path':'http://multiplymenew.tech:8001/design/business-profile'},
                {'page_name':'Email Settings', 'url': 'design/email-settings/', 'page_path':'http://multiplymenew.tech:8001/design/email-settings'},
                {'page_name':'Email Template', 'url': 'design/email-template/', 'page_path':'http://multiplymenew.tech:8001/design/email-template'},
                {'page_name':'Email Edit', 'url': 'design/email-template/edit/', 'page_path':'http://multiplymenew.tech:8001/design/email-template/edit'},
                {'page_name':'Business Profile', 'url': 'design/business-profile/', 'page_path':'http://multiplymenew.tech:8001/design/business-profile'},
                {'page_name':'Profile', 'url': 'design/profile/', 'page_path':'http://multiplymenew.tech:8001/design/profile'},
                {'page_name':'Review Forms', 'url': 'design/review-forms/', 'page_path':'http://multiplymenew.tech:8001/design/review-forms'},
                {'page_name':'Review History', 'url': 'design/review-history/', 'page_path':'http://multiplymenew.tech:8001/design/review-history'},
                {'page_name':'Review Company Module', 'url': 'design/review-company-module/', 'page_path':'http://multiplymenew.tech:8001/design/review-company-module'},
                {'page_name':'Updated History', 'url': 'design/updated-history/', 'page_path':'http://multiplymenew.tech:8001/design/updated-history'},
                {'page_name':'View Status', 'url': 'design/view-status/', 'page_path':'http://multiplymenew.tech:8001/design/view-status'},
                {'page_name':'My Trainings', 'url': 'design/my-trainings/', 'page_path':'http://multiplymenew.tech:8001/design/my-trainings'},
                {'page_name':'Identify', 'url': 'design/identify/', 'page_path':'http://multiplymenew.tech:8001/design/identify'},
                {'page_name':'Identify New', 'url': 'design/identify/new/', 'page_path':'http://multiplymenew.tech:8001/design/identify/new'},
                {'page_name':'Identify Edit', 'url': 'design/identify/edit/', 'page_path':'http://multiplymenew.tech:8001/design/identify/edit/'},
                {'page_name':'Identify Edit', 'url': 'design/identify/edit/', 'page_path':'http://multiplymenew.tech:8001/design/identify/edit/'},
                {'page_name':'Identify Edit', 'url': 'design/identify/edit/', 'page_path':'http://multiplymenew.tech:8001/design/identify/edit/'}
            ]
        }
    },
    methods: {

    },
    computed: {},
    watch: {
    },
}
</script>
