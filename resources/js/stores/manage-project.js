import { ref } from 'vue';
import { defineStore } from 'pinia';

export const useManageProjectStore = defineStore('manage-project-modal-store', () => {
  let open = ref(false);
  let name = ref('');
  let project_users = ref([]);
  let administered_by = ref(null);
  let administeredBy = ref(null);

  function setProject(initialProject) {
    name.value = initialProject.name;
    project_users.value = initialProject.users;
    administered_by.value = initialProject.administered_by;
    administeredBy.value = initialProject.administeredBy;
  }

  function onClose() {
    name.value = '';
    open.value = false;
  }

  function onOpen(initialProject) {
    setProject(initialProject);
    open.value = true;
  }

  return {
    open,
    name,
    project_users,
    administered_by,
    administeredBy,
    setProject,
    onOpen,
    onClose,
  };
});
