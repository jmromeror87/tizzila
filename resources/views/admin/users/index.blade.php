{{--
|--------------------------------------------------------------------------
| TIZZILA APP - © 2026 Tizzila App · Orquestación Avícola Inteligente
|--------------------------------------------------------------------------
| Proyecto privado desarrollado por:
| Ingeniero Jhoan Romero Rivera
| LinkedIn: https://linkedin.com/in/jmromeror87
|--------------------------------------------------------------------------
--}}

<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-3xl font-black text-white tracking-tighter uppercase">
                    Gestión de <span class="text-yellow-500">Usuarios</span>
                </h2>
                <p class="text-[10px] text-gray-500 uppercase tracking-[0.3em] font-bold mt-1">
                    Control de acceso y roles del sistema
                </p>
            </div>
        </div>
    </x-slot>

    <div class="py-4 space-y-6" x-data="{ showCreate: false, editUser: null }">

        {{-- NAV TABS --}}
        <div class="flex gap-2 border-b border-white/5 pb-4">
            <a href="{{ route('admin.users.index') }}"
               class="px-4 py-2 rounded-xl bg-yellow-500 text-black text-[10px] font-black uppercase tracking-widest">
                <i class="fas fa-users mr-2"></i>Usuarios
            </a>
            <a href="{{ route('admin.permissions.index') }}"
               class="px-4 py-2 rounded-xl bg-white/5 border border-white/10 text-gray-400 hover:text-white text-[10px] font-black uppercase tracking-widest transition-all">
                <i class="fas fa-shield-alt mr-2"></i>Permisos & Roles
            </a>
        </div>

        @if(session('success'))
            <div class="bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-xs font-bold px-5 py-3 rounded-xl">
                ✅ {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="bg-red-500/10 border border-red-500/20 text-red-400 text-xs font-bold px-5 py-3 rounded-xl">
                ⚠️ {{ session('error') }}
            </div>
        @endif

        {{-- STATS --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @foreach($roles as $role)
                @php $count = $users->where('role_id', $role->id)->count(); @endphp
                <div class="bg-[#0d121f] border border-white/5 rounded-2xl p-4">
                    <p class="text-[9px] font-black uppercase tracking-widest text-gray-500">{{ $role->label }}</p>
                    <p class="text-2xl font-black text-white mt-1">{{ $count }}</p>
                    <p class="text-[9px] text-gray-600 mt-0.5">{{ $role->description }}</p>
                </div>
            @endforeach
        </div>

        {{-- TABLA DE USUARIOS --}}
        <div class="bg-[#0d121f] border border-white/5 rounded-2xl overflow-hidden">
            <div class="px-6 py-4 border-b border-white/5 flex items-center justify-between">
                <h3 class="text-[11px] font-black uppercase tracking-[0.2em] text-gray-400">Usuarios del Sistema</h3>
                <button @click="showCreate = true"
                    class="bg-yellow-500 hover:bg-yellow-400 text-black px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all flex items-center gap-2">
                    <i class="fas fa-plus"></i> Nuevo Usuario
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="text-[10px] uppercase tracking-widest text-gray-500 border-b border-white/5">
                            <th class="px-6 py-4 font-black">Usuario</th>
                            <th class="px-6 py-4 font-black">Email</th>
                            <th class="px-6 py-4 font-black text-center">Rol</th>
                            <th class="px-6 py-4 font-black text-center">Acceso</th>
                            <th class="px-6 py-4 font-black text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/[0.03]">
                        @foreach($users as $user)
                            <tr class="hover:bg-white/[0.01] transition-colors group">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="h-9 w-9 rounded-xl bg-yellow-500/10 border border-yellow-500/20 flex items-center justify-center text-yellow-500 font-black text-sm">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <p class="text-xs font-bold text-white">{{ $user->name }}</p>
                                            @if($user->id === auth()->id())
                                                <span class="text-[9px] text-yellow-500 font-bold">← Tú</span>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-xs text-gray-400">{{ $user->email }}</td>
                                <td class="px-6 py-4 text-center">
                                    @if($user->role)
                                        @php
                                            $colors = [
                                                'admin'      => 'bg-red-500/10 text-red-400 border-red-500/20',
                                                'gerencia'   => 'bg-purple-500/10 text-purple-400 border-purple-500/20',
                                                'finanzas'   => 'bg-blue-500/10 text-blue-400 border-blue-500/20',
                                                'operaciones'=> 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20',
                                                'comercial'  => 'bg-orange-500/10 text-orange-400 border-orange-500/20',
                                            ];
                                            $cls = $colors[$user->role->name] ?? 'bg-zinc-500/10 text-zinc-400 border-zinc-500/20';
                                        @endphp
                                        <span class="px-2 py-1 rounded-lg text-[9px] font-black uppercase tracking-wider border {{ $cls }}">
                                            {{ $user->role->label }}
                                        </span>
                                    @else
                                        <span class="text-[9px] text-gray-600 font-bold">Sin rol</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @php
                                        $modules = [
                                            'admin'       => ['Dashboard','Clientes','Programación','Logística','Facturación','Cartera','Pagos','Gastos','Contabilidad','Configuración'],
                                            'gerencia'    => ['Dashboard','Clientes','Programación','Facturación','Cartera','Pagos','Gastos','Contabilidad'],
                                            'finanzas'    => ['Dashboard','Clientes','Facturación','Cartera','Pagos','Gastos','Contabilidad'],
                                            'operaciones' => ['Dashboard','Programación','Logística'],
                                            'comercial'   => ['Dashboard','Clientes','Programación','Facturación'],
                                        ];
                                        $userModules = $modules[$user->role?->name] ?? [];
                                    @endphp
                                    <div class="flex flex-wrap gap-1 justify-center max-w-[200px] mx-auto">
                                        @foreach($userModules as $mod)
                                            <span class="text-[8px] px-1.5 py-0.5 bg-white/5 rounded text-gray-500">{{ $mod }}</span>
                                        @endforeach
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <button
                                            @click="editUser = {{ json_encode(['id'=>$user->id,'name'=>$user->name,'email'=>$user->email,'role_id'=>$user->role_id]) }}"
                                            class="h-8 w-8 rounded-lg bg-white/5 hover:bg-yellow-500/20 border border-white/10 flex items-center justify-center text-gray-400 hover:text-yellow-500 transition-all">
                                            <i class="fas fa-pen text-[10px]"></i>
                                        </button>
                                        @if($user->id !== auth()->id())
                                            <form method="POST" action="{{ route('admin.users.destroy', $user) }}"
                                                onsubmit="return confirm('¿Eliminar usuario {{ $user->name }}?')">
                                                @csrf @method('DELETE')
                                                <button type="submit"
                                                    class="h-8 w-8 rounded-lg bg-white/5 hover:bg-red-500/20 border border-white/10 flex items-center justify-center text-gray-400 hover:text-red-500 transition-all">
                                                    <i class="fas fa-trash text-[10px]"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- TABLA DE ROLES/PERMISOS --}}
        <div class="bg-[#0d121f] border border-white/5 rounded-2xl overflow-hidden">
            <div class="px-6 py-4 border-b border-white/5">
                <h3 class="text-[11px] font-black uppercase tracking-[0.2em] text-gray-400">Matriz de Acceso por Rol</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-[10px]">
                    <thead>
                        <tr class="border-b border-white/5 text-gray-500 uppercase tracking-widest">
                            <th class="px-6 py-3 font-black">Módulo</th>
                            <th class="px-4 py-3 font-black text-center text-red-400">Admin</th>
                            <th class="px-4 py-3 font-black text-center text-purple-400">Gerencia</th>
                            <th class="px-4 py-3 font-black text-center text-blue-400">Finanzas</th>
                            <th class="px-4 py-3 font-black text-center text-emerald-400">Operaciones</th>
                            <th class="px-4 py-3 font-black text-center text-orange-400">Comercial</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/[0.03]">
                        @php
                        $matrix = [
                            'Dashboard'         => [1,1,1,1,1],
                            'Clientes'          => [1,1,1,0,1],
                            'Programación'      => [1,1,0,1,1],
                            'Logística / Rutas' => [1,0,0,1,0],
                            'Facturación'       => [1,1,1,0,1],
                            'Cartera'           => [1,1,1,0,0],
                            'Pagos'             => [1,1,1,0,0],
                            'Gastos'            => [1,1,1,0,0],
                            'Contabilidad'      => [1,1,1,0,0],
                            'Configuración'     => [1,0,0,0,0],
                            'Gestión Usuarios'  => [1,0,0,0,0],
                        ];
                        @endphp
                        @foreach($matrix as $mod => $access)
                            <tr class="hover:bg-white/[0.01]">
                                <td class="px-6 py-3 font-bold text-white">{{ $mod }}</td>
                                @foreach($access as $has)
                                    <td class="px-4 py-3 text-center">
                                        @if($has)
                                            <i class="fas fa-check text-emerald-500"></i>
                                        @else
                                            <i class="fas fa-times text-white/10"></i>
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- MODAL CREAR --}}
        <div x-show="showCreate" x-cloak
            class="fixed inset-0 bg-black/70 backdrop-blur-sm z-50 flex items-center justify-center p-4"
            @click.self="showCreate = false">
            <div class="bg-[#0d121f] border border-white/10 rounded-3xl p-8 w-full max-w-md shadow-2xl">
                <h3 class="text-lg font-black uppercase tracking-tighter mb-6">Nuevo <span class="text-yellow-500">Usuario</span></h3>
                <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-widest text-gray-500 mb-2">Nombre Completo</label>
                        <input type="text" name="name" required
                            class="w-full px-4 py-3 rounded-xl bg-black/40 border border-white/10 text-sm text-white outline-none focus:border-yellow-500/50">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-widest text-gray-500 mb-2">Email</label>
                        <input type="email" name="email" required
                            class="w-full px-4 py-3 rounded-xl bg-black/40 border border-white/10 text-sm text-white outline-none focus:border-yellow-500/50">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-widest text-gray-500 mb-2">Contraseña</label>
                        <input type="password" name="password" required
                            class="w-full px-4 py-3 rounded-xl bg-black/40 border border-white/10 text-sm text-white outline-none focus:border-yellow-500/50">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-widest text-gray-500 mb-2">Rol</label>
                        <select name="role_id" required
                            class="w-full px-4 py-3 rounded-xl bg-black/40 border border-white/10 text-sm text-white outline-none focus:border-yellow-500/50">
                            <option value="">Seleccionar rol...</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}">{{ $role->label }} — {{ $role->description }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex gap-3 pt-2">
                        <button type="button" @click="showCreate = false"
                            class="flex-1 py-3 rounded-xl border border-white/10 text-gray-400 text-[10px] font-black uppercase">Cancelar</button>
                        <button type="submit"
                            class="flex-1 py-3 rounded-xl bg-yellow-500 text-black text-[10px] font-black uppercase tracking-widest">Crear Usuario</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- MODAL EDITAR --}}
        <div x-show="editUser" x-cloak
            class="fixed inset-0 bg-black/70 backdrop-blur-sm z-50 flex items-center justify-center p-4"
            @click.self="editUser = null">
            <div class="bg-[#0d121f] border border-white/10 rounded-3xl p-8 w-full max-w-md shadow-2xl">
                <h3 class="text-lg font-black uppercase tracking-tighter mb-6">Editar <span class="text-yellow-500">Usuario</span></h3>
                <template x-if="editUser">
                    <form method="POST" :action="`/admin/users/${editUser.id}`" class="space-y-4">
                        @csrf @method('PUT')
                        <input type="hidden" name="_method" value="PUT">
                        <div>
                            <label class="block text-[10px] font-black uppercase tracking-widest text-gray-500 mb-2">Nombre Completo</label>
                            <input type="text" name="name" :value="editUser.name" required
                                class="w-full px-4 py-3 rounded-xl bg-black/40 border border-white/10 text-sm text-white outline-none focus:border-yellow-500/50">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black uppercase tracking-widest text-gray-500 mb-2">Email</label>
                            <input type="email" name="email" :value="editUser.email" required
                                class="w-full px-4 py-3 rounded-xl bg-black/40 border border-white/10 text-sm text-white outline-none focus:border-yellow-500/50">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black uppercase tracking-widest text-gray-500 mb-2">Nueva Contraseña <span class="text-gray-600">(dejar en blanco para no cambiar)</span></label>
                            <input type="password" name="password"
                                class="w-full px-4 py-3 rounded-xl bg-black/40 border border-white/10 text-sm text-white outline-none focus:border-yellow-500/50">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black uppercase tracking-widest text-gray-500 mb-2">Rol</label>
                            <select name="role_id" required
                                class="w-full px-4 py-3 rounded-xl bg-black/40 border border-white/10 text-sm text-white outline-none focus:border-yellow-500/50">
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}" :selected="editUser.role_id == {{ $role->id }}">
                                        {{ $role->label }} — {{ $role->description }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex gap-3 pt-2">
                            <button type="button" @click="editUser = null"
                                class="flex-1 py-3 rounded-xl border border-white/10 text-gray-400 text-[10px] font-black uppercase">Cancelar</button>
                            <button type="submit"
                                class="flex-1 py-3 rounded-xl bg-yellow-500 text-black text-[10px] font-black uppercase tracking-widest">Guardar</button>
                        </div>
                    </form>
                </template>
            </div>
        </div>

    </div>
</x-app-layout>
