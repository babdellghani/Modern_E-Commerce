<script setup>
import UserLayout from "./Layouts/UserLayout.vue";
import { computed } from "vue";
import { usePage } from "@inertiajs/vue3";

const order = computed(() => usePage().props.order);
</script>

<template>
    <UserLayout title="Order">
        <section class="bg-white py-8 antialiased dark:bg-gray-900 md:py-16">
            <div class="mx-auto max-w-screen-xl px-4 2xl:px-0">
                <h2
                    class="text-xl font-semibold text-gray-900 dark:text-white sm:text-2xl"
                >
                    Track the delivery of order #{{ order.id }}
                </h2>

                <div class="mt-6 sm:mt-8 lg:flex lg:gap-8">
                    <div
                        class="w-full h-fit divide-y divide-gray-200 overflow-hidden rounded-lg border border-gray-200 dark:divide-gray-700 dark:border-gray-700 lg:max-w-xl xl:max-w-2xl"
                    >
                        <div v-for="item in order.items" class="space-y-4 p-6">
                            <div class="flex items-center gap-6">
                                <a
                                    v-if="item.product.images.length > 0"
                                    href="#"
                                    class="h-14 w-14 shrink-0"
                                >
                                    <img
                                        class="h-full w-full dark:hidden"
                                        :src="`storage/${item.product.images[0].image}`"
                                        :alt="item.product.name"
                                    />
                                    <img
                                        class="hidden h-full w-full dark:block"
                                        :src="`storage/${item.product.images[0].image}`"
                                        :alt="item.product.name"
                                    />
                                </a>

                                <a v-else href="#" class="h-14 w-14 shrink-0">
                                    <img
                                        class="h-full w-full dark:hidden"
                                        src="https://flowbite.s3.amazonaws.com/blocks/marketing-ui/avatars/bonnie-green.png"
                                        :alt="item.product.name"
                                    />
                                    <img
                                        class="hidden h-full w-full dark:block"
                                        src="https://flowbite.s3.amazonaws.com/blocks/marketing-ui/avatars/bonnie-green.png"
                                        :alt="item.product.name"
                                    />
                                </a>

                                <a
                                    href="#"
                                    class="min-w-0 flex-1 font-medium text-gray-900 hover:underline dark:text-white"
                                >
                                    {{ item.product.name }}
                                </a>
                            </div>

                            <div
                                class="flex items-center justify-between gap-4"
                            >
                                <p
                                    class="text-sm font-normal text-gray-500 dark:text-gray-400"
                                >
                                    <span
                                        class="font-medium text-gray-900 dark:text-white"
                                        >Product ID:</span
                                    >
                                    {{ item.product.id }}
                                </p>

                                <div
                                    class="flex items-center justify-end gap-4"
                                >
                                    <p
                                        class="text-base font-normal text-gray-900 dark:text-white"
                                    >
                                        x{{ item.quantity }}
                                    </p>

                                    <p
                                        class="text-xl font-bold leading-tight text-gray-900 dark:text-white"
                                    >
                                        ${{ item.unit_price }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-4 bg-gray-50 p-6 dark:bg-gray-800">
                            <div class="space-y-2">
                                <dl
                                    class="flex items-center justify-between gap-4"
                                >
                                    <dt
                                        class="font-normal text-gray-500 dark:text-gray-400"
                                    >
                                        Original price
                                    </dt>
                                    <dd
                                        class="font-medium text-gray-900 dark:text-white"
                                    >
                                        ${{ order.total_price }}
                                    </dd>
                                </dl>

                                <dl
                                    class="flex items-center justify-between gap-4"
                                >
                                    <dt
                                        class="font-normal text-gray-500 dark:text-gray-400"
                                    >
                                        Savings
                                    </dt>
                                    <dd
                                        class="text-base font-medium text-green-500"
                                    >
                                        -$299.00
                                    </dd>
                                </dl>

                                <dl
                                    class="flex items-center justify-between gap-4"
                                >
                                    <dt
                                        class="font-normal text-gray-500 dark:text-gray-400"
                                    >
                                        Store Pickup
                                    </dt>
                                    <dd
                                        class="font-medium text-gray-900 dark:text-white"
                                    >
                                        $99
                                    </dd>
                                </dl>

                                <dl
                                    class="flex items-center justify-between gap-4"
                                >
                                    <dt
                                        class="font-normal text-gray-500 dark:text-gray-400"
                                    >
                                        Tax
                                    </dt>
                                    <dd
                                        class="font-medium text-gray-900 dark:text-white"
                                    >
                                        $799
                                    </dd>
                                </dl>
                            </div>

                            <dl
                                class="flex items-center justify-between gap-4 border-t border-gray-200 pt-2 dark:border-gray-700"
                            >
                                <dt
                                    class="text-lg font-bold text-gray-900 dark:text-white"
                                >
                                    Total
                                </dt>
                                <dd
                                    class="text-lg font-bold text-gray-900 dark:text-white"
                                >
                                    ${{ order.total_price }}
                                </dd>
                            </dl>
                        </div>
                    </div>

                    <div class="mt-6 grow sm:mt-8 lg:mt-0">
                        <div
                            class="space-y-6 rounded-lg border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800"
                        >
                            <h3
                                class="text-xl font-semibold text-gray-900 dark:text-white"
                            >
                                Order history
                            </h3>

                            <div
                                class="text-lg rounded-lg w-full border border-gray-200 bg-gray-50 p-4 ps-4 dark:border-gray-700 dark:bg-gray-800"
                            >
                                <label
                                    class="capitalize font-medium leading-none text-gray-900 dark:text-white"
                                >
                                    {{ order.address.type }}
                                </label>
                                <p
                                    class="mt-1 capitalize text-xs font-normal text-gray-500 dark:text-gray-400"
                                >
                                    {{ order.address.address_1 }}
                                </p>
                                <p
                                    class="mt-1 uppercase text-xs font-normal text-gray-500 dark:text-gray-400"
                                >
                                    {{ order.address.city }} -
                                    {{ order.address.state }}
                                </p>
                                <p
                                    class="mt-1 text-xs font-normal text-gray-500 dark:text-gray-400"
                                >
                                    {{ order.address.postal_code }}
                                </p>
                                <p
                                    class="mt-1 uppercase text-xs font-normal text-gray-500 dark:text-gray-400"
                                >
                                    {{ order.address.country }}
                                </p>
                                <p
                                    class="mt-1 text-xs font-normal text-gray-500 dark:text-gray-400"
                                >
                                    {{ order.address.phone }}
                                </p>
                            </div>

                            <ol
                                class="relative ms-3 border-s border-gray-200 dark:border-gray-700"
                            >
                                <li class="mb-10 ms-6">
                                    <span
                                        class="absolute -start-3 flex h-6 w-6 items-center justify-center rounded-full bg-gray-100 ring-8 ring-white dark:bg-gray-700 dark:ring-gray-800"
                                    >
                                        <svg
                                            class="h-4 w-4 text-gray-500 dark:text-gray-400"
                                            aria-hidden="true"
                                            xmlns="http://www.w3.org/2000/svg"
                                            width="24"
                                            height="24"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke="currentColor"
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="m4 12 8-8 8 8M6 10.5V19a1 1 0 0 0 1 1h3v-3a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v3h3a1 1 0 0 0 1-1v-8.5"
                                            />
                                        </svg>
                                    </span>
                                    <h4
                                        class="mb-0.5 text-base font-semibold text-gray-900 dark:text-white"
                                    >
                                        Estimated delivery in 24 Nov 2023
                                    </h4>
                                    <p
                                        class="text-sm font-normal text-gray-500 dark:text-gray-400"
                                    >
                                        Products delivered
                                    </p>
                                </li>

                                <li class="mb-10 ms-6">
                                    <span
                                        class="absolute -start-3 flex h-6 w-6 items-center justify-center rounded-full bg-gray-100 ring-8 ring-white dark:bg-gray-700 dark:ring-gray-800"
                                    >
                                        <svg
                                            class="h-4 w-4 text-gray-500 dark:text-gray-400"
                                            aria-hidden="true"
                                            xmlns="http://www.w3.org/2000/svg"
                                            width="24"
                                            height="24"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke="currentColor"
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M13 7h6l2 4m-8-4v8m0-8V6a1 1 0 0 0-1-1H4a1 1 0 0 0-1 1v9h2m8 0H9m4 0h2m4 0h2v-4m0 0h-5m3.5 5.5a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0Zm-10 0a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0Z"
                                            />
                                        </svg>
                                    </span>
                                    <h4
                                        class="mb-0.5 text-base font-semibold text-gray-900 dark:text-white"
                                    >
                                        Today
                                    </h4>
                                    <p
                                        class="text-sm font-normal text-gray-500 dark:text-gray-400"
                                    >
                                        Products being delivered
                                    </p>
                                </li>

                                <li
                                    class="mb-10 ms-6"
                                    :class="{
                                        'text-yellow-500 dark:text-yellow-700':
                                            order.status === 'pending',

                                        'text-blue-700 dark:text-blue-500':
                                            order.status === 'paid',

                                        'text-red-700 dark:text-red-500':
                                            order.status === 'unpaid',
                                    }"
                                >
                                    <span
                                        class="absolute -start-3 flex h-6 w-6 items-center justify-center rounded-full bg-blue-100 ring-8 ring-white dark:bg-blue-900 dark:ring-gray-800"
                                    >
                                        <svg
                                            class="h-4 w-4"
                                            aria-hidden="true"
                                            xmlns="http://www.w3.org/2000/svg"
                                            width="24"
                                            height="24"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke="currentColor"
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M5 11.917 9.724 16.5 19 7.5"
                                            />
                                        </svg>
                                    </span>
                                    <h4 class="mb-0.5 font-semibold">
                                        {{
                                            new Date(
                                                order.payment.created_at
                                            ).toUTCString()
                                        }}
                                    </h4>
                                    <p class="text-sm capitalize">
                                        Payment {{ order.payment.status }} -
                                        {{ order.payment.type }}
                                    </p>
                                </li>

                                <li
                                    class="ms-6 text-blue-700 dark:text-blue-500"
                                >
                                    <span
                                        class="absolute -start-3 flex h-6 w-6 items-center justify-center rounded-full bg-blue-100 ring-8 ring-white dark:bg-blue-900 dark:ring-gray-800"
                                    >
                                        <svg
                                            class="h-4 w-4"
                                            aria-hidden="true"
                                            xmlns="http://www.w3.org/2000/svg"
                                            width="24"
                                            height="24"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke="currentColor"
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M5 11.917 9.724 16.5 19 7.5"
                                            />
                                        </svg>
                                    </span>
                                    <div>
                                        <h4 class="mb-0.5 font-semibold">
                                            {{
                                                new Date(
                                                    order.created_at
                                                ).toUTCString()
                                            }}
                                        </h4>
                                        <a
                                            href="#"
                                            class="text-sm font-medium hover:underline"
                                            >Order placed - Receipt #{{
                                                order.id
                                            }}</a
                                        >
                                    </div>
                                </li>
                            </ol>

                            <div
                                v-if="order.status === 'pending'"
                                class="gap-4 sm:flex sm:items-center"
                            >
                                <button
                                    type="button"
                                    class="w-full rounded-lg border border-gray-200 bg-white px-5 py-2.5 text-sm font-medium text-gray-900 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:outline-none focus:ring-4 focus:ring-gray-100 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white dark:focus:ring-gray-700"
                                >
                                    Cancel the order
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </UserLayout>
</template>
