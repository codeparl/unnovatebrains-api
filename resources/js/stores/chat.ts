import { defineStore } from 'pinia';
import { ref } from 'vue';
import axios from 'axios';
import type { Conversation, Message } from '../types';

export const useChatStore = defineStore('chat', () => {
  const conversations = ref<Conversation[]>([]);
  const activeConversation = ref<Conversation | null>(null);
  const messages = ref<Message[]>([]);
  const loadingConversations = ref(false);
  const loadingMessages = ref(false);

  // We use standard axios now; Laravel handles CSRF & Session automatically
  async function fetchConversations() {
    loadingConversations.value = true;
    try {
      const response = await axios.get<Conversation[]>('/api/v1/chat/conversations');
      conversations.value = response.data;
    } finally {
      loadingConversations.value = false;
    }
  }

  async function selectConversation(id: number) {
    loadingMessages.value = true;
    try {
      const active = conversations.value.find((c) => c.id === id);
      if (active) activeConversation.value = active;

      const response = await axios.get<Message[]>(`/api/v1/chat/conversation/${id}`);
      messages.value = response.data;
      
      if (activeConversation.value) {
        activeConversation.value.unread_count = 0;
      }
    } finally {
      loadingMessages.value = false;
    }
  }

  async function sendMessage(conversationId: number, messageText: string) {
    try {
      const response = await axios.post<Message>('/api/v1/chat/reply', {
        conversation_id: conversationId,
        message: messageText,
      });
      messages.value.push(response.data);
      updateLastMessage(conversationId, response.data.message);
    } catch (err) {
      console.error('Failed to send reply:', err);
    }
  }

  async function closeConversation(conversationId: number) {
    try {
      await axios.post(`/api/v1/chat/close/${conversationId}`);
      if (activeConversation.value?.id === conversationId) {
        activeConversation.value.status = 'closed';
      }
      const found = conversations.value.find((c) => c.id === conversationId);
      if (found) found.status = 'closed';
    } catch (err) {
      console.error('Failed to close conversation:', err);
    }
  }

  function updateLastMessage(conversationId: number, messageText: string) {
    const convo = conversations.value.find((c) => c.id === conversationId);
    if (convo) {
      convo.last_message_preview = messageText;
      convo.updated_at = new Date().toISOString();
    }
  }

  // Hook triggered when Laravel Echo receives a private message event
  function handleIncomingMessage(incoming: Message) {
    if (activeConversation.value && activeConversation.value.id === incoming.conversation_id) {
      messages.value.push(incoming);
    } else {
      const convo = conversations.value.find((c) => c.id === incoming.conversation_id);
      if (convo) {
        convo.unread_count++;
        convo.last_message_preview = incoming.message;
      }
    }
  }

  return {
    conversations,
    activeConversation,
    messages,
    loadingConversations,
    loadingMessages,
    fetchConversations,
    selectConversation,
    sendMessage,
    closeConversation,
    handleIncomingMessage,
  };
});