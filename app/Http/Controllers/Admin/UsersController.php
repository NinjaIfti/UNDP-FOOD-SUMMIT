<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UsersController extends Controller
{
    public function index(Request $request)
    {
        $this->ensureRolesExist();

        $query = User::role(['admin', 'super-admin', 'cms-admin'])->with('roles');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->orderByDesc('created_at')->paginate(20)->withQueryString();

        $users->getCollection()->transform(function ($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->roles->first()?->name === 'super-admin' ? 'Super Admin' : ($user->roles->first()?->name === 'cms-admin' ? 'CMS Admin' : 'Admin'),
                'role_name' => $user->roles->first()?->name ?? 'admin',
                'status' => 'Active',
                'created_at' => $user->created_at?->format('M d, Y'),
                'last_login' => $user->updated_at?->format('M d, Y h:i A'),
                'permissions' => $this->getUserPermissions($user),
            ];
        });

        $stats = [
            'total' => User::role(['admin', 'super-admin', 'cms-admin'])->count(),
            'super_admins' => User::role('super-admin')->count(),
            'cms_admins' => User::role('cms-admin')->count(),
        ];

        return Inertia::render('Admin/Users/Index', [
            'users' => $users,
            'stats' => $stats,
        ]);
    }

    public function store(Request $request)
    {
        $this->ensureRolesExist();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => 'required|in:super-admin,cms-admin',
            'permissions' => 'array',
            'permissions.*' => 'boolean',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        $role = Role::firstOrCreate(['name' => $validated['role'], 'guard_name' => 'web']);
        $user->assignRole($role);

        if (isset($validated['permissions'])) {
            $this->syncPermissions($user, $validated['permissions']);
        }

        return redirect()->route('admin.users.index')
            ->with('success', 'User created successfully.');
    }

    public function update(Request $request, User $user)
    {
        $this->ensureRolesExist();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
            'role' => 'required|in:super-admin,cms-admin',
            'permissions' => 'array',
            'permissions.*' => 'boolean',
        ]);

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        if (!empty($validated['password'])) {
            $user->update(['password' => Hash::make($validated['password'])]);
        }

        $role = Role::firstOrCreate(['name' => $validated['role'], 'guard_name' => 'web']);
        $user->syncRoles([$role]);

        if (isset($validated['permissions'])) {
            $this->syncPermissions($user, $validated['permissions']);
        }

        return redirect()->route('admin.users.index')
            ->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'You cannot delete your own account.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted successfully.');
    }

    protected function getUserPermissions(User $user): array
    {
        $permissionKeys = [
            'homepage',
            'programme-experience',
            'your-bhutan-stay',
            'get-involved',
            'faqs',
            'blog',
            'applications',
            'payments',
            'packages',
            'communications',
            'users',
            'settings',
        ];

        $permissions = [];
        foreach ($permissionKeys as $key) {
            $permission = Permission::firstOrCreate(['name' => $key, 'guard_name' => 'web']);
            $permissions[$key] = $user->hasPermissionTo($permission);
        }

        return $permissions;
    }

    protected function syncPermissions(User $user, array $permissions): void
    {
        $permissionKeys = [
            'homepage',
            'programme-experience',
            'your-bhutan-stay',
            'get-involved',
            'faqs',
            'blog',
            'applications',
            'payments',
            'packages',
            'communications',
            'users',
            'settings',
        ];

        foreach ($permissionKeys as $key) {
            $permission = Permission::firstOrCreate(['name' => $key, 'guard_name' => 'web']);
            if (isset($permissions[$key]) && $permissions[$key]) {
                $user->givePermissionTo($permission);
            } else {
                $user->revokePermissionTo($permission);
            }
        }
    }

    protected function ensureRolesExist(): void
    {
        Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'super-admin', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'cms-admin', 'guard_name' => 'web']);
    }
}
