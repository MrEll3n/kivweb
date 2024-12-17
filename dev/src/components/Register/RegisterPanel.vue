<script setup lang="ts">
    import { ref } from "vue";
    import axios from "axios";
    import router from "@/router";

    import LoginPassInput from "@/components/Inputs/LoginPassInput.vue";
    import LoginSubmitInput from "@/components/Inputs/SubmitInput.vue";
    import LoginUserInput from "@/components/Inputs/LoginUserInput.vue";
    import LoginTextLogo from "@/assets/icons/LegoTextLogo.vue";
    

    // Declare ref variables
    const user_name = ref("");
    const user_email = ref("");
    const user_password = ref("");
    const isBadLogin = ref(false);
    const isError = ref(false);

    const urlBackend = "http://localhost:8080/kivweb/backend/api/index.php/users/";

    // Function to handle the PUT request
    async function sendPostReq() {
        try {
            // Sending the PUT request
            const response = await axios.put(urlBackend, {
                user_name: user_name.value,
                user_email: user_email.value,
                user_password: user_password.value
            });

            // Handling different status codes in the response
            if (response.status === 201) { // Successful request
                isBadLogin.value = false;
                isError.value = false;
                router.push({ name: 'Login' });
            } else if (response.status === 400) { // Bad request
                isBadLogin.value = false;
                isError.value = true;
            } else if (response.status === 401) { // Unauthorized (bad email/password)
                isError.value = false;
                isBadLogin.value = true;
            }
        } catch (error) {
            // Catching errors in the request
            console.error("Error during the request:", error);
            isBadLogin.value = false;
            isError.value = true;
        }
    }

</script>

<template>
    <div class="relative flex flex-col gap-16 w-[42rem] h-full md:h-[40rem] border-0 md:border dark:border-neutral-300 border-neutral-800 dark:bg-black bg-gray-50 p-8 rounded-2xl z-10">
        <h1 class="hidden md:block absolute font-dosis-bold text-5xl dark:text-neutral-100 text-neutral-800 top-8">Sign In</h1>
        <LoginTextLogo class="mx-auto mt-2">[CW]</LoginTextLogo>
        <div class="flex flex-col my-auto md:m-0">
            <h1 class="md:hidden font-dosis-bold text-5xl dark:text-neutral-100 text-neutral-800 mb-14 mx-auto">Sign In</h1>
            <LoginUserInput v-model="user_name" label="Username" class="relative flex flex-col mx-auto mb-14" />
            <LoginUserInput v-model="user_email" label="Email" class="relative flex flex-col mx-auto mb-14" />
            <LoginPassInput v-model="user_password" label="Password" class="relative flex flex-col mx-auto" />
            <span class="font-dosis-regular dark:text-neutral-100 text-neutral-800 ml-auto mr-40 mt-2">
                Already have an account? <router-link to="/login"><span class="font-dosis-bold dark:text-neutral-100 text-neutral-800">Login
            </span></router-link>.</span>
            <LoginSubmitInput @clicker="sendPostReq" class="flex items-center mt-10 mx-auto">Sign In</LoginSubmitInput>
        </div>
    </div>
</template>

<style scoped>
</style>
