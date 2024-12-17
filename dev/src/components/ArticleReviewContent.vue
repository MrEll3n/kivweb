<script setup lang="ts">
    import { useArticleStore } from "@/stores/article.store";
    import { useAuthStore } from "@/stores/auth.store";
    // TODO: ts-ignore
    //@ts-ignore

    import { ref } from "vue";

    import MonoButton from "./Inputs/MonoButton.vue";
    //@ts-ignore
    import TextArea from "@/components/Inputs/TextArea.vue";
    import SubmitInput from "@/components/Inputs/SubmitInput.vue";
    import type { Article } from "@/types";
    import { acceptReview, deleteArticle, deleteReview, getReviewedArticles, getReviews, updateArticleReviewed } from "@/utils/rest-api";
    import router from "@/router";
    import { useReviewStore } from "@/stores/review.store";
    

    const authStore = useAuthStore();
    const articleStore = useArticleStore();

    const props = defineProps<{
        article: Article
    }>();

    const reviewedArticles = await getReviews();
    const reviewed_id = reviewedArticles?.find((review) => review.article_id === props.article.article_id)?.review_id;

    const isUserLogged = (localStorage.getItem('isUserLogged') === 'true');

    async function eventAcceptReview() {
        const result1 = await acceptReview(props.article.article_id, reviewed_id ? reviewed_id : 0);
        const result2 = await updateArticleReviewed(props.article.article_id, 0);
        // Handle the result as needed
        //console.log(result);
        router.go(-1);
    }

    async function eventDenyReview() {
            const result1 = await deleteReview(reviewed_id ? reviewed_id : 0);
            const result2 = await deleteArticle(props.article.article_id);
            // Handle the result as needed
            //console.log(result);
            router.go(-1);
        }

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
                <div class="flex justify-start">
                    <p class="font-dosis-regular text-pretty dark:text-neutral-100 text-neutral-800 text-xl md:mr-auto pl-4 break-all">{{ props.article.article_content }}</p>
                </div>
                <div>
                    <MonoButton @clicker="eventAcceptReview" class="z-50">Accept</MonoButton>
                    <MonoButton @clicker="eventDenyReview" class="z-50">Deny</MonoButton>
                </div>
            </div>
        </div>
</template>
