<script setup lang="ts">
    const value = defineModel<string>();
</script>

<template>
    <input v-model="value" type="text" class="font-dosis-regular dark:text-neutral-100 text-neutral-800 text-2xl z-10 border peer dark:border-neutral-300 border-neutral-800 rounded w-80 h-12 p-4 bg-transparent focus:box-shadow-none focus:outline focus:outline-1 focus:outline-neutral-900 dark:focus:outline-neutral-100 focus:caret-neutral-800 dark:focus:caret-neutral-100">
</template>