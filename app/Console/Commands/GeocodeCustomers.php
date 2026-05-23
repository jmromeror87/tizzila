<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class GeocodeCustomers extends Command
{
    protected $signature   = 'customers:geocode {--limit=50 : Máximo de clientes a procesar}';
    protected $description = 'Geocodifica clientes sin coordenadas usando Mapbox';

    public function handle(): void
    {
        $clientes = DB::table('customers')
            ->where(function ($q) {
                $q->whereNull('latitude')->orWhere('latitude', 0);
            })
            ->select('id', 'name', 'address', 'municipality_id')
            ->limit((int) $this->option('limit'))
            ->get();

        if ($clientes->isEmpty()) {
            $this->info('Todos los clientes ya tienen coordenadas.');
            return;
        }

        $this->info("Geocodificando {$clientes->count()} clientes...");
        $bar = $this->output->createProgressBar($clientes->count());
        $bar->start();

        $ok = 0;
        $fail = 0;

        foreach ($clientes as $cliente) {
            $coords = $this->geocode($cliente->address . ', Colombia');

            if ($coords) {
                DB::table('customers')->where('id', $cliente->id)->update([
                    'latitude'  => $coords['latitude'],
                    'longitude' => $coords['longitude'],
                ]);
                $ok++;
            } else {
                $fail++;
            }

            $bar->advance();
            usleep(200000); // 0.2s entre llamadas para no exceder rate limit
        }

        $bar->finish();
        $this->newLine();
        $this->info("Listo: {$ok} geocodificados, {$fail} fallidos.");

        $pendientes = DB::table('customers')
            ->where(function ($q) {
                $q->whereNull('latitude')->orWhere('latitude', 0);
            })
            ->count();

        if ($pendientes > 0) {
            $this->warn("{$pendientes} clientes aún sin coordenadas. Corre el comando de nuevo.");
        }
    }

    private function geocode(string $address): ?array
    {
        $response = Http::get(
            'https://api.mapbox.com/geocoding/v5/mapbox.places/' . urlencode($address) . '.json',
            [
                'access_token' => config('services.mapbox.token'),
                'limit'        => 1,
                'country'      => 'CO',
            ]
        );

        if (!$response->successful()) return null;

        $data = $response->json();

        if (empty($data['features'][0])) return null;

        return [
            'longitude' => $data['features'][0]['center'][0],
            'latitude'  => $data['features'][0]['center'][1],
        ];
    }
}
