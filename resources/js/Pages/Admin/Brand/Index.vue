<script setup>
import AdminLayout from "@/Pages/Admin/Layouts/AdminLayout.vue";
import { router, usePage } from "@inertiajs/vue3";
import Create from "./Create.vue";
import { computed, ref, watch } from "vue";
import Edit from "./Edit.vue";

const brandsPage = computed(() => usePage().props.brands);
const brands = computed(() => brandsPage.value.data);

// Pagination
const changePage = (page) => {
    router.visit(`/admin/brands?page=${page}`, {
        preserveState: false,
        preserveScroll: true,
    });
};

// Edit Brand
const editModalOpen = ref(false);
const selectedBrand = ref(null);

const openEditModal = (id) => {
    selectedBrand.value = brands.value.find((brand) => brand.id === id);
    editModalOpen.value = true;
};

// Delete Brand
const deleteBrand = async (id) => {
    try {
        router.delete("/admin/brands/" + id + "/destroy", {
            preserveScroll: true,
            onSuccess: () => {
                brands.value = brands.value.filter((brand) => brand.id !== id);
            },
            onError: (error) => {
                console.log(error);
            },
        });
    } catch (error) {
        console.log(error);
    }
};

// Delete Multiple Brands
const selectedBrands = ref([]);
const selectAll = ref(false);

const toggleAllCheckboxes = () => {
    if (selectAll.value) {
        selectedBrands.value = brands.value.map((brand) => brand.id);
    } else {
        selectedBrands.value = [];
    }
};

const deleteAll = async () => {
    if (selectedBrands.value.length > 0) {
        try {
            router.delete(
                "/admin/brands/" + selectedBrands.value + "/multiple/delete",
                {
                    preserveScroll: true,
                    onSuccess: () => {
                        brands.value = brands.value.filter(
                            (brand) => !selectedBrands.value.includes(brand.id)
                        );
                        selectedBrands.value = [];
                        selectAll.value = false;
                    },
                    onError: (error) => {
                        console.log(error);
                    },
                }
            );
        } catch (error) {
            console.log(error);
        }
    }
};

watch(selectedBrands, () => {
    selectAll.value = selectedBrands.value.length === brands.value.length;
});
</script>

