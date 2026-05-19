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
                <a href="{{ route('customers.index') }}"
                   class="h-9 w-9 rounded-xl bg-white/5 border border-white/10 hover:bg-white/10 text-zinc-500 hover:text-white flex items-center justify-center transition-all">
                    <i class="fas fa-arrow-left text-xs"></i>
                </a>
                <div>
                    <h2 class="text-2xl font-black text-white tracking-tighter uppercase">
                        Nuevo <span class="text-yellow-500">Cliente</span>
                    </h2>
                    <p class="text-[9px] text-zinc-500 uppercase tracking-[0.3em] font-bold mt-1">
                        Protocolo Facturación Electrónica · DIAN
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

        {{-- Alerta duplicado --}}
        <div id="duplicateAlert" class="hidden mb-5 bg-red-500/10 border border-red-500/20 rounded-2xl px-5 py-4 flex items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <i class="fas fa-id-card text-red-400"></i>
                <div>
                    <p class="text-[10px] font-black uppercase tracking-widest text-red-400">Cliente ya registrado</p>
                    <p id="duplicateName" class="text-xs text-red-300/70 font-bold mt-0.5"></p>
                </div>
            </div>
            <a id="viewCustomerBtn" href="#"
               class="px-4 py-2 bg-red-500 hover:bg-red-400 text-white text-[9px] font-black uppercase tracking-widest rounded-xl transition-all">
                Ver Expediente
            </a>
        </div>

        <form action="{{ route('customers.store') }}" method="POST">
            @csrf

            <div class="space-y-5">

                {{-- SECCIÓN 1: IDENTIFICACIÓN --}}
                <div class="bg-[#0d121f] border border-white/5 rounded-2xl overflow-hidden">
                    <div class="px-6 py-4 border-b border-white/5 flex items-center gap-3">
                        <span class="text-[9px] font-black text-yellow-500 bg-yellow-500/10 border border-yellow-500/20 px-2 py-0.5 rounded-lg">01</span>
                        <h3 class="text-[11px] font-black uppercase tracking-[0.2em] text-gray-400">Identificación Legal</h3>
                    </div>
                    <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="md:col-span-3">
                            <label class="block text-[9px] font-black uppercase tracking-widest text-zinc-500 mb-1.5">Razón Social / Nombre <span class="text-red-400">*</span></label>
                            <input type="text" name="name" value="{{ old('name') }}" required
                                placeholder="NOMBRE SEGÚN RUT"
                                class="w-full px-4 py-3 rounded-xl bg-black/40 border border-white/10 text-sm font-bold text-white uppercase tracking-wide outline-none focus:border-yellow-500/50">
                        </div>

                        <div class="md:col-span-1">
                            <label class="block text-[9px] font-black uppercase tracking-widest text-zinc-500 mb-1.5">Tipo Documento <span class="text-red-400">*</span></label>
                            <select id="doc_type" name="type_document_id"
                                class="w-full px-4 py-3 rounded-xl bg-black/40 border border-white/10 text-sm text-white outline-none focus:border-yellow-500/50">
                                <option value="31" {{ old('type_document_id') == '31' ? 'selected' : '' }}>NIT</option>
                                <option value="13" {{ old('type_document_id') == '13' ? 'selected' : '' }}>Cédula</option>
                                <option value="41" {{ old('type_document_id') == '41' ? 'selected' : '' }}>Pasaporte</option>
                                <option value="22" {{ old('type_document_id') == '22' ? 'selected' : '' }}>Tarjeta Identidad</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-[9px] font-black uppercase tracking-widest text-zinc-500 mb-1.5">Número Identificación <span class="text-red-400">*</span></label>
                            <input type="text" id="id_number" name="identification_number" value="{{ old('identification_number') }}" required
                                placeholder="Solo números"
                                class="w-full px-4 py-3 rounded-xl bg-black/40 border border-white/10 text-sm font-mono text-white outline-none focus:border-yellow-500/50">
                        </div>

                        <div>
                            <label class="block text-[9px] font-black uppercase tracking-widest text-zinc-500 mb-1.5">DV (auto)</label>
                            <input type="text" id="dv_input" name="dv" readonly value="{{ old('dv') }}"
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
                                <option value="1" {{ old('type_organization_id') == '1' ? 'selected' : '' }}>Persona Jurídica</option>
                                <option value="2" {{ old('type_organization_id') == '2' ? 'selected' : '' }}>Persona Natural</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-[9px] font-black uppercase tracking-widest text-zinc-500 mb-1.5">Régimen <span class="text-red-400">*</span></label>
                            <select name="type_regime_id"
                                class="w-full px-4 py-3 rounded-xl bg-black/40 border border-white/10 text-sm text-white outline-none focus:border-yellow-500/50">
                                <option value="48" {{ old('type_regime_id') == '48' ? 'selected' : '' }}>Responsable IVA</option>
                                <option value="49" {{ old('type_regime_id') == '49' ? 'selected' : '' }}>No Responsable</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-[9px] font-black uppercase tracking-widest text-zinc-500 mb-1.5">Responsabilidad Fiscal <span class="text-red-400">*</span></label>
                            <select name="type_liability_id"
                                class="w-full px-4 py-3 rounded-xl bg-black/40 border border-white/10 text-sm text-white outline-none focus:border-yellow-500/50">
                                <option value="R-99-PN" {{ old('type_liability_id') == 'R-99-PN' ? 'selected' : '' }}>R-99-PN (Persona Natural)</option>
                                <option value="O-13" {{ old('type_liability_id') == 'O-13' ? 'selected' : '' }}>O-13 (Gran Contribuyente)</option>
                                <option value="O-47" {{ old('type_liability_id') == 'O-47' ? 'selected' : '' }}>O-47 (Régimen Simple)</option>
                            </select>
                        </div>
                    </div>
                </div>

                {{-- SECCIÓN 3: CONTACTO Y UBICACIÓN --}}
                <div class="bg-[#0d121f] border border-white/5 rounded-2xl overflow-hidden">
                    <div class="px-6 py-4 border-b border-white/5 flex items-center gap-3">
                        <span class="text-[9px] font-black text-yellow-500 bg-yellow-500/10 border border-yellow-500/20 px-2 py-0.5 rounded-lg">03</span>
                        <h3 class="text-[11px] font-black uppercase tracking-[0.2em] text-gray-400">Contacto y Ubicación</h3>
                    </div>
                    <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="md:col-span-2">
                            <label class="block text-[9px] font-black uppercase tracking-widest text-zinc-500 mb-1.5">Dirección <span class="text-red-400">*</span></label>
                            <input type="text" name="address" value="{{ old('address') }}" required
                                placeholder="Dirección completa de correspondencia"
                                class="w-full px-4 py-3 rounded-xl bg-black/40 border border-white/10 text-sm text-white outline-none focus:border-yellow-500/50">
                        </div>
                        <div>
                            <label class="block text-[9px] font-black uppercase tracking-widest text-zinc-500 mb-1.5">Municipio (DANE) <span class="text-red-400">*</span></label>
                            <input type="text" name="municipality_id" value="{{ old('municipality_id') }}" required
                                placeholder="Ej: 05001"
                                class="w-full px-4 py-3 rounded-xl bg-black/40 border border-white/10 text-sm font-mono text-yellow-500 outline-none focus:border-yellow-500/50">
                        </div>
                        <div>
                            <label class="block text-[9px] font-black uppercase tracking-widest text-zinc-500 mb-1.5">Teléfono</label>
                            <input type="text" name="phone" value="{{ old('phone') }}"
                                placeholder="300 000 0000"
                                class="w-full px-4 py-3 rounded-xl bg-black/40 border border-white/10 text-sm text-white outline-none focus:border-yellow-500/50">
                        </div>
                        <div>
                            <label class="block text-[9px] font-black uppercase tracking-widest text-zinc-500 mb-1.5">Correo Electrónico <span class="text-red-400">*</span></label>
                            <input type="email" name="email" value="{{ old('email') }}" required
                                placeholder="correo@empresa.com"
                                class="w-full px-4 py-3 rounded-xl bg-black/40 border border-white/10 text-sm text-white outline-none focus:border-yellow-500/50">
                        </div>
                        <div>
                            <label class="block text-[9px] font-black uppercase tracking-widest text-zinc-500 mb-1.5">Código Postal</label>
                            <input type="text" name="postal_code" value="{{ old('postal_code') }}"
                                placeholder="Opcional"
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
                                <option value="">— Seleccionar condición —</option>
                                @foreach($paymentTerms as $term)
                                    <option value="{{ $term->id }}" {{ old('payment_term_id') == $term->id ? 'selected' : '' }}>
                                        {{ $term->name }}
                                    </option>
                                @endforeach
                            </select>
                            <p class="text-[9px] text-zinc-600 mt-2">Se aplica al generar facturas y calcular fechas de vencimiento.</p>
                        </div>
                    </div>
                </div>

                {{-- ACCIONES --}}
                <div class="flex items-center justify-end gap-4 pt-2">
                    <a href="{{ route('customers.index') }}"
                       class="text-[10px] font-black uppercase tracking-widest text-zinc-500 hover:text-white transition-colors">
                        Cancelar
                    </a>
                    <button type="submit" id="submitBtn"
                        class="bg-yellow-500 hover:bg-yellow-400 text-black px-8 py-3 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all flex items-center gap-2 shadow-[0_8px_20px_rgba(234,179,8,0.2)] disabled:opacity-30 disabled:cursor-not-allowed">
                        <i class="fas fa-plus-circle"></i> Vincular Cliente
                    </button>
                </div>
            </div>
        </form>
    </div>

    <script>
    const idInput   = document.getElementById('id_number');
    const docType   = document.getElementById('doc_type');
    const alertBox  = document.getElementById('duplicateAlert');
    const alertName = document.getElementById('duplicateName');
    const viewBtn   = document.getElementById('viewCustomerBtn');
    const submitBtn = document.getElementById('submitBtn');
    let debounceTimer = null;

    function checkCustomerExists() {
        const idValue  = idInput.value.trim();
        const docValue = docType.value;
        if (!idValue || idValue.length < 5) {
            alertBox.classList.add('hidden');
            submitBtn.disabled = false;
            return;
        }
        fetch(`{{ route('customers.check-exists') }}?type_document_id=${docValue}&identification_number=${idValue}`)
            .then(r => r.json())
            .then(data => {
                if (data.exists) {
                    alertName.textContent = data.name;
                    viewBtn.href = `/customers/${data.customer_id}`;
                    alertBox.classList.remove('hidden');
                    submitBtn.disabled = true;
                } else {
                    alertBox.classList.add('hidden');
                    submitBtn.disabled = false;
                }
            });
    }

    idInput.addEventListener('input', () => {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(checkCustomerExists, 600);
        // DV
        if (docType.value === '31' && idInput.value && !isNaN(idInput.value)) {
            document.getElementById('dv_input').value = calcularDV(idInput.value);
        } else {
            document.getElementById('dv_input').value = '';
        }
    });
    docType.addEventListener('change', checkCustomerExists);

    function calcularDV(nit) {
        const vpri = [3,7,13,17,19,23,29,37,41,43,47,53,59,67,71];
        let x = 0;
        for (let i = 0; i < nit.length; i++) x += nit[nit.length - 1 - i] * vpri[i];
        const y = x % 11;
        return y > 1 ? 11 - y : y;
    }
    </script>
</x-app-layout>
