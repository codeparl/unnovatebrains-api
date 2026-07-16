<template>
  <div class="flex-1 flex flex-col h-full bg-slate-50 dark:bg-slate-900">
    <div v-if="chatStore.activeConversation" class="h-16 border-b border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950 px-6 flex items-center justify-between z-10 shrink-0">
      <div>
        <h2 class="font-bold text-md text-slate-900 dark:text-white flex items-center gap-2">
          {{ chatStore.activeConversation.visitor_identifier }}
          <span :class="['w-2 h-2 rounded-full inline-block', chatStore.activeConversation.status === 'open' ? 'bg-emerald-500' : 'bg-slate-400']"></span>
        </h2>
        <p class="text-xs text-slate-400">Status: {{ chatStore.activeConversation.status }}</p>
      </div>

      <button 
        v-if="chatStore.activeConversation.status === 'open'"
        @click="closeChat"
        class="text-xs bg-rose-50 hover:bg-rose-100 text-rose-600 px-3 py-1.5 rounded-lg border border-rose-200 dark:border-rose-950/20 dark:bg-rose-950/20 font-bold transition-all cursor-pointer"
      >
        Close Chat
      </button>
    </div>

    <div 
      ref="messageContainer"
      class="flex-1 overflow-y-auto p-6 space-y-4"
    >
      <div v-if="chatStore.loadingMessages" class="space-y-4">
        <div class="flex space-x-3 max-w-[70%]">
          <div class="h-9 w-9 rounded-full bg-slate-200 dark:bg-slate-850 animate-pulse"></div>
          <div class="h-12 bg-slate-200 dark:bg-slate-850 rounded-2xl w-48 animate-pulse"></div>
        </div>
      </div>

      <div v-else-if="chatStore.activeConversation && chatStore.messages.length > 0" class="space-y-4">
        <MessageBubble 
          v-for="msg in chatStore.messages" 
          :key="msg.id" 
          :message="msg" 
        />
      </div>

      <div v-else-if="chatStore.activeConversation" class="h-full flex items-center justify-center text-slate-400 flex-col">
        <p class="text-sm font-medium">This chat has no activity yet</p>
      </div>

      <div v-else class="h-full flex items-center justify-center text-slate-400 flex-col">
        <h3 class="font-semibold text-slate-900 dark:text-white">No Chat Selected</h3>
        <p class="text-xs text-slate-400 mt-1">Select a conversation to begin replying.</p>
      </div>
    </div>

    <div v-if="chatStore.activeConversation && chatStore.activeConversation.status === 'open'" class="p-4 bg-white dark:bg-slate-950 border-t border-slate-200 dark:border-slate-800 shrink-0">
      <form @submit.prevent="handleSend" class="flex gap-2">
        <input 
          v-model="newMessageText" 
          type="text" 
          placeholder="Write your response..." 
          class="flex-1 px-4 py-2.5 text-sm bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500"
        />
        <button 
          type="submit" 
          :disabled="!newMessageText.trim()"
          class="bg-indigo-600 hover:bg-indigo-700 disabled:opacity-50 text-white px-5 py-2.5 rounded-xl font-bold text-sm shadow-md cursor-pointer"
        >
          Send
        </button>
      </form>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, watch, nextTick, onUnmounted } from 'vue';
import { useChatStore } from '../stores/chat';
import MessageBubble from './MessageBubble.vue';

const chatStore = useChatStore();
const newMessageText = ref('');
const messageContainer = ref<HTMLElement | null>(null);

const handleSend = async () => {
  if (!newMessageText.value.trim() || !chatStore.activeConversation) return;
  const conversationId = chatStore.activeConversation.id;
  const messageContent = newMessageText.value;
  newMessageText.value = ''; // Clean field immediately
  await chatStore.sendMessage(conversationId, messageContent);
  scrollToBottom();
};

const closeChat = async () => {
  if (chatStore.activeConversation) {
    await chatStore.closeConversation(chatStore.activeConversation.id);
  }
};

const scrollToBottom = async () => {
  await nextTick();
  if (messageContainer.value) {
    messageContainer.value.scrollTop = messageContainer.value.scrollHeight;
  }
};

watch(() => chatStore.messages.length, () => {
  scrollToBottom();
});

// Watch Chat changes to bind/unbind real-time websocket channels dynamically
watch(() => chatStore.activeConversation?.id, (newId, oldId) => {
  scrollToBottom();
  if (oldId) unsubscribeEcho(oldId);
  if (newId) subscribeEcho(newId);
});

/* -------------------------------------------------------------
 * Laravel Reverb / Laravel Echo Real-time Integration Preparation
 * ------------------------------------------------------------- */
const subscribeEcho = (conversationId: number) => {
  // If window.Echo is imported globally (bootstrap.js), listen dynamically:
  //
  // window.Echo.private(`conversation.${conversationId}`)
  //   .listen('.MessageCreated', (e: { message: any }) => {
  //     chatStore.handleIncomingMessage(e.message);
  //     scrollToBottom();
  //   });
};

const unsubscribeEcho = (conversationId: number) => {
  // if (window.Echo) {
  //   window.Echo.leave(`conversation.${conversationId}`);
  // }
};

onUnmounted(() => {
  if (chatStore.activeConversation?.id) {
    unsubscribeEcho(chatStore.activeConversation.id);
  }
});
</script>