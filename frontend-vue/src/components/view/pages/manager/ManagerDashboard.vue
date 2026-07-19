<script setup lang="ts">
import { onMounted } from 'vue'
import { useAuthStore } from '../../../../store/auth';
const auth =useAuthStore();
onMounted(()=>{
    document.title= "Dashboard | Manager";
})

</script>

<template>
    <div class="container">
        <h1>Hello Manager Dashboard</h1>
        <h3>Welcome back , {{ auth.user?.name }}</h3>
    </div>
</template>

<style scoped>

</style>