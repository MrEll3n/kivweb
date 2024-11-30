<script setup lang="ts">

    import LoginUserInput from "@/components/Inputs/LoginUserInput.vue";
    import LoginPassInput from "@/components/Inputs/LoginPassInput.vue";
    import SubmitInput from "@/components/Inputs/SubmitInput.vue";
    import LoginTextLogo from "@/assets/icons/LegoTextLogo.vue";

    import { ref } from "vue";
    import router from "@/router";
    import { useAuthStore } from "@/stores/auth.store";
    import { login } from "@/utils/rest-api";

    const authStore = useAuthStore();


    const user_email = ref<string>("");
    const user_password = ref<string>("");

    const isBadLogin = ref(false);


    
    async function eventLogin() {
        const result = await login(user_email.value, user_password.value);
        if(result != null) {
            authStore.userData = result;
            sessionStorage.setItem('userData', JSON.stringify(result));
            isBadLogin.value = false;
            localStorage.setItem('isUserLogged', 'true');
            router.replace({name: "News", query: {page: 1}});
            //router.go(0);
        } else {
            isBadLogin.value = true;
        }
    }


</script>

<template>
    <div class="relative flex flex-col gap-16 w-[42rem] h-full md:h-[32rem] border-0 md:border dark:border-neutral-300 border-neutral-800 dark:bg-black bg-gray-50 p-8 rounded-2xl z-10">
        <h1 class="hidden md:block absolute font-dosis-bold text-5xl dark:text-neutral-100 text-neutral-800 top-8">Login</h1>
        <LoginTextLogo class="mx-auto mt-2">[CW]</LoginTextLogo>
        <form @submit.prevent="eventLogin" id="loginForm" class="flex flex-col my-auto md:m-0">
            <h1 class="md:hidden font-dosis-bold text-5xl dark:text-neutral-100 text-neutral-800 mb-14 mx-auto">Login</h1>
            <LoginUserInput v-model="user_email" label="Email" class="relative flex flex-col mx-auto mb-14" />
            <LoginPassInput v-model="user_password" label="Password" class="relative flex flex-col mx-auto" />
            <span class="font-dosis-regular dark:text-neutral-100 text-neutral-800 ml-auto mr-40 mt-2">
                Don't have an account? <router-link to="/register">
                <span class="font-dosis-bold dark:text-neutral-100 text-neutral-800">Sign In</span>
            </router-link>.</span>
            <SubmitInput @clicker="eventLogin" class="flex items-center mt-10 mx-auto">Log In</SubmitInput>
            <span class="absolute bottom-3 right-4 font-dosis-bold text-red-500" :class="{ hidden: !isBadLogin }">Login failed - wrong email or passoword</span>
            <span class="absolute bottom-3 right-4 font-dosis-bold text-red-500" :class="{ hidden: !authStore.authError }">Something went wrong</span>
        </form>
    </div>
</template>
