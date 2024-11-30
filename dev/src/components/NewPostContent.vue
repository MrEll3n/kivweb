<script setup lang="ts">
	import { useArticleStore } from "@/stores/article.store";
	import { useAuthStore } from "@/stores/auth.store";


	import { ref } from "vue";

	import ChevronLeft from "@/assets/icons/chevron-left.vue";
	import ChevronRight from "@/assets/icons/chevron-right.vue";
	import PageSelector from "@/components/PageSelector/PageSelector.vue";
	import ContentCard from "./ContentCard/ContentCard.vue";
	import Input from "@/components/Inputs/Input.vue";
	import UploadInput from "@/components/Inputs/UploadInput.vue";
	//@ts-ignore
	import TextArea from "@/components/Inputs/TextArea.vue";
	import SubmitInput from "@/components/Inputs/SubmitInput.vue";

	const authStore = useAuthStore();
	const articleStore = useArticleStore();

	const isUserLogged = localStorage.getItem("isUserLogged") === "true";

	//console.log(articleStore.articles)
</script>

<template>
	<div class="flex flex-col justify-center items-center py-14 w-full h-full dark:bg-neutral-900 bg-orange-50">
		<div class="relative flex flex-col items-center gap-10 border dark:border-neutral-300 border-neutral-800 dark:bg-black bg-gray-50 w-10/12 px-8 py-10 min-h-screen rounded">
			<div class="flex flex-row justify-between w-full">
				<h1 class="font-dosis-bold dark:text-neutral-100 text-neutral-800 text-5xl md:mr-auto pl-4">
				New Article
				</h1>
			</div>
			<div class="flex flex-col justify-between items-center w-full px-10 gap-10">
				<div class="flex flex-col justify-between w-full">
					<label class="font-dosis-bold dark:text-neutral-100 text-neutral-800 text-4xl md:mr-auto pl-4 mb-4">Header</label>
					<Input class="w-full" />
				</div>
			</div>	
			<div class="flex flex-col justify-between w-full px-10">
				<label class="font-dosis-bold dark:text-neutral-100 text-neutral-800 text-4xl md:mr-auto pl-4 mb-4">Content</label>
				<TextArea class="w-full" rows="25" cols="30" />
			</div>
			<div class="flex flex-col justify-between w-full px-10">
				<label class="font-dosis-bold dark:text-neutral-100 text-neutral-800 text-4xl md:mr-auto pl-4 mb-4">Article image</label>
				<div class="flex flex-row justify-between items-center">
					<UploadInput label="Upload Image" />
					<SubmitInput>Send</SubmitInput>
				</div>
			</div>
		</div>
	</div>
</template>
