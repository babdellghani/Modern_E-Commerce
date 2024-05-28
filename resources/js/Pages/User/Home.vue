<script setup>
import UserLayout from "./Layouts/UserLayout.vue";
import Carousel from "./Components/Carousel.vue";
import Logos from "./Components/Logos.vue";
import Product from "./Components/Product.vue";
import Team from "./Components/Team.vue";
import Testimonials from "./Components/Testimonials.vue";

import { ref } from "vue";
import { usePage } from "@inertiajs/vue3";

const products = ref(usePage().props.products);
</script>

<template>
    <UserLayout title="Home">
        <Carousel />
        <Logos />
        <Product :products="products" title="Featured Products" />
        <Team />
        <Testimonials />
    </UserLayout>
</template>
