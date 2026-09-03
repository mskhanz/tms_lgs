<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Role, Permission};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::withCount(['users', 'permissions'])->get();
        $permissions = Permission::all()->groupBy('group');
        
        return view('admin.roles.index', compact('roles', 'permissions'));
    }
    
    public function create()
    {
        $permissions = Permission::all()->groupBy('group');
        return view('admin.roles.create', compact('permissions'));
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:50|unique:roles,name|regex:/^[a-z_]+$/',
            'display_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);
        
        $role = Role::create($validated);
        
        // Attach permissions
        if (!empty($validated['permissions'])) {
            $role->permissions()->attach($validated['permissions']);
        }
        
        // Log activity
        activity()
            ->performedOn($role)
            ->causedBy(Auth::user())
            ->log('Role created');
        
        return redirect()->route('admin.roles.index')
            ->with('success', 'Role created successfully!');
    }
    
    public function show($id)
    {
        $role = Role::with(['permissions', 'users'])->findOrFail($id);
        return view('admin.roles.show', compact('role'));
    }
    
    public function edit($id)
    {
        $role = Role::with('permissions')->findOrFail($id);
        $permissions = Permission::all()->groupBy('group');
        
        return view('admin.roles.edit', compact('role', 'permissions'));
    }
    
    public function update(Request $request, $id)
    {
        $role = Role::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:50|unique:roles,name,' . $id . '|regex:/^[a-z_]+$/',
            'display_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);
        
        $role->update($validated);
        
        // Sync permissions
        if (isset($validated['permissions'])) {
            $role->permissions()->sync($validated['permissions']);
        } else {
            $role->permissions()->sync([]);
        }
        
        // Log activity
        activity()
            ->performedOn($role)
            ->causedBy(Auth::user())
            ->log('Role updated');
        
        return redirect()->route('admin.roles.index')
            ->with('success', 'Role updated successfully!');
    }
    
    public function destroy($id)
    {
        $role = Role::findOrFail($id);
        
        // Prevent deletion of system roles
        if (in_array($role->name, ['system_admin', 'director', 'deputy_director'])) {
            return back()->with('error', 'System roles cannot be deleted!');
        }
        
        // Check if role has users
        if ($role->users()->count() > 0) {
            return back()->with('error', 'Cannot delete role with assigned users!');
        }
        
        // Log activity before deletion
        activity()
            ->performedOn($role)
            ->causedBy(Auth::user())
            ->log('Role deleted');
        
        $role->delete();
        
        return redirect()->route('admin.roles.index')
            ->with('success', 'Role deleted successfully!');
    }
}
