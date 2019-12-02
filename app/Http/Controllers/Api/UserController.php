<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use \Illuminate\Http\Response;
use App\User;

class UserController extends Controller
{
    /**
     * Get user list except current.
     *
     * @return Response
     */
    public function getAll()
    {
        $users = User::all()->except(auth()->user()->id);
        return response()->json(['users' => $users], 200);
    }

    /**
     * Get all messages from concrete user.
     *
     * @param  Request $request
     * @return Response
     */
    public function getMessagesFromUser(Request $request)
    {
        $authUserId = auth()->user()->id;

        $messages = User::find($authUserId)
            ->received()
            ->where('user_id_from', $request['user_id_from'])
            ->get();

        return response()->json(['messages' => $messages], 200);
    }
}
