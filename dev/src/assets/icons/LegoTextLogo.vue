<script setup lang="ts">
    const props = defineProps({
        link: String
    });
</script>

<template>
    <div class="flex items-center">
        <div>
            <p class="flex font-teko-bold text-6xl dark:text-neutral-100 text-neutral-800 px-2 transition "><slot /></p>
        </div>
    </div>
</template>

<style scoped>

</style>
