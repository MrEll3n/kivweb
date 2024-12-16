<script setup lang="ts">
    import { useArticleStore } from "@/stores/article.store";
    import { defineAsyncComponent } from "vue";

    import PageSelectorArticle from "@/components/PageSelector/PageSelectorArticle.vue";
    //import ContentCard from "./ContentCard/ContentCard.vue";
    const AsyncContentCard = defineAsyncComponent({
        loader: () => import('@/components/ContentCard/ContentCard.vue'),
        loadingComponent: () => import('@/components/Loader/Loader.vue'),
        delay: 200,
        timeout: 3000
    });
    

    const articleStore = useArticleStore();

    const isUserLogged = (localStorage.getItem('isUserLogged') === 'true');
    //console.log(useArticleStore().countAccepted);

</script>

<template>
    <div class="flex flex-col justify-center items-center py-14 w-full h-full dark:bg-black bg-orange-50">
        <div class="relative flex flex-col items-center gap-10 border dark:border-neutral-300 border-neutral-800 dark:bg-black bg-gray-50 w-10/12 px-8 pt-10 min-h-screen rounded"> 
            <div class="flex flex-row justify-between w-full">
                <h1 class="font-dosis-bold dark:text-neutral-100 text-neutral-800 text-5xl md:mr-auto pl-4">Articles</h1>
                <h1 v-if="useArticleStore().countAccepted > 0" class="font-dosis-bold dark:text-neutral-100 text-neutral-800 text-5xl md:ml-auto pr-4"> - {{ useArticleStore().page }} - </h1>
            </div>
            <div v-if="!(useArticleStore().countAccepted > 0)" class="flex flex-row justify-center w-full">
                <h1 class="text-4xl font-dosis-bold dark:text-neutral-100 text-neutral-800">Nothing to review here!</h1>
            </div>
            <AsyncContentCard
                v-if="useArticleStore().countAccepted > 0"
                v-for="article in articleStore.articles" 
                :header="article.article_header" 
                :content="article.article_content" 
                :author="article.article_author" 
                :image="article.article_image" 
                :created="article.article_created"
                :articleId="article.article_id"
            />
            <PageSelectorArticle v-if="useArticleStore().countAccepted > 0" link="/news" class="-mt-5 mb-5" />
        </div>
    </div>
</template>
