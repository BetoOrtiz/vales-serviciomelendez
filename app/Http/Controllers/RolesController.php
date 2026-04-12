<?php

namespace App\Http\Controllers\Laradash;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesController extends Controller
{
    public function index()
    {
        $roles = Role::select('id', 'name', 'guard_name')->with('permissions')->orderBy('id')->get();
        $permissions = Permission::select('id', 'name')->get();

        return Inertia::render('Roles', compact('roles', 'permissions'));
    }
}
