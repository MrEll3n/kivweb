<script setup lang="ts">
    import { ref, onMounted } from "vue";
    import { useArticleStore } from "@/stores/article.store";
    import { useAuthStore } from "@/stores/auth.store";
    import MonoButton from "./Inputs/MonoButton.vue";
    import { deleteUser, getAllPerms, getAllUsers } from "@/utils/rest-api";
    import type { Perm, UserData } from "@/types";
import router from "@/router";

    interface MergedUserData {
        user_id: number;
        user_name: string;
        user_email: string;
        perm_id: number;
        perm_name: string;
    }

    const isUserLogged = localStorage.getItem("isUserLogged") === "true";
    const areUsersLoaded = ref(false);
    const arePermsLoaded = ref(false);

    // Fetching user data
    let users = [] as UserData[];
    const fetchedUsers = await getAllUsers();
    users = fetchedUsers !== null ? fetchedUsers : [];
    
    // Fetching permissions data
    let perms = [] as Perm[];
    const fetchedPerms = await getAllPerms();
    perms = fetchedPerms !== null ? fetchedPerms : [];
    
    // Merging user and permissions data
    const mergedData = ref<MergedUserData[]>([]);

    if (users === null) {
        console.error("Failed to fetch users!");
    } else {
        areUsersLoaded.value = true;
    }

    if (perms === null) {
        console.error("Failed to fetch permissions!");
    } else {
        arePermsLoaded.value = true;
    }
    
    console.log(users);
    console.log(perms)
    for (const user of users) {
        for (const perm of perms) {
            if (user.perm_id == perm.perm_id) {
                mergedData.value.push({
                    user_id: user.user_id,
                    user_name: user.user_name,
                    user_email: user.user_email,
                    perm_id: perm.perm_id,
                    perm_name: perm.perm_name
                });
            }
        }
    }

    async function eventDeleteUser(user_id: number) {

        const result = await deleteUser(user_id);
        if (result) {
            console.log("Delete user " + user_id);
        }
        router.go(0);
    }

</script>

<template>
    <div class="flex flex-col justify-center items-center py-14 w-full h-full dark:bg-black bg-orange-50">
        <div class="relative flex flex-col items-center gap-10 border dark:border-neutral-300 border-neutral-800 dark:bg-black bg-gray-50 w-10/12 px-8 py-10 min-h-screen rounded">
            <div class="flex flex-row justify-between w-full">
                <h1 class="font-dosis-bold dark:text-neutral-100 text-neutral-800 text-6xl md:mr-auto pl-4">
                    Dashboard
                </h1>
            </div>
            <div class="font-dosis-regular dark:text-neutral-100 text-neutral-800 overflow-hidden overflow-x-auto w-full rounded-md divide-gray-200 dark:divide-neutral-700 border dark:border-neutral-300 border-neutral-800 dark:bg-black bg-gray-50">
                <table class="min-w-full divide-y">
                    <thead class="bg-gray-100 dark:bg-neutral-900 rounded-t-md font-dosis-bold">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-lg font-medium text-gray-500 dark:text-neutral-300 uppercase tracking-wider"> <!-- Zvětšeno na text-lg -->
                                ID
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-lg font-medium text-gray-500 dark:text-neutral-300 uppercase tracking-wider"> <!-- Zvětšeno na text-lg -->
                                Name
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-lg font-medium text-gray-500 dark:text-neutral-300 uppercase tracking-wider"> <!-- Zvětšeno na text-lg -->
                                Email
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-lg font-medium text-gray-500 dark:text-neutral-300 uppercase tracking-wider"> <!-- Zvětšeno na text-lg -->
                                Role
                            </th>
                            <th scope="col" class="pl-6 py-3 text-left text-lg font-medium text-gray-500 dark:text-neutral-300 uppercase"> <!-- Zvětšeno na text-lg -->
                            </th>
                            <th scope="col" class="pr-6 py-3 text-left text-lg font-medium text-gray-500 dark:text-neutral-300 uppercase"> <!-- Zvětšeno na text-lg -->
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-black divide-y divide-gray-200 dark:divide-neutral-700 rounded-b-md">
                        <tr v-for="user in mergedData" :key="user.user_id" class="dark:hover:bg-neutral-900 hover:bg-neutral-50">
                            <td class="px-6 py-4 whitespace-nowrap text-lg font-medium text-gray-900 dark:text-white">
                                {{ user.user_id }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-500 dark:text-neutral-300">
                                {{ user.user_name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-500 dark:text-neutral-300">
                                {{ user.user_email }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-500 dark:text-neutral-300">
                                {{ user.perm_name }}
                            </td>
                            <td class="pl-6 pr-2 py-4 w-14 whitespace-nowrap text-lg text-gray-500 dark:text-neutral-300">
                                <MonoButton class="">Update</MonoButton>
                            </td>
                            <td class="pr-6 py-4 w-14 whitespace-nowrap text-lg text-gray-500 dark:text-neutral-300">
                                <MonoButton @click="eventDeleteUser(user.user_id)">Delete</MonoButton>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>



