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
                <a href="{{ route('customers.show', $customer) }}"
                   class="h-9 w-9 rounded-xl bg-white/5 border border-white/10 hover:bg-white/10 text-zinc-500 hover:text-white flex items-center justify-center transition-all">
                    <i class="fas fa-arrow-left text-xs"></i>
                </a>
                <div>
                    <h2 class="text-2xl font-black text-white tracking-tighter uppercase leading-none">
                        Editar <span class="text-yellow-500">Cliente</span>
                    </h2>
                    <p class="text-[9px] text-zinc-500 uppercase tracking-[0.3em] font-bold mt-1">
                        {{ $customer->name }}
                    </p>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-4">

        @if($errors->any())
            <div class="mb-5 bg-red-500/10 border border-red-500/20 text-red-400 text-xs font-bold px-5 py-4 rounded-2xl">
                <p class="font-black uppercase tracking-widest mb-2 flex items-center gap-2">
                    <i class="fas fa-triangle-exclamation"></i> Errores de Validación
                </p>
                <ul class="space-y-1">
                    @foreach($errors->all() as $error)
                        <li>· {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if($hasOverdueInvoices)
            <div class="mb-5 bg-amber-500/10 border border-amber-500/20 rounded-2xl px-5 py-4 flex items-center gap-3">
                <i class="fas fa-triangle-exclamation text-amber-400"></i>
                <p class="text-[10px] font-black text-amber-400 uppercase tracking-widest">
                    Este cliente tiene facturas vencidas. El cambio de plazo aplica solo a futuras facturas.
                </p>
            </div>
        @endif

        <form id="edit-form" action="{{ route('customers.update', $customer) }}" method="POST">
            @csrf @method('PUT')

            <div class="space-y-5">

                {{-- SECCIÓN 1: IDENTIFICACIÓN --}}
                <div class="bg-[#0d121f] border border-white/5 rounded-2xl overflow-hidden">
                    <div class="px-6 py-4 border-b border-white/5 flex items-center gap-3">
                        <span class="text-[9px] font-black text-yellow-500 bg-yellow-500/10 border border-yellow-500/20 px-2 py-0.5 rounded-lg">01</span>
                        <h3 class="text-[11px] font-black uppercase tracking-[0.2em] text-gray-400">Identificación Legal</h3>
                        <div class="ml-auto">
                            <div class="text-[9px] text-zinc-600 font-bold flex items-center gap-2">
                                <i class="fas fa-triangle-exclamation text-yellow-500/50 text-[8px]"></i>
                                Cambios deben coincidir con el RUT vigente
                            </div>
                        </div>
                    </div>
                    <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="md:col-span-3">
                            <label class="block text-[9px] font-black uppercase tracking-widest text-zinc-500 mb-1.5">Razón Social / Nombre <span class="text-red-400">*</span></label>
                            <input type="text" name="name" value="{{ old('name', $customer->name) }}" required
                                class="w-full px-4 py-3 rounded-xl bg-black/40 border border-white/10 text-sm font-bold text-white uppercase tracking-wide outline-none focus:border-yellow-500/50">
                        </div>

                        <div>
                            <label class="block text-[9px] font-black uppercase tracking-widest text-zinc-500 mb-1.5">Tipo Documento <span class="text-red-400">*</span></label>
                            <select id="doc_type" name="type_document_id"
                                class="w-full px-4 py-3 rounded-xl bg-black/40 border border-white/10 text-sm text-white outline-none focus:border-yellow-500/50">
                                <option value="31" @selected($customer->type_document_id == '31')>NIT</option>
                                <option value="13" @selected($customer->type_document_id == '13')>Cédula</option>
                                <option value="41" @selected($customer->type_document_id == '41')>Pasaporte</option>
                                <option value="22" @selected($customer->type_document_id == '22')>Tarjeta Identidad</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-[9px] font-black uppercase tracking-widest text-zinc-500 mb-1.5">Número Identificación <span class="text-red-400">*</span></label>
                            <input type="text" id="id_number" name="identification_number"
                                value="{{ old('identification_number', $customer->identification_number) }}" required
                                class="w-full px-4 py-3 rounded-xl bg-black/40 border border-white/10 text-sm font-mono text-white outline-none focus:border-yellow-500/50">
                        </div>

                        <div>
                            <label class="block text-[9px] font-black uppercase tracking-widest text-zinc-500 mb-1.5">DV</label>
                            <input type="text" id="dv_input" name="dv" readonly
                                value="{{ old('dv', $customer->dv) }}"
                                class="w-full px-4 py-3 rounded-xl bg-black/20 border border-white/5 text-sm font-black text-yellow-500 text-center cursor-not-allowed">
                        </div>
                    </div>
                </div>

                {{-- SECCIÓN 2: PERFIL TRIBUTARIO --}}
                <div class="bg-[#0d121f] border border-white/5 rounded-2xl overflow-hidden">
                    <div class="px-6 py-4 border-b border-white/5 flex items-center gap-3">
                        <span class="text-[9px] font-black text-yellow-500 bg-yellow-500/10 border border-yellow-500/20 px-2 py-0.5 rounded-lg">02</span>
                        <h3 class="text-[11px] font-black uppercase tracking-[0.2em] text-gray-400">Perfil Tributario DIAN</h3>
                    </div>
                    <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-[9px] font-black uppercase tracking-widest text-zinc-500 mb-1.5">Organización <span class="text-red-400">*</span></label>
                            <select name="type_organization_id"
                                class="w-full px-4 py-3 rounded-xl bg-black/40 border border-white/10 text-sm text-white outline-none focus:border-yellow-500/50">
                                <option value="1" @selected(old('type_organization_id', $customer->type_organization_id) == '1')>Persona Jurídica</option>
                                <option value="2" @selected(old('type_organization_id', $customer->type_organization_id) == '2')>Persona Natural</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-[9px] font-black uppercase tracking-widest text-zinc-500 mb-1.5">Régimen <span class="text-red-400">*</span></label>
                            <select name="type_regime_id"
                                class="w-full px-4 py-3 rounded-xl bg-black/40 border border-white/10 text-sm text-white outline-none focus:border-yellow-500/50">
                                <option value="48" @selected(old('type_regime_id', $customer->type_regime_id) == '48')>Responsable IVA</option>
                                <option value="49" @selected(old('type_regime_id', $customer->type_regime_id) == '49')>No Responsable</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-[9px] font-black uppercase tracking-widest text-zinc-500 mb-1.5">Responsabilidad Fiscal <span class="text-red-400">*</span></label>
                            <select name="type_liability_id"
                                class="w-full px-4 py-3 rounded-xl bg-black/40 border border-white/10 text-sm text-white outline-none focus:border-yellow-500/50">
                                <option value="R-99-PN" @selected(old('type_liability_id', $customer->type_liability_id) == 'R-99-PN')>R-99-PN (Persona Natural)</option>
                                <option value="O-13" @selected(old('type_liability_id', $customer->type_liability_id) == 'O-13')>O-13 (Gran Contribuyente)</option>
                                <option value="O-47" @selected(old('type_liability_id', $customer->type_liability_id) == 'O-47')>O-47 (Régimen Simple)</option>
                            </select>
                        </div>
                    </div>
                </div>

                {{-- SECCIÓN 3: CONTACTO --}}
                <div class="bg-[#0d121f] border border-white/5 rounded-2xl overflow-hidden">
                    <div class="px-6 py-4 border-b border-white/5 flex items-center gap-3">
                        <span class="text-[9px] font-black text-yellow-500 bg-yellow-500/10 border border-yellow-500/20 px-2 py-0.5 rounded-lg">03</span>
                        <h3 class="text-[11px] font-black uppercase tracking-[0.2em] text-gray-400">Contacto y Ubicación</h3>
                    </div>
                    <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="md:col-span-2">
                            <label class="block text-[9px] font-black uppercase tracking-widest text-zinc-500 mb-1.5">Dirección <span class="text-red-400">*</span></label>
                            <input type="text" name="address" value="{{ old('address', $customer->address) }}" required
                                class="w-full px-4 py-3 rounded-xl bg-black/40 border border-white/10 text-sm text-white outline-none focus:border-yellow-500/50">
                        </div>
                        <div>
                            <label class="block text-[9px] font-black uppercase tracking-widest text-zinc-500 mb-1.5">Municipio (DANE) <span class="text-red-400">*</span></label>
                            <input type="text" name="municipality_id" value="{{ old('municipality_id', $customer->municipality_id) }}" required
                                class="w-full px-4 py-3 rounded-xl bg-black/40 border border-white/10 text-sm font-mono text-yellow-500 outline-none focus:border-yellow-500/50">
                        </div>
                        <div>
                            <label class="block text-[9px] font-black uppercase tracking-widest text-zinc-500 mb-1.5">Teléfono</label>
                            <input type="text" name="phone" value="{{ old('phone', $customer->phone) }}"
                                class="w-full px-4 py-3 rounded-xl bg-black/40 border border-white/10 text-sm text-white outline-none focus:border-yellow-500/50">
                        </div>
                        <div>
                            <label class="block text-[9px] font-black uppercase tracking-widest text-zinc-500 mb-1.5">Correo Electrónico <span class="text-red-400">*</span></label>
                            <input type="email" name="email" value="{{ old('email', $customer->email) }}" required
                                class="w-full px-4 py-3 rounded-xl bg-black/40 border border-white/10 text-sm text-white outline-none focus:border-yellow-500/50">
                        </div>
                        <div>
                            <label class="block text-[9px] font-black uppercase tracking-widest text-zinc-500 mb-1.5">Código Postal</label>
                            <input type="text" name="postal_code" value="{{ old('postal_code', $customer->postal_code) }}"
                                class="w-full px-4 py-3 rounded-xl bg-black/40 border border-white/10 text-sm text-white outline-none focus:border-yellow-500/50">
                        </div>
                    </div>
                </div>

                {{-- SECCIÓN 4: POLÍTICA FINANCIERA --}}
                <div class="bg-[#0d121f] border border-white/5 rounded-2xl overflow-hidden">
                    <div class="px-6 py-4 border-b border-white/5 flex items-center gap-3">
                        <span class="text-[9px] font-black text-yellow-500 bg-yellow-500/10 border border-yellow-500/20 px-2 py-0.5 rounded-lg">04</span>
                        <h3 class="text-[11px] font-black uppercase tracking-[0.2em] text-gray-400">Política Financiera</h3>
                    </div>
                    <div class="p-6">
                        <div class="max-w-sm">
                            <label class="block text-[9px] font-black uppercase tracking-widest text-zinc-500 mb-1.5">Término de Pago <span class="text-red-400">*</span></label>
                            <select name="payment_term_id" required
                                class="w-full px-4 py-3 rounded-xl bg-black/40 border border-white/10 text-sm text-white outline-none focus:border-yellow-500/50">
                                @foreach($paymentTerms as $term)
                                    <option value="{{ $term->id }}"
                                        @selected(old('payment_term_id', $customer->payment_term_id) == $term->id)>
                                        {{ $term->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                {{-- ACCIONES --}}
                <div class="flex items-center justify-between pt-2">
                    <form method="POST" action="{{ route('customers.destroy', $customer) }}"
                          onsubmit="return confirm('¿Eliminar definitivamente a {{ $customer->name }}? Esta acción no se puede deshacer.')">
                        @csrf @method('DELETE')
                        <button type="submit"
                            class="text-[9px] font-black uppercase tracking-widest text-red-400/50 hover:text-red-400 transition-colors flex items-center gap-2">
                            <i class="fas fa-trash text-[9px]"></i> Eliminar Cliente
                        </button>
                    </form>

                    <div class="flex items-center gap-4">
                        <a href="{{ route('customers.show', $customer) }}"
                           class="text-[10px] font-black uppercase tracking-widest text-zinc-500 hover:text-white transition-colors">
                            Cancelar
                        </a>
                        <button type="submit" form="edit-form"
                            class="bg-yellow-500 hover:bg-yellow-400 text-black px-8 py-3 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all flex items-center gap-2 shadow-[0_8px_20px_rgba(234,179,8,0.2)]">
                            <i class="fas fa-save"></i> Guardar Cambios
                        </button>
                    </div>
                </div>

            </div>
        </form>
    </div>

    <script>
    const idInput = document.getElementById('id_number');
    const docType = document.getElementById('doc_type');
    const dvField = document.getElementById('dv_input');

    idInput.addEventListener('input', function () {
        if (docType.value == '31' && this.value && !isNaN(this.value)) {
            dvField.value = calcularDV(this.value);
        } else {
            dvField.value = '';
        }
    });

    function calcularDV(nit) {
        const vpri = [3,7,13,17,19,23,29,37,41,43,47,53,59,67,71];
        let x = 0;
        for (let i = 0; i < nit.length; i++) x += nit[nit.length - 1 - i] * vpri[i];
        const y = x % 11;
        return y > 1 ? 11 - y : y;
    }
    </script>
</x-app-layout>
