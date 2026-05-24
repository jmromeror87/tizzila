{{--
|--------------------------------------------------------------------------
| TIZZILA APP - © 2026 Tizzila App · Orquestación Avícola Inteligente
|--------------------------------------------------------------------------
| Proyecto privado desarrollado por:
| Ingeniero Jhoan Romero Rivera
| LinkedIn: https://linkedin.com/in/jmromeror87
|--------------------------------------------------------------------------
--}}

<div class="grid grid-cols-1 md:grid-cols-6 gap-x-8 gap-y-6">

    {{-- TIPO DE TERCERO --}}
    <div class="col-span-1 md:col-span-6">
        <label class="block text-[11px] font-bold text-gray-400 uppercase tracking-widest mb-3 ml-1">Tipo de Tercero</label>
        <div class="grid grid-cols-3 gap-3">
            @php $currentType = old('provider_type', $provider->provider_type ?? 'poultry'); @endphp

            {{-- Proveedor de Aves --}}
            <label class="cursor-pointer">
                <input type="radio" name="provider_type" value="poultry" @checked($currentType == 'poultry') class="sr-only peer">
                <div class="{{ $currentType == 'poultry' ? 'border-yellow-500/50 bg-yellow-500/10 text-yellow-400' : 'border-white/10 bg-black/20 text-zinc-500 hover:border-white/20' }} border rounded-xl px-4 py-3 flex items-center gap-3 transition-all">
                    <i class="fas fa-feather-alt text-sm"></i>
                    <span class="text-[10px] font-black uppercase tracking-widest">Proveedor de Aves</span>
                </div>
            </label>

            {{-- Proveedor de Gastos --}}
            <label class="cursor-pointer">
                <input type="radio" name="provider_type" value="expense" @checked($currentType == 'expense') class="sr-only peer">
                <div class="{{ $currentType == 'expense' ? 'border-blue-500/50 bg-blue-500/10 text-blue-400' : 'border-white/10 bg-black/20 text-zinc-500 hover:border-white/20' }} border rounded-xl px-4 py-3 flex items-center gap-3 transition-all">
                    <i class="fas fa-receipt text-sm"></i>
                    <span class="text-[10px] font-black uppercase tracking-widest">Proveedor de Gastos</span>
                </div>
            </label>

            {{-- General --}}
            <label class="cursor-pointer">
                <input type="radio" name="provider_type" value="general" @checked($currentType == 'general') class="sr-only peer">
                <div class="{{ $currentType == 'general' ? 'border-zinc-400/50 bg-zinc-500/10 text-zinc-300' : 'border-white/10 bg-black/20 text-zinc-500 hover:border-white/20' }} border rounded-xl px-4 py-3 flex items-center gap-3 transition-all">
                    <i class="fas fa-user-tie text-sm"></i>
                    <span class="text-[10px] font-black uppercase tracking-widest">General / Otro</span>
                </div>
            </label>
        </div>

        {{-- JS para feedback visual inmediato al hacer clic --}}
        <script>
        document.querySelectorAll('input[name="provider_type"]').forEach(radio => {
            radio.addEventListener('change', () => {
                const configs = {
                    poultry: 'border-yellow-500/50 bg-yellow-500/10 text-yellow-400',
                    expense: 'border-blue-500/50 bg-blue-500/10 text-blue-400',
                    general: 'border-zinc-400/50 bg-zinc-500/10 text-zinc-300',
                };
                document.querySelectorAll('input[name="provider_type"]').forEach(r => {
                    const div = r.nextElementSibling;
                    div.className = div.className
                        .replace(/border-yellow-500\/50|bg-yellow-500\/10|text-yellow-400/g, '')
                        .replace(/border-blue-500\/50|bg-blue-500\/10|text-blue-400/g, '')
                        .replace(/border-zinc-400\/50|bg-zinc-500\/10|text-zinc-300/g, '')
                        .replace('border-white/10 bg-black/20 text-zinc-500', '')
                        .trim();
                    div.className += ' border-white/10 bg-black/20 text-zinc-500';
                });
                const sel = radio.nextElementSibling;
                sel.className = sel.className.replace('border-white/10 bg-black/20 text-zinc-500', '').trim();
                sel.className += ' ' + (configs[radio.value] || configs.general);
            });
        });
        </script>
    </div>

    {{-- BLOQUE 1: IDENTIFICACIÓN LEGAL --}}
    <div class="col-span-1 md:col-span-6 flex items-center gap-3 mb-2">
        <div class="h-[1px] flex-1 bg-white/5"></div>
        <span class="text-[9px] font-black text-gray-600 uppercase tracking-[0.3em]">Identidad Corporativa</span>
        <div class="h-[1px] flex-1 bg-white/5"></div>
    </div>

    {{-- Razón Social --}}
    <div class="col-span-1 md:col-span-6">
        <label class="block text-[11px] font-bold text-gray-400 uppercase tracking-widest mb-3 ml-1">Razón Social (Legal)</label>
        <div class="relative group">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                <i class="fas fa-building text-gray-600 group-focus-within:text-yellow-500 transition-colors"></i>
            </div>
            <input type="text" name="business_name"
                   class="w-full bg-[#070a13] border-white/10 rounded-xl text-white text-sm focus:border-yellow-500 focus:ring-1 focus:ring-yellow-500/30 transition-all py-3.5 pl-11 pr-4 @error('business_name') border-red-500/50 @enderror"
                   value="{{ old('business_name', $provider->business_name ?? '') }}"
                   placeholder="Ej: Avícola Nacional S.A.S." required>
        </div>
        @error('business_name') <p class="mt-2 text-[10px] text-red-500 font-bold uppercase tracking-tight">{{ $message }}</p> @enderror
    </div>

    {{-- Nombre Comercial --}}
    <div class="col-span-1 md:col-span-3">
        <label class="block text-[11px] font-bold text-gray-400 uppercase tracking-widest mb-3 ml-1">Nombre Comercial (Marca)</label>
        <div class="relative group">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                <i class="fas fa-tag text-gray-600 group-focus-within:text-yellow-500 transition-colors"></i>
            </div>
            <input type="text" name="trade_name"
                   class="w-full bg-[#070a13] border-white/10 rounded-xl text-white text-sm focus:border-yellow-500 focus:ring-1 focus:ring-yellow-500/30 transition-all py-3.5 pl-11 pr-4"
                   value="{{ old('trade_name', $provider->trade_name ?? '') }}"
                   placeholder="Ej: Pollos El Dorado">
        </div>
    </div>

    {{-- Identificación Fiscal (Tipo + Número) --}}
    <div class="col-span-1 md:col-span-3">
        <label class="block text-[11px] font-bold text-gray-400 uppercase tracking-widest mb-3 ml-1">Identificación Fiscal</label>
        <div class="flex gap-2">
            <div class="w-1/3 relative group">
                <select name="tax_id_type" class="w-full bg-[#070a13] border-white/10 rounded-xl text-white text-xs font-bold focus:border-yellow-500 focus:ring-yellow-500/30 transition-all py-3.5 px-3 appearance-none text-center uppercase tracking-tighter" required>
                    @foreach(['NIT','CC','CE','PASSPORT'] as $type)
                        <option value="{{ $type }}" @selected(old('tax_id_type', $provider->tax_id_type ?? '') == $type)>
                            {{ $type }}
                        </option>
                    @endforeach
                </select>
                <div class="absolute inset-y-0 right-2 flex items-center pointer-events-none text-gray-700">
                    <i class="fas fa-caret-down text-[10px]"></i>
                </div>
            </div>
            <div class="w-2/3 relative group">
                <input type="text" name="tax_id"
                       class="w-full bg-[#070a13] border-white/10 rounded-xl text-white text-sm focus:border-yellow-500 focus:ring-1 focus:ring-yellow-500/30 transition-all py-3.5 px-4 font-mono @error('tax_id') border-red-500/50 @enderror"
                       value="{{ old('tax_id', $provider->tax_id ?? '') }}"
                       placeholder="900.000.000-1" required>
            </div>
        </div>
    </div>

    {{-- BLOQUE 2: COMUNICACIÓN --}}
    <div class="col-span-1 md:col-span-6 flex items-center gap-3 mt-4 mb-2">
        <div class="h-[1px] flex-1 bg-white/5"></div>
        <span class="text-[9px] font-black text-gray-600 uppercase tracking-[0.3em]">Canales de Contacto</span>
        <div class="h-[1px] flex-1 bg-white/5"></div>
    </div>

    {{-- Teléfono --}}
    <div class="col-span-1 md:col-span-3">
        <label class="block text-[11px] font-bold text-gray-400 uppercase tracking-widest mb-3 ml-1">Teléfono Directo</label>
        <div class="relative group">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                <i class="fas fa-phone-alt text-gray-600 group-focus-within:text-yellow-500 transition-colors"></i>
            </div>
            <input type="text" name="phone"
                   class="w-full bg-[#070a13] border-white/10 rounded-xl text-white text-sm focus:border-yellow-500 focus:ring-1 focus:ring-yellow-500/30 transition-all py-3.5 pl-11 pr-4"
                   value="{{ old('phone', $provider->phone ?? '') }}"
                   placeholder="+57 3XX XXX XXXX">
        </div>
    </div>

    {{-- Email --}}
    <div class="col-span-1 md:col-span-3">
        <label class="block text-[11px] font-bold text-gray-400 uppercase tracking-widest mb-3 ml-1">Correo Corporativo</label>
        <div class="relative group">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                <i class="fas fa-envelope text-gray-600 group-focus-within:text-yellow-500 transition-colors"></i>
            </div>
            <input type="email" name="email"
                   class="w-full bg-[#070a13] border-white/10 rounded-xl text-white text-sm focus:border-yellow-500 focus:ring-1 focus:ring-yellow-500/30 transition-all py-3.5 pl-11 pr-4"
                   value="{{ old('email', $provider->email ?? '') }}"
                   placeholder="logistica@proveedor.com">
        </div>
    </div>

    {{-- BLOQUE 2B: UBICACIÓN --}}
    <div class="col-span-1 md:col-span-6 flex items-center gap-3 mt-4 mb-2">
        <div class="h-[1px] flex-1 bg-white/5"></div>
        <span class="text-[9px] font-black text-gray-600 uppercase tracking-[0.3em]">Ubicación</span>
        <div class="h-[1px] flex-1 bg-white/5"></div>
    </div>

    {{-- Dirección con autocompletado estilo Google --}}
    <div class="col-span-1 md:col-span-6 relative">
        <label class="block text-[11px] font-bold text-gray-400 uppercase tracking-widest mb-3 ml-1">Dirección</label>
        <div class="relative group">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none z-10">
                <i class="fas fa-map-marker-alt text-gray-600 group-focus-within:text-yellow-500 transition-colors"></i>
            </div>
            <input type="text" id="address_input" autocomplete="off"
                   class="w-full bg-[#070a13] border-white/10 rounded-xl text-white text-sm focus:border-yellow-500 focus:ring-1 focus:ring-yellow-500/30 transition-all py-3.5 pl-11 pr-10"
                   value="{{ old('address_line', $provider->address_line ?? '') }}"
                   placeholder="Ej: Calle 10 # 5-20 Bogotá...">
            <div id="addr_spinner" class="absolute inset-y-0 right-4 flex items-center pointer-events-none hidden">
                <i class="fas fa-circle-notch fa-spin text-yellow-500 text-xs"></i>
            </div>
        </div>

        {{-- Hidden inputs → backend --}}
        <input type="hidden" name="address_line" id="address_value"   value="{{ old('address_line', $provider->address_line ?? '') }}">
        <input type="hidden" name="city"         id="city_value"      value="{{ old('city', $provider->city ?? '') }}">
        <input type="hidden" name="department"   id="department_value" value="{{ old('department', $provider->department ?? '') }}">
        <input type="hidden" name="postal_code"  id="postal_code_value" value="{{ old('postal_code', $provider->postal_code ?? '') }}">

        {{-- Dropdown sugerencias --}}
        <ul id="addr_suggestions"
            class="absolute z-50 left-0 right-0 mt-1 bg-[#0d121f] border border-yellow-500/20 rounded-xl overflow-hidden shadow-2xl hidden divide-y divide-white/5">
        </ul>
    </div>

    {{-- Ciudad / Departamento / CP (solo lectura, auto) --}}
    <div class="col-span-1 md:col-span-2">
        <label class="block text-[11px] font-bold text-gray-400 uppercase tracking-widest mb-3 ml-1">
            Ciudad <span class="text-yellow-500/40 normal-case font-bold text-[9px]">↑ auto</span>
        </label>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                <i class="fas fa-city text-gray-700"></i>
            </div>
            <input type="text" id="city_display" readonly
                   class="w-full bg-black/20 border-white/5 rounded-xl text-gray-300 text-sm py-3.5 pl-11 pr-4 cursor-default"
                   value="{{ old('city', $provider->city ?? '') }}"
                   placeholder="—">
        </div>
    </div>

    <div class="col-span-1 md:col-span-2">
        <label class="block text-[11px] font-bold text-gray-400 uppercase tracking-widest mb-3 ml-1">
            Departamento <span class="text-yellow-500/40 normal-case font-bold text-[9px]">↑ auto</span>
        </label>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                <i class="fas fa-map text-gray-700"></i>
            </div>
            <input type="text" id="department_display" readonly
                   class="w-full bg-black/20 border-white/5 rounded-xl text-gray-300 text-sm py-3.5 pl-11 pr-4 cursor-default"
                   value="{{ old('department', $provider->department ?? '') }}"
                   placeholder="—">
        </div>
    </div>

    <div class="col-span-1 md:col-span-2">
        <label class="block text-[11px] font-bold text-gray-400 uppercase tracking-widest mb-3 ml-1">
            Código Postal <span class="text-yellow-500/40 normal-case font-bold text-[9px]">↑ auto</span>
        </label>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                <i class="fas fa-hashtag text-gray-700"></i>
            </div>
            <input type="text" id="postal_display" readonly
                   class="w-full bg-black/20 border-white/5 rounded-xl text-gray-300 text-sm py-3.5 pl-11 pr-4 cursor-default"
                   value="{{ old('postal_code', $provider->postal_code ?? '') }}"
                   placeholder="—">
        </div>
    </div>

    <script>
    (function () {
        const TOKEN   = '{{ config("services.mapbox.token") }}';
        const input   = document.getElementById('address_input');
        const list    = document.getElementById('addr_suggestions');
        const spinner = document.getElementById('addr_spinner');

        const addrVal = document.getElementById('address_value');
        const cityVal = document.getElementById('city_value');
        const deptVal = document.getElementById('department_value');
        const cpVal   = document.getElementById('postal_code_value');

        const cityDisp = document.getElementById('city_display');
        const deptDisp = document.getElementById('department_display');
        const cpDisp   = document.getElementById('postal_display');

        let timer;

        input.addEventListener('input', function () {
            clearTimeout(timer);
            const q = this.value.trim();
            if (q.length < 3) { hide(); return; }
            spinner.classList.remove('hidden');
            timer = setTimeout(() => search(q), 350);
        });

        async function search(q) {
            try {
                const url = `https://api.mapbox.com/geocoding/v5/mapbox.places/${encodeURIComponent(q)}.json`
                    + `?country=CO&language=es&limit=6&types=address,place,poi&access_token=${TOKEN}`;
                const r    = await fetch(url);
                const data = await r.json();
                spinner.classList.add('hidden');
                render(data.features || []);
            } catch { spinner.classList.add('hidden'); }
        }

        function render(features) {
            list.innerHTML = '';
            if (!features.length) { hide(); return; }

            features.forEach(f => {
                const ctx      = f.context || [];
                const place    = f.place_name || '';
                const street   = f.text || '';
                const fullAddr = f.properties?.address
                    ? f.properties.address + ' ' + street
                    : (f.place_type?.[0] === 'address' ? place.split(',')[0] : street);

                const city = ctx.find(c => c.id.startsWith('place'))?.text
                          || (f.place_type?.[0] === 'place' ? f.text : '');
                const dept = ctx.find(c => c.id.startsWith('region'))?.text || '';
                const cp   = ctx.find(c => c.id.startsWith('postcode'))?.text || '';

                const li = document.createElement('li');
                li.className = 'px-4 py-3 hover:bg-yellow-500/5 cursor-pointer transition-colors flex items-start gap-3 group';
                li.innerHTML = `
                    <i class="fas fa-map-marker-alt text-yellow-500/60 group-hover:text-yellow-500 text-xs mt-0.5 w-4 shrink-0 transition-colors"></i>
                    <div class="min-w-0">
                        <p class="text-xs font-black text-white truncate">${place.split(',')[0]}</p>
                        <p class="text-[10px] text-gray-500 truncate">${[city, dept, cp ? 'CP ' + cp : ''].filter(Boolean).join(' · ')}</p>
                    </div>`;

                li.addEventListener('mousedown', e => {
                    e.preventDefault();
                    input.value    = place.split(',')[0];
                    addrVal.value  = place.split(',')[0];
                    cityVal.value  = city;
                    deptVal.value  = dept;
                    cpVal.value    = cp;
                    cityDisp.value = city || '—';
                    deptDisp.value = dept || '—';
                    cpDisp.value   = cp   || '—';
                    hide();
                    input.blur();
                });

                list.appendChild(li);
            });

            list.classList.remove('hidden');
        }

        function hide() { list.classList.add('hidden'); }

        input.addEventListener('blur', () => setTimeout(hide, 150));
        input.addEventListener('focus', () => {
            if (input.value.trim().length >= 3) search(input.value.trim());
        });
    })();
    </script>

    {{-- BLOQUE 3: CONDICIONES COMERCIALES --}}
    <div class="col-span-1 md:col-span-6 flex items-center gap-3 mt-4 mb-2">
        <div class="h-[1px] flex-1 bg-white/5"></div>
        <span class="text-[9px] font-black text-gray-600 uppercase tracking-[0.3em]">Acuerdos de Pago</span>
        <div class="h-[1px] flex-1 bg-white/5"></div>
    </div>

    {{-- Plazo de Pago --}}
    <div class="col-span-1 md:col-span-3">
        <label class="block text-[11px] font-bold text-gray-400 uppercase tracking-widest mb-3 ml-1">Plazo de Crédito</label>
        <div class="relative group">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                <i class="fas fa-hourglass-half text-gray-600 group-focus-within:text-yellow-500 transition-colors"></i>
            </div>
            <input type="number" style="appearance:none;-moz-appearance:textfield;-webkit-appearance:none" name="payment_terms_days"
                   class="w-full bg-[#070a13] border-white/10 rounded-xl text-white text-sm focus:border-yellow-500 focus:ring-1 focus:ring-yellow-500/30 transition-all py-3.5 pl-11 pr-16"
                   value="{{ old('payment_terms_days', $provider->payment_terms_days ?? 0) }}"
                   placeholder="0">
            <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none">
                <span class="text-[10px] font-black text-gray-600 group-focus-within:text-yellow-500/50 transition-colors uppercase">Días</span>
            </div>
        </div>
    </div>

    {{-- Método de Pago --}}
    <div class="col-span-1 md:col-span-3">
        <label class="block text-[11px] font-bold text-gray-400 uppercase tracking-widest mb-3 ml-1">Método Preferido</label>
        <div class="relative group">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                <i class="fas fa-credit-card text-gray-600 group-focus-within:text-yellow-500 transition-colors"></i>
            </div>
            <select name="preferred_payment_method" class="w-full bg-[#070a13] border-white/10 rounded-xl text-white text-sm focus:border-yellow-500 focus:ring-1 focus:ring-yellow-500/30 transition-all py-3.5 pl-11 pr-10 appearance-none">
                @foreach(['transfer' => 'Transferencia Bancaria', 'cash' => 'Efectivo', 'check' => 'Cheque de Gerencia'] as $value => $label)
                    <option value="{{ $value }}" @selected(old('preferred_payment_method', $provider->preferred_payment_method ?? '') == $value)>
                        {{ $label }}
                    </option>
                @endforeach
            </select>
            <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-gray-600">
                <i class="fas fa-chevron-down text-xs"></i>
            </div>
        </div>
    </div>

    {{-- BLOQUE 4: ESTADO OPERATIVO --}}
    <div class="col-span-1 md:col-span-6 bg-white/[0.02] border border-white/5 p-6 rounded-2xl mt-4">
        <label class="block text-[10px] font-black text-gray-500 uppercase tracking-[0.2em] mb-4 ml-1 text-center">Estatus en el Sistema</label>
        <div class="flex flex-col sm:flex-row justify-center items-center gap-8">
            <label class="relative flex items-center gap-4 cursor-pointer group">
                <input type="radio" name="status" value="active" @checked(old('status', $provider->status ?? 'active') == 'active')
                       class="w-5 h-5 text-yellow-500 border-white/20 bg-[#070a13] focus:ring-yellow-500 focus:ring-offset-[#0f1420]">
                <div class="flex flex-col">
                    <span class="text-sm font-black text-gray-400 group-hover:text-white transition-colors uppercase tracking-widest">Activo</span>
                    <span class="text-[9px] text-emerald-500/70 font-bold uppercase tracking-tighter">Habilitado para órdenes</span>
                </div>
            </label>

            <label class="relative flex items-center gap-4 cursor-pointer group">
                <input type="radio" name="status" value="inactive" @checked(old('status', $provider->status ?? '') == 'inactive')
                       class="w-5 h-5 text-yellow-500 border-white/20 bg-[#070a13] focus:ring-yellow-500 focus:ring-offset-[#0f1420]">
                <div class="flex flex-col">
                    <span class="text-sm font-black text-gray-400 group-hover:text-white transition-colors uppercase tracking-widest">Inactivo</span>
                    <span class="text-[9px] text-rose-500/70 font-bold uppercase tracking-tighter">Bloqueado temporalmente</span>
                </div>
            </label>
        </div>
    </div>

</div>
