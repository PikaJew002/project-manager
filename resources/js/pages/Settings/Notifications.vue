<script setup>
import { computed } from 'vue';
import { Head, usePage, useForm } from '@inertiajs/vue3';
import SettingsLayout from '../../layouts/SettingsLayout.vue';

let page = usePage();

let notifications = computed(() => page.props.auth.user.settings.notifications);

let form = useForm({
  daily_tasks_due: notifications.value.daily_tasks_due,
  weekly_tasks_due: notifications.value.weekly_tasks_due,
  tasks_assigned: notifications.value.tasks_assigned,
  tasks_updated: notifications.value.tasks_updated,
  tasks_stale_7_days: notifications.value.tasks_stale_7_days,
  tasks_stale_30_days: notifications.value.tasks_stale_30_days,
});

function submit() {
  form.post(route('update-notifications'));
}
</script>

<template>
  <Head :title="page.props.app_name + ' | Notification settings'" />
  <SettingsLayout :pageRoute="route().current()">
    <form @submit.prevent="submit" class="divide-y divide-gray-200 bg-white/75 backdrop-blur-sm">
      <div class="grid max-w-7xl grid-cols-1 gap-x-8 gap-y-10 px-4 py-16 sm:px-6 md:grid-cols-3 lg:px-8">
        <div>
          <h2 class="text-base/7 font-semibold text-gray-900">Tasks Due</h2>
          <p class="mt-1 text-sm/6 text-gray-500">When a task you're on is due today.</p>
        </div>
        <div class="md:col-span-2">
          <div class="space-y-5">
            <div class="flex gap-3">
              <div class="flex h-6 shrink-0 items-center">
                <div class="group grid size-4 grid-cols-1">
                  <input v-model="form.daily_tasks_due" type="checkbox" id="daily_summary" aria-describedby="daily_summary_description" name="daily_summary" class="col-start-1 row-start-1 appearance-none rounded border border-gray-300 bg-white checked:border-indigo-600 checked:bg-indigo-600 indeterminate:border-indigo-600 indeterminate:bg-indigo-600 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 disabled:border-gray-300 disabled:bg-gray-100 disabled:checked:bg-gray-100 forced-colors:appearance-auto" />
                  <svg class="pointer-events-none col-start-1 row-start-1 size-3.5 self-center justify-self-center stroke-white group-has-[:disabled]:stroke-gray-950/25" viewBox="0 0 14 14" fill="none">
                    <path class="opacity-0 group-has-[:checked]:opacity-100" d="M3 8L6 11L11 3.5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    <path class="opacity-0 group-has-[:indeterminate]:opacity-100" d="M3 7H11" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                  </svg>
                </div>
              </div>
              <div class="text-sm/6">
                <label for="daily_summary" class="font-medium text-gray-900">Daily Summary</label>
                <p id="daily_summary_description" class="text-gray-500">Get notified the morning of when tasks are due.</p>
              </div>
            </div>
            <div class="flex gap-3">
              <div class="flex h-6 shrink-0 items-center">
                <div class="group grid size-4 grid-cols-1">
                  <input v-model="form.weekly_tasks_due" type="checkbox" id="weekly_summary" aria-describedby="weekly_summary_description" class="col-start-1 row-start-1 appearance-none rounded border border-gray-300 bg-white checked:border-indigo-600 checked:bg-indigo-600 indeterminate:border-indigo-600 indeterminate:bg-indigo-600 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 disabled:border-gray-300 disabled:bg-gray-100 disabled:checked:bg-gray-100 forced-colors:appearance-auto" />
                  <svg class="pointer-events-none col-start-1 row-start-1 size-3.5 self-center justify-self-center stroke-white group-has-[:disabled]:stroke-gray-950/25" viewBox="0 0 14 14" fill="none">
                    <path class="opacity-0 group-has-[:checked]:opacity-100" d="M3 8L6 11L11 3.5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    <path class="opacity-0 group-has-[:indeterminate]:opacity-100" d="M3 7H11" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                  </svg>
                </div>
              </div>
              <div class="text-sm/6">
                <label for="weekly_summary" class="font-medium text-gray-900">Weekly Summary</label>
                <p id="weekly_summary_description" class="text-gray-500">Get notified at the beginning of the week with a summary of all tasks due that week.</p>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="grid max-w-7xl grid-cols-1 gap-x-8 gap-y-10 px-4 py-16 sm:px-6 md:grid-cols-3 lg:px-8">
        <div>
          <h2 class="text-base/7 font-semibold text-gray-900">Tasks Assigned</h2>
          <p class="mt-1 text-sm/6 text-gray-500">When you're assigned to a task.</p>
        </div>
        <div class="md:col-span-2">
          <div class="space-y-5">
            <div class="flex gap-3">
              <div class="flex h-6 shrink-0 items-center">
                <div class="group grid size-4 grid-cols-1">
                  <input v-model="form.tasks_assigned" type="checkbox" id="daily_summary" aria-describedby="daily_summary_description" name="daily_summary" class="col-start-1 row-start-1 appearance-none rounded border border-gray-300 bg-white checked:border-indigo-600 checked:bg-indigo-600 indeterminate:border-indigo-600 indeterminate:bg-indigo-600 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 disabled:border-gray-300 disabled:bg-gray-100 disabled:checked:bg-gray-100 forced-colors:appearance-auto" />
                  <svg class="pointer-events-none col-start-1 row-start-1 size-3.5 self-center justify-self-center stroke-white group-has-[:disabled]:stroke-gray-950/25" viewBox="0 0 14 14" fill="none">
                    <path class="opacity-0 group-has-[:checked]:opacity-100" d="M3 8L6 11L11 3.5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    <path class="opacity-0 group-has-[:indeterminate]:opacity-100" d="M3 7H11" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                  </svg>
                </div>
              </div>
              <div class="text-sm/6">
                <label for="daily_summary" class="font-medium text-gray-900">When you're assigned to a task</label>
                <p id="daily_summary_description" class="text-gray-500">Get notified when you're assigned to a task.</p>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="grid max-w-7xl grid-cols-1 gap-x-8 gap-y-10 px-4 py-16 sm:px-6 md:grid-cols-3 lg:px-8">
        <div>
          <h2 class="text-base/7 font-semibold text-gray-900">Tasks Updated</h2>
          <p class="mt-1 text-sm/6 text-gray-500">When a task you're on is updated.</p>
        </div>
        <div class="md:col-span-2">
          <div class="space-y-5">
            <div class="flex gap-3">
              <div class="flex h-6 shrink-0 items-center">
                <div class="group grid size-4 grid-cols-1">
                  <input v-model="form.tasks_updated" type="checkbox" id="daily_summary" aria-describedby="daily_summary_description" name="daily_summary" class="col-start-1 row-start-1 appearance-none rounded border border-gray-300 bg-white checked:border-indigo-600 checked:bg-indigo-600 indeterminate:border-indigo-600 indeterminate:bg-indigo-600 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 disabled:border-gray-300 disabled:bg-gray-100 disabled:checked:bg-gray-100 forced-colors:appearance-auto" />
                  <svg class="pointer-events-none col-start-1 row-start-1 size-3.5 self-center justify-self-center stroke-white group-has-[:disabled]:stroke-gray-950/25" viewBox="0 0 14 14" fill="none">
                    <path class="opacity-0 group-has-[:checked]:opacity-100" d="M3 8L6 11L11 3.5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    <path class="opacity-0 group-has-[:indeterminate]:opacity-100" d="M3 7H11" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                  </svg>
                </div>
              </div>
              <div class="text-sm/6">
                <label for="daily_summary" class="font-medium text-gray-900">When a task you're on is updated</label>
                <p id="daily_summary_description" class="text-gray-500">Get notified when a task you're on is updated.</p>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="grid max-w-7xl grid-cols-1 gap-x-8 gap-y-10 px-4 py-16 sm:px-6 md:grid-cols-3 lg:px-8">
        <div>
          <h2 class="text-base/7 font-semibold text-gray-900">Tasks Stale</h2>
          <p class="mt-1 text-sm/6 text-gray-500">When a task you're on has had no activity.</p>
        </div>
        <div class="md:col-span-2">
          <div class="space-y-5">
            <div class="flex gap-3">
              <div class="flex h-6 shrink-0 items-center">
                <div class="group grid size-4 grid-cols-1">
                  <input v-model="form.tasks_stale_7_days" type="checkbox" id="tasks_stale_7_days" aria-describedby="tasks_stale_7_days_description" name="tasks_stale_7_days" class="col-start-1 row-start-1 appearance-none rounded border border-gray-300 bg-white checked:border-indigo-600 checked:bg-indigo-600 indeterminate:border-indigo-600 indeterminate:bg-indigo-600 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 disabled:border-gray-300 disabled:bg-gray-100 disabled:checked:bg-gray-100 forced-colors:appearance-auto" />
                  <svg class="pointer-events-none col-start-1 row-start-1 size-3.5 self-center justify-self-center stroke-white group-has-[:disabled]:stroke-gray-950/25" viewBox="0 0 14 14" fill="none">
                    <path class="opacity-0 group-has-[:checked]:opacity-100" d="M3 8L6 11L11 3.5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    <path class="opacity-0 group-has-[:indeterminate]:opacity-100" d="M3 7H11" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                  </svg>
                </div>
              </div>
              <div class="text-sm/6">
                <label for="tasks_stale_7_days" class="font-medium text-gray-900">After 7 Days</label>
                <p id="tasks_stale_7_days_description" class="text-gray-500">Get notified the morning of when tasks have had no activity for 7 days.</p>
              </div>
            </div>
            <div class="flex gap-3">
              <div class="flex h-6 shrink-0 items-center">
                <div class="group grid size-4 grid-cols-1">
                  <input v-model="form.tasks_stale_30_days" type="checkbox" id="tasks_stale_30_days" aria-describedby="tasks_stale_30_days_description" name="tasks_stale_30_days" class="col-start-1 row-start-1 appearance-none rounded border border-gray-300 bg-white checked:border-indigo-600 checked:bg-indigo-600 indeterminate:border-indigo-600 indeterminate:bg-indigo-600 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 disabled:border-gray-300 disabled:bg-gray-100 disabled:checked:bg-gray-100 forced-colors:appearance-auto" />
                  <svg class="pointer-events-none col-start-1 row-start-1 size-3.5 self-center justify-self-center stroke-white group-has-[:disabled]:stroke-gray-950/25" viewBox="0 0 14 14" fill="none">
                    <path class="opacity-0 group-has-[:checked]:opacity-100" d="M3 8L6 11L11 3.5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    <path class="opacity-0 group-has-[:indeterminate]:opacity-100" d="M3 7H11" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                  </svg>
                </div>
              </div>
              <div class="text-sm/6">
                <label for="tasks_stale_30_days" class="font-medium text-gray-900">After 30 Days</label>
                <p id="tasks_stale_30_days_description" class="text-gray-500">Get notified the morning of when tasks have had no activity for 30 days.</p>
              </div>
            </div>
          </div>
          <div class="mt-16 flex">
            <button type="submit" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-xs hover:bg-indigo-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Save</button>
          </div>
        </div>
      </div>
    </form>
  </SettingsLayout>
</template>
