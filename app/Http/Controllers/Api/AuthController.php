<?php

namespace App\Http\Controllers\Api;

use App\Models\Auth\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\ApiLoginRequest;
use App\Http\Requests\Api\Auth\ApiRegisterRequest;
use App\Http\Requests\Api\Auth\ResetPasswordRequest;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cookie;

class AuthController extends Controller
{
    const REFRESH_TOKEN = 'refresh_token';

    public function login(ApiLoginRequest $request)
    {
        $user = User::where('email', $request->input('email'))->with(['roles'])->first();

        if (!$user || !Hash::check($request->input('password'), $user->password)) {
            // TODO: Refactor this
            return response()->json([
                'message' => 'Invalid credentials.'
            ], Response::HTTP_UNAUTHORIZED);
        }

        $body = $this->proxy('password', [
            'username' => $request->input('email'),
            'password' => $request->input('password')
        ]);

        $token = $body->access_token;
        $refreshToken = encrypt($body->refresh_token);

        $cookie = cookie(self::REFRESH_TOKEN, $refreshToken, 864000, null, null, false, true);

        return response()->json([
            'access_token' => $token,
            'expires_in' => $body->expires_in,
            'user' => [
                'id' => $user->getKey(),
                'name' => $user->name,
                'roles' => $user->roles->pluck('name')
            ]
        ], Response::HTTP_OK)->cookie($cookie);
    }

    public function register(ApiRegisterRequest $request)
    {
        $user = User::create([
             'name' => $request->input('name'),
             'email' => $request->input('email'),
             'password' => Hash::make($request->input('password')),
        ]);

        if (!$user) abort(Response::HTTP_SERVICE_UNAVAILABLE);

        $body = $this->proxy('password', [
            'username' => $request->input('email'),
            'password' => $request->input('password')
        ]);

        $token = $body->access_token;
        $refreshToken = encrypt($body->refresh_token);

        $cookie = cookie(self::REFRESH_TOKEN, $refreshToken, 864000, null, null, false, true);

        $user->attachRole('user');

        return response()->json([
            'access_token' => $token,
            'expires_in' => $body->expires_in,
            'user' => [
                'id' => $user->getKey(),
                'name' => $user->name,
                'roles' => $user->roles->pluck('name')
            ]
        ], Response::HTTP_OK)->cookie($cookie);
    }

    public function refreshToken(Request $request)
    {
        if (!Cookie::has('refresh_token')) {
            abort(500, 'Invalid payload.');
            exit();
        }

        $data = [
            'refresh_token' => decrypt(Cookie::get('refresh_token')),
            'scope' => ''
        ];

        $body = $this->proxy('refresh_token', $data);

        $token = $body->access_token;
        $refreshToken = encrypt($body->refresh_token);

        $cookie = cookie(self::REFRESH_TOKEN, $refreshToken, 864000, null, null, false, true);

        return response()->json([
            'access_token' => $token,
            'expires_in' => $body->expires_in,
        ], Response::HTTP_OK)->cookie($cookie);
    }

    public function resetPassword(ResetPasswordRequest $request)
    {
        $user = User::where('email', $request->input('email'))->first();

        if (!$user) {
            return response()->json([
                'message' => 'User not found.'
            ], Response::HTTP_BAD_REQUEST);
        }

        $user->notify(new ResetPasswordNotification);
    }

    protected function proxy(string $grantType = 'password', array $data = [])
    {
        $data = array_merge($data, [
            'grant_type' => $grantType,
            'client_id' => env('PASSWORD_CLIENT_ID'),
            'client_secret' => env('PASSWORD_CLIENT_SECRET'),
        ]);

        $client = new Client([
            'base_uri' => config('app.url'),
        ]);

        $request = $client->request(
            'POST',
             '/oauth/token',
            ['form_params' => $data]
        );

        if ($request->getStatusCode() == Response::HTTP_OK) {
            return \GuzzleHttp\json_decode($request->getBody()->getContents());
        }
    }

    public function logout(Request $request)
    {
        if ($request->user()) {
            $token = $request->user()->token();
            $token->revoke();
        }
//        $token->delete();

        return response()
            ->json([], Response::HTTP_OK)
            ->cookie(cookie()->forget(self::REFRESH_TOKEN));
    }

    public function getUserInfo(Request $request)
    {
        return response()->json([
            'id' => auth()->user()->getKey(),
            'name' => auth()->user()->name,
            'roles' => auth()->user()->roles->pluck('name')
        ]);
    }

    public function getRoles(Request $request)
    {
        return $request->user()->roles()->get(['name']);
    }
}
