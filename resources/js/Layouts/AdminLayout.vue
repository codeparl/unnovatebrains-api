<template>
  <div class="flex h-screen bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-slate-100 overflow-hidden">
    <Sidebar />

    <div class="flex flex-col flex-1 overflow-hidden">
      <header class="h-16 border-b border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950 flex items-center justify-between px-6 z-10">
        <div>
          <h1 class="font-semibold text-lg">Unnovate Brains Inbox</h1>
        </div>
        <div class="flex items-center gap-4">
          <div class="flex items-center gap-3">
            <div class="w-8 h-8 rounded-full bg-indigo-600 text-white flex items-center justify-center font-bold text-sm">
              {{ $page.props.auth.user.name.charAt(0) }}
            </div>
            <div class="hidden md:block">
              <p class="text-xs font-medium text-slate-400">Agent</p>
              <p class="text-sm font-semibold leading-none">{{ $page.props.auth.user.name }}</p>
            </div>
          </div>
          <Link 
            href="/logout" 
            method="post" 
            as="button"
            class="text-sm px-3 py-1.5 border border-slate-200 dark:border-slate-800 rounded-md hover:bg-slate-100 dark:hover:bg-slate-900 transition-all font-medium text-rose-500 cursor-pointer"
          >
            Logout
          </Link>
        </div>
      </header>

      <main class="flex-1 overflow-hidden">
        <slot />
      </main>
    </div>
  </div>
</template>

<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import Sidebar from '../Components/Sidebar.vue';
</script>