<?php
/*
|--------------------------------------------------------------------------
| TIZZILA APP - © 2026 Tizzila App · Orquestación Avícola Inteligente
|--------------------------------------------------------------------------
| Proyecto privado desarrollado por:
| Ingeniero Jhoan Romero Rivera
| LinkedIn: https://linkedin.com/in/jmromeror87
|--------------------------------------------------------------------------
*/


namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\RolePermission;
use App\Models\SystemModule;
use Illuminate\Http\Request;

class RolePermissionController extends Controller
{
    public function index()
    {
        $roles   = Role::where('is_active', true)->with('permissions')->orderBy('id')->get();
        $modules = SystemModule::active()->get();

        return view('admin.permissions.index', compact('roles', 'modules'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'permissions'          => 'nullable|array',
            'permissions.*'        => 'nullable|array',
            'permissions.*.*'      => 'boolean',
        ]);

        $modules = SystemModule::active()->pluck('slug')->toArray();
        $roles   = Role::where('name', '!=', 'admin')->get();

        foreach ($roles as $role) {
            foreach ($modules as $module) {
                $granted = $request->boolean("permissions.{$role->id}.{$module}");

                if ($granted) {
                    RolePermission::firstOrCreate([
                        'role_id' => $role->id,
                        'module'  => $module,
                    ]);
                } else {
                    RolePermission::where('role_id', $role->id)
                        ->where('module', $module)
                        ->delete();
                }
            }
        }

        return back()->with('success', 'Permisos actualizados correctamente.');
    }

    public function storeModule(Request $request)
    {
        $validated = $request->validate([
            'label' => 'required|string|max:100',
            'icon'  => 'required|string|max:50',
        ]);

        $slug = \Illuminate\Support\Str::slug($validated['label'], '_');
        $slug = preg_replace('/[^a-z0-9_]/', '', strtolower($slug));

        $maxOrder = SystemModule::max('sort_order') ?? 0;

        SystemModule::create([
            'slug'       => $slug,
            'label'      => $validated['label'],
            'icon'       => $validated['icon'],
            'sort_order' => $maxOrder + 1,
            'is_active'  => true,
        ]);

        return back()->with('success', "Módulo \"{$validated['label']}\" creado.");
    }

    public function destroyModule(SystemModule $module)
    {
        RolePermission::where('module', $module->slug)->delete();
        $module->delete();

        return back()->with('success', 'Módulo eliminado.');
    }
}
