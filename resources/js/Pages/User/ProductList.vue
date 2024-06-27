<script setup>
import UserLayout from "./Layouts/UserLayout.vue";
import { computed, onMounted, ref } from "vue";
import {
    Dialog,
    DialogPanel,
    Disclosure,
    DisclosureButton,
    DisclosurePanel,
    Menu,
    MenuButton,
    MenuItem,
    MenuItems,
    TransitionChild,
    TransitionRoot,
} from "@headlessui/vue";
import { XMarkIcon } from "@heroicons/vue/24/outline";
import {
    ChevronDownIcon,
    FunnelIcon,
    MinusIcon,
    PlusIcon,
    Squares2X2Icon,
} from "@heroicons/vue/20/solid";
import Product from "./Components/Product.vue";
import { Link, useForm, usePage } from "@inertiajs/vue3";

const productsPage = computed(() => usePage().props.products);
const products = computed(() => productsPage.value.data);
const categories = computed(() => usePage().props.categories);
const brands = computed(() => usePage().props.brands);

const filtersForm = useForm({
    category: [],
    brand: [],
    sort: "",
    order: "",
    search: "",
    price: { min: "", max: "" },
});

const sortOptions = [
    { name: "Newest", href: "?sort=created_at&order=desc", current: false },
    { name: "Oldest", href: "?sort=created_at&order=asc", current: false },
    {
        name: "Price: Low to High",
        href: "?sort=price&order=asc",
        current: false,
    },
    {
        name: "Price: High to Low",
        href: "?sort=price&order=desc",
        current: false,
    },
];
const subCategories = [
    { name: "Totes", href: "#" },
    { name: "Backpacks", href: "#" },
    { name: "Travel Bags", href: "#" },
    { name: "Hip Bags", href: "#" },
    { name: "Laptop Sleeves", href: "#" },
];
const filters = [
    {
        id: "category",
        name: "Category",
        options: categories.value.map((category) => ({
            value: category.id,
            label: category.name,
            checked: false,
        })),
    },
    {
        id: "brand",
        name: "Brand",
        options: brands.value.map((brand) => ({
            value: brand.id,
            label: brand.name,
            checked: false,
        })),
    },
];

const mobileFiltersOpen = ref(false);

const minprice = ref(0);
const maxprice = ref(1000);
const min = ref(0);
const max = ref(1000);
const minthumb = ref(0);
const maxthumb = ref(0);

function mintrigger() {
  minprice.value = Math.min(minprice.value, maxprice.value - 100);
  minthumb.value = ((minprice.value - min.value) / (max.value - min.value)) * 100;
}

function maxtrigger() {
  maxprice.value = Math.max(maxprice.value, minprice.value + 100);
  maxthumb.value = 100 - (((maxprice.value - min.value) / (max.value - min.value)) * 100);
}

onMounted(() => {
  mintrigger();
  maxtrigger();
});

const changePrice = () => {
  filtersForm.price.min = minprice.value;
  filtersForm.price.max = maxprice.value;
  filtersForm.transform((data)=>({
    ...data,
    price: [data.price.min, data.price.max],
  })).get('products', {
    preserveState: true,
    replace: true,
  }
  );
}
</script>

