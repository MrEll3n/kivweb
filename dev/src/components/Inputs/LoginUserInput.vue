<script setup lang="ts">
const props = defineProps({
    label: String,
});

const value = defineModel();

</script>

<template>
    <div>
        <input
            class="font-dosis-regular dark:text-neutral-100 text-neutral-800 text-2xl z-10 border peer dark:border-neutral-300 border-neutral-800 rounded w-80 h-12 p-4 bg-transparent focus:box-shadow-none focus:outline focus:outline-1 focus:outline-neutral-900 dark:focus:outline-neutral-100 focus:caret-neutral-100"
            placeholder=" "
            type="text"
            v-model="value"
        />
        <label class="absolute left-3 top-2 z-20 origin-[0] -translate-y-10 transform text-2xl font-dosis-bold dark:text-neutral-100 text-neutral-800 duration-300 peer-placeholder-shown:translate-y-0 peer-focus:-translate-y-10 peer-placeholder-shown:font-dosis-regular peer-focus:font-dosis-bold peer-placeholder-shown:-z-10 peer-focus:-z-10 transition ">{{label}}</label>
    </div>
</template>
