<script setup>
import { router, usePage } from "@inertiajs/vue3";
import { computed, defineProps } from "vue";

defineProps({
    products: {
        type: Object,
    },
    productsPage: {
        type: Object,
    },
});

const cartItems = computed(() => usePage().props.cartItems);

const addToCart = async (product) => {
    try {
        router.post(
            "/cart/add/" + product.id,
            {
                product_id: product.id,
                quantity: 1,
            },
            {
                preserveScroll: true,
                onSuccess: () => {
                    document.getElementById("myCartDropdownButton1").click();
                },
                onError: (error) => {
                    console.log(error);
                },
            }
        );
    } catch (error) {
        console.log(error);
    }
};

const isProductInCart = (productId) => {
    return cartItems.value.some((item) => item.product_id === productId);
};

const getProductQuantity = (productId) => {
    const item = cartItems.value.find((item) => item.product_id === productId);
    return item ? item.quantity : 0;
};

const updateQuantity = async (id, quantity) => {
    try {
        router.post(
            `/cart/update/${id}/${quantity}`,
            {
                _method: "put",
                quantity: quantity,
                product_id: id,
            },
            {
                preserveScroll: true,
                onError: (error) => {
                    console.error(error);
                },
            }
        );
    } catch (error) {
        console.error(error);
    }
};

const removeItem = async (id) => {
    try {
        router.delete(`/cart/remove/${id}`, {
            preserveScroll: true,
            onError: (error) => {
                console.error(error);
            },
        });
    } catch (error) {
        console.error(error);
    }
};

// Pagination
const changePage = (page) => {
    const params = new URLSearchParams(window.location.search);
    params.set("page", page);
    router.visit(`/products?${params.toString()}`, {
        preserveState: true,
        preserveScroll: false,
        replace: true,
    });
};
</script>

