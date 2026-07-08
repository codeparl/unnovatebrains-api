<?php


require_once __DIR__.'/../app/Database/Database.php';


use Bramus\Router\Router;
use App\Chat\Controllers\ChatController;


$router = new Router();



$chat=new ChatController();



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
[$chat,'conversations']
);

$router->get(
'/api/chat/conversation/(\d+)',
[$chat,'show']
);

$router->post(
'/api/chat/reply',
[$chat,'reply']
);

$router->post(
'/api/chat/close/(\d+)',
[$chat,'close']
);