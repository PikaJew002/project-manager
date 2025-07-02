<script setup>
let props = defineProps({
  active: {
    type: Boolean,
    default: true,
  },
});
</script>

<template>
  <div class="flex mr-[6px] mb-[6px] last:mr-0 only:mb-0 max-w-full">
    <div :class="['flex flex-row flex-nowrap justify-center items-center h-4 py-px px-[7px] text-xs min-w-1 max-w-full rounded border border-solid border-transparent', active ? 'bg-indigo-600 text-white' : 'bg-gray-400 text-gray-900']">
      <span class="overflow-ellipsis whitespace-nowrap overflow-hidden items-center pointer-events-none">
        <slot />
      </span>
    </div>
  </div>
</template>
