<script setup lang="ts">
    const props = defineProps({
        link: String
    });
</script>

<template>
    <div class="flex items-center">
        <div>
            <RouterLink :to="`${props.link}`" class="flex gap-1 font-teko-bold text-3xl dark:text-neutral-100 text-neutral-800 px-2 transition "><slot class="hover:fill-red-500" /></RouterLink>
        </div>
    </div>
</template>

<style scoped>

</style>
