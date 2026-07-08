<?php

namespace App\Chat\Controllers;

use App\Chat\Services\ChatService;

class ChatController
{
    private ChatService $chatService;

    public function __construct()
    {
        $this->chatService = new ChatService();
    }

    public function start()
    {
        echo json_encode($this->chatService->start());
    }

    public function send()
    {
        $data = json_decode(
            file_get_contents('php://input'),
            true
        );

        echo json_encode($this->chatService->send($data));
    }

    public function history($id)
    {
        echo json_encode($this->chatService->history((int)$id));
    }

    public function conversations()
    {
        echo json_encode($this->chatService->conversations());
    }

    public function show($id)
    {
        $result = $this->chatService->show((int)$id);

        if (!empty($result['not_found'])) {
            http_response_code(404);
            echo json_encode(['message' => $result['message']]);
            return;
        }

        echo json_encode([
            'conversation' => $result['conversation'],
            'messages' => $result['messages'],
        ]);
    }

    public function reply()
    {
        $data = json_decode(
            file_get_contents('php://input'),
            true
        );

        echo json_encode($this->chatService->reply($data));
    }

    public function close($id)
    {
        $result = $this->chatService->close((int)$id);

        if (!empty($result['not_found'])) {
            http_response_code(404);
            echo json_encode(['message' => $result['message']]);
            return;
        }

        echo json_encode(['status' => $result['status']]);
    }
}

