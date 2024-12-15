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

const authStore = useAuthStore();
const articleStore = useArticleStore();

const isUserLogged = localStorage.getItem("isUserLogged") === "true";

//console.log(articleStore.articles)
</script>

<template>
  <div
    class="flex flex-col justify-center items-center py-14 w-full h-full dark:bg-black bg-orange-50"
  >
    <div
      class="relative flex flex-col items-center gap-10 border dark:border-neutral-300 border-neutral-800 dark:bg-black bg-gray-50 w-10/12 px-8 pt-10 min-h-screen rounded"
    >
      <div class="flex flex-row justify-between w-full">
        <h1
          class="font-dosis-bold dark:text-neutral-100 text-neutral-800 text-5xl md:mr-auto pl-4"
        >
          Dashboard
        </h1>
      </div>
      <div>
      </div>
    </div>
  </div>
</template>
