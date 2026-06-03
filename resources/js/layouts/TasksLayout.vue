<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';
import AppLayout from './AppLayout.vue';
import GridIcon from '../components/icons/GridIcon.vue';
import BoardIcon from '../components/icons/BoardIcon.vue';

let props = defineProps({
  pageRoute: {
    type: String,
    required: true,
  },
  paramId: {
    type: String,
  },
  otherVersionPageRouteName: {
    type: String,
    required: true,
  },
  pageTitle: {
    type: String,
    required: true,
  },
  pageDescription: {
    type: String,
    required: false,
  },
  showCreateTaskButton: {
    type: Boolean,
    required: false,
    default: true,
  },
});

let emit = defineEmits(['openCreateTaskModal']);

let isProjectPage = computed(() => {
  return props.pageRoute.startsWith('project');
});

let otherVersionPageRoute = computed(() => {
  if (isProjectPage.value) {
    return route(props.otherVersionPageRouteName, props.paramId);
  }
  return route(props.otherVersionPageRouteName);
});
</script>

<template>
  <AppLayout :pageRoute="pageRoute" :paramId="isProjectPage ? paramId : undefined">
    <div class="sm:flex sm:items-center pb-2 pt-4 sm:pt-10 sm:pb-0 px-4 sm:px-6 lg:px-8">
      <div class="sm:flex-auto">
        <h1 class="text-base font-semibold text-gray-900">{{ pageTitle }}</h1>
        <p class="mt-2 text-sm text-gray-700">{{ pageDescription }}</p>
      </div>
      <div class="flex justify-between gap-y-8 sm:flex-row">
        <div class="flex items-center gap-8 order-last sm:order-first">
          <template v-if="otherVersionPageRoute.endsWith('board')">
            <span class="border-b-2 border-b-indigo-600 pb-2">
              <span class="text-indigo-600"><GridIcon :filled="true" /></span> <span class="font-medium">Grid</span>
            </span>
            <Link :href="otherVersionPageRoute" class="pb-[calc(0.5rem+2px)]">
              <span class="text-black"><BoardIcon :filled="false" /></span> <span class="font-normal">Board</span>
            </Link>
          </template>
          <template v-else>
            <Link :href="otherVersionPageRoute" class="pb-[calc(0.5rem+2px)]">
              <span class="text-black"><GridIcon :filled="false" /></span> <span class="font-normal">Grid</span>
            </Link>
            <span class="border-b-2 border-b-indigo-600 pb-2">
              <span class="text-indigo-600"><BoardIcon :filled="true" /></span> <span class="font-medium">Board</span>
            </span>
          </template>
        </div>
        <div v-if="showCreateTaskButton" class="mt-2 sm:ml-16 sm:mt-0 sm:flex-none order-first sm:order-last">
          <button
            type="button"
            @click="emit('openCreateTaskModal')"
            class="block rounded-md bg-indigo-600 px-3 py-2 text-center text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
          >
            Add Task
          </button>
        </div>
      </div>
    </div>
    <slot />
  </AppLayout>
</template>
