<script setup>
defineProps({
    type: {
        type: String,
        default: "submit",
    },
    colors: {
        type: Array,
        default: {
            bg: "bg-slate-800",
            hover: "hover:bg-slate-700",
            active: "active:bg-slate-900",
        },
    },
});
</script>

<template>
    <button
        :type="type"
        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest focus:outline-none focus:ring disabled:opacity-25 shadow-sm hover:shadow-md focus:shadow-lg active:shadow-outline transition focus:border-slate-900/70 focus:ring-slate-300"
        :class="`${colors.bg} ${colors.hover} ${colors.active}`"
    >
        <slot />
    </button>
</template>
