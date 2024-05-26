<script setup>
import UserLayout from "./Layouts/UserLayout.vue";
import Carousel from "./Components/Carousel.vue";
import Logos from "./Components/Logos.vue";
import Product from "./Components/Product.vue";
import Team from "./Components/Team.vue";
import Testimonials from "./Components/Testimonials.vue";

</script>

<template>
    <UserLayout title="Home">
        <Carousel />
        <Logos />
        <Product />
        <Team />
        <Testimonials />
    </UserLayout>
</template>
