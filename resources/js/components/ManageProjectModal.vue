<script setup>
import { ref, computed } from 'vue';
import { usePage } from '@inertiajs/vue3';
import { storeToRefs } from 'pinia';
import {
  Dialog, DialogPanel, DialogTitle,
  TransitionChild, TransitionRoot,
  Combobox, ComboboxInput, ComboboxOption, ComboboxOptions,
  Popover, PopoverButton, PopoverPanel,
} from '@headlessui/vue';
import { XMarkIcon } from '@heroicons/vue/24/outline';
import { PlusIcon } from '@heroicons/vue/20/solid';
import { useManageProjectStore } from '../stores/manage-project.js';
import InitialsList from './shared/InitialsList.vue';
import Pill from '../components/dashboard/Pill.vue';

let page = usePage();
let store = useManageProjectStore();

let { open, name, project_users, administered_by } = storeToRefs(store);

let query = ref('');

let queryAdmin = ref('');

let all_users = computed(() => {
  return page.props.options?.assignable_users;
});

let administers_project = computed({
  get() {
    return project_users.value.filter(p => p.id === administered_by.value)[0];
  },
  set(newValue) {
    administered_by.value = newValue;
  }
});

let filteredAdminPeople = computed(() =>
  queryAdmin.value === ''
    ? project_users.value
    : project_users.value.filter((person) => person.name.toLowerCase().includes(queryAdmin.value.toLowerCase()))
);

let unassignedFilteredAdminPeople = computed(() =>
  filteredAdminPeople.value.filter((person) => person.id !== administered_by.value)
);

let assigned_to_project = computed({
  get() {
    return project_users.value.map(a => a.id);
  },
  set(newValue) {
    project_users.value = all_users.value.filter(u => newValue.includes(u.id));
  }
});

let filteredPeople = computed(() =>
  query.value === ''
    ? all_users.value
    : all_users.value.filter((person) => person.name.toLowerCase().includes(query.value.toLowerCase()))
);

let assignedFilteredPeople = computed(() =>
  filteredPeople.value.filter((person) =>
    project_users.value.find((p) => p.id === person.id)
  )
);

let unassignedFilteredPeople = computed(() =>
  filteredPeople.value.filter((person) => project_users.value.findIndex((p) => p.id === person.id) === -1)
);

function onClose() {
  store.onClose();
}
</script>