<template>
    <UserLayout title="Products">
        <div class="bg-white">
            <div>
                <!-- Mobile filter dialog -->
                <TransitionRoot as="template" :show="mobileFiltersOpen">
                    <Dialog
                        class="relative z-40 lg:hidden"
                        @close="mobileFiltersOpen = false"
                    >
                        <TransitionChild
                            as="template"
                            enter="transition-opacity ease-linear duration-300"
                            enter-from="opacity-0"
                            enter-to="opacity-100"
                            leave="transition-opacity ease-linear duration-300"
                            leave-from="opacity-100"
                            leave-to="opacity-0"
                        >
                            <div class="fixed inset-0 bg-black bg-opacity-25" />
                        </TransitionChild>

                        <div class="fixed inset-0 z-40 flex">
                            <TransitionChild
                                as="template"
                                enter="transition ease-in-out duration-300 transform"
                                enter-from="translate-x-full"
                                enter-to="translate-x-0"
                                leave="transition ease-in-out duration-300 transform"
                                leave-from="translate-x-0"
                                leave-to="translate-x-full"
                            >
                                <DialogPanel
                                    class="relative ml-auto flex h-full w-full max-w-xs flex-col overflow-y-auto bg-white py-4 pb-12 shadow-xl"
                                >
                                    <div
                                        class="flex items-center justify-between px-4"
                                    >
                                        <h2
                                            class="text-lg font-medium text-gray-900"
                                        >
                                            Filters
                                        </h2>
                                        <button
                                            type="button"
                                            class="-mr-2 flex h-10 w-10 items-center justify-center rounded-md bg-white p-2 text-gray-400"
                                            @click="mobileFiltersOpen = false"
                                        >
                                            <span class="sr-only"
                                                >Close menu</span
                                            >
                                            <XMarkIcon
                                                class="h-6 w-6"
                                                aria-hidden="true"
                                            />
                                        </button>
                                    </div>

                                    <!-- Filters -->
                                    <form class="mt-4 border-t border-gray-200">
                                        <h3 class="sr-only">Price</h3>

                                        <form class="px-4 py-6 pb-12 mx-auto">
                                            <h3
                                                class="font-medium text-gray-900"
                                            >
                                                Price:
                                                <span class="text-gray-500"
                                                    >1000$</span
                                                >
                                            </h3>
                                            <div class="relative">
                                                <label
                                                    for="price-range-input"
                                                    class="sr-only"
                                                    >Default range</label
                                                >
                                                <input
                                                    id="price-range-input"
                                                    type="range"
                                                    value="1000"
                                                    min="100"
                                                    max="1500"
                                                    class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer dark:bg-gray-700"
                                                />
                                                <span
                                                    class="text-sm text-gray-500 dark:text-gray-400 absolute start-0 -bottom-6"
                                                    >Min ($100)</span
                                                >
                                                <span
                                                    class="text-sm text-gray-500 dark:text-gray-400 absolute end-0 -bottom-6"
                                                    >Max ($1500)</span
                                                >
                                            </div>
                                        </form>

                                        <Disclosure
                                            as="div"
                                            v-for="section in filters"
                                            :key="section.id"
                                            class="border-t border-gray-200 px-4 py-6"
                                            v-slot="{ open }"
                                        >
                                            <h3 class="-mx-2 -my-3 flow-root">
                                                <DisclosureButton
                                                    class="flex w-full items-center justify-between bg-white px-2 py-3 text-gray-400 hover:text-gray-500"
                                                >
                                                    <span
                                                        class="font-medium text-gray-900"
                                                        >{{
                                                            section.name
                                                        }}</span
                                                    >
                                                    <span
                                                        class="ml-6 flex items-center"
                                                    >
                                                        <PlusIcon
                                                            v-if="!open"
                                                            class="h-5 w-5"
                                                            aria-hidden="true"
                                                        />
                                                        <MinusIcon
                                                            v-else
                                                            class="h-5 w-5"
                                                            aria-hidden="true"
                                                        />
                                                    </span>
                                                </DisclosureButton>
                                            </h3>
                                            <DisclosurePanel class="pt-6">
                                                <div class="space-y-6">
                                                    <div
                                                        v-for="(
                                                            option, optionIdx
                                                        ) in section.options"
                                                        :key="option.value"
                                                        class="flex items-center"
                                                    >
                                                        <input
                                                            :id="`filter-mobile-${section.id}-${optionIdx}`"
                                                            :name="`${section.id}[]`"
                                                            :value="
                                                                option.value
                                                            "
                                                            type="checkbox"
                                                            :checked="
                                                                option.checked
                                                            "
                                                            class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                                        />
                                                        <label
                                                            :for="`filter-mobile-${section.id}-${optionIdx}`"
                                                            class="ml-3 min-w-0 flex-1 text-gray-500"
                                                            >{{
                                                                option.label
                                                            }}</label
                                                        >
                                                    </div>
                                                </div>
                                            </DisclosurePanel>
                                        </Disclosure>
                                    </form>
                                </DialogPanel>
                            </TransitionChild>
                        </div>
                    </Dialog>
                </TransitionRoot>

                <main class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div
                        class="flex items-baseline justify-between border-b border-gray-200 pb-6 pt-24"
                    >
                        <h1
                            class="text-4xl font-bold tracking-tight text-gray-900"
                        >
                            New Arrivals
                        </h1>

                        <div class="flex items-center">
                            <Menu
                                as="div"
                                class="relative inline-block text-left"
                            >
                                <div>
                                    <MenuButton
                                        class="group inline-flex justify-center text-sm font-medium text-gray-700 hover:text-gray-900"
                                    >
                                        Sort
                                        <ChevronDownIcon
                                            class="-mr-1 ml-1 h-5 w-5 flex-shrink-0 text-gray-400 group-hover:text-gray-500"
                                            aria-hidden="true"
                                        />
                                    </MenuButton>
                                </div>

                                <transition
                                    enter-active-class="transition ease-out duration-100"
                                    enter-from-class="transform opacity-0 scale-95"
                                    enter-to-class="transform opacity-100 scale-100"
                                    leave-active-class="transition ease-in duration-75"
                                    leave-from-class="transform opacity-100 scale-100"
                                    leave-to-class="transform opacity-0 scale-95"
                                >
                                    <MenuItems
                                        class="absolute right-0 z-10 mt-2 w-40 origin-top-right rounded-md bg-white shadow-2xl ring-1 ring-black ring-opacity-5 focus:outline-none"
                                    >
                                        <div class="py-1">
                                            <MenuItem
                                                v-for="option in sortOptions"
                                                :key="option.name"
                                                v-slot="{ active }"
                                            >
                                                <Link
                                                    :href="option.href"
                                                    :class="[
                                                        option.current
                                                            ? 'font-medium text-gray-900'
                                                            : 'text-gray-500',
                                                        active
                                                            ? 'bg-gray-100'
                                                            : '',
                                                        'block px-4 py-2 text-sm',
                                                    ]"
                                                    >{{ option.name }}</Link
                                                >
                                            </MenuItem>
                                        </div>
                                    </MenuItems>
                                </transition>
                            </Menu>

                            <button
                                type="button"
                                class="-m-2 ml-5 p-2 text-gray-400 hover:text-gray-500 sm:ml-7"
                            >
                                <span class="sr-only">View grid</span>
                                <Squares2X2Icon
                                    class="h-5 w-5"
                                    aria-hidden="true"
                                />
                            </button>
                            <button
                                type="button"
                                class="-m-2 ml-4 p-2 text-gray-400 hover:text-gray-500 sm:ml-6 lg:hidden"
                                @click="mobileFiltersOpen = true"
                            >
                                <span class="sr-only">Filters</span>
                                <FunnelIcon
                                    class="h-5 w-5"
                                    aria-hidden="true"
                                />
                            </button>
                        </div>
                    </div>

                    <section
                        aria-labelledby="products-heading"
                        class="pb-24 pt-6"
                    >
                        <h2 id="products-heading" class="sr-only">Products</h2>

                        <div
                            class="grid grid-cols-1 gap-x-8 gap-y-10 lg:grid-cols-4"
                        >
                            <!-- Filters -->
                            <form class="hidden lg:block">
                                <div
                                    role="list"
                                    class="space-y-4 border-b border-gray-200 pb-6 text-sm font-medium text-gray-900"
                                >
                                    <h3 class="font-medium text-gray-900">
                                        Price:
                                        <span class="text-gray-500">{{ minprice }}$ - {{ maxprice }}$</span>
                                    </h3>

                                    <!-- Muli Range -->
                                    <div class="relative">
                                        <input
                                            type="range"
                                            step="10"
                                            :min="min"
                                            :max="max"
                                            @input="mintrigger"
                                            v-model="minprice"
                                            @change="changePrice"
                                            class="absolute pointer-events-none appearance-none z-20 h-2 w-full opacity-0 cursor-pointer"
                                        />

                                        <input
                                            type="range"
                                            step="10"
                                            :min="min"
                                            :max="max"
                                            @input="maxtrigger"
                                            v-model="maxprice"
                                            @change="changePrice"
                                            class="absolute pointer-events-none appearance-none z-20 h-2 w-full opacity-0 cursor-pointer"
                                        />

                                        <div class="relative z-10 h-2">
                                            <div
                                                class="absolute z-10 left-0 right-0 bottom-0 top-0 rounded-md bg-gray-200"
                                            ></div>
                                            <div
                                                class="absolute z-20 top-0 bottom-0 rounded-md bg-blue-300"
                                                :style="{
                                                    right: maxthumb + '%',
                                                    left: minthumb + '%',
                                                }"
                                            ></div>
                                            <div
                                                class="absolute z-30 w-6 h-6 top-0 left-0 bg-blue-300 rounded-full -mt-2 -ml-1"
                                                :style="{
                                                    left: minthumb + '%',
                                                }"
                                            ></div>
                                            <div
                                                class="absolute z-30 w-6 h-6 top-0 right-0 bg-blue-300 rounded-full -mt-2 -mr-3"
                                                :style="{
                                                    right: maxthumb + '%',
                                                }"
                                            ></div>
                                        </div>
                                    </div>
                                    <div
                                        class="flex justify-between items-center py-5"
                                    >
                                        <div>
                                            <input
                                                type="text"
                                                maxlength="5"
                                                @input="mintrigger"
                                                v-model="minprice"
                                                :min="min"
                                                class="px-3 py-2 border border-gray-200 rounded w-24 text-center"
                                            />
                                        </div>
                                        <div>
                                            <input
                                                type="text"
                                                maxlength="5"
                                                @input="maxtrigger"
                                                v-model="maxprice"
                                                :max="max"
                                                class="px-3 py-2 border border-gray-200 rounded w-24 text-center"
                                            />
                                        </div>
                                    </div>
                                    <!-- End Muli Range -->
                                </div>

                                <Disclosure
                                    as="div"
                                    v-for="section in filters"
                                    :key="section.id"
                                    class="border-b border-gray-200 py-6"
                                    v-slot="{ open }"
                                >
                                    <h3 class="-my-3 flow-root">
                                        <DisclosureButton
                                            class="flex w-full items-center justify-between bg-white py-3 text-sm text-gray-400 hover:text-gray-500"
                                        >
                                            <span
                                                class="font-medium text-gray-900"
                                                >{{ section.name }}</span
                                            >
                                            <span
                                                class="ml-6 flex items-center"
                                            >
                                                <PlusIcon
                                                    v-if="!open"
                                                    class="h-5 w-5"
                                                    aria-hidden="true"
                                                />
                                                <MinusIcon
                                                    v-else
                                                    class="h-5 w-5"
                                                    aria-hidden="true"
                                                />
                                            </span>
                                        </DisclosureButton>
                                    </h3>
                                    <DisclosurePanel class="pt-6">
                                        <div class="space-y-4">
                                            <div
                                                v-for="(
                                                    option, optionIdx
                                                ) in section.options"
                                                :key="option.value"
                                                class="flex items-center"
                                            >
                                                <input
                                                    :id="`filter-${section.id}-${optionIdx}`"
                                                    :name="`${section.id}[]`"
                                                    :value="option.value"
                                                    type="checkbox"
                                                    :checked="option.checked"
                                                    class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                                />
                                                <label
                                                    :for="`filter-${section.id}-${optionIdx}`"
                                                    class="ml-3 text-sm text-gray-600"
                                                    >{{ option.label }}</label
                                                >
                                            </div>
                                        </div>
                                    </DisclosurePanel>
                                </Disclosure>
                            </form>

                            <!-- Product grid -->
                            <div class="lg:col-span-3">
                                <Product
                                    :products="products"
                                    :productsPage="productsPage"
                                />
                            </div>
                        </div>
                    </section>
                </main>
            </div>
        </div>
    </UserLayout>
</template>

<style scoped>
input[type="range"]::-webkit-slider-thumb {
    pointer-events: all;
    width: 24px;
    height: 24px;
    -webkit-appearance: none;
}
</style>
