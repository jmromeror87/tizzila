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


namespace App\Services\Dispatch;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Client\Response;
use Carbon\Carbon;
use Exception;

class MapboxRouteService
{
    protected string $token;
    protected string $baseUrl = 'https://api.mapbox.com';

    public function __construct()
    {
        $this->token = config('services.mapbox.token');

        if (empty($this->token)) {
            throw new Exception('Token de Mapbox no configurado');
        }
    }

    /**
     * Genera una ruta optimizada y calcula ETA por parada
     *
     * @param string $originAddress
     * @param array $stops
     * @param string $departureTime (H:i)
     * @return array
     * @throws Exception
     */
    public function generateRoute(
        string $originAddress,
        array $stops,
        string $departureTime
    ): array {

        if (empty($originAddress)) {
            throw new Exception('Dirección de origen vacía');
        }

        if (empty($stops)) {
            throw new Exception('No hay paradas para generar la ruta');
        }

        /* ============================================================
         | 1️⃣ GEOCODIFICAR DIRECCIONES
         ============================================================ */

        $coordinates = $this->geocodeAddresses($originAddress, $stops);

        if (count($coordinates) < 2) {
            throw new Exception('Mapbox requiere origen + al menos un destino');
        }

        /* ============================================================
         | 2️⃣ OPTIMIZED TRIPS (SIN roundtrip ❗)
         ============================================================ */

        $coordinatesString = collect($coordinates)
            ->map(fn ($c) => "{$c['lng']},{$c['lat']}")
            ->implode(';');

        /** @var Response $response */
        $response = Http::timeout(20)->get(
            "{$this->baseUrl}/optimized-trips/v1/mapbox/driving/{$coordinatesString}",
            [
                'access_token' => $this->token,
                'source'       => 'first',
                'destination'  => 'last',
            ]
        );

        if (!$response->successful()) {
            Log::error('❌ Mapbox optimized-trips error', [
                'status' => $response->status(),
                'body'   => $response->json(),
            ]);

            throw new Exception('Error al generar ruta con Mapbox');
        }

        $data = $response->json();

        if (
            empty($data['trips'][0]['legs']) ||
            !is_array($data['trips'][0]['legs'])
        ) {
            Log::error('❌ Respuesta inválida de Mapbox', $data);
            throw new Exception('Respuesta inválida de Mapbox');
        }

        $trip = $data['trips'][0];
        $legs = $trip['legs'];

        /* ============================================================
         | 3️⃣ CALCULAR ETA POR PARADA
         ============================================================ */

        $departure   = Carbon::createFromFormat('H:i', $departureTime);
        $currentTime = $departure->copy();
        $routeStops  = [];

        foreach ($legs as $index => $leg) {

            if (!isset($stops[$index])) {
                continue;
            }

            $minutes = max(1, (int) round(($leg['duration'] ?? 0) / 60));
            $currentTime->addMinutes($minutes);

            $routeStops[] = [
                'customer_id'       => $stops[$index]['customer_id'],
                'address'           => $stops[$index]['address'],
                'chicks_quantity'   => $stops[$index]['chicks_quantity'],
                'stop_order'        => $index + 1,
                'estimated_arrival' => $currentTime->format('H:i'),
            ];
        }

        return [
            'total_distance_km'   => round(($trip['distance'] ?? 0) / 1000, 2),
            'total_duration_min' => round(($trip['duration'] ?? 0) / 60),
            'stops'              => $routeStops,
        ];
    }

    

    /**
     * Geocodifica origen + direcciones de clientes
     */
    protected function geocodeAddresses(string $origin, array $stops): array
    {
        $coordinates = [];

        // Origen
        $coordinates[] = $this->geocode($origin);

        // Clientes
        foreach ($stops as $stop) {
            if (!empty($stop['address'])) {
                $coordinates[] = $this->geocode($stop['address']);
            }
        }

        return $coordinates;
    }

    /**
     * Geocodificación simple
     *
     * @throws Exception
     */
    protected function geocode(string $address): array
    {
        /** @var Response $response */
        $response = Http::timeout(15)->get(
            "{$this->baseUrl}/geocoding/v5/mapbox.places/" . urlencode($address) . ".json",
            [
                'access_token' => $this->token,
                'limit'        => 1,
            ]
        );

        $json = $response->json();

        if (
            !$response->successful() ||
            empty($json['features'][0]['center'])
        ) {
            Log::warning('⚠️ Geocodificación fallida', [
                'address' => $address,
                'status'  => $response->status(),
                'body'    => $json,
            ]);

            throw new Exception("No se pudo geocodificar la dirección: {$address}");
        }

        return [
            'lng' => $json['features'][0]['center'][0],
            'lat' => $json['features'][0]['center'][1],
        ];
    }
}
