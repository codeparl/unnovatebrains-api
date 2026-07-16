<template>
  <div class="w-96 border-r border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950 flex flex-col h-full shrink-0">
    <div class="p-4 border-b border-slate-200 dark:border-slate-800 flex flex-col gap-3">
      <input 
        v-model="searchQuery" 
        type="text" 
        placeholder="Search conversations..." 
        class="w-full px-3.5 py-2 text-sm bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500"
      />
      
      <div class="flex bg-slate-100 dark:bg-slate-900 p-1 rounded-lg">
        <button 
          @click="statusFilter = 'open'"
          :class="['flex-1 text-center py-1.5 text-xs font-semibold rounded-md transition-all', statusFilter === 'open' ? 'bg-white dark:bg-slate-800 shadow-sm text-slate-900 dark:text-white' : 'text-slate-400 hover:text-slate-600']"
        >
          Open
        </button>
        <button 
          @click="statusFilter = 'closed'"
          :class="['flex-1 text-center py-1.5 text-xs font-semibold rounded-md transition-all', statusFilter === 'closed' ? 'bg-white dark:bg-slate-800 shadow-sm text-slate-900 dark:text-white' : 'text-slate-400 hover:text-slate-600']"
        >
          Closed
        </button>
      </div>
    </div>

    <div class="flex-1 overflow-y-auto divide-y divide-slate-100 dark:divide-slate-900">
      <div v-if="chatStore.loadingConversations" class="p-4 space-y-4">
        <div v-for="i in 4" :key="i" class="animate-pulse flex items-center space-x-3">
          <div class="rounded-full bg-slate-200 dark:bg-slate-800 h-10 w-10"></div>
          <div class="flex-1 space-y-2 py-1">
            <div class="h-3 bg-slate-200 dark:bg-slate-800 rounded w-3/4"></div>
            <div class="h-2 bg-slate-200 dark:bg-slate-800 rounded w-5/6"></div>
          </div>
        </div>
      </div>

      <div v-else-if="filteredConversations.length === 0" class="p-8 text-center text-slate-400">
        <p class="text-sm font-medium">No conversations found</p>
      </div>

      <div 
        v-else 
        v-for="convo in filteredConversations" 
        :key="convo.id"
        @click="chatStore.selectConversation(convo.id)"
        :class="['p-4 flex items-start gap-3 cursor-pointer hover:bg-slate-50 dark:hover:bg-slate-900 transition-all border-l-4', chatStore.activeConversation?.id === convo.id ? 'border-indigo-500 bg-indigo-50/10 dark:bg-indigo-950/10' : 'border-transparent']"
      >
        <div class="w-10 h-10 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center border border-slate-200 dark:border-slate-800 shrink-0 font-bold text-sm">
          {{ convo.visitor_identifier.substring(0, 2).toUpperCase() }}
        </div>
        <div class="flex-1 min-w-0">
          <div class="flex items-center justify-between mb-1">
            <h4 class="font-semibold text-sm truncate text-slate-900 dark:text-white">
              {{ convo.visitor_identifier }}
            </h4>
            <span class="text-[10px] text-slate-400">{{ formatTime(convo.updated_at) }}</span>
          </div>
          <p class="text-xs text-slate-400 truncate pr-2">
            {{ convo.last_message_preview || 'No messages yet' }}
          </p>
        </div>
        <div class="flex flex-col items-end shrink-0 gap-1.5">
          <span 
            v-if="convo.unread_count > 0" 
            class="bg-indigo-600 text-white text-[10px] font-bold h-5 w-5 rounded-full flex items-center justify-center"
          >
            {{ convo.unread_count }}
          </span>
          <span 
            :class="['text-[10px] uppercase font-bold tracking-wider px-1.5 py-0.5 rounded-full', convo.status === 'open' ? 'bg-emerald-100 dark:bg-emerald-950/50 text-emerald-600' : 'bg-slate-100 dark:bg-slate-800 text-slate-500']"
          >
            {{ convo.status }}
          </span>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue';
import { useChatStore } from '../stores/chat';

const chatStore = useChatStore();
const searchQuery = ref('');
const statusFilter = ref<'open' | 'closed'>('open');

const filteredConversations = computed(() => {
  return chatStore.conversations.filter((convo) => {
    const matchesSearch = convo.visitor_identifier.toLowerCase().includes(searchQuery.value.toLowerCase());
    const matchesStatus = convo.status === statusFilter.value;
    return matchesSearch && matchesStatus;
  });
});

const formatTime = (timeStr: string) => {
  return new Date(timeStr).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
};
</script>