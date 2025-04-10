<script setup>
import TaskStatus from '../shared/TaskStatus.vue';
import TaskRow from './TaskRow.vue';

let props = defineProps({
  tasks: {
    type: Array,
    required: true,
  },
  depth: {
    type: Number,
    default: 1,
  },
});

let emit = defineEmits(['editTask', 'updateTaskStatus']);
</script>

<template>
  <template v-for="task in tasks" :key="task.id">
    <TaskRow
      :task="task"
      @edit-task="openEditTaskModal"
      @update-task-status="updateTaskStatus"
    >
      <template #col-1="{ task }">
        <td class="table-cell border-b border-gray-200 whitespace-nowrap bg-white text-sm text-gray-900">
          <div class="flex flex-row items-center justify-start select-none">
            <div v-for="level in depth - 1" :key="level" class="bg-indigo-600 w-4 h-[65px] mr-1"></div>
            <div
              :class="
              // depth === 1 ? 'ml-0' : (depth === 2 ? 'ml-5' : (depth === 3 ? 'ml-10' : 'ml-14'))
              'ml-4 mr-2'
              "
            >
              <TaskStatus
                classes="flex flex-row flex-nowrap grow-0 shrink-0 basis-[18px] justify-start items-center font-normal"
                :status="task.status"
                @update-task-status="(status) => $emit('updateTaskStatus', { task, newStatus: status })"
              />
            </div>
          </div>
        </td>
      </template>
      <template #col-4="{ task }">
        <ul class="flex flex-row flex-wrap grow shrink basis-auto justify-start min-h-0 min-w-0 p-0">
          <li v-for="bucket in task.buckets" :key="id" class="mr-[6px] mb-[6px] last:mr-0 only:mb-0">
            <div class="flex flex-row flex-nowrap justify-center items-center h-4 py-px px-[7px] text-xs min-w-1 max-w-28 rounded border border-solid border-transparent bg-indigo-600 text-white">
              <span class=" overflow-ellipsis whitespace-nowrap overflow-hidden items-center pointer-events-none">
                {{ bucket.name }}
              </span>
            </div>
          </li>
        </ul>
      </template>
    </TaskRow>

    <TaskRowWrapper
      v-if="task.tasks.length > 0"
      :tasks="task.tasks"
      :depth="depth + 1"
    />
  </template>
</template>
