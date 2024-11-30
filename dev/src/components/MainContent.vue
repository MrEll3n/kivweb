<script setup lang="ts">
    import { useArticleStore } from "@/stores/article.store";
    import { useAuthStore } from "@/stores/auth.store";
    import { type Article } from "@/types";
    import { useRoute } from "vue-router";

    import { ref } from "vue";

    import ChevronLeft from "@/assets/icons/chevron-left.vue";
    import ChevronRight from "@/assets/icons/chevron-right.vue";
    import PageSelector from "@/components/PageSelector/PageSelector.vue";
    import ContentCard from "./ContentCard/ContentCard.vue";
    

    const authStore = useAuthStore();
    const articleStore = useArticleStore();
    const route = useRoute();

    const isUserLogged = (localStorage.getItem('isUserLogged') === 'true');

</script>

<template>
        <div class="flex flex-col justify-center items-center py-14 w-full h-full dark:bg-neutral-900 bg-orange-50">
            <div class="relative flex flex-col items-center gap-10 border dark:border-neutral-300 border-neutral-800 dark:bg-black bg-gray-50 w-10/12 px-8 pt-10 min-h-screen rounded"> 
                <div class="flex flex-row justify-between w-full">
                    <h1 class="font-dosis-bold dark:text-neutral-100 text-neutral-800 text-5xl md:mr-auto pl-4">Articles</h1>
                    <h1 class="font-dosis-bold dark:text-neutral-100 text-neutral-800 text-5xl md:ml-auto pr-4"> - {{ articleStore.page }} - </h1>
                </div>
                <ContentCard 
                    v-for="article in articleStore.articles" 
                    :header="article.article_header" 
                    :content="article.article_content" 
                    :author="article.article_author" 
                    :image="article.article_image" 
                    :created="article.article_created"
                    :articleId="article.article_id"
                />
                <PageSelector class="-mt-5 mb-5" />
            </div>
        </div>
</template>
