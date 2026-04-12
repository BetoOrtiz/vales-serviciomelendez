<script>
export default {};
</script>

<style>
</style>

<template>
  <div class="w-full overflow-hidden shadow-lg">
    <div class="w-full overflow-x-auto">
      <table class="w-full whitespace-no-wrap">
        <thead>
          <tr class="
              text-xs
              font-semibold
              tracking-wide
              text-left text-slate-600
              uppercase
              border-b
              bg-slate-50
            ">
            <slot name="col"></slot>
          </tr>
        </thead>
        <tbody class="bg-white">
          <slot name="row"></slot>
        </tbody>
      </table>
    </div>
    <slot name="pagination"></slot>
  </div>
</template>
