<script setup lang="ts">
    import { ref } from "vue";
    import { useAuthStore } from "@/stores/auth.store";
    import Input from "@/components/Inputs/Input.vue";
    import TextArea from "@/components/Inputs/TextArea.vue";
    import SubmitInput from "@/components/Inputs/SubmitInput.vue";
    import { updateUser } from "@/utils/rest-api";

    // User profile data (name, email, bio)
    const userName = ref(useAuthStore().userData?.user_name || "");
    const userEmail = ref(useAuthStore().userData?.user_email || "");
    const userPasword = ref("");
    const isUpdateSuccess = ref(false);
    const cannotUpdate = ref(false);

    // Function to handle form submission
    async function eventProfileUpdate() {
        // Update user profile data
        const result = await updateUser(
            Number(useAuthStore().userData?.user_id),
            userName.value,
            userEmail.value,
            userPasword.value
        );

        if (result) {
            isUpdateSuccess.value = true;
        } else {
            cannotUpdate.value = true;
        }
    }
</script>

<template>
    <div class="flex flex-col justify-center items-center py-14 w-full h-full dark:bg-black bg-orange-50">
        <div class="relative flex flex-col items-center gap-10 border dark:border-neutral-300 border-neutral-800 dark:bg-black bg-gray-50 w-10/12 px-8 pt-10 min-h-screen rounded">
            <div class="flex flex-row justify-between w-full">
                <h1 class="font-dosis-bold dark:text-neutral-100 text-neutral-800 text-5xl md:mr-auto pl-4">Your Account</h1>
            </div>

            <div v-if="!isUpdateSuccess" class="flex flex-col justify-center items-center w-full p-10 gap-10">
                <!-- Name Input -->
                <div class="flex flex-col justify-center gap-2">
                    <label for="name" class="font-dosis-bold text-2xl dark:text-neutral-100 text-neutral-800">Name</label>
                    <Input v-model="userName" id="name" type="text" />
                </div>

                <!-- Email Input -->
                <div class="flex flex-col justify-center gap-2">
                    <label for="email" class="font-dosis-bold text-2xl dark:text-neutral-100 text-neutral-800">Email</label>
                    <Input v-model="userEmail" id="email" type="email" />
                </div>

                <!-- Email Input -->
                <div class="flex flex-col justify-center gap-2">
                    <label for="password" class="font-dosis-bold text-2xl dark:text-neutral-100 text-neutral-800">Password</label>
                    <Input v-model="userPasword" id="password" type="password" />
                </div>

                <!-- Submit Button -->
                <div class="flex w-full justify-center">
                    <SubmitInput @click="eventProfileUpdate">Update info</SubmitInput>
                </div>
            </div>
            <div v-if="isUpdateSuccess" class="flex flex-col justify-center items-center w-full p-10">
                <h1 class="font-dosis-bold text-4xl dark:text-neutral-100 text-neutral-800">Info succesfully updated!</h1>
            </div>
            <div v-if="cannotUpdate" class="flex flex-col justify-end items-end w-full p-10">
                <h1 class="font-dosis-bold text-xl text-red-500">Cannot update info!</h1>
            </div>
        </div>
    </div>
</template>
