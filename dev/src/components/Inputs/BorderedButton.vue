<script setup lang="ts">
    const props = defineProps({
        link: String
    });
</script>

<template>
    <div class="flex items-center justify-center h-9 border dark:border-neutral-300 border-neutral-800 dark:bg-black bg-gray-50 hover:bg-gray-200 rounded">
        <div>
            <button class="flex justify-center items-center gap-1 font-dosis-regular text-2xl dark:text-neutral-100 text-neutral-800 px-2  dark:hover:bg-neutral-800 rounded transition "><slot /></button>
        </div>
    </div>
</template>

