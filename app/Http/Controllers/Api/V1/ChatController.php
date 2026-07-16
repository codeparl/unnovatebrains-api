<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\Chat\ChatService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function __construct(
        protected ChatService $chatService
    ) {
    }

    public function start(): JsonResponse
    {
        return response()->json(
            $this->chatService->start()
        );
    }

    public function send(Request $request): JsonResponse
    {
        $data = $request->validate([
            'conversation_id' => ['required', 'integer'],
            'message' => ['required', 'string'],
        ]);

        return response()->json(
            $this->chatService->send($data)
        );
    }

    public function history(int $id): JsonResponse
    {
        return response()->json(
            $this->chatService->history($id)
        );
    }

    public function conversations(): JsonResponse
    {
        return response()->json(
            $this->chatService->conversations()
        );
    }

    public function show(int $id): JsonResponse
    {
        $result = $this->chatService->show($id);

        if (!empty($result['not_found'])) {
            return response()->json([
                'message' => $result['message'],
            ], 404);
        }

        return response()->json([
            'conversation' => $result['conversation'],
            'messages' => $result['messages'],
        ]);
    }

    public function reply(Request $request): JsonResponse
    {
        $data = $request->validate([
            'conversation_id' => ['required', 'integer'],
            'message' => ['required', 'string'],
        ]);

        return response()->json(
            $this->chatService->reply($data)
        );
    }

    public function close(int $id): JsonResponse
    {
        $result = $this->chatService->close($id);

        if (!empty($result['not_found'])) {
            return response()->json([
                'message' => $result['message'],
            ], 404);
        }

        return response()->json([
            'status' => $result['status'],
        ]);
    }
}