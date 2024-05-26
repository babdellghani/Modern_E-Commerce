<script setup>
import { Head } from "@inertiajs/vue3";
import { defineProps, onMounted } from "vue";
import { initFlowbite } from "flowbite";
import Header from "../Components/Header.vue";
import Footer from "../Components/Footer.vue";

const props = defineProps({
    title: {
        type: String,
        required: true,
    },
});

// initialize components based on data attribute selectors
onMounted(() => {
    initFlowbite();
});
</script>

<template>
    <Head :title="title" />
    <div class="antialiased bg-gray-50 dark:bg-gray-900 min-h-screen">
        <!-- Header -->
        <Header />

        <!-- Main content -->
        <main class="p-4 h-auto">
            <!-- Flash messages -->
            <div
                v-if="$page.props.flash.success"
                class="flex p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg dark:bg-green-200 dark:text-green-800"
                role="alert"
            >
                <span class="font-medium">{{ $page.props.flash.success }}</span>
            </div>
            
            <!-- Error messages -->
            <div
                v-if="$page.props.flash.error"
                class="flex p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg dark:bg-red-200 dark:text-red-800"
                role="alert"
            >
                <span class="font-medium">{{ $page.props.flash.error }}</span>
            </div>

            <!-- Page content -->
            <slot />

        </main>
        <!-- Footer -->
        <Footer />
    </div>
</template>
