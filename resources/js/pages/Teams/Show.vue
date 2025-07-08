<template>
  <AppLayout :title="team.name">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ team.name }}
      </h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
          <div class="p-6">
            <!-- Team Information -->
            <div class="mb-8">
              <h3 class="text-lg font-medium text-gray-900 mb-4">Team Information</h3>
              <form @submit.prevent="updateTeam">
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                  <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">
                      Team Name
                    </label>
                    <input
                      id="name"
                      v-model="teamForm.name"
                      type="text"
                      class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                      required
                    />
                  </div>
                  <div class="flex items-end">
                    <button
                      type="submit"
                      :disabled="teamForm.processing"
                      class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring focus:ring-indigo-300 disabled:opacity-25 transition"
                    >
                      Update Team
                    </button>
                  </div>
                </div>
              </form>
            </div>

            <!-- Team Members -->
            <div class="mb-8">
              <h3 class="text-lg font-medium text-gray-900 mb-4">Team Members</h3>
              <div class="space-y-4">
                <div v-for="user in team.users" :key="user.id" class="flex items-center justify-between p-4 border rounded-lg">
                  <div class="flex items-center space-x-3">
                    <div class="flex-shrink-0">
                      <div class="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center">
                        <span class="text-sm font-medium text-gray-700">{{ user.initials }}</span>
                      </div>
                    </div>
                    <div>
                      <p class="text-sm font-medium text-gray-900">{{ user.name }}</p>
                      <p class="text-sm text-gray-500">{{ user.email }}</p>
                    </div>
                  </div>
                  <div class="flex items-center space-x-2">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                      {{ user.membership?.role || 'Owner' }}
                    </span>
                  </div>
                </div>
              </div>
            </div>

            <!-- Invite New Member -->
            <div class="mb-8">
              <h3 class="text-lg font-medium text-gray-900 mb-4">Invite New Member</h3>
              <form @submit.prevent="inviteMember">
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                  <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">
                      Email Address
                    </label>
                    <input
                      id="email"
                      v-model="inviteForm.email"
                      type="email"
                      class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                      required
                    />
                  </div>
                  <div>
                    <label for="role" class="block text-sm font-medium text-gray-700">
                      Role
                    </label>
                    <select
                      id="role"
                      v-model="inviteForm.role"
                      class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                    >
                      <option value="member">Member</option>
                      <option value="admin">Administrator</option>
                    </select>
                  </div>
                  <div class="flex items-end">
                    <button
                      type="submit"
                      :disabled="inviteForm.processing"
                      class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-900 focus:outline-none focus:border-green-900 focus:ring focus:ring-green-300 disabled:opacity-25 transition"
                    >
                      Send Invitation
                    </button>
                  </div>
                </div>
              </form>
            </div>

            <!-- Pending Invitations -->
            <div v-if="team.invites && team.invites.length > 0" class="mb-8">
              <h3 class="text-lg font-medium text-gray-900 mb-4">Pending Invitations</h3>
              <div class="space-y-4">
                <div v-for="invite in team.invites" :key="invite.id" class="flex items-center justify-between p-4 border rounded-lg">
                  <div class="flex items-center space-x-3">
                    <div class="flex-shrink-0">
                      <div class="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center">
                        <span class="text-sm font-medium text-gray-700">{{ invite.email.charAt(0).toUpperCase() }}</span>
                      </div>
                    </div>
                    <div>
                      <p class="text-sm font-medium text-gray-900">{{ invite.email }}</p>
                      <p class="text-sm text-gray-500">Invited by {{ invite.invitedBy?.name }}</p>
                    </div>
                  </div>
                  <div class="flex items-center space-x-2">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                      {{ invite.role }}
                    </span>
                    <button
                      @click="cancelInvitation(invite.id)"
                      class="text-red-600 hover:text-red-900 text-sm font-medium"
                    >
                      Cancel
                    </button>
                  </div>
                </div>
              </div>
            </div>

            <!-- Danger Zone -->
            <div class="border-t pt-8">
              <h3 class="text-lg font-medium text-red-900 mb-4">Danger Zone</h3>
              <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                <div class="flex items-center justify-between">
                  <div>
                    <h4 class="text-sm font-medium text-red-900">Delete Team</h4>
                    <p class="text-sm text-red-700 mt-1">
                      Once you delete a team, there is no going back. Please be certain.
                    </p>
                  </div>
                  <button
                    @click="deleteTeam"
                    class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-900 focus:outline-none focus:border-red-900 focus:ring focus:ring-red-300 transition"
                  >
                    Delete Team
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'

const props = defineProps({
  team: Object,
})

let teamForm = useForm({
  name: props.team.name,
})

let inviteForm = useForm({
  email: '',
  role: 'member',
})

function updateTeam() {
  teamForm.put(route('teams.update', props.team.id))
}

function inviteMember() {
  inviteForm.post(route('team-members.store', props.team.id), {
    onSuccess: () => {
      inviteForm.reset()
    },
  })
}

function cancelInvitation(invitationId) {
  if (confirm('Are you sure you want to cancel this invitation?')) {
    useForm().delete(route('team-invitations.destroy', invitationId))
  }
}

function deleteTeam() {
  if (confirm('Are you sure you want to delete this team? This action cannot be undone.')) {
    useForm().delete(route('teams.destroy', props.team.id))
  }
}
</script>
