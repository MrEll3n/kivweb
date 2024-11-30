<script setup lang="ts">
    import Navbar from "@/components/Navbar/Navbar.vue";
    import Footer from "@/components/Footer/Footer.vue"
    import NewPostContent from "@/components/NewPostContent.vue";

    import { ref} from "vue";
    import { getCurrentUser } from "@/utils/utils";

    import { useAuthStore } from "@/stores/auth.store";
    import { useArticleStore } from "@/stores/article.store";
    import { useRoute } from "vue-router";

    const articleStore = useArticleStore();
    const route = useRoute();

    // Getting the current user data
    await getCurrentUser();
    
</script>

<template>
    <div class="flex flex-col items-center dark:bg-black bg-gray-50">
        <Navbar :is-user-logged="false"/>
        <NewPostContent />
        <Footer />
    </div>
</template>
