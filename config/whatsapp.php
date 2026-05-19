<?php

return [
    'service_url' => env('WHATSAPP_SERVICE_URL', 'http://localhost:3001'),
    'secret'      => env('WHATSAPP_SECRET', 'tizzila_wa_secret'),
    'timeout'     => env('WHATSAPP_TIMEOUT', 10),
];
