@if ($paginator->hasPages())
<div class="flex items-center justify-between gap-4">

    {{-- Info --}}
    <p class="text-[10px] font-black text-zinc-600 uppercase tracking-widest hidden sm:block">
        Mostrando <span class="text-zinc-400">{{ $paginator->firstItem() }}–{{ $paginator->lastItem() }}</span>
        de <span class="text-zinc-400">{{ $paginator->total() }}</span> registros
    </p>

    {{-- Controles --}}
    <div class="flex items-center gap-1 ml-auto">

        {{-- Anterior --}}
        @if ($paginator->onFirstPage())
            <span class="h-8 w-8 flex items-center justify-center rounded-xl bg-white/5 border border-white/5 text-zinc-700 cursor-not-allowed">
                <i class="fas fa-chevron-left text-[9px]"></i>
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}"
               class="h-8 w-8 flex items-center justify-center rounded-xl bg-white/5 border border-white/10 text-zinc-400 hover:bg-yellow-500/10 hover:border-yellow-500/30 hover:text-yellow-500 transition-all">
                <i class="fas fa-chevron-left text-[9px]"></i>
            </a>
        @endif

        {{-- Páginas --}}
        @foreach ($elements as $element)
            @if (is_string($element))
                <span class="h-8 px-2 flex items-center text-zinc-600 text-[10px] font-black">···</span>
            @endif
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="h-8 min-w-[2rem] px-2 flex items-center justify-center rounded-xl bg-yellow-500 text-black text-[10px] font-black">
                            {{ $page }}
                        </span>
                    @else
                        <a href="{{ $url }}"
                           class="h-8 min-w-[2rem] px-2 flex items-center justify-center rounded-xl bg-white/5 border border-white/10 text-zinc-400 hover:bg-yellow-500/10 hover:border-yellow-500/30 hover:text-yellow-500 text-[10px] font-black transition-all">
                            {{ $page }}
                        </a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Siguiente --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}"
               class="h-8 w-8 flex items-center justify-center rounded-xl bg-white/5 border border-white/10 text-zinc-400 hover:bg-yellow-500/10 hover:border-yellow-500/30 hover:text-yellow-500 transition-all">
                <i class="fas fa-chevron-right text-[9px]"></i>
            </a>
        @else
            <span class="h-8 w-8 flex items-center justify-center rounded-xl bg-white/5 border border-white/5 text-zinc-700 cursor-not-allowed">
                <i class="fas fa-chevron-right text-[9px]"></i>
            </span>
        @endif

    </div>
</div>
@endif
