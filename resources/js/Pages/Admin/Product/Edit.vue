<script setup>
import { router, useForm } from "@inertiajs/vue3";
import { usePage } from "@inertiajs/vue3";
import { ref, watch } from "vue";

// Variables
const { categories } = usePage().props;
const { brands } = usePage().props;

// Props
const props = defineProps({
    product: {
        type: Object,
    },
    refreshProducts: {
        type: Function,
        required: true,
    },
});

// Form
const formm = useForm({
    name: props.product?.name || "",
    slug: props.product?.slug || "",
    description: props.product?.description || "",
    price: props.product?.price || "",
    category_id: props.product?.category_id || "",
    brand_id: props.product?.brand_id || "",
    imagesView: [],
    imagesViewOld: props.product?.images || [],
    images: [],
    quantity: props.product?.quantity || "",
    published: props.product?.published || "",
    in_stock: props.product?.in_stock || "",
    errors: {},
});

// formm reset after prop change
watch(() => {
    if (props.product) {
        formm.name = props.product.name;
        formm.slug = props.product.slug;
        formm.description = props.product.description;
        formm.price = props.product.price;
        formm.category_id = props.product.category_id;
        formm.brand_id = props.product.brand_id;
        formm.imagesViewOld = props.product.images;
        formm.quantity = props.product.quantity;
        formm.published = props.product.published;
        formm.in_stock = props.product.in_stock;
    }
});

// Slug
watch(
    formm,
    () => {
        formm.slug = formm.name
            .toLowerCase()
            .replace(/ /g, "-")
            .replace(/[^\w-]+/g, "");
    },
    { deep: true }
);

// Image
const handleFileChange = (file) => {
    formm.imagesView.push(file);
    formm.images = formm.imagesView.map((file) => file.raw);
};

// Delete Single Image
const deleteImage = async (id, index) => {
    try {
        await router.delete("/admin/products/" + id + "/image/delete", {
            preserveScroll: true,
            onSuccess: () => {
                formm.imagesViewOld.splice(index, 1);
            },
            onError: (error) => {
                console.error("Failed to delete image:", error);
            },
        });
    } catch (error) {
        console.error("An error occurred:", error);
    }
};

// Update Product
const FormSubmited = ref(false);
const updateProduct = async () => {
    try {
        await router.post(
            "/admin/products/" + props.product.id + "/update",
            formm,
            {
                preserveScroll: true,
                onSuccess: () => {
                    formm.reset();
                    formm.errors = {};
                    document.getElementById("defaultModalEdit").click();
                    props.refreshProducts();
                    FormSubmited.value = true;
                    setTimeout(() => {
                        FormSubmited.value = false;
                    }, 3000);
                },
                onError: (error) => {
                    formm.errors = error || {};
                    console.log(error);
                },
            }
        );
    } catch (error) {
        console.log(error);
        document.getElementById("defaultModalEdit").click();
    }
};

