<script setup>
import { onMounted, ref } from "vue";

const props = defineProps({
  modelValue: String,
  options: Array,
  value: String,
});

defineEmits(["update:modelValue"]);

const select = ref(null);

onMounted(() => {
  if (select.value.hasAttribute("autofocus")) {
    select.value.focus();
  }
});

defineExpose({ focus: () => select.value.focus() });
</script>

<template>
  <select ref="select" class="
      border-gray-400
      focus:border-slate-400
      focus:ring
      focus:ring-slate-400
      focus:ring-opacity-50
      rounded-md
      shadow-sm
      disabled:text-gray-500
    " :class="value === '' ? 'text-gray-600' : ''" :value="modelValue"
    @change="$emit('update:modelValue', $event.target.value)">
    <option selected value="" disabled>Seleccione una Opción</option>
    <option v-for="option in options" :disabled="option.disabled" :key="option.value"
      :value="option.value ? option.value : option.name" v-html="option.name" />
  </select>
</template>
