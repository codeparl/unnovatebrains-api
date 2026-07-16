<?php

namespace Database\Seeders;

use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Database\Seeder;

class MessageSeeder extends Seeder
{
    public function run(): void
    {
        $conversations = Conversation::all();

        foreach ($conversations as $conversation) {

            Message::create([
                'conversation_id' => $conversation->id,
                'sender_type' => 'visitor',
                'message' => 'Hello, I need help with your services.',
            ]);

            Message::create([
                'conversation_id' => $conversation->id,
                'sender_type' => 'agent',
                'sender_id' => 1,
                'message' => 'Hello! Welcome to Unnovate Brains. How can I assist you today?',
            ]);

            Message::create([
                'conversation_id' => $conversation->id,
                'sender_type' => 'visitor',
                'message' => 'I would like to know more about SchoolPalm.',
            ]);

            Message::create([
                'conversation_id' => $conversation->id,
                'sender_type' => 'agent',
                'sender_id' => 1,
                'message' => 'SchoolPalm is our cloud-based school management platform. Which features are you interested in?',
            ]);

            Message::create([
                'conversation_id' => $conversation->id,
                'sender_type' => 'visitor',
                'message' => 'Admissions and online fee payments.',
            ]);
        }
    }
}