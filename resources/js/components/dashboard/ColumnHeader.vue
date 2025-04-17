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
});
</script>

<template>
  <th
    scope="col"
    :class="[
      'sticky top-0 z-10 table-cell border-b border-gray-300 bg-white/75 text-nowrap text-left text-sm font-semibold text-gray-900 backdrop-blur backdrop-filter py-3.5 pr-3',
      first
        ? 'pl-4 sm:pl-6 lg:pl-8'
        : (last
          ? 'pl-3 pr-4 sm:pr-6 lg:pr-8'
          : 'pl-3'),
    ]"
  >
    <slot />
  </th>
</template>
