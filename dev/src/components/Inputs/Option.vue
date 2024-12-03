<script setup lang="ts">

    defineProps<{
        value: number
        label: string
    }>()

</script>


<template>
    <option value="{{ value }}">{{ label }}</option>
</template>