<script setup lang="ts">
    import { useArticleStore } from "@/stores/article.store";
    import { useAuthStore } from "@/stores/auth.store";
    // TODO: ts-ignore
    //@ts-ignore

    import { ref } from "vue";

    import ChevronLeft from "@/assets/icons/chevron-left.vue";
    import ChevronRight from "@/assets/icons/chevron-right.vue";
    import PageSelector from "@/components/PageSelector/PageSelector.vue";
    import ContentCard from "./ContentCard/ContentCard.vue";
    import Input from "@/components/Inputs/Input.vue";
    //@ts-ignore
    import TextArea from "@/components/Inputs/TextArea.vue";
    import SubmitInput from "@/components/Inputs/SubmitInput.vue";
    import type { Article } from "@/types";
    

    const authStore = useAuthStore();
    const articleStore = useArticleStore();

    const props = defineProps<{
        article: Article;
    }>();



    const isUserLogged = (localStorage.getItem('isUserLogged') === 'true');

    console.log(props.article);

    

</script>

<template>
        <div class="flex flex-col justify-center items-center py-14 w-full h-full dark:bg-neutral-900 bg-orange-50">
            <div class="relative flex flex-col items-center gap-10 border dark:border-neutral-300 border-neutral-800 dark:bg-black bg-gray-50 w-10/12 px-8 pt-10 min-h-screen rounded"> 
                <div class="flex flex-row justify-between items-center w-full">
                    <h1 class="font-dosis-bold dark:text-neutral-100 text-neutral-800 text-5xl md:mr-auto pl-4">{{ props.article.article_header }}</h1>
                    <div class="flex flex-col">
                        <h2 class="font-dosis-regular dark:text-neutral-100 text-neutral-800 text-2xl md:ml-auto pr-4">by <span class="font-dosis-bold">{{ props.article.article_author }}</span></h2>
                        <h2 class="font-dosis-regular dark:text-neutral-100 text-neutral-800 text-xl md:ml-auto pr-4">posted on {{ props.article.article_created.toString() }}</h2>
                    </div>
                </div>
                <div>
                    <p class="font-dosis-regular text-pretty dark:text-neutral-100 text-neutral-800 text-xl md:mr-auto pl-4">{{ props.article.article_content }}</p>
                </div>
            </div>
        </div>
</template>
