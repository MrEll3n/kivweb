<script setup lang="ts">
    import UserTimeOut from "@/components/Notifications/UserTimeOut.vue";
    //import { checkTimeOut } from "@/utils/utils";

    import { onMounted, onUnmounted, ref, watch } from 'vue';
    import { RouterView } from 'vue-router'
    import { isTokenExpired } from '@/utils/rest-api';
    import { onUpdated } from "vue";

    const isSessionExpired = localStorage.getItem('isSessionExpired') ? ref((localStorage.getItem('isSessionExpired') === 'true')) : ref(false);

    if (isSessionExpired.value) {
        document.body.style.overflow = 'hidden';
    } else {
        document.body.style.overflow = 'auto';
    }

</script>

<template class="relative">
    <!--<UserTimeOut v-if="isSessionExpired" v-model="isSessionExpired" />-->
    <Suspense>
        <RouterView />
    </Suspense>
</template>