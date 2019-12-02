<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Message;
use Illuminate\Validation\Rule;

class MessageController extends Controller
{
    /**
     * Create message.
     *
     * @param Request $request
     * @return Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function create(Request $request)
    {
        $authUserId = auth()->user()->id;

        $this->validate($request, [
            'user_id_to' => ['required', Rule::notIn($authUserId)],
            'body' => 'required',
        ]);

        $message = Message::create([
            'user_id_from' => $authUserId,
            'user_id_to' => $request['user_id_to'],
            'body' => $request['body']
        ]);

        return response()->json([ 'message_id' => $message->id], 200);
    }
}
