<template>
  <div :class="['flex items-end gap-2.5', isAgent ? 'flex-row-reverse' : '']">
    <div 
      :class="['w-7 h-7 rounded-full text-[10px] font-bold flex items-center justify-center shrink-0 border', 
        isAgent ? 'bg-indigo-600 border-indigo-500 text-white' : 'bg-slate-200 border-slate-300 dark:bg-slate-800 dark:border-slate-700 text-slate-700 dark:text-slate-200']"
    >
      {{ isAgent ? 'A' : 'V' }}
    </div>

    <div :class="['flex flex-col max-w-[70%]', isAgent ? 'items-end' : 'items-start']">
      <div 
        :class="['px-4 py-2.5 rounded-2xl text-sm leading-relaxed shadow-sm', 
          isAgent ? 'bg-indigo-600 text-white rounded-br-none' : 'bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 rounded-bl-none border border-slate-200/60 dark:border-slate-700/60']"
      >
        <p>{{ message.message }}</p>
      </div>
      <span class="text-[10px] text-slate-400 mt-1 px-1">
        {{ formatTime(message.created_at) }}
      </span>
    </div>
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue';
import type { Message } from '../types';

const props = defineProps<{
  message: Message;
}>();

const isAgent = computed(() => props.message.sender_type === 'agent');

const formatTime = (timeStr: string) => {
  return new Date(timeStr).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
};
</script>