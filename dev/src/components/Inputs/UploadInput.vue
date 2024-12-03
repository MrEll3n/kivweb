<script setup lang="ts">
import { ref } from 'vue';

const fileName = ref('No file selected');
const fileSelected = ref(false);
const selectedFile = defineModel<File | null>(); // Store the selected file

const handleFileChange = (event: Event) => {
    const target = event.target as HTMLInputElement;
    const file = target.files?.[0]; // Get the first file

    if (file) {
        selectedFile.value = file; // Store the file in the reactive reference
        fileName.value = file.name; // Update the file name
        fileSelected.value = true; // Mark that a file has been selected
    } else {
        selectedFile.value = null; // Reset the file reference
        fileName.value = 'No file selected'; // Reset the file name
        fileSelected.value = false; // Mark that no file is selected
    }
};
</script>

<template>
    <div class="relative border dark:border-neutral-300 border-neutral-800 rounded overflow-hidden bg-transparent">
        <label
            for="upload"
            class="inline-block font-dosis-bold px-4 py-3 cursor-pointer rounded-r-sm dark:bg-gray-50 bg-neutral-900 
                dark:text-neutral-800 text-neutral-100 hover:bg-gray-50 hover:dark:bg-neutral-900 hover:text-neutral-800 
                hover:dark:text-neutral-100 text-lg font-bold"
        >
            Upload File
        </label>
        <input
            id="upload"
            type="file"
            class="hidden"
            @change="handleFileChange"
        />
        <span v-if="!fileSelected" id="file-chosen" class="m-4 text-gray-800 dark:text-neutral-100">Select a file</span>
        <span v-if="fileSelected" id="file-chosen" class="m-4 text-gray-800 dark:text-neutral-100">{{ fileName }}</span>
    </div>
</template>

<style>
input[type="file"] {
    @apply absolute z-[-1] top-[10px] left-[8px] text-[17px] text-gray-400 dark:text-gray-600;
}
</style>