<?php


require_once __DIR__.'/../app/Database/Database.php';


use Bramus\Router\Router;
use App\Chat\Controllers\ChatController;
use App\Auth\Controllers\AuthController;
use App\Auth\Middleware\AuthMiddleware;

$router = new Router();

$chat = new ChatController();
$auth = new AuthController();
$authMiddleware = new AuthMiddleware();

$router->post(
'/api/chat/start',
[$chat,'start']
);




$router->post(
'/api/chat/send',
[$chat,'send']
);



$router->get(
'/api/chat/history/(\d+)',
[$chat,'history']
);

$router->get(
'/api/chat/conversations',
function() use ($chat, $authMiddleware) {
    $auth = $authMiddleware->authenticate();
    if (!$auth['ok']) {
        http_response_code(401);
        echo json_encode(['message' => 'Unauthenticated']);
        return;
    }
    $chat->conversations();
}
);


$router->get(
'/api/chat/conversation/(\d+)',
function($id) use ($chat, $authMiddleware) {
    $auth = $authMiddleware->authenticate();
    if (!$auth['ok']) {
        http_response_code(401);
        echo json_encode(['message' => 'Unauthenticated']);
        return;
    }
    $chat->show($id);
}
);


$router->post(
'/api/chat/reply',
function() use ($chat, $authMiddleware) {
    $auth = $authMiddleware->authenticate();
    if (!$auth['ok']) {
        http_response_code(401);
        echo json_encode(['message' => 'Unauthenticated']);
        return;
    }
    $chat->reply();
}
);

$router->post(
'/api/chat/close/(\d+)',
function($id) use ($chat, $authMiddleware) {
    $auth = $authMiddleware->authenticate();
    if (!$auth['ok']) {
        http_response_code(401);
        echo json_encode(['message' => 'Unauthenticated']);
        return;
    }
    $chat->close($id);
}
);

$router->post(
'/api/auth/login',
[$auth,'login']
);

$router->post(
'/api/auth/logout',
[$auth,'logout']
);

$router->get(
'/api/auth/me',
function() use ($auth, $authMiddleware) {
    $authMiddlewareResult = $authMiddleware->authenticate();
    if (!$authMiddlewareResult['ok']) {
        http_response_code(401);
        echo json_encode(['message' => 'Unauthenticated']);
        return;
    }
    // keep controller signature-free (controller also has me())
    echo json_encode($authMiddlewareResult['user']);
}
);

