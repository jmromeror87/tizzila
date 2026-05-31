{{--
|--------------------------------------------------------------------------
| TIZZILA APP - © 2026 Tizzila App · Orquestación Avícola Inteligente
|--------------------------------------------------------------------------
| Proyecto privado desarrollado por:
| Ingeniero Jhoan Romero Rivera
| LinkedIn: https://linkedin.com/in/jmromeror87
|--------------------------------------------------------------------------
--}}

<div class="space-y-5">

    {{-- FLASH --}}
    @if(session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
             class="bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-xs font-black px-5 py-3 rounded-2xl flex items-center gap-3">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    {{-- KPIs --}}
    @php
        $total      = $documents->total();
        $processed  = $documents->getCollection()->where('processing_status', 'processed')->count();
        $pending    = $documents->getCollection()->whereIn('processing_status', ['pending','processing'])->count();
        $failed     = $documents->getCollection()->where('processing_status', 'failed')->count();
    @endphp

    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
        <div class="bg-[#0d121f] border border-white/5 rounded-2xl p-4">
            <p class="text-[9px] font-black uppercase tracking-widest text-zinc-600 mb-1">Total Documentos</p>
            <p class="text-3xl font-black text-white">{{ $total }}</p>
            <p class="text-[9px] text-zinc-600 mt-1 font-bold uppercase">
                {{ \Carbon\Carbon::create($year, $month)->translatedFormat('F Y') }}
            </p>
        </div>
        <div class="bg-[#0d121f] border border-emerald-500/10 rounded-2xl p-4">
            <p class="text-[9px] font-black uppercase tracking-widest text-zinc-600 mb-1">Procesados</p>
            <p class="text-3xl font-black text-emerald-400">{{ $processed }}</p>
            <p class="text-[9px] text-zinc-600 mt-1 font-bold uppercase">En esta página</p>
        </div>
        <div class="bg-[#0d121f] border border-amber-500/10 rounded-2xl p-4">
            <p class="text-[9px] font-black uppercase tracking-widest text-zinc-600 mb-1">Procesando</p>
            <p class="text-3xl font-black {{ $pending > 0 ? 'text-amber-400' : 'text-zinc-600' }}">{{ $pending }}</p>
            <p class="text-[9px] text-zinc-600 mt-1 font-bold uppercase flex items-center gap-1">
                @if($pending > 0) <span class="w-1 h-1 rounded-full bg-amber-500 animate-pulse"></span> @endif
                En cola IA
            </p>
        </div>
        <div class="bg-[#0d121f] border border-white/5 rounded-2xl p-4">
            <p class="text-[9px] font-black uppercase tracking-widest text-zinc-600 mb-1">Con error</p>
            <p class="text-3xl font-black {{ $failed > 0 ? 'text-red-400' : 'text-zinc-600' }}">{{ $failed }}</p>
            <p class="text-[9px] text-zinc-600 mt-1 font-bold uppercase">Revisar</p>
        </div>
    </div>

    {{-- TOOLBAR --}}
    <div class="bg-[#0d121f] border border-white/5 rounded-2xl p-4 flex flex-col md:flex-row gap-3 items-center">

        {{-- Búsqueda --}}
        <div class="relative flex-1 w-full">
            <i class="fas fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-zinc-600 text-xs"></i>
            <input wire:model.live.debounce.300ms="search" type="text"
                placeholder="Buscar por proveedor..."
                class="w-full pl-9 pr-4 py-2.5 bg-black/40 border border-white/10 rounded-xl text-xs font-bold text-white placeholder-zinc-700 outline-none focus:border-yellow-500/50 uppercase tracking-wide">
        </div>

        {{-- Navegación mes --}}
        <div class="flex items-center gap-2 shrink-0">
            <button wire:click="previousMonth"
                class="h-9 w-9 rounded-xl bg-black/40 border border-white/10 hover:border-yellow-500/30 hover:text-yellow-500 text-zinc-500 flex items-center justify-center transition-all">
                <i class="fas fa-chevron-left text-xs"></i>
            </button>
            <div class="text-center min-w-[110px]">
                <p class="text-xs font-black text-white uppercase">
                    {{ ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'][$month - 1] }}
                </p>
                <p class="text-[9px] font-black text-yellow-500/50">{{ $year }}</p>
            </div>
            <button wire:click="nextMonth"
                class="h-9 w-9 rounded-xl bg-black/40 border border-white/10 hover:border-yellow-500/30 hover:text-yellow-500 text-zinc-500 flex items-center justify-center transition-all">
                <i class="fas fa-chevron-right text-xs"></i>
            </button>
        </div>

        {{-- Selectores rápidos --}}
        <div class="flex items-center gap-2 shrink-0">
            <select wire:model.live="month"
                class="bg-black/40 border border-white/10 rounded-xl px-3 py-2.5 text-[10px] font-black text-white uppercase outline-none focus:border-yellow-500/50">
                @foreach(range(1, 12) as $m)
                    <option value="{{ $m }}">{{ \Carbon\Carbon::create()->month($m)->translatedFormat('M') }}</option>
                @endforeach
            </select>
            <select wire:model.live="year"
                class="bg-black/40 border border-white/10 rounded-xl px-3 py-2.5 text-[10px] font-black text-white outline-none focus:border-yellow-500/50">
                @foreach(range(now()->year - 2, now()->year + 1) as $y)
                    <option value="{{ $y }}">{{ $y }}</option>
                @endforeach
            </select>
        </div>

        {{-- Botón subir --}}
        <button wire:click="openUploadModal"
            class="h-9 px-5 bg-yellow-500 hover:bg-yellow-400 text-black font-black rounded-xl transition-all text-[10px] uppercase tracking-widest flex items-center gap-2 shrink-0 shadow-[0_8px_20px_rgba(234,179,8,0.2)]">
            <i class="fas fa-upload text-xs"></i> Ingresar Doc.
        </button>

        {{-- Loading bar --}}
        <div wire:loading class="absolute bottom-0 left-0 right-0 h-0.5 bg-yellow-500/50 animate-pulse rounded-full"></div>
    </div>

    {{-- TABLA DE DOCUMENTOS --}}
    <div class="bg-[#0d121f] border border-white/5 rounded-2xl overflow-hidden">
        <div class="px-6 py-4 border-b border-white/5 flex items-center justify-between">
            <h3 class="text-[11px] font-black uppercase tracking-[0.2em] text-gray-400">
                <i class="fas fa-folder-open text-yellow-500 mr-2"></i>Repositorio de Documentos
            </h3>
            <span class="text-[9px] font-black text-zinc-600 uppercase tracking-widest">
                {{ $documents->total() }} documento(s)
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-white/[0.04] text-[9px] font-black uppercase tracking-widest text-zinc-600">
                        <th class="px-6 py-3">Ref</th>
                        <th class="px-4 py-3">Proveedor</th>
                        <th class="px-4 py-3 text-center">Tipo</th>
                        <th class="px-4 py-3 text-center">Estado IA</th>
                        <th class="px-4 py-3">Fecha</th>
                        <th class="px-4 py-3 text-right w-20">Archivo</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/[0.03]">
                    @forelse($documents as $doc)
                    @php
                        $statusStyle = match($doc->processing_status) {
                            'processed'  => ['text-emerald-400 bg-emerald-500/10 border-emerald-500/20', 'Procesado',   'fa-check-circle'],
                            'processing' => ['text-blue-400 bg-blue-500/10 border-blue-500/20',          'Procesando',  'fa-circle-notch'],
                            'pending'    => ['text-amber-400 bg-amber-500/10 border-amber-500/20',       'En Cola',     'fa-clock'],
                            'failed'     => ['text-red-400 bg-red-500/10 border-red-500/20',             'Error',       'fa-triangle-exclamation'],
                            default      => ['text-zinc-500 bg-white/5 border-white/10',                 ucfirst($doc->processing_status ?? '—'), 'fa-question'],
                        };
                        $isPdf = str_ends_with(strtolower($doc->file_path ?? ''), '.pdf');
                        $ext   = strtoupper(pathinfo($doc->file_path ?? '', PATHINFO_EXTENSION)) ?: 'DOC';
                    @endphp
                    <tr class="hover:bg-white/[0.01] transition-colors group">
                        <td class="px-6 py-4">
                            <span class="font-mono text-[9px] text-zinc-600">#{{ str_pad($doc->id, 4, '0', STR_PAD_LEFT) }}</span>
                        </td>
                        <td class="px-4 py-4">
                            <div class="flex items-center gap-3">
                                <div class="h-8 w-8 rounded-lg bg-black/40 border border-white/5 group-hover:border-yellow-500/20 flex items-center justify-center shrink-0 transition-all">
                                    <i class="fas {{ $isPdf ? 'fa-file-pdf text-red-400' : 'fa-file-image text-blue-400' }} text-xs"></i>
                                </div>
                                <div>
                                    <p class="text-xs font-black text-white uppercase tracking-tight group-hover:text-yellow-500 transition-colors">
                                        {{ $doc->provider->business_name ?? '—' }}
                                    </p>
                                    @if($doc->original_name)
                                        <p class="text-[9px] text-zinc-600 font-mono mt-0.5 truncate max-w-[180px]">{{ $doc->original_name }}</p>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-4 text-center">
                            <span class="text-[9px] font-black uppercase px-2 py-0.5 rounded border border-white/10 bg-white/5 text-zinc-500 font-mono">
                                {{ $ext }}
                            </span>
                        </td>
                        <td class="px-4 py-4 text-center">
                            <span class="inline-flex items-center gap-1.5 text-[9px] font-black uppercase px-2.5 py-1 rounded-full border {{ $statusStyle[0] }}">
                                <i class="fas {{ $statusStyle[2] }} text-[8px] {{ $doc->processing_status === 'processing' ? 'animate-spin' : '' }}"></i>
                                {{ $statusStyle[1] }}
                            </span>
                        </td>
                        <td class="px-4 py-4">
                            <p class="text-xs text-zinc-400 font-bold">{{ $doc->created_at->translatedFormat('d M Y') }}</p>
                            <p class="text-[9px] text-zinc-600 font-mono">{{ $doc->created_at->format('H:i') }}</p>
                        </td>
                        <td class="px-4 py-4 text-right">
                            <a href="{{ Storage::url($doc->file_path) }}" target="_blank"
                               class="h-8 w-8 rounded-lg bg-white/5 border border-white/10 hover:bg-yellow-500/10 hover:border-yellow-500/20 hover:text-yellow-400 text-zinc-500 flex items-center justify-center transition-all ml-auto">
                                <i class="fas fa-external-link-alt text-[10px]"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-16 text-center">
                            <div class="flex flex-col items-center gap-3 opacity-20">
                                <i class="fas fa-folder-open text-4xl"></i>
                                <p class="text-[10px] font-black uppercase tracking-[0.4em]">Sin documentos en este período</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($documents->hasPages())
            <div class="px-6 py-4 border-t border-white/5 flex items-center justify-between">
                <button wire:click="previousPage" @disabled($documents->onFirstPage())
                    class="h-8 px-4 rounded-xl bg-black/40 border border-white/10 text-[10px] font-black uppercase tracking-widest text-zinc-500 hover:text-yellow-500 disabled:opacity-30 transition-all flex items-center gap-2">
                    <i class="fas fa-chevron-left text-[9px]"></i> Anterior
                </button>
                <span class="text-[9px] font-black text-zinc-600 uppercase tracking-widest">
                    Página {{ $documents->currentPage() }} de {{ $documents->lastPage() }}
                </span>
                <button wire:click="nextPage" @disabled(!$documents->hasMorePages())
                    class="h-8 px-4 rounded-xl bg-black/40 border border-white/10 text-[10px] font-black uppercase tracking-widest text-zinc-500 hover:text-yellow-500 disabled:opacity-30 transition-all flex items-center gap-2">
                    Siguiente <i class="fas fa-chevron-right text-[9px]"></i>
                </button>
            </div>
        @endif
    </div>

    {{-- MODAL UPLOAD --}}
    @if($showUploadModal)
    <div class="fixed inset-0 bg-black/80 backdrop-blur-sm z-50 flex items-center justify-center p-4"
         wire:click.self="$set('showUploadModal', false)">
        <div class="bg-[#0d121f] border border-white/10 rounded-2xl w-full max-w-md shadow-2xl overflow-hidden">

            <div class="h-0.5 w-full bg-gradient-to-r from-transparent via-yellow-500/50 to-transparent"></div>

            <div class="p-6 space-y-5">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-black text-white uppercase tracking-tight">Ingresar Documento</h3>
                        <p class="text-[9px] text-zinc-500 font-bold uppercase tracking-widest mt-0.5">Protocolo de Almacenamiento</p>
                    </div>
                    <button wire:click="$set('showUploadModal', false)"
                        class="h-8 w-8 rounded-xl bg-white/5 border border-white/10 hover:bg-white/10 text-zinc-500 hover:text-white flex items-center justify-center transition-all">
                        <i class="fas fa-times text-xs"></i>
                    </button>
                </div>

                <form wire:submit.prevent="cargar" class="space-y-4">

                    {{-- Proveedor --}}
                    <div>
                        <label class="block text-[9px] font-black uppercase tracking-widest text-zinc-500 mb-1.5">
                            Proveedor <span class="text-red-400">*</span>
                        </label>
                        <select wire:model.defer="provider_id"
                            class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-2.5 text-xs font-bold text-white outline-none focus:border-yellow-500/50 appearance-none">
                            <option value="">— Seleccionar Proveedor —</option>
                            @foreach($providers as $provider)
                                <option value="{{ $provider->id }}">{{ strtoupper($provider->business_name) }}</option>
                            @endforeach
                        </select>
                        @error('provider_id')
                            <p class="text-red-400 text-[9px] font-black mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Dropzone --}}
                    <div>
                        <label class="block text-[9px] font-black uppercase tracking-widest text-zinc-500 mb-1.5">
                            Archivo (PDF / JPG / PNG) <span class="text-red-400">*</span>
                        </label>
                        <label class="relative flex flex-col items-center justify-center h-28 border-2 border-dashed {{ $file ? 'border-yellow-500/40 bg-yellow-500/5' : 'border-white/10 bg-black/20' }} rounded-xl cursor-pointer hover:border-zinc-600 transition-all">
                            <input type="file" wire:model="file" class="absolute inset-0 opacity-0 cursor-pointer" accept=".pdf,.jpg,.jpeg,.png">
                            @if($file)
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-file-check text-yellow-500"></i>
                                    <span class="text-[10px] font-black text-yellow-500 truncate max-w-[200px]">{{ $file->getClientOriginalName() }}</span>
                                </div>
                                <p class="text-[8px] text-zinc-600 mt-1 font-bold uppercase">{{ number_format($file->getSize() / 1024, 0) }} KB</p>
                            @else
                                <i class="fas fa-cloud-upload-alt text-xl text-zinc-700"></i>
                                <p class="text-[9px] font-black uppercase tracking-widest text-zinc-600 mt-1">Arrastra o selecciona</p>
                                <p class="text-[8px] text-zinc-700 mt-0.5">Máx. 20 MB</p>
                            @endif
                        </label>

                        <div wire:loading wire:target="file" class="mt-2">
                            <div class="w-full bg-black/40 h-1 rounded-full overflow-hidden">
                                <div class="bg-yellow-500 h-full animate-pulse w-full rounded-full"></div>
                            </div>
                        </div>

                        @error('file')
                            <p class="text-red-400 text-[9px] font-black mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center gap-3 pt-2">
                        <button type="button" wire:click="$set('showUploadModal', false)"
                            class="flex-1 py-2.5 text-[9px] font-black uppercase tracking-widest text-zinc-600 hover:text-white transition-colors rounded-xl border border-white/5 hover:border-white/10">
                            Cancelar
                        </button>
                        <button type="submit"
                            wire:loading.attr="disabled"
                            class="flex-[2] py-2.5 bg-yellow-500 hover:bg-yellow-400 text-black font-black rounded-xl text-[10px] uppercase tracking-widest transition-all disabled:opacity-50 flex items-center justify-center gap-2">
                            <span wire:loading.remove wire:target="cargar">
                                <i class="fas fa-bolt"></i> Sincronizar
                            </span>
                            <span wire:loading wire:target="cargar" class="flex items-center gap-2">
                                <i class="fas fa-circle-notch animate-spin"></i> Procesando…
                            </span>
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
    @endif

</div>
