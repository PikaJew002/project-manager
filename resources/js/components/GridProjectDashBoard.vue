<script setup>
import { computed } from 'vue';
import { usePagesStore } from '../stores/pages.js';
import InitialsList from './shared/InitialsList.vue';

let props = defineProps({
  tasks: {
    type: Array,
    required: true,
  },
});

let emit = defineEmits(['editTask']);
</script>

<template>
  <div class="px-4 sm:px-6 lg:px-8 mt-4 sm:mt-8 flow-root">
    <div class="-mx-4 -my-2 sm:-mx-6 lg:-mx-8 overflow-x-auto">
      <div class="inline-block min-w-full py-2 align-middle">
        <table class="min-w-full border-separate border-spacing-0">
          <thead>
            <tr>
              <th
                scope="col"
                class="sticky top-0 z-10 border-b border-gray-300 bg-white py-3.5 pl-4 pr-3 text-nowrap text-left text-sm font-semibold text-gray-900 backdrop-blur backdrop-filter sm:pl-6 lg:pl-8"
              >
                Task Name
              </th>
              <th
                scope="col"
                class="sticky top-0 z-10 hidden border-b border-gray-300 bg-white px-3 py-3.5 text-nowrap text-left text-sm font-semibold text-gray-900 backdrop-blur backdrop-filter sm:table-cell"
              >
                Assigned To
              </th>
              <th
                scope="col"
                class="sticky top-0 z-10 hidden border-b border-gray-300 bg-white px-3 py-3.5 text-nowrap text-left text-sm font-semibold text-gray-900 backdrop-blur backdrop-filter sm:table-cell"
              >
                Buckets
              </th>
              <th
                scope="col"
                class="sticky top-0 z-10 hidden border-b border-gray-300 bg-white px-3 py-3.5 text-nowrap text-left text-sm font-semibold text-gray-900 backdrop-blur backdrop-filter lg:table-cell"
              >
                Due Date
              </th>
              <th
                scope="col"
                class="sticky top-0 z-10 border-b border-gray-300 bg-white px-3 py-3.5 text-nowrap text-left text-sm font-semibold text-gray-900 backdrop-blur backdrop-filter"
              >
                Priority
              </th>
              <th
                scope="col"
                class="sticky top-0 z-10 border-b border-gray-300 bg-white px-3 py-3.5 text-nowrap text-left text-sm font-semibold text-gray-900 backdrop-blur backdrop-filter"
              >
                Progress
              </th>
              <th
                scope="col"
                class="sticky top-0 z-10 border-b border-gray-300 bg-white py-3.5 pl-3 pr-4 backdrop-blur backdrop-filter sm:pr-6 lg:pr-8"
              >
                <span class="sr-only">Edit</span>
              </th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(task, index) in tasks" :key="task.id">
              <td
                :class="[
                  tasks.length - 1 !== index ? 'border-b border-gray-200' : '',
                  'whitespace-nowrap py-4 pl-4 pr-3 bg-white text-sm font-medium text-gray-900 sm:pl-6 lg:pl-8',
                ]"
              >
                <button type="button" @click="$emit('editTask', task)">{{ task.name }}</button>
              </td>
              <td
                :class="[
                  tasks.length - 1 !== index ? 'border-b border-gray-200' : '',
                  'whitespace-nowrap px-3 py-4 bg-white text-sm text-gray-500 able-cell',
                ]"
              >
                <InitialsList :items="task.assigned_to" />
              </td>
              <td
                :class="[
                  tasks.length - 1 !== index ? 'border-b border-gray-200' : '',
                  'whitespace-nowrap px-3 py-4 bg-white text-sm text-gray-500 table-cell',
                ]"
              >
                {{ task.buckets.map(b => b.name).join(', ') }}
              </td>
              <td
                :class="[
                  tasks.length - 1 !== index ? 'border-b border-gray-200' : '',
                  'whitespace-nowrap px-3 py-4 bg-white text-sm text-gray-500 table-cell',
                ]"
              >
                {{ task.due_at ?new Date(task.due_at).toLocaleString('en-US', { timeZone: 'EST' }) : '' }}
              </td>
              <td
                :class="[
                  tasks.length - 1 !== index ? 'border-b border-gray-200' : '',
                  'whitespace-nowrap px-3 py-4 bg-white text-sm text-gray-500',
                ]"
              >
                {{ task.priority }}
              </td>
              <td
                :class="[
                  tasks.length - 1 !== index ? 'border-b border-gray-200' : '',
                  'whitespace-nowrap px-3 py-4 bg-white text-sm text-gray-500',
                ]"
              >
                {{ task.status }}
              </td>
              <td
                :class="[
                  tasks.length - 1 !== index ? 'border-b border-gray-200' : '',
                  'relative whitespace-nowrap py-4 bg-white pl-3 pr-4 text-right text-sm font-medium sm:pr-8 lg:pr-8',
                ]"
              >
                <button
                  type="button"
                  @click="$emit('editTask', task)"
                  class="text-indigo-600 hover:text-indigo-900"
                >
                  Edit<span class="sr-only">, {{ task.name }}</span>
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>
