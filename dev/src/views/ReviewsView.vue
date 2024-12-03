<script setup lang="ts">
    import Navbar from "@/components/Navbar/Navbar.vue";
    import Footer from "@/components/Footer/Footer.vue"
    import ReviewsContent from "@/components/ReviewsContent.vue";

    import { ref } from "vue";
    import router from "@/router";
    import { useRoute } from "vue-router";
    import { getReviews } from "@/utils/rest-api";
    import { useReviewStore } from "@/stores/review.store";

    const isUserLogged = localStorage.getItem('isUserLogged') ? ref(localStorage.getItem('isUserLogged') === 'true') : ref(false);
    console.log('IsUserLogged: '+isUserLogged.value);

    const route = useRoute();

    // Checking if the page is a number
    if (
        route.query.page === undefined ||
        route.query.page === null ||
        isNaN(Number(route.query.page)) 
        //Number(route.query.page) > Math.floor(articleStore.count/articleStore.numberOfContentInPage) ||
        //Number(route.query.page) < 1
    ) {
        router.push({name: 'Reviews', query: { page: 1 } });
    };

    // Getting the number of articles
    const reviews = await getReviews();
    useReviewStore().count = reviews ? reviews.length : 0;

    useReviewStore().reviews = await getReviews(useReviewStore().page);

    console.log(useReviewStore().reviews);
    


</script>

<template>
    <div class="flex flex-col items-center dark:bg-black bg-gray-50">
        <Suspense>
            <Navbar :is-user-logged="false"/>
        </Suspense>
        <ReviewsContent />
        <Footer />
    </div>
</template>

<style scoped>

</style>