<template>
  <TransitionRoot as="template" :show="open">
    <Dialog class="relative z-50" @close="onClose">
      <TransitionChild
        as="template"
        class="opacity-0"
        enter="ease-in-out duration-500"
        enter-from="opacity-0"
        enter-to="opacity-100"
        leave="ease-in-out duration-500"
        leave-from="opacity-100"
        leave-to="opacity-0"
      >
        <div class="fixed inset-0 bg-gray-500/75 transition-opacity" />
      </TransitionChild>

      <div class="fixed inset-0 overflow-hidden">
        <div class="absolute inset-0 overflow-hidden">
          <div class="pointer-events-none fixed inset-y-0 right-0 flex max-w-full pl-10 sm:pl-16">
            <TransitionChild
              as="template"
              class="translate-x-full"
              enter="transform transition ease-in-out duration-500 sm:duration-700"
              enter-from="translate-x-full"
              enter-to="translate-x-0"
              leave="transform transition ease-in-out duration-500 sm:duration-700"
              leave-from="translate-x-0"
              leave-to="translate-x-full"
            >
              <DialogPanel class="pointer-events-auto w-screen max-w-2xl">
                <form @submit.prevent="() => {}" class="flex h-full flex-col overflow-y-scroll bg-white shadow-xl">
                  <div class="flex-1">
                    <!-- Header -->
                    <div class="bg-gray-50 px-4 py-6 sm:px-6">
                      <div class="flex items-start justify-between space-x-3">
                        <div class="flex items-start justify-between">
                          <DialogTitle class="text-base font-semibold text-gray-900">
                            {{ name }}
                          </DialogTitle>
                        </div>
                        <div class="flex h-7 items-center">
                          <button
                            type="button"
                            @click="onClose"
                            class="relative text-gray-400 hover:text-gray-500"
                          >
                            <span class="absolute -inset-2.5" />
                            <span class="sr-only">Close panel</span>
                            <XMarkIcon class="size-6" aria-hidden="true" />
                          </button>
                        </div>
                      </div>
                    </div>

                    <!-- Administered By -->
                    <div class="space-y-2 px-4 sm:grid sm:grid-cols-3 sm:items-center sm:gap-4 sm:space-y-0 sm:px-6 sm:py-5">
                      <div>
                        <h3 class="text-sm/6 font-medium text-gray-900">
                          Administered By
                        </h3>
                      </div>
                      <div class="sm:col-span-2">
                        <InitialsList :items="assignedFilteredPeople" key-attribute="email" title-attribute="name">
                          <Popover>
                            <PopoverButton class="relative inline-flex size-8 shrink-0 items-center justify-center rounded-full border-2 border-dashed border-gray-200 bg-white text-gray-400 hover:border-gray-300 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                              <span class="absolute -inset-2" />
                              <span class="sr-only">Choose project admin</span>
                              <PlusIcon class="size-5" aria-hidden="true" />
                            </PopoverButton>
                            <PopoverPanel class="absolute top-8 -left-2">
                              <Combobox
                                as="div"
                                v-model="administered_by"
                                @update:modelValue="query = ''"
                              >
                                <div class="relative mt-2">
                                  <ComboboxInput
                                    class="block w-full rounded-md bg-white py-1.5 pl-3 pr-12 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6"
                                    @change="query = $event.target.value"
                                    @blur="query = ''"
                                    :display-value="(user) => user?.name"
                                  />

                                  <ComboboxOptions static class="absolute z-10 mt-1 max-h-56 w-full overflow-auto rounded-md bg-white py-1 text-base shadow-lg ring-1 ring-black/5 focus:outline-none sm:text-sm">
                                    <div v-if="administered_by">
                                      <li class="relative cursor-default select-none py-2 pl-3 pr-9 text-gray-900">
                                        <span class="font-medium">
                                          Administrator
                                        </span>
                                      </li>
                                      <ComboboxOption
                                        :value="administers_project.id"
                                        :disabled="administers_project.is_me"
                                        as="template"
                                        v-slot="{ active }"
                                      >
                                        <li
                                          :class="[
                                            'relative cursor-default select-none py-2 pl-3 pr-9',
                                            active
                                              ? 'bg-gray-100 outline-none'
                                              : '',
                                          ]"
                                        >
                                          <div class="flex items-center">
                                            <div class="relative text-center">
                                              <div
                                                class="rounded-full bg-gray-200 w-8 h-8 leading-8 font-semibold"
                                              >
                                                <span>{{ administers_project.initials }}</span>
                                              </div>
                                            </div>
                                            <span class="ml-3 truncate font-semibold">
                                              {{ administers_project.name }}
                                            </span>
                                          </div>
                                          <span
                                            :class="[
                                              'absolute inset-y-0 right-0 flex items-center pr-4',
                                              active
                                                ? 'text-indigo-600'
                                                : 'text-gray-900',
                                            ]"
                                          >
                                            <Pill>Admin</Pill>
                                          </span>
                                        </li>
                                      </ComboboxOption>
                                    </div>
                                    <div v-if="unassignedFilteredAdminPeople.length > 0">
                                      <li class="relative cursor-default select-none py-2 pl-3 pr-9 text-gray-900">
                                        <span class="font-medium">Suggested</span>
                                      </li>
                                      <ComboboxOption
                                        v-for="user in unassignedFilteredAdminPeople"
                                        :key="user.id"
                                        :value="user.id"
                                        as="template"
                                        v-slot="{ active }"
                                      >
                                        <li
                                          :class="[
                                            'relative cursor-default select-none py-2 pl-3 pr-9',
                                            active
                                              ? 'bg-gray-100 text-gray-900 outline-none'
                                              : 'text-gray-900',
                                          ]"
                                        >
                                          <div class="flex items-center">
                                            <div class="relative text-center">
                                              <div
                                                class="rounded-full bg-gray-200 w-8 h-8 leading-8 font-semibold"
                                              >
                                                <span>{{ user.initials }}</span>
                                              </div>
                                            </div>
                                            <span class="ml-3 truncate">
                                              {{ user.name }}
                                            </span>
                                          </div>
                                          <span v-if="user.accepted_at && user.accepted_at !== null"
                                            :class="[
                                              'absolute inset-y-0 right-0 flex items-center pr-4',
                                              active
                                                ? 'text-indigo-600'
                                                : 'text-gray-900',
                                            ]"
                                          >
                                            <Pill>Pending</Pill>
                                          </span>
                                        </li>
                                      </ComboboxOption>
                                    </div>
                                  </ComboboxOptions>
                                </div>
                              </Combobox>
                            </PopoverPanel>
                          </Popover>
                        </InitialsList>
                      </div>
                    </div>

                    <!-- Users -->
                    <div class="space-y-2 px-4 sm:grid sm:grid-cols-3 sm:items-center sm:gap-4 sm:space-y-0 sm:px-6 sm:py-5">
                      <div>
                        <h3 class="text-sm/6 font-medium text-gray-900">
                          Project Users
                        </h3>
                      </div>
                      <div class="sm:col-span-2">
                        <InitialsList :items="assignedFilteredPeople" key-attribute="email" title-attribute="name">
                          <Popover>
                            <PopoverButton class="relative inline-flex size-8 shrink-0 items-center justify-center rounded-full border-2 border-dashed border-gray-200 bg-white text-gray-400 hover:border-gray-300 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                              <span class="absolute -inset-2" />
                              <span class="sr-only">Add team member</span>
                              <PlusIcon class="size-5" aria-hidden="true" />
                            </PopoverButton>
                            <PopoverPanel class="absolute top-8 -left-2">
                              <Combobox
                                as="div"
                                v-model="assigned_to_project"
                                @update:modelValue="query = ''"
                                multiple
                              >
                                <div class="relative mt-2">
                                  <ComboboxInput
                                    class="block w-full rounded-md bg-white py-1.5 pl-3 pr-12 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6"
                                    @change="query = $event.target.value"
                                    @blur="query = ''"
                                    :display-value="(user) => user?.name"
                                  />

                                  <ComboboxOptions static class="absolute z-10 mt-1 max-h-56 w-full overflow-auto rounded-md bg-white py-1 text-base shadow-lg ring-1 ring-black/5 focus:outline-none sm:text-sm">
                                    <div v-if="assignedFilteredPeople.length > 0">
                                      <li class="relative cursor-default select-none py-2 pl-3 pr-9 text-gray-900">
                                        <span class="font-medium">
                                          Members
                                        </span>
                                      </li>
                                      <ComboboxOption
                                        v-for="user in assignedFilteredPeople"
                                        :key="user.id"
                                        :value="user.id"
                                        :disabled="user.is_me"
                                        as="template"
                                        v-slot="{ active, selected }"
                                      >
                                        <li
                                          :class="[
                                            'relative cursor-default select-none py-2 pl-3 pr-9',
                                            active
                                              ? 'bg-gray-100 outline-none'
                                              : '',
                                          ]"
                                        >
                                          <div class="flex items-center">
                                            <div class="relative text-center">
                                              <div
                                                class="rounded-full bg-gray-200 w-8 h-8 leading-8 font-semibold"
                                              >
                                                <span>{{ user.initials }}</span>
                                              </div>
                                            </div>
                                            <span class="ml-3 truncate font-semibold">
                                              {{ user.name }}
                                            </span>
                                          </div>

                                          <span
                                            v-if="user.id !== administered_by"
                                            :class="[
                                              'absolute inset-y-0 right-0 flex items-center pr-4',
                                              active
                                                ? 'text-indigo-600'
                                                : 'text-gray-900',
                                            ]"
                                          >
                                            <XMarkIcon
                                              class="size-5"
                                              aria-hidden="true"
                                            />
                                          </span>
                                          <span
                                            v-else
                                            :class="[
                                              'absolute inset-y-0 right-0 flex items-center pr-4',
                                              active
                                                ? 'text-indigo-600'
                                                : 'text-gray-900',
                                            ]"
                                          >
                                            <Pill>Admin</Pill>
                                          </span>
                                        </li>
                                      </ComboboxOption>
                                    </div>
                                    <div v-if="unassignedFilteredPeople.length > 0">
                                      <li class="relative cursor-default select-none py-2 pl-3 pr-9 text-gray-900">
                                        <span class="font-medium">Suggested</span>
                                      </li>
                                      <ComboboxOption
                                        v-for="user in unassignedFilteredPeople"
                                        :key="user.id"
                                        :value="user.id"
                                        as="template"
                                        v-slot="{ active }"
                                      >
                                        <li
                                          :class="[
                                            'relative cursor-default select-none py-2 pl-3 pr-9',
                                            active
                                              ? 'bg-gray-100 text-gray-900 outline-none'
                                              : 'text-gray-900',
                                          ]"
                                        >
                                          <div class="flex items-center">
                                            <div class="relative text-center">
                                              <div
                                                class="rounded-full bg-gray-200 w-8 h-8 leading-8 font-semibold"
                                              >
                                                <span>{{ user.initials }}</span>
                                              </div>
                                            </div>
                                            <span class="ml-3 truncate">
                                              {{ user.name }}
                                            </span>
                                          </div>
                                        </li>
                                      </ComboboxOption>
                                    </div>
                                  </ComboboxOptions>
                                </div>
                              </Combobox>
                            </PopoverPanel>
                          </Popover>
                        </InitialsList>
                      </div>
                    </div>
                  </div>
                </form>
              </DialogPanel>
            </TransitionChild>
          </div>
        </div>
      </div>
    </Dialog>
  </TransitionRoot>
</template>
