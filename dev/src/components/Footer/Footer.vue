<script setup lang="ts">
    const currentYear = new Date().getFullYear();
</script>

<template>
    <footer class="flex flex-col w-full h-auto top-full justify-center items-center p-4 mb-20 md:mb-0 border-t dark:border-neutral-300 border-neutral-800 dark:bg-black bg-gray-50">
        <div class="flex flex-col justify-center items-center space-y-2">
            <span class="font-dosis-regular dark:text-neutral-100 text-neutral-800 text-xl">© {{currentYear}} <a href="/">Vít Novotný.</a></span>
        </div>
    </footer>
</template>

<style scoped>

</style>