<template>
    <AdminLayout title="Brands">
        <!-- Main modal -->
        <Create />

        <!-- Main modal -->
        <Edit
            v-if="openEditModal"
            :open="editModalOpen"
            :brand="selectedBrand"
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
                            class="flex w-full flex-col flex-shrink-0 space-y-3 md:flex-row md:items-center lg:justify-between md:space-y-0 md:space-x-3"
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
                                Add new brand
                            </button>
                            <button
                                type="button"
                                @click="deleteAll"
                                v-if="selectedBrands.length > 0"
                                class="flex items-center justify-center px-4 py-2 text-sm font-medium text-white rounded-lg bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 dark:bg-red-600 dark:hover:bg-red-700 focus:outline-none dark:focus:ring-red-800"
                            >
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    width="24"
                                    height="24"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    class="lucide lucide-trash-2 h-5 w-5 mr-2"
                                >
                                    <path d="M3 6h18" />
                                    <path
                                        d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"
                                    />
                                    <path
                                        d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"
                                    />
                                    <line x1="10" x2="10" y1="11" y2="17" />
                                    <line x1="14" x2="14" y1="11" y2="17" />
                                </svg>
                                Delete
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
                                                v-model="selectAll"
                                                @change="toggleAllCheckboxes"
                                                class="w-4 h-4 bg-gray-100 border-gray-300 rounded text-blue-600 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                                            />
                                            <label
                                                for="checkbox-all"
                                                class="sr-only"
                                                >checkbox</label
                                            >
                                        </div>
                                    </th>
                                    <th scope="col" class="px-4 py-3">Brand</th>
                                    <th scope="col" class="px-4 py-3">
                                        Description
                                    </th>
                                    <th scope="col" class="px-4 py-3">
                                        Last Update
                                    </th>
                                    <th scope="col" class="px-4 py-3 text-end">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody v-if="brands.length > 0">
                                <tr
                                    v-for="brand in brands"
                                    :key="brand.id"
                                    class="border-b dark:border-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700"
                                >
                                    <td class="w-4 px-4 py-3">
                                        <div class="flex items-center">
                                            <input
                                                :id="`checkbox-table-search-${brand.id}`"
                                                type="checkbox"
                                                v-model="selectedBrands"
                                                :value="brand.id"
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
                                    <td
                                        scope="row"
                                        class="px-4 py-2 font-medium text-gray-900 max-w-24 truncate dark:text-white"
                                    >
                                        <div class="flex items-center">
                                            <img
                                                v-if="brand.image"
                                                :src="`/storage/${brand.image}`"
                                                :alt="brand.name"
                                                class="w-8 h-8 mr-3 rounded-md object-contain"
                                            />
                                            <img
                                                v-else
                                                src="https://via.placeholder.com/150"
                                                :alt="brand.name"
                                                class="w-8 h-8 mr-3 rounded-md object-contain"
                                            />
                                            <span class="truncate"
                                                >{{ brand.name }}
                                            </span>
                                        </div>
                                    </td>
                                    <td class="px-4 py-2 max-w-24 truncate">
                                        {{ brand.description }}
                                    </td>
                                    <td
                                        class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap dark:text-white"
                                    >
                                        {{ brand.updated_at.split("T")[0] }}
                                    </td>
                                    <td class="px-4 py-3">
                                        <div
                                            class="flex items-center justify-end"
                                        >
                                            <button
                                                :id="`${brand.id}-dropdown-button`"
                                                :data-dropdown-toggle="`${brand.id}-dropdown`"
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
                                                :id="`${brand.id}-dropdown`"
                                                class="hidden z-10 w-44 bg-white rounded divide-y divide-gray-100 shadow dark:bg-gray-700 dark:divide-gray-600"
                                            >
                                                <ul
                                                    class="py-1 text-sm text-gray-700 dark:text-gray-200"
                                                    :aria-labelledby="`${brand.id}-dropdown-button`"
                                                >
                                                    <li>
                                                        <a
                                                            href="#"
                                                            class="block py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white"
                                                            ><span
                                                                class="flex justify-start items-center gap-2"
                                                            >
                                                                <svg
                                                                    xmlns="http://www.w3.org/2000/svg"
                                                                    width="16"
                                                                    height="16"
                                                                    viewBox="0 0 24 24"
                                                                    fill="none"
                                                                    stroke="currentColor"
                                                                    stroke-width="2"
                                                                    stroke-linecap="round"
                                                                    stroke-linejoin="round"
                                                                    class="lucide lucide-external-link"
                                                                >
                                                                    <path
                                                                        d="M15 3h6v6"
                                                                    />
                                                                    <path
                                                                        d="M10 14 21 3"
                                                                    />
                                                                    <path
                                                                        d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"
                                                                    />
                                                                </svg>
                                                                <span
                                                                    >Show</span
                                                                ></span
                                                            ></a
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
                                                                    brand.id
                                                                )
                                                            "
                                                        >
                                                            <span
                                                                class="flex justify-start items-center gap-2"
                                                            >
                                                                <svg
                                                                    xmlns="http://www.w3.org/2000/svg"
                                                                    width="16"
                                                                    height="16"
                                                                    viewBox="0 0 24 24"
                                                                    fill="none"
                                                                    stroke="currentColor"
                                                                    stroke-width="2"
                                                                    stroke-linecap="round"
                                                                    stroke-linejoin="round"
                                                                    class="lucide lucide-pencil"
                                                                >
                                                                    <path
                                                                        d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z"
                                                                    />
                                                                    <path
                                                                        d="m15 5 4 4"
                                                                    />
                                                                </svg>
                                                                <span
                                                                    >Edit</span
                                                                ></span
                                                            >
                                                        </a>
                                                    </li>
                                                </ul>
                                                <div class="py-1">
                                                    <a
                                                        @click="
                                                            deleteBrand(
                                                                brand.id
                                                            )
                                                        "
                                                        class="block py-2 px-4 text-sm text-red-700 hover:bg-red-100 dark:hover:bg-red-600 dark:text-gray-200 dark:hover:text-white cursor-pointer"
                                                    >
                                                        <span
                                                            class="flex justify-start items-center gap-2"
                                                        >
                                                            <svg
                                                                xmlns="http://www.w3.org/2000/svg"
                                                                width="16"
                                                                height="16"
                                                                viewBox="0 0 24 24"
                                                                fill="none"
                                                                stroke="currentColor"
                                                                stroke-width="2"
                                                                stroke-linecap="round"
                                                                stroke-linejoin="round"
                                                                class="lucide lucide-trash"
                                                            >
                                                                <path
                                                                    d="M3 6h18"
                                                                />
                                                                <path
                                                                    d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"
                                                                />
                                                                <path
                                                                    d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"
                                                                />
                                                            </svg>
                                                            <span
                                                                >Delete</span
                                                            ></span
                                                        >
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                            <tbody
                                v-else
                                class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800"
                            >
                                <tr class="text-gray-700 dark:text-gray-400">
                                    <td
                                        colspan="4"
                                        class="px-4 py-3 text-center"
                                    >
                                        No brands found
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
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
                                >{{ brandsPage.from }}</span
                            >
                            -
                            <span
                                class="font-semibold text-gray-900 dark:text-white"
                                >{{ brandsPage.to }}</span
                            >
                            of
                            <span
                                class="font-semibold text-gray-900 dark:text-white"
                                >{{ brandsPage.total }}</span
                            >
                        </span>
                        <ul class="inline-flex items-stretch -space-x-px">
                            <li>
                                <a
                                    @click="
                                        brandsPage.current_page !== 1
                                            ? changePage(
                                                  brandsPage.current_page - 1
                                              )
                                            : null
                                    "
                                    :class="[
                                        'cursor-pointer flex items-center justify-center h-full py-1.5 px-3 ml-0 text-gray-500 bg-white rounded-l-lg border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white',
                                        {
                                            '!cursor-not-allowed opacity-50':
                                                brandsPage.current_page === 1,
                                        },
                                    ]"
                                    :tabindex="
                                        brandsPage.current_page === 1 ? -1 : 0
                                    "
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
                            <li
                                v-for="page in brandsPage.last_page"
                                :key="page"
                            >
                                <a
                                    @click="changePage(page)"
                                    :class="[
                                        'cursor-pointer flex items-center justify-center px-3 py-2 text-sm leading-tight border',
                                        brandsPage.current_page === page
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
                                        brandsPage.current_page !==
                                        brandsPage.last_page
                                            ? changePage(
                                                  brandsPage.current_page + 1
                                              )
                                            : null
                                    "
                                    :class="[
                                        'cursor-pointer flex items-center justify-center h-full py-1.5 px-3 leading-tight text-gray-500 bg-white rounded-r-lg border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white',
                                        {
                                            '!cursor-not-allowed opacity-50':
                                                brandsPage.current_page ===
                                                brandsPage.last_page,
                                        },
                                    ]"
                                    :tabindex="
                                        brandsPage.current_page ===
                                        brandsPage.last_page
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
                </div>
            </div>
        </section>
    </AdminLayout>
</template>
