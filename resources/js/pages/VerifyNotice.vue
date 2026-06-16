<script setup>
import { ref } from 'vue';
import { Head, useForm, usePage } from '@inertiajs/vue3';
import AuthLayout from '../layouts/AuthLayout.vue';

let page = usePage();

let form = useForm({
  email: '',
  password: '',
  remember: false,
});

let isSendingVerificationEmail = ref(false);

function resendVerificationEmail() {
  form.post(route('verification.send'), {
    onFinish: () => {
      isSendingVerificationEmail.value = true;
    },
  });
};
</script>

<template>
  <Head :title="page.props.app_name + ' | Verify Your Email'" />
  <AuthLayout>
    <div class="flex flex-col gap-6">
      <div v-if="page.props.message" class="grid gap-6">
        <div class="grid gap-2">
          <p class="text-sm text-red-600 dark:text-red-500">
            {{ page.props.message }}
          </p>
        </div>
      </div>
      <div class="grid gap-6">
        <div class="space-y-6">
          <div class="space-y-2">
            <p class="text-center text-sm text-muted-foreground">
              Please click the link in the email we sent to verify your email address to log in. You may need to check your spam folder.
            </p>
            <p v-if="!isSendingVerificationEmail" class="text-center text-sm text-muted-foreground">
              If you need to resend the verification email, please click the button below.
            </p>
          </div>
          <div v-if="!isSendingVerificationEmail" class="flex justify-center">
            <button type="button" @click="resendVerificationEmail" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-xs hover:bg-indigo-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
              Resend Verification Email
            </button>
          </div>
        </div>
      </div>
    </div>
  </AuthLayout>
</template>
