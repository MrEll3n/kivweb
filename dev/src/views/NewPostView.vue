<script setup lang="ts">
    import Navbar from "@/components/Navbar/Navbar.vue";
    import Footer from "@/components/Footer/Footer.vue"
    import NewPostContent from "@/components/NewPostContent.vue";

</script>

<template>
    <div class="flex flex-col items-center dark:bg-black bg-gray-50">
        <Navbar :is-user-logged="false"/>
        <NewPostContent />
        <Footer />
    </div>
</template>
