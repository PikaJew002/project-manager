<script setup>
import { Listbox, ListboxLabel, ListboxButton, ListboxOptions, ListboxOption } from '@headlessui/vue';
import { ChevronDownIcon, CheckIcon } from '@heroicons/vue/24/outline';

let status = defineModel({
  type: String,
  required: true,
});
</script>

<template>
  <Listbox
    as="div"
    v-model="status"
  >
    <ListboxLabel class="block text-sm/6 font-medium text-gray-900">
      Progress
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
          <template v-if="status === 'Not Started'">
            <span class="flex min-h-px max-h-9 w-5 my-0 align-middle shrink-0 items-center justify-center text-indigo-600">
              <svg class="inline leading-[0] items-center justify-center bg-white rounded-full" fill="currentColor" aria-hidden="true" width="20" height="20" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" focusable="false"><path d="M10 3a7 7 0 1 0 0 14 7 7 0 0 0 0-14Zm-8 7a8 8 0 1 1 16 0 8 8 0 0 1-16 0Z" fill="currentColor"></path></svg>
            </span>
            Not Started
          </template>
          <template v-else-if="status === 'In Progress'">
            <span class="flex min-h-px max-h-9 w-5 my-0 align-middle shrink-0 items-center justify-center text-indigo-600">
              <svg class="inline leading-[0] items-center justify-center rotate-45 origin-center" fill="currentColor" aria-hidden="true" width="20" height="20" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" focusable="false"><path d="M3 10a7 7 0 0 1 14 0H3Zm7-8a8 8 0 1 0 0 16 8 8 0 0 0 0-16Z" fill="currentColor"></path></svg>
            </span>
            In Progress
          </template>
          <template v-else-if="status === 'Completed'">
            <span class="flex min-h-px max-h-9 w-5 my-0 align-middle shrink-0 items-center justify-center text-indigo-600">
              <svg class="inline leading-[0] items-center justify-center" fill="currentColor" aria-hidden="true" width="20" height="20" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" focusable="false"><path d="M10 2a8 8 0 1 1 0 16 8 8 0 0 1 0-16Zm3.36 5.65a.5.5 0 0 0-.64-.06l-.07.06L9 11.3 7.35 9.65l-.07-.06a.5.5 0 0 0-.7.7l.07.07 2 2 .07.06c.17.11.4.11.56 0l.07-.06 4-4 .07-.08a.5.5 0 0 0-.06-.63Z" fill="currentColor"></path></svg>
            </span>
            Completed
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
          <ListboxOption v-slot="{ active, selected }" value="Not Started">
            <li
              :class="[
                active
                  ? 'bg-indigo-600 text-white outline-none'
                  : 'text-gray-900',
                'relative cursor-default select-none py-2 pl-3 pr-9',
              ]"
            >
              <div class="flex items-center">
                <span class="flex min-h-px max-h-9 w-5 my-0 align-middle shrink-0 items-center justify-center text-indigo-600">
                  <svg class="inline leading-[0] items-center justify-center bg-white rounded-full" fill="currentColor" aria-hidden="true" width="20" height="20" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" focusable="false"><path d="M10 3a7 7 0 1 0 0 14 7 7 0 0 0 0-14Zm-8 7a8 8 0 1 1 16 0 8 8 0 0 1-16 0Z" fill="currentColor"></path></svg>
                </span>
                <span
                  :class="[
                    selected
                      ? 'font-semibold'
                      : 'font-normal',
                    'ml-3 block truncate',
                  ]"
                  >Not Started</span
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
          <ListboxOption v-slot="{ active, selected }" value="In Progress">
            <li
              :class="[
                active
                  ? 'bg-indigo-600 text-white outline-none'
                  : 'text-gray-900',
                'relative cursor-default select-none py-2 pl-3 pr-9',
              ]"
            >
              <div class="flex items-center">
                <span class="flex min-h-px max-h-9 w-5 my-0 align-middle shrink-0 items-center justify-center text-indigo-600">
                  <svg class="inline leading-[0] items-center justify-center rotate-45 origin-center bg-white rounded-full" fill="currentColor" aria-hidden="true" width="20" height="20" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" focusable="false"><path d="M3 10a7 7 0 0 1 14 0H3Zm7-8a8 8 0 1 0 0 16 8 8 0 0 0 0-16Z" fill="currentColor"></path></svg>
                </span>
                <span
                  :class="[
                    selected
                      ? 'font-semibold'
                      : 'font-normal',
                    'ml-3 block truncate',
                  ]"
                  >In Progress</span
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
          <ListboxOption v-slot="{ active, selected }" value="Completed">
            <li
              :class="[
                active
                  ? 'bg-indigo-600 text-white outline-none'
                  : 'text-gray-900',
                'relative cursor-default select-none py-2 pl-3 pr-9',
              ]"
            >
              <div class="flex items-center">
                <span class="flex min-h-px max-h-9 w-5 my-0 align-middle shrink-0 items-center justify-center text-indigo-600">
                  <svg class="inline leading-[0] items-center justify-center bg-white rounded-full" fill="currentColor" aria-hidden="true" width="20" height="20" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" focusable="false"><path d="M10 2a8 8 0 1 1 0 16 8 8 0 0 1 0-16Zm3.36 5.65a.5.5 0 0 0-.64-.06l-.07.06L9 11.3 7.35 9.65l-.07-.06a.5.5 0 0 0-.7.7l.07.07 2 2 .07.06c.17.11.4.11.56 0l.07-.06 4-4 .07-.08a.5.5 0 0 0-.06-.63Z" fill="currentColor"></path></svg>
                </span>
                <span
                  :class="[
                    selected
                      ? 'font-semibold'
                      : 'font-normal',
                    'ml-3 block truncate',
                  ]"
                  >Completed</span
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
