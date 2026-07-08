<?php

namespace App\Chat\Services;

use App\Chat\Models\Visitor;
use App\Chat\Models\Conversation;
use App\Chat\Models\Message;

class ChatService
{
    public function start(): array
    {
        $visitorId = uniqid();

        $visitor = Visitor::create([
            'visitor_id' => $visitorId,
        ]);

        $conversation = Conversation::create([
            'visitor_id' => $visitor->id,
        ]);

        return [
            'conversation_id' => $conversation->id,
            'visitor_id' => $visitorId,
        ];
    }

    public function send(array $data): array
    {
        $message = Message::create([
            'conversation_id' => $data['conversation_id'],
            'sender' => $data['sender'],
            'message' => $data['message'],
        ]);

        return [
            'status' => 'sent',
            'id' => $message->id,
        ];
    }

    public function history(int $conversationId): array
    {
        return Message::where('conversation_id', $conversationId)->get()->toArray();
    }

    public function conversations(): array
    {
        return Conversation::where('status', 'open')
            ->orderBy('created_at', 'desc')
            ->get()->toArray();
    }

    public function show(int $conversationId)
    {
        $conversation = Conversation::find($conversationId);

        if (!$conversation) {
            return [
                'not_found' => true,
                'message' => 'Conversation not found',
            ];
        }

        $messages = Message::where('conversation_id', $conversationId)
            ->orderBy('created_at', 'asc')
            ->get();

        return [
            'not_found' => false,
            'conversation' => $conversation,
            'messages' => $messages,
        ];
    }

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

    public function close(int $conversationId): array
    {
        $conversation = Conversation::find($conversationId);

        if (!$conversation) {
            return [
                'not_found' => true,
                'message' => 'Conversation not found',
            ];
        }

        $conversation->status = 'closed';
        $conversation->save();

        return [
            'not_found' => false,
            'status' => 'conversation closed',
        ];
    }
}

