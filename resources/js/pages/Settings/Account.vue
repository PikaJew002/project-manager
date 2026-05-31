<script setup>
import { Head, usePage, useForm } from '@inertiajs/vue3';
import SettingsLayout from '../../layouts/SettingsLayout.vue';

let page = usePage();

let form = useForm({
  first_name: page.props.auth.user.first_name,
  last_name: page.props.auth.user.last_name,
  email: page.props.auth.user.email,
  timezone: page.props.auth.user.timezone,
});

let timezones = [
  { value: 'Pacific Standard Time', label: 'Pacific Standard Time' },
  { value: 'Eastern Standard Time', label: 'Eastern Standard Time' },
  { value: 'Greenwich Mean Time', label: 'Greenwich Mean Time' },
];

function submit() {
  form.post(route('settings.account'));
}
</script>

<template>
  <Head title="Settings" />
  <SettingsLayout :pageRoute="route().current()">
    <form @submit.prevent="submit" class="divide-y divide-gray-200 bg-white/75 backdrop-blur-sm">
      <div class="grid max-w-7xl grid-cols-1 gap-x-8 gap-y-10 px-4 py-16 sm:px-6 md:grid-cols-3 lg:px-8">
        <div>
          <h2 class="text-base/7 font-semibold text-gray-900">Personal Information</h2>
        </div>

        <div class="md:col-span-2">
          <div class="grid grid-cols-1 gap-x-6 gap-y-8 sm:max-w-xl sm:grid-cols-6">
            <div class="col-span-full flex items-center gap-x-8">
              <img src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80" alt="" class="size-24 flex-none rounded-lg bg-gray-100 object-cover outline outline-1 -outline-offset-1 outline-black/5" />
              <div>
                <button type="button" class="rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-100">Change avatar</button>
                <p class="mt-2 text-xs/5 text-gray-500">JPG, GIF or PNG. 1MB max.</p>
              </div>
            </div>

            <div class="sm:col-span-3">
              <label for="first-name" class="block text-sm/6 font-medium text-gray-900">First name</label>
              <div class="mt-2">
                <input v-model="form.first_name" type="text" name="first-name" id="first-name" autocomplete="given-name" class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6" />
              </div>
            </div>

            <div class="sm:col-span-3">
              <label for="last-name" class="block text-sm/6 font-medium text-gray-900">Last name</label>
              <div class="mt-2">
                <input v-model="form.last_name" type="text" name="last-name" id="last-name" autocomplete="family-name" class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6" />
              </div>
            </div>

            <div class="col-span-full">
              <label for="email" class="block text-sm/6 font-medium text-gray-900">Email address</label>
              <div class="mt-2">
                <input v-model="form.email" id="email" name="email" type="email" autocomplete="email" class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6" />
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="grid max-w-7xl grid-cols-1 gap-x-8 gap-y-10 px-4 py-16 sm:px-6 md:grid-cols-3 lg:px-8">
        <div>
          <h2 class="text-base/7 font-semibold text-gray-900">Change password</h2>
          <p class="mt-1 text-sm/6 text-gray-500">Update your password associated with your account.</p>
        </div>

        <div class="md:col-span-2">
          <div class="grid grid-cols-1 gap-x-6 gap-y-8 sm:max-w-xl sm:grid-cols-6">
            <div class="col-span-full">
              <label for="current-password" class="block text-sm/6 font-medium text-gray-900">Current password</label>
              <div class="mt-2">
                <input id="current-password" name="current_password" type="password" autocomplete="current-password" class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6" />
              </div>
            </div>

            <div class="col-span-full">
              <label for="new-password" class="block text-sm/6 font-medium text-gray-900">New password</label>
              <div class="mt-2">
                <input id="new-password" name="new_password" type="password" autocomplete="new-password" class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6" />
              </div>
            </div>

            <div class="col-span-full">
              <label for="confirm-password" class="block text-sm/6 font-medium text-gray-900">Confirm password</label>
              <div class="mt-2">
                <input id="confirm-password" name="confirm_password" type="password" autocomplete="new-password" class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6" />
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="grid max-w-7xl grid-cols-1 gap-x-8 gap-y-10 px-4 py-16 sm:px-6 md:grid-cols-3 lg:px-8">
        <div>
          <h2 class="text-base/7 font-semibold text-gray-900">Delete account</h2>
          <p class="mt-1 text-sm/6 text-gray-500">No longer want to use our service? You can delete your account here. This action is not reversible. All information related to this account will be deleted permanently.</p>
        </div>

        <div class="md:col-span-2">
          <div class="grid grid-cols-1 gap-x-6 gap-y-8 sm:max-w-xl sm:grid-cols-6">
            <div class="col-span-full">
              <button type="submit" class="rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500">Yes, delete my account</button>
            </div>
          </div>

          <div class="mt-24 flex">
            <button type="submit" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Save</button>
          </div>
        </div>
      </div>
    </form>
  </SettingsLayout>
</template>
