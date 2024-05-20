<script setup>
import AdminLayout from "@/Pages/Admin/Layouts/AdminLayout.vue";
import { router, usePage } from "@inertiajs/vue3";
import Create from "./Create.vue";
import { ref, watch } from "vue";
import Edit from "./Edit.vue";

const products = ref(usePage().props.products);

// Refresh products
const refreshProducts = () => {
    products.value = usePage().props.products;
};

// Change Published
const ChangePublished = async (id) => {
    try {
        const productIndex = products.value.findIndex((p) => p.id === id);
        if (productIndex !== -1) {
            // Toggle the published status immediately in the UI
            products.value[productIndex].published =
                !products.value[productIndex].published;
        }
        await router.post(
            "/admin/products/" + id + "/published",
            {},
            {
                preserveState: true,
                preserveScroll: true,
                scroll: false,
                onError: (error) => {
                    if (productIndex !== -1) {
                        products.value[productIndex].published =
                            !products.value[productIndex].published;
                    }
                    console.log(error);
                },
            }
        );
    } catch (error) {
        console.log(error);
    }
};


// Edit Product
const editModalOpen = ref(false);
const selectedProduct = ref(null);

const openEditModal = (id) => {
    selectedProduct.value = products.value.find(product => product.id === id);
    editModalOpen.value = true;
};
</script>

