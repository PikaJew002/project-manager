<script setup>
import { computed } from 'vue';
import InitialsList from '../shared/InitialsList.vue';
import TaskStatus from '../shared/TaskStatus.vue';

let props = defineProps({
  task: {
    type: Object,
    required: false,
  },
});

let emit = defineEmits(['editTask', 'updateTaskStatus']);

let projects = computed(() => {
  return props.task.projects.concat(props.task.buckets.map((b) => b.project));
});
</script>

<template>
  <tr @click="$emit('editTask', task)" class="cursor-pointer h-[65px]">
    <slot name="col-1" :task="task">
      <td class="table-cell border-b border-gray-200 whitespace-nowrap py-4 bg-white text-sm text-gray-900">
        <TaskStatus :status="task.status" @update-task-status="(status) => $emit('updateTaskStatus', { task, newStatus: status })" />
      </td>
    </slot>
    <td class="table-cell border-b border-gray-200 whitespace-nowrap px-3 py-4 bg-white text-sm text-gray-700">
      <slot name="col-2" :task="task">
        {{ task.name }}
      </slot>
    </td>
    <td class="table-cell border-b border-gray-200 whitespace-nowrap px-3 py-4 bg-white text-sm text-gray-700">
      <slot name="col-3" :task="task">
        <InitialsList :items="task.assigned_to" />
      </slot>
    </td>
    <td class="table-cell border-b border-gray-200 whitespace-nowrap px-3 py-4 bg-white text-sm text-gray-700">
      <slot name="col-4" :task="task">
        <InitialsList :items="projects" />
      </slot>
    </td>
    <td class="table-cell border-b border-gray-200 whitespace-nowrap px-3 py-4 bg-white text-sm text-gray-700">
      <slot name="col-5">
        {{ task.due_at ?new Date(task.due_at).toLocaleString('en-US', { timeZone: 'EST' }) : '' }}
      </slot>
    </td>
    <td class="table-cell border-b border-gray-200 whitespace-nowrap px-3 py-4 bg-white text-sm text-gray-700">
      <slot name="col-6">
        {{ task.priority }}
      </slot>
    </td>
    <td class="table-cell border-b border-gray-200 relative whitespace-nowrap py-4 pl-3 pr-4 text-right bg-white text-sm font-medium sm:pr-8 lg:pr-8">
      <slot name="col-7">
        <button
          type="button"
          @click="$emit('editTask', task)"
          class="text-indigo-600 hover:text-indigo-900"
        >
          Edit<span class="sr-only">, {{ task.name }}</span>
        </button>
      </slot>
    </td>
  </tr>
</template>
