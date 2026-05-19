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
                    Permisos de <span class="text-yellow-500">Acceso</span>
                </h2>
                <p class="text-[10px] text-gray-500 uppercase tracking-[0.3em] font-bold mt-1">
                    Matriz de módulos por rol — edición en tiempo real
                </p>
            </div>
            <a href="{{ route('admin.users.index') }}"
               class="text-[10px] font-black uppercase tracking-widest text-gray-500 hover:text-white flex items-center gap-2">
                <i class="fas fa-arrow-left text-[9px]"></i> Usuarios
            </a>
        </div>
    </x-slot>

    <div class="py-4 space-y-6" x-data="{ showNewModule: false }">

        {{-- NAV TABS --}}
        <div class="flex gap-2 border-b border-white/5 pb-4">
            <a href="{{ route('admin.users.index') }}"
               class="px-4 py-2 rounded-xl bg-white/5 border border-white/10 text-gray-400 hover:text-white text-[10px] font-black uppercase tracking-widest transition-all">
                <i class="fas fa-users mr-2"></i>Usuarios
            </a>
            <a href="{{ route('admin.permissions.index') }}"
               class="px-4 py-2 rounded-xl bg-yellow-500 text-black text-[10px] font-black uppercase tracking-widest">
                <i class="fas fa-shield-alt mr-2"></i>Permisos & Roles
            </a>
        </div>

        @if(session('success'))
            <div class="bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-xs font-bold px-5 py-3 rounded-xl flex items-center gap-3">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif

        {{-- AVISO --}}
        <div class="bg-yellow-500/5 border border-yellow-500/10 rounded-2xl px-5 py-4 flex gap-4 items-start">
            <i class="fas fa-shield-alt text-yellow-500 mt-0.5"></i>
            <div>
                <p class="text-[10px] font-black uppercase tracking-widest text-yellow-500">El rol Admin siempre tiene acceso total</p>
                <p class="text-xs text-yellow-500/60 mt-0.5">Los cambios aplican inmediatamente al siguiente request del usuario.</p>
            </div>
        </div>

        {{-- MATRIZ EDITABLE --}}
        <form method="POST" action="{{ route('admin.permissions.update') }}">
            @csrf

            <div class="bg-[#0d121f] border border-white/5 rounded-2xl overflow-hidden">
                <div class="px-6 py-4 border-b border-white/5 flex items-center justify-between">
                    <h3 class="text-[11px] font-black uppercase tracking-[0.2em] text-gray-400">
                        <i class="fas fa-table-cells text-yellow-500 mr-2"></i>Matriz de Acceso
                    </h3>
                    <button type="submit"
                        class="bg-yellow-500 hover:bg-yellow-400 text-black px-5 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all flex items-center gap-2">
                        <i class="fas fa-save"></i> Guardar Cambios
                    </button>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-white/5">
                                <th class="px-6 py-4 text-left text-[10px] font-black uppercase tracking-widest text-gray-500 w-48">
                                    Módulo
                                </th>
                                {{-- Admin fijo --}}
                                <th class="px-4 py-4 text-center w-24">
                                    <div class="flex flex-col items-center gap-1">
                                        <div class="h-8 w-8 rounded-xl bg-red-500/10 border border-red-500/20 flex items-center justify-center">
                                            <i class="fas fa-crown text-red-400 text-xs"></i>
                                        </div>
                                        <span class="text-[9px] font-black uppercase text-red-400">Admin</span>
                                    </div>
                                </th>
                                @foreach($roles->where('name','!=','admin') as $role)
                                    @php
                                        $colors = [
                                            'gerencia'    => ['bg-purple-500/10','border-purple-500/20','text-purple-400'],
                                            'finanzas'    => ['bg-blue-500/10','border-blue-500/20','text-blue-400'],
                                            'operaciones' => ['bg-emerald-500/10','border-emerald-500/20','text-emerald-400'],
                                            'comercial'   => ['bg-orange-500/10','border-orange-500/20','text-orange-400'],
                                        ];
                                        [$bg, $border, $text] = $colors[$role->name] ?? ['bg-zinc-500/10','border-zinc-500/20','text-zinc-400'];
                                    @endphp
                                    <th class="px-4 py-4 text-center w-28">
                                        <div class="flex flex-col items-center gap-1">
                                            <div class="h-8 w-8 rounded-xl {{ $bg }} border {{ $border }} flex items-center justify-center">
                                                <i class="fas fa-user text-xs {{ $text }}"></i>
                                            </div>
                                            <span class="text-[9px] font-black uppercase {{ $text }}">{{ $role->label }}</span>
                                        </div>
                                    </th>
                                @endforeach
                                <th class="px-4 py-4 w-12"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/[0.03]">
                            @foreach($modules as $module)
                                <tr class="hover:bg-white/[0.01] transition-colors group">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="h-8 w-8 rounded-xl bg-white/[0.03] border border-white/5 flex items-center justify-center">
                                                <i class="fas {{ $module->icon }} text-gray-500 text-xs"></i>
                                            </div>
                                            <span class="text-sm font-bold text-white">{{ $module->label }}</span>
                                        </div>
                                    </td>

                                    {{-- Admin — siempre marcado, no editable --}}
                                    <td class="px-4 py-4 text-center">
                                        <div class="flex justify-center">
                                            <div class="h-6 w-6 rounded-lg bg-red-500/20 border border-red-500/30 flex items-center justify-center">
                                                <i class="fas fa-check text-red-400 text-[10px]"></i>
                                            </div>
                                        </div>
                                    </td>

                                    @foreach($roles->where('name','!=','admin') as $role)
                                        @php
                                            $has = $role->permissions->contains('module', $module->slug);
                                            [$bg, $border, $text] = $colors[$role->name] ?? ['bg-zinc-500/10','border-zinc-500/20','text-zinc-400'];
                                        @endphp
                                        <td class="px-4 py-4 text-center">
                                            <div class="flex justify-center" x-data="{ on: {{ $has ? 'true' : 'false' }} }">
                                                <input type="hidden"
                                                    name="permissions[{{ $role->id }}][{{ $module->slug }}]"
                                                    :value="on ? '1' : '0'">
                                                <button type="button" @click="on = !on"
                                                    :class="on
                                                        ? 'bg-emerald-500/20 border-emerald-500/40 text-emerald-400 shadow-[0_0_12px_rgba(52,211,153,0.2)]'
                                                        : 'bg-white/[0.02] border-white/10 text-white/10 hover:border-white/20'"
                                                    class="h-8 w-8 rounded-xl border flex items-center justify-center transition-all">
                                                    <i class="fas text-[11px]"
                                                        :class="on ? 'fa-check' : 'fa-times'"></i>
                                                </button>
                                            </div>
                                        </td>
                                    @endforeach

                                    {{-- Eliminar módulo (solo si no es core) --}}
                                    <td class="px-4 py-4 text-center">
                                        @php $core = ['dashboard','clientes','programacion','logistica','facturacion','cartera','pagos','gastos','contabilidad','configuracion','usuarios']; @endphp
                                        @if(!in_array($module->slug, $core))
                                            <form method="POST" action="{{ route('admin.permissions.modules.destroy', $module) }}"
                                                onsubmit="return confirm('¿Eliminar módulo {{ $module->label }}?')">
                                                @csrf @method('DELETE')
                                                <button type="submit"
                                                    class="h-7 w-7 rounded-lg bg-red-500/10 border border-red-500/20 flex items-center justify-center text-red-400 hover:bg-red-500/20 transition-all mx-auto opacity-0 group-hover:opacity-100">
                                                    <i class="fas fa-trash text-[9px]"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="px-6 py-4 border-t border-white/5 flex justify-end">
                    <button type="submit"
                        class="bg-yellow-500 hover:bg-yellow-400 text-black px-8 py-3 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all flex items-center gap-2 shadow-[0_10px_25px_rgba(234,179,8,0.25)]">
                        <i class="fas fa-save"></i> Guardar Todos los Cambios
                    </button>
                </div>
            </div>
        </form>

        {{-- AGREGAR MÓDULO --}}
        <div class="bg-[#0d121f] border border-white/5 rounded-2xl overflow-hidden">
            <div class="px-6 py-4 border-b border-white/5 flex items-center justify-between">
                <h3 class="text-[11px] font-black uppercase tracking-[0.2em] text-gray-400">
                    <i class="fas fa-plus-circle text-yellow-500 mr-2"></i>Agregar Nuevo Módulo
                </h3>
                <button @click="showNewModule = !showNewModule" type="button"
                    class="text-[9px] font-black uppercase tracking-widest text-gray-500 hover:text-yellow-500 transition-colors">
                    <span x-text="showNewModule ? 'Cancelar' : 'Expandir'"></span>
                </button>
            </div>

            <div x-show="showNewModule" x-transition class="p-6">
                <form method="POST" action="{{ route('admin.permissions.modules.store') }}"
                    class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                    @csrf
                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-widest text-gray-500 mb-2">Nombre del Módulo</label>
                        <input type="text" name="label" required placeholder="Ej: Reportes Gerenciales"
                            class="w-full px-4 py-3 rounded-xl bg-black/40 border border-white/10 text-sm text-white outline-none focus:border-yellow-500/50">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-widest text-gray-500 mb-2">
                            Icono FontAwesome
                            <a href="https://fontawesome.com/icons" target="_blank" class="text-yellow-500 normal-case ml-1">ver iconos ↗</a>
                        </label>
                        <input type="text" name="icon" required placeholder="fa-chart-bar"
                            class="w-full px-4 py-3 rounded-xl bg-black/40 border border-white/10 text-sm text-white outline-none focus:border-yellow-500/50">
                    </div>
                    <button type="submit"
                        class="w-full py-3 bg-yellow-500 hover:bg-yellow-400 text-black rounded-xl text-[10px] font-black uppercase tracking-widest transition-all flex items-center justify-center gap-2">
                        <i class="fas fa-plus"></i> Crear Módulo
                    </button>
                </form>
                <p class="text-[9px] text-gray-600 mt-3">
                    El módulo se crea sin acceso para ningún rol. Actívalo manualmente en la matriz de arriba.
                </p>
            </div>
        </div>

    </div>
</x-app-layout>
