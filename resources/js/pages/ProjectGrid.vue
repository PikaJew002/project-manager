<script setup>
import { Head, Link, usePage } from '@inertiajs/vue3';
import { storeToRefs } from 'pinia';
import { useTaskModalStore } from '../stores/task-modal.js';
import { usePagesStore } from '../stores/pages.js';
import AppLayout from '../layouts/AppLayout.vue';
import BoardIcon from '../components/icons/BoardIcon.vue';
import GridIcon from '../components/icons/GridIcon.vue';
import TaskModal from '../components/TaskModal.vue';
import GridDashboard from '../components/GridDashboard.vue';
import TaskRow from '../components/dashboard/TaskRow.vue';
import TaskStatus from '../components/shared/TaskStatus.vue';
import TaskRowWrapper from '../components/dashboard/TaskRowWrapper.vue';
import ColumnHeader from '../components/dashboard/ColumnHeader.vue';

let props = defineProps({
  project: Object,
  project_tasks: Array,
  id: Number,
});

const currentURL = route(route().current(), route().params);

let page = usePage();

let taskModalStore = useTaskModalStore();
let pagesStore = usePagesStore();

pagesStore.setProject(props.project)
pagesStore.setTasks(props.project_tasks);

taskModalStore.setUsers(page.props.task_options?.assignable_users);
taskModalStore.setProjects(page.props.task_options?.your_projects);
taskModalStore.setBuckets(page.props.task_options?.your_buckets);

let { open, projects, assigned_to, status } = storeToRefs(taskModalStore);

let { tasks } = storeToRefs(pagesStore);

function openEditTaskModal(task) {
  if (task.task_id) {
    let foundTask = pagesStore.findTaskInGrid(task.task_id);
    if (foundTask) {
      taskModalStore.setTask(task, true);
    } else {
      taskModalStore.setTask(task, false);
    }
  } else {
    taskModalStore.setTask(task, false);
  }
  open.value = true;
}

function updateTaskStatus({ task, newStatus }) {
  taskModalStore.setTask(task);
  status.value = newStatus;
  taskModalStore.onFormSubmitUpdate(currentURL, function (results) {
    onUpdatedTasks(results);
  });
}

function openCreateTaskModal() {
  assigned_to.value = [page.props.task_options?.assignable_users.find(u => u.is_me)];
  projects.value = [props.project.id];

  open.value = true;
}

function onUpdatedTasks(results) {
  pagesStore.setTasks(results.props.project_tasks);
}

function onEditParentTask(taskId) {
  let foundTask = pagesStore.findTaskInGrid(taskId);
  if (foundTask) {
    let foundParent = pagesStore.findTaskInGrid(foundTask.task_id);
    if (foundParent) {
      taskModalStore.setTask(foundTask, true);
    } else {
      taskModalStore.setTask(foundTask, false);
    }
  }
}
</script>

<template>
  <Head title="Project Manager" />
  <AppLayout :pageRoute="route().current()" :paramId="route().params.id">
    <div class="sm:flex sm:items-center pt-10 px-4 sm:px-6 lg:px-8">
      <div class="sm:grow sm:shrink">
        <h1 class="text-base font-semibold text-gray-900">{{ project.name }}</h1>
        <p class="mt-2 text-sm text-gray-700">A list of this projects tasks</p>
      </div>
      <div class="flex flex-col gap-y-8 sm:flex-row">
        <div class="flex gap-8 order-last sm:order-first">
          <span class="border-b-2 border-b-indigo-600 pb-2">
            <span class="text-indigo-600"><GridIcon :filled="true" /></span> <span class="font-medium">Grid</span>
          </span>
          <Link :href="route('project-board', id)" class="pb-[calc(0.5rem+2px)]">
            <span class="text-black"><BoardIcon :filled="false" /></span> <span class="font-normal">Board</span>
          </Link>
        </div>
        <div v-if="tasks.length > 0" class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none order-first sm:order-last">
          <button
            type="button"
            @click="openCreateTaskModal"
            class="block rounded-md bg-indigo-600 px-3 py-2 text-center text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
          >
            Add Task
          </button>
        </div>
      </div>
    </div>
    <GridDashboard v-if="tasks.length > 0" :items="tasks" @edit-task="openEditTaskModal">
      <template #header-4>
        <ColumnHeader>
          Buckets
        </ColumnHeader>
      </template>
      <template #default="{ items }">
        <TaskRowWrapper
          :tasks="items"
          @edit-task="openEditTaskModal"
          @update-task-status="updateTaskStatus"
        />
      </template>
    </GridDashboard>
    <div v-else class="text-center mt-6">
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="mx-auto size-12 text-gray-400" aria-hidden="true">
        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z" />
      </svg>
      <h3 class="mt-2 text-sm font-semibold text-gray-900">No tasks</h3>
      <p class="mt-1 text-sm text-gray-500">Get started by creating a new task.</p>
      <div class="mt-6">
        <button @click="openCreateTaskModal" type="button" class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
          Add Task
        </button>
      </div>
    </div>
    <TaskModal :page="currentURL" @updated-tasks="onUpdatedTasks" @load-task="onEditParentTask" />
  </AppLayout>
</template>
