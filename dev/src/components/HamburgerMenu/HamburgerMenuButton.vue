<script setup lang="ts">
    import ThreeLinesIcon from "@/assets/icons/three-lines-icon.vue";
    import XIcon from "@/assets/icons/x-icon.vue";

    defineProps({
        isDropdownOpen: Boolean
    });

</script>

<template>
    <button @click="$emit('toggleMenu')" class="flex items-center justify-center">
        <three-lines-icon v-show="!isDropdownOpen" class="dark:fill-neutral-100 fill-neutral-800 dark:stroke-neutral-100 stroke-neutral-800 hover:fill-red-500 hover:stroke-red-500"  />
        <x-icon v-show="isDropdownOpen" class="dark:fill-neutral-300 fill-neutral-800 dark:stroke-neutral-300 stroke-neutral-800 hover:fill-red-400 hover:stroke-red-500" />
    </button>
</template>

<style scoped>

</style>
