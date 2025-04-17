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
        <div class="flex flex-col items-center gap-4">
          <Link
            :href="route('welcome')"
            class="flex flex-col items-center gap-2 font-medium focus-visible:outline-none focus-visible:ring-indigo-600 focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2"
          >
            <div class="mb-1 flex h-9 w-9 items-center justify-center rounded-md text-indigo-600">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 40 42">
                <path fill="currentColor" fill-rule="evenodd" clip-rule="evenodd" d="M17.2 5.633 8.6.855 0 5.633v26.51l16.2 9 16.2-9v-8.442l7.6-4.223V9.856l-8.6-4.777-8.6 4.777V18.3l-5.6 3.111V5.633ZM38 18.301l-5.6 3.11v-6.157l5.6-3.11V18.3Zm-1.06-7.856-5.54 3.078-5.54-3.079 5.54-3.078 5.54 3.079ZM24.8 18.3v-6.157l5.6 3.111v6.158L24.8 18.3Zm-1 1.732 5.54 3.078-13.14 7.302-5.54-3.078 13.14-7.3v-.002Zm-16.2 7.89 7.6 4.222V38.3L2 30.966V7.92l5.6 3.111v16.892ZM8.6 9.3 3.06 6.222 8.6 3.143l5.54 3.08L8.6 9.3Zm21.8 15.51-13.2 7.334V38.3l13.2-7.334v-6.156ZM9.6 11.034l5.6-3.11v14.6l-5.6 3.11v-14.6Z"/>
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