<template>
    <AdminLayout title="Products">
        <!-- Main modal -->
        <Create :refreshProducts="refreshProducts" />

        <!-- Main modal -->
        <Edit
            v-if="openEditModal"
            :open="editModalOpen"
            :product="selectedProduct"
            :refreshProducts="refreshProducts"
            @close="editModalOpen = false"
        />

        <section class="bg-gray-50 dark:bg-gray-900 py-3 sm:py-5">
            <div class="px-4 mx-auto max-w-screen-2xl lg:px-12">
                <div
                    class="relative overflow-hidden bg-white shadow-md dark:bg-gray-800 sm:rounded-lg"
                >
                    <div
                        class="flex flex-col px-4 py-3 space-y-3 lg:flex-row lg:items-center lg:justify-between lg:space-y-0 lg:space-x-4"
                    >
                        <div
                            class="flex flex-col flex-shrink-0 space-y-3 md:flex-row md:items-center lg:justify-end md:space-y-0 md:space-x-3"
                        >
                            <!-- Modal toggle -->
                            <button
                                type="button"
                                id="defaultModalButton"
                                data-modal-target="defaultModal"
                                data-modal-toggle="defaultModal"
                                class="flex items-center justify-center px-4 py-2 text-sm font-medium text-white rounded-lg bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800"
                            >
                                <svg
                                    class="h-5 w-5 mr-2"
                                    fill="currentColor"
                                    viewbox="0 0 20 20"
                                    xmlns="http://www.w3.org/2000/svg"
                                    aria-hidden="true"
                                >
                                    <path
                                        clip-rule="evenodd"
                                        fill-rule="evenodd"
                                        d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"
                                    />
                                </svg>
                                Add new product
                            </button>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table
                            class="w-full text-sm text-left text-gray-500 dark:text-gray-400"
                        >
                            <thead
                                class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400"
                            >
                                <tr>
                                    <th scope="col" class="p-4">
                                        <div class="flex items-center">
                                            <input
                                                id="checkbox-all"
                                                type="checkbox"
                                                class="w-4 h-4 bg-gray-100 border-gray-300 rounded text-blue-600 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                                            />
                                            <label
                                                for="checkbox-all"
                                                class="sr-only"
                                                >checkbox</label
                                            >
                                        </div>
                                    </th>
                                    <th scope="col" class="px-4 py-3">
                                        Product
                                    </th>
                                    <th scope="col" class="px-4 py-3">
                                        Category
                                    </th>
                                    <th scope="col" class="px-4 py-3">Brand</th>
                                    <th scope="col" class="px-4 py-3">Stock</th>
                                    <th scope="col" class="px-4 py-3">In Stock</th>
                                    <th scope="col" class="px-4 py-3">
                                        Published
                                    </th>
                                    <th scope="col" class="px-4 py-3">Price</th>
                                    <th scope="col" class="px-4 py-3">
                                        Last Update
                                    </th>
                                    <th scope="col" class="px-4 py-3">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="product in products"
                                    :key="product.id"
                                    class="border-b dark:border-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700"
                                >
                                    <td class="w-4 px-4 py-3">
                                        <div class="flex items-center">
                                            <input
                                                id="checkbox-table-search-1"
                                                type="checkbox"
                                                onclick="event.stopPropagation()"
                                                class="w-4 h-4 bg-gray-100 border-gray-300 rounded text-blue-600 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                                            />
                                            <label
                                                for="checkbox-table-search-1"
                                                class="sr-only"
                                                >checkbox</label
                                            >
                                        </div>
                                    </td>
                                    <th
                                        scope="row"
                                        class="flex items-center px-4 py-2 font-medium text-gray-900 whitespace-nowrap dark:text-white"
                                    >
                                        <img
                                            src="https://flowbite.s3.amazonaws.com/blocks/application-ui/products/imac-front-image.png"
                                            alt="iMac Front Image"
                                            class="w-auto h-8 mr-3"
                                        />
                                        <span>{{ product.name }} </span>
                                    </th>
                                    <td class="px-4 py-2">
                                        <span
                                            class="bg-blue-100 text-blue-800 text-xs font-medium px-2 py-0.5 rounded dark:bg-blue-900 dark:text-blue-300"
                                            >{{ product.category.name }}</span
                                        >
                                    </td>
                                    <td class="px-4 py-2">
                                        <span
                                            class="bg-purple-100 text-purple-800 text-xs font-medium px-2 py-0.5 rounded dark:bg-purple-900 dark:text-purple-300"
                                            >{{ product.brand.name }}</span
                                        >
                                    </td>
                                    <td
                                        class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap dark:text-white"
                                    >
                                        <div class="flex items-center">
                                            <div
                                                :class="[
                                                    'inline-block w-4 h-4 mr-2 rounded-full',
                                                    product.quantity <= 20
                                                        ? product.quantity <= 10
                                                            ? 'bg-red-500'
                                                            : 'bg-yellow-500'
                                                        : 'bg-green-500',
                                                ]"
                                            ></div>
                                            {{ product.quantity }}
                                        </div>
                                    </td>
                                    <td class="px-4 py-2">
                                        <span
                                            :class="[
                                                    'text-xs font-medium px-2 py-0.5 rounded',
                                                     product.in_stock
                                                            ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300'
                                                            : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300',
                                                ]"
                                            >{{ product.in_stock ? 'Yes' : 'No' }}</span
                                        >
                                    </td>
                                    <td
                                        class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap dark:text-white"
                                    >
                                        <label
                                            class="inline-flex items-center me-5 cursor-pointer"
                                        >
                                            <form @submit.prevent>
                                                <input
                                                    type="checkbox"
                                                    class="sr-only peer"
                                                    :checked="product.published"
                                                    @change="
                                                        ChangePublished(
                                                            product.id
                                                        )
                                                    "
                                                />
                                                <div
                                                    :class="[
                                                        'relative w-11 h-6 rounded-full peer-focus:ring-4  peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content after:absolute after:top-0.5 after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all',
                                                        product.published
                                                            ? ' bg-gray-200 dark:bg-gray-700 peer-focus:ring-green-300 dark:peer-focus:ring-green-800 dark:border-gray-600 peer-checked:bg-green-600'
                                                            : 'bg-red-200 dark:bg-red-700 peer-focus:ring-red-300 dark:peer-focus:ring-red-800 dark:border-gray-600 peer-checked:bg-green-600',
                                                    ]"
                                                ></div>
                                            </form>
                                        </label>
                                    </td>
                                    <td class="px-4 py-2">
                                        ${{ product.price }}
                                    </td>
                                    <td
                                        class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap dark:text-white"
                                    >
                                        {{ product.updated_at.slice(0, 10) }}
                                    </td>
                                    <td
                                        class="px-4 py-3 flex items-center justify-end"
                                    >
                                        <button
                                            :id="`${product.id}-dropdown-button`"
                                            :data-dropdown-toggle="`${product.id}-dropdown`"
                                            class="inline-flex items-center p-0.5 text-sm font-medium text-center text-gray-500 hover:text-gray-800 rounded-lg focus:outline-none dark:text-gray-400 dark:hover:text-gray-100"
                                            type="button"
                                        >
                                            <svg
                                                class="w-5 h-5"
                                                aria-hidden="true"
                                                fill="currentColor"
                                                viewbox="0 0 20 20"
                                                xmlns="http://www.w3.org/2000/svg"
                                            >
                                                <path
                                                    d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z"
                                                />
                                            </svg>
                                        </button>
                                        <div
                                            :id="`${product.id}-dropdown`"
                                            class="hidden z-10 w-44 bg-white rounded divide-y divide-gray-100 shadow dark:bg-gray-700 dark:divide-gray-600"
                                        >
                                            <ul
                                                class="py-1 text-sm text-gray-700 dark:text-gray-200"
                                                :aria-labelledby="`${product.id}-dropdown-button`"
                                            >
                                                <li>
                                                    <a
                                                        href="#"
                                                        class="block py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white"
                                                        >Show</a
                                                    >
                                                </li>
                                                <li>
                                                    <!-- Modal toggle -->
                                                    <a
                                                        type="button"
                                                        id="defaultModalButtonEdit"
                                                        data-modal-target="defaultModalEdit"
                                                        data-modal-toggle="defaultModalEdit"
                                                        class="block py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white cursor-pointer"
                                                        @click="
                                                            openEditModal(
                                                                product.id
                                                            )
                                                        "
                                                        >Edit</a
                                                    >
                                                </li>
                                            </ul>
                                            <div class="py-1">
                                                <a
                                                    href="#"
                                                    class="block py-2 px-4 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white"
                                                    >Delete</a
                                                >
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <nav
                        class="flex flex-col items-start justify-between p-4 space-y-3 md:flex-row md:items-center md:space-y-0"
                        aria-label="Table navigation"
                    >
                        <span
                            class="text-sm font-normal text-gray-500 dark:text-gray-400"
                        >
                            Showing
                            <span
                                class="font-semibold text-gray-900 dark:text-white"
                                >1-10</span
                            >
                            of
                            <span
                                class="font-semibold text-gray-900 dark:text-white"
                                >1000</span
                            >
                        </span>
                        <ul class="inline-flex items-stretch -space-x-px">
                            <li>
                                <a
                                    href="#"
                                    class="flex items-center justify-center h-full py-1.5 px-3 ml-0 text-gray-500 bg-white rounded-l-lg border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white"
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
                            <li>
                                <a
                                    href="#"
                                    class="flex items-center justify-center px-3 py-2 text-sm leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white"
                                    >1</a
                                >
                            </li>
                            <li>
                                <a
                                    href="#"
                                    class="flex items-center justify-center px-3 py-2 text-sm leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white"
                                    >2</a
                                >
                            </li>
                            <li>
                                <a
                                    href="#"
                                    aria-current="page"
                                    class="z-10 flex items-center justify-center px-3 py-2 text-sm leading-tight border text-blue-600 bg-blue-50 border-blue-300 hover:bg-blue-100 hover:text-blue-700 dark:border-gray-700 dark:bg-gray-700 dark:text-white"
                                    >3</a
                                >
                            </li>
                            <li>
                                <a
                                    href="#"
                                    class="flex items-center justify-center px-3 py-2 text-sm leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white"
                                    >...</a
                                >
                            </li>
                            <li>
                                <a
                                    href="#"
                                    class="flex items-center justify-center px-3 py-2 text-sm leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white"
                                    >100</a
                                >
                            </li>
                            <li>
                                <a
                                    href="#"
                                    class="flex items-center justify-center h-full py-1.5 px-3 leading-tight text-gray-500 bg-white rounded-r-lg border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white"
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
                </div>
            </div>
        </section>
    </AdminLayout>
</template>
