<script setup lang="ts">
//const props = defineProps({
//    link: String
//});
</script>

<template>
    <div>
        <div class="border border-black dark:border-neutral-300 rounded p-1 dark:text-neutral-100 text-neutral-800 hover:bg-neutral-900 hover:text-gray-50 dark:hover:bg-neutral-100 dark:hover:text-neutral-900 transition">
            <button @click="$emit('clicker')" type="button" class="flex justify-center items-center gap-1 font-dosis-regular text-2xl px-2"><slot /></button>
        </div>
    </div>
</template>

<style scoped>

</style>
