<template>
  <aside class="w-64 bg-slate-950 text-slate-400 flex flex-col justify-between h-full border-r border-slate-800 shrink-0">
    <div class="py-4">
      <div class="px-6 pb-6 flex items-center gap-3 border-b border-slate-900">
      <ApplicationLogo />
      </div>

      <nav class="mt-6 px-4 space-y-1">
        <Link href="/dashboard" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-white bg-slate-900 font-medium text-sm transition-all">
          <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" /></svg>
          Active Chats
        </Link>
      </nav>
    </div>

    <div class="p-4 border-t border-slate-900 flex items-center justify-between">
      <span class="text-xs">v1.2.0-stable</span>
      <span class="h-2 w-2 rounded-full bg-emerald-500 shadow-[0_0_8px_rgba(16,185,129,0.6)]"></span>
    </div>
  </aside>
</template>

<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import ApplicationLogo from './ApplicationLogo.vue';
</script>