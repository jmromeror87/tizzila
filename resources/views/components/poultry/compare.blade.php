{{--
|--------------------------------------------------------------------------
| TIZZILA APP - © 2026 Tizzila App · Orquestación Avícola Inteligente
|--------------------------------------------------------------------------
| Proyecto privado desarrollado por:
| Ingeniero Jhoan Romero Rivera
| LinkedIn: https://linkedin.com/in/jmromeror87
|--------------------------------------------------------------------------
--}}

@props([
    'label',
    'left' => null,
    'right' => null,
])

@php
    // Normaliza valores para evitar falsos negativos
    $leftVal  = is_null($left)  ? '' : trim((string) $left);
    $rightVal = is_null($right) ? '' : trim((string) $right);

    $match = $leftVal === $rightVal;
@endphp

<div class="bg-white/5 border border-white/10 rounded-xl p-4">
    <p class="text-[10px] text-gray-400 uppercase font-black tracking-widest mb-2">
        {{ $label }}
    </p>

    <div class="grid grid-cols-[1fr_auto_1fr] items-center gap-4 text-xs">
        <span class="text-gray-400 truncate">
            {{ $leftVal !== '' ? $leftVal : '—' }}
        </span>

        <i class="fas {{ $match ? 'fa-check-circle text-emerald-500' : 'fa-exclamation-triangle text-yellow-500' }}"></i>

        <span class="text-white font-black truncate text-right">
            {{ $rightVal !== '' ? $rightVal : '—' }}
        </span>
    </div>
</div>
