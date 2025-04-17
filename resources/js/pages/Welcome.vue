<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AuthLayout from '../layouts/AuthLayout.vue';

let form = useForm({
  email: '',
  password: '',
  remember: false,
});

function submit() {
  form.post(route('login'), {
    onFinish: () => form.reset('password'),
  });
};
</script>

<template>
  <Head title="Login" />
  <AuthLayout>
    <form @submit.prevent="submit" class="flex flex-col gap-6">
      <div class="grid gap-6">
        <div class="grid gap-2">
          <label
            for="email"
            class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70"
          >
            Email address
          </label>
          <input
            id="email"
            type="email"
            class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-base ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-indigo-600 focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 md:text-sm"
            required
            autofocus
            autocomplete="email"
            v-model="form.email"
            placeholder="email@example.com"
          />
          <div v-show="form.errors.email">
            <p class="text-sm text-red-600 dark:text-red-500">
              {{ form.errors.email }}
            </p>
          </div>
        </div>

        <div class="grid gap-2">
          <label
            for="password"
            class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70"
          >
            Password
          </label>
          <input
            id="password"
            type="password"
            class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-base ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-indigo-600 focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 md:text-sm"
            required
            autocomplete="current-password"
            v-model="form.password"
            placeholder="Password"
          />
          <div v-show="form.errors.password">
            <p class="text-sm text-red-600 dark:text-red-500">
              {{ form.errors.password }}
            </p>
          </div>
        </div>

        <button
          type="submit"
          :disabled="form.processing"
          class="mt-4 w-full inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md text-sm font-medium border ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-indigo-600 focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 [&_svg]:pointer-events-none [&_svg]:size-4 [&_svg]:shrink-0 bg-primary text-primary-foreground shadow hover:bg-primary/90 h-9 px-4 py-2"
        >
          <svg
            v-if="form.processing"
            class="mr-3 -ml-1 size-5 animate-spin text-white"
            xmlns="http://www.w3.org/2000/svg"
            fill="none"
            viewBox="0 0 24 24"
          >
            <circle
              class="opacity-25"
              cx="12"
              cy="12"
              r="10"
              stroke="currentColor"
              stroke-width="4"
            ></circle>
            <path
              class="opacity-75"
              fill="currentColor"
              d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
            ></path>
          </svg>
          Log in
        </button>
      </div>
      <div class="text-center text-sm text-muted-foreground">
        Don't have an account?
        <Link :href="route('register-organization')" class="text-foreground underline decoration-neutral-300 underline-offset-4 transition-colors duration-300 ease-out hover:decoration-current! dark:decoration-neutral-500">
          Sign up
        </Link>
      </div>
    </form>
  </AuthLayout>
</template>
