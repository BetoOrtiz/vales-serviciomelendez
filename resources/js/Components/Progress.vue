<script setup>
const props = defineProps({
  value: String,
});


</script>
<script>
export default {};
</script>


<template>
  <div class="w-full bg-gray-200 rounded-full dark:bg-slate-400">
    <div class="bg-slate-700 text-[8px] font-medium text-blue-100 text-center p-0.5 leading-none rounded-full"
      :style="'width: ' + value + '%'">{{ value }}%</div>
  </div>
</template>
