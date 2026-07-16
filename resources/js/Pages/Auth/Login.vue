<template>
  <div class="min-h-screen flex items-center justify-center bg-slate-50 dark:bg-slate-900 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 bg-white dark:bg-slate-950 p-8 rounded-2xl border border-slate-100 dark:border-slate-800 shadow-xl">
      <div class="text-center">
        <div class="mx-auto h-12 w-12 rounded-xl bg-indigo-600 text-white flex items-center justify-center text-3xl font-extrabold shadow-lg shadow-indigo-500/30">
          U
        </div>
        <h2 class="mt-6 text-2xl font-extrabold text-slate-900 dark:text-white">
          Agent Portal
        </h2>
        <p class="mt-2 text-sm text-slate-400">
          Sign in to access Unnovate Brains Chat
        </p>
      </div>

      <form class="mt-8 space-y-6" @submit.prevent="submit">
        <div v-if="form.errors.email" class="bg-rose-50 dark:bg-rose-950/20 text-rose-600 p-3.5 rounded-lg border border-rose-100 dark:border-rose-950/50 text-xs font-medium">
          {{ form.errors.email }}
        </div>

        <div class="space-y-4">
          <div>
            <label for="email" class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-1.5">Email address</label>
            <input 
              v-model="form.email" 
              id="email" 
              type="email" 
              required 
              class="appearance-none rounded-lg relative block w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 placeholder-slate-400 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500 sm:text-sm" 
              placeholder="agent@unnovate.test" 
            />
          </div>
          <div>
            <label for="password" class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-1.5">Password</label>
            <input 
              v-model="form.password" 
              id="password" 
              type="password" 
              required 
              class="appearance-none rounded-lg relative block w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 placeholder-slate-400 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500 sm:text-sm" 
              placeholder="••••••••" 
            />
          </div>
        </div>

        <div>
          <button 
            type="submit" 
            :disabled="form.processing"
            class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-bold rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all cursor-pointer disabled:opacity-50"
          >
            <span v-if="form.processing" class="animate-spin mr-2 h-5 w-5 text-white inline-block border-2 border-white border-t-transparent rounded-full"></span>
            {{ form.processing ? 'Signing in...' : 'Sign In' }}
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';

const form = useForm({
  email: '',
  password: '',
});

const submit = () => {
  form.post('/login', {
    onFinish: () => form.reset('password'),
  });
};
</script>