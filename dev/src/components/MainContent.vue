<script setup lang="ts">

import ChevronLeft from "@/assets/icons/chevron-left.vue";
import ChevronRight from "@/assets/icons/chevron-right.vue";
import PageSelector from "@/components/PageSelector/PageSelector.vue";
</script>

<template>
        <div class="flex justify-center py-14 w-full h-full dark:bg-neutral-900 bg-orange-50">
            <div class="relative flex flex-col items-center gap-10 border dark:border-neutral-300 border-neutral-800 dark:bg-black bg-gray-50 w-10/12 px-8 pt-10 min-h-screen rounded">
                <h1 class="font-dosis-bold dark:text-neutral-100 text-neutral-800 text-5xl md:mr-auto pl-4">Articles</h1>
                <slot />
                <PageSelector class="mt-auto -mt-5 mb-5" />
            </div>
        </div>
</template>
