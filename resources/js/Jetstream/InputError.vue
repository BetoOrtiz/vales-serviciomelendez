<script setup>
defineProps({
  message: String,
});
</script>
<style>
.slide-fade-enter-active {
  transition: all 0.3s ease-out;
}

.slide-fade-leave-active {
  transition: all 0.4s cubic-bezier(1, 0.5, 0.8, 1);
}

.slide-fade-enter-from,
.slide-fade-leave-to {
  transform: translateX(50px);
  opacity: 0;
}
</style>
<template>
  <Transition name="slide-fade">
    <div v-show="message">
      <p class="text-sm text-red-600">
        {{ message }}
      </p>
    </div>
  </Transition>
</template>
