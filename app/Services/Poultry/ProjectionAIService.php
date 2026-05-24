<?php
/*
|--------------------------------------------------------------------------
| TIZZILA APP - © 2026 Tizzila App · Orquestación Avícola Inteligente
|--------------------------------------------------------------------------
*/

namespace App\Services\Poultry;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ProjectionAIService
{
    /**
     * Genera análisis predictivo narrativo usando GPT-4o.
     * Recibe datos históricos y contexto del negocio.
     */
    public function analyze(array $historicalData, array $nextMonths, array $fixedClients, array $marketEvents = []): array
    {
        $prompt = $this->buildPrompt($historicalData, $nextMonths, $fixedClients, $marketEvents);

        try {
            $response = Http::withToken(config('services.openai.key'))
                ->timeout(30)
                ->post('https://api.openai.com/v1/chat/completions', [
                    'model' => 'gpt-4o',
                    'temperature' => 0.3,
                    'max_tokens' => 800,
                    'messages' => [
                        [
                            'role' => 'system',
                            'content' => 'Eres un analista de datos experto en distribución avícola colombiana para DISTRIAVICOLA SOFRAQ SAS. Analizas pedidos de pollitos BB y gallinas de postura. Respondes SIEMPRE en JSON válido con las claves exactas indicadas. Sé conciso, directo y usa lenguaje de negocio colombiano.',
                        ],
                        [
                            'role' => 'user',
                            'content' => $prompt,
                        ],
                    ],
                ]);

            if (!$response->successful()) {
                Log::warning('ProjectionAI: OpenAI error', ['status' => $response->status()]);
                return $this->fallbackAnalysis($historicalData, $nextMonths);
            }

            $content = $response->json('choices.0.message.content', '');
            $content = preg_replace('/```json|```/', '', $content);
            $decoded = json_decode(trim($content), true);

            if (!$decoded || !isset($decoded['resumen'])) {
                return $this->fallbackAnalysis($historicalData, $nextMonths);
            }

            return $decoded;
        } catch (\Exception $e) {
            Log::error('ProjectionAI: ' . $e->getMessage());
            return $this->fallbackAnalysis($historicalData, $nextMonths);
        }
    }

    private function buildPrompt(array $historical, array $nextMonths, array $fixedClients, array $marketEvents = []): string
    {
        $histJson   = json_encode($historical,    JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        $nextJson   = json_encode($nextMonths,    JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        $fixedJson  = json_encode($fixedClients,  JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        $eventsJson = json_encode($marketEvents,  JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        return <<<PROMPT
Analiza estos datos históricos de pedidos de aves de DISTRIAVICOLA SOFRAQ SAS y genera proyección predictiva.

## HISTORIAL MENSUAL (últimos meses registrados):
{$histJson}

## PEDIDOS YA PROGRAMADOS (próximos 3 meses):
{$nextJson}

## CLIENTES FIJOS (demanda comprometida):
{$fixedJson}

## NOVEDADES DE MERCADO ACTIVAS (factores externos que afectan la demanda):
{$eventsJson}

Considera:
- La empresa opera lunes y jueves (2 despachos por semana)
- Tipos de ave: Pollito BB, Lohmann Brown, Lohmann LSL, Engorde
- La demanda real puede diferir de lo programado si hay cancelaciones o excedentes de PRONAVICOLA

Responde ÚNICAMENTE con este JSON (sin texto adicional):
{
  "resumen": "2-3 oraciones sobre la tendencia general del negocio",
  "tendencia": "creciendo|estable|declinando",
  "tendencia_pct": 12.5,
  "alerta": "texto de alerta principal o null si no hay",
  "alerta_tipo": "riesgo|oportunidad|info|null",
  "recomendaciones": ["recomendación 1", "recomendación 2", "recomendación 3"],
  "prediccion_meses": [
    {"mes": "Junio 2026", "cantidad_sugerida": 90000, "confianza": "alta|media|baja", "razon": "corta explicación"},
    {"mes": "Julio 2026",  "cantidad_sugerida": 95000, "confianza": "alta|media|baja", "razon": "corta explicación"},
    {"mes": "Agosto 2026", "cantidad_sugerida": 98000, "confianza": "alta|media|baja", "razon": "corta explicación"}
  ]
}
PROMPT;
    }

    private function fallbackAnalysis(array $historical, array $nextMonths): array
    {
        $totals = collect($historical)->pluck('total')->filter()->values();
        $avg    = $totals->average() ?? 0;
        $trend  = $totals->count() >= 2
            ? (($totals->last() - $totals->first()) / max(1, $totals->first())) * 100
            : 0;

        return [
            'resumen'          => 'Análisis basado en datos históricos del sistema. Se requiere conexión a IA para proyección avanzada.',
            'tendencia'        => $trend > 5 ? 'creciendo' : ($trend < -5 ? 'declinando' : 'estable'),
            'tendencia_pct'    => round(abs($trend), 1),
            'alerta'           => null,
            'alerta_tipo'      => null,
            'recomendaciones'  => ['Configura clientes fijos para mejorar la proyección.', 'Registra más pedidos históricos para mayor precisión.'],
            'prediccion_meses' => collect($nextMonths)->map(fn($m, $i) => [
                'mes'               => $m['month'],
                'cantidad_sugerida' => (int) round($avg ?: ($m['total_programmed'] ?: 0)),
                'confianza'         => 'baja',
                'razon'             => 'Promedio histórico disponible.',
            ])->values()->toArray(),
        ];
    }
}
