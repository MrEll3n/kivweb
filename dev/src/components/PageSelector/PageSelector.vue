<script setup lang="ts">
    import ChevronRight from "@/assets/icons/chevron-right.vue";
    import ChevronLeft from "@/assets/icons/chevron-left.vue";
    import { useArticleStore } from "@/stores/article.store";
    import { getArticles } from "@/utils/rest-api";
    import router from "@/router";
    // TODO: ts-ignore
    //@ts-ignore
    import { Article } from "@/types"
    import { useRoute } from "vue-router";
    import { computed } from "vue";

    const articleStore = useArticleStore();
    const route = useRoute();

    const qPage = computed(() => route.query.page)

    async function nextPage() {
        if (route.query.page &&
            Math.floor(articleStore.count/articleStore.numberOfContentInPage) >= articleStore.page
        ) {
            articleStore.page = Number(qPage.value);

            articleStore.page++;
            router.push({ path: '/news', query: { page: articleStore.page as number} });

            articleStore.articles = await getArticles(articleStore.page) as Article[] | null;
        }
    }

    async function previousPage() {
        if (route.query.page &&
            articleStore.page > 1
        ) {
            articleStore.page = Number(qPage.value);
            //console.log(articleStore.page);

            articleStore.page--;
            router.push({ path: '/news', query: { page: articleStore.page as number} });

            articleStore.articles = await getArticles(articleStore.page) as Article[] | null;
        }

    }


</script>

<template>
    <div class="flex flex-row items-center bottom-0 dark:border-neutral-300 border-neutral-800 w-20 h-9 rounded">
        <button @click="previousPage" class="flex-none mx-2 p-1 hover:bg-gray-200 dark:hover:bg-neutral-800 rounded"><chevron-left class="dark:stroke-neutral-100 stroke-neutral-800" /></button>
        <div class="w-full"></div>
        <button @click="nextPage" class="flex-none mx-2 p-1 hover:bg-gray-200 dark:hover:bg-neutral-800 rounded"><chevron-right class="dark:stroke-neutral-100 stroke-neutral-800" /></button>
    </div>
</template>
