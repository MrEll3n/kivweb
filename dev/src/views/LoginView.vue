<script setup lang="ts">
    import LoginPanel from "@/components/Login/LoginPanel.vue";
    import ThemeSwitcher from "@/components/ThemeSwitcher/ThemeSwitcher.vue";
    const currentYear = new Date().getFullYear();
</script>

<template>
    <div class="relative flex flex-col justify-center items-center min-h-screen h-screen dark:bg-black bg-gray-50 md:bg-orange-50">
        <LoginPanel/>
        <ThemeSwitcher class="absolute right-4 top-4"/>
        <div class="absolute bottom-3 flex flex-col justify-center items-center z-10">
            <span class="font-dosis-regular dark:text-neutral-100 text-neutral-800 text-xl">© {{currentYear}} <a href="/">Vít Novotný.</a></span>
        </div>
    </div>
</template>
