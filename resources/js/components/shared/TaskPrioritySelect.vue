<script setup>
import { Listbox, ListboxLabel, ListboxButton, ListboxOptions, ListboxOption } from '@headlessui/vue';
import { ChevronDownIcon, CheckIcon } from '@heroicons/vue/24/outline';

let priority = defineModel({
  type: String,
  required: true,
});
</script>

<template>
  <Listbox
    as="div"
    v-model="priority"
  >
    <ListboxLabel class="block text-sm/6 font-medium text-gray-900">
      Priority
    </ListboxLabel>
    <div class="relative mt-2">
      <ListboxButton
        :class="[
          'grid w-full cursor-default grid-cols-1 rounded-md bg-white min-h-[calc(2rem+(2*0.375rem))] py-1.5 pl-3 pr-2 text-left outline outline-1 -outline-offset-1 outline-gray-300 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6 text-gray-900',
        ]"
      >
        <span
          class="col-start-1 row-start-1 flex items-center gap-3 pr-6"
        >
          <template v-if="priority === 'Urgent'">
            <span class="flex w-6 h-6 my-0 align-middle shrink-0 items-center justify-center text-red-600 rounded-full bg-white p-0.5">
              <svg class="items-center justify-center" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.0" stroke-linecap="round" stroke-linejoin="round">
                <path d="M7.86 2h8.28L22 7.86v8.28L16.14 22H7.86L2 16.14V7.86L7.86 2z"></path>
                <line x1="12" y1="8" x2="12" y2="12"></line>
                <line x1="12" y1="16" x2="12.01" y2="16"></line>
              </svg>
            </span>
            Urgent
          </template>
          <template v-else-if="priority === 'Important'">
            <span class="flex w-6 h-6 my-0 align-middle shrink-0 items-center justify-center text-amber-500 rounded-full bg-white p-0.5">
              <svg class="items-center justify-center" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.0" stroke-linecap="round" stroke-linejoin="round">
                <path d="m12 3 8 14H4L12 3z"></path>
                <line x1="12" y1="9" x2="12" y2="13"></line>
                <line x1="12" y1="17" x2="12.01" y2="17"></line>
              </svg>
            </span>
            Important
          </template>
          <template v-else-if="priority === 'Medium'">
            <span class="flex w-6 h-6 my-0 align-middle shrink-0 items-center justify-center text-indigo-600 rounded-full bg-white p-0.5">
              <svg class="items-center justify-center" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.0" stroke-linecap="round" stroke-linejoin="round">
                  <circle cx="12" cy="12" r="10"></circle>
                  <line x1="8" y1="12" x2="16" y2="12"></line>
              </svg>
            </span>
            Medium
          </template>
          <template v-else-if="priority === 'Low'">
            <span class="flex w-6 h-6 my-0 align-middle shrink-0 items-center justify-center text-gray-600 rounded-full bg-white p-0.5">
              <svg class="items-center justify-center" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.0" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"></circle>
                <path d="m15 11-3 3-3-3"></path>
              </svg>
            </span>
            Low
          </template>
        </span>
        <ChevronDownIcon
          class="col-start-1 row-start-1 size-5 self-center justify-self-end text-gray-500 sm:size-4"
          aria-hidden="true"
        />
      </ListboxButton>

      <transition
        leave-active-class="transition ease-in duration-100"
        leave-from-class="opacity-100"
        leave-to-class="opacity-0"
      >
        <ListboxOptions
          class="absolute z-10 mt-1 max-h-56 w-full overflow-auto rounded-md bg-white py-1 text-base shadow-lg ring-1 ring-black/5 focus:outline-none sm:text-sm"
        >
          <ListboxOption v-slot="{ active, selected }" value="Urgent">
            <li
              :class="[
                active
                  ? 'bg-indigo-600 text-white outline-none'
                  : 'text-gray-900',
                'relative cursor-default select-none py-2 pl-3 pr-9',
              ]"
            >
              <div class="flex items-center">
                <span class="flex w-6 h-6 my-0 align-middle shrink-0 items-center justify-center text-red-600 rounded-full bg-white p-0.5">
                  <svg class="items-center justify-center" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.0" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M7.86 2h8.28L22 7.86v8.28L16.14 22H7.86L2 16.14V7.86L7.86 2z"></path>
                    <line x1="12" y1="8" x2="12" y2="12"></line>
                    <line x1="12" y1="16" x2="12.01" y2="16"></line>
                  </svg>
                </span>
                <span
                  :class="[
                    selected
                      ? 'font-semibold'
                      : 'font-normal',
                    'ml-3 block truncate',
                  ]"
                  >Urgent</span
                >
              </div>

              <span
                v-if="selected"
                :class="[
                  active
                    ? 'text-white'
                    : 'text-indigo-600',
                  'absolute inset-y-0 right-0 flex items-center pr-4',
                ]"
              >
                <CheckIcon
                  class="size-5"
                  aria-hidden="true"
                />
              </span>
            </li>
          </ListboxOption>
          <ListboxOption v-slot="{ active, selected }" value="Important">
            <li
              :class="[
                active
                  ? 'bg-indigo-600 text-white outline-none'
                  : 'text-gray-900',
                'relative cursor-default select-none py-2 pl-3 pr-9',
              ]"
            >
              <div class="flex items-center">
                <span class="flex w-6 h-6 my-0 align-middle shrink-0 items-center justify-center text-amber-500 rounded-full bg-white p-0.5">
                  <svg class="items-center justify-center" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.0" stroke-linecap="round" stroke-linejoin="round">
                    <path d="m12 3 8 14H4L12 3z"></path>
                    <line x1="12" y1="9" x2="12" y2="13"></line>
                    <line x1="12" y1="17" x2="12.01" y2="17"></line>
                  </svg>
                </span>
                <span
                  :class="[
                    selected
                      ? 'font-semibold'
                      : 'font-normal',
                    'ml-3 block truncate',
                  ]"
                  >Important</span
                >
              </div>

              <span
                v-if="selected"
                :class="[
                  active
                    ? 'text-white'
                    : 'text-indigo-600',
                  'absolute inset-y-0 right-0 flex items-center pr-4',
                ]"
              >
                <CheckIcon
                  class="size-5"
                  aria-hidden="true"
                />
              </span>
            </li>
          </ListboxOption>
          <ListboxOption v-slot="{ active, selected }" value="Medium">
            <li
              :class="[
                active
                  ? 'bg-indigo-600 text-white outline-none'
                  : 'text-gray-900',
                'relative cursor-default select-none py-2 pl-3 pr-9',
              ]"
            >
              <div class="flex items-center">
                <span class="flex w-6 h-6 my-0 align-middle shrink-0 items-center justify-center text-indigo-600 rounded-full bg-white p-0.5">
                  <svg class="items-center justify-center" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.0" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="8" y1="12" x2="16" y2="12"></line>
                  </svg>
                </span>
                <span
                  :class="[
                    selected
                      ? 'font-semibold'
                      : 'font-normal',
                    'ml-3 block truncate',
                  ]"
                  >Medium</span
                >
              </div>

              <span
                v-if="selected"
                :class="[
                  active
                    ? 'text-white'
                    : 'text-indigo-600',
                  'absolute inset-y-0 right-0 flex items-center pr-4',
                ]"
              >
                <CheckIcon
                  class="size-5"
                  aria-hidden="true"
                />
              </span>
            </li>
          </ListboxOption>
          <ListboxOption v-slot="{ active, selected }" value="Low">
            <li
              :class="[
                active
                  ? 'bg-indigo-600 text-white outline-none'
                  : 'text-gray-900',
                'relative cursor-default select-none py-2 pl-3 pr-9',
              ]"
            >
              <div class="flex items-center">
                <span class="flex w-6 h-6 my-0 align-middle shrink-0 items-center justify-center text-gray-600 rounded-full bg-white p-0.5">
                  <svg class="items-center justify-center" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.0" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <path d="m15 11-3 3-3-3"></path>
                  </svg>
                </span>
                <span
                  :class="[
                    selected
                      ? 'font-semibold'
                      : 'font-normal',
                    'ml-3 block truncate',
                  ]"
                  >Low</span
                >
              </div>

              <span
                v-if="selected"
                :class="[
                  active
                    ? 'text-white'
                    : 'text-indigo-600',
                  'absolute inset-y-0 right-0 flex items-center pr-4',
                ]"
              >
                <CheckIcon
                  class="size-5"
                  aria-hidden="true"
                />
              </span>
            </li>
          </ListboxOption>
        </ListboxOptions>
      </transition>
    </div>
  </Listbox>
</template>
