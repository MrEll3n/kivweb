<script setup lang="ts">
    import {useDark, useToggle} from "@vueuse/core";
    import SunIcon from "@/assets/icons/sun-icon.vue";
    import MoonIcon from "@/assets/icons/moon-icon.vue";

    const isDark = useDark();
    const toggleDark = useToggle(isDark);
</script>

<template>
    <div class="flex justify-center items-center">
        <button @click="toggleDark()" class="flex items-center justify-center">
            <sun-icon v-if="isDark" class="dark:fill-neutral-100 fill-neutral-800 dark:stroke-neutral-100 stroke-neutral-800 hover:fill-black hover:stroke-neutral-500 transition" />
            <moon-icon v-else-if="!isDark" class="dark:fill-neutral-300 fill-neutral-800 dark:stroke-neutral-300 stroke-neutral-800 hover:fill-gray-50 hover:stroke-neutral-600 transition" />
        </button>
    </div>

</template>

<style scoped>

</style>
