<?php

namespace App\Http\Controllers\User;

use App\Events\UserVerifyEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserUpdateRequest;
use App\Http\Resources\User\UserResource;
use App\Models\User;
use Exception;
use function App\Helpers\errorResponse;
use function App\Helpers\showAll;
use function App\Helpers\showMessage;
use function App\Helpers\showOne;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if ($this->authorize('viewAny', User::class)) {
            try {
                $users = User::latest()->get();

                $users = UserResource::collection($users);

                return showAll($users, 200);
            } catch (Exception $e) {
                return errorResponse($e->getMessage(), 500);
            }
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        if ($this->authorize('view', $user)) {
            try {
                return showOne(new UserResource($user), 200);
            } catch (Exception $e) {
                return errorResponse($e->getMessage(), 500);
            }
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserUpdateRequest $request, User $user)
    {
        $data = $request->validated();

        if ($this->authorize('update', $user)) {

            try {
                if (isset($data['role'])) {
                    if (!$user->isVerified()) {
                        return errorResponse('Your account is not verified', 409);
                    }
                    $user->role = $data['role'];
                }

                $user->fill(array_filter([
                    'first_name' => $data['first_name'] ?? null,
                    'last_name'  => $data['last_name'] ?? null,
                    'password'   => isset($data['password']) ? Hash::make($data['password']) : null,
                    'phone'      => $data['phone'] ?? null,
                ]));



                if (isset($data['email']) && $user->email !== $data['email']) {
                    $user->fill([
                        'email' => $data['email'],
                        'verified' => User::UNVERIFIED_USER,
                        'verification_token' => User::generatedTokenString(),
                    ]);

                    event(new UserVerifyEvent($user));
                }

                if ($user->isClean()) {
                    return errorResponse('You need to specify a different value to update', 422);
                }

                $user->save();

                return showOne(new UserResource($user), 200);
            } catch (Exception $e) {
                return errorResponse($e->getMessage(), 500);
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        if ($this->authorize('delete', $user)) {
            try {
                $user->delete();

                return showOne(new UserResource($user), 200);
            } catch (Exception $e) {
                return errorResponse($e->getMessage(), 500);
            }
        }
    }

    public function verify(String $token)
    {
        try {
            $user = User::where('verification_token', $token)->firstOrFail();
            $user->verified = User::VERIFIED_USER;
            $user->verification_token = null;
            $user->save();

            return showOne(new UserResource($user), 200);
        } catch (Exception $e) {
            return errorResponse($e->getMessage(), 500);
        }
    }

    public function resendEmail(User $user)
    {
        if ($user->isVerified()) {
            return errorResponse('This user is already verified.', 409);
        }

        event(new UserVerifyEvent($user));

        return showMessage('The verification email has been resend.', 200);
    }
}
