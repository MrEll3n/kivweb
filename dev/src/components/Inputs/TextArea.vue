<script setup lang="ts">

    const props = defineProps<{
        rows: string,
        cols: string
    }>()

    const value = defineModel<string>();

</script>


<template>
    <textarea v-model="value" type="text" :rows="props.rows as string" :cols="props.cols as string" class="font-dosis-regular dark:text-neutral-100 text-neutral-800 text-2xl z-10 border peer dark:border-neutral-300 border-neutral-800 rounded p-4 focus:box-shadow-none focus:outline focus:outline-1 focus:outline-neutral-900 dark:focus:outline-neutral-100 focus:caret-neutral-800 dark:focus:caret-neutral-100 bg-transparent"></textarea>
</template>