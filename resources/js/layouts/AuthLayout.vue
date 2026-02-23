<script setup>
import { ref } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import Banner from '../components/dashboard/Banner.vue';

let showStatus = ref(true);

let page = usePage();
</script>

<template>
  <div
    class="flex min-h-svh flex-col items-center justify-center gap-6 bg-background p-6 md:p-10"
  >
    <div class="w-full max-w-sm">
      <div class="flex flex-col gap-8">
        <div class="flex flex-col gap-y-6 items-center">
          <Link
            :href="route('welcome')"
            class="flex flex-col items-center gap-2 font-medium focus-visible:outline-none focus-visible:ring-indigo-600 focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2"
          >
            <div class="flex size-40 items-center justify-center rounded-md">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="20 20 120 120">
                <title>Project Manager</title>
                <defs>
                    <linearGradient id="logoGradient" x1="0" y1="0" x2="140" y2="140" gradientUnits="userSpaceOnUse">
                        <stop stop-color="#2563EB"></stop>
                        <stop offset="1" stop-color="#7C3AED"></stop>
                    </linearGradient>
                </defs>
                <rect x="20" y="20" width="120" height="120" rx="24" fill="url(#logoGradient)"></rect>
                <rect x="40" y="45" width="14" height="60" rx="4" fill="white" opacity="0.95"></rect>
                <rect x="60" y="55" width="14" height="50" rx="4" fill="white" opacity="0.95"></rect>
                <rect x="80" y="65" width="14" height="40" rx="4" fill="white" opacity="0.95"></rect>
                <path d="M100 85 L115 100 L135 70" stroke="white" stroke-width="6" stroke-linecap="round" stroke-linejoin="round"></path>
              </svg>
            </div>
            <span class="sr-only">Logo</span>
          </Link>
          <div class="space-y-2 text-center">
            <h1 class="text-xl font-medium">Project Manager</h1>
            <p class="text-center text-sm text-muted-foreground">
              Yet another project management solution no one asked for and some
              developer thinks he can do better
            </p>
          </div>
        </div>
        <Banner :show="page.props.status !== undefined && showStatus" @close="showStatus = false">
          {{ page.props.status }}
        </Banner>
        <slot />
      </div>
    </div>
  </div>
</template>
