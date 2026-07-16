export interface User {
    id: number;
    name: string;
    email: string;
    email_verified_at?: string;
    role: 'agent' | 'admin';
}

export type PageProps<
    T extends Record<string, unknown> = Record<string, unknown>,
> = T & {
    auth: {
        user: User;
    };
};



export interface Conversation {
  id: number;
  visitor_identifier: string;
  last_message_preview?: string;
  status: 'open' | 'closed';
  unread_count: number;
  created_at: string;
  updated_at: string;
}

export interface Message {
  id: number;
  conversation_id: number;
  sender_type: 'visitor' | 'agent';
  sender_id?: number;
  message: string;
  created_at: string;
}
