import { reactive, ref } from 'vue';
import { defineStore } from 'pinia';

export const usePagesStore = defineStore('pages-store', () => {
  let project = reactive({
    id: null,
    name: '',
    buckets: [],
    users: [],
    tasks: [],
  });

  // personal tasks view, grid view on project view
  let tasks = ref([]);

  // your tasks board
  let projects = ref([]);
  let project_tasks = ref([]);

  function setTasks(initialTasks) {
    tasks.value = initialTasks;
  }

  function setProject(initialProject) {
    project.id = initialProject.id;
    project.name = initialProject.name;
    project.tasks = initialProject?.tasks;
    project.buckets = initialProject?.buckets;
    project.users = initialProject.users;
  }

  function setProjects(initialProjects) {
    projects.value = initialProjects;
  }

  function setProjectTasks(initialProjectTasks) {
    project_tasks.value = initialProjectTasks;
  }

  function addProjectBucket(bucket) {
    project.buckets.push({ ...bucket, tasks: [] });
  }

  function addProjectTask(task) {
    project.tasks.push({ ...task });
  }

  function addBucketTask(task) {
    let bucketIndex = project.buckets.findIndex(
      (b) =>
        b.id === task.buckets.find((tb) => tb.project_id === project.id)?.id
    );
    project.buckets[bucketIndex].tasks.push({ ...task });
  }

  function deleteProjectTask(taskId) {
    project.tasks = project.tasks.filter((t) => t.id !== taskId);
    project.buckets.map((b) => {
      b.tasks = b.tasks.filter((t) => t.id !== taskId);
      return b;
    });
  }

  function updateProjectTask(task, bucketIds, projectIds) {
    let oldBucketIds = new Set(bucketIds);
    let oldProjectIds = new Set(projectIds);
    let newBucketIds = new Set(task.buckets.map((b) => b.id));
    let newProjectIds = new Set(task.projects.map((p) => p.id));

    let bucketsToRemove = oldBucketIds.difference(newBucketIds);
    let bucketsToAdd = newBucketIds.difference(oldBucketIds);

    let projectsToRemove = oldProjectIds.difference(newProjectIds);
    let projectsToAdd = newProjectIds.difference(oldBucketIds);

    bucketsToRemove.forEach((bucketId) => {
      let bucketIndex = project.buckets.findIndex((b) => b.id === bucketId);

      if (bucketIndex > -1) {
        project.buckets[bucketIndex].tasks = project.buckets[
          bucketIndex
        ].tasks.filter((t) => t.id !== task.id);
      } else {
        // removed from a bucket in another project. Maybe show a toast message or something?
      }
    });

    bucketsToAdd.forEach((bucketId) => {
      let bucketIndex = project.buckets.findIndex((b) => b.id === bucketId);

      if (bucketIndex > -1) {
        project.buckets[bucketIndex].tasks.push(task);
      } else {
        // added to a bucket in another project. Maybe show a toast message or something?
      }
    });

    projectsToRemove.forEach((projectId) => {
      if (projectId === project.id) {
        project.tasks = project.tasks.filter((t) => t.id !== task.id);
      } else {
        // removed from another project's tasks (no bucket). Maybe show a toast message or something?
      }
    });

    projectsToAdd.forEach((projectId) => {
      if (projectId === project.id) {
        project.tasks.push(task);
      } else {
        // added to another project's tasks (no bucket). Maybe show a toast message or something?
      }
    });
  }

  function storeTask(task) {
    tasks.value.push({
      ...task,
    });
  }

  function updatePersonalTask(task) {
    // if not assigned to you, remove it from all_tasks, otherwise update it
    tasks.value = task.assigned_to.reduce(
      (carry, user) => carry || user.is_me,
      false
    )
      ? tasks.value.map((t) => (t.id === task.id ? { ...task } : t))
      : tasks.value.filter((t) => t.id !== task.id);
  }

  function deleteTask(id) {
    tasks.value = tasks.value.filter((t) => t.id !== id);
  }

  function getBucketTasks(bucket_id) {
    return tasks.value.filter(
      (t) => t.buckets.findIndex((b) => b.id === bucket_id) > -1
    );
  }

  function findTaskInProjectBoard(taskId) {
    for (const i of project.tasks) {
      let foundTask = findTask(i, taskId);
      if (foundTask) {
        return foundTask;
      }
    }
    for (const i of project.buckets) {
      for (const j of i.tasks) {
        let foundTask = findTask(j, taskId);
        if (foundTask) {
          return foundTask;
        }
      }
    }
    return undefined;
  }

  function findTaskInDashboardBoard(taskId) {
    for (const i of project_tasks.value) {
      let foundTask = findTask(i, taskId);
      if (foundTask) {
        return foundTask;
      }
    }
    for (const i of projects.value) {
      for (const j of i.tasks) {
        let foundTask = findTask(j, taskId);
        if (foundTask) {
          return foundTask;
        }
      }
      for (const j of i.buckets) {
        for (const k of j.tasks) {
          let foundTask = findTask(k, taskId);
          if (foundTask) {
            return foundTask;
          }
        }
      }
    }
    return undefined;
  }

  function findTaskInGrid(taskId) {
    for (const i of tasks.value) {
      let foundTask = findTask(i, taskId);
      if (foundTask) {
        return foundTask;
      }
    }
    return undefined;
  }

  function findTaskInGrid(taskId) {
    for (const i of tasks.value) {
      let foundTask = findTask(i, taskId);
      if (foundTask) {
        return foundTask;
      }
    }
    return undefined;
  }

  function findTask(task, taskId) {
    if (task.id === taskId) {
      return task;
    }
    if (task.tasks) {
      for (const i of task.tasks) {
        let foundTask = findTask(i, taskId);
        if (foundTask) {
          return foundTask;
        }
      }
    }
    return undefined;
  }

  function getTask(task_id) {
    return tasks.value.find((t) => t.id === task_id);
  }

  return {
    tasks,
    project,
    projects,
    project_tasks,
    setTasks,
    setProject,
    setProjects,
    setProjectTasks,
    addProjectBucket,
    addProjectTask,
    addBucketTask,
    storeTask,
    updatePersonalTask,
    deleteProjectTask,
    updateProjectTask,
    deleteTask,
    getBucketTasks,
    getTask,
    findTaskInProjectBoard,
    findTaskInDashboardBoard,
    findTaskInGrid,
  };
});
