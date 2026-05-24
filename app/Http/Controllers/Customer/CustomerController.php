<?php
/*
|--------------------------------------------------------------------------
| TIZZILA APP - © 2026 Tizzila App · Orquestación Avícola Inteligente
|--------------------------------------------------------------------------
| Proyecto privado desarrollado por:
| Ingeniero Jhoan Romero Rivera
| LinkedIn: https://linkedin.com/in/jmromeror87
|--------------------------------------------------------------------------
*/


namespace App\Http\Controllers\Customer;

use App\Models\Customer\Customer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Http;
use App\Models\Global\PaymentTerm;

class CustomerController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | 📋 Listado de Clientes
    |--------------------------------------------------------------------------
    */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $customers = Customer::withCount('invoices')
            ->withSum('invoices', 'total')
            ->withSum('invoices', 'balance')
            ->when($search, function ($query, $search) {
                return $query->where('name', 'like', "%{$search}%")
                    ->orWhere('identification_number', 'like', "%{$search}%");
            })
            ->orderBy('name', 'asc')
            ->paginate(15);

        return view('customers.index', compact('customers'));
    }

    /*
    |--------------------------------------------------------------------------
    | ➕ Formulario Crear
    |--------------------------------------------------------------------------
    */
    

public function create()
{
    $paymentTerms = PaymentTerm::orderBy('id')->get();

    return view('customers.create', compact('paymentTerms'));
}

    /*
    |--------------------------------------------------------------------------
    | 💾 Guardar Cliente
    |--------------------------------------------------------------------------
    */
    public function store(Request $request)
    {
        $validated = $request->validate(
            [
                'name' => 'required|string|max:255',

                'type_document_id' => 'required|string',

                'identification_number' => [
                    'required',
                    'string',
                    'max:20',
                    Rule::unique('customers')->where(function ($query) use ($request) {
                        return $query->where('type_document_id', $request->type_document_id);
                    }),
                ],

                'dv' => 'nullable|numeric|digits:1',

                'type_organization_id' => 'required|string',
                'type_regime_id'       => 'required|string',
                'type_liability_id'    => 'required|string',

                'municipality_id' => 'required|string|max:10',
                'address'         => 'required|string|max:255',

                'email' => [
                    'required',
                    'email',
                    Rule::unique('customers', 'email'),
                ],

                'phone'       => 'nullable|string|max:50',
                'postal_code' => 'nullable|string|max:10',
              
                'payment_term_id' => 'required|exists:payment_terms,id',
                'credit_limit'    => 'nullable|numeric|min:0',
            ]
        );

        $validated['credit_limit'] = $request->input('credit_limit', 0) ?? 0;

        /*
        |--------------------------------------------------------------------------
        | 🌍 GEOCODIFICAR DIRECCIÓN (COLOMBIA)
        |--------------------------------------------------------------------------
        */

        $fullAddress = $validated['address'] . ', Colombia';
        $coords = $this->geocodeAddress($fullAddress);

        if (!$coords) {
            return back()
                ->withInput()
                ->withErrors(['address' => 'No se pudo validar la dirección. Verifique que sea correcta.']);
        }

        $validated['latitude']  = $coords['latitude'];
        $validated['longitude'] = $coords['longitude'];

        Customer::create($validated);

        return redirect()
            ->route('customers.index')
            ->with('success', 'Cliente registrado correctamente.');
    }

    /*
    |--------------------------------------------------------------------------
    | 🔍 Verificar si Cliente Existe
    |--------------------------------------------------------------------------
    */
    public function checkExists(Request $request)
    {
        $request->validate([
            'type_document_id'      => 'required',
            'identification_number' => 'required',
        ]);

        $customer = Customer::where('type_document_id', $request->type_document_id)
            ->where('identification_number', $request->identification_number)
            ->first();

        if ($customer) {
            return response()->json([
                'exists'      => true,
                'customer_id' => $customer->id,
                'name'        => $customer->name,
            ]);
        }

        return response()->json(['exists' => false]);
    }

    /*
    |--------------------------------------------------------------------------
    | 👁 Mostrar Cliente
    |--------------------------------------------------------------------------
    */
    public function show(Customer $customer)
    {
        $customer->load(['invoices.payments', 'paymentTerm']);

        $totalInvoiced  = $customer->invoices->sum('total');
        $totalPaid      = $customer->invoices->sum(fn($i) => $i->total - $i->balance);
        $totalBalance   = $customer->invoices->sum('balance');
        $totalInvoices  = $customer->invoices->count();
        $paidCount      = $customer->invoices->where('balance', 0)->count();
        $overdueCount   = $customer->invoices->where('payment_status', 'overdue')->count();
        $overdueBalance = $customer->invoices->where('payment_status', 'overdue')->sum('balance');
        $pendingCount   = $customer->invoices->whereIn('payment_status', ['pending','partial'])->count();

        $scorePct = $totalInvoiced > 0
            ? round(($totalPaid / $totalInvoiced) * 100, 1)
            : 0;

        $scoreLabel = match(true) {
            $scorePct >= 90 => ['EXCELENTE', 'text-emerald-400', 'bg-emerald-500/10', 'border-emerald-500/20'],
            $scorePct >= 70 => ['BUENO',     'text-blue-400',    'bg-blue-500/10',    'border-blue-500/20'],
            $scorePct >= 40 => ['REGULAR',   'text-yellow-400',  'bg-yellow-500/10',  'border-yellow-500/20'],
            default         => ['CRÍTICO',   'text-red-400',     'bg-red-500/10',     'border-red-500/20'],
        };

        return view('customers.show', compact(
            'customer',
            'totalInvoiced', 'totalPaid', 'totalBalance',
            'totalInvoices', 'paidCount', 'overdueCount', 'overdueBalance', 'pendingCount',
            'scorePct', 'scoreLabel'
        ));
    }

    /*
    |--------------------------------------------------------------------------
    | ✏️ Editar Cliente
    |--------------------------------------------------------------------------
    */
    public function edit(Customer $customer)
{
    $paymentTerms = PaymentTerm::orderBy('id')->get();

    $hasOverdueInvoices = $customer->invoices()
        ->where('status', 'overdue')
        ->exists();

    return view('customers.edit', compact(
        'customer',
        'paymentTerms',
        'hasOverdueInvoices'
    ));
}
    /*
    |--------------------------------------------------------------------------
    | 🔄 Actualizar Cliente
    |--------------------------------------------------------------------------
    */
    public function update(Request $request, Customer $customer)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',

        'type_document_id' => 'required|string',

        'identification_number' => [
            'required',
            'string',
            'max:20',
            Rule::unique('customers')
                ->ignore($customer->id)
                ->where(function ($query) use ($request) {
                    return $query->where('type_document_id', $request->type_document_id);
                }),
        ],

        'dv'                   => 'nullable|numeric|digits:1',
        'type_organization_id' => 'required|string',
        'type_regime_id'       => 'required|string',
        'type_liability_id'    => 'required|string',

        'email' => [
            'required',
            'email',
            Rule::unique('customers', 'email')->ignore($customer->id),
        ],

        'phone'           => 'nullable|string|max:50',
        'municipality_id' => 'required|string|max:10',
        'address'         => 'required|string|max:255',
        'payment_term_id'        => 'required|exists:payment_terms,id',
        'credit_limit'           => 'nullable|numeric|min:0',
        'is_fixed_client'        => 'nullable|boolean',
        'fixed_weekly_quantity'  => 'nullable|integer|min:0',
        'delivery_zone'          => 'nullable|in:norte,sur,oriente,occidente,centro',
    ]);

    $validated['credit_limit']          = $request->input('credit_limit', 0) ?? 0;
    $validated['is_fixed_client']       = $request->boolean('is_fixed_client');
    $validated['fixed_weekly_quantity'] = $request->input('fixed_weekly_quantity', 0) ?? 0;
    $validated['delivery_zone']         = $request->input('delivery_zone') ?: null;

    /*
    |--------------------------------------------------------------------------
    | 🌍 RE-GEOCODIFICAR SOLO SI CAMBIA DIRECCIÓN
    |--------------------------------------------------------------------------
    */

    if ($validated['address'] !== $customer->address) {

        $fullAddress = $validated['address'] . ', Colombia';
        $coords = $this->geocodeAddress($fullAddress);

        if (!$coords) {
            return back()
                ->withInput()
                ->withErrors(['address' => 'No se pudo validar la nueva dirección.']);
        }

        $validated['latitude']  = $coords['latitude'];
        $validated['longitude'] = $coords['longitude'];
    }

    /*
    |--------------------------------------------------------------------------
    | 🔄 ACTUALIZAR CLIENTE
    |--------------------------------------------------------------------------
    */

    $customer->update($validated);

    return redirect()
        ->route('customers.index')
        ->with('info', 'Datos del cliente actualizados con éxito.');
}

    /*
    |--------------------------------------------------------------------------
    | 🗑 Eliminar Cliente
    |--------------------------------------------------------------------------
    */
    public function destroy(Customer $customer)
    {
        $customer->update(['is_active' => !$customer->is_active]);

        $estado = $customer->is_active ? 'activado' : 'desactivado';

        return redirect()
            ->route('customers.index')
            ->with('success', "Cliente {$customer->name} {$estado} correctamente.");
    }

    /*
    |--------------------------------------------------------------------------
    | 🌍 Servicio Geocoding Mapbox
    |--------------------------------------------------------------------------
    */
    private function geocodeAddress(string $address): ?array
    {
        $response = Http::get(
            "https://api.mapbox.com/geocoding/v5/mapbox.places/" .
                urlencode($address) . ".json",
            [
                'access_token' => config('services.mapbox.token'),
                'limit' => 1,
                'country' => 'CO'
            ]
        );

        if (!$response->successful()) {
            return null;
        }

        $data = $response->json();

        if (empty($data['features'][0])) {
            return null;
        }

        return [
            'longitude' => $data['features'][0]['center'][0],
            'latitude'  => $data['features'][0]['center'][1],
        ];
    }
}
