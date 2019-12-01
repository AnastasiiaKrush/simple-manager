<?php

namespace App\Http\Controllers;

use \Illuminate\Http\Request;
use \Illuminate\Http\Response;
use App\Message;

class MessageController extends Controller
{
    /**
     * Create get users except current.
     *
     * @return Response
     */
    public function create(Request $data)
    {
        $authUserId = auth()->user()->id;

        if (!$authUserId) {
            return response()->json([ 'status'=> 0, 'error' => 'Not authorized!' ]);
        }

        if( !$data['user_id_to'] || !$data['body']) {
            return response()->json([ 'status'=> 0, 'error' => 'Some fields are empty' ]);
        }

        $message = Message::create([
            'user_id_from' => $authUserId,
            'user_id_to' => $data['user_id_to'],
            'body' => $data['body']
        ]);

        return response()->json([ 'message id' => $message->id]);
    }

    /**
     * Get all messages from concrete user.
     *
     * @param  Request
     * @return Response
     */
    public function getMessagesFromUser(Request $data)
    {
        $authUserId = auth()->user()->id;
        if (!$authUserId) {
            return response()->json([ 'status'=> 0, 'error' => 'Not authorized!' ]);
        }

        $messages = Message::select('body', 'created_at')
            ->where('user_id_from', $data['user_id_from'])
            ->where('user_id_to', $authUserId)
            ->get();

        return response()->json($messages);
    }
}
