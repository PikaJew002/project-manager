<script setup>
import ColumnHeader from './dashboard/ColumnHeader.vue';
import TaskRowWrapper from './dashboard/TaskRowWrapper.vue';

let props = defineProps({
  items: {
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
        <table class="min-w-full border-separate border-spacing-0 table-fixed">
          <thead>
            <slot name="header">
              <tr>
                <slot name="header-1">
                  <ColumnHeader first />
                </slot>
                <slot name="header-2">
                  <ColumnHeader>
                    Task Name
                  </ColumnHeader>
                </slot>
                <slot name="header-3">
                  <ColumnHeader>
                    Assigned To
                  </ColumnHeader>
                </slot>
                <slot name="header-4">
                  <ColumnHeader>
                    Projects
                  </ColumnHeader>
                </slot>
                <slot name="header-5">
                  <ColumnHeader>
                    Due Date
                  </ColumnHeader>
                </slot>
                <slot name="header-6">
                  <ColumnHeader last>
                    Priority
                  </ColumnHeader>
                </slot>
              </tr>
            </slot>
          </thead>
          <tbody>
            <slot name="default" :items="items">
              <TaskRowWrapper
                :tasks="items"
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
