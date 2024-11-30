<script setup lang="ts">
    import Navbar from "@/components/Navbar/Navbar.vue";
    import Footer from "@/components/Footer/Footer.vue"
    import ArticleContent from "@/components/ArticleContent.vue";


    import { ref} from "vue";
    import { getArticles, getArticle } from "@/utils/rest-api";
    // TODO: ts-ignore
    //@ts-ignore
    import { type Article } from "@/types";
    import { useArticleStore } from "@/stores/article.store";
    import { useRoute } from "vue-router";

    const articleStore = useArticleStore();
    const route = useRoute();


    const isUserLogged = localStorage.getItem('isUserLogged') ? ref(localStorage.getItem('isUserLogged') === 'true') : ref(false);
    articleStore.page = route.query.page ? parseInt(route.query.page as string) as number : 1;


    const currentArticleId = Number(route.params.article);
    const currentArticle = useArticleStore().articles?.find((article: Article) => article.article_id === currentArticleId);
</script>

<template>
    <div class="flex flex-col items-center dark:bg-black bg-gray-50">
        <Navbar :isUserLogged="false"/>
        <ArticleContent :article="(currentArticle as Article)" />
        <Footer />
    </div>
</template>
