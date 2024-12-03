<script setup lang="ts">
    import NavLink from "@/components/Navbar/NavLink.vue";
    //const NavLink = () => import('@/components/Navbar/NavLink.vue');
    import AccountIcon from "@/assets/icons/account-icon.vue";
    //const AccountIcon = () => import('@/assets/icons/account-icon.vue');
    import NavTextLogo from "@/components/Navbar/NavTextLogo.vue";
    //const NavTextLogo = () => import('@/components/Navbar/NavTextLogo.vue');
    import NavLinkBorder from "@/components/Navbar/NavLinkBorder.vue";
    //const NavLinkBorder = () => import('@/components/Navbar/NavLinkBorder.vue');
    import ThemeSwitcher from "@/components/ThemeSwitcher/ThemeSwitcher.vue";
    //const ThemeSwitcher = () => import('@/components/ThemeSwitcher/ThemeSwitcher.vue');
    import Button from "@/components/Inputs/Button.vue";
    //const Button = () => import('@/components/Inputs/Button.vue');
    import NavAccountLink from "@/components/Navbar/NavAccountLink.vue";
    //const NavAccountLink = () => import('@/components/Navbar/NavAccountLink.vue');

    import HomeIcon from "@/assets/icons/home-icon.vue";
    import DashboardIcon from "@/assets/icons/dashboard-icon.vue";
    import PowerIcon from "@/assets/icons/power-icon.vue";
    import PlusIcon from "@/assets/icons/plus-icon.vue";

    import HamburgerMenuButton from "@/components/HamburgerMenu/HamburgerMenuButton.vue";
    import HamburgerDropdown from "@/components/HamburgerMenu/HamburgerDropdown.vue";

    import {ref} from "vue";
    import { useAuthStore } from "@/stores/auth.store";
    import { sessionManager, getLoginStatus, isPermStored, isUserStored } from "@/utils/utils";
    import router from "@/router";

    // Setting up the storage
    const authStore = useAuthStore();
    const isUserLogged = ref(getLoginStatus());
    const currentUserPermWeight = ref(0);
    const isDropdownOpen = ref(false);

    //console.log("Perms: " + authStore.userPerm);
    //console.log("User: " + authStore.userData);


    currentUserPermWeight.value = authStore.userPerm?.perm_weight ?? 0;
    

    //const currentUserPerm = await getCurrentUserPerm();

    function toggleDropdown() {
        isDropdownOpen.value = !isDropdownOpen.value;
    }

    async function logout() {
        await sessionManager().logout();
        isUserLogged.value = false;
        router.push({name: 'Login'});
    }

</script>

<template>
    <!-- Standard -->
    <nav class="hidden lg:block sticky top-0 w-full z-20 dark:bg-black bg-gray-50"> <!-- bg-white -->
        <!--  Main navigation container -->
        <div id="bg" class="relative h-20 transition border-b dark:border-neutral-300 border-neutral-800"> <!-- border-b  -->
            <div class="relative flex flex-row gap-6 h-20 justify-center">
                <div class="absolute flex flex-row gap-6 h-20 left-0 items-center pl-14" >
                    <NavTextLogo link="/">[CW]</NavTextLogo>
                    <NavLinkBorder v-if="isUserLogged" link="/newpost">
                        <plus-icon />
                    </NavLinkBorder>
                </div>
                <div>
                    <div v-if="isUserLogged" class="flex flex-row gap-6 h-20">
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
                </div>
                <div class="absolute flex flex-row gap-6 h-20 right-0 pr-14">
                    <NavLinkBorder v-if="!isUserLogged" link="/login">
                        <account-icon />
                        Log In
                    </NavLinkBorder>
                    <ThemeSwitcher />
                    <NavAccountLink v-if="isUserLogged" link="/profile">
                        <template v-slot:Icon><account-icon/></template>
                        <template v-slot:Name>
                            <div class="flex flex-col flex-shrink-0 items-center">
                                <span>{{ authStore.userData?.user_name ?? 'Name' }}</span>
                                <span class="font-dosis-regular text-sm">{{ authStore.userPerm?.perm_name ?? "Role" }}</span>
                            </div>
                        </template>
                    </NavAccountLink>
                    <Button v-if="isUserLogged" @click="logout">
                        <power-icon />
                    </Button>
                </div>
            </div>
        </div>
    </nav>

    <!-- Tablet -->
    <nav class="hidden md:block lg:hidden sticky top-0 w-full z-20 dark:bg-black bg-gray-50"> <!-- bg-white -->
        <!--  Main navigation container -->
        <div id="bg" class="relative h-20 transition border-b dark:border-neutral-300 border-neutral-800"> <!-- border-b  -->
            <div class="flex flex-row gap-6 h-20 justify-center">
                <div>
                    <div id="NavListLong" class="flex flex-row gap-6 h-20">
                        <NavTextLogo link="/">[CW]</NavTextLogo>
                    </div>
                </div>
                <div class="absolute flex flex-row gap-6 h-20 right-0 pr-14">
                    <HamburgerMenuButton @toggle-menu="toggleDropdown" :isDropdownOpen="isDropdownOpen" />
                </div>
            </div>
        </div>
        <HamburgerDropdown v-if="isDropdownOpen" />
    </nav>

    <!-- Mobile View -->
    <nav class="fixed md:hidden bottom-0 w-full z-20 dark:bg-black bg-gray-50"> <!-- bg-white -->
        <!--  Main navigation container -->
        <div id="bg" class="relative h-20 transition border-t dark:border-neutral-300 border-neutral-800"> <!-- border-b  -->
            <div class="flex flex-row gap-6 h-20 justify-center items-center">
                <div class="absolute flex flex-row space-x-6 h-20 left-0 items-center pl-7" >
                    <ThemeSwitcher/>
                </div>
                <div class="flex flex-row space-x-6 h-20">
                    <NavLink v-if="isUserLogged" link="/news?page=1">
                        <home-icon />
                    </NavLink>
                    <NavLinkBorder v-if="isUserLogged" link="/newpost">
                        <plus-icon />
                    </NavLinkBorder>
                    <NavLink v-if="isUserLogged" link="/dashboard">
                        <dashboard-icon />
                    </NavLink>
                </div>
                <div class="absolute flex flex-row space-x-6 h-20 right-0 pr-7">
                    <NavLink v-if="isUserLogged" link="/profile">
                        <account-icon />
                    </NavLink>
                    <NavLinkBorder v-if="!isUserLogged" link="/login">
                        <account-icon />
                        Log In
                    </NavLinkBorder>
                </div>
            </div>
        </div>
    </nav>


</template>
