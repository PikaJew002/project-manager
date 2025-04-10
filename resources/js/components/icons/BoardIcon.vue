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
    class="inline leading-[0]"
    fill="currentColor"
    aria-hidden="true"
    width="20"
    height="20"
    viewBox="0 0 20 20"
    xmlns="http://www.w3.org/2000/svg"
    v-if="!filled"
  >
    <path
      d="M3 6a3 3 0 0 1 3-3h8a3 3 0 0 1 3 3v8a3 3 0 0 1-3 3H6a3 3 0 0 1-3-3V6Zm3-2a2 2 0 0 0-2 2v1.06L9.5 7V4H6Zm4.5 0v8H16V6a2 2 0 0 0-2-2h-3.5Zm5.5 9h-5.5v3H14a2 2 0 0 0 2-2v-1Zm-6.5 3V8L4 8.06V14c0 1.1.9 2 2 2h3.5Z"
      fill="currentColor"
    ></path>
  </svg>
  <svg
    class="inline leading-[0]"
    fill="currentColor"
    aria-hidden="true"
    width="20"
    height="20"
    viewBox="0 0 20 20"
    xmlns="http://www.w3.org/2000/svg"
    v-else
  >
    <path
      d="M9.5 3v4H3V6a3 3 0 0 1 3-3h3.5Zm1 0v9H17V6a3 3 0 0 0-3-3h-3.5ZM17 13h-6.5v4H14a3 3 0 0 0 3-3v-1Zm-7.5 4V8H3v6a3 3 0 0 0 3 3h3.5Z"
      fill="currentColor"
    ></path>
  </svg>
</template>
