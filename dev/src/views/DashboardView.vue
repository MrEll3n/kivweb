<script setup lang="ts">
    import Navbar from "@/components/Navbar/Navbar.vue";
    import Footer from "@/components/Footer/Footer.vue"
    import DashboardContent from "@/components/DashboardContent.vue";

    import { ref } from "vue";
    import { useAuthStore } from "@/stores/auth.store";
    import { useArticleStore } from "@/stores/article.store";


    
    const isUserLogged = localStorage.getItem('isUserLogged') ? ref(localStorage.getItem('isUserLogged') === 'true') : ref(false);

</script>

<template>
    <div class="flex flex-col items-center dark:bg-black bg-gray-50">
        <Navbar :is-user-logged="false"/>
        <DashboardContent />
        <Footer />
    </div>
</template>

<style scoped>

</style>
