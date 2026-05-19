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
            <div class="flex items-center gap-4">
                <div class="bg-yellow-500/10 p-3 rounded-xl border border-yellow-500/20">
                    <i class="fas fa-file-lines text-yellow-500 text-xl"></i>
                </div>
                <div>
                    <h2 class="text-xl font-black text-white uppercase tracking-tight">
                        Vista previa de <span class="text-yellow-500">Documento</span>
                    </h2>
                    <p class="text-[10px] text-gray-500 uppercase tracking-widest">
                        {{ $document->original_name }}
                    </p>
                </div>
            </div>

            <a href="{{ url()->previous() }}"
               class="text-xs font-black text-gray-400 hover:text-white uppercase tracking-widest">
                Volver
            </a>
        </div>
    </x-slot>

    <div class="py-10 max-w-6xl mx-auto px-6">

        <div class="bg-[#0f1420] border border-white/10 rounded-3xl shadow-2xl overflow-hidden">

            {{-- META --}}
            <div class="p-6 border-b border-white/5 flex flex-wrap gap-6 text-[11px] text-gray-400 uppercase tracking-widest font-bold">
                <div>
                    Tipo:
                    <span class="text-white ml-1">{{ $document->document_type }}</span>
                </div>
                <div>
                    MIME:
                    <span class="text-white ml-1">{{ $document->mime_type }}</span>
                </div>
                <div>
                    Pedido:
                    <span class="text-yellow-500 ml-1">#{{ $document->poultry_order_schedule_id }}</span>
                </div>
            </div>

            {{-- CONTENIDO --}}
            <div class="bg-black">

                @if (str_contains($document->mime_type, 'pdf'))
                    {{-- PDF EMBEBIDO --}}
                    <iframe
                        src="{{ $url }}"
                        class="w-full h-[80vh]"
                        style="border: none;"
                    ></iframe>

                @elseif (str_contains($document->mime_type, 'image'))
                    {{-- IMAGEN --}}
                    <div class="flex justify-center bg-black p-6">
                        <img src="{{ $url }}"
                             alt="{{ $document->original_name }}"
                             class="max-h-[80vh] object-contain rounded-xl shadow-xl">
                    </div>
                @else
                    {{-- FALLBACK --}}
                    <div class="p-10 text-center text-gray-400">
                        <i class="fas fa-triangle-exclamation text-2xl mb-3"></i>
                        <p class="text-sm">
                            Este tipo de archivo no se puede previsualizar.
                        </p>
                        <a href="{{ $url }}"
                           target="_blank"
                           class="text-yellow-500 font-bold uppercase text-xs tracking-widest mt-4 inline-block">
                            Descargar archivo
                        </a>
                    </div>
                @endif

            </div>
        </div>
    </div>
</x-app-layout>
