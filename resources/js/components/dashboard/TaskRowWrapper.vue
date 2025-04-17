<script setup>
import TaskStatus from '../shared/TaskStatus.vue';
import Row from './Row.vue';
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
      @edit-task="(t) => $emit('editTask', t)"
    >
      <template #col-1="{ task }">
        <td class="border-b border-gray-200 whitespace-nowrap bg-white text-sm text-gray-900 w-16">
          <div class="flex flex-row items-center justify-start select-none">
            <div v-for="level in depth" :key="level" :class="['w-4 h-[65px] mr-1 rounded-sm', task.status !== 'Completed' ? 'bg-gray-200/75' : 'bg-indigo-600/75']"></div>
            <div class="ml-4 mr-2">
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
        <Row>
          <ul class="flex flex-row flex-wrap grow shrink basis-auto justify-start min-h-0 min-w-0 p-0">
            <li v-for="bucket in task.buckets" :key="bucket.id" class="mr-[6px] mb-[6px] last:mr-0 only:mb-0">
              <div class="flex flex-row flex-nowrap justify-center items-center h-4 py-px px-[7px] text-xs min-w-1 max-w-28 rounded border border-solid border-transparent bg-indigo-600 text-white">
                <span class=" overflow-ellipsis whitespace-nowrap overflow-hidden items-center pointer-events-none">
                  {{ bucket.name }}
                </span>
              </div>
            </li>
          </ul>
        </Row>
      </template>
    </TaskRow>

    <TaskRowWrapper
      v-if="task.tasks.length > 0"
      :tasks="task.tasks"
      :depth="depth + 1"
      @edit-task="(t) => $emit('editTask', t)"
      @update-task-status="(all) => $emit('updateTaskStatus', all)"
    />
  </template>
</template>
