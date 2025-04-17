<script setup>
let props = defineProps({
  first: {
    type: Boolean,
    default: false,
  },
  last: {
    type: Boolean,
    default: false,
  },
  width: {
    type: String,
    default: '',
  },
});
</script>

<template>
  <td :class="[
    'table-cell border-b border-gray-200 whitespace-nowrap bg-white text-sm text-gray-700 truncate py-2',
    width,
    first ? '' :
    (last ? 'pl-3 pr-4 sm:pr-8 lg:pr-8' : 'px-3'),
  ]">
    <slot />
  </td>
</template>
