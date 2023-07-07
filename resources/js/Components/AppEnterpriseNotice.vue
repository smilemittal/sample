<template>
    <div class="bg-white">
        <div class="bg-yellow-100 border-l-4 border-yellow-600 p-4">
        <div class="flex">
            <div class="flex-shrink-0">            
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-6 text-yellow-900" fill="none" viewBox="0 0 24 22" stroke="currentColor" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
            </svg>            
            </div>
            <div class="ml-3">
            <p class="text-sm text-yellow-900">
                {{ trans('lang.labels.app_enterprise_notice') }} <a :href="`mailto:support@instawp.io?subject=InstaWP Enterprise Query&body=Hi, I want to know more about InstaWP Enterprise plans.`" class="font-medium underline text-yellow-900 hover:text-yellow-600">{{ trans('lang.labels.contact_us') }}</a>
            </p>
            </div>
        </div>
        </div>        
    </div>
</template>
<style scoped>
</style>
<script>
export default {}
</script>