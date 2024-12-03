<script setup lang="ts">
    import ChevronRight from "@/assets/icons/chevron-right.vue";
    import ChevronLeft from "@/assets/icons/chevron-left.vue";
    import { useArticleStore } from "@/stores/article.store";
    import { getArticles, getReviews } from "@/utils/rest-api";
    import router from "@/router";
    // TODO: ts-ignore
    //@ts-ignore
    import { Review } from "@/types"
    import { useRoute } from "vue-router";
    import { computed } from "vue";
import { useReviewStore } from "@/stores/review.store";

    const props = defineProps<{
        link: string
    }>();

    const articleStore = useArticleStore();
    const route = useRoute();

    const qPage = computed(() => route.query.page)

    async function nextPage() {
        if (route.query.page &&
            Math.floor(useReviewStore().count/useReviewStore().numberOfContentInPage) >= useReviewStore().page
        ) {
            useReviewStore().page = Number(qPage.value);

            useReviewStore().page++;
            router.push({ path: props.link, query: { page: useReviewStore().page as number} });

            useReviewStore().reviews = await getReviews(useReviewStore().page) as Review[] | null;
        }
    }

    async function previousPage() {
        if (route.query.page &&
            useArticleStore().page > 1
        ) {
            useArticleStore().page = Number(qPage.value);
            //console.log(articleStore.page);

            useReviewStore().page--;
            router.push({ path: props.link, query: { page: articleStore.page as number} });

            useReviewStore().reviews = await getArticles(articleStore.page) as Review[] | null;
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
