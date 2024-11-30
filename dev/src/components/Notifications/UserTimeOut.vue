<script setup lang="ts">
    import { onMounted } from 'vue';
    import Button from "@/components/Inputs/Button.vue";
    import router from '@/router';

    const isSessionExpired = defineModel();
    const scroll = window.scrollY;

    

    function closeWindow() {
        isSessionExpired.value = false;
        localStorage.setItem('isUserLogged', 'false');
        localStorage.setItem('isSessionExpired', 'false');
        document.body.style.overflow = 'auto';
        router.push({name: 'Login'});
    }

</script>

<template>
    <div class="absolute flex justify-center items-center top-[100] h-screen w-screen">
        <div class="h-full w-full dark:bg-neutral-800 bg-gray-50 opacity-80 backdrop-filter backdrop-blur-lg z-40">
        
        </div>
        <div class="absolute w-96 h-72 border dark:border-neutral-300 border-neutral-800 dark:bg-black bg-gray-50 z-50 rounded">
            <div class="flex flex-col justify-center items-center h-full gap-16">
                <h1 class="text-6xl font-dosis-regular dark:text-neutral-100 text-neutral-800">Session expired</h1>
                <Button @click="closeWindow"><span class="text-4xl font-dosis-regular dark:text-neutral-100 text-neutral-800"> ->   OK   <- </span></Button>
            </div>
            
        </div>
        <div class="absolute w-96 h-72 bg-black dark:bg-neutral-700 z-40 rounded ml-10 mt-10 opacity-90"></div>
    </div>
</template>