<template>
    <div v-if="products.length > 0">
        <div
            class="bg-white dark:bg-gray-900 mb-4 grid gap-4"
            :class="
                productsPage
                    ? 'sm:grid-cols-2 md:mb-8 lg:grid-cols-2 xl:grid-cols-3'
                    : 'sm:grid-cols-2 md:mb-8 lg:grid-cols-3 xl:grid-cols-4'
            "
        >
            <div
                v-for="product in products"
                class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800"
            >
                <div class="h-56 w-full">
                    <a href="#">
                        <img
                            v-if="product.images && product.images.length > 0"
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
                    <div class="mb-4 flex items-center justify-between gap-4">
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
                                <span class="sr-only"> Add to Favorites </span>
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
                        class="mt-2 flex items-center gap-4"
                    >
                        <p
                            class="text-sm font-medium text-gray-500 dark:text-gray-400"
                        >
                            {{ product.brand.name }}
                        </p>
                    </ul>

                    <div class="mt-4 flex items-center justify-between gap-4">
                        <p
                            class="text-2xl font-extrabold leading-tight text-gray-900 dark:text-white"
                        >
                            {{ product.price }}$
                        </p>

                        <div
                            v-if="!isProductInCart(product.id)"
                            class="flex items-center justify-end gap-1"
                        >
                            <button
                                type="button"
                                class="inline-flex items-center rounded-lg bg-blue-700 px-5 py-2.5 text-sm font-medium text-white hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                                @click="addToCart(product)"
                                v-if="product.quantity > 0"
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

                            <button
                                v-else
                                type="button"
                                class="inline-flex items-center rounded-lg bg-gray-200 px-5 py-2.5 text-sm font-medium text-gray-900 hover:bg-gray-300 focus:outline-none focus:ring-4 focus:ring-gray-300 dark:bg-gray-700 dark:text-gray-400 dark:hover:bg-gray-600 dark:focus:ring-gray-700"
                            >
                                Not available
                            </button>
                        </div>
                        <div v-else class="flex items-center">
                            <button
                                type="button"
                                id="decrement-button"
                                class="inline-flex h-5 w-5 shrink-0 items-center justify-center rounded-md border border-gray-300 bg-gray-100 hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-100 dark:border-gray-600 dark:bg-gray-700 dark:hover:bg-gray-600 dark:focus:ring-gray-700"
                                @click="
                                    getProductQuantity(product.id) === 1
                                        ? removeItem(product.id)
                                        : updateQuantity(
                                              product.id,
                                              getProductQuantity(product.id) - 1
                                          )
                                "
                            >
                                <svg
                                    class="h-2.5 w-2.5 text-gray-900 dark:text-white"
                                    aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg"
                                    fill="none"
                                    viewBox="0 0 18 2"
                                >
                                    <path
                                        stroke="currentColor"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M1 1h16"
                                    />
                                </svg>
                            </button>
                            <input
                                type="text"
                                id="counter-input"
                                class="w-10 shrink-0 border-0 bg-transparent text-center text-sm font-medium text-gray-900 focus:outline-none focus:ring-0 dark:text-white"
                                disabled
                                :value="getProductQuantity(product.id)"
                                required
                            />
                            <button
                                type="button"
                                id="increment-button"
                                class="inline-flex h-5 w-5 shrink-0 items-center justify-center rounded-md border border-gray-300 bg-gray-100 hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-100 dark:border-gray-600 dark:bg-gray-700 dark:hover:bg-gray-600 dark:focus:ring-gray-700"
                                @click="
                                    updateQuantity(
                                        product.id,
                                        getProductQuantity(product.id) + 1
                                    )
                                "
                            >
                                <svg
                                    class="h-2.5 w-2.5 text-gray-900 dark:text-white"
                                    aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg"
                                    fill="none"
                                    viewBox="0 0 18 18"
                                >
                                    <path
                                        stroke="currentColor"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M9 1v16M1 9h16"
                                    />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div v-else class="flex items-center justify-center w-full">
        <div class="empty-state">
            <div class="empty-state__content">
                <div class="empty-state__icon">
                    <img
                        src="storage/img/No_Product_Found.png"
                        alt="No Product Found"
                    />
                </div>
                <div class="empty-state__message">
                    No Product Found
                </div>
                <div class="empty-state__help">
                    We can't seem to find any products that match your search
                </div>
            </div>
        </div>
    </div>

    <!-- Pagination -->
    <nav
        v-if="productsPage && products.length > 0"
        class="flex flex-col items-start justify-between p-4 space-y-3 md:flex-row md:items-center md:space-y-0"
        aria-label="Table navigation"
    >
        <span class="text-sm font-normal text-gray-500 dark:text-gray-400">
            Showing
            <span class="font-semibold text-gray-900 dark:text-white">{{
                productsPage.from
            }}</span>
            -
            <span class="font-semibold text-gray-900 dark:text-white">{{
                productsPage.to
            }}</span>
            of
            <span class="font-semibold text-gray-900 dark:text-white">{{
                productsPage.total
            }}</span>
        </span>
        <ul class="inline-flex items-stretch -space-x-px">
            <li>
                <a
                    @click="
                        productsPage.current_page !== 1
                            ? changePage(productsPage.current_page - 1)
                            : null
                    "
                    :class="[
                        'cursor-pointer flex items-center justify-center h-full py-1.5 px-3 ml-0 text-gray-500 bg-white rounded-l-lg border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white',
                        {
                            '!cursor-not-allowed opacity-50':
                                productsPage.current_page === 1,
                        },
                    ]"
                    :tabindex="productsPage.current_page === 1 ? -1 : 0"
                >
                    <span class="sr-only">Previous</span>
                    <svg
                        class="w-5 h-5"
                        aria-hidden="true"
                        fill="currentColor"
                        viewbox="0 0 20 20"
                        xmlns="http://www.w3.org/2000/svg"
                    >
                        <path
                            fill-rule="evenodd"
                            d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                            clip-rule="evenodd"
                        />
                    </svg>
                </a>
            </li>
            <li v-for="page in productsPage.last_page" :key="page">
                <a
                    @click="changePage(page)"
                    :class="[
                        'cursor-pointer flex items-center justify-center px-3 py-2 text-sm leading-tight border',
                        productsPage.current_page === page
                            ? 'z-10 text-blue-600 bg-blue-50 border-blue-300 hover:bg-blue-100 hover:text-blue-700 dark:border-gray-700 dark:bg-gray-700 dark:text-white'
                            : 'text-gray-500 bg-white border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white',
                    ]"
                >
                    {{ page }}
                </a>
            </li>
            <li>
                <a
                    @click="
                        productsPage.current_page !== productsPage.last_page
                            ? changePage(productsPage.current_page + 1)
                            : null
                    "
                    :class="[
                        'cursor-pointer flex items-center justify-center h-full py-1.5 px-3 leading-tight text-gray-500 bg-white rounded-r-lg border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white',
                        {
                            '!cursor-not-allowed opacity-50':
                                productsPage.current_page ===
                                productsPage.last_page,
                        },
                    ]"
                    :tabindex="
                        productsPage.current_page === productsPage.last_page
                            ? -1
                            : 0
                    "
                >
                    <span class="sr-only">Next</span>
                    <svg
                        class="w-5 h-5"
                        aria-hidden="true"
                        fill="currentColor"
                        viewbox="0 0 20 20"
                        xmlns="http://www.w3.org/2000/svg"
                    >
                        <path
                            fill-rule="evenodd"
                            d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                            clip-rule="evenodd"
                        />
                    </svg>
                </a>
            </li>
        </ul>
    </nav>
</template>

<style scoped>
.empty-state__content {
    padding: 48px;
    display: flex;
    align-items: center;
    flex-direction: column;
}
.empty-state__icon {
    width: 200px;
    height: 200px;
    display: flex;
    align-items: center;
    border-radius: 200px;
    justify-content: center;
    background-color: #f7fafc;
    box-shadow: 0px 2px 1px #e1e3ec;
}
.empty-state__icon img {
    width: 170px;
}

.empty-state__message {
    color: #38a169;
    font-size: 1.5rem;
    font-weight: 500;
    margin-top: 0.85rem;
}

.empty-state__help {
    color: #a2a5b9;
    font-size: 0.875rem;
}
</style>
