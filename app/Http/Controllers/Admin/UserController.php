<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{User, Role};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, Hash, Storage};
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with('roles');
        
        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }
        
        // User type filter
        if ($request->filled('user_type')) {
            $query->where('user_type', $request->user_type);
        }
        
        // Status filter
        if ($request->filled('status')) {
            $query->where('is_active', $request->status == 'active');
        }
        
        // Role filter
        if ($request->filled('role')) {
            $query->whereHas('roles', function($q) use ($request) {
                $q->where('name', $request->role);
            });
        }
        
        $users = $query->latest()->paginate(20);
        $roles = Role::all();
        
        return view('admin.users.index', compact('users', 'roles'));
    }
    
    public function create()
    {
        $roles = Role::all();
        return view('admin.users.create', compact('roles'));
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => ['required', 'confirmed', Password::min(8)],
            'user_type' => 'required|in:trainee,trainer,staff,admin',
            'photo' => 'nullable|image|max:2048',
            'is_active' => 'required|boolean',
            'role_id' => 'nullable|exists:roles,id',
        ]);
        
        $validated['password'] = Hash::make($validated['password']);
        $validated['profile_completed'] = false;
        $validated['email_verified_at'] = now();
        
        // Handle photo upload
        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('user_photos'), $filename);
            $validated['photo'] = $filename;
        }
        
        $user = User::create($validated);
        
        // Attach role if selected
        if (!empty($validated['role_id'])) {
            $user->roles()->attach($validated['role_id']);
        }
        
        // Log activity
        activity()
            ->performedOn($user)
            ->causedBy(Auth::user())
            ->log('User created');
        
        return redirect()->route('admin.users.index')
            ->with('success', 'User created successfully!');
    }
    
    public function show($id)
    {
        $user = User::with(['roles', 'traineeProfile', 'enrollments.trainingBatch.trainingProgram'])
            ->findOrFail($id);
        
        return view('admin.users.show', compact('user'));
    }
    
    public function edit($id)
    {
        $user = User::with('roles')->findOrFail($id);
        $roles = Role::all();
        
        return view('admin.users.edit', compact('user', 'roles'));
    }
    
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $id,
            'password' => ['nullable', 'confirmed', Password::min(8)],
            'user_type' => 'required|in:trainee,trainer,staff,admin',
            'photo' => 'nullable|image|max:2048',
            'is_active' => 'required|boolean',
            'role_id' => 'nullable|exists:roles,id',
        ]);
        
        // Update password only if provided
        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }
        
        // Handle photo upload
        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            if ($user->photo && file_exists(public_path('user_photos/' . $user->photo))) {
                unlink(public_path('user_photos/' . $user->photo));
            }
            $file = $request->file('photo');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('user_photos'), $filename);
            $validated['photo'] = $filename;
        }
        
        $user->update($validated);
        
        // Sync role (single role)
        if (isset($validated['role_id'])) {
            $user->roles()->sync([$validated['role_id']]);
        } else {
            $user->roles()->sync([]);
        }
        
        // Log activity
        activity()
            ->performedOn($user)
            ->causedBy(Auth::user())
            ->log('User updated');
        
        return redirect()->route('admin.users.index')
            ->with('success', 'User updated successfully!');
    }
    
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        
        // Prevent self-deletion
        if ($user->id == Auth::id()) {
            return back()->with('error', 'You cannot delete your own account!');
        }
        
        // Log activity before deletion
        activity()
            ->performedOn($user)
            ->causedBy(Auth::user())
            ->log('User deleted');
        
        $user->delete();
        
        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted successfully!');
    }
    
    public function toggleStatus($id)
    {
        $user = User::findOrFail($id);
        
        // Prevent self-deactivation
        if ($user->id == Auth::id()) {
            return back()->with('error', 'You cannot deactivate your own account!');
        }
        
        $user->update(['is_active' => !$user->is_active]);
        
        $status = $user->is_active ? 'activated' : 'deactivated';
        
        activity()
            ->performedOn($user)
            ->causedBy(Auth::user())
            ->log("User {$status}");
        
        return back()->with('success', "User {$status} successfully!");
    }
}
