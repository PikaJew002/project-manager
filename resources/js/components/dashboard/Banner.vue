<script setup>
import { XMarkIcon } from '@heroicons/vue/20/solid';

let props = defineProps({
  show: {
    type: Boolean,
    required: true,
  },
});

let emit = defineEmits(['close']);
</script>

<template>
  <div v-if="show" class="flex items-center gap-x-6 bg-indigo-600 px-6 py-2.5 sm:px-3.5 sm:before:flex-1">
    <p class="text-sm/6 text-white">
      <slot />
    </p>
    <div class="flex flex-1 justify-end">
      <button @click="$emit('close')" type="button" class="-m-3 p-3 focus-visible:outline-offset-[-4px]">
        <span class="sr-only">Dismiss</span>
        <XMarkIcon class="size-5 text-white" aria-hidden="true" />
      </button>
    </div>
  </div>
</template>
