{{--
|--------------------------------------------------------------------------
| TIZZILA APP - © 2026 Tizzila App · Orquestación Avícola Inteligente
|--------------------------------------------------------------------------
| Proyecto privado desarrollado por:
| Ingeniero Jhoan Romero Rivera
| LinkedIn: https://linkedin.com/in/jmromeror87
|--------------------------------------------------------------------------
--}}

@props(['index', 'accounts'])

<tr class="group hover:bg-white/[0.02] transition-colors">
    <td class="px-4 py-3">
        <select name="lines[{{ $index }}][account_id]" 
                class="w-full bg-transparent border-none text-white text-xs font-bold focus:ring-0 cursor-pointer" required>
            <option value="" class="bg-[#0a0a0c]">Seleccionar Cuenta...</option>
            @foreach($accounts as $account)
                <option value="{{ $account->id }}" class="bg-[#0a0a0c]">
                    {{ $account->code }} - {{ $account->name }}
                </option>
            @endforeach
        </select>
    </td>
    <td class="px-4 py-3">
        <input type="text" name="lines[{{ $index }}][description]" placeholder="Opcional..."
               class="w-full bg-transparent border-none text-zinc-400 text-xs focus:ring-0 placeholder:text-zinc-800">
    </td>
    <td class="px-4 py-3">
        <input type="number" step="0.01" name="lines[{{ $index }}][debit]" value="0"
               class="w-full bg-transparent border-none text-right font-mono font-black text-sm text-white focus:ring-0 debit-input">
    </td>
    <td class="px-4 py-3">
        <input type="number" step="0.01" name="lines[{{ $index }}][credit]" value="0"
               class="w-full bg-transparent border-none text-right font-mono font-black text-sm text-white focus:ring-0 credit-input">
    </td>
    <td class="px-4 py-3 text-center text-zinc-800">
        {{-- Primera fila usualmente no se borra, o puedes dejar el icono --}}
        <i class="fas fa-lock text-[10px] opacity-20"></i>
    </td>
</tr>