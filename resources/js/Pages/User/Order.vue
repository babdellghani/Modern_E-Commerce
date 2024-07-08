<script setup>
import UserLayout from "./Layouts/UserLayout.vue";

import { computed } from "vue";
import { usePage } from "@inertiajs/vue3";

const orders = computed(() => usePage().props.orders);
</script>


<template>
    <UserLayout title="Order">
        {{ orders }}
    </UserLayout>
</template>