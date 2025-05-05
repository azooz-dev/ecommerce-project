<?php

namespace App\Http\Controllers\Auth;

use App\Events\UserVerifyEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserLoginRequest;
use App\Http\Requests\User\UserStoreRequest;
use App\Http\Resources\User\UserResource;
use App\Models\User;
use Exception;
use function App\Helpers\errorResponse;

use function App\Helpers\showMessage;
use function App\Helpers\showOne;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(UserStoreRequest $request)
    {
        $data = $request->validated();
        try {
            $data['password'] = Hash::make($data['password']);
            $data['verified'] = User::UNVERIFIED_USER;
            $data['verification_token'] = User::generatedTokenString();

            $user = User::create($data);

            event(new UserVerifyEvent($user));

            $token = $user->createToken('personal_token')->plainTextToken;

            $user = new UserResource($user);

            return showOne(['user' => $user, 'token' => $token], 201);
        } catch (Exception $e) {
            return errorResponse($e->getMessage(), 500);
        }
    }


    public function login(UserLoginRequest $request)
    {
        $data = $request->validated();

        try {
            if (Auth::attempt($data)) {
                $user = User::where('email', $data['email'])->first();

                if (!$user->verified) {
                    return errorResponse(__('auth.unverified'), 403);
                }

                if ($user->role === User::ADMIN_USER) {
                    $token = $user->createToken('admin-token', ['view', 'create', 'update', 'delete'])->plainTextToken;
                } else {
                    $token = $user->createToken('regular-token', ['view'])->plainTextToken;
                }

                $user->token = $token;

                return showOne(['user' => new UserResource($user), 'token' => $token], 200);
            }

            return errorResponse(__('auth.failed'), 401);
        } catch (Exception $e) {
            return errorResponse($e->getMessage(), 500);
        }
    }

    public function logout(Request $request)
    {
        try {
            if ($request->user()->currentAccessToken()->delete()) {
                return showMessage(__('auth.logged_out'), 200);
            } else {
                return errorResponse(__('auth.failed_logged_out'), 500);
            }
        } catch (Exception $e) {
            return errorResponse($e->getMessage(), 500);
        }
    }
}
