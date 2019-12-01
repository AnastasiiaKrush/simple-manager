<?php

namespace App\Http\Controllers;

use \Illuminate\Http\Response;
use App\User;

class UserController extends Controller
{
    /**
     * Create get users except current.
     *
     * @return Response
     */
    public function getAll()
    {
        $users = User::all()->except(auth()->user()->id);
        return response()->json($users);
    }
}
