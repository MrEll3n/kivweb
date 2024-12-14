<script setup lang="ts">
    import Navbar from "@/components/Navbar/Navbar.vue";
    import Footer from "@/components/Footer/Footer.vue"
    import ModerationContent from "@/components/ModerationContent.vue";

    import { ref } from "vue";

    
    const isUserLogged = localStorage.getItem('isUserLogged') ? ref(localStorage.getItem('isUserLogged') === 'true') : ref(false);
    console.log('IsUserLogged: '+isUserLogged.value);

</script>

<template>
    <div class="flex flex-col items-center dark:bg-black bg-gray-50">
        <Navbar :is-user-logged="false"/>
        <Suspense>
            <ModerationContent />
        </Suspense>
        <Footer />
    </div>
</template>

<style scoped>

</style>
