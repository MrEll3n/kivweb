<script setup lang="ts">
import ChevronRight from "@/assets/icons/chevron-right.vue";
import ChevronLeft from "@/assets/icons/chevron-left.vue";
import { useArticleStore } from "@/stores/article.store";
import { getAcceptedArticles } from "@/utils/rest-api";
import router from "@/router";
import { type Article } from "@/types";
import { useRoute } from "vue-router";
import { computed, ref } from "vue";

const props = defineProps<{
    link: string
}>();

const articleStore = useArticleStore();
const route = useRoute();

const qPage = computed(() => Number(route.query.page) || 1);
const currentCount = ref<number>(0);
currentCount.value = (await getAcceptedArticles(true, articleStore.page))?.length || 0;

async function nextPage() {
    currentCount.value = (await getAcceptedArticles(true, articleStore.page))?.length || 0;
    console.log(currentCount)
    if (currentCount.value == articleStore.numberOfContentInPage) {
        articleStore.page = qPage.value + 1;
        await navigateToPage();
    }
}

async function previousPage() {
    if (articleStore.page > 1) {
        articleStore.page = qPage.value - 1;
        await navigateToPage();
    }
}

async function navigateToPage() {
    articleStore.articles = await getAcceptedArticles(true, articleStore.page) as Article[] | null;
    router.push({ path: props.link, query: { page: articleStore.page.toString() } });
    currentCount.value = (await getAcceptedArticles(true, articleStore.page))?.length || 0;
}

const count = computed(() => articleStore.articles?.length ?? 0);
</script>

<template>
    <div class="flex flex-row items-center bottom-0 dark:border-neutral-300 border-neutral-800 w-20 h-9 rounded">
        <button @click="previousPage" class="flex-none mx-2 p-1 hover:bg-gray-200 dark:hover:bg-neutral-800 rounded">
            <chevron-left class="dark:stroke-neutral-100 stroke-neutral-800" />
        </button>
        <div class="w-full"></div>
        <button @click="nextPage" class="flex-none mx-2 p-1 hover:bg-gray-200 dark:hover:bg-neutral-800 rounded">
            <chevron-right class="dark:stroke-neutral-100 stroke-neutral-800" />
        </button>
    </div>
</template>
