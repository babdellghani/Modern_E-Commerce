<script setup>
defineProps({
    products: {
        type: Object,
    },
    title: {
        type: String,
        required: true,
    },
});
</script>

<template>
    <section class="bg-gray-50 py-8 antialiased dark:bg-gray-900 md:py-12">
        <div class="mx-auto max-w-screen-xl px-4 2xl:px-0">
            <!-- Heading -->
            <div
                class="mb-4 items-end justify-between space-y-4 sm:flex sm:space-y-0 md:mb-8"
            >
                <div>
                    <h2
                        class="mt-3 text-xl font-semibold text-gray-900 dark:text-white sm:text-2xl"
                    >
                        {{ title }}
                    </h2>
                </div>
            </div>
            <div
                class="mb-4 grid gap-4 sm:grid-cols-2 md:mb-8 lg:grid-cols-3 xl:grid-cols-4"
            >
                <div
                    v-for="product in products"
                    class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800"
                >
                    <div class="h-56 w-full">
                        <a href="#">
                            <img
                                v-if="
                                    product.images && product.images.length > 0
                                "
                                class="mx-auto h-full dark:hidden object-cover"
                                :src="`/storage/${product.images[0].image}`"
                                :alt="product.name"
                            />
                            <img
                                v-else
                                class="mx-auto h-full dark:hidden object-cover"
                                src="https://via.placeholder.com/150"
                                :alt="product.name"
                            />
                        </a>
                    </div>
                    <div class="pt-6">
                        <div
                            class="mb-4 flex items-center justify-between gap-4"
                        >
                            <span
                                v-if="product.category"
                                class="me-2 rounded bg-blue-100 px-2.5 py-0.5 text-xs font-medium text-blue-800 dark:bg-blue-900 dark:text-blue-300"
                            >
                                {{ product.category.name }}
                            </span>

                            <div class="flex items-center justify-end gap-1">
                                <button
                                    type="button"
                                    data-tooltip-target="tooltip-add-to-favorites"
                                    class="rounded-lg p-2 text-gray-500 hover:bg-gray-100 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white"
                                >
                                    <span class="sr-only">
                                        Add to Favorites
                                    </span>
                                    <svg
                                        class="h-5 w-5"
                                        aria-hidden="true"
                                        xmlns="http://www.w3.org/2000/svg"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke="currentColor"
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M12 6C6.5 1 1 8 5.8 13l6.2 7 6.2-7C23 8 17.5 1 12 6Z"
                                        />
                                    </svg>
                                </button>
                                <div
                                    id="tooltip-add-to-favorites"
                                    role="tooltip"
                                    class="tooltip invisible absolute z-10 inline-block rounded-lg bg-gray-900 px-3 py-2 text-sm font-medium text-white opacity-0 shadow-sm transition-opacity duration-300 dark:bg-gray-700"
                                    data-popper-placement="top"
                                >
                                    Add to favorites
                                    <div
                                        class="tooltip-arrow"
                                        data-popper-arrow=""
                                    ></div>
                                </div>
                            </div>
                        </div>

                        <a
                            href="#"
                            class="text-lg font-semibold leading-tight text-gray-900 hover:underline dark:text-white"
                            >{{ product.name }}</a
                        >

                        <ul 
                            v-if="product.brand"
                        class="mt-2 flex items-center gap-4">
                            <p
                                class="text-sm font-medium text-gray-500 dark:text-gray-400"
                            >
                                {{ product.brand.name }}
                            </p>
                        </ul>

                        <div
                            class="mt-4 flex items-center justify-between gap-4"
                        >
                            <p
                                class="text-2xl font-extrabold leading-tight text-gray-900 dark:text-white"
                            >
                                {{ product.price }}$
                            </p>

                            <button
                                type="button"
                                class="inline-flex items-center rounded-lg bg-blue-700 px-5 py-2.5 text-sm font-medium text-white hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                            >
                                <svg
                                    class="-ms-2 me-2 h-5 w-5"
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
                                        d="M4 4h1.5L8 16m0 0h8m-8 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4Zm8 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4Zm.75-3H7.5M11 7H6.312M17 4v6m-3-3h6"
                                    />
                                </svg>
                                Add to cart
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="w-full text-center">
                <button
                    type="button"
                    class="rounded-lg border border-gray-200 bg-white px-5 py-2.5 text-sm font-medium text-gray-900 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:outline-none focus:ring-4 focus:ring-gray-100 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white dark:focus:ring-gray-700"
                >
                    Show more
                </button>
            </div>
        </div>
    </section>
</template>
