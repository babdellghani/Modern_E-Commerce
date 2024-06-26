<script setup>
import UserLayout from "./Layouts/UserLayout.vue";
import Carousel from "./Components/Carousel.vue";
import Logos from "./Components/Logos.vue";
import Team from "./Components/Team.vue";
import Testimonials from "./Components/Testimonials.vue";
import ProductSection from "./Components/ProductSection.vue";

import { ref } from "vue";
import { usePage } from "@inertiajs/vue3";

const products = ref(usePage().props.products);
</script>

<template>
    <UserLayout title="Home">
        <Carousel />
        <Logos />
        <ProductSection :products="products" title="Featured Products" />
        <Team />
        <Testimonials />
    </UserLayout>
</template>
