<script setup>
import { router, useForm } from "@inertiajs/vue3";
import { ref, watch } from "vue";

// Form
const form = useForm({
    name: "",
    slug: "",
    description: "",
    image: "",
    oldName: "",
    errors: {},
});

// Image
const imagePreview = ref(null);
const changeImage = (e) => {
    imagePreview.value = URL.createObjectURL(e.target.files[0]);
    form.image = e.target.files[0];
};

// Add Brand
const FormSubmited = ref(false);
const AddBrand = async () => {
    try {
        router.post("/admin/brands/store", form, {
            preserveScroll: true,
            onSuccess: () => {
                form.reset();
                form.errors = {};
                document.getElementById("defaultModal").click();
                FormSubmited.value = true;
                setTimeout(() => {
                    FormSubmited.value = false;
                }, 3000);
            },
            onError: (error) => {
                form.errors = error || {};
                console.log(error);
            },
        });
    } catch (error) {
        console.log(error);
        document.getElementById("defaultModal").click();
    }
};

// Slug
watch(
    () => form.name,
    (newValue) => {
        if (!form.slug || form.slug === slugify(form.oldName)) {
            form.slug = slugify(newValue);
        }
        form.oldName = newValue;
    }
);
const slugify = (text) => {
    return text
        .toLowerCase()
        .replace(/ /g, "-")
        .replace(/[^\w-]+/g, "");
};

// Watch For Form Changes
watch(
    [
        () => form.name,
        () => form.slug,
        () => form.description,
        () => form.image,
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
            form.errors.name = undefined;
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
            form.errors.slug = undefined;
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
            form.errors.description = undefined;
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
            form.errors.images = undefined;
        }
    }
);
</script>

<template>
    <div
        id="defaultModal"
        tabindex="-1"
        aria-hidden="true"
        class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center !items-start w-full md:inset-0 h-modal md:h-full"
    >
        <div class="relative grid p-4 w-full max-w-2xl h-full md:h-auto">
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
                        Add Brand
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
                <form @submit.prevent="AddBrand()">
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
                                v-model="form.name"
                                :class="[
                                    'border text-sm rounded-lg block w-full p-2.5',
                                    form.errors.name
                                        ? 'bg-red-50 border-red-500 text-red-900 placeholder-red-700 focus:ring-red-500 dark:bg-gray-700 focus:border-red-500 dark:text-red-500 dark:placeholder-red-500 dark:border-red-500'
                                        : 'bg-gray-50 border-gray-300 text-gray-900 focus:ring-blue-600 focus:border-blue-600 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500',
                                ]"
                                placeholder="Type brand name"
                                required=""
                            />
                            <p
                                v-if="form.errors.name"
                                class="mt-2 text-sm text-red-600 dark:text-red-500"
                            >
                                {{ form.errors.name }}
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
                                v-model="form.slug"
                                :class="[
                                    'border text-sm rounded-lg block w-full p-2.5',
                                    form.errors.slug
                                        ? 'bg-red-50 border-red-500 text-red-900 placeholder-red-700 focus:ring-red-500 dark:bg-gray-700 focus:border-red-500 dark:text-red-500 dark:placeholder-red-500 dark:border-red-500'
                                        : 'bg-gray-50 border-gray-300 text-gray-900 focus:ring-blue-600 focus:border-blue-600 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500',
                                ]"
                                placeholder="Type brand slug"
                                required=""
                            />
                            <p
                                v-if="form.errors.slug"
                                class="mt-2 text-sm text-red-600 dark:text-red-500"
                            >
                                {{ form.errors.slug }}
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
                                v-model="form.description"
                                :class="[
                                    'border text-sm rounded-lg block w-full p-2.5',
                                    form.errors.description
                                        ? 'bg-red-50 border-red-500 text-red-900 placeholder-red-700 focus:ring-red-500 dark:bg-gray-700 focus:border-red-500 dark:text-red-500 dark:placeholder-red-500 dark:border-red-500'
                                        : 'bg-gray-50 border-gray-300 text-gray-900 focus:ring-blue-600 focus:border-blue-600 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500',
                                ]"
                                placeholder="Write Brand description here"
                            ></textarea>
                            <p
                                v-if="form.errors.description"
                                class="mt-2 text-sm text-red-600 dark:text-red-500"
                            >
                                {{ form.errors.description }}
                            </p>
                        </div>

                        <div class="sm:col-span-2">
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
                            <div class="mt-2" v-if="imagePreview">
                                <div
                                    class="flex items-center justify-center w-full"
                                >
                                    <img
                                        class="w-40 h-40 rounded-lg object-contain"
                                        :src="imagePreview"
                                        v-if="imagePreview"
                                    />
                                </div>
                            </div>
                            <p
                                v-if="form.errors.image"
                                class="mt-2 text-sm text-red-600 dark:text-red-500"
                            >
                                {{ form.errors.image }}
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
                        Add new brand
                    </button>
                </form>
            </div>
        </div>
    </div>
</template>
