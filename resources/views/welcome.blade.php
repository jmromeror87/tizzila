{{--
|--------------------------------------------------------------------------
| TIZZILA APP - © 2026 Tizzila App · Orquestación Avícola Inteligente
|--------------------------------------------------------------------------
| Proyecto privado desarrollado por:
| Ingeniero Jhoan Romero Rivera
| LinkedIn: https://linkedin.com/in/jmromeror87
|--------------------------------------------------------------------------
--}}

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tizzila App | Avícola Inteligente</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <!-- Google Analytics 4 -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-XXXXXXX"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());
  gtag('config', 'G-XXXXXXX');
</script>


      <style>
        :root {
            --bg-dark: #0f172a;
            --bg-card: #1e293b;
            --primary-yellow: #FFB300;
            --accent-yellow-light: #FFD54F;
            --text-muted: #cbd5e1; /* FIX VISIBILIDAD */
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-dark);
            color: #ffffff;
            overflow-x: hidden;
        }

        /* Font helpers */
        .fw-800 { font-weight: 800; }
        .fw-900 { font-weight: 900; }

        /* FIX Bootstrap muted */
        .text-muted {
            color: var(--text-muted) !important;
            opacity: 1 !important;
        }

        section { scroll-margin-top: 120px; }

        .text-primary-yellow { color: var(--primary-yellow) !important; }

        .bg-primary-yellow-subtle {
            background-color: rgba(255,179,0,.15) !important;
            color: var(--primary-yellow) !important;
        }

        .border-primary-yellow { border-color: var(--primary-yellow) !important; }

        .text-gradient-yellow {
            background: linear-gradient(90deg, var(--primary-yellow), var(--accent-yellow-light));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .btn-primary-custom {
            background: linear-gradient(135deg, var(--primary-yellow), var(--accent-yellow-light));
            color: var(--bg-dark);
            border: none;
            padding: 14px 34px;
            border-radius: 14px;
            font-weight: 800;
            box-shadow: 0 10px 30px rgba(255,179,0,.35);
            transition: all .3s ease;
        }

        .btn-primary-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 18px 40px rgba(255,179,0,.55);
        }

        .navbar {
            background-color: rgba(15,23,42,.95) !important;
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255,255,255,.1);
            padding: 20px 0;
        }

        .hero-section {
            padding: 160px 0 110px;
            background: radial-gradient(circle at top right, rgba(255,179,0,.08), transparent 55%);
        }

        .dashboard-preview {
            background: var(--bg-card);
            border-radius: 26px;
            padding: 22px;
            border: 1px solid rgba(255,255,255,.08);
            box-shadow: 0 40px 90px rgba(0,0,0,.55);
            position: relative;
        }

        .dashboard-preview::before {
            content: '';
            position: absolute;
            inset: -15%;
            background: var(--primary-yellow);
            opacity: .12;
            filter: blur(100px);
            z-index: -1;
        }

        .bar-chart {
            display: flex;
            align-items: flex-end;
            gap: 8px;
            height: 150px;
            margin-bottom: 20px;
        }

        .bar {
            flex: 1;
            background: rgba(255,179,0,.2);
            border-top: 3px solid var(--primary-yellow);
            border-radius: 6px 6px 0 0;
        }

        .bento-card {
            background: var(--bg-card);
            border-radius: 26px;
            padding: 32px;
            border: 1px solid rgba(255,255,255,.06);
            height: 100%;
            transition: all .3s ease;
        }

        .bento-card:hover {
            border-color: var(--primary-yellow);
            box-shadow: 0 20px 40px rgba(255,179,0,.12);
        }

        .icon-box {
            width: 52px;
            height: 52px;
            background: rgba(255,179,0,.15);
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary-yellow);
            font-size: 1.4rem;
            margin-bottom: 18px;
        }

        .pain-section {
            background-color: rgba(30,41,59,.55);
            padding: 90px 0;
        }

        .cta-yellow-box {
            background: linear-gradient(135deg, var(--primary-yellow), var(--accent-yellow-light));
            color: var(--bg-dark);
        }

        .btn-dark-custom {
            background-color: var(--bg-dark);
            color: var(--primary-yellow);
            border-radius: 14px;
            font-weight: 800;
            border: 2px solid var(--bg-dark);
        }

        .btn-dark-custom:hover {
            background: transparent;
            color: var(--bg-dark);
        }

        .footer {
            padding: 70px 0;
            border-top: 1px solid rgba(255,255,255,.08);
            color: var(--text-muted);
        }
    </style>
