<script setup>
import { router, useForm } from "@inertiajs/vue3";
import { ref, watch } from "vue";

// Props
const props = defineProps({
    category: {
        type: Object,
    },
});

// Form
const formm = useForm({
    name: props.category?.name || "",
    slug: props.category?.slug || "",
    description: props.category?.description || "",
    image: props.category?.image || "",
    oldName: "",
    errors: {},
});

// Image
const imagePreview = ref(null);

const changeImage = (e) => {
    imagePreview.value = URL.createObjectURL(e.target.files[0]);
    formm.image = e.target.files[0];
};

// formm reset after prop change
watch(
    () => props.category,
    (newCategory) => {
        if (newCategory) {
            formm.name = newCategory.name;
            formm.slug = newCategory.slug;
            formm.description = newCategory.description;
            imagePreview.value = newCategory.image;
        }
    },
    { deep: true }
);

// Slug
watch(
    () => formm.name,
    (newValue) => {
        if (!formm.slug || formm.slug === slugify(formm.oldName)) {
            formm.slug = slugify(newValue);
        }
        formm.oldName = newValue;
    }
);
const slugify = (text) => {
    return text
        .toLowerCase()
        .replace(/ /g, "-")
        .replace(/[^\w-]+/g, "");
};

// Update Category
const FormSubmited = ref(false);
const updateCategory = async () => {
    try {
        await router.post(
            "/admin/categories/" + props.category.id + "/update",
            { _method: "put", ...formm },
            {
                preserveScroll: true,
                onSuccess: () => {
                    formm.reset();
                    formm.errors = {};
                    document.getElementById("defaultModalEdit").click();
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
        () => formm.image,
    ],
    (
        [newName, newSlug, newDescription, newImage],
        [oldName, oldSlug, oldDescription, oldImage]
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
        if (newImage !== oldImage) {
            formm.errors.images = undefined;
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
            v-if="category"
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
                        Edit Category
                    </h3>
                    <button
                        type="button"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white"
                        data-modal-toggle="defaultModalEdit"
                        onclick='document.getElementById("defaultModalEdit").click()'
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
                <form
                    @submit.prevent="updateCategory()"
                    enctype="multipart/form-data"
                >
                    <input type="hidden" name="_method" value="PUT" />
                    <input
                        type="hidden"
                        name="_token"
                        :value="$page.props.csrf"
                    />
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
                                placeholder="Type category name"
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
                                placeholder="Type category slug"
                                required=""
                            />
                            <p
                                v-if="formm.errors.slug"
                                class="mt-2 text-sm text-red-600 dark:text-red-500"
                            >
                                {{ formm.errors.slug }}
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
                                placeholder="Write category description here"
                            ></textarea>
                            <p
                                v-if="formm.errors.description"
                                class="mt-2 text-sm text-red-600 dark:text-red-500"
                            >
                                {{ formm.errors.description }}
                            </p>
                        </div>

                        <div class="sm:col-span-2">
                            <!-- Upload images -->
                            <label
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                                for="file_input"
                                >Upload file</label
                            >
                            <input
                                class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
                                aria-describedby="file_input_help"
                                id="file_input"
                                type="file"
                                name="image"
                                @input="changeImage($event)"
                            />
                            <p
                                class="mt-1 text-sm text-gray-500 dark:text-gray-300"
                                id="file_input_help"
                            >
                                SVG, PNG, JPG or GIF (MAX. 2MB).
                            </p>
                            <div
                                class="mt-2"
                                v-if="formm.image || imagePreview"
                            >
                                <div
                                    class="flex items-center justify-center w-full"
                                >
                                    <img
                                        class="w-40 h-40 rounded-lg object-contain"
                                        :src="imagePreview"
                                        v-if="imagePreview"
                                    />
                                    <img
                                        class="w-40 h-40 rounded-lg object-contain"
                                        :src="`/storage/${formm.image}`"
                                        v-else
                                    />
                                </div>
                            </div>
                            <p
                                v-if="formm.errors.image"
                                class="mt-2 text-sm text-red-600 dark:text-red-500"
                            >
                                {{ formm.errors.image }}
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
                        Update category
                    </button>
                </form>
            </div>
        </div>
    </div>
</template>
