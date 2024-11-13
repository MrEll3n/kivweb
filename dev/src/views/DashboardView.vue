<script setup lang="ts">
    import router from "@/router";
    import axios from 'axios';
    import { useLocalStorage } from "@vueuse/core";

    const urlBackend = "http://localhost:8080/kivweb/backend/api/index.php/auth";

    const sessionToken = localStorage.getItem("authorization");
    //console.log(sessionToken);

    axios({
        method: 'post',
        url: urlBackend,
        headers: {
           'Authorization': sessionToken,
        }
    }).then(response => {
        //console.log(response.status);
    }).catch(error => {
        if (error.status == 408) {
            localStorage.removeItem('authorization');
            router.push({name: 'Login'});
        }
    });
</script>

<template>

</template>

<style scoped>

</style>
