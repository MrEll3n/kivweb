<script setup lang="ts">
    import NavLink from "@/components/Navbar/NavLink.vue";
    import AccountIcon from "@/assets/icons/account-icon.vue";
    import NavTextLogo from "@/components/Navbar/NavTextLogo.vue";
    import NavLinkBorder from "@/components/Navbar/NavLinkBorder.vue";
    import ThemeSwitcher from "@/components/ThemeSwitcher/ThemeSwitcher.vue";
    import HomeIcon from "@/assets/icons/home-icon.vue";
    import HamburgerMenuButton from "@/components/HamburgerMenu/HamburgerMenuButton.vue";
    import HamburgerDropdown from "@/components/HamburgerMenu/HamburgerDropdown.vue";

    import {ref} from "vue";

    const isDropdownOpen = ref(false);

    const props = defineProps({

    })

    function toggleDropdown() {
        isDropdownOpen.value = !isDropdownOpen.value;
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
                </div>
                <div>
                    <div class="flex flex-row gap-6 h-20">

                    </div>
                </div>
                <div class="absolute flex flex-row gap-6 h-20 right-0 pr-14">
                    <NavLinkBorder link="/login">
                        <account-icon />
                        Log In
                    </NavLinkBorder>
                    <ThemeSwitcher />
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
                    <NavLink link="news">
                        <home-icon/>
                    </NavLink>
                </div>
                <div class="absolute flex flex-row space-x-6 h-20 right-0 pr-7">
                    <NavLinkBorder link="/login">
                        <account-icon />
                    </NavLinkBorder>
                </div>
            </div>
        </div>
    </nav>


</template>