// Watch For Form Changes
watch(
    [
        () => formm.name,
        () => formm.slug,
        () => formm.description,
        () => formm.price,
        () => formm.brand_id,
        () => formm.category_id,
        () => formm.images,
        () => formm.quantity,
        () => formm.published,
        () => formm.in_stock,
    ],
    (
        [
            newName,
            newSlug,
            newDescription,
            newPrice,
            newBrandId,
            newCategoryId,
            newImages,
            newQuantity,
            newPublished,
            newInStock,
        ],
        [
            oldName,
            oldSlug,
            oldDescription,
            oldPrice,
            oldBrandId,
            oldCategoryId,
            oldImages,
            oldQuantity,
            oldPublished,
            oldInStock,
        ]
    ) => {
        const classError = [
            "bg-red-50",
            "border-red-500",
            "text-red-900",
            "placeholder-red-700",
            "focus:ring-red-500",
            "dark:bg-gray-700",
            "focus:border-red-500",
            "dark:text-red-500",
            "dark:placeholder-red-500",
            "dark:border-red-500",
        ];
        const classSuccess = [
            "bg-gray-50",
            "border-gray-300",
            "text-gray-900",
            "focus:ring-blue-600",
            "focus:border-blue-600",
            "dark:bg-gray-700",
            "dark:border-gray-600",
            "dark:placeholder-gray-400",
            "dark:text-white",
            "dark:focus:ring-blue-500",
            "dark:focus:border-blue-500",
        ];
        if (FormSubmited.value) return;
        if (newName !== oldName) {
            formm.errors.name = undefined;
            const nameElement = document.getElementById("name");
            if (newName === "") {
                nameElement.classList.add(...classError);
                nameElement.classList.remove(...classSuccess);
            } else {
                nameElement.classList.add(...classSuccess);
                nameElement.classList.remove(...classError);
            }
        }
        if (newSlug !== oldSlug) {
            formm.errors.slug = undefined;
            const slugElement = document.getElementById("slug");
            if (newSlug === "") {
                slugElement.classList.add(...classError);
                slugElement.classList.remove(...classSuccess);
            } else {
                slugElement.classList.add(...classSuccess);
                slugElement.classList.remove(...classError);
            }
        }
        if (newDescription !== oldDescription) {
            formm.errors.description = undefined;
            const descriptionElement = document.getElementById("description");
            if (newDescription === "") {
                descriptionElement.classList.add(...classError);
                descriptionElement.classList.remove(...classSuccess);
            } else {
                descriptionElement.classList.add(...classSuccess);
                descriptionElement.classList.remove(...classError);
            }
        }
        if (newPrice !== oldPrice) {
            formm.errors.price = undefined;
            const priceElement = document.getElementById("price");
            if (newPrice === "") {
                priceElement.classList.add(...classError);
                priceElement.classList.remove(...classSuccess);
            } else {
                priceElement.classList.add(...classSuccess);
                priceElement.classList.remove(...classError);
            }
        }
        if (newBrandId !== oldBrandId) {
            formm.errors.brand_id = undefined;
            const brandElement = document.getElementById("brand");
            if (newBrandId === "") {
                brandElement.classList.add(...classError);
                brandElement.classList.remove(...classSuccess);
            } else {
                brandElement.classList.add(...classSuccess);
                brandElement.classList.remove(...classError);
            }
        }
        if (newCategoryId !== oldCategoryId) {
            formm.errors.category_id = undefined;
            const categoryElement = document.getElementById("category");
            if (newCategoryId === "") {
                categoryElement.classList.add(...classError);
                categoryElement.classList.remove(...classSuccess);
            } else {
                categoryElement.classList.add(...classSuccess);
                categoryElement.classList.remove(...classError);
            }
        }
        if (newImages !== oldImages) {
            formm.errors.images = undefined;
        }
        if (newQuantity !== oldQuantity) {
            formm.errors.quantity = undefined;
            const quantityElement = document.getElementById("quantity");
            if (newQuantity === "") {
                quantityElement.classList.add(...classError);
                quantityElement.classList.remove(...classSuccess);
            } else {
                quantityElement.classList.add(...classSuccess);
                quantityElement.classList.remove(...classError);
            }
        }
        if (newPublished !== oldPublished) {
            formm.errors.published = undefined;
            const publishedElement = document.getElementById("published");
            if (newPublished === "") {
                publishedElement.classList.add(...classError);
                publishedElement.classList.remove(...classSuccess);
            } else {
                publishedElement.classList.add(...classSuccess);
                publishedElement.classList.remove(...classError);
            }
        }
        if (newInStock !== oldInStock) {
            formm.errors.in_stock = undefined;
            const inStockElement = document.getElementById("in_stock");
            if (newInStock === "") {
                inStockElement.classList.add(...classError);
                inStockElement.classList.remove(...classSuccess);
            } else {
                inStockElement.classList.add(...classSuccess);
                inStockElement.classList.remove(...classError);
            }
        }
    }
);
</script>

