<script setup>
import { Head, Link, usePage } from '@inertiajs/vue3';
import { storeToRefs } from 'pinia';
import { useTaskModalStore } from '../stores/task-modal.js';
import { usePagesStore } from '../stores/pages.js';
import AppLayout from '../layouts/AppLayout.vue';
import BoardIcon from '../components/icons/BoardIcon.vue';
import GridIcon from '../components/icons/GridIcon.vue';
import TaskModal from '../components/TaskModal.vue';
import BoardDashboard from '../components/BoardDashboard.vue';
import Bucket from '../components/dashboard/Bucket.vue';

let props = defineProps({
  your_projects: Array,
  your_tasks: Array,
});

const currentURL = route(route().current(), route().params);

let page = usePage();

let taskModalStore = useTaskModalStore();
let pagesStore = usePagesStore();

pagesStore.setProjects(props.your_projects);
pagesStore.setProjectTasks(props.your_tasks);

taskModalStore.setUsers(page.props.task_options?.assignable_users);
taskModalStore.setProjects(page.props.task_options?.your_projects);
taskModalStore.setBuckets(page.props.task_options?.your_buckets);

let { open, projects, assigned_to, status } = storeToRefs(taskModalStore);

function openEditTaskModal(task) {
  if (task.task_id) {
    let foundTask = pagesStore.findTaskInYourTasks(task.task_id);
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
  projects.value = [page.props.task_options?.your_projects.find(p => p.is_personal)?.id];

  open.value = true;
}

function onUpdatedTasks(results) {
  pagesStore.setProjects(results.props.your_projects);
  pagesStore.setProjectTasks(results.props.your_tasks);
}

function onEditParentTask(taskId) {
  let foundTask = pagesStore.findTaskInYourTasks(taskId);
  if (foundTask) {
    let foundParent = pagesStore.findTaskInYourTasks(foundTask.task_id);
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
  <AppLayout>
    <div class="sm:flex sm:items-center px-4 sm:px-6 lg:px-8">
      <div class="sm:flex-auto">
        <h1 class="text-base font-semibold text-gray-900">Your Tasks</h1>
        <p class="mt-2 text-sm text-gray-700">A list of all your tasks</p>
      </div>
      <div class="flex flex-col gap-y-8 sm:flex-row">
        <div class="flex gap-8 order-last sm:order-first">
          <Link :href="route('dashboard-grid')" class="pb-[calc(0.5rem+2px)]">
            <span class="text-black"><GridIcon :filled="false" /></span> <span class="font-normal">Grid</span>
          </Link>
          <span class="border-b-2 border-b-indigo-600 pb-2">
            <span class="text-indigo-600"><BoardIcon :filled="true" /></span> <span class="font-medium">Board</span>
          </span>
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
    <BoardDashboard>
      <Bucket
        v-if="pagesStore.project_tasks.length > 0"
        title="No Project"
        :tasks="pagesStore.project_tasks"
        @click-add-task="openCreateTaskModal()"
        @click-task-card="openEditTaskModal"
        @update-task-status="updateTaskStatus"
        :show-three-dots="false"
        :show-add-task="false"
      />
      <template v-for="project in pagesStore.projects" :key="project.id">
        <Bucket
          v-if="project.tasks.length > 0"
          title="No Bucket"
          :subtitle="project.name"
          :tasks="project.tasks"
          @click-add-task="openCreateTaskModal()"
          @click-task-card="openEditTaskModal"
          @update-task-status="updateTaskStatus"
          :show-three-dots="false"
        />
        <Bucket
          v-for="bucket in project.buckets.filter(b => b.tasks.length > 0)"
          :key="bucket.id"
          :title="bucket.name"
          :subtitle="project.name"
          :id="bucket.id"
          :tasks="bucket.tasks"
          :show-three-dots="false"
          @click-add-task="openCreateTaskModal"
          @click-task-card="openEditTaskModal"
          @update-task-status="updateTaskStatus"
        />
      </template>
    </BoardDashboard>
    <TaskModal :page="currentURL" @updated-tasks="onUpdatedTasks" @load-task="onEditParentTask" />
  </AppLayout>
</template>
