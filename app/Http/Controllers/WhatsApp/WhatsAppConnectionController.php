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


namespace App\Http\Controllers\WhatsApp;

use App\Http\Controllers\Controller;
use App\Services\WhatsApp\WhatsAppService;
use Illuminate\Http\JsonResponse;

class WhatsAppConnectionController extends Controller
{
    public function __construct(private WhatsAppService $whatsapp) {}

    /**
     * Vista de configuración / escaneo de QR
     */
    public function index()
    {
        return view('whatsapp.connection');
    }

    /**
     * API interna: estado actual (polling desde el frontend)
     */
    public function status(): JsonResponse
    {
        $data = $this->whatsapp->status();

        return response()->json($data);
    }

    /**
     * Cerrar sesión de WhatsApp y generar nuevo QR
     */
    public function disconnect(): JsonResponse
    {
        try {
            $this->whatsapp->disconnect();
            return response()->json(['ok' => true, 'message' => 'Sesión cerrada. Escanea el nuevo QR.']);
        } catch (\Exception $e) {
            return response()->json(['ok' => false, 'error' => $e->getMessage()], 500);
        }
    }
}
