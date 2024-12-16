<script setup lang="ts">
import { useArticleStore } from "@/stores/article.store";
import { useAuthStore } from "@/stores/auth.store";
// TODO: ts-ignore
//@ts-ignore

import { ref } from "vue";

import ChevronLeft from "@/assets/icons/chevron-left.vue";
import ChevronRight from "@/assets/icons/chevron-right.vue";
import ContentCard from "./ContentCard/ContentCard.vue";
import Input from "@/components/Inputs/Input.vue";
import SelectInput from "@/components/Inputs/SelectInput.vue";
//@ts-ignore
import TextArea from "@/components/Inputs/TextArea.vue";
import SubmitInput from "@/components/Inputs/SubmitInput.vue";
import type SelectVue from "@/components/Inputs/SelectInput.vue";
import Option from "@/components/Inputs/Option.vue";
import type { Article } from "@/types";
import { createReview, getArticlesModeration, getReviewedArticles, getReviewers, updateArticleReviewed } from "@/utils/rest-api";
import router from "@/router";

const articlesToReview = await getArticlesModeration() ? ref(await getArticlesModeration()) : ref([]);
const reviewers = await getReviewers() ? ref(await getReviewers()) : ref([]);

const selectedArticle = ref();
const selectedReviewer = ref();

async function appointReviewer() {
    if (selectedArticle.value === undefined || selectedReviewer.value === undefined) {
        //console.log("Please select an article and a reviewer");
        return;
    }
    
    const result1 = await createReview(selectedArticle.value, selectedReviewer.value);
    const result2 = await updateArticleReviewed(selectedArticle.value, 1);
    router.go(0);
}


</script>

<template>
    <div class="flex flex-col flex-shrink justify-center items-center py-14 w-full h-full dark:bg-black bg-orange-50">
        <div class="relative flex flex-col items-center gap-10 border dark:border-neutral-300 border-neutral-800 dark:bg-black bg-gray-50 w-10/12 px-8 pt-10 min-h-screen rounded">
            <div class="flex flex-row justify-between w-full">
                <h1 class="font-dosis-bold dark:text-neutral-100 text-neutral-800 text-5xl md:mr-auto pl-4">
                    Moderation
                </h1>
            </div>
            <div class="flex flex-col justify-center items-center gap-4 border dark:border-neutral-300 border-neutral-800 dark:bg-black bg-gray-50 rounded p-10 w-full pt-14">
                <div class="flex xl:flex-row flex-col xl:gap-8 gap-16">
                    <div class="relative flex flex-col justify-center items-center">
                        <label class="absolute -top-9 text-2xl font-dosis-regular dark:text-neutral-100 text-neutral-800">Article</label>
                        <SelectInput v-model="selectedArticle">
                            <Option
                                v-for="article in articlesToReview"
                                :key="article.article_id"
                                :value="article.article_id"
                                :label="article.article_header"
                            />
                        </SelectInput>
                    </div>

                    <div class="relative flex flex-col justify-center items-center">
                        <label class="absolute -top-9 text-2xl font-dosis-regular dark:text-neutral-100 text-neutral-800">Reviewer</label>
                        <SelectInput v-model="selectedReviewer">
                            <Option
                                v-for="reviewer in reviewers"
                                :key="reviewer.user_id"
                                :value="reviewer.user_id"
                                :label="reviewer.user_name"
                            />
                        </SelectInput>
                    </div>

                    <div class="flex flex-col justify-center items-center">
                        <SubmitInput @click="appointReviewer">Appoint</SubmitInput>
                    </div>
                
                </div>
            </div>
        </div>
    </div>
</template>
