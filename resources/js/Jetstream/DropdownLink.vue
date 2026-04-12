<script setup>
import { Link } from "@inertiajs/vue3";

defineProps({
    href: String,
    as: String,
    target: String,
});
</script>

<template>
    <div>
        <button
            v-if="as === 'button'"
            type="submit"
            class="block w-full px-4 py-2 text-sm leading-5 text-left text-slate-700 hover:bg-slate-300 focus:outline-none focus:bg-slate-500 transition"
        >
            <slot />
        </button>

        <a
            v-else-if="as === 'a'"
            :href="href"
            :target="target"
            class="block px-4 py-2 text-sm leading-5 text-slate-700 hover:bg-slate-300 focus:outline-none focus:bg-slate-500 transition"
        >
            <slot />
        </a>

        <Link
            v-else
            :href="href"
            class="block px-4 py-2 text-sm leading-5 text-slate-700 hover:bg-slate-300 focus:outline-none focus:bg-slate-500 transition"
        >
            <slot />
        </Link>
    </div>
</template>
