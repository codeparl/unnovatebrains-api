<?php

namespace App\Services\Chat;

use App\Models\Visitor;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Support\Facades\DB;

class ChatService
{
    /**
     * Start a new chat conversation.
     */
    public function start(): array
    {
        return DB::transaction(function () {

            $visitorId = uniqid('visitor_', true);

            $visitor = Visitor::create([
                'visitor_id' => $visitorId,
            ]);

            $conversation = Conversation::create([
                'visitor_id' => $visitor->id,
                'status' => 'open',
            ]);

            return [
                'conversation_id' => $conversation->id,
                'visitor_id' => $visitorId,
            ];
        });
    }


    /**
     * Send visitor message.
     */
    public function send(array $data): array
    {
        $message = Message::create([
            'conversation_id' => $data['conversation_id'],
            'sender' => $data['sender'] ?? 'visitor',
            'message' => $data['message'],
        ]);

        return [
            'status' => 'sent',
            'id' => $message->id,
        ];
    }


    /**
     * Get conversation messages.
     */
    public function history(int $conversationId)
    {
        return Message::where('conversation_id', $conversationId)
            ->orderBy('created_at')
            ->get();
    }


    /**
     * Get active conversations.
     */
    public function conversations()
    {
        return Conversation::where('status', 'open')
            ->latest()
            ->get();
    }


    /**
     * Show conversation with messages.
     */
    public function show(int $conversationId): array
    {
        $conversation = Conversation::find($conversationId);

        if (!$conversation) {
            return [
                'not_found' => true,
                'message' => 'Conversation not found',
            ];
        }

        return [
            'not_found' => false,
            'conversation' => $conversation,
            'messages' => Message::where(
                'conversation_id',
                $conversationId
            )
            ->orderBy('created_at')
            ->get(),
        ];
    }


    /**
     * Send agent reply.
     */
    public function reply(array $data): array
    {
        $message = Message::create([
            'conversation_id' => $data['conversation_id'],
            'sender' => 'agent',
            'message' => $data['message'],
        ]);

        return [
            'status' => 'reply sent',
            'id' => $message->id,
        ];
    }


    /**
     * Close conversation.
     */
    public function close(int $conversationId): array
    {
        $conversation = Conversation::find($conversationId);

        if (!$conversation) {
            return [
                'not_found' => true,
                'message' => 'Conversation not found',
            ];
        }

        $conversation->update([
            'status' => 'closed',
        ]);

        return [
            'not_found' => false,
            'status' => 'conversation closed',
        ];
    }
}