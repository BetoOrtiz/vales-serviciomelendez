<script setup>
import { onMounted, ref } from "vue";

const props = defineProps({
    modelValue: String,
    options: Array,
    value: String,
});

defineEmits(["update:modelValue"]);

const textarea = ref(null);

onMounted(() => {
    if (textarea.value.hasAttribute("autofocus")) {
        textarea.value.focus();
    }
});

defineExpose({ focus: () => textarea.value.focus() });
</script>

<template>
    <textarea
        ref="textarea"
        class="border-gray-400 focus:border-slate-400 focus:ring focus:ring-slate-400 focus:ring-opacity-50 rounded-md shadow-sm disabled:text-gray-500"
        :class="value === '' ? 'text-gray-600' : ''"
        :value="modelValue"
        @change="$emit('update:modelValue', $event.target.value)"
    />
</template>
