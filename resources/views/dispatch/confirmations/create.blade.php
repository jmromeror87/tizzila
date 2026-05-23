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
    <div class="min-h-screen bg-[#050507] text-zinc-300 px-4 py-8 font-sans">
        <div class="max-w-md mx-auto">

            {{-- HEADER --}}
            <div class="mb-8 flex justify-between items-end">
                <div>
                    <h1 class="text-2xl font-black text-white uppercase tracking-tight">
                        Confirmar Entrega
                    </h1>
                    <p class="text-xs text-zinc-500 uppercase mt-1 tracking-widest">
                        Operación Logística
                    </p>
                </div>
                <div class="bg-white/5 px-3 py-1 rounded-full border border-white/10">
                    <p class="text-[10px] text-zinc-400 uppercase font-bold">
                        Parada #{{ $stop->stop_order }}
                    </p>
                </div>
            </div>

            <div class="bg-[#0f0f14] border border-white/5 rounded-3xl p-6 space-y-6 shadow-2xl">

                {{-- INFO CLIENTE --}}
                <div class="space-y-1">
                    <p class="text-[10px] text-zinc-500 uppercase font-black tracking-tighter">
                        Cliente Destinatario
                    </p>
                    <p class="text-white font-bold text-lg leading-tight">
                        {{ $stop->customer->name }}
                    </p>
                    <div class="flex items-start gap-2 mt-1">
                        <svg class="w-4 h-4 text-zinc-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <p class="text-xs text-zinc-500 italic">
                            {{ $stop->customer_address }}
                        </p>
                    </div>
                </div>

                {{-- CANTIDAD TRANSPORTADA --}}
                <div class="bg-gradient-to-b from-zinc-900 to-black border border-white/5 rounded-2xl p-5 text-center shadow-inner">
                    <p class="text-[10px] uppercase font-black text-zinc-500 tracking-widest">
                        Carga en Manifiesto
                    </p>
                    <p class="text-4xl font-black text-yellow-500 mt-1 drop-shadow-[0_0_15px_rgba(234,179,8,0.2)]">
                        {{ number_format($stop->chicks_quantity) }}
                    </p>
                    <p class="text-[10px] text-zinc-600 font-bold uppercase mt-1">Unidades</p>
                </div>

                <form method="POST"
                      action="{{ route('dispatch.stops.confirm.store', $stop) }}"
                      enctype="multipart/form-data"
                      id="confirm-form"
                      class="space-y-5">

                    @csrf

                    <input type="hidden" id="base_quantity" value="{{ $stop->chicks_quantity }}">

                    <div class="grid grid-cols-2 gap-4">
                        {{-- MUERTOS --}}
                        <div>
                            <label class="text-[10px] uppercase font-black text-zinc-500 ml-1">
                                Bajas (Muertos)
                            </label>
                            <input type="number" style="appearance:none;-moz-appearance:textfield;-webkit-appearance:none"
                                   name="dead_quantity"
                                   id="dead_quantity"
                                   value="0"
                                   min="0"
                                   class="w-full mt-1 bg-zinc-900 border border-white/10 rounded-xl px-4 py-3 text-white font-bold text-xl focus:border-red-500/50 focus:ring-0 transition-colors">
                        </div>

                        {{-- RECIBIDOS (AUTO CALCULADO) --}}
                        <div>
                            <label class="text-[10px] uppercase font-black text-zinc-500 ml-1">
                                Total Recibidos
                            </label>
                            <input type="number" style="appearance:none;-moz-appearance:textfield;-webkit-appearance:none"
                                   name="received_quantity"
                                   id="received_quantity"
                                   readonly
                                   class="w-full mt-1 bg-black border border-yellow-500/20 rounded-xl px-4 py-3 text-yellow-500 font-black text-xl pointer-events-none">
                        </div>
                    </div>

                    {{-- MENSAJE DE ERROR --}}
                    <div id="warning_box" class="hidden animate-pulse bg-red-500/10 border border-red-500/20 p-3 rounded-lg text-red-500 text-[10px] font-bold uppercase text-center">
                        ⚠ Error: Excede la cantidad transportada
                    </div>

                    {{-- EVIDENCIAS --}}
                    <div class="space-y-2">
                        <label class="text-[10px] uppercase font-black text-zinc-500 ml-1">
                            Evidencias Fotográficas
                        </label>
                        <div class="relative group">
                            <input type="file"
                                   name="evidences[]"
                                   multiple
                                   accept="image/*,image/heic,image/heif"
                                   class="block w-full text-xs text-zinc-500
                                          file:mr-4 file:py-2 file:px-4
                                          file:rounded-full file:border-0
                                          file:text-[10px] file:font-black file:uppercase
                                          file:bg-zinc-800 file:text-zinc-300
                                          hover:file:bg-zinc-700 cursor-pointer">
                        </div>
                    </div>

                    {{-- FIRMA --}}
                    <div class="space-y-2">
                        <div class="flex justify-between items-center px-1">
                            <label class="text-[10px] uppercase font-black text-zinc-500">
                                Firma del Cliente
                            </label>
                            <button type="button"
                                    onclick="clearSignature()"
                                    class="text-[10px] text-red-500/70 font-bold uppercase hover:text-red-400 transition-colors">
                                Borrar
                            </button>
                        </div>

                        <div class="relative bg-white rounded-2xl overflow-hidden shadow-lg border-2 border-transparent focus-within:border-yellow-500/50 transition-all">
                            <canvas id="signature-pad" class="w-full h-44 touch-none cursor-crosshair"></canvas>
                        </div>

                        <input type="hidden" name="signature" id="signature-input">
                    </div>

                    {{-- NOTAS --}}
                    <div>
                        <label class="text-[10px] uppercase font-black text-zinc-500 ml-1">
                            Observaciones de Entrega
                        </label>
                        <textarea name="notes"
                                  rows="2"
                                  placeholder="Escribe detalles adicionales aquí..."
                                  class="w-full mt-1 bg-zinc-900 border border-white/10 rounded-xl px-4 py-3 text-sm text-zinc-300 focus:border-yellow-500/50 focus:ring-0 placeholder:text-zinc-700 transition-all"></textarea>
                    </div>

                    {{-- GEO --}}
                    <input type="hidden" name="latitude" id="latitude">
                    <input type="hidden" name="longitude" id="longitude">

                    <button type="submit"
                            id="submit-btn"
                            class="w-full mt-4 bg-yellow-500 hover:bg-yellow-400 active:scale-[0.98] text-black font-black uppercase py-5 rounded-2xl shadow-lg shadow-yellow-500/10 transition-all flex items-center justify-center gap-2 group">
                        <span>Confirmar Entrega</span>
                        <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </button>

                </form>
            </div>
        </div>
    </div>

    {{-- Scripts --}}
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>

    <script>
        const baseQuantity = parseInt(document.getElementById('base_quantity').value);
        const deadInput = document.getElementById('dead_quantity');
        const receivedInput = document.getElementById('received_quantity');
        const warningBox = document.getElementById('warning_box');
        const submitBtn = document.getElementById('submit-btn');

        // Lógica de cálculo
        function updateReceived() {
            let dead = parseInt(deadInput.value) || 0;

            if (dead < 0) {
                deadInput.value = 0;
                dead = 0;
            }

            if (dead > baseQuantity) {
                warningBox.classList.remove('hidden');
                receivedInput.value = 0;
                submitBtn.disabled = true;
                submitBtn.classList.add('opacity-50', 'grayscale');
            } else {
                warningBox.classList.add('hidden');
                receivedInput.value = baseQuantity - dead;
                submitBtn.disabled = false;
                submitBtn.classList.remove('opacity-50', 'grayscale');
            }
        }

        deadInput.addEventListener('input', updateReceived);
        updateReceived();

        // Inicialización Firma Responsiva
        const canvas = document.getElementById('signature-pad');
        const signaturePad = new SignaturePad(canvas, {
            backgroundColor: 'rgb(255, 255, 255)',
            penColor: 'rgb(0, 0, 0)'
        });

        function resizeCanvas() {
            const ratio = Math.max(window.devicePixelRatio || 1, 1);
            canvas.width = canvas.offsetWidth * ratio;
            canvas.height = canvas.offsetHeight * ratio;
            canvas.getContext("2d").scale(ratio, ratio);
            signaturePad.clear(); 
        }

        window.addEventListener("resize", resizeCanvas);
        resizeCanvas();

        function clearSignature() {
            signaturePad.clear();
        }

        document.getElementById('confirm-form').addEventListener('submit', function(e) {
            e.preventDefault();
            if (!signaturePad.isEmpty()) {
                document.getElementById('signature-input').value = signaturePad.toDataURL('image/png');
            }
            this.submit();
        });

        // Geolocalización
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(position => {
                document.getElementById('latitude').value = position.coords.latitude;
                document.getElementById('longitude').value = position.coords.longitude;
            }, (error) => {
                console.warn("Error obteniendo ubicación:", error.message);
            });
        }
    </script>
</x-app-layout>