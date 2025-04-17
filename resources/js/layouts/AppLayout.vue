<script setup>
import { computed, ref, useTemplateRef, nextTick } from 'vue';
import { Link, usePage, useForm } from '@inertiajs/vue3';
import { onClickOutside } from '@vueuse/core';
import {
  Dialog,
  DialogPanel,
  Menu,
  MenuButton,
  MenuItem,
  MenuItems,
  TransitionChild,
  TransitionRoot,
} from '@headlessui/vue';
import { Float } from '@headlessui-float/vue';
import {
  Bars3Icon,
  BellIcon,
  Cog6ToothIcon,
  FolderIcon,
  HomeIcon,
  UsersIcon,
  XMarkIcon,
} from '@heroicons/vue/24/outline';
import { ChevronDownIcon, MagnifyingGlassIcon } from '@heroicons/vue/20/solid';
import { useTaskModalStore } from '../stores/task-modal.js';
import Banner from '../components/dashboard/Banner.vue';

let props = defineProps({
  pageRoute: {
    type: String,
    required: true,
  },
  paramId: {
    type: String,
  },
});

let showStatus = ref(true);

let isProjectPage = computed(() => {
  return props.pageRoute.startsWith('project');
});

const currentURL = route(props.pageRoute, route().params);

let page = usePage();

let taskModalStore = useTaskModalStore();

let auth = computed(() => page.props.auth);

const nav = [
  { name: 'Your Tasks', href: route('dashboard-grid'), icon: HomeIcon, route: ['dashboard-grid', 'dashboard-board'] },
  { name: 'Organization', href: route('organization'), icon: UsersIcon, route: ['organization'] },
  { name: 'Projects', href: '#', icon: FolderIcon, route: [] },
];

let navigation = computed(() => {
  if (auth.value?.user.is_admin) {
    return nav;
  }

  return nav.filter((item) => item.name !== 'Organization');
});

let sidebarOpen = ref(false);

let addProjectMode = ref(false);
let newProjectForm = useTemplateRef('new-project-form');
let newProjectNameInput = useTemplateRef('new-project-name-input');
let addProjectButton = useTemplateRef('add-project-button');

let newProjectName = ref('');
let newProjectInitials = ref('');

onClickOutside(newProjectForm, () => addProjectMode.value = false);

let form = useForm({
  name: '',
  initials: '',
});

function onClickNewProject() {
  addProjectMode.value = true;
  nextTick(() => {
    newProjectNameInput.value.focus();
  });
}

function onNewProjectSubmit() {
  form.name = newProjectName.value;
  form.initials = newProjectInitials.value;
  form.post(route('create-project'), {
    headers: {
      'X-From': currentURL,
    },
    preserveState: true,
    preserveScroll: true,
    onSuccess: (results) => {
      taskModalStore.setProjects(results.props.task_options?.your_projects);
      addProjectMode.value = false;
      form.reset();
    },
    onError: (err) => {
      console.log(err);
      addProjectMode.value = false;
      form.reset();
      console.log('submitted error');
    },
  });
}

function handleEscape() {
  addProjectMode.value = false;
  nextTick(() => {
    addProjectButton.value.focus();
  });
}
</script>

