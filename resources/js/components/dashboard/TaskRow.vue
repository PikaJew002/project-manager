<script setup>
import { computed } from 'vue';
import InitialsList from '../shared/InitialsList.vue';
import TaskStatus from '../shared/TaskStatus.vue';
import Row from './Row.vue';

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
  <tr @click="$emit('editTask', task)" class="cursor-pointer h-[49px]">
    <slot name="col-1" :task="task">
      <Row first>
        <TaskStatus :status="task.status" @update-task-status="(status) => $emit('updateTaskStatus', { task, newStatus: status })" />
      </Row>
    </slot>
    <slot name="col-2" :task="task">
      <Row>
        {{ task.name }}
      </Row>
    </slot>
    <slot name="col-3" :task="task">
      <Row>
        <InitialsList :items="task.assigned_to" />
      </Row>
    </slot>
    <slot name="col-4" :task="task">
      <Row>
        <InitialsList :items="projects" />
      </Row>
    </slot>
    <slot name="col-5">
      <Row>
        {{ task.due_at ? new Date(task.due_at).toLocaleString('en-US', { timeZone: 'EST' }) : '' }}
      </Row>
    </slot>
    <slot name="col-6">
      <Row last>
        {{ task.priority }}
      </Row>
    </slot>
  </tr>
</template>
