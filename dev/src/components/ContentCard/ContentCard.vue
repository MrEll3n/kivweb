<script setup lang="ts">
import ChatBubbleLeftIcon from "@/assets/icons/chat-bubble-left-icon.vue";
import AccountIcon from "@/assets/icons/account-icon.vue";

const props = defineProps({
    image: String,
    header: String,
    desc: String,

});

</script>

<template>
    <RouterLink to="/" class="border dark:border-neutral-300 border-neutral-800 dark:bg-black bg-gray-50 h-1/4 z-10 rounded">
        <div class="flex md:flex-row flex-col flex-shrink-0 h-full rounded">
            <div class="flex md:flex-shrink-0 w-full md:w-80 justify-center sm:border-b md:border-b-0 md:border-r dark:border-neutral-300 border-neutral-800">
                    <img v-if="props.image == null" src="/src/assets/images/news-holder.jpg" alt="news_image" />
                    <img v-else-if="true" :src="`/src/assets/images/${props.image}`" alt="news_image">
            </div>
            <div class="flex flex-col">
                <div class="flex flex-col h-5/6 px-4 pt-4 pb-2">
                    <h2 class="font-dosis-bold dark:text-neutral-100 text-neutral-800 text-3xl px-3 pb-2 border-b border-neutral-800 dark:border-neutral-300 truncate">Lorem Ipsum</h2>
                    <p class="font-dosis-regular dark:text-neutral-100 text-neutral-800 text-md px-3 pt-2 line-clamp-5 md:line-clamp-3">Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Morbi imperdiet, mauris ac auctor dictum, nisl ligula egestas nulla, et sollicitudin sem purus in lacus. Nulla turpis magna, cursus sit amet, suscipit a, interdum id, felis. Integer tempor. Integer rutrumLorem ipsum dolor sit amet, consectetuer adipiscing elit. Morbi imperdiet, mauris ac auctor dictum, nisl ligula egestas nulla, et sollicitudin sem purus in lacus. Nulla turpis magna, cursus sit amet, suscipit a, interdum id, felis. Integer tempor. Integer rutrumLorem ipsum dolor sit amet, consectetuer adipiscing elit. Morbi imperdiet, mauris ac auctor dictum, nisl ligula egestas nulla, et sollicitudin sem purus in lacus. Nulla turpis magna, cursus sit amet, suscipit a, interdum id, felis. Integer tempor. Integer rutrumLorem ipsum dolor sit amet, consectetuer adipiscing elit. Morbi imperdiet, mauris ac auctor dictum, nisl ligula egestas nulla, et sollicitudin sem purus in lacus. Nulla turpis magna, cursus sit amet, suscipit a, interdum id, felis. Integer tempor. Integer rutrumLorem ipsum dolor sit amet, consectetuer adipiscing elit. Morbi imperdiet, mauris ac auctor dictum, nisl ligula egestas nulla, et sollicitudin sem purus in lacus. Nulla turpis magna, cursus sit amet, suscipit a, interdum id, felis. Integer tempor. Integer rutrum</p>
                </div>
                <div class="flex flex-row justify-between m-4">
                    <chat-bubble-left-icon class=" dark:stroke-neutral-100 stroke-neutral-800" />
                    <div class="flex flex-row gap-1">
                        <account-icon class=" dark:stroke-neutral-100 stroke-neutral-800" />
                        <p class="font-dosis-regular dark:text-neutral-100 text-neutral-800">Autor</p>
                    </div>
                </div>
            </div>
        </div>
    </RouterLink>
</template>

<style></style>
