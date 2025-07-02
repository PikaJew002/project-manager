<script setup>
import { ref, reactive, computed, watch, useTemplateRef, nextTick } from 'vue';
import { onClickOutside } from '@vueuse/core';
import { storeToRefs } from 'pinia';
import {
  Dialog, DialogPanel, DialogTitle,
  TransitionChild, TransitionRoot,
  Combobox, ComboboxInput, ComboboxOption, ComboboxOptions,
  Popover, PopoverButton, PopoverPanel,
  Listbox, ListboxButton, ListboxLabel, ListboxOption, ListboxOptions,
} from '@headlessui/vue';
import { XMarkIcon } from '@heroicons/vue/24/outline';
import { PlusIcon, CheckIcon, ChevronDownIcon } from '@heroicons/vue/20/solid';
import { ChevronUpDownIcon } from '@heroicons/vue/16/solid';
import { useTaskModalStore } from '../stores/task-modal.js';
import InitialsList from './shared/InitialsList.vue';
import TaskStatus from './shared/TaskStatus.vue';

let props = defineProps({
  page: {
    type: String,
    required: true,
  },
});

let emit = defineEmits(['updatedTasks', 'loadTask', 'updateTaskStatus']);

let store = useTaskModalStore();

let {
  id, task_id, name, description, due_at, priority, status, assigned_to, projects, buckets, tasks,
  all_projects, all_buckets, all_users,
  open, parentTask,
} = storeToRefs(store);

let formErrors = reactive({
  name: [],
  description: [],
  due_at: [],
  priority: [],
  status: [],
  assigned_to: [],
  projects: [],
  buckets: [],
});

let query = ref('');

let addTaskMode = ref(false);
let newTaskInput = useTemplateRef('new-task-input');
let addTaskButton = useTemplateRef('add-task-button');

let newTaskName = ref('');

onClickOutside(newTaskInput, () => addTaskMode.value = false);

let assigned_to_task = computed({
  get() {
    return assigned_to.value.map(a => a.id);
  },
  set(newValue) {
    assigned_to.value = all_users.value.filter(u => newValue.includes(u.id));
  }
});

let your_projects = computed(() => {
  return all_projects.value;
});

let your_buckets = computed(() => {
  return all_buckets.value.filter(b => projects.value.includes(b.project_id));
});

let your_project = computed(() => {
  return your_projects.value.find(p => p.is_personal);
});

let your_user = computed(() => {
  return all_users.value.find(u => u.is_me);
});

let filteredPeople = computed(() =>
  query.value === ''
    ? all_users.value
    : all_users.value.filter((person) => person.name.toLowerCase().includes(query.value.toLowerCase()))
);

let assignedFilteredPeople = computed(() =>
  filteredPeople.value.filter((person) =>
    assigned_to.value.find((p) => p.id === person.id)
  )
);

let unassignedFilteredPeople = computed(() =>
  filteredPeople.value.filter((person) => assigned_to.value.findIndex((p) => p.id === person.id) === -1)
);

function onProjectsUpdated(selectedProjects) {
  buckets.value = buckets.value.filter((bucketId) => {
    let bucket = your_buckets.value.find((bucket) => bucket.id === bucketId);
    return selectedProjects.includes(bucket.project_id);
  });

  projects.value = selectedProjects;
}

watch(projects, (newProjects) => {
  if (id.value === null) {
    if (newProjects.length === 0) {
      projects.value = [your_project.value.id];
      assigned_to.value = [your_user.value];
    } else if (newProjects.length === 1 && newProjects[0] === your_project.value.id) {
      assigned_to.value = [your_user.value];
    } else {
      if(newProjects.includes(your_project.value.id) && assigned_to.value.findIndex(u => u.is_me) === -1) {
        assigned_to.value = assigned_to.value.concat([your_user.value]);
      }
    }
  }
});

