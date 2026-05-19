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


namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = ['name', 'label', 'description', 'is_active'];

    public function users()
    {
        return $this->hasMany(\App\Models\User::class);
    }

    public function permissions()
    {
        return $this->hasMany(\App\Models\RolePermission::class);
    }

    public function can(string $module): bool
    {
        // admin siempre tiene todo
        if ($this->name === 'admin') return true;

        return $this->permissions->pluck('module')->contains($module);
    }
}
