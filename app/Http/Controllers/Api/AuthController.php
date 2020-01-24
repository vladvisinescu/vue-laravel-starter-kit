<?php

namespace App\Http\Controllers\Api;

use App\Models\Auth\User;

use App\Notifications\ResetPasswordNotification;
use App\Notifications\ChangedPasswordNotification;

use App\Http\Controllers\Controller;

use App\Http\Requests\Api\Auth\ApiLoginRequest;
use App\Http\Requests\Api\Auth\ApiRegisterRequest;
use App\Http\Requests\Api\Auth\SendPasswordRequest;
use App\Http\Requests\Api\Auth\ResetPasswordRequest;

use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use Illuminate\Support\Str;
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

        $token = encrypt($user->id . '-' . now()->format('YmdHis') . '-' . Str::random(32));

        $user->update([
            'reset_token' => $token,
            'reset_token_created_at' => now()
        ]);

        $user->notify(new ResetPasswordNotification($token));

        return response()->json([], 200);
    }

    public function sendPassword(SendPasswordRequest $request)
    {
        $user = User::where('email', $request->input('email'))->first();

        // Check token expiry
        if ($user->reset_token_created_at->diffInMinutes(now()) > config('auth.reset_token_lifetime')) {
            abort(400, 'Token expired.');
        }

        // Check provided and stored tokens match
        if ($user->reset_token !== $request->input('token')) {
            abort(400, 'Token mismatch.');
        }

        $user->update([
            'password' => bcrypt($request->input('password')),
            'reset_token' => null,
            'reset_token_created_at' => null
        ]);

        $user->notify(new ChangedPasswordNotification);

        return response()->json([], 200);
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
