<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Entrega ya confirmada</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700;900&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { background: #050507; color: #fff; font-family: 'Inter', sans-serif; min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 1.5rem; }
        .card { background: #0f0f14; border: 1px solid rgba(255,255,255,0.06); border-radius: 1.5rem; padding: 2.5rem; max-width: 400px; width: 100%; text-align: center; }
        .icon { font-size: 3rem; margin-bottom: 1.5rem; }
        h1 { font-size: 1.25rem; font-weight: 900; text-transform: uppercase; letter-spacing: 0.05em; color: #eab308; margin-bottom: 0.75rem; }
        p { font-size: 0.8rem; color: #71717a; line-height: 1.6; }
        .customer { font-size: 1rem; font-weight: 700; color: #fff; margin: 1rem 0 0.25rem; }
        .date { font-size: 0.7rem; color: #52525b; text-transform: uppercase; letter-spacing: 0.1em; margin-top: 1.5rem; }
    </style>
</head>
<body>
    <div class="card">
        <div class="icon">✅</div>
        <h1>Entrega ya confirmada</h1>
        <p class="customer">{{ $stop->customer->name }}</p>
        <p>Esta entrega ya fue verificada y registrada correctamente en el sistema.</p>
        @if($stop->client_confirmed_at)
            <p class="date">Confirmada el {{ \Carbon\Carbon::parse($stop->client_confirmed_at)->format('d/m/Y \a \l\a\s H:i') }}</p>
        @endif
    </div>
</body>
</html>
