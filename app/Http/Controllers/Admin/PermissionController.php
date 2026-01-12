<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    /**
     * Display a listing of permissions.
     */
    public function index()
    {
        $permissions = Permission::all()->groupBy('group_name');
        return view('admin.permissions.index', compact('permissions'));
    }

    /**
     * Show the form for creating a new permission.
     */
    public function create()
    {
        $groups = Permission::distinct()->pluck('group_name')->filter()->toArray();
        return view('admin.permissions.create', compact('groups'));
    }

    /**
     * Store a newly created permission.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:permissions',
            'display_name' => 'nullable|string|max:255',
            'group_name' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:500',
        ], [
            'name.required' => 'Vui lòng nhập tên quyền.',
            'name.unique' => 'Tên quyền đã tồn tại.',
        ]);

        Permission::create([
            'name' => $request->name,
            'display_name' => $request->display_name,
            'group_name' => $request->group_name,
            'description' => $request->description,
        ]);

        return redirect()->route('admin.permissions.index')
            ->with('success', 'Tạo quyền thành công!');
    }

    /**
     * Show the form for editing the permission.
     */
    public function edit(string $id)
    {
        $permission = Permission::findOrFail($id);
        $groups = Permission::distinct()->pluck('group_name')->filter()->toArray();
        return view('admin.permissions.edit', compact('permission', 'groups'));
    }

    /**
     * Update the specified permission.
     */
    public function update(Request $request, string $id)
    {
        $permission = Permission::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255|unique:permissions,name,' . $id,
            'display_name' => 'nullable|string|max:255',
            'group_name' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:500',
        ]);

        $permission->update([
            'name' => $request->name,
            'display_name' => $request->display_name,
            'group_name' => $request->group_name,
            'description' => $request->description,
        ]);

        return redirect()->route('admin.permissions.index')
            ->with('success', 'Cập nhật quyền thành công!');
    }

    /**
     * Remove the specified permission.
     */
    public function destroy(string $id)
    {
        $permission = Permission::findOrFail($id);
        $permission->roles()->detach();
        $permission->delete();

        return redirect()->route('admin.permissions.index')
            ->with('success', 'Xóa quyền thành công!');
    }
}
