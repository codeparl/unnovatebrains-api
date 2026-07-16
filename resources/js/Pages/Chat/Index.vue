<template>
  <AdminLayout>
    <div class="flex h-full overflow-hidden bg-slate-50 dark:bg-slate-900">
      <ConversationList />
      <ChatWindow />
    </div>
  </AdminLayout>
</template>

<script setup lang="ts">
import { onMounted } from 'vue';
import { useChatStore } from '../../stores/chat';
import AdminLayout from '../../Layouts/AdminLayout.vue';
import ConversationList from '../../Components/ConversationList.vue';
import ChatWindow from '../../Components/ChatWindow.vue';

const chatStore = useChatStore();

onMounted(() => {
  chatStore.fetchConversations();
});
</script>