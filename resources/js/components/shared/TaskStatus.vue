<script setup>
import { Menu, MenuButton, MenuItem, MenuItems } from '@headlessui/vue';
import { Float } from '@headlessui-float/vue';

let props = defineProps({
  status: {
    type: String,
    required: true,
  },
  classes: {
    type: String,
    default: 'flex flex-row flex-nowrap grow-0 shrink-0 basis-[18px] justify-center items-center font-normal',
  },
});

let emit = defineEmits(['updateTaskStatus']);
</script>

<template>
  <Menu as="div" :class="classes">
    <Float placement="bottom-start">
      <div>
        <MenuButton @click.stop class="flex min-h-px max-h-9 w-5 my-0 mx-1 align-middle shrink-0 items-center justify-center text-indigo-600">
          <svg v-if="status === 'Not Started'" class="inline leading-[0] items-center justify-center" fill="currentColor" aria-hidden="true" width="20" height="20" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" focusable="false"><path d="M10 3a7 7 0 1 0 0 14 7 7 0 0 0 0-14Zm-8 7a8 8 0 1 1 16 0 8 8 0 0 1-16 0Z" fill="currentColor"></path></svg>
          <svg v-else-if="status === 'In Progress'" class="inline leading-[0] items-center justify-center" fill="currentColor" aria-hidden="true" width="20" height="20" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" focusable="false"><path d="M3 10a7 7 0 0 1 14 0H3Zm7-8a8 8 0 1 0 0 16 8 8 0 0 0 0-16Z" fill="currentColor"></path></svg>
          <svg v-else class="inline leading-[0] items-center justify-center" fill="currentColor" aria-hidden="true" width="20" height="20" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" focusable="false"><path d="M10 2a8 8 0 1 1 0 16 8 8 0 0 1 0-16Zm3.36 5.65a.5.5 0 0 0-.64-.06l-.07.06L9 11.3 7.35 9.65l-.07-.06a.5.5 0 0 0-.7.7l.07.07 2 2 .07.06c.17.11.4.11.56 0l.07-.06 4-4 .07-.08a.5.5 0 0 0-.06-.63Z" fill="currentColor"></path></svg>
        </MenuButton>
      </div>

      <transition enter-active-class="transition ease-out duration-100" enter-from-class="transform opacity-0 scale-95" enter-to-class="transform opacity-100 scale-100" leave-active-class="transition ease-in duration-75" leave-from-class="transform opacity-100 scale-100" leave-to-class="transform opacity-0 scale-95">
        <MenuItems class="mt-2 rounded-md w-full bg-white shadow-lg ring-1 ring-black/5 focus:outline-none">
          <div class="py-1">
            <MenuItem v-slot="{ active, disabled }" :disabled="status === 'Not Started'">
              <button
                type="button"
                @click.stop="$emit('updateTaskStatus', 'Not Started')"
                :class="[,
                disabled
                  ? 'text-gray-400'
                  : (active ? 'bg-gray-100 text-gray-900 outline-none' : 'text-gray-700'), 'flex items-center px-4 py-2 text-sm'
                ]"
              >
                <span class="flex min-h-px max-h-9 w-5 my-0 ml-1 mr-3 align-middle shrink-0 items-center justify-center text-indigo-600">
                  <svg class="inline leading-[0] items-center justify-center" fill="currentColor" aria-hidden="true" width="20" height="20" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" focusable="false"><path d="M10 3a7 7 0 1 0 0 14 7 7 0 0 0 0-14Zm-8 7a8 8 0 1 1 16 0 8 8 0 0 1-16 0Z" fill="currentColor"></path></svg>
                </span>
                Not Started
              </button>
            </MenuItem>
            <MenuItem v-slot="{ active, disabled }" :disabled="status === 'In Progress'">
              <button
                type="button"
                @click.stop="emit('updateTaskStatus', 'In Progress')"
                :class="[,
                disabled
                  ? 'text-gray-400'
                  : (active ? 'bg-gray-100 text-gray-900 outline-none' : 'text-gray-700'), 'flex items-center px-4 py-2 text-sm'
                ]"
              >
                <span class="flex min-h-px max-h-9 w-5 my-0 ml-1 mr-3 align-middle shrink-0 items-center justify-center text-indigo-600">
                  <svg class="inline leading-[0] items-center justify-center" fill="currentColor" aria-hidden="true" width="20" height="20" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" focusable="false"><path d="M3 10a7 7 0 0 1 14 0H3Zm7-8a8 8 0 1 0 0 16 8 8 0 0 0 0-16Z" fill="currentColor"></path></svg>
                </span>
                In Progress
              </button>
            </MenuItem>
            <MenuItem v-slot="{ active, disabled }" :disabled="status === 'Completed'">
              <button
                type="button"
                @click.stop="emit('updateTaskStatus', 'Completed')"
                :class="[,
                disabled
                  ? 'text-gray-400'
                  : (active ? 'bg-gray-100 text-gray-900 outline-none' : 'text-gray-700'), 'flex items-center px-4 py-2 text-sm'
                ]"
              >
                <span class="flex min-h-px max-h-9 w-5 my-0 ml-1 mr-3 align-middle shrink-0 items-center justify-center text-indigo-600">
                  <svg class="inline leading-[0] items-center justify-center" fill="currentColor" aria-hidden="true" width="20" height="20" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" focusable="false"><path d="M10 2a8 8 0 1 1 0 16 8 8 0 0 1 0-16Zm3.36 5.65a.5.5 0 0 0-.64-.06l-.07.06L9 11.3 7.35 9.65l-.07-.06a.5.5 0 0 0-.7.7l.07.07 2 2 .07.06c.17.11.4.11.56 0l.07-.06 4-4 .07-.08a.5.5 0 0 0-.06-.63Z" fill="currentColor"></path></svg>
                </span>
                Completed
              </button>
            </MenuItem>
          </div>
        </MenuItems>
      </transition>
    </Float>
  </Menu>
</template>
