<script setup lang="ts">

import LoginUserInput from "@/components/Inputs/LoginUserInput.vue";
import LoginPassInput from "@/components/Inputs/LoginPassInput.vue";
import LoginSubmitInput from "@/components/Inputs/LoginSubmitInput.vue";
import LoginTextLogo from "@/assets/icons/LegoTextLogo.vue"
import axios from "axios";
import {ref} from "vue";

const user_email = ref("")
const user_password = ref("")
const urlBackend = "http://localhost:8080/kivweb/backend/api/index.php/login"

let data = ref(null);

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
    data.value = response.data

    

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
            <LoginSubmitInput @click="sendPostReq" class="flex items-center mt-10 mx-auto">Log In</LoginSubmitInput>
        </form>
    </div>
</template>
