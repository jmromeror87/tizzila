{{-- Autocompletado de dirección estilo Google · Mapbox · Tizzila 2026 --}}
@php
    $addr    = $customer->address      ?? old('address', '');
    $city    = $customer->city         ?? old('city', '');
    $dept    = $customer->department   ?? old('department', '');
    $cp      = $customer->postal_code  ?? old('postal_code', '');
    $munId   = $customer->municipality_id ?? old('municipality_id', '');
@endphp

<div class="md:col-span-3 grid grid-cols-1 md:grid-cols-3 gap-4">

    {{-- ── Dirección con autocompletado ── --}}
    <div class="md:col-span-2 relative">
        <label class="block text-[9px] font-black uppercase tracking-widest text-zinc-500 mb-1.5">Dirección</label>
        <div class="relative">
            <input type="text" id="cust_addr_input" autocomplete="off"
                   value="{{ $addr }}"
                   placeholder="Ej: Calle 10 # 5-20 Bucaramanga..."
                   class="w-full px-4 py-3 rounded-xl bg-black/40 border border-white/10 text-sm text-white outline-none focus:border-yellow-500/50 pr-10">
            <div id="cust_addr_spinner" class="absolute inset-y-0 right-3 flex items-center pointer-events-none hidden">
                <i class="fas fa-circle-notch fa-spin text-yellow-500 text-xs"></i>
            </div>
        </div>
        <input type="hidden" name="address"       id="cust_addr_val"  value="{{ $addr }}">
        <input type="hidden" name="postal_code"   id="cust_cp_val"    value="{{ $cp }}">

        {{-- Dropdown --}}
        <ul id="cust_addr_list"
            class="absolute z-50 left-0 right-0 mt-1 bg-[#0d121f] border border-yellow-500/20 rounded-xl overflow-hidden shadow-2xl hidden divide-y divide-white/5">
        </ul>
    </div>

    {{-- ── Municipio DANE (manual) ── --}}
    <div>
        <label class="block text-[9px] font-black uppercase tracking-widest text-zinc-500 mb-1.5">Municipio (DANE)</label>
        <input type="text" name="municipality_id" id="cust_mun_val"
               value="{{ $munId }}"
               placeholder="Ej: 68001"
               class="w-full px-4 py-3 rounded-xl bg-black/40 border border-white/10 text-sm font-mono text-yellow-500 outline-none focus:border-yellow-500/50">
    </div>

    {{-- ── Teléfono ── --}}
    <div>
        <label class="block text-[9px] font-black uppercase tracking-widest text-zinc-500 mb-1.5">Teléfono</label>
        <input type="text" name="phone"
               value="{{ $customer->phone ?? old('phone', '') }}"
               placeholder="300 000 0000"
               class="w-full px-4 py-3 rounded-xl bg-black/40 border border-white/10 text-sm text-white outline-none focus:border-yellow-500/50">
    </div>

    {{-- ── Correo ── --}}
    <div>
        <label class="block text-[9px] font-black uppercase tracking-widest text-zinc-500 mb-1.5">Correo Electrónico</label>
        <input type="email" name="email"
               value="{{ $customer->email ?? old('email', '') }}"
               placeholder="correo@empresa.com"
               class="w-full px-4 py-3 rounded-xl bg-black/40 border border-white/10 text-sm text-white outline-none focus:border-yellow-500/50">
    </div>

    {{-- ── Ciudad / Departamento / CP (auto) ── --}}
    <div>
        <label class="block text-[9px] font-black uppercase tracking-widest text-zinc-500 mb-1.5">
            Ciudad <span class="text-yellow-500/40 normal-case font-bold text-[9px]">↑ auto</span>
        </label>
        <input type="text" id="cust_city_disp" readonly
               value="{{ $city }}"
               placeholder="—"
               class="w-full px-4 py-3 rounded-xl bg-black/20 border border-white/5 text-sm text-gray-300 outline-none cursor-default">
        <input type="hidden" name="city" id="cust_city_val" value="{{ $city }}">
    </div>

    <div>
        <label class="block text-[9px] font-black uppercase tracking-widest text-zinc-500 mb-1.5">
            Departamento <span class="text-yellow-500/40 normal-case font-bold text-[9px]">↑ auto</span>
        </label>
        <input type="text" id="cust_dept_disp" readonly
               value="{{ $dept }}"
               placeholder="—"
               class="w-full px-4 py-3 rounded-xl bg-black/20 border border-white/5 text-sm text-gray-300 outline-none cursor-default">
        <input type="hidden" name="department" id="cust_dept_val" value="{{ $dept }}">
    </div>

    <div>
        <label class="block text-[9px] font-black uppercase tracking-widest text-zinc-500 mb-1.5">
            Código Postal <span class="text-yellow-500/40 normal-case font-bold text-[9px]">↑ auto</span>
        </label>
        <input type="text" id="cust_cp_disp" readonly
               value="{{ $cp }}"
               placeholder="—"
               class="w-full px-4 py-3 rounded-xl bg-black/20 border border-white/5 text-sm text-gray-300 outline-none cursor-default">
    </div>

</div>

<script>
(function () {
    const TOKEN   = '{{ config("services.mapbox.token") }}';
    const input   = document.getElementById('cust_addr_input');
    const list    = document.getElementById('cust_addr_list');
    const spinner = document.getElementById('cust_addr_spinner');

    const addrVal  = document.getElementById('cust_addr_val');
    const cpVal    = document.getElementById('cust_cp_val');
    const cityVal  = document.getElementById('cust_city_val');
    const deptVal  = document.getElementById('cust_dept_val');

    const cityDisp = document.getElementById('cust_city_disp');
    const deptDisp = document.getElementById('cust_dept_disp');
    const cpDisp   = document.getElementById('cust_cp_disp');

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
            const ctx    = f.context || [];
            const place  = f.place_name || '';
            const city   = ctx.find(c => c.id.startsWith('place'))?.text
                        || (f.place_type?.[0] === 'place' ? f.text : '');
            const dept   = ctx.find(c => c.id.startsWith('region'))?.text || '';
            const cp     = ctx.find(c => c.id.startsWith('postcode'))?.text || '';
            const street = place.split(',')[0];

            const li = document.createElement('li');
            li.className = 'px-4 py-3 hover:bg-yellow-500/5 cursor-pointer transition-colors flex items-start gap-3 group';
            li.innerHTML = `
                <i class="fas fa-map-marker-alt text-yellow-500/60 group-hover:text-yellow-500 text-xs mt-0.5 w-4 shrink-0 transition-colors"></i>
                <div class="min-w-0">
                    <p class="text-xs font-black text-white truncate">${street}</p>
                    <p class="text-[10px] text-gray-500 truncate">${[city, dept, cp ? 'CP ' + cp : ''].filter(Boolean).join(' · ')}</p>
                </div>`;

            li.addEventListener('mousedown', e => {
                e.preventDefault();
                input.value    = street;
                addrVal.value  = street;
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
    input.addEventListener('blur',  () => setTimeout(hide, 150));
    input.addEventListener('focus', () => {
        if (input.value.trim().length >= 3) search(input.value.trim());
    });
})();
</script>
