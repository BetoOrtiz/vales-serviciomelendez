<script setup>
import { Link } from "@inertiajs/vue3";

const props = defineProps({
    href: String,
    target: String,
    active: Boolean,
});
</script>

<template>
    <Link
        :href="href"
        :target="target"
        class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring focus:ring-gray-300 disabled:opacity-25 shadow-sm hover:shadow-md focus:shadow-lg active:shadow-outline transition"
    >
        <slot />
    </Link>
</template>
