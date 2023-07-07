<template>
    <div class="relative mt-6">
        <div class="bg-black theme-scrollbar footerLinks h-full rounded-t-xl pl-5 pr-12 py-6 text-end">
            <span class="text-gray-400 text-sm flex flex-wrap justify-end footerLinksSpan">
                <inertia-link :href="route('app.terms-conditions')" class="hover:text-amber-500">Terms and conditions</inertia-link> <span class="mx-2">|</span>
                <inertia-link :href="route('app.privacy-policy')" class="hover:text-amber-500">Privacy Policy</inertia-link>
            </span>
        </div>
    </div>
</template>

<script>

export default {
    name: 'Footer',
    components: {
        
    },
    data() {
        return {

        }
    },
}
</script>
