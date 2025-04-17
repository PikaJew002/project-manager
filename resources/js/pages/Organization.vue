<script setup>
import { Head, useForm, usePage } from '@inertiajs/vue3';
import AppLayout from '../layouts/AppLayout.vue';
import GridDashboard from '../components/GridDashboard.vue';
import Row from '../components/dashboard/Row.vue';
import ColumnHeader from '../components/dashboard/ColumnHeader.vue';
import InitialsList from '../components/shared/InitialsList.vue';
import Pill from '../components/dashboard/Pill.vue';

let props = defineProps({
  errors: Object,
  organization: {
    type: Object,
    required: true,
  },
});

let page = usePage();

let form = useForm({
  email: '',
});

function onNewInvite() {
  form.post(route('create-invite'), {
    onFinish: () => {
      form.reset();
    },
  });
}
</script>

<template>
  <Head title="Project Manager" />
  <AppLayout :page-route="route().current()">
    <div class="sm:flex sm:items-center pt-10 px-4 sm:px-6 lg:px-8">
      <div class="sm:flex-auto">
        <h1 class="text-base font-semibold text-gray-900">Your Organization</h1>
        <p class="mt-2 text-sm text-gray-700">A list of all users in your organization</p>
      </div>
      <div class="flex flex-col gap-y-8 sm:flex-row">
        <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none order-first sm:order-last">
          <form @submit.prevent="onNewInvite" class="flex items-start" v-if="organization.users.length > 0">
            <div>
              <input v-model="form.email" type="text" placeholder="New User Email" maxlength="256" aria-label="New User Email" class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6" />
              <div v-show="form.errors.email">
                <p class="text-sm text-red-600 dark:text-red-500">
                  {{ form.errors.email }}
                </p>
              </div>
            </div>
            <button
              type="submit"
              class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 ml-4 text-sm text-nowrap font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
            >
              Send Invite
            </button>
          </form>
        </div>
      </div>
    </div>
    <GridDashboard
      v-if="organization.users.length > 0"
      :items="organization.users"
    >
      <template #header>
        <tr>
          <ColumnHeader first>

          </ColumnHeader>
          <ColumnHeader>
            Name
          </ColumnHeader>
          <ColumnHeader>
            Email
          </ColumnHeader>
          <ColumnHeader>
            User Since
          </ColumnHeader>
        </tr>
      </template>
      <template #default="{ items }">
        <tr v-for="user in organization.invites" :key="user.id" class="h-[49px]">
          <Row first width="w-6 px-4">
            <InitialsList :items="[{ initials: 'PU', name: 'Pending User', id: 0}]" />
          </Row>
          <Row width="w-80">
            Pending User
          </Row>
          <Row>
            {{ user.email }}
          </Row>
          <Row width="w-80">
            <Pill>
              Pending Invitation
            </Pill>
          </Row>
        </tr>
        <tr v-for="user in items" :key="user.id" class="h-[49px]">
          <Row first width="w-6 px-4">
            <InitialsList :items="[user]" />
          </Row>
          <Row width="w-80">
            <div class="flex items-center">
              <div>
                {{ user.first_name }} {{ user.last_name }}
              </div>
              <div class="ml-2 flex mt-[6px] items-center grow-0">
                <Pill v-if="user.is_admin">
                  Admin
                </Pill>
                <Pill v-if="user.is_me">
                  Me
                </Pill>
              </div>
            </div>
          </Row>
          <Row>
            {{ user.email }}
          </Row>
          <Row>
            {{ user.created_at ? new Date(user.created_at).toLocaleString('en-US', { timeZone: 'EST' }) : '' }}
          </Row>
        </tr>
      </template>
    </GridDashboard>
    <div v-else class="mx-auto max-w-lg">
      <div>
        <div class="text-center">
          <svg class="mx-auto size-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 48 48" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M34 40h10v-4a6 6 0 00-10.712-3.714M34 40H14m20 0v-4a9.971 9.971 0 00-.712-3.714M14 40H4v-4a6 6 0 0110.713-3.714M14 40v-4c0-1.313.253-2.566.713-3.714m0 0A10.003 10.003 0 0124 26c4.21 0 7.813 2.602 9.288 6.286M30 14a6 6 0 11-12 0 6 6 0 0112 0zm12 6a4 4 0 11-8 0 4 4 0 018 0zm-28 0a4 4 0 11-8 0 4 4 0 018 0z" />
          </svg>
          <h3 class="mt-2 text-sm font-semibold text-gray-900">No Users</h3>
          <p class="mt-1 text-sm text-gray-500">Get started by adding a new user to your organization.</p>
          <div class="mt-6">
            <form @submit.prevent="onNewInvite" class="flex">
              <input v-model="form.email" type="text" placeholder="New User Email" maxlength="256" aria-label="New User Email" class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6" />
              <button
                type="submit"
                class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 ml-4 text-sm text-nowrap font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
              >
                Send Invite
              </button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>
