<template>
    <select
        v-model="internalValue"
        @change="handleChange"
        class="font-dosis-regular dark:text-neutral-100 text-neutral-800 text-2xl z-10 border peer dark:border-neutral-300 border-neutral-800 rounded w-80 h-12 bg-transparent focus:box-shadow-none focus:outline focus:outline-1 focus:outline-neutral-900 dark:focus:outline-neutral-100 focus:caret-neutral-800 dark:focus:caret-neutral-100 appearance-none">
        <slot />
    </select>
</template>

<script setup lang="ts">
import { ref, defineProps, defineEmits, watch } from 'vue';

const props = defineProps({
    modelValue: {
        type: String,
        required: true
    }
});

const emit = defineEmits(['update:modelValue']);

const internalValue = ref(props.modelValue);

watch(() => props.modelValue, (newValue) => {
    internalValue.value = newValue;
});

function handleChange(event: Event) {
    const value = (event.target as HTMLSelectElement).value;
    internalValue.value = value;
    emit('update:modelValue', value);
}
</script>

<style scoped>
select {
    height: 3rem; /* Adjust as needed */
    line-height: 3rem; /* Match this to the height */
    padding-left: 0.5rem;
    background: transparent; /* Ensure background is transparent */
}
</style>
