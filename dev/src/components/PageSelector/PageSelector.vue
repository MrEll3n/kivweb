<script setup lang="ts">

import ChevronRight from "@/assets/icons/chevron-right.vue";
import ChevronLeft from "@/assets/icons/chevron-left.vue";
</script>

<template>
    <div class="flex flex-row items-center bottom-0 dark:border-neutral-300 border-neutral-800 w-20 h-9 rounded">
        <button class="flex-none mx-2 p-1 hover:bg-gray-200 dark:hover:bg-neutral-800 rounded"><chevron-left class="dark:stroke-neutral-100 stroke-neutral-800" /></button>
        <div class="w-full"></div>
        <button class="flex-none mx-2 p-1 hover:bg-gray-200 dark:hover:bg-neutral-800 rounded"><chevron-right class="dark:stroke-neutral-100 stroke-neutral-800" /></button>
    </div>
</template>
