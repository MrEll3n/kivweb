<template>
    <select
        type="select"
        class="font-dosis-regular dark:text-neutral-100 text-neutral-800 text-2xl z-10 border peer dark:border-neutral-300 border-neutral-800 rounded w-80 h-12 bg-transparent focus:box-shadow-none focus:outline focus:outline-1 focus:outline-neutral-900 dark:focus:outline-neutral-100 focus:caret-neutral-800 dark:focus:caret-neutral-100 appearance-none">
        <slot />
    </select>
</template>

<style scoped>
select {
    /* Set a fixed height and line-height to center text vertically */
    height: 3rem; /* Adjust as needed */
    line-height: 3rem; /* Match this to the height */
    padding-left: 0.5rem;
    background: transparent; /* Ensure background is transparent */
}
</style>
