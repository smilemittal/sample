<template>
    <template v-for="option in options">
        <option :value="option.id">{{ ('-  ').repeat(level ?? 1) + option.name }}</option>
        <select-option :options="option.children" :level="(level ?? 1)+1"></select-option>
    </template>
</template>
<script>
export default {
    name: 'SelectOption',
    props: ['options', 'level'],
}
</script>
