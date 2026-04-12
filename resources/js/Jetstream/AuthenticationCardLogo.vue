<script setup>
import { Link } from "@inertiajs/vue3";
</script>

<template>
    <Link :href="'/'">
    <img :src="$page.props.logo" class="w-1/2 m-auto" />
    </Link>
</template>
