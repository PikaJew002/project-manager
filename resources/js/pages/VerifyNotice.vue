<script setup>
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import AuthLayout from '../layouts/AuthLayout.vue';

let page = usePage();

let form = useForm({
  email: '',
  password: '',
  remember: false,
});

function submit() {
  form.post(route('login'), {
    onFinish: () => form.reset('password'),
  });
};
</script>

<template>
  <Head title="Login" />
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
        <div>
          <p>
            Please click the link in the email we sent to verify your email address to log in. You may need to check your spam folder.
          </p>
          <p>
            If you need to resend the verification email, please click the button below.
          </p>
          <button type="button" @click="resendVerificationEmail" class="bg-blue-500 text-white px-4 py-2 rounded-md">
            Resend Verification Email
          </button>
        </div>
      </div>
    </div>
  </AuthLayout>
</template>
