<script setup>
import { nextTick, ref, useTemplateRef } from 'vue';
import { Head, Link, usePage } from '@inertiajs/vue3';
import { onClickOutside } from '@vueuse/core';
import { storeToRefs } from 'pinia';
import { useForm } from '@inertiajs/vue3';
import { useTaskModalStore } from '../stores/task-modal.js';
import { usePagesStore } from '../stores/pages.js';
import AppLayout from '../layouts/AppLayout.vue';
import BoardIcon from '../components/icons/BoardIcon.vue';
import GridIcon from '../components/icons/GridIcon.vue';
import TaskModal from '../components/TaskModal.vue';
import BoardDashboard from '../components/BoardDashboard.vue';
import Bucket from '../components/dashboard/Bucket.vue';
import BucketWrapper from '../components/dashboard/BucketWrapper.vue';
import TaskCard from '../components/dashboard/TaskCard.vue';

let props = defineProps({
  project: Object,
  id: Number,
});

const currentURL = route(route().current(), route().params);

let page = usePage();

let taskModalStore = useTaskModalStore();
let pagesStore = usePagesStore();

pagesStore.setProject(props.project);

taskModalStore.setUsers(page.props.task_options?.assignable_users);
taskModalStore.setProjects(page.props.task_options?.your_projects);
taskModalStore.setBuckets(page.props.task_options?.your_buckets);

let { open, projects, buckets, assigned_to, status } = storeToRefs(taskModalStore);

let addBucketMode = ref(false);
let newBucketInput = useTemplateRef('new-bucket-input');
let addBucketButton = useTemplateRef('add-bucket-button');

let newBucketName = ref('');

onClickOutside(newBucketInput, () => addBucketMode.value = false);

let form = useForm({
  project_id: props.project.id,
  name: '',
});

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

function openCreateTaskModal(bucketId = null) {
  assigned_to.value = [page.props.task_options?.assignable_users.find(u => u.is_me)];
  projects.value = [props.project.id];
  if (bucketId) {
    buckets.value = [bucketId];
  }

  open.value = true;
}

function onClickNewBucket() {
  addBucketMode.value = true;
  nextTick(() => {
    newBucketInput.value.focus();
  });
}

function onNewBucketSubmit() {
  form.name = newBucketName.value;
  form.post(route('create-bucket'), {
    headers: {
      'X-From': currentURL,
    },
    preserveState: true,
    preserveScroll: true,
    onSuccess: (page) => {
      pagesStore.setProject(props.project);
      taskModalStore.setProjects(page.props.task_options?.your_projects);
      taskModalStore.setBuckets(page.props.task_options?.your_buckets);
      addBucketMode.value = false;
      form.reset();
    },
    onError: (err) => {
      console.log(err);
      addBucketMode.value = false;
      form.reset();
      console.log('submitted error');
    },
  });
}

function handleEscape() {
  addBucketMode.value = false;
  nextTick(() => {
    addBucketButton.value.focus();
  });
}

function onUpdatedTasks(results) {
  pagesStore.setProject(results.props.project);
}

function onEditParentTask(taskId) {
  let foundTask = pagesStore.findTaskInProjectBoard(taskId);
  if (foundTask) {
    let foundParent = pagesStore.findTaskInDashboardBoard(foundTask.task_id);
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
        <p class="mt-2 text-sm text-gray-700">A list of this project's tasks</p>
      </div>
      <div class="flex flex-col gap-y-8 sm:flex-row">
        <div class="flex gap-8 order-last sm:order-first">
          <Link :href="route('project-grid', id)" class="pb-[calc(0.5rem+2px)]">
            <span class="text-black"><GridIcon :filled="false" /></span> <span class="font-normal">Grid</span>
          </Link>
          <span class="border-b-2 border-b-indigo-600 pb-2">
            <span class="text-indigo-600"><BoardIcon :filled="true" /></span> <span class="font-medium">Board</span>
          </span>
        </div>
        <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none order-first sm:order-last">
          <button
            type="button"
            @click="openCreateTaskModal()"
            class="block rounded-md bg-indigo-600 px-3 py-2 text-center text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
          >
            Add Task
          </button>
        </div>
      </div>
    </div>
    <BoardDashboard>
      <Bucket
        title="No Bucket"
        :tasks="pagesStore.project.tasks"
        @click-add-task="openCreateTaskModal"
        :show-three-dots="false"
        v-slot="{ tasks }"
      >
        <TaskCard
          v-for="task in tasks"
          :key="task.id"
          :task="task"
          @edit-task="openEditTaskModal"
          @update-task-status="updateTaskStatus"
        />
      </Bucket>
      <Bucket
        v-for="bucket in pagesStore.project.buckets"
        :key="bucket.id"
        :title="bucket.name"
        :id="bucket.id"
        :tasks="bucket.tasks"
        @click-add-task="openCreateTaskModal"
        v-slot="{ tasks }"
      >
        <TaskCard
          v-for="task in tasks"
          :key="task.id"
          :task="task"
          @edit-task="openEditTaskModal"
          @update-task-status="updateTaskStatus"
        />
      </Bucket>
      <BucketWrapper>
        <div class="px-3 font-semibold leading-8 min-h-0 min-w-0">
          <button ref="add-bucket-button" v-if="!addBucketMode" @click="onClickNewBucket" type="button" class="flex flex-row flex-nowrap flex-auto basis-0 my-0 mx-1">
            Add a new bucket
          </button>
          <form v-else autocomplete="off" @submit.prevent="onNewBucketSubmit" @keydown.esc="handleEscape">
            <input v-model="newBucketName" ref="new-bucket-input" type="text" placeholder="Bucket name" maxlength="256" aria-label="Add a new bucket" class="block rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6" />
          </form>
        </div>
      </BucketWrapper>
    </BoardDashboard>
    <TaskModal :page="currentURL" @updated-tasks="onUpdatedTasks" @load-task="onEditParentTask" />
  </AppLayout>
</template>
