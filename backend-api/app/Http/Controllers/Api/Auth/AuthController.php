<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\LoginRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;


class AuthController extends Controller
{
    /**
     * Handle user login
     */
    public function login(LoginRequest $request): JsonResponse
    {
        try {
            $credentials = $request->validated();

            if (!Auth::attempt([
                'email' => $credentials['email'],
                'password' => $credentials['password']
            ])) {
                $userExists = User::where('email', $credentials['email'])->exists();
                if ($userExists) {
                    throw ValidationException::withMessages([
                        'password' => ['The provided password is incorrect.'],
                    ]);
                } else {
                    throw ValidationException::withMessages([
                        'email' => ['No account found with this email address.'],
                    ]);
                }
            }

            $user = Auth::user();

            // Previous tokens revoke
            // $user->tokens()->delete();
            $expiry = $request->boolean('remember_me') ? now()->addDays(30) : now()->addDay();

            $token = $user->createToken(
                $request->device_name ?? 'web-browser',
                ['*'], // abilities
                $expiry
            )->plainTextToken;

            return response()->json([
                'status' => 'success',
                'message' => 'Login successful',
                'data' => [
                    'token' => $token,
                    'token_type' => 'Bearer',
                    'user' => new UserResource($user),
                    'expires_at' => $expiry ? $expiry->toDateTimeString() : null,
                ]
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid credentials',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Handle user logout
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Logged out successfully'
        ]);
    }

    /**
     * Get authenticated user
     */
    public function me(Request $request): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'data' => '[
            "user"=>"bangladesh",
            ]'
        ]);
    }
    public function roleRegister(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required|string|min:8|confirmed',
            "phone" => 'required|string|max:11'
        ], [
            'email.required' => 'The email field is required.',
            'email.email' => 'Please provide a valid email address.',
            'password.required' => 'The password field is required.',
            'password.min' => 'The password must be at least 8 characters.',
            'password.confirmed' => 'The password confirmation does not match.',
            'phone.required' => 'The phone number field is required.',
            'phone.max' => 'The phone number must be 11 characters max.',
            'phone.string' => 'The phone number must be string.',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
        ]);
        $user->assignRole($request->role);

        if (!$user) {
            return response()->Json([
                'success' => false,
                'message' => 'Registration failed'
            ], 422);
        }

        return response()->json([
            'status' => 'success',
            'success' => true,
            'data' => $user,
            'message' => 'New User Register Successfully.',
        ], 201);
    }
    public function getNewRegisterUsers()
    {

        $newUsers = User::latest()->get()->map(function ($user){        //map function use for extra data added to User table
            $data=$user->toArray();
            $data['role']=$user->getRoleNames()->first();

            return $data;

        });

        // get array to roles table

        // $newUsers = User::with('roles')->latest()->get()->map(function ($newUser){
        //    $data= $newUser->toArray();
        //    $data['role']=$newUser->roles->pluck('name')->first();
        //    return $data;
        // });


        if(!$newUsers){
            return response()->Json([
                'status' => 'failed',
                'success' => false,
                'message' => 'error.',
            ],422);
        }

        if($newUsers){
            return response()->Json([
                'status' => 'success',
                'success' => true,
                'data' => $newUsers,
                'message' => 'User Data Fetch Successfull.',
            ],200);
        }

    }
}
