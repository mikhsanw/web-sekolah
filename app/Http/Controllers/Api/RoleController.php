<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Auth\Access\AuthorizationException;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Role::query();

        if (! $request->user()->hasRole('Super Admin')) {
            $query->where('name', '!=', 'Super Admin');
        }

        if ($request->search) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Role list loaded successfully.',
            'data' => $query->paginate(10),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
        ]);

        if (! $request->user()->hasRole('Super Admin') && $validated['name'] === 'Super Admin') {
            throw new AuthorizationException('You are not allowed to create this role.');
        }

        $role = Role::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Role created successfully.',
            'data' => $role,
        ], 201);
    }

    public function show(Request $request, string $id): JsonResponse
    {
        $role = Role::findOrFail($id);
        $this->ensureRoleAccessible($request, $role);

        return response()->json([
            'status' => 'success',
            'message' => 'Role loaded successfully.',
            'data' => $role,
        ]);
    }

    public function update(Request $request, string $id): JsonResponse
    {
        $role = Role::findOrFail($id);
        $this->ensureRoleAccessible($request, $role);

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $id,
        ]);

        if (! $request->user()->hasRole('Super Admin') && $validated['name'] === 'Super Admin') {
            throw new AuthorizationException('You are not allowed to update this role.');
        }

        $role->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Role updated successfully.',
            'data' => $role,
        ]);
    }

    public function destroy(Request $request, string $id): JsonResponse
    {
        $role = Role::findOrFail($id);
        $this->ensureRoleAccessible($request, $role);

        if ($role->name === 'Super Admin') {
            throw new AuthorizationException('This role cannot be deleted.');
        }

        $role->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Role deleted successfully.',
        ]);
    }

    private function ensureRoleAccessible(Request $request, Role $role): void
    {
        if ($role->name === 'Super Admin' && ! $request->user()->hasRole('Super Admin')) {
            throw new AuthorizationException('You are not allowed to access this role.');
        }
    }
}