</head>
<a href="https://wa.me/573001234567?text=Hola,%20quiero%20una%20demo%20de%20Tizzila%20App"
   target="_blank"
   class="whatsapp-float">
    <i class="fab fa-whatsapp"></i>
</a>

<style>
.whatsapp-float {
    position: fixed;
    bottom: 25px;
    right: 25px;
    width: 60px;
    height: 60px;
    background: #25d366;
    color: #fff;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    box-shadow: 0 10px 30px rgba(0,0,0,.4);
    z-index: 9999;
}
.whatsapp-float:hover {
    background: #1ebe5d;
    transform: translateY(-3px);
}
</style>
<script>
document.getElementById('demoForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const form = e.target;
    const data = {
        name: form.name.value,
        email: form.email.value,
        phone: form.phone.value,
        company: form.company.value,
        source: 'Landing Tizzila'
    };

    /* 1️⃣ Google Analytics Event */
    if (typeof gtag === 'function') {
        gtag('event', 'lead_submit', {
            event_category: 'conversion',
            event_label: 'Demo Tizzila'
        });
    }

    /* 2️⃣ CRM Webhook (Zapier / n8n / HubSpot) */
    fetch('https://YOUR-WEBHOOK-URL', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data)
    }).catch(() => {});

    /* 3️⃣ Redirect WhatsApp */
    const msg = `Hola, soy ${data.name} de ${data.company}. Quiero una demo de Tizzila App.`;
    const phone = '57'; // TU WHATSAPP
    window.location.href = `https://wa.me/${phone}?text=${encodeURIComponent(msg)}`;
});
</script>


<body>

    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container">
            <a class="navbar-brand fw-bold fs-3 d-flex align-items-center gap-2" href="#">
                <i class="fas fa-egg text-primary-yellow fs-2"></i>
                <span>Tizzila<span class="text-primary-yellow">App</span></span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item"><a class="nav-link px-3" href="#modulos">Módulos</a></li>
                    <li class="nav-item"><a class="nav-link px-3" href="#ia">Motor IA</a></li>
                    <li class="nav-item ms-lg-4">
                        <a class="btn btn-primary-custom" href="{{ route('login') }}">Ingresar</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <span class="badge rounded-pill bg-primary-yellow-subtle border border-primary-yellow px-3 py-2 mb-4 fw-bold">
                        <i class="fas fa-globe-americas me-2"></i>Líder en Colombia y LATAM
                    </span>
                    <h1 class="display-3 fw-800 mb-4">Orquestación <span class="text-gradient-yellow">Avícola Inteligente</span></h1>
                    <p class="lead text-muted mb-5">
                        Centralice su operación de alto volumen, elimine los errores de Excel y proteja su margen con el motor de predicción de precios más robusto del sector.
                    </p>
                    <div class="d-flex gap-3 flex-column flex-sm-row">
                        <button class="btn btn-primary-custom px-5 py-3 shadow-lg">Optimizar Operación <i class="fas fa-arrow-right ms-2"></i></button>
                        <button class="btn btn-outline-light border-secondary px-4 py-3 rounded-4 fw-bold">Ver Video Demo</button>
                    </div>
                </div>
                <div class="col-lg-6 mt-5 mt-lg-0 z-1">
                    <div class="dashboard-preview">
                        <div class="d-flex justify-content-between mb-4">
                            <h6 class="text-uppercase text-muted small fw-bold">Previsión Semanal de Margen</h6>
                            <i class="fas fa-ellipsis-h text-muted"></i>
                        </div>
                        <div class="bar-chart">
                            <div class="bar" style="height: 60%;"></div>
                            <div class="bar" style="height: 85%;"></div>
                            <div class="bar" style="height: 45%;"></div>
                            <div class="bar" style="height: 90%;"></div>
                            <div class="bar" style="height: 70%;"></div>
                            <div class="bar" style="height: 95%;"></div>
                        </div>
                        <div class="row g-3">
                            <div class="col-6">
                                <div class="p-3 rounded-4" style="background: #0f172a; border: 1px solid rgba(255,179,0,0.2);">
                                    <p class="small text-muted mb-1 text-uppercase fw-bold">Precio Pollito (IA)</p>
                                    <h4 class="mb-0 text-primary-yellow fw-bold">$3.450 <small class="fs-6 fw-normal">+4.2%</small></h4>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="p-3 rounded-4" style="background: #0f172a; border: 1px solid rgba(255,179,0,0.2);">
                                    <p class="small text-muted mb-1 text-uppercase fw-bold">Utilidad Neta</p>
                                    <h4 class="mb-0 text-white fw-bold">18.5%</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section id="demo" class="py-5 mb-5">
  <div class="container">
    <div class="cta-yellow-box p-5 rounded-5 shadow-lg">
      <div class="row align-items-center">
        
        <div class="col-lg-6 mb-4 mb-lg-0">
          <h2 class="display-5 fw-900 mb-3">
            Solicite una Demo Estratégica
          </h2>
          <p class="fw-bold opacity-75">
            Evaluamos su operación y le mostramos cuánto margen puede recuperar.
          </p>
          <ul class="mt-4 fw-bold">
            <li>✔ Forecast con IA</li>
            <li>✔ Control de precio del pollito</li>
            <li>✔ Rentabilidad en tiempo real</li>
          </ul>
        </div>

        <div class="col-lg-6">
          <form id="demoForm" class="bg-dark p-4 rounded-4">

            <div class="mb-3">
              <input type="text" class="form-control" name="name" placeholder="Nombre completo" required>
            </div>

            <div class="mb-3">
              <input type="email" class="form-control" name="email" placeholder="Correo empresarial" required>
            </div>

            <div class="mb-3">
              <input type="tel" class="form-control" name="phone" placeholder="WhatsApp / Teléfono" required>
            </div>

            <div class="mb-3">
              <input type="text" class="form-control" name="company" placeholder="Empresa / Distribuidora" required>
            </div>

            <button type="submit" class="btn btn-primary-custom w-100 py-3 mt-3">
              Solicitar Demo
            </button>

            <p class="small text-muted mt-3 text-center">
              Respuesta en menos de 24 horas
            </p>

          </form>
        </div>

      </div>
    </div>
  </div>
