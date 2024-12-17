<script lang="ts">
import ChatBubbleLeftIcon from "@/assets/icons/chat-bubble-left-icon.vue";
import AccountIcon from "@/assets/icons/account-icon.vue";
import clockIcon from "@/assets/icons/clock-icon.vue";
import MonoButton from "@/components/Inputs/MonoButton.vue";
import { defineComponent, onMounted, ref } from "vue";
import { acceptReview, deleteArticle, deleteReview, fetchImage, updateArticleReviewed } from "@/utils/rest-api";
import router from "@/router";

export default defineComponent({
    name: "ReviewCard",
    components: {
        ChatBubbleLeftIcon,
        AccountIcon,
        clockIcon,
        MonoButton,
    },
    props: {
        review_id: {
        type: Number,
        required: true,
        },
        article_id: {
        type: Number,
        required: true,
        },
        article_header: {
        type: String,
        required: true,
        },
        article_content: {
        type: String,
        required: true,
        },
        article_author: {
        type: String,
        required: true,
        },
        article_image: {
        type: String,
        required: true,
        },
        article_created: {
        type: Date,
        required: true,
        },
    },
    setup(props) {
        // Access article_id from props
        const article_id = props.article_id;
        const review_id = props.review_id;
        const image_src = ref<string>('');

        async function eventAcceptReview() {
            const result1 = await acceptReview(article_id, review_id);
            const result2 = await updateArticleReviewed(article_id, 0);
            // Handle the result as needed
            //console.log(result);
            router.go(0);
        }

        async function eventDenyReview() {
            const result1 = await deleteReview(review_id);
            const result2 = await deleteArticle(article_id);
            // Handle the result as needed
            //console.log(result);
            router.go(0);
        }

        const fetchWrapper = async () => {
            try {
                if (props.article_image != null) {
                    //console.log(`Fetching image: ${props.article_image}`);
                    const response = await fetchImage(props.article_image);
                    if (response) {
                        image_src.value = response as string;
                        //console.log(image_src.value);
                    } else {
                        throw new Error('No response from fetchImage');
                    }
                } else {
                    image_src.value = "./../../assets/images/news-holder.jpg";
                    //console.log('Using default image');
                }
            } catch (error) {
                console.error('Error fetching image:', error);
                image_src.value = "./../../assets/images/news-holder.jpg";
            }
        };
    
        onMounted(() => {
            fetchWrapper();
        });

        // Return any reactive properties or methods you want to expose to the template
        return {
            eventAcceptReview,
            eventDenyReview,
            image_src
        };
    },
});

//<RouterLink :to="`reviews/${review_id as number}`" class="flex md:flex-row flex-col flex-shrink-0 h-full rounded">
</script>

<template>
    <div class="flex md:flex-row flex-col flex-shrink-0 h-full border dark:border-neutral-300 border-neutral-800 dark:bg-black bg-gray-50 h1/4 md:h-[24rem] z-10 rounded min-w-full">
        <div class="flex md:flex-shrink-0 w-full md:w-80 justify-center sm:border-b md:border-b-0 md:border-r dark:border-neutral-300 border-neutral-800">
            <img
                v-if="article_image == null"
                src="/src/assets/images/news-holder.jpg"
                alt="news_image"
            />
            <img
                v-else:
                :src="image_src"
                alt="news_image"
            />
        </div>
        <div class="flex flex-col flex-grow">
            <div class="flex flex-col h-5/6 px-4 pt-4 pb-2">
                <h2 class="font-dosis-bold dark:text-neutral-100 text-neutral-800 text-3xl px-3 pb-2 border-b border-neutral-800 dark:border-neutral-300">{{ article_header }}</h2>
                <p class="font-dosis-regular dark:text-neutral-100 text-neutral-800 text-md px-3 pt-2 line-clamp-5 md:line-clamp-6 break-all">{{ article_content }}</p>
            </div>
            <div class="flex flex-col md:flex-row justify-between m-4 flex-wrap gap-4">
                <div class="flex flex-row gap-1">
                    <div class="flex fled-row mx-2 gap-1">
                        <clock-icon class="dark:stroke-neutral-100 stroke-neutral-800" />
                        <p class="font-dosis-regular dark:text-neutral-100 text-neutral-800">{{ article_created?.toString() }}</p>
                    </div>
                    <div class="flex fled-row gap-1">
                        <account-icon class="dark:stroke-neutral-100 stroke-neutral-800" />
                        <p class="font-dosis-regular dark:text-neutral-100 text-neutral-800">{{ article_author }}</p>
                    </div>
                </div>
                <div>
                    <div class="flex fled-row mx-2 gap-2 flex-wrap">
                        <RouterLink :to="`review/${article_id}`"><MonoButton class="z-50"> Preview </MonoButton></RouterLink>
                        <MonoButton @clicker="eventAcceptReview" class="z-50">Accept</MonoButton>
                        <MonoButton @clicker="eventDenyReview" class="z-50">Deny</MonoButton>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
