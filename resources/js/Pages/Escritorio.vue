<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { toRefs, computed } from "vue";
import Tabla from "@/Components/Tabla.vue";

const props = defineProps({});
</script>

<style>
.slide-fade-enter-active {
    transition: all 0.3s ease-out;
}

.slide-fade-leave-active {
    transition: all 0.3s ease-in;
}

.slide-fade-enter-from,
.slide-fade-leave-to {
    transform: translateX(-20px);
    opacity: 0;
}
</style>

<template>
    <AppLayout title="Escritorio">
        <div class="py-4">
            <div class="flex flex-col items-center justify-center mt-10">
                <p class="text-xl font-medium text-gray-500">Escritorio</p>
            </div>
        </div>
    </AppLayout>
</template>
