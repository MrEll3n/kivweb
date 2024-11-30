<script setup lang="ts">
    import Navbar from "@/components/Navbar/Navbar.vue";
    import Footer from "@/components/Footer/Footer.vue"
    import DashboardContent from "@/components/DashboardContent.vue";

    import { ref } from "vue";
    import { getArticles, refreshToken } from "@/utils/rest-api";
    import { getCurrentUser,} from "@/utils/utils";
    import { useAuthStore } from "@/stores/auth.store";
    import { useArticleStore } from "@/stores/article.store";
    import { useRoute } from "vue-router";
    import { type UserData } from "@/types";

    
    // Setting up the page
    const authStore = useAuthStore();
    const articleStore = useArticleStore();
    const route = useRoute();
    
    const isUserLogged = localStorage.getItem('isUserLogged') ? ref(localStorage.getItem('isUserLogged') === 'true') : ref(false);
    console.log('IsUserLogged: '+isUserLogged.value);

    await refreshToken();
    // Getting the current user data
    authStore.userData = getCurrentUser() as UserData | null;
    // Getting the number of articles
    const articles = await getArticles();
    articleStore.count = articles ? articles.length : 0;
    // Getting the articles
    articleStore.articles = await getArticles(articleStore.page);
    // End of setup
    
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
