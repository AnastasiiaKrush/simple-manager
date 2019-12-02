<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Laravel\Passport\Client;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use \Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /**
     * Client object.
     *
     * @var object
     */
    private $client;

    /**
     * Write client information to client object.
     *
     * @return void
     */
    public function __construct () {
        $this->client = Client::find(2);
    }

    /**
     * User login.
     *
     * @param  Request $request
     * @return Route
     * @throws ValidationException
     */

    public function login(Request $request) {
        $this->validate($request, [
            'username' => 'required',
            'password' => 'required',
        ]);

        $params = [
            'grant_type' => 'password',
            'client_id' => $this->client->id,
            'client_secret' => $this->client->secret,
            'username' => request('username'),
            'password' => request('password'),
            'scope' => '*'
        ];

        $request->request->add($params);
        $proxy = Request::create('oauth/token', 'POST');

        return Route::dispatch($proxy);
    }

    /**
     * Refresh token.
     *
     * @param Request $request
     * @return Route
     * @throws ValidationException
     */
    public function refresh(Request $request) {
        $this->validate($request, [
            'refresh_token' => 'required',
        ]);

        $params = [
            'grant_type' => 'refresh_token',
            'client_id' => $this->client->id,
            'client_secret' => $this->client->secret,
            'username' => request('username'),
            'password' => request('password')
        ];

        $request->request->add($params);
        $proxy = Request::create('oauth/token', 'POST');

        return Route::dispatch($proxy);
    }

    /**
     * User logout.
     *
     * @return Response
     */

    public function logout() {
        $accessToken = Auth::user()->token();

        DB::table('oauth_refresh_tokens')
            ->where('access_token_id', $accessToken)
            ->update(['revoked' => true]);

        $accessToken->revoke();

        return response()->json([], 204);
    }
}
