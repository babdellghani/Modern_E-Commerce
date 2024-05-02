<script setup>
import { onMounted } from "vue";
import { initFlowbite } from "flowbite";
import { Head } from "@inertiajs/vue3";
import Navbar from "../Components/Navbar.vue";
import Sidebar from "../Components/Sidebar.vue";
// initialize components based on data attribute selectors
onMounted(() => {
    initFlowbite();
});
</script>

<template>
    <Head :title="title" />
    <div class="antialiased bg-gray-50 dark:bg-gray-900">
        
        <!-- Navbar -->
        <Navbar />
        <!-- Sidebar -->
        <Sidebar />

        <main class="p-4 md:ml-64 h-auto pt-20">
            <slot />
        </main>
    </div>
</template>
