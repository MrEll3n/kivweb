<script setup lang="ts">

import NavLink from "@/components/Navbar/NavLink.vue";
import ThemeSwitcher from "@/components/ThemeSwitcher/ThemeSwitcher.vue";
import AccountIcon from "@/assets/icons/account-icon.vue";
import NavLinkBorder from "@/components/Navbar/NavLinkBorder.vue";
import HamburgerMenuButton from "@/components/HamburgerMenu/HamburgerMenuButton.vue";
import NavTextLogo from "@/components/Navbar/NavTextLogo.vue";


</script>

<template>
    <nav class="sticky top-0 w-full dark:bg-black bg-gray-50 border-b dark:border-neutral-300 border-neutral-800">
        <div class="flex flex-row gap-6 justify-center items-center">
            <div>
                <div id="NavListLong" class="flex flex-col gap-6 my-6">
                    <NavLink link="news">
                        Home
                    </NavLink>
                    <NavLink link="crew">
                        Clenove
                    </NavLink>
                    <NavLink link="showcase">
                        Ukázka prací
                    </NavLink>
                    <NavLink link="gallery">
                        Galerie
                    </NavLink>
                </div>
            </div>
            <div class="absolute flex flex-row gap-6 right-0 top-0 my-6 pr-14">
                <NavLinkBorder link="/login">
                    <account-icon />
                    Log In
                </NavLinkBorder>
                <ThemeSwitcher />
            </div>
        </div>
    </nav>
</template>

<style scoped>

</style>
