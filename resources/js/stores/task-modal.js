import { ref } from 'vue';
import { defineStore } from 'pinia';
import { useForm } from '@inertiajs/vue3';

export const useTaskModalStore = defineStore('task-modal-store', () => {
  // controls display of modal
  let open = ref(false);
  let openCreateTaskButton = ref(null);

  // in edit mode, part of submission URL
  let id = ref(null);

  // create/new task form elements
  let task_id = ref(null); // parent task, if subtask
  let parentTask = ref(false);
  let name = ref('');
  let description = ref('');
  let due_at = ref(null);
  let priority = ref('Medium');
  let status = ref('Not Started');
  let assigned_to = ref([]);
  let tasks = ref([]);
  let projects = ref([]);
  let buckets = ref([]);
  let oldProjects = ref([]);
  let oldBuckets = ref([]);

  let form = useForm({
    task_id: null,
    name: '',
    description: '',
    due_at: null,
    priority: 'Medium',
    status: 'Not Started',
    assigned_to: [],
    projects: [],
    buckets: [],
  });

  let subTaskForm = useForm({
    task_id: null,
    name: '',
  });

  let subTaskUpdateForm = useForm({
    status: '',
  });

  // global stores
  let all_projects = ref([]);
  let all_buckets = ref([]);

  // in the future, headshots/avatars should be this size: https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80
  let all_users = ref([]);

  let all_tasks = ref([]);

  function setTask(currentTask, parent = false) {
    id.value = currentTask.id;
    task_id.value = currentTask.task_id;
    parentTask.value = parent;
    name.value = currentTask.name;
    description.value = currentTask.description;
    due_at.value = currentTask.due_at;
    priority.value = currentTask.priority;
    status.value = currentTask.status;
    assigned_to.value = currentTask.assigned_to;
    buckets.value = currentTask.buckets.map((b) => b.id);
    oldBuckets.value = buckets.value;
    projects.value = currentTask.projects.map((p) => p.id).concat(currentTask.buckets.map(b => b.project_id));
    oldProjects.value = projects.value;
    tasks.value = currentTask.tasks;
  }

  function onOpen() {
    open.value = true;
  }

  function onClose() {
    open.value = false;
    reset();
  }

  function reset() {
    id.value = null;
    task_id.value = null;
    name.value = '';
    description.value = '';
    due_at.value = null;
    priority.value = 'Medium';
    status.value = 'Not Started';
    assigned_to.value = [];
    projects.value = [];
    tasks.value = [];
    oldProjects.value = [];
    buckets.value = [];
    oldBuckets.value = [];
  }

  function setProjects(initialProjects) {
    all_projects.value = initialProjects;
  }

  function setBuckets(initialBuckets) {
    all_buckets.value = initialBuckets;
  }

  function setUsers(initialUsers) {
    all_users.value = initialUsers;
  }

  function setTasks(initialTasks) {
    all_tasks.value = initialTasks;
  }

  function preFormSubmit() {
    form.task_id = task_id.value;
    form.name = name.value;
    form.description = description.value;
    form.due_at = due_at.value;
    form.priority = priority.value;
    form.status = status.value;
    form.assigned_to = assigned_to.value.map((user) => user.id);
    form.projects = projects.value;
    form.buckets = buckets.value;
  }

  function onFormSubmitCreate(page, onFinish, onError) {
    preFormSubmit();
    form.post(route('create-task'), {
      headers: {
        'X-From': page,
      },
      preserveState: true,
      preserveScroll: true,
      onSuccess: (page) => {
        onFinish(page);
        onClose();
      },
      onError,
    });
  }

  function onSubTaskFormSubmitCreate(newName, page, onFinish, onError) {
    subTaskForm.task_id = id.value;
    subTaskForm.name = newName;
    subTaskForm.post(route('create-task'), {
      headers: {
        'X-From': page,
      },
      preserveState: true,
      preserveScroll: true,
      onSuccess: (page) => {
        subTaskForm.reset();
        onFinish(page);
      },
      onError,
    });
  }

  function onSubTaskFormSubmitUpdateStatus(
    subTaskId,
    newStatus,
    page,
    onFinish,
    onError
  ) {
    subTaskUpdateForm.status = newStatus;

    subTaskUpdateForm.put(route('update-task', subTaskId), {
      headers: {
        'X-From': page,
      },
      preserveState: true,
      preserveScroll: true,
      onSuccess: (page) => {
        subTaskUpdateForm.reset();
        onFinish(page);
      },
      onError,
    });
  }

  function onFormSubmitUpdate(page, onFinish, onError) {
    preFormSubmit();
    form.put(route('update-task', id.value), {
      headers: {
        'X-From': page,
      },
      preserveState: true,
      preserveScroll: true,
      onSuccess: (page) => {
        onFinish(page);
        onClose();
      },
      onError,
    });
  }

  function onFormSubmitDelete(page, onFinish, onError) {
    form.delete(route('delete-task', id.value), {
      headers: {
        'X-From': page,
      },
      preserveState: true,
      preserveScroll: true,
      onSuccess: (page) => {
        onFinish(page);
        onClose();
      },
      onError,
    });
  }

  function getFormError(key) {
    return form.errors[key];
  }

  return {
    // display of modal
    open,
    openCreateTaskButton,
    parentTask,
    // form submission elements
    id,
    task_id,
    name,
    description,
    due_at,
    priority,
    status,
    assigned_to,
    projects,
    buckets,
    tasks,
    // global stores
    all_projects,
    all_buckets,
    all_users,
    all_tasks,
    // setters
    setTask,
    setProjects,
    setBuckets,
    setUsers,
    setTasks,
    // submit form
    onFormSubmitCreate,
    onFormSubmitUpdate,
    onFormSubmitDelete,
    onSubTaskFormSubmitCreate,
    onSubTaskFormSubmitUpdateStatus,
    getFormError,
    onOpen,
    onClose,
    reset,
  };
});
