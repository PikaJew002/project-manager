<script setup>
import TaskRow from './dashboard/TaskRow.vue';
import TaskRowWrapper from './dashboard/TaskRowWrapper.vue';

let props = defineProps({
  tasks: {
    type: Array,
    required: true,
  },
});

let emit = defineEmits(['editTask', 'updateTaskStatus']);
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
                class="sticky top-0 z-10 border-b border-gray-300 bg-white/75 py-3.5 pl-4 pr-3 text-nowrap text-left text-sm font-semibold text-gray-900 backdrop-blur backdrop-filter sm:pl-6 lg:pl-8"
              >
                <slot name="header-1" />
              </th>
              <th
                scope="col"
                class="sticky top-0 z-10 table-cell border-b border-gray-300 bg-white/75 px-3 py-3.5 text-nowrap text-left text-sm font-semibold text-gray-900 backdrop-blur backdrop-filter"
              >
                <slot name="header-2">
                  Task Name
                </slot>
              </th>
              <th
                scope="col"
                class="sticky top-0 z-10 table-cell border-b border-gray-300 bg-white/75 px-3 py-3.5 text-nowrap text-left text-sm font-semibold text-gray-900 backdrop-blur backdrop-filter"
              >
                <slot name="header-3">
                  Assigned To
                </slot>
              </th>
              <th
                scope="col"
                class="sticky top-0 z-10 table-cell border-b border-gray-300 bg-white/75 px-3 py-3.5 text-nowrap text-left text-sm font-semibold text-gray-900 backdrop-blur backdrop-filter"
              >
                <slot name="header-4">
                  Projects
                </slot>
              </th>
              <th
                scope="col"
                class="sticky top-0 z-10 table-cell border-b border-gray-300 bg-white/75 px-3 py-3.5 text-nowrap text-left text-sm font-semibold text-gray-900 backdrop-blur backdrop-filter"
              >
                <slot name="header-5">
                  Due Date
                </slot>
              </th>
              <th
                scope="col"
                class="sticky top-0 z-10 table-cell border-b border-gray-300 bg-white/75 px-3 py-3.5 text-nowrap text-left text-sm font-semibold text-gray-900 backdrop-blur backdrop-filter"
              >
                <slot name="header-6">
                  Priority
                </slot>
              </th>
              <th
                scope="col"
                class="sticky top-0 z-10 table-cell border-b border-gray-300 bg-white/75 py-3.5 pl-3 pr-4 backdrop-blur backdrop-filter sm:pr-6 lg:pr-8"
              >
                <slot name="header-7">
                  <span class="sr-only">Edit</span>
                </slot>
              </th>
            </tr>
          </thead>
          <tbody>
            <slot name="default" :tasks="tasks">
              <TaskRowWrapper
                :tasks="tasks"
                @edit-task="(t) => $emit('editTask', t)"
                @update-task-status="(all) => $emit('updateTaskStatus', all)"
              />
            </slot>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>
