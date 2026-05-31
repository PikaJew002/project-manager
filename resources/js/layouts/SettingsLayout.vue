<script setup>
import { defineProps } from 'vue';
import AppLayout from './AppLayout.vue';

const props = defineProps({
  pageRoute: {
    type: String,
    required: false,
  },
});

let secondaryNavigation = [
  // { name: 'Account', href: route('settings.account'), current: props.pageRoute === 'settings.account' },
  { name: 'Notifications', href: route('settings.notifications'), current: props.pageRoute === 'settings.notifications' },
];
</script>

<template>
  <AppLayout :pageRoute="pageRoute">
    <header class="border-b border-gray-200 bg-white backdrop-blur-sm">
      <nav class="flex overflow-x-auto py-4">
        <ul role="list" class="flex min-w-full flex-none gap-x-6 px-4 text-sm/6 font-semibold text-gray-500 sm:px-6 lg:px-8">
          <li v-for="item in secondaryNavigation" :key="item.name">
            <a :href="item.href" :class="item.current ? 'text-indigo-600' : ''">{{ item.name }}</a>
          </li>
        </ul>
      </nav>
    </header>
    <slot />
  </AppLayout>
</template>
