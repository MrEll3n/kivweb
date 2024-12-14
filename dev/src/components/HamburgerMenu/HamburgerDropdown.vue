<script setup lang="ts">

import NavLink from "@/components/Navbar/NavLink.vue";
import ThemeSwitcher from "@/components/ThemeSwitcher/ThemeSwitcher.vue";
import AccountIcon from "@/assets/icons/account-icon.vue";
import NavLinkBorder from "@/components/Navbar/NavLinkBorder.vue";
import HamburgerMenuButton from "@/components/HamburgerMenu/HamburgerMenuButton.vue";
import NavTextLogo from "@/components/Navbar/NavTextLogo.vue";
import PlusIcon from "@/assets/icons/plus-icon.vue";
import Button from "@/components/Inputs/Button.vue";
import PowerIcon from "@/assets/icons/power-icon.vue";
import { useAuthStore } from "@/stores/auth.store";
import { ref } from "vue";
import { getLoginStatus, sessionManager } from "@/utils/utils";
import router from "@/router";

const authStore = useAuthStore();
const currentUserPermWeight = ref(0);

const isUserLogged = ref(getLoginStatus());
currentUserPermWeight.value = authStore.userPerm?.perm_weight ?? 0;

async function logout() {
        await sessionManager().logout();
        isUserLogged.value = false;
        router.push({name: 'Login'});
    }

</script>

<template>
    <nav class="sticky top-0 w-full dark:bg-black bg-gray-50 border-b dark:border-neutral-300 border-neutral-800">
        <div class="flex flex-row gap-6 justify-center items-center">
            <div class="absolute flex flex-row gap-6 left-0 top-0 my-6 pl-14">
                <NavLinkBorder v-if="isUserLogged" link="/newPost">
                    <plus-icon />
                </NavLinkBorder>
                <ThemeSwitcher />
            </div>
            <div>
                <div v-if="isUserLogged" class="flex flex-col gap-6 py-6">
                        <NavLink link="/news?page=1">
                            News
                        </NavLink>
                        <NavLink v-if="(currentUserPermWeight >= 2)" link="/reviews?page=1">
                            Reviews
                        </NavLink>
                        <NavLink v-if="(currentUserPermWeight >= 3)" link="/moderation?page=1">
                            Moderation
                        </NavLink>
                        <NavLink v-if="(currentUserPermWeight >= 4)" link="/dashboard">
                            Dashboard
                        </NavLink>
                    </div>
                <div v-if="!isUserLogged" id="NavListLong" class="mx-24">
                    <div class="h-24"></div>
                </div>
            </div>
            <div class="absolute flex flex-row gap-6 right-0 top-0 my-6 pr-14">
                <NavLinkBorder v-if="!isUserLogged" link="/login">
                    <account-icon />
                    Log In
                </NavLinkBorder>
                <NavLink v-if="isUserLogged" link="/profile">
                    <account-icon />
                    {{ authStore.userData?.user_name }}
                </NavLink>
                <Button v-if="isUserLogged" @click="logout">
                        <power-icon />
                </Button>
            </div>
        </div>
    </nav>
</template>

<style scoped>

</style>
