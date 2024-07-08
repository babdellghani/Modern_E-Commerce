<script setup>
import UserLayout from "./Layouts/UserLayout.vue";
import UserAddress from "@/Pages/User/Components/UserAddress.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import Checkbox from "@/Components/Checkbox.vue";
import { computed, ref } from "vue";
import { Link, useForm, usePage, router } from "@inertiajs/vue3";

const carts = computed(() => usePage().props.carts);
const total = computed(() => usePage().props.total);
const user = computed(() => usePage().props.user);

const form = useForm({
    name: "",
    email: "",
    password: "",
    password_confirmation: "",
    terms: false,
});

const orderForm = useForm({
    user_address_id: "",
});

const showModal = ref(false);

const submit = () => {
    router.post("register", {
        onFinish: () => form.reset("password", "password_confirmation"),
    });
};

const deleteAddress = ($id) => {
    router.delete(
        `user-address/${$id}/delete`,
        {
            id: $id,
        },
        {
            preserveScroll: true,
        }
    );
};

const checkout = () => {
    router.post(
        "checkout/order",
        {
            ...orderForm,
        },
        {
            preserveScroll: true,
        }
    );
};
</script>

<template>
    <UserLayout title="Checkout">
        <section class="bg-white py-8 antialiased dark:bg-gray-900 md:py-16">
            <div class="mx-auto max-w-screen-xl px-4 2xl:px-0">
                <ol
                    class="items-center flex w-full max-w-2xl text-center text-sm font-medium text-gray-500 dark:text-gray-400 sm:text-base"
                >
                    <li
                        class="after:border-1 flex items-center text-blue-700 after:mx-6 after:hidden after:h-1 after:w-full after:border-b after:border-gray-200 dark:text-blue-500 dark:after:border-gray-700 sm:after:inline-block sm:after:content-[''] md:w-full xl:after:mx-10"
                    >
                        <span
                            class="flex items-center after:mx-2 after:text-gray-200 after:content-['/'] dark:after:text-gray-500 sm:after:hidden"
                        >
                            <svg
                                class="me-2 h-4 w-4 sm:h-5 sm:w-5"
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
                                    d="M8.5 11.5 11 14l4-4m6 2a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"
                                />
                            </svg>
                            Cart
                        </span>
                    </li>

                    <li
                        class="after:border-1 flex items-center text-blue-700 after:mx-6 after:hidden after:h-1 after:w-full after:border-b after:border-gray-200 dark:text-blue-500 dark:after:border-gray-700 sm:after:inline-block sm:after:content-[''] md:w-full xl:after:mx-10"
                    >
                        <span
                            class="flex items-center after:mx-2 after:text-gray-200 after:content-['/'] dark:after:text-gray-500 sm:after:hidden"
                        >
                            <svg
                                class="me-2 h-4 w-4 sm:h-5 sm:w-5"
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
                                    d="M8.5 11.5 11 14l4-4m6 2a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"
                                />
                            </svg>
                            Checkout
                        </span>
                    </li>

                    <li class="flex shrink-0 items-center">
                        <svg
                            class="me-2 h-4 w-4 sm:h-5 sm:w-5"
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
                                d="M8.5 11.5 11 14l4-4m6 2a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"
                            />
                        </svg>
                        Order summary
                    </li>
                </ol>

                <div
                    class="mt-6 sm:mt-8 lg:flex lg:items-start lg:gap-12 xl:gap-16"
                >
                    <div class="min-w-0 flex-1 space-y-8">
                        <div class="space-y-4">
                            <h2
                                class="text-xl font-semibold text-gray-900 dark:text-white"
                            >
                                Delivery Details
                            </h2>

                            <!-- Register If user is Guest -->
                            <form
                                v-if="!user"
                                @submit.prevent="submit"
                                class="grid grid-cols-1 gap-4 sm:grid-cols-2"
                            >
                                <h2
                                    class="col-span-2 text-lg font-semibold text-gray-900 dark:text-white"
                                >
                                    Register your account:
                                </h2>
                                <div>
                                    <label
                                        for="your_name"
                                        class="mb-2 block text-sm font-medium text-gray-900 dark:text-white"
                                    >
                                        Your name
                                    </label>
                                    <input
                                        type="text"
                                        id="your_name"
                                        v-model="form.name"
                                        class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder:text-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500"
                                        placeholder="Bonnie Green"
                                        required
                                    />
                                    <InputError
                                        class="mt-2"
                                        :message="form.errors.name"
                                    />
                                </div>

                                <div>
                                    <label
                                        for="your_email"
                                        class="mb-2 block text-sm font-medium text-gray-900 dark:text-white"
                                    >
                                        Your email*
                                    </label>
                                    <input
                                        type="email"
                                        id="your_email"
                                        v-model="form.email"
                                        class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder:text-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500"
                                        placeholder="name@flowbite.com"
                                        required
                                    />
                                    <InputError
                                        class="mt-2"
                                        :message="form.errors.email"
                                    />
                                </div>
                                <div>
                                    <label
                                        for="your_password"
                                        class="mb-2 block text-sm font-medium text-gray-900 dark:text-white"
                                    >
                                        Your password*
                                    </label>
                                    <input
                                        type="password"
                                        id="your_password"
                                        v-model="form.password"
                                        class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder:text-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500"
                                        required
                                    />
                                    <InputError
                                        class="mt-2"
                                        :message="form.errors.password"
                                    />
                                </div>
                                <div>
                                    <label
                                        for="your_password_confirmation"
                                        class="mb-2 block text-sm font-medium text-gray-900 dark:text-white"
                                    >
                                        Confirm your password*
                                    </label>
                                    <input
                                        type="password"
                                        id="your_password_confirmation"
                                        v-model="form.password_confirmation"
                                        class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder:text-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500"
                                        required
                                    />
                                    <InputError
                                        class="mt-2"
                                        :message="
                                            form.errors.password_confirmation
                                        "
                                    />
                                </div>
                                <div
                                    v-if="
                                        $page.props.jetstream
                                            .hasTermsAndPrivacyPolicyFeature
                                    "
                                    class="mt-4"
                                >
                                    <InputLabel for="terms">
                                        <div class="flex items-center">
                                            <Checkbox
                                                id="terms"
                                                v-model:checked="form.terms"
                                                name="terms"
                                                required
                                            />

                                            <div class="ms-2">
                                                I agree to the
                                                <a
                                                    target="_blank"
                                                    :href="route('terms.show')"
                                                    class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                                    >Terms of Service</a
                                                >
                                                and
                                                <a
                                                    target="_blank"
                                                    :href="route('policy.show')"
                                                    class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                                    >Privacy Policy</a
                                                >
                                            </div>
                                        </div>
                                        <InputError
                                            class="mt-2"
                                            :message="form.errors.terms"
                                        />
                                    </InputLabel>
                                </div>
                                <div class="col-span-2">
                                    <button
                                        type="submit"
                                        class="w-full rounded-lg border border-transparent bg-blue-600 p-2.5 text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-500 dark:bg-blue-500 dark:hover:bg-blue-600 dark:focus:ring-blue-600"
                                    >
                                        Register
                                    </button>
                                </div>
                            </form>

                            <!-- Select Address -->
                            <div
                                v-if="user && user.addresses"
                                class="space-y-4"
                            >
                                <h3
                                    class="text-lg font-semibold text-gray-900 dark:text-white"
                                >
                                    Select an address:
                                </h3>

                                <div
                                    class="flex sm:flex-row flex-col gap-5 flex-wrap"
                                >
                                    <div
                                        v-for="address in user.addresses"
                                        :key="address.id"
                                        class="rounded-lg sm:w-[calc(50%-1.25rem)] w-full border border-gray-200 bg-gray-50 p-4 ps-4 dark:border-gray-700 dark:bg-gray-800"
                                    >
                                        <div class="flex items-start">
                                            <div class="flex h-5 items-center">
                                                <input
                                                    :id="`address-select-${address.id}`"
                                                    :aria-describedby="`address-select-${address.id}`"
                                                    type="radio"
                                                    name="address-select"
                                                    :value="address.id"
                                                    v-model="
                                                        orderForm.user_address_id
                                                    "
                                                    class="h-4 w-4 border-gray-300 bg-white text-blue-600 focus:ring-2 focus:ring-blue-600 dark:border-gray-600 dark:bg-gray-700 dark:ring-offset-gray-800 dark:focus:ring-blue-600"
                                                />
                                            </div>

                                            <div class="ms-4 text-sm">
                                                <label
                                                    :for="`address-select-${address.id}`"
                                                    class="capitalize font-medium leading-none text-gray-900 dark:text-white"
                                                >
                                                    {{ address.type }}
                                                </label>
                                                <p
                                                    id="credit-card-text"
                                                    class="mt-1 capitalize text-xs font-normal text-gray-500 dark:text-gray-400"
                                                >
                                                    {{ address.address_1 }}
                                                </p>
                                                <p
                                                    id="credit-card-text"
                                                    class="mt-1 uppercase text-xs font-normal text-gray-500 dark:text-gray-400"
                                                >
                                                    {{ address.city }}
                                                </p>
                                                <p
                                                    id="credit-card-text"
                                                    class="mt-1 text-xs font-normal text-gray-500 dark:text-gray-400"
                                                >
                                                    {{ address.state }}
                                                </p>
                                                <p
                                                    id="credit-card-text"
                                                    class="mt-1 text-xs font-normal text-gray-500 dark:text-gray-400"
                                                >
                                                    {{ address.postal_code }}
                                                </p>
                                                <p
                                                    id="credit-card-text"
                                                    class="mt-1 uppercase text-xs font-normal text-gray-500 dark:text-gray-400"
                                                >
                                                    {{ address.country }}
                                                </p>
                                                <p
                                                    id="credit-card-text"
                                                    class="mt-1 text-xs font-normal text-gray-500 dark:text-gray-400"
                                                >
                                                    {{ address.phone }}
                                                </p>
                                            </div>
                                        </div>

                                        <div
                                            class="mt-4 flex items-center gap-2"
                                        >
                                            <button
                                                @click="
                                                    deleteAddress(address.id)
                                                "
                                                type="button"
                                                class="text-sm font-medium text-gray-500 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white"
                                            >
                                                Delete
                                            </button>

                                            <div
                                                class="h-3 w-px shrink-0 bg-gray-200 dark:bg-gray-700"
                                            ></div>

                                            <button
                                                type="button"
                                                class="text-sm font-medium text-gray-500 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white"
                                            >
                                                Edit
                                            </button>
                                        </div>
                                    </div>

                                    <InputError
                                        class="w-full"
                                        :message="$page.props.errors.user_address_id"
                                    />

                                    <button
                                        @click="showModal = true"
                                        :class="[
                                            'flex w-full items-center justify-center gap-2 rounded-lg border border-gray-200 bg-white px-5 py-2.5 text-sm font-medium text-gray-900 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:outline-none focus:ring-4 focus:ring-gray-100 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white dark:focus:ring-gray-700',
                                            showModal ? 'hidden' : 'block',
                                        ]"
                                    >
                                        <svg
                                            class="h-5 w-5"
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
                                                d="M5 12h14m-7 7V5"
                                            />
                                        </svg>
                                        Add New Address
                                    </button>
                                </div>
                            </div>

                            <!-- Add & Edit Address -->
                            <UserAddress
                                :class="['', showModal ? 'block' : 'hidden']"
                            />
                        </div>

                        <div class="space-y-4">
                            <h3
                                class="text-xl font-semibold text-gray-900 dark:text-white"
                            >
                                Payment
                            </h3>

                            <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                                <div
                                    class="rounded-lg border border-gray-200 bg-gray-50 p-4 ps-4 dark:border-gray-700 dark:bg-gray-800"
                                >
                                    <div class="flex items-start">
                                        <div class="flex h-5 items-center">
                                            <input
                                                id="credit-card"
                                                aria-describedby="credit-card-text"
                                                type="radio"
                                                name="payment-method"
                                                value=""
                                                class="h-4 w-4 border-gray-300 bg-white text-blue-600 focus:ring-2 focus:ring-blue-600 dark:border-gray-600 dark:bg-gray-700 dark:ring-offset-gray-800 dark:focus:ring-blue-600"
                                                checked
                                            />
                                        </div>

                                        <div class="ms-4 text-sm">
                                            <label
                                                for="credit-card"
                                                class="font-medium leading-none text-gray-900 dark:text-white"
                                            >
                                                Credit Card
                                            </label>
                                            <p
                                                id="credit-card-text"
                                                class="mt-1 text-xs font-normal text-gray-500 dark:text-gray-400"
                                            >
                                                Pay with your credit card
                                            </p>
                                        </div>
                                    </div>

                                    <div class="mt-4 flex items-center gap-2">
                                        <button
                                            type="button"
                                            class="text-sm font-medium text-gray-500 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white"
                                        >
                                            Delete
                                        </button>

                                        <div
                                            class="h-3 w-px shrink-0 bg-gray-200 dark:bg-gray-700"
                                        ></div>

                                        <button
                                            type="button"
                                            class="text-sm font-medium text-gray-500 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white"
                                        >
                                            Edit
                                        </button>
                                    </div>
                                </div>

                                <div
                                    class="rounded-lg border border-gray-200 bg-gray-50 p-4 ps-4 dark:border-gray-700 dark:bg-gray-800"
                                >
                                    <div class="flex items-start">
                                        <div class="flex h-5 items-center">
                                            <input
                                                id="pay-on-delivery"
                                                aria-describedby="pay-on-delivery-text"
                                                type="radio"
                                                name="payment-method"
                                                value=""
                                                class="h-4 w-4 border-gray-300 bg-white text-blue-600 focus:ring-2 focus:ring-blue-600 dark:border-gray-600 dark:bg-gray-700 dark:ring-offset-gray-800 dark:focus:ring-blue-600"
                                            />
                                        </div>

                                        <div class="ms-4 text-sm">
                                            <label
                                                for="pay-on-delivery"
                                                class="font-medium leading-none text-gray-900 dark:text-white"
                                            >
                                                Payment on delivery
                                            </label>
                                            <p
                                                id="pay-on-delivery-text"
                                                class="mt-1 text-xs font-normal text-gray-500 dark:text-gray-400"
                                            >
                                                +$15 payment processing fee
                                            </p>
                                        </div>
                                    </div>

                                    <div class="mt-4 flex items-center gap-2">
                                        <button
                                            type="button"
                                            class="text-sm font-medium text-gray-500 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white"
                                        >
                                            Delete
                                        </button>

                                        <div
                                            class="h-3 w-px shrink-0 bg-gray-200 dark:bg-gray-700"
                                        ></div>

                                        <button
                                            type="button"
                                            class="text-sm font-medium text-gray-500 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white"
                                        >
                                            Edit
                                        </button>
                                    </div>
                                </div>

                                <div
                                    class="rounded-lg border border-gray-200 bg-gray-50 p-4 ps-4 dark:border-gray-700 dark:bg-gray-800"
                                >
                                    <div class="flex items-start">
                                        <div class="flex h-5 items-center">
                                            <input
                                                id="paypal-2"
                                                aria-describedby="paypal-text"
                                                type="radio"
                                                name="payment-method"
                                                value=""
                                                class="h-4 w-4 border-gray-300 bg-white text-blue-600 focus:ring-2 focus:ring-blue-600 dark:border-gray-600 dark:bg-gray-700 dark:ring-offset-gray-800 dark:focus:ring-blue-600"
                                            />
                                        </div>

                                        <div class="ms-4 text-sm">
                                            <label
                                                for="paypal-2"
                                                class="font-medium leading-none text-gray-900 dark:text-white"
                                            >
                                                Paypal account
                                            </label>
                                            <p
                                                id="paypal-text"
                                                class="mt-1 text-xs font-normal text-gray-500 dark:text-gray-400"
                                            >
                                                Connect to your account
                                            </p>
                                        </div>
                                    </div>

                                    <div class="mt-4 flex items-center gap-2">
                                        <button
                                            type="button"
                                            class="text-sm font-medium text-gray-500 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white"
                                        >
                                            Delete
                                        </button>

                                        <div
                                            class="h-3 w-px shrink-0 bg-gray-200 dark:bg-gray-700"
                                        ></div>

                                        <button
                                            type="button"
                                            class="text-sm font-medium text-gray-500 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white"
                                        >
                                            Edit
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <h3
                                class="text-xl font-semibold text-gray-900 dark:text-white"
                            >
                                Delivery Methods
                            </h3>

                            <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                                <div
                                    class="rounded-lg border border-gray-200 bg-gray-50 p-4 ps-4 dark:border-gray-700 dark:bg-gray-800"
                                >
                                    <div class="flex items-start">
                                        <div class="flex h-5 items-center">
                                            <input
                                                id="dhl"
                                                aria-describedby="dhl-text"
                                                type="radio"
                                                name="delivery-method"
                                                value=""
                                                class="h-4 w-4 border-gray-300 bg-white text-blue-600 focus:ring-2 focus:ring-blue-600 dark:border-gray-600 dark:bg-gray-700 dark:ring-offset-gray-800 dark:focus:ring-blue-600"
                                                checked
                                            />
                                        </div>

                                        <div class="ms-4 text-sm">
                                            <label
                                                for="dhl"
                                                class="font-medium leading-none text-gray-900 dark:text-white"
                                            >
                                                $15 - DHL Fast Delivery
                                            </label>
                                            <p
                                                id="dhl-text"
                                                class="mt-1 text-xs font-normal text-gray-500 dark:text-gray-400"
                                            >
                                                Get it by Tommorow
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div
                                    class="rounded-lg border border-gray-200 bg-gray-50 p-4 ps-4 dark:border-gray-700 dark:bg-gray-800"
                                >
                                    <div class="flex items-start">
                                        <div class="flex h-5 items-center">
                                            <input
                                                id="fedex"
                                                aria-describedby="fedex-text"
                                                type="radio"
                                                name="delivery-method"
                                                value=""
                                                class="h-4 w-4 border-gray-300 bg-white text-blue-600 focus:ring-2 focus:ring-blue-600 dark:border-gray-600 dark:bg-gray-700 dark:ring-offset-gray-800 dark:focus:ring-blue-600"
                                            />
                                        </div>

                                        <div class="ms-4 text-sm">
                                            <label
                                                for="fedex"
                                                class="font-medium leading-none text-gray-900 dark:text-white"
                                            >
                                                Free Delivery - FedEx
                                            </label>
                                            <p
                                                id="fedex-text"
                                                class="mt-1 text-xs font-normal text-gray-500 dark:text-gray-400"
                                            >
                                                Get it by Friday, 13 Dec 2023
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div
                                    class="rounded-lg border border-gray-200 bg-gray-50 p-4 ps-4 dark:border-gray-700 dark:bg-gray-800"
                                >
                                    <div class="flex items-start">
                                        <div class="flex h-5 items-center">
                                            <input
                                                id="express"
                                                aria-describedby="express-text"
                                                type="radio"
                                                name="delivery-method"
                                                value=""
                                                class="h-4 w-4 border-gray-300 bg-white text-blue-600 focus:ring-2 focus:ring-blue-600 dark:border-gray-600 dark:bg-gray-700 dark:ring-offset-gray-800 dark:focus:ring-blue-600"
                                            />
                                        </div>

                                        <div class="ms-4 text-sm">
                                            <label
                                                for="express"
                                                class="font-medium leading-none text-gray-900 dark:text-white"
                                            >
                                                $49 - Express Delivery
                                            </label>
                                            <p
                                                id="express-text"
                                                class="mt-1 text-xs font-normal text-gray-500 dark:text-gray-400"
                                            >
                                                Get it today
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div v-if="carts.length > 0">
                            <label
                                for="voucher"
                                class="mb-2 block text-sm font-medium text-gray-900 dark:text-white"
                            >
                                Enter a gift card, voucher or promotional code
                            </label>
                            <div class="flex max-w-md items-center gap-4">
                                <input
                                    type="text"
                                    id="voucher"
                                    class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder:text-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500"
                                    placeholder=""
                                    required
                                />
                                <button
                                    type="button"
                                    class="flex items-center justify-center rounded-lg bg-blue-700 px-5 py-2.5 text-sm font-medium text-white hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                                >
                                    Apply
                                </button>
                            </div>
                        </div>
                    </div>

                    <div
                        v-if="carts.length > 0"
                        class="mt-6 w-full space-y-6 sm:mt-8 lg:mt-0 lg:max-w-xs xl:max-w-md"
                    >
                        <div class="flow-root">
                            <div
                                class="-my-3 divide-y divide-gray-200 dark:divide-gray-800"
                            >
                                <dl
                                    class="flex items-center justify-between gap-4 py-3"
                                >
                                    <dt
                                        class="text-base font-normal text-gray-500 dark:text-gray-400"
                                    >
                                        Subtotal
                                    </dt>
                                    <dd
                                        class="text-base font-medium text-gray-900 dark:text-white"
                                    >
                                        {{ total }}
                                    </dd>
                                </dl>

                                <dl
                                    class="flex items-center justify-between gap-4 py-3"
                                >
                                    <dt
                                        class="text-base font-normal text-gray-500 dark:text-gray-400"
                                    >
                                        Savings
                                    </dt>
                                    <dd
                                        class="text-base font-medium text-green-500"
                                    >
                                        0
                                    </dd>
                                </dl>

                                <dl
                                    class="flex items-center justify-between gap-4 py-3"
                                >
                                    <dt
                                        class="text-base font-normal text-gray-500 dark:text-gray-400"
                                    >
                                        Store Pickup
                                    </dt>
                                    <dd
                                        class="text-base font-medium text-gray-900 dark:text-white"
                                    >
                                        $99
                                    </dd>
                                </dl>

                                <dl
                                    class="flex items-center justify-between gap-4 py-3"
                                >
                                    <dt
                                        class="text-base font-normal text-gray-500 dark:text-gray-400"
                                    >
                                        Tax
                                    </dt>
                                    <dd
                                        class="text-base font-medium text-gray-900 dark:text-white"
                                    >
                                        $199
                                    </dd>
                                </dl>

                                <dl
                                    class="flex items-center justify-between gap-4 py-3"
                                >
                                    <dt
                                        class="text-base font-bold text-gray-900 dark:text-white"
                                    >
                                        Total
                                    </dt>
                                    <dd
                                        class="text-base font-bold text-gray-900 dark:text-white"
                                    >
                                        $8,392.00
                                    </dd>
                                </dl>
                            </div>
                        </div>

                        <div class="space-y-3">
                            <button
                                @click="checkout"
                                type="submit"
                                class="flex w-full items-center justify-center rounded-lg bg-blue-700 px-5 py-2.5 text-sm font-medium text-white hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                            >
                                Proceed to Payment
                            </button>

                            <p
                                class="text-sm font-normal text-gray-500 dark:text-gray-400"
                            >
                                One or more items in your cart require an
                                account.
                                <Link
                                    :href="route('register')"
                                    title="Sign in or create an account now."
                                    class="font-medium text-blue-700 underline hover:no-underline dark:text-blue-500"
                                    >Sign in or create an account now.</Link
                                >.
                            </p>
                        </div>
                    </div>
                    <div
                        v-else
                        class="mt-6 w-full space-y-6 sm:mt-8 lg:mt-0 lg:max-w-xs xl:max-w-md flex flex-col items-center justify-center gap-4 rounded-lg border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-800 md:p-6"
                    >
                        <p
                            class="text-center text-sm font-medium text-gray-500 dark:text-gray-400"
                        >
                            Your cart is empty
                        </p>
                        <Link
                            :href="route('home')"
                            class="block text-center text-sm font-medium text-blue-600 hover:underline dark:text-blue-500"
                            >Continue Shopping</Link
                        >
                    </div>
                </div>
            </div>
        </section>
    </UserLayout>
</template>
