<script setup>
import { ref } from 'vue';
import { Head, useForm } from '@inertiajs/vue3';
import AuthLayout from '../layouts/AuthLayout.vue';

let props = defineProps({
  accepted_at: {
    type: String,
  },
  declined_at: {
    type: String,
  },
  invited_by: {
    type: String,
  },
  invited_to: {
    type: String,
  },
  token: {
    type: String,
  },
});

let form = useForm({
  first_name: '',
  last_name: '',
  initials: '',
  password: '',
  password_confirmation: '',
  token: props.token,
});

let formDecline = useForm({
  token: props.token,
});

let formUnDecline = useForm({
  token: props.token,
});

let acceptInvite = ref(props.accepted_at === null ? (props.declined_at === null ? null : false) : true);

function submitRegistation() {
  form.post(route('invite-accept'), {
    onError: () => form.reset('password', 'password_confirmation'),
  });
}

function submitDecline() {
  formDecline.post(route('invite-decline'));
}

function submitUnDecline() {
  formUnDecline.post(route('invite-reset'), {
    onSuccess: (results) => {
      acceptInvite.value = props.accepted_at === null ? (props.declined_at === null ? null : false) : true;
    },
  });
}
</script>

<template>
  <Head title="Register" />
  <AuthLayout>
    <p>
      You have been invited to {{ invited_to }} by {{ invited_by }}!<br>
      Please accept or decline the invitation below.
    </p>
    <div class="grid grid-cols-1 gap-4">
      <div class="group relative flex items-center justify-center space-x-3 rounded-lg border border-gray-300 bg-white px-6 py-3 shadow-sm focus-within:ring-2 focus-within:ring-indigo-500 focus-within:ring-offset-2 hover:border-gray-400">
        <div class="min-w-0">
          <button @click.prevent="acceptInvite = true" type="button" class="focus:outline-none">
            <span class="absolute inset-0" aria-hidden="true" />
            <p class="text-sm font-medium text-gray-900">Accept</p>
          </button>
        </div>
      </div>
      <form v-if="acceptInvite === true" @submit.prevent="submitRegistation" class="flex flex-col gap-6">
        <div class="grid gap-6">
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
            class="my-4 w-full inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md text-sm font-medium border ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 [&_svg]:pointer-events-none [&_svg]:size-4 [&_svg]:shrink-0 bg-primary text-primary-foreground shadow hover:bg-primary/90 h-9 px-4 py-2"
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
      </form>
      <div class="group relative flex items-center justify-center space-x-3 rounded-lg border border-gray-300 bg-white px-6 py-3 shadow-sm focus-within:ring-2 focus-within:ring-indigo-500 focus-within:ring-offset-2 hover:border-gray-400">
        <div class="min-w-0">
          <button @click.prevent="acceptInvite = false" type="button" class="focus:outline-none">
            <span class="absolute inset-0" aria-hidden="true" />
            <p class="text-sm font-medium text-gray-900">Decline</p>
          </button>
        </div>
      </div>
      <template v-if="acceptInvite === false">
        <form v-if="declined_at === null" @submit.prevent="submitDecline" class="flex flex-col gap-6">
          <button class="text-foreground underline decoration-neutral-300 underline-offset-4 transition-colors duration-300 ease-out hover:decoration-current! dark:decoration-neutral-500">Confirm Decline</button>
        </form>
        <form v-else @submit.prevent="submitUnDecline">
          You declined this invitaion. If this was done in error, you may reset the invitaion <button class="text-foreground underline decoration-neutral-300 underline-offset-4 transition-colors duration-300 ease-out hover:decoration-current! dark:decoration-neutral-500">here</button>.
        </form>
      </template>
    </div>
  </AuthLayout>
</template>

