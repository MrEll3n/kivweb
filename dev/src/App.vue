<script setup lang="ts">
    //import { checkTimeOut } from "@/utils/utils";

    import { onMounted, onUnmounted, ref, watch } from 'vue';
    import { RouterView, useRoute } from 'vue-router'; // Import useRoute from vue-router


    const isSessionExpired = localStorage.getItem('isSessionExpired') ? ref((localStorage.getItem('isSessionExpired') === 'true')) : ref(false);

    if (isSessionExpired.value) {
        document.body.style.overflow = 'hidden';
    } else {
        document.body.style.overflow = 'auto';
    }

    const route = useRoute(); // Add this line to get the route object


</script>

<template class="relative">
    <!--<UserTimeOut v-if="isSessionExpired" v-model="isSessionExpired" />-->
    <Suspense>
        <RouterView />
    </Suspense>
</template>