function onClose() {
  store.onClose();
  formErrors.name = [];
  formErrors.description = [];
  formErrors.due_at = [];
  formErrors.priority = [];
  formErrors.status = [];
  formErrors.assigned_to = [];
  formErrors.buckets = [];
  formErrors.projects = [];
}

function onSubmit() {
  if (id.value) {
    store.onFormSubmitUpdate(props.page, function (results) {
      emit('updatedTasks', results);
      onClose();
    });
  } else {
    store.onFormSubmitCreate(props.page, function (results) {
      emit('updatedTasks', results);
    }, function (errors) {
      for (const e of Object.keys(errors)) {
        formErrors[e] = Array.isArray(errors[e]) ? errors[e] : [errors[e]];
      }
    });
  }
}

function onDelete() {
  store.onFormSubmitDelete(props.page, function (results) {
    emit('updatedTasks', results);
  });
}

function onClickNewTask() {
  addTaskMode.value = true;
  nextTick(() => {
    newTaskInput.value.focus();
  });
}

function onNewTaskSubmit() {
  store.onSubTaskFormSubmitCreate(newTaskName.value, props.page, function (results) {
    emit('updatedTasks', results);
    addTaskMode.value = false;

    if (results.props.task) {
      tasks.value.push(results.props.task);
    }
  });
}

function handleEscape() {
  addTaskMode.value = false;
  nextTick(() => {
    addTaskButton.value.focus();
  });
}

function onEditSubTask(subTask) {
  open.value = false;
  setTimeout(() => {
    emit('loadTask', subTask.id);
    open.value = true;
  }, 750);
}

function updateSubTaskStatus(subTaskId, newStatus) {
  store.onSubTaskFormSubmitUpdateStatus(subTaskId, newStatus, props.page, function (results) {
    emit('updatedTasks', results);
    tasks.value = tasks.value.map((t) => {
      if (t.id === subTaskId) {
        t.status = newStatus;
      }
      return t;
    });
  });
}

