<?php

namespace App\Services\WhatsApp;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    private string $baseUrl;
    private string $secret;
    private int    $timeout;

    public function __construct()
    {
        $this->baseUrl = rtrim(config('whatsapp.service_url', 'http://localhost:3001'), '/');
        $this->secret  = config('whatsapp.secret', '');
        $this->timeout = (int) config('whatsapp.timeout', 10);
    }

    /**
     * Enviar mensaje de texto a un número de WhatsApp.
     * El número puede venir con o sin código de país, con o sin caracteres especiales.
     *
     * @throws \Exception si el servicio no está disponible o responde con error
     */
    public function send(string $phone, string $message): bool
    {
        $phone = $this->normalizePhone($phone);

        $response = $this->request('POST', '/send', [
            'phone'   => $phone,
            'message' => $message,
        ]);

        return $response['ok'] ?? false;
    }

    /**
     * Obtener estado actual de la conexión y QR (si aplica).
     * Retorna: ['status' => 'connected|qr_ready|disconnected', 'qr' => 'data:image/png;base64,...|null']
     */
    public function status(): array
    {
        try {
            $response = $this->request('GET', '/status');
            return $response;
        } catch (\Exception) {
            return ['status' => 'service_unavailable', 'qr' => null];
        }
    }

    /**
     * Cerrar sesión y generar nuevo QR.
     */
    public function disconnect(): bool
    {
        $response = $this->request('POST', '/disconnect');
        return $response['ok'] ?? false;
    }

    /**
     * Verificar si el servicio está disponible (health check).
     */
    public function isAvailable(): bool
    {
        try {
            $response = Http::timeout(3)->get($this->baseUrl . '/health');
            return $response->successful();
        } catch (\Exception) {
            return false;
        }
    }

    /**
     * Verificar si WhatsApp está conectado y listo para enviar.
     */
    public function isConnected(): bool
    {
        return ($this->status()['status'] ?? '') === 'connected';
    }

    // ─── Mensajes predefinidos del sistema ────────────────────────────────

    public function sendReceipt(string $phone, string $clientName, string $receiptNumber, string $amount): bool
    {
        $message =
            "TIZZILA APP\n\n" .
            "Hola *{$clientName}*, se registró tu pago.\n\n" .
            "📄 Recibo: *#REC-{$receiptNumber}*\n" .
            "💰 Monto: *\$ {$amount}*\n\n" .
            "Gracias por tu pago.";

        return $this->send($phone, $message);
    }

    public function sendDeliveryLink(string $phone, string $clientName, string $publicUrl): bool
    {
        $empresa = config('app.name');
        $message =
            "*{$empresa}*\n\n" .
            "Hola *{$clientName}* 🐣\n\n" .
            "Su pedido de pollitos está en camino.\n" .
            "Por favor valide la entrega aquí:\n\n" .
            $publicUrl;

        return $this->send($phone, $message);
    }

    public function sendDriverRouteLink(string $phone, string $driverName, string $routeId, ?int $totalBirds, int $stops, string $routeUrl): bool
    {
        $empresa = config('app.name');
        $message =
            "*{$empresa}*\n\n" .
            "🚚 Hola *{$driverName}*, tienes una ruta asignada.\n\n" .
            "Ruta: *#{$routeId}*\n" .
            "Pollitos: *" . ($totalBirds ?? 0) . "*\n" .
            "Paradas: *{$stops}*\n\n" .
            "Iniciar aquí:\n" .
            $routeUrl;

        return $this->send($phone, $message);
    }

    public function sendOverdueReminder(string $phone, string $clientName, float $totalOverdue, int $daysOverdue, int $invoiceCount): bool
    {
        $message =
            "*DISTRIAVICOLA SOFRAQ SAS*\n\n" .
            "Hola *{$clientName}*,\n\n" .
            "Le recordamos que tiene *{$invoiceCount} factura(s)* con saldo vencido:\n\n" .
            "💰 *Total vencido: \$ " . number_format($totalOverdue, 0, ',', '.') . "*\n" .
            "📅 Días de mora: *{$daysOverdue} días*\n\n" .
            "Por favor comuníquese con nosotros para coordinar su pago.\n\n" .
            "_TIZZILA APP · Sistema de Gestión Avícola_";

        return $this->send($phone, $message);
    }

    public function buildOverdueMessage(string $clientName, float $totalOverdue, int $daysOverdue, int $invoiceCount): string
    {
        return
            "*DISTRIAVICOLA SOFRAQ SAS*\n\n" .
            "Hola *{$clientName}*,\n\n" .
            "Le recordamos que tiene *{$invoiceCount} factura(s)* con saldo vencido:\n\n" .
            "💰 *Total vencido: \$ " . number_format($totalOverdue, 0, ',', '.') . "*\n" .
            "📅 Días de mora: *{$daysOverdue} días*\n\n" .
            "Por favor comuníquese con nosotros para coordinar su pago.\n\n" .
            "_TIZZILA APP · Sistema de Gestión Avícola_";
    }

    // ─── Helpers ──────────────────────────────────────────────────────────

    private function normalizePhone(string $phone): string
    {
        $digits = preg_replace('/[^0-9]/', '', $phone);

        // Si no tiene código de país, asumir Colombia (+57)
        if (strlen($digits) === 10) {
            $digits = '57' . $digits;
        }

        return $digits;
    }

    private function request(string $method, string $path, array $body = []): array
    {
        try {
            $http = Http::timeout($this->timeout)
                ->withHeaders(['X-WA-Secret' => $this->secret]);

            $response = match (strtoupper($method)) {
                'GET'  => $http->get($this->baseUrl . $path),
                'POST' => $http->post($this->baseUrl . $path, $body),
                default => throw new \InvalidArgumentException("Método HTTP no soportado: {$method}"),
            };

            if ($response->failed()) {
                $error = $response->json('error') ?? $response->body();
                throw new \Exception("WhatsApp service error [{$response->status()}]: {$error}");
            }

            return $response->json() ?? [];

        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            Log::error('[WhatsAppService] Servicio no disponible: ' . $e->getMessage());
            throw new \Exception('El servicio de WhatsApp no está disponible. Verifique que esté corriendo.');
        }
    }
}