</section>

    <section class="pain-section">
        <div class="container text-center mb-5">
            <h2 class="fw-bold display-5">Diseñado para la Realidad del Distribuidor</h2>
            <p class="text-muted fs-5">Deje de perder dinero por caos operativo y falta de visibilidad.</p>
        </div>
        <div class="container">
            <div class="row g-4 justify-content-center">
                <div class="col-md-5">
                    <div class="p-5 rounded-5 border border-danger border-opacity-10 bg-dark h-100">
                        <h4 class="text-danger mb-4 fw-bold"><i class="fas fa-times-circle me-2"></i>Operación Fragmentada</h4>
                        <ul class="list-unstyled text-muted">
                            <li class="mb-3 d-flex"><i class="fas fa-times me-3 mt-1 text-danger"></i> Pronósticos manuales en Excel (alto error humano).</li>
                            <li class="mb-3 d-flex"><i class="fas fa-times me-3 mt-1 text-danger"></i> Cálculo incorrecto de utilidades reales.</li>
                            <li class="mb-3 d-flex"><i class="fas fa-times me-3 mt-1 text-danger"></i> Reclamos perdidos sin trazabilidad.</li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="p-5 rounded-5 border border-primary-yellow bg-dark shadow-lg h-100" style="box-shadow: 0 0 30px rgba(255, 179, 0, 0.1) !important;">
                        <h4 class="text-primary-yellow mb-4 fw-bold"><i class="fas fa-check-circle me-2"></i>Control con Tizzila</h4>
                        <ul class="list-unstyled text-white">
                            <li class="mb-3 d-flex"><i class="fas fa-check me-3 mt-1 text-primary-yellow"></i> Automatización del Forecast con motor de IA.</li>
                            <li class="mb-3 d-flex"><i class="fas fa-check me-3 mt-1 text-primary-yellow"></i> Centralización total de la cadena de suministro.</li>
                            <li class="mb-3 d-flex"><i class="fas fa-check me-3 mt-1 text-primary-yellow"></i> Protección del margen de negocio con datos.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="modulos" class="py-5">
        <div class="container py-5">
            <h2 class="text-center display-5 fw-bold mb-5">Ecosistema de Rentabilidad</h2>
            <div class="row g-4 align-items-stretch">
                <div class="col-lg-8">
                    <div class="bento-card d-flex flex-column justify-content-between">
                        <div>
                            <div class="icon-box"><i class="fas fa-chart-line"></i></div>
                            <h3>Tablero Gerencial Predictivo</h3>
                            <p class="text-muted lead">Visualice el futuro de su negocio. Nuestro tablero centraliza datos de todos los módulos para entregarle una visión 360° de su rentabilidad actual y proyectada a 30 y 60 días.</p>
                        </div>
                        <div class="mt-4 p-4 rounded-4" style="background: rgba(255,179,0,0.05);">
                            <div class="d-flex gap-5">
                                <div><span class="d-block display-6 fw-bold text-primary-yellow">98%</span><span class="small text-muted text-uppercase fw-bold">Precisión Forecast</span></div>
                                <div><span class="d-block display-6 fw-bold text-white">+15%</span><span class="small text-muted text-uppercase fw-bold">Recuperación Margen</span></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="row g-4 h-100">
                        <div class="col-12">
                            <div class="bento-card">
                                <div class="icon-box"><i class="fas fa-calendar-check"></i></div>
                                <h4>Programación de Pedidos</h4>
                                <p class="text-muted">Logística inteligente para distribuidores de alto volumen. Evite roturas de stock.</p>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="bento-card">
                                <div class="icon-box"><i class="fas fa-shield-alt"></i></div>
                                <h4>Gestión de Reclamos</h4>
                                <p class="text-muted">Blindaje de servicio al cliente con trazabilidad total de devoluciones.</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4">
                    <div class="bento-card">
                        <div class="icon-box"><i class="fas fa-file-invoice-dollar"></i></div>
                        <h4>Facturación y Cartera</h4>
                        <p class="text-muted">Flujo de caja optimizado y sincronizado con proveedores en tiempo real.</p>
                    </div>
                </div>
                <div class="col-lg-8" id="ia">
                    <div class="bento-card bg-primary-yellow-subtle border-primary-yellow">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div class="icon-box mb-0 bg-primary-yellow text-dark"><i class="fas fa-brain"></i></div>
                            <h3 class="mb-0 text-primary-yellow">Motor de Inteligencia Artificial (IA)</h3>
                        </div>
                        <p class="text-white opacity-75 lead">El corazón de Tizzila. Nuestro algoritmo procesa variables de mercado para predecir el <strong>precio óptimo del pollito</strong>, asegurando que compre y venda en el momento correcto para maximizar la utilidad.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

