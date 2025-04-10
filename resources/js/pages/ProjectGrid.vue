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
  taskModalStore.setTask(task);
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
</script>

<template>
  <Head title="Project Manager" />
  <AppLayout>
    <div class="sm:flex sm:items-center px-4 sm:px-6 lg:px-8">
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
        <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none order-first sm:order-last">
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
    <GridDashboard :tasks="tasks" @edit-task="openEditTaskModal">
      <template #header-4>
        Buckets
      </template>
      <template #default="{ tasks }">
        <TaskRowWrapper
          :tasks="tasks"
          @edit-task="openEditTaskModal"
          @update-task-status="updateTaskStatus"
        />
      </template>
    </GridDashboard>
    <TaskModal :page="currentURL" @updated-tasks="onUpdatedTasks" />
  </AppLayout>
</template>
