<script lang="ts">
import ChatBubbleLeftIcon from "@/assets/icons/chat-bubble-left-icon.vue";
import AccountIcon from "@/assets/icons/account-icon.vue";
import clockIcon from "@/assets/icons/clock-icon.vue";
import { defineComponent } from "vue";

/*
const props = defineProps({
    image: String,
    header: String,
    content: String,
    author: String,
    created: Date,
    articleId: Number
});
*/

export default defineComponent({
    name: 'ContentCard',
    components: {
        ChatBubbleLeftIcon,
        AccountIcon,
        clockIcon
    },
    props: {
        header: {
            type: String,
            required: true
        },
        content: {
            type: String,
            required: true
        },
        author: {
            type: String,
            required: true
        },
        image: {
            type: String,
            required: true
        },
        created: {
            type: Date,
            required: true
        },
        articleId: {
            type: Number,
            required: true
        }
    }
});


</script>

<template>
    <RouterLink :to="`post/${articleId as number}`" class="border dark:border-neutral-300 border-neutral-800 dark:bg-black bg-gray-50 h-1/4 md:h-[24rem] z-10 rounded">
        <div class="flex md:flex-row flex-col flex-shrink-0 h-full rounded">
            <div class="flex md:flex-shrink-0 w-full md:w-80 justify-center sm:border-b md:border-b-0 md:border-r dark:border-neutral-300 border-neutral-800">
                    <img v-if="image == null" src="/src/assets/images/news-holder.jpg" alt="news_image" />
                    <img v-else-if="true" :src="`/src/assets/images/${image}`" alt="news_image">
            </div>
            <div class="flex flex-col">
                <div class="flex flex-col h-5/6 px-4 pt-4 pb-2">
                    <h2 class="font-dosis-bold dark:text-neutral-100 text-neutral-800 text-3xl px-3 pb-2 border-b border-neutral-800 dark:border-neutral-300">{{ header }}</h2>
                    <p class="font-dosis-regular dark:text-neutral-100 text-neutral-800 text-md px-3 pt-2 line-clamp-5 md:line-clamp-6">{{ content }}</p>
                </div>
                <div class="flex flex-row justify-between m-4">
                    <chat-bubble-left-icon class=" dark:stroke-neutral-100 stroke-neutral-800" />
                    <div class="flex flex-row gap-1">
                        <div class="flex fled-row mx-2 gap-1">
                            <clock-icon class=" dark:stroke-neutral-100 stroke-neutral-800" />
                            <p class="font-dosis-regular dark:text-neutral-100 text-neutral-800">{{ created?.toString() }}</p>
                        </div>
                        <div class="flex fled-row gap-1">
                            <account-icon class=" dark:stroke-neutral-100 stroke-neutral-800" />
                            <p class="font-dosis-regular dark:text-neutral-100 text-neutral-800">{{ author }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </RouterLink>
</template>

<style></style>
