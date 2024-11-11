<script setup lang="ts">

import LoginPassInput from "@/components/Inputs/LoginPassInput.vue";
import LoginSubmitInput from "@/components/Inputs/LoginSubmitInput.vue";
import LoginUserInput from "@/components/Inputs/LoginUserInput.vue";
import LoginTextLogo from "@/assets/icons/LegoTextLogo.vue";
</script>

<template>
    <div class="relative flex flex-col gap-16 w-[42rem] h-full md:h-[40rem] border-0 md:border dark:border-neutral-300 border-neutral-800 dark:bg-black bg-gray-50 p-8 rounded-2xl z-10">
        <h1 class="hidden md:block absolute font-dosis-bold text-5xl dark:text-neutral-100 text-neutral-800 top-8">Sign In</h1>
        <LoginTextLogo class="mx-auto mt-2">[CW]</LoginTextLogo>
        <div class="flex flex-col my-auto md:m-0">
            <h1 class="md:hidden font-dosis-bold text-5xl dark:text-neutral-100 text-neutral-800 mb-14 mx-auto">Sign In</h1>
            <LoginUserInput label="Username" class="relative flex flex-col mx-auto mb-14" />
            <LoginUserInput label="Email" class="relative flex flex-col mx-auto mb-14" />
            <LoginPassInput label="Password" class="relative flex flex-col mx-auto" />
            <span class="font-dosis-regular dark:text-neutral-100 text-neutral-800 ml-auto mr-40 mt-2">
                Already have an account? <router-link to="/login"><span class="font-dosis-bold dark:text-neutral-100 text-neutral-800">Login
            </span></router-link>.</span>
            <LoginSubmitInput link="/" class="flex items-center mt-10 mx-auto">Sign In</LoginSubmitInput>
        </div>
    </div>
</template>

<style scoped>

</style>
