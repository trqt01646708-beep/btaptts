<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $permissions = \App\Models\Permission::all();
        return view('admin.permissions.index', compact('permissions'));
    }

    public function create()
    {
        return view('admin.permissions.create');
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|unique:permissions,name']);
        \App\Models\Permission::create(['name' => $request->name]);
        return redirect()->route('admin.permissions.index')->with('success', 'Quyền hạn đã được tạo thành công.');
    }

    public function edit(\App\Models\Permission $permission)
    {
        return view('admin.permissions.edit', compact('permission'));
    }

    public function update(Request $request, \App\Models\Permission $permission)
    {
        $request->validate(['name' => 'required|unique:permissions,name,' . $permission->id]);
        $permission->update(['name' => $request->name]);
        return redirect()->route('admin.permissions.index')->with('success', 'Quyền hạn đã được cập nhật thành công.');
    }

    public function destroy(\App\Models\Permission $permission)
    {
        $permission->delete();
        return redirect()->route('admin.permissions.index')->with('success', 'Quyền hạn đã được xóa thành công.');
    }
}