<template>
    <div
        id="defaultModalEdit"
        tabindex="-1"
        aria-hidden="true"
        class="hidden min-w-full overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center !items-start w-full md:inset-0 h-modal md:h-full"
    >
        <div
            v-if="product"
            class="relative p-4 w-full max-w-2xl h-full md:h-auto"
        >
            <!-- Modal content -->
            <div
                class="relative p-4 bg-white rounded-lg shadow dark:bg-gray-800 sm:p-5"
            >
                <!-- Modal header -->
                <div
                    class="flex justify-between items-center pb-4 mb-4 rounded-t border-b sm:mb-5 dark:border-gray-600"
                >
                    <h3
                        class="text-lg font-semibold text-gray-900 dark:text-white"
                    >
                        Edit Product
                    </h3>
                    <button
                        type="button"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white"
                        data-modal-toggle="defaultModal"
                    >
                        <svg
                            aria-hidden="true"
                            class="w-5 h-5"
                            fill="currentColor"
                            viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <path
                                fill-rule="evenodd"
                                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                clip-rule="evenodd"
                            ></path>
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <!-- Modal body -->
                <form @submit.prevent="updateProduct()">
                    <div class="grid gap-4 mb-4 sm:grid-cols-2">
                        <div>
                            <label
                                for="name"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                                >Name</label
                            >
                            <input
                                type="text"
                                name="name"
                                id="name"
                                v-model="formm.name"
                                :class="[
                                    'border text-sm rounded-lg block w-full p-2.5',
                                    formm.errors.name
                                        ? 'bg-red-50 border-red-500 text-red-900 placeholder-red-700 focus:ring-red-500 dark:bg-gray-700 focus:border-red-500 dark:text-red-500 dark:placeholder-red-500 dark:border-red-500'
                                        : 'bg-gray-50 border-gray-300 text-gray-900 focus:ring-blue-600 focus:border-blue-600 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500',
                                ]"
                                placeholder="Type product name"
                                required=""
                            />
                            <p
                                v-if="formm.errors.name"
                                class="mt-2 text-sm text-red-600 dark:text-red-500"
                            >
                                {{ formm.errors.name }}
                            </p>
                        </div>
                        <div>
                            <label
                                for="slug"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                                >Slug</label
                            >
                            <input
                                type="text"
                                name="slug"
                                id="slug"
                                v-model="formm.slug"
                                :class="[
                                    'border text-sm rounded-lg block w-full p-2.5',
                                    formm.errors.slug
                                        ? 'bg-red-50 border-red-500 text-red-900 placeholder-red-700 focus:ring-red-500 dark:bg-gray-700 focus:border-red-500 dark:text-red-500 dark:placeholder-red-500 dark:border-red-500'
                                        : 'bg-gray-50 border-gray-300 text-gray-900 focus:ring-blue-600 focus:border-blue-600 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500',
                                ]"
                                placeholder="Type product slug"
                                required=""
                            />
                            <p
                                v-if="formm.errors.slug"
                                class="mt-2 text-sm text-red-600 dark:text-red-500"
                            >
                                {{ formm.errors.slug }}
                            </p>
                        </div>
                        <div>
                            <label
                                for="price"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                                >Price</label
                            >
                            <input
                                type="number"
                                name="price"
                                id="price"
                                v-model="formm.price"
                                step="any"
                                :class="[
                                    'border text-sm rounded-lg block w-full p-2.5',
                                    formm.errors.price
                                        ? 'bg-red-50 border-red-500 text-red-900 placeholder-red-700 focus:ring-red-500 dark:bg-gray-700 focus:border-red-500 dark:text-red-500 dark:placeholder-red-500 dark:border-red-500'
                                        : 'bg-gray-50 border-gray-300 text-gray-900 focus:ring-blue-600 focus:border-blue-600 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500',
                                ]"
                                placeholder="$2999"
                                required
                            />
                            <p
                                v-if="formm.errors.quantity"
                                class="mt-2 text-sm text-red-600 dark:text-red-500"
                            >
                                {{ formm.errors.quantity }}
                            </p>
                        </div>

                        <div>
                            <label
                                for="quantity"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                                >Quantity</label
                            >
                            <input
                                type="number"
                                name="quantity"
                                id="quantity"
                                v-model="formm.quantity"
                                :class="[
                                    'border text-sm rounded-lg block w-full p-2.5',
                                    formm.errors.quantity
                                        ? 'bg-red-50 border-red-500 text-red-900 placeholder-red-700 focus:ring-red-500 dark:bg-gray-700 focus:border-red-500 dark:text-red-500 dark:placeholder-red-500 dark:border-red-500'
                                        : 'bg-gray-50 border-gray-300 text-gray-900 focus:ring-blue-600 focus:border-blue-600 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500',
                                ]"
                                placeholder="100"
                                required=""
                            />
                            <p
                                v-if="formm.errors.quantity"
                                class="mt-2 text-sm text-red-600 dark:text-red-500"
                            >
                                {{ formm.errors.quantity }}
                            </p>
                        </div>
                        <div>
                            <label
                                for="brand"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                                >Brand</label
                            >
                            <select
                                id="brand"
                                name="brand_id"
                                v-model="formm.brand_id"
                                :class="[
                                    'border text-sm rounded-lg block w-full p-2.5',
                                    formm.errors.brand_id
                                        ? 'bg-red-50 border-red-500 text-red-900 placeholder-red-700 focus:ring-red-500 dark:bg-gray-700 focus:border-red-500 dark:text-red-500 dark:placeholder-red-500 dark:border-red-500'
                                        : 'bg-gray-50 border-gray-300 text-gray-900 focus:ring-blue-600 focus:border-blue-600 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500',
                                ]"
                            >
                                <option selected disabled value="">
                                    Select Brand
                                </option>
                                <option
                                    v-for="brand in brands"
                                    :value="brand.id"
                                >
                                    {{ brand.name }}
                                </option>
                            </select>
                            <p
                                v-if="formm.errors.brand_id"
                                class="mt-2 text-sm text-red-600 dark:text-red-500"
                            >
                                {{ formm.errors.brand_id }}
                            </p>
                        </div>
                        <div>
                            <label
                                for="category"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                                >Category</label
                            >
                            <select
                                id="category"
                                name="category_id"
                                v-model="formm.category_id"
                                :class="[
                                    'border text-sm rounded-lg block w-full p-2.5',
                                    formm.errors.category_id
                                        ? 'bg-red-50 border-red-500 text-red-900 placeholder-red-700 focus:ring-red-500 dark:bg-gray-700 focus:border-red-500 dark:text-red-500 dark:placeholder-red-500 dark:border-red-500'
                                        : 'bg-gray-50 border-gray-300 text-gray-900 focus:ring-blue-600 focus:border-blue-600 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500',
                                ]"
                            >
                                <option selected disabled value="">
                                    Select Category
                                </option>
                                <option
                                    v-for="category in categories"
                                    :value="category.id"
                                >
                                    {{ category.name }}
                                </option>
                            </select>
                            <p
                                v-if="formm.errors.category_id"
                                class="mt-2 text-sm text-red-600 dark:text-red-500"
                            >
                                {{ formm.errors.category_id }}
                            </p>
                        </div>
                        <div class="sm:col-span-2">
                            <label
                                for="description"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                                >Description</label
                            >
                            <textarea
                                id="description"
                                rows="4"
                                name="description"
                                v-model="formm.description"
                                :class="[
                                    'border text-sm rounded-lg block w-full p-2.5',
                                    formm.errors.description
                                        ? 'bg-red-50 border-red-500 text-red-900 placeholder-red-700 focus:ring-red-500 dark:bg-gray-700 focus:border-red-500 dark:text-red-500 dark:placeholder-red-500 dark:border-red-500'
                                        : 'bg-gray-50 border-gray-300 text-gray-900 focus:ring-blue-600 focus:border-blue-600 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500',
                                ]"
                                placeholder="Write product description here"
                            ></textarea>
                            <p
                                v-if="formm.errors.description"
                                class="mt-2 text-sm text-red-600 dark:text-red-500"
                            >
                                {{ formm.errors.description }}
                            </p>
                        </div>

                        <label class="inline-flex items-center cursor-pointer">
                            <input
                                type="checkbox"
                                name="in_stock"
                                id="in_stock"
                                class="sr-only peer"
                                :checked="formm.in_stock"
                                @change="formm.in_stock = !formm.in_stock"
                            />
                            <div
                                class="relative w-11 h-6 bg-gray-200 rounded-full peer peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"
                            ></div>
                            <span
                                class="ms-3 text-sm font-medium text-gray-900 dark:text-gray-300"
                                >In stock</span
                            >
                            <p
                                v-if="formm.errors.in_stock"
                                class="mt-2 text-sm text-red-600 dark:text-red-500"
                            >
                                {{ formm.errors.in_stock }}
                            </p>
                        </label>

                        <label class="inline-flex items-center cursor-pointer">
                            <input
                                type="checkbox"
                                name="published"
                                id="published"
                                class="sr-only peer"
                                :checked="formm.published"
                                @change="formm.published = !formm.published"
                            />
                            <div
                                class="relative w-11 h-6 bg-gray-200 rounded-full peer peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"
                            ></div>
                            <span
                                class="ms-3 text-sm font-medium text-gray-900 dark:text-gray-300"
                                >Published</span
                            >
                            <p
                                v-if="formm.errors.published"
                                class="mt-2 text-sm text-red-600 dark:text-red-500"
                            >
                                {{ formm.errors.published }}
                            </p>
                        </label>

                        <div class="sm:col-span-2">
                            <!-- Upload images -->
                            <label
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                                for="multiple_files"
                                >Upload Images</label
                            >
                            <el-upload
                                id="images"
                                v-model:file-list="formm.imagesView"
                                multiple
                                list-type="picture-card"
                                :on-change="handleFileChange"
                            >
                                <el-icon></el-icon>
                            </el-upload>
                            <!-- End upload images -->
                            <!-- list of images for selected product -->
                            <div
                                class="sm:col-span-2 my-8"
                                v-if="formm.imagesViewOld.length > 0"
                            >
                                <label
                                    class="block mb-2 text font-medium text-gray-900 dark:text-white"
                                    for="multiple_files"
                                    >Images List
                                </label>
                                <div class="flex flex-nowrap">
                                    <div
                                        v-for="(
                                            image, index
                                        ) in formm.imagesViewOld"
                                        :key="image.id"
                                        class="relative w-40 h-32 bg-slate-300 rounded mr-2"
                                    >
                                        <img
                                            class="w-40 h-32 rounded object-contain"
                                            :src="`/storage/${image.image}`"
                                        />
                                        <span
                                            class="absolute top-0 right-8 transform -translate-y-1/2 w-6 h-6 bg-red-400 border-2 border-white dark:border-gray-800 rounded-full"
                                        >
                                            <span
                                                @click="
                                                    deleteImage(image.id, index)
                                                "
                                                class="text-white text-s font-bold absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 cursor-pointer"
                                                >x</span
                                            >
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <!-- end -->
                            <p
                                v-if="formm.errors.images"
                                class="mt-2 text-sm text-red-600 dark:text-red-500"
                            >
                                {{ formm.errors.images }}
                            </p>
                        </div>
                    </div>
                    <button
                        type="submit"
                        class="text-white inline-flex items-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                    >
                        <svg
                            class="mr-1 -ml-1 w-6 h-6"
                            fill="currentColor"
                            viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <path
                                fill-rule="evenodd"
                                d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                                clip-rule="evenodd"
                            ></path>
                        </svg>
                        Update product
                    </button>
                </form>
            </div>
        </div>
    </div>
</template>