function onBackToParentTask() {
  open.value = false;
  emit('loadTask', task_id.value);
  setTimeout(() => {
    open.value = true;
  }, 750);
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
              enter="transform transition ease-in-out duration-500"
              enter-from="translate-x-full"
              enter-to="translate-x-0"
              leave="transform transition ease-in-out duration-500"
              leave-from="translate-x-0"
              leave-to="translate-x-full"
            >
              <DialogPanel class="pointer-events-auto w-screen max-w-2xl">
                <form @submit.prevent="onSubmit" class="flex h-full flex-col overflow-y-scroll bg-white shadow-xl">
                  <div class="flex-1">
                    <!-- Header -->
                    <div class="bg-gray-50 px-4 py-6 sm:px-6">
                      <div class="flex items-start justify-between space-x-3">
                        <div class="flex items-start justify-between">
                          <button v-if="parentTask" type="button" @click="onBackToParentTask" title="Parent Task Name" class="mr-4 hover:text-indigo-600">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                              <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                            </svg>
                          </button>
                          <DialogTitle class="text-base font-semibold text-gray-900">
                            {{ id ? 'Edit' : 'New' }} Task
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

                    <!-- Task name -->
                    <div class="space-y-2 px-4 sm:grid sm:grid-cols-3 sm:items-center sm:gap-4 sm:space-y-0 sm:px-6 sm:py-5">
                      <div>
                        <label
                          for="project-name"
                          class="block text-sm/6 font-medium text-gray-900 sm:mt-1.5"
                        >
                          Task name
                        </label>
                      </div>
                      <div class="sm:col-span-2 relative">
                        <input
                          type="text"
                          v-model="name"
                          name="project-name"
                          id="project-name"
                          class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6"
                        />
                        <div v-show="formErrors.name.length > 0" class="absolute">
                          <p v-for="err in formErrors.name" :key="err" class="text-sm text-red-600 dark:text-red-500">
                            {{ err }}
                          </p>
                        </div>
                      </div>
                    </div>

                    <!-- Assigned To -->
                    <div class="space-y-2 px-4 sm:grid sm:grid-cols-3 sm:items-center sm:gap-4 sm:space-y-0 sm:px-6 sm:py-5">
                      <div>
                        <h3 class="text-sm/6 font-medium text-gray-900">
                          Assigned To
                        </h3>
                      </div>
                      <div class="sm:col-span-2">
                        <InitialsList :items="assignedFilteredPeople" key-attribute="email" title-attribute="name">
                          <Popover>
                            <PopoverButton :disabled="projects.length == 1 && projects[0] === your_projects.find(p => p.is_personal).id" class="relative inline-flex size-8 shrink-0 items-center justify-center rounded-full border-2 border-dashed border-gray-200 bg-white text-gray-400 hover:border-gray-300 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                              <span class="absolute -inset-2" />
                              <span class="sr-only">Add team member</span>
                              <PlusIcon class="size-5" aria-hidden="true" />
                            </PopoverButton>
                            <PopoverPanel class="absolute top-8 -left-2">
                              <Combobox
                                as="div"
                                v-model="assigned_to_task"
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
                                          Assigned
                                        </span>
                                      </li>
                                      <ComboboxOption
                                        v-for="user in assignedFilteredPeople"
                                        :key="user.id"
                                        :value="user.id"
                                        :disabled="user.is_me && projects.includes(your_project.id)"
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
                                            <span
                                              :class="[
                                                'ml-3 truncate',
                                                selected && 'font-semibold',
                                              ]"
                                            >
                                              {{ user.name }}
                                            </span>
                                          </div>

                                          <span
                                            v-if="selected && !(user.is_me && projects.includes(your_project.id))"
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

                    <!-- Dates and such -->
                    <div class="space-y-6 py-6 sm:space-y-0 sm:py-5">
                      <div class="space-y-2 px-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:space-y-0 sm:px-6 sm:py-5">
                        <div class="sm:col-span-3">
                          <div
                            class="grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6"
                          >
                            <div class="sm:col-span-3">
                              <Listbox
                                as="div"
                                :modelValue="projects"
                                @update:modelValue="onProjectsUpdated"
                                multiple
                              >
                                <ListboxLabel class="block text-sm/6 font-medium text-gray-900">
                                  Projects
                                </ListboxLabel>
                                <div class="relative mt-2">
                                  <ListboxButton
                                    :class="[
                                      'grid w-full cursor-default grid-cols-1 rounded-md bg-white min-h-[calc(2rem+(2*0.375rem))] py-1.5 pl-3 pr-2 text-left outline outline-1 -outline-offset-1 outline-gray-300 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6',
                                      your_projects.length === 0 ? 'text-gray-400' : 'text-gray-900',
                                    ]"
                                    :disabled="your_projects.length === 0"
                                  >
                                    <span
                                      class="col-start-1 row-start-1 flex items-center gap-3 pr-6"
                                    >
                                      <InitialsList v-if="projects.length > 0" :items="your_projects.filter(p => projects.includes(p.id))" key-attribute="id" title-attribute="name" />
                                      <template v-else>None</template>
                                    </span>
                                    <ChevronUpDownIcon
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
                                      <ListboxOption
                                        as="template"
                                        v-for="project in your_projects"
                                        :key="project.id"
                                        :value="project.id"
                                        v-slot="{ active, selected }"
                                      >
                                        <li
                                          :class="[
                                            active
                                              ? 'bg-indigo-600 text-white outline-none'
                                              : 'text-gray-900',
                                            'relative cursor-default select-none py-2 pl-3 pr-9',
                                          ]"
                                        >
                                          <div class="flex items-center">
                                            <span
                                              :class="[
                                                selected
                                                  ? 'font-semibold'
                                                  : 'font-normal',
                                                'ml-3 block truncate',
                                              ]"
                                              >{{ project.name }}</span
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
                            </div>

                            <div class="sm:col-span-3">
                              <Listbox as="div" v-model="buckets" multiple>
                                <ListboxLabel class="block text-sm/6 font-medium text-gray-900">
                                  Buckets
                                </ListboxLabel>
                                <div class="relative mt-2">
                                  <ListboxButton
                                    :class="[
                                      'grid w-full cursor-default grid-cols-1 rounded-md bg-white min-h-[calc(2rem+(2*0.375rem))] py-1.5 pl-3 pr-2 text-left outline outline-1 -outline-offset-1 outline-gray-300 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6',
                                      your_buckets.length === 0 ? 'text-gray-400' : 'text-gray-900',
                                    ]"
                                    :disabled="your_buckets.length === 0"
                                  >
                                    <span class="col-start-1 row-start-1 flex items-center gap-3 pr-6">
                                      {{ buckets.length > 0
                                          ? your_buckets.filter(b => buckets.includes(b.id)).map(b => b.name).join(', ')
                                          : 'None'
                                      }}
                                    </span>
                                    <ChevronUpDownIcon
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
                                      <ListboxOption
                                        as="template"
                                        v-for="bucket in your_buckets"
                                        :key="bucket.id"
                                        :value="bucket.id"
                                        v-slot="{ active, selected }"
                                      >
                                        <li
                                          :class="[
                                            active
                                              ? 'bg-indigo-600 text-white outline-none'
                                              : 'text-gray-900',
                                            'relative cursor-default select-none py-2 pl-3 pr-9',
                                          ]"
                                        >
                                          <div class="flex items-center">
                                            <span
                                              :class="[
                                                selected
                                                  ? 'font-semibold'
                                                  : 'font-normal',
                                                'ml-3 block truncate',
                                              ]"
                                              >{{ bucket.name }}</span
                                            >
                                            <span :class="[active ? 'text-indigo-200' : 'text-gray-500', 'ml-2 truncate']">{{ bucket.project.name }}</span>
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
                            </div>

                            <div class="sm:col-span-3">
                              <label for="status" class="block text-sm/6 font-medium text-gray-900">
                                Progress
                              </label>
                              <div class="mt-2 grid grid-cols-1">
                                <select
                                  v-model="status"
                                  id="status"
                                  name="status"
                                  class="col-start-1 row-start-1 w-full appearance-none rounded-md bg-white py-1.5 pl-3 pr-8 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6"
                                >
                                  <option>Not Started</option>
                                  <option>In Progress</option>
                                  <option>Completed</option>
                                </select>
                                <ChevronDownIcon
                                  class="pointer-events-none col-start-1 row-start-1 mr-2 size-5 self-center justify-self-end text-gray-500 sm:size-4"
                                  aria-hidden="true"
                                />
                              </div>
                            </div>

                            <div class="sm:col-span-3">
                              <label
                                for="priority"
                                class="block text-sm/6 font-medium text-gray-900"
                                >Priority</label
                              >
                              <div class="mt-2 grid grid-cols-1">
                                <select
                                  v-model="priority"
                                  id="priority"
                                  name="priority"
                                  class="col-start-1 row-start-1 w-full appearance-none rounded-md bg-white py-1.5 pl-3 pr-8 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6"
                                >
                                  <option>Urgent</option>
                                  <option>Important</option>
                                  <option>Medium</option>
                                  <option>Low</option>
                                </select>
                                <ChevronDownIcon
                                  class="pointer-events-none col-start-1 row-start-1 mr-2 size-5 self-center justify-self-end text-gray-500 sm:size-4"
                                  aria-hidden="true"
                                />
                              </div>
                            </div>

                            <div class="sm:col-span-6">
                              <label
                                for="due_at"
                                class="block text-sm/6 font-medium text-gray-900"
                                >Due Date</label
                              >
                              <div class="mt-2">
                                <input
                                  v-model="due_at"
                                  type="datetime-local"
                                  id="due_at"
                                  name="due_at"
                                  class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6"
                                />
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>

                      <!-- Task description -->
                      <div class="space-y-2 px-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:space-y-0 sm:px-6 sm:py-5">
                        <div>
                          <label for="project-description" class="block text-sm/6 font-medium text-gray-900 sm:mt-1.5">
                            Description
                          </label>
                        </div>
                        <div class="sm:col-span-2">
                          <textarea
                            rows="3"
                            v-model="description"
                            name="project-description"
                            id="project-description"
                            class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6"
                          />
                        </div>
                      </div>
                      <div class="space-y-2 px-4 sm:px-6 sm:py-5">
                        <div>
                          <div>
                            <span class="block text-sm/6 font-medium text-gray-900 sm:mt-1.5">
                              Subtasks
                            </span>
                          </div>
                          <div class="flow-root">
                            <div class="inline-block min-w-full py-2 align-middle">
                              <table class="min-w-full divide-y divide-gray-300">
                                <thead>
                                  <tr>
                                    <th scope="col" class="py-3.5 text-left text-sm font-semibold text-gray-900">Progress</th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Name</th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Assigned To</th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Due On</th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 sm:pr-0">Priority</th>
                                  </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                  <tr v-for="subTask in tasks" :key="subTask.id" @click="onEditSubTask(subTask)" class="cursor-pointer">
                                    <td class="whitespace-nowrap py-4 text-sm font-medium text-gray-900 flex items-center justify-center">
                                      <TaskStatus :status="subTask.status" @update-task-status="(status) => updateSubTaskStatus(subTask.id, status)" />
                                    </td>
                                    <td class=" max-w-36 px-3 py-4 text-sm text-gray-700">
                                      {{ subTask.name }}
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-700">
                                      <InitialsList :items="subTask.assigned_to" size="6" />
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-700 sm:pr-0">
                                      {{ subTask.due_at ? new Date(subTask.due_at).toLocaleString('en-US', { timeZone: 'EST' }) : '' }}
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-700 sm:pr-0">
                                      {{ subTask.priority }}
                                    </td>
                                  </tr>
                                  <tr>
                                    <td :colspan="addTaskMode ? '1' : '4'" class=" whitespace-nowrap py-4 text-sm font-medium text-gray-900 flex items-center justify-center">
                                      <div>
                                        <button ref="add-task-button" @click="onClickNewTask" :disabled="id === null" :title="id === null ? 'Save the task before creating subtasks' : 'New subtask'" type="button" class="flex min-h-px max-h-9 w-5 my-0 mx-1 align-middle shrink-0 items-center justify-center text-indigo-600">
                                          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                          </svg>
                                        </button>
                                      </div>
                                    </td>
                                    <td v-if="addTaskMode" colspan="3">
                                      <div>
                                        <form autocomplete="off" @submit.prevent.stop="onNewTaskSubmit" @keydown.esc.stop="handleEscape">
                                          <input v-model="newTaskName" ref="new-task-input" type="text" placeholder="Task name" maxlength="256" aria-label="Add a new sub task" class="block rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6" />
                                        </form>
                                      </div>
                                    </td>
                                  </tr>
                                </tbody>
                              </table>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                  <!-- Action buttons -->
                  <div class="shrink-0 border-t border-gray-200 px-4 py-5 sm:px-6">
                    <div class="flex justify-between space-x-3">
                      <button
                        type="button"
                        class="rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50"
                        @click="onClose"
                      >
                        Cancel
                      </button>
                      <button
                        v-if="id"
                        type="button"
                        class="rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-red-500"
                        @click="onDelete"
                      >
                        Delete
                      </button>
                      <button
                        type="submit"
                        class="inline-flex justify-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
                      >
                        <span v-if="id">Save</span>
                        <span v-else>Create</span>
                      </button>
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
