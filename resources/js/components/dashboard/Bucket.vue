<script setup>
import { Menu, MenuButton, MenuItem, MenuItems } from '@headlessui/vue';
import { Float } from '@headlessui-float/vue';
import BucketWrapper from './BucketWrapper.vue';
import TaskCard from './TaskCard.vue';

let props = defineProps({
  title: {
    type: String,
    required: true,
  },
  subtitle: {
    type: [String, null],
    default: null,
  },
  tasks: {
    type: Array,
    default: () => [],
  },
  id: {
    type: [Number, String],
    default: '',
  },
  showThreeDots: {
    type: Boolean,
    default: true,
  },
  showAddTask: {
    type: Boolean,
    default: true,
  },
});

let emit = defineEmits(['clickTaskCard', 'clickAddTask', 'updateTaskStatus']);

function onClickAddTask() {
  if (props.id === '') {
    emit('clickAddTask');
  } else {
    emit('clickAddTask', props.id);
  }
}
</script>

<template>
  <BucketWrapper>
    <!-- column header -->
    <div class="px-[13px] mb-0">
      <!-- header title -->
      <div class="flex flex-row flex-nowrap w-[280px] min-h-0 min-w-0">
        <!-- bucket title -->
        <div class="flex grow shrink basis-0 items-center whitespace-nowrap text-clip h-8 my-0 -mx-[5px] font-semibold">
          <div class="flex flex-auto basis-0 items-center whitespace-nowrap leading-8 mx-[5px]">
            <div>
              {{ title }}
            </div>
            <div v-if="subtitle">
              <div class="flex flex-row flex-wrap grow shrink basis-auto justify-start items-center min-h-0 min-w-0 p-0 ml-2">
                <div class="flex flex-row flex-nowrap justify-center items-center h-4 py-px px-[7px] text-xs min-w-1 max-w-28 rounded border border-solid border-transparent bg-indigo-600 text-white">
                  <span class=" overflow-ellipsis whitespace-nowrap overflow-hidden items-center pointer-events-none">
                    {{ subtitle }}
                  </span>
                </div>
              </div>
            </div>
          </div>
          <!-- bucket 3 dots menu button -->
          <div v-if="showThreeDots" class="block grow-0 shrink-0 basis-auto items-center my-0 mx-[5px] px-1">
            <!-- add headlessui dropdown menu -->
            <Menu as="div" class="relative inline-flex">
              <Float placement="bottom-start">
                <div>
                  <MenuButton class="block size-5 rounded-md text-sm font-semibold text-gray-900">
                    <svg class="inline" fill="currentColor" aria-hidden="true" width="20" height="20" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M6.25 10a1.25 1.25 0 1 1-2.5 0 1.25 1.25 0 0 1 2.5 0Zm5 0a1.25 1.25 0 1 1-2.5 0 1.25 1.25 0 0 1 2.5 0ZM15 11.25a1.25 1.25 0 1 0 0-2.5 1.25 1.25 0 0 0 0 2.5Z" fill="currentColor"></path></svg>
                  </MenuButton>
                </div>
                <transition enter-active-class="transition ease-out duration-100" enter-from-class="transform opacity-0 scale-95" enter-to-class="transform opacity-100 scale-100" leave-active-class="transition ease-in duration-75" leave-from-class="transform opacity-100 scale-100" leave-to-class="transform opacity-0 scale-95">
                  <MenuItems class="w-56 rounded-md bg-white shadow-lg ring-1 ring-black/5 focus:outline-none">
                    <div class="py-1">
                      <MenuItem v-slot="{ active }">
                        <a href="#" :class="[active ? 'bg-gray-100 text-gray-900 outline-none' : 'text-gray-700', 'block px-4 py-2 text-sm']">Rename</a>
                      </MenuItem>
                      <MenuItem v-slot="{ active }">
                        <a href="#" :class="[active ? 'bg-gray-100 text-gray-900 outline-none' : 'text-gray-700', 'block px-4 py-2 text-sm']">Delete</a>
                      </MenuItem>
                    </div>
                  </MenuItems>
                </transition>
              </Float>
            </Menu>
          </div>
        </div>
      </div>
      <!-- header add task button -->
      <div v-if="showAddTask" class="flex flex-row flex-nowrap mb-4">
        <button @click="onClickAddTask" class="flex flex-row flex-nowrap grow-0 shrink-0 basis-[280px] items-center justify-between space-x-3 rounded-lg border border-gray-300 p-2 text-left shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 h-10 mt-1 py-0 px-3">
          <span class="flex grow-0 shrink-0 basis-auto py-0 px-2">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
          </span>
          <span class="flex grow shrink basis-0">Add task</span>
        </button>
      </div>
    </div>
    <!-- column content (tasks) -->
    <div class="flex flex-col flex-nowrap grow shrink basis-auto min-h-0 min-w-0 overflow-hidden -mt-[9px] px-[13px]">
      <div v-if="tasks.length > 0" class="flex flex-col flex-nowrap grow-0 shrink-0 basis-auto min-w-0 min-h-[10px] pt-[13px]">
        <div class="min-w-0 min-h-0">
          <!-- list -->
          <div class="relative w-[280px] min-w-0 min-h-0">
            <slot :tasks="tasks">
              <TaskCard
                v-for="task in tasks"
                :key="task.id"
                :task="task"
                @edit-task="(t) => $emit('clickTaskCard', t)"
                @update-task-status="(update) => $emit('updateTaskStatus', update)"
              />
            </slot>
          </div>
        </div>
      </div>
      <div v-else class="flex flex-col flex-nowrap grow-0 shrink-0 basis-auto w-[294px] min-w-0 min-h-[10px] pt-[13px]">
        <div></div>
        <div class="grow shrink basis-auto box-border h-7 min-h-0 min-w-0"></div>
      </div>
      <div class="flex flex-col flex-nowrap grow shrink-0 basis-auto min-h-0 min-w-0 w-[294px]">
        <div class="flex flew-row flex-nowrap grow-0 shrink-0 basis-10 mb-[6px] min-h-0 min-w-0">

        </div>
      </div>
    </div>
  </BucketWrapper>
</template>
