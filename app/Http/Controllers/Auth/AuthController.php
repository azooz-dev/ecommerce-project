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
use function App\Helpers\showOne;
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
            if (!auth()->attempt($data)) {
                return errorResponse('بيانات الدخول غير صحيحة', 401);
            } else {

                $user = auth()->user();
                $token = $user->createToken('personal_token')->plainTextToken;

                return showOne(['user' => new UserResource($user), 'token' => $token], 200);
            }
        } catch (Exception $e) {
            return errorResponse($e->getMessage(), 500);
        }
    }
}
