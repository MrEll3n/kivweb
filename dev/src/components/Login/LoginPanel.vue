<script setup lang="ts">

import LoginUserInput from "@/components/Inputs/LoginUserInput.vue";
import LoginPassInput from "@/components/Inputs/LoginPassInput.vue";
import LoginSubmitInput from "@/components/Inputs/LoginSubmitInput.vue";
import LoginTextLogo from "@/assets/icons/LegoTextLogo.vue"

import { useLocalStorage } from "@vueuse/core";
import axios from "axios";
import {ref} from "vue";
import router from "@/router";




const user_email = ref("");
const user_password = ref("");
const urlBackend = "http://localhost:8080/kivweb/backend/api/index.php/login";

const isBadLogin = ref(false);
const isError = ref(false);

//let data = ref(null);

function sendPostReq() {
    //console.log([user_email.value, user_password.value])
    
    axios({
        method: 'post',
        url: urlBackend,
        data: {
            user_email: user_email.value,
            user_password: user_password.value
        }
}).then(response => {
    //console.log(response.data);
    //data.value = response.data
    //console.log(response.status);
    if (response.data.status == 401) { // wrong email or password
        isError.value = false;
        isBadLogin.value = true;
        //console.log(response.data.status);
    } else if (response.data.status == 400) { // something went wrong
        isBadLogin.value = false;
        isError.value = true;
        //console.log(response.data.status);
    } else if (response.data.status == 200) { // login successful
        isBadLogin.value = false;
        isError.value = false;
        //console.log(response.headers["authorization"]);
        if (localStorage.getItem('authorization') != null) {
            localStorage.removeItem('authorization');
        }
        localStorage.setItem('authorization', response.headers["authorization"]);
        
        router.push({name: 'Dashboard'})
    }
});
}

</script>

<template>
    <div class="relative flex flex-col gap-16 w-[42rem] h-full md:h-[32rem] border-0 md:border dark:border-neutral-300 border-neutral-800 dark:bg-black bg-gray-50 p-8 rounded-2xl z-10">
        <h1 class="hidden md:block absolute font-dosis-bold text-5xl dark:text-neutral-100 text-neutral-800 top-8">Login</h1>
        <LoginTextLogo class="mx-auto mt-2">[CW]</LoginTextLogo>
        <form id="loginForm" class="flex flex-col my-auto md:m-0">
            <h1 class="md:hidden font-dosis-bold text-5xl dark:text-neutral-100 text-neutral-800 mb-14 mx-auto">Login</h1>
            <LoginUserInput v-model="user_email" label="Email" class="relative flex flex-col mx-auto mb-14" />
            <LoginPassInput v-model="user_password" label="Password" class="relative flex flex-col mx-auto" />
            <span class="font-dosis-regular dark:text-neutral-100 text-neutral-800 ml-auto mr-40 mt-2">
                Don't have an account? <router-link to="/register">
                <span class="font-dosis-bold dark:text-neutral-100 text-neutral-800">Sign In</span>
            </router-link>.</span>
            <LoginSubmitInput @clicker="sendPostReq" class="flex items-center mt-10 mx-auto">Log In</LoginSubmitInput>
            <span class="absolute bottom-3 right-4 font-dosis-bold text-red-500" :class="{ hidden: !isBadLogin }">Login failed - wrong email or passoword</span>
            <span class="absolute bottom-3 right-4 font-dosis-bold text-red-500" :class="{ hidden: !isError }">Something went wrong</span>
        </form>
    </div>
</template>
