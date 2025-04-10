<script setup>
//
</script>

<template>
  <div class="flex flex-row flex-nowrap grow shrink basis-full overflow-y-auto min-h-0 min-w-0 mt-4 sm:mt-8">
    <div class="flex flex-row flex-nowrap grow shrink basis-0 min-h-0 min-w-0">
      <ul class="flex flex-row flex-nowrap grow shrink basis-0 min-h-0 min-w-0 overflow-x-auto overflow-y-hidden mx-0 -mt-[9px] px-[9px]">
        <slot />
      </ul>
    </div>
  </div>
</template>
