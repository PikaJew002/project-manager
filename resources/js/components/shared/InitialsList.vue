<script setup>
let props = defineProps({
  items: {
    type: Array,
    required: true,
  },
  keyAttribute: {
    type: String,
    default: 'id',
  },
  titleAttribute: {
    type: String,
    default: 'name',
  },
  initialsAttribute: {
    type: String,
    default: 'initials',
  },
  size: {
    type: [String, Number],
    default: '8',
  },
});

const sizeMap = {
  '8': 'h-8 w-8 leading-8 text-normal',
  '6': 'h-6 w-6 leading-6 text-xs',
};
</script>

<template>
  <div class="flex space-x-2 relative">
    <slot />
    <button
      v-for="item in items"
      type="button"
      :key="item[keyAttribute]"
      class="shrink-0 rounded-full hover:opacity-75"
      :title="item[titleAttribute]"
    >
      <div class="inline-block relative text-center">
        <div :class="['rounded-full bg-gray-200 font-semibold', sizeMap[size]]">
          <span>{{ item[initialsAttribute] }}</span>
        </div>
      </div>
    </button>
  </div>
</template>
