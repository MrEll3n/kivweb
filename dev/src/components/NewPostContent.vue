<script setup lang="ts">
import { useArticleStore } from "@/stores/article.store";
import { useAuthStore } from "@/stores/auth.store";
import { ref } from "vue";

import Input from "@/components/Inputs/Input.vue";
import UploadInput from "@/components/Inputs/UploadInput.vue";
//@ts-ignore
import TextArea from "@/components/Inputs/TextArea.vue";
import SubmitInput from "@/components/Inputs/SubmitInput.vue";
import { submitNewArticle } from "@/utils/rest-api";

const authStore = useAuthStore();
const articleStore = useArticleStore();

const article_header = ref<string>("");
const article_content = ref<string>("");

const selectedFile = ref<File | null>(null); // Add this line

const isUserLogged = localStorage.getItem("isUserLogged") === "true";
const successPost = ref<boolean>(false);
const faliedPost = ref<boolean>(false);

async function eventNewPost() {
	const result = await submitNewArticle(article_header.value, article_content.value, selectedFile.value as File);
	if (result) {
		successPost.value = true;
		window.scrollTo(0, 0);
	} else {
		faliedPost.value = true;
	}
}
</script>

<template>
	<form
	v-if="!successPost"
	id="loginForm"
	class="flex flex-col justify-center items-center py-14 w-full h-full dark:bg-black bg-orange-50">
		<div class="relative flex flex-col items-center gap-10 border dark:border-neutral-300 border-neutral-800 dark:bg-black bg-gray-50 w-10/12 px-8 py-10 min-h-screen rounded">
			<div class="flex flex-row justify-between w-full">
				<h1 class="font-dosis-bold dark:text-neutral-100 text-neutral-800 text-5xl md:mr-auto pl-4">
					New Article
				</h1>
			</div>
			<div class="flex flex-col justify-between items-center w-full px-10 gap-10">
				<div class="flex flex-col justify-between w-full">
					<label class="font-dosis-bold dark:text-neutral-100 text-neutral-800 text-4xl md:mr-auto pl-4 mb-4">Header</label>
					<Input v-model="article_header" class="w-full" type="text"/>
				</div>
			</div>
			<div class="flex flex-col justify-between w-full px-10">
				<label class="font-dosis-bold dark:text-neutral-100 text-neutral-800 text-4xl md:mr-auto pl-4 mb-4">Content</label>
				<TextArea
					v-model="article_content"
					class="w-full"
					rows="25"
					cols="30"
				/>
			</div>
			<div class="flex flex-col justify-between w-full px-10">
				<label class="font-dosis-bold dark:text-neutral-100 text-neutral-800 text-4xl md:mr-auto pl-4 mb-4">Article image</label>
				<div class="flex flex-row justify-between items-center">
					<div>
						<UploadInput v-model="selectedFile" label="Upload Image" />
						<p class="font-dosis-regular dark:text-neutral-100 text-neutral-800">Maximum image size 10 MB</p>
					</div>
					<SubmitInput @clicker="eventNewPost">Submit</SubmitInput>
				</div>
			</div>
		</div>
	</form>
	<div v-if="successPost" class="flex flex-col justify-center items-center py-14 w-full h-full dark:bg-black bg-orange-50">
		<div class="relative flex flex-col items-center gap-10 border dark:border-neutral-300 border-neutral-800 dark:bg-black bg-gray-50 w-10/12 px-8 py-10 min-h-screen rounded">
			<div class="flex flex-row justify-between w-full">
				<div class="flex flex-row justify-center w-full">
					<h1 class="text-4xl font-dosis-bold dark:text-neutral-100 text-neutral-800">Post send successfully!</h1>
				</div>
			</div>
		</div>
	</div>
	<div v-if="faliedPost" class="flex flex-col justify-center items-center py-14 w-full h-full dark:bg-black bg-orange-50">
		<div class="relative flex flex-col items-center gap-10 border dark:border-neutral-300 border-neutral-800 dark:bg-black bg-gray-50 w-10/12 px-8 py-10 min-h-screen rounded">
			<div class="flex flex-row justify-between w-full">
				<div class="flex flex-row justify-center w-full">
					<h1 class="text-4xl font-dosis-bold text-red-500">Something gone wrong!</h1>
				</div>
			</div>
		</div>
	</div>
</template>
