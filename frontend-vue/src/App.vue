<script setup lang="ts">
import { onMounted } from 'vue';
import AOS from "aos";

onMounted(() => {
    AOS.init({ duration: 1000, once: true });
});
</script>
<template>
    <router-view/>
</template>
<style scoped>

</style>
