<script setup>
defineProps({
  filled: {
    type: Boolean,
    default: false,
  },
});
</script>

<template>
  <svg
    class="leading-[0] inline"
    fill="currentColor"
    aria-hidden="true"
    width="20"
    height="20"
    viewBox="0 0 20 20"
    xmlns="http://www.w3.org/2000/svg"
    focusable="false"
    v-if="!filled"
  >
    <path
      d="M17 5.5A2.5 2.5 0 0 0 14.5 3h-9A2.5 2.5 0 0 0 3 5.5v9A2.5 2.5 0 0 0 5.5 17h9a2.5 2.5 0 0 0 2.5-2.5v-9Zm-13 9V13h3v3H5.36A1.5 1.5 0 0 1 4 14.5Zm8-1.5v3H8v-3h4Zm2.5 3H13v-3h3V14.64A1.5 1.5 0 0 1 14.5 16ZM12 8v4H8V8h4Zm1 0h3v4h-3V8Zm-1-4v3H8V4h4Zm1 0H14.64A1.5 1.5 0 0 1 16 5.5V7h-3V4ZM7 4v3H4V5.36A1.5 1.5 0 0 1 5.5 4H7Zm0 4v4H4V8h3Z"
      fill="currentColor"
    ></path>
  </svg>
  <svg
    class="leading-[0] inline"
    fill="currentColor"
    aria-hidden="true"
    width="20"
    height="20"
    viewBox="0 0 20 20"
    xmlns="http://www.w3.org/2000/svg"
    v-else
  >
    <path
      d="M13 17h1.5a2.5 2.5 0 0 0 2.5-2.5V13h-4v4Zm0-5V8h4v4h-4Zm-1 0H8V8h4v4Zm-4 1h4v4H8v-4Zm-1-1V8H3v4h4Zm-4 1h4v4H5.5A2.5 2.5 0 0 1 3 14.5V13Zm10-6h4V5.5A2.5 2.5 0 0 0 14.5 3H13v4Zm-1-4v4H8V3h4ZM7 3v4H3V5.5A2.5 2.5 0 0 1 5.5 3H7Z"
      fill="currentColor"
    ></path>
  </svg>
</template>
