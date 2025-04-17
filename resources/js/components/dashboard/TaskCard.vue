<script setup>
import InitialsList from '../shared/InitialsList.vue';
import TaskStatus from '../shared/TaskStatus.vue';

let props = defineProps({
  task: {
    type: Object,
    required: true,
  },
});

let emit = defineEmits(['editTask', 'updateTaskStatus']);
</script>

<template>
  <div @click="$emit('editTask', task)" class="relative w-full my-1 mx-0 rounded-lg border border-gray-300 bg-white shadow-sm focus-within:ring-2 focus-within:ring-indigo-500 focus-within:ring-offset-2 hover:border-gray-400" tabindex="0">
    <div>
      <div class="flex flex-row flex-nowrap cursor-pointer">
        <div class="flex flex-row flex-nowrap grow-0 shrink-0 basis-auto min-h-[10px] w-full">
          <div class="flex flex-col flex-nowrap relative grow shrink basis-0 min-h-0 min-w-0">
            <!-- remove hidden if we want to implement it, not working now becuase of stacking context: need a special portal or something -->
            <!-- <Menu as="div" class="hidden flex flex-row flex-nowrap grow-0 shrink-0 basis-auto absolute top-3 right-[2px] my-0 mx-[2px] items-start leading-5 tracking-wide text-xs">
              <div>
                <MenuButton class="flex flex-row flex-nowrap grow-0 shrink-0 basis-auto my-0 mx-[2px] py-0 px-[2px]">
                  <span class="flex items-center justify-center">
                    <svg class="leading-[0] inline text-gray-400" fill="currentColor" aria-hidden="true" width="20" height="20" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M6.25 10a1.25 1.25 0 1 1-2.5 0 1.25 1.25 0 0 1 2.5 0Zm5 0a1.25 1.25 0 1 1-2.5 0 1.25 1.25 0 0 1 2.5 0ZM15 11.25a1.25 1.25 0 1 0 0-2.5 1.25 1.25 0 0 0 0 2.5Z" fill="currentColor"></path></svg>
                  </span>
                </MenuButton>
              </div>
              <transition enter-active-class="transition ease-out duration-100" enter-from-class="transform opacity-0 scale-95" enter-to-class="transform opacity-100 scale-100" leave-active-class="transition ease-in duration-75" leave-from-class="transform opacity-100 scale-100" leave-to-class="transform opacity-0 scale-95">
                <MenuItems class="absolute left-0 z-20 mt-2 w-56 origin-top-left rounded-md bg-white shadow-lg ring-1 ring-black/5 focus:outline-none">
                  <div class="py-1">
                    <MenuItem v-slot="{ active }">
                      <a href="#" :class="[active ? 'bg-gray-100 text-gray-900 outline-none' : 'text-gray-700', 'block px-4 py-2 text-sm']">Account settings</a>
                    </MenuItem>
                    <MenuItem v-slot="{ active }">
                      <a href="#" :class="[active ? 'bg-gray-100 text-gray-900 outline-none' : 'text-gray-700', 'block px-4 py-2 text-sm']">Support</a>
                    </MenuItem>
                    <MenuItem v-slot="{ active }">
                      <a href="#" :class="[active ? 'bg-gray-100 text-gray-900 outline-none' : 'text-gray-700', 'block px-4 py-2 text-sm']">License</a>
                    </MenuItem>
                    <form method="POST" action="#">
                      <MenuItem v-slot="{ active }">
                        <button type="submit" :class="[active ? 'bg-gray-100 text-gray-900 outline-none' : 'text-gray-700', 'block w-full px-4 py-2 text-left text-sm']">Sign out</button>
                      </MenuItem>
                    </form>
                  </div>
                </MenuItems>
              </transition>
            </Menu> -->
            <!-- remove hidden if we want to implement labels it -->
            <!-- <ul class="hidden flex flex-row flex-wrap grow shrink basis-auto justify-center pt-3 pb-0 px-3 m-0">
            </ul> -->
            <!-- top content (checkbox, title) -->
            <div class="flex flex-col flex-nowrap grow shrink basis-auto justify-between min-h-0 min-w-0 p-3">
              <!-- checkbox/title -->
              <div class="flex flex-row flex-nowrap grow-0 shrink-0 basis-auto items-center">
                <TaskStatus :status="task.status" @update-task-status="(status) => $emit('updateTaskStatus', { task, newStatus: status })" />
                <div class="grow-0 shrink-0 basis-[218px] ml-2 overflow-x-hidden text-ellipsis" @click="$emit('editTask', task)">
                  {{ task.name }}
                </div>
              </div>
              <!-- sub task checkboxes/titles -->
              <div v-if="task.tasks.length > 0" class="flex flex-row flex-nowrap mt-[10px] text-base">
                <ul class="block flex-nowrap grow shrink basis-0 min-w-0 min-h-0 list-disc">
                  <li v-for="subtask in task.tasks" :key="subtask.id" class="flex flex-row flex-nowrap text-ellipsis whitespace-nowrap overflow-hidden py-[3px] pl-[2px] pr-0">
                    <div class="mt-0 ml-0" :title="subtask.name">
                      <div class="flex mt-0 ml-0 max-w-full">
                        <div class="flex items-center mt-0 ml-1 select-none text-sm font-normal">
                          <div class="h-4 leading-4 mr-2 ml-[2px]">
                            —
                          </div>
                          <TaskStatus :status="subtask.status" @update-task-status="(status) => $emit('updateTaskStatus', { task: subtask, newStatus: status })" />
                          <button type="button" @click="onClickSubtask" class="peer mt-0 ml-0 overflow-hidden whitespace-nowrap text-ellipsis text-sm leading-4 hover:underline">
                            {{ subtask.name }}
                          </button>
                        </div>
                      </div>
                    </div>
                  </li>
                </ul>
              </div>
            </div>
            <!-- bottom bar (due on, assigned to) -->
            <div class="flex flex-row flex-nowrap border-t-[1px]">
              <!-- due on -->
              <div class="flex flex-row flex-nowrap grow shrink basis-0 pl-3">
                <div class="inline-flex flex-row flex-nowrap items-center h-6 my-[6px] mx-0 rounded-sm cursor-pointer">
                  <span class="flex px-[2px] items-center justify-center cursor-pointer">
                    <svg class="leading-[0] inline items-center justify-center" fill="currentColor" aria-hidden="true" width="20" height="20" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" focusable="false"><path d="M7 11a1 1 0 1 0 0-2 1 1 0 0 0 0 2Zm1 2a1 1 0 1 1-2 0 1 1 0 0 1 2 0Zm2-2a1 1 0 1 0 0-2 1 1 0 0 0 0 2Zm1 2a1 1 0 1 1-2 0 1 1 0 0 1 2 0Zm2-2a1 1 0 1 0 0-2 1 1 0 0 0 0 2Zm4-5.5A2.5 2.5 0 0 0 14.5 3h-9A2.5 2.5 0 0 0 3 5.5v9A2.5 2.5 0 0 0 5.5 17h9a2.5 2.5 0 0 0 2.5-2.5v-9ZM4 7h12v7.5c0 .83-.67 1.5-1.5 1.5h-9A1.5 1.5 0 0 1 4 14.5V7Zm1.5-3h9c.83 0 1.5.67 1.5 1.5V6H4v-.5C4 4.67 4.67 4 5.5 4Z" fill="currentColor"></path></svg>
                  </span>
                  <span class="text-sm font-normal pl-[2px] pr-2 cursor-pointer">
                    {{ task.due_at
                      ? new Date(task.due_at).toLocaleString('en-US', {
                          timeZone: 'EST',
                          day: '2-digit',
                          month: '2-digit',
                        })
                      : 'Due' }}
                  </span>
                </div>
              </div>
              <!-- assigned to -->
              <div class="flex flex-row flex-nowrap mr-3 mt-[6px]">
                <button class="flex flex-col flex-nowrap grow shrink basis-0 w-full">
                  <InitialsList :items="task.assigned_to" size="6" />
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
