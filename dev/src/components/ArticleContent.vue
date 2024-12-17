<script setup lang="ts">
    import { useArticleStore } from "@/stores/article.store";
    import { useAuthStore } from "@/stores/auth.store";
    import type { Article } from "@/types";

    const props = defineProps<{
        article: Article;
    }>();



    const isUserLogged = (localStorage.getItem('isUserLogged') === 'true');


</script>

<template>
        <div class="flex flex-col justify-center items-center py-14 w-full h-full dark:bg-black bg-orange-50">
            <div class="relative flex flex-col items-center gap-10 border dark:border-neutral-300 border-neutral-800 dark:bg-black bg-gray-50 w-10/12 px-8 pt-10 min-h-screen rounded"> 
                <div class="flex flex-row justify-between items-center w-full">
                    <h1 class="font-dosis-bold dark:text-neutral-100 text-neutral-800 text-5xl md:mr-auto pl-4 break-all">{{ props.article.article_header }}</h1>
                    <div class="flex flex-col">
                        <h2 class="font-dosis-regular dark:text-neutral-100 text-neutral-800 text-2xl md:ml-auto pr-4 break-all">by <span class="font-dosis-bold">{{ props.article.article_author }}</span></h2>
                        <h2 class="font-dosis-regular dark:text-neutral-100 text-neutral-800 text-xl md:ml-auto pr-4 break-all">posted on {{ props.article.article_created.toString() }}</h2>
                    </div>
                </div>
                <div>
                    <p class="font-dosis-regular text-pretty dark:text-neutral-100 text-neutral-800 text-xl md:mr-auto pl-4 break-all">{{ props.article.article_content }}</p>
                </div>
            </div>
        </div>
</template>
