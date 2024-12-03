<script setup lang="ts">
    import Navbar from "@/components/Navbar/Navbar.vue";
    //const Navbar = () => import('@/components/Navbar/Navbar.vue');
    import MainContent from "@/components/MainContent.vue";
    //const MainContent = () => import('@/components/MainContent.vue');
    import Footer from "@/components/Footer/Footer.vue"
    //const Footer = () => import('@/components/Footer/Footer.vue');

    import router from "@/router";
    import { ref} from "vue";
    import { getArticles } from "@/utils/rest-api";

    import { useAuthStore } from "@/stores/auth.store";
    import { useArticleStore } from "@/stores/article.store";
    import { useRoute } from "vue-router";
    //const isPermStoredk = () => import('@/utils/utils');

    // Setting up the page
    const authStore = useAuthStore();
    const articleStore = useArticleStore();
    const route = useRoute();

    const isUserLogged = localStorage.getItem('isUserLogged') ? ref(localStorage.getItem('isUserLogged') === 'true') : ref(false);
    articleStore.page = route.query.page ? parseInt(route.query.page as string) as number : 1;
    
    // Checking if the page is a number
    if (
        route.query.page === undefined ||
        route.query.page === null ||
        isNaN(Number(route.query.page)) 
        //Number(route.query.page) > Math.floor(articleStore.count/articleStore.numberOfContentInPage) ||
        //Number(route.query.page) < 1
    ) {
        router.push({name: 'News', query: { page: 1 } });
    };

    // Getting the number of articles
    const articles = await getArticles();
    articleStore.count = articles ? articles.length : 0;
    // Getting the articles
    articleStore.articles = await getArticles(articleStore.page);
    // End of setup

    //console.log(authStore.userData);
    
</script>

<template>
    <div class="flex flex-col items-center dark:bg-black bg-gray-50">
        <Suspense>
            <Navbar :is-user-logged="false" />
        </Suspense>
        <MainContent />
        <Footer />
    </div>
</template>