<section class="py-5 mb-5">
    <div class="container-fluid px-3 px-md-5">
        <div class="cta-yellow-box p-5 p-md-5 rounded-5 text-center shadow-lg position-relative overflow-hidden">

            <!-- Glow decorativo -->
            <div style="
                position: absolute;
                top: -50%;
                right: -20%;
                width: 1000px;
                height: 600px;
                background: radial-gradient(circle, rgba(255,255,255,0.25) 0%, rgba(255,255,255,0) 70%);
                transform: rotate(45deg);
                pointer-events: none;
            "></div>

            <!-- Contenido -->
            <div class="position-relative z-1 mx-auto" style="max-width: 1100px;">
                <h2 class="display-4 fw-900 mb-4" style="color: #0f172a;">
                    ¿Listo para blindar su margen?
                </h2>

                <p class="lead mb-5 fw-bold" style="color: #0f172a; opacity: 0.85;">
                    Únase a los líderes del sector avícola en LATAM que toman decisiones
                    basadas en datos, no en intuición.
                </p>

                <button class="btn btn-dark-custom btn-lg px-5 py-4 rounded-4 fw-900 fs-5 shadow-sm">
                    Solicitar Demo de Rentabilidad
                </button>
            </div>

        </div>
    </div>
</section>


    <footer class="footer">
        <div class="container text-center">
            <a class="navbar-brand fw-bold fs-4 d-flex align-items-center justify-content-center gap-2 mb-4" href="#">
                <i class="fas fa-egg text-primary-yellow"></i>
                <span class="text-white">Tizzila<span class="text-primary-yellow">App</span></span>
            </a>
            <div class="d-flex justify-content-center gap-4 mb-5">
                <a href="#" class="text-muted hover-yellow transition"><i class="fab fa-linkedin fs-4"></i></a>
                <a href="#" class="text-muted hover-yellow transition"><i class="fab fa-twitter fs-4"></i></a>
                <a href="#" class="text-muted hover-yellow transition"><i class="fas fa-envelope fs-4"></i></a>
            </div>
            <p class="small opacity-50">&copy; 2024 Tizzila App. Plataforma empresarial de orquestación avícola. Todos los derechos reservados.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>