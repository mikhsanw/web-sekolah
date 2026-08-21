<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request): JsonResponse
    {
        $fields = $request->validate([
            'name' => 'nullable|string|max:255',
            'username' => 'nullable|string|max:255',
            'email' => 'required|string|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'hp' => 'nullable|string|max:20',
        ]);

        $user = User::create([
            'name' => $fields['name'] ?? $fields['username'] ?? explode('@', $fields['email'])[0],
            'detail' => $this->buildDetailPayload($fields),
            'email' => $fields['email'],
            'password' => bcrypt($fields['password']),
        ]);

        // Role/permission assignment must never be driven by public input.
        // Assign roles via the authenticated admin endpoints instead.
        if ($defaultRole = config('master.app.auth.default_role')) {
            $user->syncRoles([$defaultRole]);
        }

        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'status' => 'success',
            'message' => 'User registered successfully',
            'data' => [
                'user' => $user->load('roles'),
                'token' => $token,
            ],
        ], 201);
    }

    public function login(Request $request): JsonResponse
    {
        $fields = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $fields['email'])->first();

        if (! $user || ! Hash::check($fields['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Invalid credentials'],
            ]);
        }

        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'status' => 'success',
            'message' => 'Login successful.',
            'data' => [
                'user' => $user->load('roles'),
                'token' => $token,
            ],
        ]);
    }

    public function profile(Request $request): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'message' => 'User profile loaded successfully.',
            'data' => $request->user()->load('roles'),
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Logged out successfully.',
        ]);
    }

    private function buildDetailPayload(array $fields): ?array
    {
        $detail = array_filter([
            'hp' => $fields['hp'] ?? null,
        ], fn ($value) => filled($value));

        return $detail ?: null;
    }
}
