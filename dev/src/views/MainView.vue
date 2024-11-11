<script setup lang="ts">
    import Navbar from "@/components/Navbar/Navbar.vue";
    import MainContent from "@/components/MainContent.vue";
    import Footer from "@/components/Footer/Footer.vue"
    import ContentCard from "@/components/ContentCard/ContentCard.vue";
    import HamburgerDropdown from "@/components/HamburgerMenu/HamburgerDropdown.vue";


</script>

<template>
    <div class="flex flex-col items-center dark:bg-black bg-gray-50">
        <Navbar />
        <MainContent>
            <ContentCard />
            <ContentCard />
            <ContentCard />
            <ContentCard />
            <ContentCard />
        </MainContent>
        <Footer />
    </div>
</template>
