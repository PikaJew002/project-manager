<script setup>
import { ref } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import AuthLayout from '../layouts/AuthLayout.vue';

let form = useForm({
  first_name: '',
  last_name: '',
  initials: '',
  email: '',
  password: '',
  password_confirmation: '',
  organization_name: '',
});

let selectedRegistrationType = ref(null);

function submit() {
  form.post(route('register'), {
    onFinish: () => form.reset('password', 'password_confirmation'),
  });
}
</script>

<template>
  <Head title="Register" />
  <AuthLayout>
    <div v-if="selectedRegistrationType === null" class="grid grid-cols-1 gap-4">
      <div class="group relative flex items-center space-x-3 rounded-lg border border-gray-300 bg-white px-6 py-5 shadow-sm focus-within:ring-2 focus-within:ring-indigo-500 focus-within:ring-offset-2 hover:border-gray-400">
        <div class="min-w-0 flex-1">
          <button @click.prevent="selectedRegistrationType = 'new-organization'" type="button" class="focus:outline-none">
            <span class="absolute inset-0" aria-hidden="true" />
            <p class="text-sm font-medium text-gray-900">New Organization</p>
            <p class="text-sm text-gray-500">Are you looking to create a new Organization in Project Manager?</p>
          </button>
        </div>
        <span class="pointer-events-none absolute right-4 top-4 text-gray-300 group-hover:text-gray-400 group-focus-within:text-gray-400" aria-hidden="true">
          <svg class="size-4" fill="currentColor" viewBox="0 0 24 24">
            <path d="M20 4h1a1 1 0 00-1-1v1zm-1 12a1 1 0 102 0h-2zM8 3a1 1 0 000 2V3zM3.293 19.293a1 1 0 101.414 1.414l-1.414-1.414zM19 4v12h2V4h-2zm1-1H8v2h12V3zm-.707.293l-16 16 1.414 1.414 16-16-1.414-1.414z" />
          </svg>
        </span>
      </div>
      <div class="group relative flex items-center space-x-3 rounded-lg border border-gray-300 bg-white px-6 py-5 shadow-sm focus-within:ring-2 focus-within:ring-indigo-500 focus-within:ring-offset-2 hover:border-gray-400">
        <div class="min-w-0 flex-1">
          <button @click.prevent="selectedRegistrationType = 'new-user'" type="button" class="focus:outline-none">
            <span class="absolute inset-0" aria-hidden="true" />
            <p class="text-sm font-medium text-gray-900">New User</p>
            <p class="text-sm text-gray-500">Are you looking to register as a new User with an existing Organization in Project Manager?</p>
          </button>
        </div>
        <span class="pointer-events-none absolute right-4 top-4 text-gray-300 group-hover:text-gray-400 group-focus-within:text-gray-400" aria-hidden="true">
          <svg class="size-4" fill="currentColor" viewBox="0 0 24 24">
            <path d="M20 4h1a1 1 0 00-1-1v1zm-1 12a1 1 0 102 0h-2zM8 3a1 1 0 000 2V3zM3.293 19.293a1 1 0 101.414 1.414l-1.414-1.414zM19 4v12h2V4h-2zm1-1H8v2h12V3zm-.707.293l-16 16 1.414 1.414 16-16-1.414-1.414z" />
          </svg>
        </span>
      </div>
    </div>
    <div v-else class="group relative flex items-center justify-center rounded-lg border border-gray-300 bg-white px-6 py-3 shadow-sm focus-within:ring-2 focus-within:ring-indigo-500 focus-within:ring-offset-2 hover:border-gray-400">
      <div class="min-w-0">
        <button @click.prevent="selectedRegistrationType = null" type="button" class="focus:outline-none">
          <span class="absolute inset-0" aria-hidden="true" />
          <p class="text-sm font-medium text-gray-900">Back</p>
        </button>
      </div>
      <span class="pointer-events-none absolute left-4 top-4 text-gray-300 -rotate-90 group-hover:text-gray-400 group-focus-within:text-gray-400" aria-hidden="true">
        <svg class="size-4" fill="currentColor" viewBox="0 0 24 24">
          <path d="M20 4h1a1 1 0 00-1-1v1zm-1 12a1 1 0 102 0h-2zM8 3a1 1 0 000 2V3zM3.293 19.293a1 1 0 101.414 1.414l-1.414-1.414zM19 4v12h2V4h-2zm1-1H8v2h12V3zm-.707.293l-16 16 1.414 1.414 16-16-1.414-1.414z" />
        </svg>
      </span>
    </div>
    <form v-if="selectedRegistrationType !== null" @submit.prevent="submit" class="flex flex-col gap-6">
      <template v-if="selectedRegistrationType === 'new-organization'">
        <div class="grid gap-6">
          <div class="grid gap-2">
            <label
              for="organization_name"
              class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70"
            >
              Organization Name
            </label>
            <input
              id="organization_name"
              type="text"
              class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-base ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium file:text-foreground placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 md:text-sm"
              required
              v-model="form.organization_name"
              placeholder="KCTCS Marketing"
            />
            <div v-show="form.errors.organization_name">
              <p class="text-sm text-red-600 dark:text-red-500">
                {{ form.errors.organization_name }}
              </p>
            </div>
          </div>

          <div class="grid gap-2">
            <label
              for="first_name"
              class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70"
            >
              First Name
            </label>
            <input
              id="first_name"
              type="text"
              class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-base ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium file:text-foreground placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 md:text-sm"
              required
              v-model="form.first_name"
              placeholder="John"
            />
            <div v-show="form.errors.first_name">
              <p class="text-sm text-red-600 dark:text-red-500">
                {{ form.errors.first_name }}
              </p>
            </div>
          </div>

          <div class="grid gap-2">
            <label
              for="last_name"
              class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70"
            >
              Last Name
            </label>
            <input
              id="last_name"
              type="text"
              class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-base ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium file:text-foreground placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 md:text-sm"
              required
              v-model="form.last_name"
              placeholder="John"
            />
            <div v-show="form.errors.last_name">
              <p class="text-sm text-red-600 dark:text-red-500">
                {{ form.errors.last_name }}
              </p>
            </div>
          </div>

          <div class="grid gap-2">
            <label
              for="initials"
              class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70"
            >
              Initials
            </label>
            <input
              id="initials"
              type="text"
              class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-base ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium file:text-foreground placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 md:text-sm"
              required
              v-model="form.initials"
              placeholder="JD"
            />
            <div v-show="form.errors.initials">
              <p class="text-sm text-red-600 dark:text-red-500">
                {{ form.errors.initials }}
              </p>
            </div>
          </div>

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
              class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-base ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium file:text-foreground placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 md:text-sm"
              required
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
              class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-base ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium file:text-foreground placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 md:text-sm"
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

          <div class="grid gap-2">
            <label
              for="password_confirmation"
              class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70"
            >
              Confirm Password
            </label>
            <input
              id="password_confirmation"
              type="password"
              class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-base ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium file:text-foreground placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 md:text-sm"
              required
              autocomplete="new-password"
              v-model="form.password_confirmation"
              placeholder="Confirm Password"
            />
            <div v-show="form.errors.password_confirmation">
              <p class="text-sm text-red-600 dark:text-red-500">
                {{ form.errors.password_confirmation }}
              </p>
            </div>
          </div>

          <button
            type="submit"
            :disabled="form.processing"
            class="mt-4 w-full inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md text-sm font-medium border ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 [&_svg]:pointer-events-none [&_svg]:size-4 [&_svg]:shrink-0 bg-primary text-primary-foreground shadow hover:bg-primary/90 h-9 px-4 py-2"
          >
            <svg
              v-if="form.processing"
              class="mr-3 -ml-1 size-5 animate-spin text-white"
              xmlns="http://www.w3.org/2000/svg"
              fill="none"
              viewBox="0 0 24 24"
            >
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Register
          </button>
        </div>
      </template>
      <template v-else-if="selectedRegistrationType === 'new-user'">
        <div class="grid gap-6">
          <p>
            If you are needing to register a new account with an existing Organization, you must have one of the admins of that Organization send you an invite email.
          </p>
        </div>
      </template>
    </form>
    <div class="text-center text-sm text-muted-foreground">
      Already have an account?
      <Link :href="route('welcome')" class="text-foreground underline decoration-neutral-300 underline-offset-4 transition-colors duration-300 ease-out hover:decoration-current! dark:decoration-neutral-500">
        Log in
      </Link>
    </div>
  </AuthLayout>
</template>
