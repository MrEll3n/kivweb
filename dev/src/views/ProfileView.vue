<script setup lang="ts">
    import Navbar from "@/components/Navbar/Navbar.vue";
    import Footer from "@/components/Footer/Footer.vue"
    import ProfileContent from "@/components/ProfileContent.vue";``

    import { ref } from "vue";
    import { getArticles } from "@/utils/rest-api";



    const isUserLogged = localStorage.getItem('isUserLogged') ? ref(localStorage.getItem('isUserLogged') === 'true') : ref(false);

    
</script>

<template>
    <div class="flex flex-col items-center dark:bg-black bg-gray-50">
        <Navbar :is-user-logged="false"/>
            <ProfileContent />
        <Footer />
    </div>
</template>

<style scoped>

</style>
