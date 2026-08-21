<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = User::with('roles');
        $query->whereHas('roles', function ($query) use ($request) {
            if (! $request->user()->hasRole('Super Admin')) {
                $query->where('name', '!=', 'Super Admin');
            }
        });

        if ($request->search) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        return response()->json([
            'status' => 'success',
            'message' => 'User list loaded successfully.',
            'data' => $query->paginate(10),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'status' => 'nullable|integer',
            'hp' => 'nullable|string|max:20',
            'roles' => 'nullable|array',
            'roles.*' => 'integer|exists:roles,id',
        ]);

        $roleNames = $this->resolveRoleNames($request->user(), $validated['roles'] ?? []);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'status' => $validated['status'] ?? 1,
            'detail' => $this->buildDetailPayload($validated),
        ]);

        if ($roleNames->isNotEmpty()) {
            $user->syncRoles($roleNames);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'User created successfully.',
            'data' => $user->load('roles'),
        ], 201);
    }

    public function show(string $id): JsonResponse
    {
        $user = User::with('roles')->findOrFail($id);
        $this->ensureUserAccessible(auth()->user(), $user);

        return response()->json([
            'status' => 'success',
            'message' => 'User loaded successfully.',
            'data' => $user,
        ]);
    }

    public function update(Request $request, string $id): JsonResponse
    {
        $user = User::findOrFail($id);
        $this->ensureUserAccessible($request->user(), $user);

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:users,email,' . $id,
            'password' => 'sometimes|string|min:8|confirmed',
            'roles' => 'sometimes|array',
            'roles.*' => 'integer|exists:roles,id',
            'status' => 'sometimes|integer',
            'hp' => 'nullable|string|max:20',
        ]);

        if ($request->has('password')) {
            $validated['password'] = Hash::make($validated['password']);
        }

        $payload = collect($validated)
            ->except(['roles', 'hp'])
            ->toArray();

        if ($request->has('hp')) {
            $payload['detail'] = $this->mergeDetailPayload($user, $validated['hp']);
        }

        $user->update($payload);

        if ($request->has('roles')) {
            $user->syncRoles($this->resolveRoleNames($request->user(), $validated['roles'] ?? []));
        }

        return response()->json([
            'status' => 'success',
            'message' => 'User updated successfully.',
            'data' => $user->load('roles'),
        ]);
    }

    public function destroy(Request $request, string $id): JsonResponse
    {
        $user = User::findOrFail($id);

        if ($user->id === $request->user()->id || $user->hasRole('Super Admin')) {
            throw new AuthorizationException('This user cannot be deleted.');
        }

        $this->ensureUserAccessible($request->user(), $user);
        $user->syncRoles([]);
        $user->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'User deleted successfully.',
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

    private function ensureUserAccessible(User $actor, User $target): void
    {
        if ($target->hasRole('Super Admin') && $target->id !== $actor->id) {
            throw new AuthorizationException('You are not allowed to access this user.');
        }
    }

    private function resolveRoleNames(User $actor, array $roleIds)
    {
        if ($roleIds === []) {
            return collect();
        }

        $roles = Role::query()
            ->whereIn('id', $roleIds)
            ->get();

        if (! $actor->hasRole('Super Admin') && $roles->contains('name', 'Super Admin')) {
            throw new AuthorizationException('You are not allowed to assign Super Admin.');
        }

        return $roles->pluck('name');
    }

    private function buildDetailPayload(array $validated): ?array
    {
        $detail = array_filter([
            'hp' => $validated['hp'] ?? null,
        ], fn ($value) => filled($value));

        return $detail ?: null;
    }

    private function mergeDetailPayload(User $user, ?string $hp): ?array
    {
        $detail = $user->detail ?? [];
        $detail['hp'] = $hp;
        $detail = array_filter($detail, fn ($value) => filled($value));

        return $detail ?: null;
    }
}
