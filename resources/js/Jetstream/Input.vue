<script setup>
import { onMounted, ref } from "vue";

defineProps({
  modelValue: String,
});

defineEmits(["update:modelValue"]);

const input = ref(null);

onMounted(() => {
  if (input.value.hasAttribute("autofocus")) {
    input.value.focus();
  }
});

defineExpose({ focus: () => input.value.focus() });
</script>

<template>
  <input ref="input" class="
      border-gray-400
      focus:border-slate-400
      focus:ring
      focus:ring-slate-400
      focus:ring-opacity-50
      rounded-md
      shadow-sm
      disabled:text-gray-500
      
      file:border-solid
      file:inline-flex
      file:items-center
      file:px-4
      file:py-2
      file:bg-gray-800
      file:border
      file:border-transparent
      file:rounded-md
      file:font-semibold
      file:text-xs
      file:text-white
      file:uppercase
      file:tracking-widest
      hover:file:bg-gray-700
      active:file:bg-gray-900
      focus:file:outline-none
      focus:file:border-gray-900
      focus:file:ring
      focus:file:ring-gray-300
      file:disabled:opacity-25
      file:shadow-sm
      hover:file:shadow-md
      focus:file:shadow-lg
      active:file:shadow-outline
      file:transition
    " :value="modelValue" @input="$emit('update:modelValue', $event.target.value)" />
</template>
