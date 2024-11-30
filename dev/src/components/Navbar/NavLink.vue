<script setup lang="ts">
    const props = defineProps({
        link: String
    });
</script>

<template>
    <div class="flex items-center justify-center">
        <div>
            <RouterLink :to="`${props.link}`" class="flex justify-center items-center gap-1 font-dosis-regular text-2xl dark:text-neutral-100 text-neutral-800 px-2 hover:bg-gray-200 dark:hover:bg-neutral-800 rounded transition "><slot /></RouterLink>
        </div>
    </div>
</template>

