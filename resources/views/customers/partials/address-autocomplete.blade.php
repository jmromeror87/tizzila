{{-- Ubicación con autocompletado opcional · Tizzila 2026 --}}
@php
    $addr  = $customer->address         ?? old('address', '');
    $city  = $customer->city            ?? old('city', '');
    $dept  = $customer->department      ?? old('department', '');
    $cp    = $customer->postal_code     ?? old('postal_code', '');
    $munId = $customer->municipality_id ?? old('municipality_id', '');
@endphp

<div class="md:col-span-3 space-y-4">

    {{-- ══ FILA 1: Dirección ══ --}}
    <div class="relative">
        <label class="block text-[9px] font-black uppercase tracking-widest text-zinc-500 mb-1.5">
            Dirección
            <span class="text-zinc-600 normal-case font-normal ml-1">(Escribe libremente o elige del autocompletado)</span>
        </label>
        <div class="relative">
            <input type="text" id="cust_addr_input" autocomplete="off"
                   name="address" value="{{ $addr }}"
                   placeholder="Calle 10 # 5-20, Barrio Centro, Vereda El Rosal..."
                   class="w-full px-4 py-3 rounded-xl bg-black/40 border border-white/10 text-sm text-white outline-none focus:border-yellow-500/50 pr-10">
            <div id="cust_addr_spinner" class="absolute inset-y-0 right-3 flex items-center pointer-events-none hidden">
                <i class="fas fa-circle-notch fa-spin text-yellow-500 text-xs"></i>
            </div>
        </div>
        {{-- Dropdown sugerencias --}}
        <ul id="cust_addr_list"
            class="absolute z-50 left-0 right-0 mt-1 bg-[#0d121f] border border-yellow-500/20 rounded-xl overflow-hidden shadow-2xl hidden divide-y divide-white/5">
        </ul>
    </div>

    {{-- ══ FILA 2: Barrio / Vereda | Ciudad | Municipio DANE ══ --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
            <label class="block text-[9px] font-black uppercase tracking-widest text-zinc-500 mb-1.5">Barrio / Vereda</label>
            <input type="text" name="neighborhood"
                   value="{{ $customer->neighborhood ?? old('neighborhood', '') }}"
                   placeholder="Barrio Cabecera, Vereda El Rosal..."
                   class="w-full px-4 py-3 rounded-xl bg-black/40 border border-white/10 text-sm text-white outline-none focus:border-yellow-500/50">
        </div>
        <div>
            <label class="block text-[9px] font-black uppercase tracking-widest text-zinc-500 mb-1.5">
                Ciudad / Municipio
                <span class="text-yellow-500/40 normal-case font-bold text-[9px] ml-1">↑ auto o manual</span>
            </label>
            <input type="text" name="city" id="cust_city_val"
                   value="{{ $city }}"
                   placeholder="Bucaramanga, Girón, Ocaña..."
                   class="w-full px-4 py-3 rounded-xl bg-black/40 border border-white/10 text-sm text-white outline-none focus:border-yellow-500/50">
        </div>
        <div>
            <label class="block text-[9px] font-black uppercase tracking-widest text-zinc-500 mb-1.5">Municipio (DANE)</label>
            <input type="text" name="municipality_id" id="cust_mun_val"
                   value="{{ $munId }}"
                   placeholder="Ej: 68001"
                   class="w-full px-4 py-3 rounded-xl bg-black/40 border border-white/10 text-sm font-mono text-yellow-500 outline-none focus:border-yellow-500/50">
        </div>
    </div>

    {{-- ══ FILA 3: Departamento | Código Postal | Teléfono ══ --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
            <label class="block text-[9px] font-black uppercase tracking-widest text-zinc-500 mb-1.5">
                Departamento
                <span class="text-yellow-500/40 normal-case font-bold text-[9px] ml-1">↑ auto o manual</span>
            </label>
            <input type="text" name="department" id="cust_dept_val"
                   value="{{ $dept }}"
                   placeholder="Santander, Norte de Santander..."
                   class="w-full px-4 py-3 rounded-xl bg-black/40 border border-white/10 text-sm text-white outline-none focus:border-yellow-500/50">
        </div>
        <div>
            <label class="block text-[9px] font-black uppercase tracking-widest text-zinc-500 mb-1.5">
                Código Postal
                <span class="text-yellow-500/40 normal-case font-bold text-[9px] ml-1">↑ auto o manual</span>
            </label>
            <input type="text" name="postal_code" id="cust_cp_val"
                   value="{{ $cp }}"
                   placeholder="680001"
                   class="w-full px-4 py-3 rounded-xl bg-black/40 border border-white/10 text-sm text-white outline-none focus:border-yellow-500/50">
        </div>
        <div>
            <label class="block text-[9px] font-black uppercase tracking-widest text-zinc-500 mb-1.5">Teléfono</label>
            <input type="text" name="phone"
                   value="{{ $customer->phone ?? old('phone', '') }}"
                   placeholder="300 000 0000"
                   class="w-full px-4 py-3 rounded-xl bg-black/40 border border-white/10 text-sm text-white outline-none focus:border-yellow-500/50">
        </div>
    </div>

    {{-- ══ FILA 4: Correo ══ --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="md:col-span-1">
            <label class="block text-[9px] font-black uppercase tracking-widest text-zinc-500 mb-1.5">Correo Electrónico</label>
            <input type="email" name="email"
                   value="{{ $customer->email ?? old('email', '') }}"
                   placeholder="correo@empresa.com"
                   class="w-full px-4 py-3 rounded-xl bg-black/40 border border-white/10 text-sm text-white outline-none focus:border-yellow-500/50">
        </div>
    </div>

</div>

<script>
(function () {
    const TOKEN   = '{{ config("services.mapbox.token") }}';
    const input   = document.getElementById('cust_addr_input');
    const list    = document.getElementById('cust_addr_list');
    const spinner = document.getElementById('cust_addr_spinner');
    const cityVal = document.getElementById('cust_city_val');
    const deptVal = document.getElementById('cust_dept_val');
    const cpVal   = document.getElementById('cust_cp_val');

    let timer;

    // El campo dirección siempre es editable — no hay hidden
    // Al escribir se busca en Mapbox, si no hay resultados el usuario escribe libre
    input.addEventListener('input', function () {
        clearTimeout(timer);
        const q = this.value.trim();
        if (q.length < 4) { hide(); return; }
        // Solo busca si el usuario hizo pausa de 600ms — evita llamadas por cada letra
        timer = setTimeout(() => {
            spinner.classList.remove('hidden');
            search(q);
        }, 600);
    });

    async function search(q) {
        try {
            const url = `https://api.mapbox.com/geocoding/v5/mapbox.places/${encodeURIComponent(q)}.json`
                + `?country=CO&language=es&limit=5&types=address,place,poi,locality,neighborhood&access_token=${TOKEN}`;
            const r    = await fetch(url);
            const data = await r.json();
            spinner.classList.add('hidden');
            render(data.features || []);
        } catch { spinner.classList.add('hidden'); }
    }

    function render(features) {
        list.innerHTML = '';
        if (!features.length) { hide(); return; }

        // Opción "No está en la lista — escribir manual"
        const manual = document.createElement('li');
        manual.className = 'px-4 py-2.5 flex items-center gap-3 text-zinc-600 hover:bg-white/5 cursor-pointer transition-colors';
        manual.innerHTML = `<i class="fas fa-pen text-[10px] w-4"></i><span class="text-[10px] font-black uppercase tracking-widest">Escribir dirección manual</span>`;
        manual.addEventListener('mousedown', e => { e.preventDefault(); hide(); input.focus(); });
        list.appendChild(manual);

        features.forEach(f => {
            const ctx    = f.context || [];
            const place  = f.place_name || '';
            const street = place.split(',')[0];
            const city   = ctx.find(c => c.id.startsWith('place'))?.text
                        || (f.place_type?.[0] === 'place' ? f.text : '');
            const dept   = ctx.find(c => c.id.startsWith('region'))?.text || '';
            const cp     = ctx.find(c => c.id.startsWith('postcode'))?.text || '';

            const li = document.createElement('li');
            li.className = 'px-4 py-3 hover:bg-yellow-500/5 cursor-pointer transition-colors flex items-start gap-3 group';
            li.innerHTML = `
                <i class="fas fa-map-marker-alt text-yellow-500/60 group-hover:text-yellow-500 text-xs mt-0.5 w-4 shrink-0 transition-colors"></i>
                <div class="min-w-0">
                    <p class="text-xs font-black text-white truncate">${street}</p>
                    <p class="text-[10px] text-gray-500 truncate">${[city, dept, cp ? 'CP '+cp : ''].filter(Boolean).join(' · ')}</p>
                </div>`;

            li.addEventListener('mousedown', e => {
                e.preventDefault();
                // Solo rellena los campos que tienen valor — no pisa lo que el usuario puso
                input.value = street;
                if (city && cityVal) cityVal.value = city;
                if (dept && deptVal) deptVal.value = dept;
                if (cp   && cpVal)   cpVal.value   = cp;
                hide();
                input.blur();
            });

            list.appendChild(li);
        });

        list.classList.remove('hidden');
    }

    function hide() { list.classList.add('hidden'); }
    input.addEventListener('blur',  () => setTimeout(hide, 200));
    input.addEventListener('focus', () => {
        if (input.value.trim().length >= 3) search(input.value.trim());
    });
})();
</script>
