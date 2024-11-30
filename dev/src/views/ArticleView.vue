<script setup lang="ts">
    import Navbar from "@/components/Navbar/Navbar.vue";
    import Footer from "@/components/Footer/Footer.vue"
    import ArticleContent from "@/components/ArticleContent.vue";


    import { ref} from "vue";
    import { getArticles, getArticle } from "@/utils/rest-api";
    import { getCurrentUser } from "@/utils/utils";
    // TODO: ts-ignore
    //@ts-ignore
    import { type Article } from "@/types";
    import { useAuthStore } from "@/stores/auth.store";
    import { useArticleStore } from "@/stores/article.store";
    import { useRoute } from "vue-router";
    import { type UserData } from "@/types";

    const authStore = useAuthStore();
    const articleStore = useArticleStore();
    const route = useRoute();

    

    const isUserLogged = localStorage.getItem('isUserLogged') ? ref(localStorage.getItem('isUserLogged') === 'true') : ref(false);
    articleStore.page = route.query.page ? parseInt(route.query.page as string) as number : 1;

    // Getting the current user data
    authStore.userData = getCurrentUser() as UserData | null;
    // Getting the number of articles
    const articles = await getArticles();
    articleStore.count = articles ? articles.length : 0;
    // Getting the articles
    articleStore.articles = await getArticles(articleStore.page);
    

    const currentArticleId = route.params.article as string;
    const currentArticle = await getArticle(currentArticleId); 
</script>

<template>
    <div class="flex flex-col items-center dark:bg-black bg-gray-50">
        <Navbar :is-user-logged="false"/>
        <ArticleContent :article="(currentArticle as Article)" />
        <Footer />
    </div>
</template>
