<?php

namespace App\Chat\Controllers;


use App\Chat\Models\Visitor;
use App\Chat\Models\Conversation;
use App\Chat\Models\Message;


class ChatController
{


    public function start()
    {

        $visitorId = uniqid();


        $visitor = Visitor::create([
            'visitor_id'=>$visitorId
        ]);


        $conversation = Conversation::create([
            'visitor_id'=>$visitor->id
        ]);


        echo json_encode([
            "conversation_id"=>$conversation->id,
            "visitor_id"=>$visitorId
        ]);

    }



    public function send()
    {

        $data=json_decode(
            file_get_contents("php://input"),
            true
        );


        $message=Message::create([

            'conversation_id'=>$data['conversation_id'],

            'sender'=>$data['sender'],

            'message'=>$data['message']

        ]);


        echo json_encode([
            "status"=>"sent",
            "id"=>$message->id
        ]);

    }



    public function history($id)
    {

        $messages=Message::where(
            'conversation_id',
            $id
        )->get();


        echo json_encode($messages);

    }


    public function conversations()
{

    $conversations = Conversation::where(
        'status',
        'open'
    )
    ->orderBy(
        'created_at',
        'desc'
    )
    ->get();


    echo json_encode($conversations);

}


public function show($id)
{

    $conversation = Conversation::find($id);


    if (!$conversation) {

        http_response_code(404);

        echo json_encode([
            "message"=>"Conversation not found"
        ]);

        return;

    }


    $messages = Message::where(
        'conversation_id',
        $id
    )
    ->orderBy(
        'created_at',
        'asc'
    )
    ->get();


    echo json_encode([
        "conversation"=>$conversation,
        "messages"=>$messages
    ]);

}

public function reply()
{

    $data=json_decode(
        file_get_contents("php://input"),
        true
    );


    $message = Message::create([

        "conversation_id"=>$data["conversation_id"],

        "sender"=>"agent",

        "message"=>$data["message"]

    ]);


    echo json_encode([

        "status"=>"reply sent",

        "id"=>$message->id

    ]);

}

public function close($id)
{

    $conversation = Conversation::find($id);


    if(!$conversation){

        http_response_code(404);

        echo json_encode([
            "message"=>"Conversation not found"
        ]);

        return;

    }


    $conversation->status="closed";

    $conversation->save();


    echo json_encode([
        "status"=>"conversation closed"
    ]);

}

}