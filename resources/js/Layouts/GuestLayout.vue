<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';

</script>

<template>
    <div class="min-h-screen flex sm:justify-center items-center pt-6 sm:pt-0 bg-body overflow-y-auto">
        <div class="w-full sm:max-w-lg px-6 pb-4">
            <slot />
        </div>
    </div>

  <Notifications />

</template>
