<script setup>
defineProps({
    type: {
        type: String,
        default: 'submit',
    },
});
</script>

<template>
    <button
        :type="type"
        class="text-sm bg-btnSubmitBg text-btnSubmitText px-6 py-2 rounded-lg"
    >
        <slot />
    </button>
</template>