<template>
  <!-- whole app wrapper -->
  <div class="flex flex-col flex-nowrap max-h-[100vh] max-w-[100vw] inset-0 fixed z-0 app-wrapper">
    <!-- header -->
    <div
      class="flex grow-0 shrink-0 basis-14 min-h-0 min-w-0 items-center gap-x-4 border-b border-gray-200 bg-white px-4 shadow-sm sm:gap-x-6 sm:px-6 lg:px-8"
    >
      <button
        type="button"
        class="-m-2.5 p-2.5 text-gray-700 lg:hidden"
        @click="sidebarOpen = true"
      >
        <span class="sr-only">Open sidebar</span>
        <Bars3Icon class="size-6" aria-hidden="true" />
      </button>

      <!-- Separator -->
      <div class="h-6 w-px bg-gray-200 lg:hidden" aria-hidden="true" />

      <div class="h-full flex flex-1 gap-x-4 self-stretch lg:gap-x-6">
        <form class="grid flex-1 grid-cols-1" action="#" method="GET">
          <input
            type="search"
            name="search"
            aria-label="Search"
            class="col-start-1 row-start-1 block size-full bg-white pl-8 text-base text-gray-900 outline-none placeholder:text-gray-400 sm:text-sm/6"
            placeholder="Search"
          />
          <MagnifyingGlassIcon
            class="pointer-events-none col-start-1 row-start-1 size-5 self-center text-gray-400"
            aria-hidden="true"
          />
        </form>
        <div class="flex items-center gap-x-4 lg:gap-x-6">
          <button
            type="button"
            class="-m-2.5 p-2.5 text-gray-400 hover:text-gray-500"
          >
            <span class="sr-only">View notifications</span>
            <BellIcon class="size-6" aria-hidden="true" />
          </button>

          <!-- Separator -->
          <div
            class="hidden lg:block lg:h-6 lg:w-px lg:bg-gray-200"
            aria-hidden="true"
          />

          <!-- Profile dropdown -->
          <Menu as="div" class="relative">
            <MenuButton class="-m-1.5 flex items-center p-1.5">
              <span class="sr-only">Open user menu</span>
              <img
                class="size-8 rounded-full bg-gray-50"
                src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80"
                alt=""
              />
              <span class="hidden lg:flex lg:items-center">
                <span
                  class="ml-4 text-sm/6 font-semibold text-gray-900"
                  aria-hidden="true"
                  >{{ auth.user?.first_name }} {{ auth.user?.last_name }}</span
                >
                <ChevronDownIcon
                  class="ml-2 size-5 text-gray-400"
                  aria-hidden="true"
                />
              </span>
            </MenuButton>
            <transition
              enter-active-class="transition ease-out duration-100"
              enter-from-class="transform opacity-0 scale-95"
              enter-to-class="transform opacity-100 scale-100"
              leave-active-class="transition ease-in duration-75"
              leave-from-class="transform opacity-100 scale-100"
              leave-to-class="transform opacity-0 scale-95"
            >
              <MenuItems
                class="absolute right-0 z-10 mt-2.5 w-56 origin-top-right rounded-md bg-white py-2 shadow-lg ring-1 ring-gray-900/5 focus:outline-none"
              >
                <div class="px-4 py-3">
                  <p class="text-sm">
                    {{ auth.user?.organization.name }}
                  </p>
                  <p class="truncate text-sm font-medium text-gray-900">
                    {{ auth.user?.email }}
                  </p>
                </div>
                <div class="py-1">
                  <MenuItem v-slot="{ active }">
                    <Link
                      method="post"
                      :href="route('logout')"
                      as="button"
                      :class="[
                        active ? 'bg-gray-50 outline-none' : '',
                        'block px-3 py-1 text-sm/6 text-gray-900',
                      ]"
                      >Logout</Link
                    >
                  </MenuItem>
                </div>
              </MenuItems>
            </transition>
          </Menu>
        </div>
      </div>
    </div>
    <!-- mobile sidebar -->
    <TransitionRoot as="template" :show="sidebarOpen">
      <Dialog class="relative z-50 lg:hidden" @close="sidebarOpen = false">
        <TransitionChild
          as="template"
          enter="transition-opacity ease-linear duration-300"
          enter-from="opacity-0"
          enter-to="opacity-100"
          leave="transition-opacity ease-linear duration-300"
          leave-from="opacity-100"
          leave-to="opacity-0"
        >
          <div class="fixed inset-0 bg-gray-900/80" />
        </TransitionChild>

        <div class="fixed inset-0 flex">
          <TransitionChild
            as="template"
            enter="transition ease-in-out duration-300 transform"
            enter-from="-translate-x-full"
            enter-to="translate-x-0"
            leave="transition ease-in-out duration-300 transform"
            leave-from="translate-x-0"
            leave-to="-translate-x-full"
          >
            <DialogPanel class="relative mr-16 flex w-full max-w-xs flex-1">
              <TransitionChild
                as="template"
                enter="ease-in-out duration-300"
                enter-from="opacity-0"
                enter-to="opacity-100"
                leave="ease-in-out duration-300"
                leave-from="opacity-100"
                leave-to="opacity-0"
              >
                <div
                  class="absolute left-full top-0 flex w-16 justify-center pt-5"
                >
                  <button
                    type="button"
                    class="-m-2.5 p-2.5"
                    @click="sidebarOpen = false"
                  >
                    <span class="sr-only">Close sidebar</span>
                    <XMarkIcon class="size-6 text-white" aria-hidden="true" />
                  </button>
                </div>
              </TransitionChild>
              <!-- Sidebar component, swap this element with another sidebar if you like -->
              <div
                class="flex grow flex-col gap-y-5 overflow-y-auto bg-white px-6 pb-4"
              >
                <div class="flex h-16 shrink-0 items-center">
                  <img
                    class="h-8 w-auto"
                    src="/logo.svg"
                    alt="Your Company"
                  />
                </div>
                <nav class="flex flex-1 flex-col">
                  <ul role="list" class="flex flex-1 flex-col gap-y-7">
                    <li>
                      <ul role="list" class="-mx-2 space-y-1">
                        <li v-for="item in navigation" :key="item.name">
                          <Link
                            :href="item.href"
                            :class="[
                              item.route.includes(pageRoute)
                                ? 'bg-gray-50 text-indigo-600'
                                : 'text-gray-700 hover:bg-gray-50 hover:text-indigo-600',
                              'group flex gap-x-3 rounded-md p-2 text-sm/6 font-semibold',
                            ]"
                          >
                            <component
                              :is="item.icon"
                              :class="[
                                item.route.includes(pageRoute)
                                  ? 'text-indigo-600'
                                  : 'text-gray-400 group-hover:text-indigo-600',
                                'size-6 shrink-0',
                              ]"
                              aria-hidden="true"
                            />
                            {{ item.name }}
                          </Link>
                        </li>
                      </ul>
                    </li>
                    <li>
                      <div class="text-xs/6 font-semibold text-gray-400">
                        Your Projects
                      </div>
                      <ul role="list" class="-mx-2 mt-2 space-y-1">
                        <li
                          v-for="project in page.props.nav_projects"
                          :key="project.id"
                          :class="[
                            isProjectPage && paramId == project.id
                              ? 'bg-gray-50 text-indigo-600'
                              : 'text-gray-700 hover:bg-gray-50 hover:text-indigo-600',
                            'group rounded-md text-sm/6 font-semibold',
                          ]"
                        >
                          <Link :href="route('project-board', project.id)" class="group flex flex-row flex-nowrap shrink grow basis-auto">
                            <div class="flex flex-row flex-nowrap shrink grow basis-auto items-center h-9">
                              <div class="flex flex-row flex-nowrap shrink-0 grow-0 basis-7 justify-center items-center w-7 h-7 ml-2 pt-px">
                                <span :class="[
                                  isProjectPage && paramId == project.id
                                    ? 'border-indigo-600 text-indigo-600'
                                    : 'border-gray-200 text-gray-400 group-hover:border-indigo-600 group-hover:text-indigo-600',
                                  'flex size-6 shrink-0 items-center justify-center rounded-lg border bg-white text-[0.625rem] font-medium',
                                ]">
                                  {{ project.initials }}
                                </span>
                              </div>
                              <div class="flex flex-row flex-nowrap shrink grow basis-auto ml-2">
                                <span class="truncate">
                                  {{ project.name }}
                                </span>
                              </div>
                              <Menu as="div" class="flex mx-2">
                                <Float placement="bottom-start">
                                  <div>
                                    <MenuButton @click.prevent class="block size-5 rounded-md text-sm font-semibold text-gray-900">
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
                          </Link>
                        </li>
                        <li ref="new-project-form-mobile" class="group rounded-md text-sm/6 font-semibold text-indigo-600">
                          <button
                            ref="add-project-button-mobile"
                            @click="onClickNewProject"
                            type="button"
                            :class="[
                              'flex opacity-0 group-hover:opacity-100 min-h-px max-h-9 w-5 my-0 ml-3 mr-1 align-middle shrink-0 items-center justify-center',

                            ]"
                            v-if="!addProjectMode"
                          >
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                              <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                            </svg>
                          </button>
                          <form v-else autocomplete="off" @submit.prevent="onNewProjectSubmit" @keydown.esc="handleEscape">
                            <input v-model="newProjectName" ref="new-project-name-input-mobile" type="text" placeholder="Project name" maxlength="256" aria-label="Add a project name" class="block rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6" />
                            <input v-model="newProjectInitials" type="text" placeholder="Project initials" maxlength="5" aria-label="Add a project initials" class="block rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6" />
                            <button type="submit" class="hidden"></button>
                          </form>
                        </li>
                      </ul>
                    </li>
                    <li v-if="page.props.admin_nav_projects.length > 0">
                      <div class="text-xs/6 font-semibold text-gray-400">
                        Other Projects
                      </div>
                      <ul role="list" class="-mx-2 mt-2 space-y-1">
                        <li
                          v-for="project in page.props.admin_nav_projects"
                          :key="project.id"
                          :class="[
                            isProjectPage && paramId == project.id
                              ? 'bg-gray-50 text-indigo-600'
                              : 'text-gray-700 hover:bg-gray-50 hover:text-indigo-600',
                            'group rounded-md text-sm/6 font-semibold',
                          ]"
                        >
                          <Link :href="route('project-board', project.id)" class="group flex flex-row flex-nowrap shrink grow basis-auto">
                            <div class="flex flex-row flex-nowrap shrink grow basis-auto items-center h-9">
                              <div class="flex flex-row flex-nowrap shrink-0 grow-0 basis-7 justify-center items-center w-7 h-7 ml-2 pt-px">
                                <span :class="[
                                  isProjectPage && paramId == project.id
                                    ? 'border-indigo-600 text-indigo-600'
                                    : 'border-gray-200 text-gray-400 group-hover:border-indigo-600 group-hover:text-indigo-600',
                                  'flex size-6 shrink-0 items-center justify-center rounded-lg border bg-white text-[0.625rem] font-medium',
                                ]">
                                  {{ project.initials }}
                                </span>
                              </div>
                              <div class="flex flex-row flex-nowrap shrink grow basis-auto ml-2">
                                <span class="truncate">
                                  {{ project.name }}
                                </span>
                              </div>
                              <Menu as="div" class="flex mx-2 opacity-0 group-hover:opacity-100">
                                <Float placement="bottom-start">
                                  <div>
                                    <MenuButton @click.prevent class="block size-5 rounded-md text-sm font-semibold text-gray-900">
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
                          </Link>
                        </li>
                      </ul>
                    </li>
                    <li class="mt-auto">
                      <a
                        href="#"
                        class="group -mx-2 flex gap-x-3 rounded-md p-2 text-sm/6 font-semibold text-gray-700 hover:bg-gray-50 hover:text-indigo-600"
                      >
                        <Cog6ToothIcon
                          class="size-6 shrink-0 text-gray-400 group-hover:text-indigo-600"
                          aria-hidden="true"
                        />
                        Settings
                      </a>
                    </li>
                  </ul>
                </nav>
              </div>
            </DialogPanel>
          </TransitionChild>
        </div>
      </Dialog>
    </TransitionRoot>

    <div class="flex flex-row flex-nowrap grow shrink basis-0 min-h-0 min-w-0">
      <!-- Static sidebar for desktop -->
      <div
        class="hidden lg:flex lg:w-72 lg:flex-col"
      >
        <!-- Sidebar component, swap this element with another sidebar if you like -->
        <div
          class="flex grow flex-col gap-y-5 overflow-y-auto overflow-x-visible min-h-0 min-w-0 border-r border-gray-200 bg-white px-6 pb-4"
        >
          <div class="flex h-16 shrink-0 items-center">
            <img
              class="h-8 w-auto"
              src="https://tailwindui.com/plus-assets/img/logos/mark.svg?color=indigo&shade=600"
              alt="Your Company"
            />
          </div>
          <nav class="flex flex-1 flex-col">
            <ul role="list" class="flex flex-1 flex-col gap-y-7">
              <li>
                <ul role="list" class="-mx-2 space-y-1">
                  <li v-for="item in navigation" :key="item.name">
                    <Link
                      :href="item.href"
                      :class="[
                        item.route.includes(pageRoute)
                          ? 'bg-gray-50 text-indigo-600'
                          : 'text-gray-700 hover:bg-gray-50 hover:text-indigo-600',
                        'group flex gap-x-3 rounded-md p-2 text-sm/6 font-semibold',
                      ]"
                    >
                      <component
                        :is="item.icon"
                        :class="[
                          item.route.includes(pageRoute)
                            ? 'text-indigo-600'
                            : 'text-gray-400 group-hover:text-indigo-600',
                          'size-6 shrink-0',
                        ]"
                        aria-hidden="true"
                      />
                      {{ item.name }}
                    </Link>
                  </li>
                </ul>
              </li>
              <li>
                <div class="text-xs/6 font-semibold text-gray-400">
                  Your Projects
                </div>
                <ul role="list" class="-mx-2 mt-2 space-y-1">
                  <li
                    v-for="project in page.props.nav_projects"
                    :key="project.id"
                    :class="[
                      isProjectPage && paramId == project.id
                        ? 'bg-gray-50 text-indigo-600'
                        : 'text-gray-700 hover:bg-gray-50 hover:text-indigo-600',
                      'group rounded-md text-sm/6 font-semibold',
                    ]"
                  >
                    <Link :href="route('project-board', project.id)" class="group flex flex-row flex-nowrap shrink grow basis-auto">
                      <div class="flex flex-row flex-nowrap shrink grow basis-auto items-center h-9">
                        <div class="flex flex-row flex-nowrap shrink-0 grow-0 basis-7 justify-center items-center w-7 h-7 ml-2 pt-px">
                          <span :class="[
                            isProjectPage && paramId == project.id
                              ? 'border-indigo-600 text-indigo-600'
                              : 'border-gray-200 text-gray-400 group-hover:border-indigo-600 group-hover:text-indigo-600',
                            'flex size-6 shrink-0 items-center justify-center rounded-lg border bg-white text-[0.625rem] font-medium',
                          ]">
                            {{ project.initials }}
                          </span>
                        </div>
                        <div class="flex flex-row flex-nowrap shrink grow basis-auto ml-2">
                          <span class="truncate">
                            {{ project.name }}
                          </span>
                        </div>
                        <Menu as="div" class="flex mx-2 opacity-0 group-hover:opacity-100">
                          <Float placement="bottom-start">
                            <div>
                              <MenuButton @click.prevent class="block size-5 rounded-md text-sm font-semibold text-gray-900">
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
                    </Link>
                  </li>
                  <li ref="new-project-form" class="group rounded-md text-sm/6 font-semibold text-indigo-600">
                    <button
                      ref="add-project-button"
                      @click="onClickNewProject"
                      type="button"
                      :class="[
                        'flex opacity-0 group-hover:opacity-100 min-h-px max-h-9 w-5 my-0 ml-3 mr-1 align-middle shrink-0 items-center justify-center',

                      ]"
                      v-if="!addProjectMode"
                    >
                      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                      </svg>
                    </button>
                    <form v-else autocomplete="off" @submit.prevent="onNewProjectSubmit" @keydown.esc="handleEscape">
                      <input v-model="newProjectName" ref="new-project-name-input" type="text" placeholder="Project name" maxlength="256" aria-label="Add a project name" class="block rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6" />
                      <input v-model="newProjectInitials" type="text" placeholder="Project initials" maxlength="5" aria-label="Add a project initials" class="block rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6" />
                      <button type="submit" class="hidden"></button>
                    </form>
                  </li>
                </ul>
              </li>
              <li v-if="page.props.admin_nav_projects.length > 0">
                <div class="text-xs/6 font-semibold text-gray-400">
                  Other Projects
                </div>
                <ul role="list" class="-mx-2 mt-2 space-y-1">
                  <li
                    v-for="project in page.props.admin_nav_projects"
                    :key="project.id"
                    :class="[
                      isProjectPage && paramId == project.id
                        ? 'bg-gray-50 text-indigo-600'
                        : 'text-gray-700 hover:bg-gray-50 hover:text-indigo-600',
                      'group rounded-md text-sm/6 font-semibold',
                    ]"
                  >
                    <Link :href="route('project-board', project.id)" class="group flex flex-row flex-nowrap shrink grow basis-auto">
                      <div class="flex flex-row flex-nowrap shrink grow basis-auto items-center h-9">
                        <div class="flex flex-row flex-nowrap shrink-0 grow-0 basis-7 justify-center items-center w-7 h-7 ml-2 pt-px">
                          <span :class="[
                            isProjectPage && paramId == project.id
                              ? 'border-indigo-600 text-indigo-600'
                              : 'border-gray-200 text-gray-400 group-hover:border-indigo-600 group-hover:text-indigo-600',
                            'flex size-6 shrink-0 items-center justify-center rounded-lg border bg-white text-[0.625rem] font-medium',
                          ]">
                            {{ project.initials }}
                          </span>
                        </div>
                        <div class="flex flex-row flex-nowrap shrink grow basis-auto ml-2">
                          <span class="truncate">
                            {{ project.name }}
                          </span>
                        </div>
                        <Menu as="div" class="flex mx-2 opacity-0 group-hover:opacity-100">
                          <Float placement="bottom-start">
                            <div>
                              <MenuButton @click.prevent class="block size-5 rounded-md text-sm font-semibold text-gray-900">
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
                    </Link>
                  </li>
                </ul>
              </li>
              <li class="mt-auto">
                <a
                  href="#"
                  class="group -mx-2 flex gap-x-3 rounded-md p-2 text-sm/6 font-semibold text-gray-700 hover:bg-gray-50 hover:text-indigo-600"
                >
                  <Cog6ToothIcon
                    class="size-6 shrink-0 text-gray-400 group-hover:text-indigo-600"
                    aria-hidden="true"
                  />
                  Settings
                </a>
              </li>
            </ul>
          </nav>
        </div>
      </div>
      <main class="w-full float-none overflow-hidden relative">
        <div class="flex flex-col flex-nowrap grow shrink basis-0 overflow-y-auto h-full min-h-0 min-w-0 relative z-0 bg-cover bg-[linear-gradient(rgb(255,255,255)_0%,rgba(255,255,255,0.2)_50%),url(https://cdn.hubblecontent.osi.office.net/getty/publish/gettyimages/849074186_t10_template_13.jpg)]">
          <div class="flex grow shrink basis-0 flex-col flex-nowrap min-w-0 min-h-0">
            <Banner :show="page.props.status !== undefined && showStatus" @close="showStatus = false">
              {{ page.props.status }}
            </Banner>
            <slot />
          </div>
        </div>
      </main>
    </div>
  </div>
</template>
