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


namespace App\Services\Poultry;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class VisionService
{
    /**
     * Extrae datos de una imagen de pedido/factura avícola usando GPT-4o
     */
    public function extractData(string $imagePath): array
    {
        $mode = env('POULTRY_IA_MODE', 'production');

        if ($mode === 'simulation') {
            return $this->getMockData();
        }

        if (!is_file($imagePath)) {
            return [
                'error'   => 'Image file not found',
                'batches' => [],
            ];
        }

        try {
            $imageData = base64_encode(file_get_contents($imagePath));

            /** @var \Illuminate\Http\Client\Response $response */
            $response = Http::withToken(config('services.openai.key'))
                ->timeout(120)
                ->post('https://api.openai.com/v1/chat/completions', [
                    'model' => 'gpt-4o',
                    'temperature' => 0,
                    'max_tokens' => 2000,
                    'messages' => [
                        [
                            'role' => 'user',
                            'content' => [
                                [
                                    'type' => 'text',
                                    'text' => $this->prompt(),
                                ],
                                [
                                    'type' => 'image_url',
                                    'image_url' => [
                                        'url' => 'data:image/jpeg;base64,' . $imageData,
                                    ],
                                ],
                            ],
                        ],
                    ],
                ]);

            if ($response->failed()) {
                throw new \Exception(
                    'OpenAI error ' . $response->status() . ': ' . $response->body()
                );
            }

            $payload = $response->json();

            // 🧠 Texto del modelo (Chat Completions)
            $rawText = data_get($payload, 'choices.0.message.content');

            if (!$rawText) {
                throw new \Exception('OpenAI response without content');
            }

            // 🔎 Extraer JSON aunque venga con markdown
            $json = $this->extractJsonFromText($rawText);

            if (!$json) {
                throw new \Exception('Could not parse JSON from OpenAI response');
            }

            // Guardamos texto crudo para auditoría
            $json['raw_text'] = $rawText;

            $json = $this->correctSuspiciousYears($json);

            return $json;

        } catch (\Throwable $e) {
            Log::error('VISION SERVICE FAILED', [
                'message' => $e->getMessage(),
                'path'    => $imagePath,
            ]);

            return [
                'error'   => $e->getMessage(),
                'batches' => [],
            ];
        }
    }

    private function correctSuspiciousYears(array $data): array
{
    $currentYear = now()->year;

    // 1️⃣ Corregir document_date
    if (!empty($data['document_date'])) {
        $year = (int) substr($data['document_date'], 0, 4);

        // Si es demasiado antiguo (ej: 2015) pero estamos en 2026
        if ($year < $currentYear - 2) {
            $monthDay = substr($data['document_date'], 5); // MM-DD
            $data['document_date'] = $currentYear . '-' . $monthDay;
        }
    }

    // 2️⃣ Corregir batches
    if (!empty($data['batches']) && is_array($data['batches'])) {
        foreach ($data['batches'] as &$batch) {
            if (!empty($batch['delivery_date'])) {

                $year = (int) substr($batch['delivery_date'], 0, 4);

                if ($year < $currentYear - 2) {
                    $monthDay = substr($batch['delivery_date'], 5);
                    $batch['delivery_date'] = $currentYear . '-' . $monthDay;
                }
            }
        }
    }

    return $data;
}


    /**
     * Extrae JSON aunque la IA lo envíe con ```json o texto adicional
     */
    private function extractJsonFromText(string $text): ?array
    {
        // Limpieza básica de markdown
        $text = str_replace(['```json', '```'], '', $text);

        if (preg_match('/\{[\s\S]*\}/', $text, $matches)) {
            $decoded = json_decode($matches[0], true);
            return is_array($decoded) ? $decoded : null;
        }

        return null;
    }

    /**
     * PROMPT DEFINITIVO (alineado con el Job y la BD)
     */
    private function prompt(): string
{
    return <<<PROMPT
Analiza la imagen de este pedido avícola.

IMPORTANTE:
El documento contiene un encabezado institucional con datos como:
"VERSIÓN", "VIGENCIA", códigos internos y año del formato.
Esa información corresponde a la plantilla del proveedor y NO debe usarse como fecha del pedido.

Debes ignorar completamente cualquier fecha asociada a:
- VIGENCIA
- VERSIÓN
- Códigos internos del formulario
- Año impreso en el encabezado

La información válida comienza en la sección que contiene:
- "PEDIDO"
- "PEDIDO POR"
- "FACTURAR A"
- Tabla de programación y fecha de entrega

Reglas de fechas:
- Si una fecha aparece manuscrita como 09/marzo/26, el año es 2026.
- Si el año tiene dos dígitos (ej: 26), interpretarlo como 2026.
- No asumir el año 2015 salvo que esté explícitamente escrito en el bloque del pedido.

Devuelve EXCLUSIVAMENTE un JSON válido con la siguiente estructura EXACTA:

{
  "provider_order_number": "string|null",
  "document_date": "YYYY-MM-DD|null",

  "product_flags": {
    "bb": boolean,
    "lsl": boolean,
    "lohmann": boolean,
    "engorde": boolean,
    "pollita_levantada": boolean
  },

  "vaccines": {
    "marek": boolean,
    "gumboro": boolean,
    "others": "string|null"
  },

  "pricing": {
    "unit_cost": number|null,
    "fonav_cost": number|null,
    "vaccine_cost": number|null
  },

  "packaging_type": "string|null",

  "batches": [
    {
      "delivery_date": "YYYY-MM-DD",
      "approved_quantity": number
    }
  ],

  "notes": "string|null"
}

REGLAS ESTRICTAS:
- Cada fila de la tabla es un batch independiente
- NO sumar ni agrupar entregas
- Checkboxes marcados = true
- Checkboxes vacíos = false
- Fechas SIEMPRE en formato ISO
- Si el año tiene dos dígitos (ej: 26), usar 2026
- No usar fechas del encabezado institucional
- No inventar datos
- Devuelve SOLO el JSON, sin texto adicional
PROMPT;
}


    /**
     * Datos simulados (solo desarrollo)
     */
    private function getMockData(): array
    {
        return [
            'provider_order_number' => 'SIM-001',
            'document_date' => now()->format('Y-m-d'),
            'product_flags' => [
                'bb' => true,
                'lsl' => false,
                'lohmann' => false,
                'engorde' => true,
                'pollita_levantada' => false,
            ],
            'vaccines' => [
                'marek' => true,
                'gumboro' => false,
                'others' => null,
            ],
            'pricing' => [
                'unit_cost' => 2470,
                'fonav_cost' => 37.8,
                'vaccine_cost' => 108,
            ],
            'batches' => [
                [
                    'delivery_date' => now()->addDays(7)->format('Y-m-d'),
                    'approved_quantity' => 3000,
                ],
            ],
            'notes' => 'Datos simulados',
        ];
    }
}
