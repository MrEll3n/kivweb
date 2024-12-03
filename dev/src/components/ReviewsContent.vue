<script setup lang="ts">
    import { useArticleStore } from "@/stores/article.store";
    import { useAuthStore } from "@/stores/auth.store";
    import { useReviewStore } from "@/stores/review.store";
    // TODO: ts-ignore
    //@ts-ignore

    import { defineAsyncComponent, ref } from "vue";

    import ChevronLeft from "@/assets/icons/chevron-left.vue";
    import ChevronRight from "@/assets/icons/chevron-right.vue";
    import PageSelectorReviews from "@/components/PageSelector/PageSelectorReviews.vue";
    import Input from "@/components/Inputs/Input.vue";
    import SelectInput from "@/components/Inputs/SelectInput.vue";
    //@ts-ignore
    import TextArea from "@/components/Inputs/TextArea.vue";
    import SubmitInput from "@/components/Inputs/SubmitInput.vue";
    import type SelectVue from "@/components/Inputs/SelectInput.vue";
    import { getReviews } from "@/utils/rest-api";
    import type { Article } from "@/types";

    const authStore = useAuthStore();
    const articleStore = useArticleStore();

    const isUserLogged = localStorage.getItem("isUserLogged") === "true";

    const AsyncReviewCard = defineAsyncComponent({
        loader: () => import('@/components/ReviewCard/ReviewCard.vue'),
        loadingComponent: () => import('@/components/Loader/Loader.vue'),
        delay: 200,
        timeout: 3000
    });

    
    //useReviewStore().review = await getReviews() as Article[];
    //console.log(reviews);

    //console.log(articleStore.articles)
</script>

<template>
    <div class="flex flex-col justify-center items-center py-14 w-full h-full dark:bg-black bg-orange-50">
        <div class="relative flex flex-col items-center gap-10 border dark:border-neutral-300 border-neutral-800 dark:bg-black bg-gray-50 w-10/12 px-8 pt-10 min-h-screen rounded"> 
            <div class="flex flex-row justify-between w-full">
                <h1 class="font-dosis-bold dark:text-neutral-100 text-neutral-800 text-5xl md:mr-auto pl-4">Reviews</h1>
                <h1 v-if="useReviewStore().count > 0" class="font-dosis-bold dark:text-neutral-100 text-neutral-800 text-5xl md:ml-auto pr-4"> - {{ articleStore.page }} - </h1>
            </div>
            <div v-if="!(useReviewStore().count > 0)" class="flex flex-row justify-center w-full">
                <h1 class="text-4xl font-dosis-bold dark:text-neutral-100 text-neutral-800">Nothing to review here!</h1>
            </div>
            <AsyncReviewCard
                v-if="useReviewStore().count > 0"
                v-for="review_item in useReviewStore().reviews" 
                :review_id="review_item.review_id"
                :article_id="review_item.article_id"
                :article_header="review_item.article_header"
                :article_content="review_item.article_content"
                :article_image="review_item.article_image"
                :article_author="review_item.article_author"
                :article_created="review_item.article_created"
            />
            <PageSelectorReviews v-if="useReviewStore().count > 0" link="/reviews" class="-mt-5 mb-5" />
        </div>
    </div>
</template>
