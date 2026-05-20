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
    <title>Tizzila App · Orquestación Avícola Inteligente</title>
    <link rel="icon" type="image/svg+xml" href="/favicon.svg">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --bg: #070a13;
            --card: #0d121f;
            --border: rgba(255,255,255,0.05);
            --yellow: #eab308;
            --yellow-hover: #ca9a06;
            --text-muted: #71717a;
            --text-sub: #a1a1aa;
        }

        html { scroll-behavior: smooth; }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg);
            color: #fff;
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
        }

        a { text-decoration: none; color: inherit; }

        /* ── NAV ── */
        nav {
            position: fixed; top: 0; left: 0; right: 0; z-index: 100;
            height: 64px;
            display: flex; align-items: center;
            background: rgba(7, 10, 19, 0.85);
            backdrop-filter: blur(16px);
            border-bottom: 1px solid var(--border);
        }
        .nav-inner {
            max-width: 1200px; margin: 0 auto; padding: 0 24px;
            width: 100%;
            display: flex; align-items: center; justify-content: space-between;
        }
        .nav-logo {
            display: flex; align-items: center; gap: 10px;
            font-size: 18px; font-weight: 900; letter-spacing: -0.03em; color: #fff;
        }
        .nav-logo svg { width: 32px; height: 32px; }
        .nav-logo span { color: var(--yellow); }
        .nav-links { display: flex; align-items: center; gap: 8px; }
        .nav-links a {
            font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.1em;
            color: var(--text-sub); padding: 8px 14px; border-radius: 10px; transition: all 0.2s;
        }
        .nav-links a:hover { color: #fff; background: rgba(255,255,255,0.05); }
        .btn-login {
            height: 36px; padding: 0 20px;
            background: var(--yellow); color: #000;
            font-size: 11px; font-weight: 900; text-transform: uppercase; letter-spacing: 0.1em;
            border-radius: 10px; transition: all 0.2s; display: inline-flex; align-items: center; gap: 8px;
        }
        .btn-login:hover { background: var(--yellow-hover); color: #000; }

        /* ── HERO ── */
        .hero {
            padding: 160px 24px 100px;
            max-width: 1200px; margin: 0 auto;
            display: grid; grid-template-columns: 1fr 1fr; gap: 64px; align-items: center;
        }
        @media (max-width: 900px) {
            .hero { grid-template-columns: 1fr; padding: 120px 24px 60px; gap: 48px; }
        }
        .hero-badge {
            display: inline-flex; align-items: center; gap: 8px;
            background: rgba(234,179,8,0.08); border: 1px solid rgba(234,179,8,0.2);
            color: var(--yellow); font-size: 10px; font-weight: 900; text-transform: uppercase;
            letter-spacing: 0.15em; padding: 6px 14px; border-radius: 100px; margin-bottom: 24px;
        }
        .hero h1 {
            font-size: clamp(2.2rem, 5vw, 3.5rem);
            font-weight: 900; line-height: 1.05; letter-spacing: -0.04em;
            margin-bottom: 20px;
        }
        .hero h1 span { color: var(--yellow); }
        .hero p {
            font-size: 15px; color: var(--text-sub); line-height: 1.7;
            margin-bottom: 36px; max-width: 480px;
        }
        .hero-actions { display: flex; gap: 12px; flex-wrap: wrap; }
        .btn-primary {
            height: 44px; padding: 0 28px;
            background: var(--yellow); color: #000;
            font-size: 11px; font-weight: 900; text-transform: uppercase; letter-spacing: 0.1em;
            border-radius: 12px; transition: all 0.2s; display: inline-flex; align-items: center; gap: 8px;
            border: none; cursor: pointer;
        }
        .btn-primary:hover { background: var(--yellow-hover); transform: translateY(-1px); }
        .btn-ghost {
            height: 44px; padding: 0 28px;
            background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.08);
            color: var(--text-sub);
            font-size: 11px; font-weight: 900; text-transform: uppercase; letter-spacing: 0.1em;
            border-radius: 12px; transition: all 0.2s; display: inline-flex; align-items: center; gap: 8px;
        }
        .btn-ghost:hover { color: #fff; background: rgba(255,255,255,0.07); }

        /* ── DASHBOARD MOCKUP ── */
        .mockup {
            background: var(--card); border: 1px solid var(--border);
            border-radius: 24px; padding: 24px; position: relative; overflow: hidden;
        }
        .mockup::before {
            content: '';
            position: absolute; top: -60%; right: -30%;
            width: 400px; height: 400px;
            background: radial-gradient(circle, rgba(234,179,8,0.12), transparent 70%);
            pointer-events: none;
        }
        .mockup-bar { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .mockup-title { font-size: 10px; font-weight: 900; text-transform: uppercase; letter-spacing: 0.2em; color: var(--text-muted); }
        .mockup-dots { display: flex; gap: 5px; }
        .mockup-dots span { width: 8px; height: 8px; border-radius: 50%; background: var(--border); }
        .chart-bars {
            display: flex; align-items: flex-end; gap: 6px; height: 120px; margin-bottom: 20px;
        }
        .chart-bar {
            flex: 1; border-radius: 6px 6px 0 0;
            background: rgba(234,179,8,0.15); border-top: 2px solid var(--yellow);
            transition: all 0.3s;
        }
        .chart-bar:hover { background: rgba(234,179,8,0.25); }
        .mockup-stats { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }
        .mockup-stat {
            background: rgba(0,0,0,0.3); border: 1px solid rgba(234,179,8,0.15);
            border-radius: 12px; padding: 14px;
        }
        .mockup-stat-label { font-size: 9px; font-weight: 900; text-transform: uppercase; letter-spacing: 0.15em; color: var(--text-muted); margin-bottom: 6px; }
        .mockup-stat-value { font-size: 20px; font-weight: 900; color: var(--yellow); letter-spacing: -0.03em; }
        .mockup-stat-value.white { color: #fff; }
        .status-pill {
            display: inline-flex; align-items: center; gap: 5px;
            font-size: 9px; font-weight: 900; text-transform: uppercase; letter-spacing: 0.1em;
            padding: 3px 8px; border-radius: 6px; margin-top: 4px;
        }
        .pill-green { background: rgba(16,185,129,0.1); color: #10b981; border: 1px solid rgba(16,185,129,0.2); }
        .pill-dot { width: 5px; height: 5px; border-radius: 50%; background: currentColor; }

        /* ── STATS BAR ── */
        .stats-bar {
            border-top: 1px solid var(--border); border-bottom: 1px solid var(--border);
            background: var(--card);
        }
        .stats-bar-inner {
            max-width: 1200px; margin: 0 auto; padding: 32px 24px;
            display: grid; grid-template-columns: repeat(4, 1fr); gap: 24px;
        }
        @media (max-width: 700px) {
            .stats-bar-inner { grid-template-columns: repeat(2, 1fr); }
        }
        .stat-item { text-align: center; }
        .stat-value { font-size: 28px; font-weight: 900; color: var(--yellow); letter-spacing: -0.04em; }
        .stat-label { font-size: 9px; font-weight: 900; text-transform: uppercase; letter-spacing: 0.2em; color: var(--text-muted); margin-top: 4px; }

        /* ── SECTIONS ── */
        section { padding: 80px 24px; }
        .section-inner { max-width: 1200px; margin: 0 auto; }
        .section-header { text-align: center; margin-bottom: 56px; }
        .section-tag {
            display: inline-flex; align-items: center; gap: 6px;
            font-size: 9px; font-weight: 900; text-transform: uppercase; letter-spacing: 0.2em;
            color: var(--yellow); margin-bottom: 16px;
        }
        .section-tag::before, .section-tag::after { content: '—'; opacity: 0.4; }
        .section-title { font-size: clamp(1.8rem, 3.5vw, 2.6rem); font-weight: 900; letter-spacing: -0.04em; }
        .section-title span { color: var(--yellow); }
        .section-sub { font-size: 14px; color: var(--text-sub); margin-top: 12px; max-width: 560px; margin-left: auto; margin-right: auto; }

        /* ── MODULES GRID ── */
        .modules-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; }
        @media (max-width: 900px) { .modules-grid { grid-template-columns: repeat(2, 1fr); } }
        @media (max-width: 600px) { .modules-grid { grid-template-columns: 1fr; } }
        .module-card {
            background: var(--card); border: 1px solid var(--border);
            border-radius: 20px; padding: 28px; transition: all 0.25s;
        }
        .module-card:hover { border-color: rgba(234,179,8,0.3); transform: translateY(-2px); }
        .module-icon {
            width: 44px; height: 44px; border-radius: 12px;
            background: rgba(234,179,8,0.08); border: 1px solid rgba(234,179,8,0.15);
            display: flex; align-items: center; justify-content: center;
            color: var(--yellow); font-size: 16px; margin-bottom: 16px;
        }
        .module-name { font-size: 13px; font-weight: 900; text-transform: uppercase; letter-spacing: -0.01em; margin-bottom: 8px; }
        .module-desc { font-size: 12px; color: var(--text-sub); line-height: 1.6; }

        /* ── COMPARE ── */
        .compare-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
        @media (max-width: 700px) { .compare-grid { grid-template-columns: 1fr; } }
        .compare-card {
            background: var(--card); border: 1px solid var(--border);
            border-radius: 20px; padding: 28px;
        }
        .compare-card.featured { border-color: rgba(234,179,8,0.3); }
        .compare-header {
            display: flex; align-items: center; gap: 10px;
            margin-bottom: 24px;
        }
        .compare-dot { width: 10px; height: 10px; border-radius: 50%; }
        .compare-dot.red { background: #ef4444; }
        .compare-dot.yellow { background: var(--yellow); }
        .compare-title { font-size: 11px; font-weight: 900; text-transform: uppercase; letter-spacing: 0.1em; }
        .compare-item {
            display: flex; align-items: flex-start; gap: 10px;
            font-size: 12px; color: var(--text-sub); padding: 10px 0;
            border-bottom: 1px solid var(--border);
        }
        .compare-item:last-child { border-bottom: none; }
        .compare-item i { font-size: 10px; margin-top: 2px; flex-shrink: 0; }
        .compare-item i.bad { color: #ef4444; }
        .compare-item i.good { color: #10b981; }
        .compare-item span { color: #fff; font-weight: 700; }

        /* ── CTA ── */
        .cta-section {
            background: var(--card); border-top: 1px solid var(--border); border-bottom: 1px solid var(--border);
            padding: 80px 24px; text-align: center;
        }
        .cta-inner { max-width: 640px; margin: 0 auto; }
        .cta-section h2 { font-size: clamp(1.8rem, 3.5vw, 2.6rem); font-weight: 900; letter-spacing: -0.04em; margin-bottom: 16px; }
        .cta-section h2 span { color: var(--yellow); }
        .cta-section p { font-size: 14px; color: var(--text-sub); margin-bottom: 36px; line-height: 1.7; }

        /* ── FOOTER ── */
        footer {
            padding: 48px 24px;
            border-top: 1px solid var(--border);
            text-align: center;
        }
        footer .foot-logo {
            display: inline-flex; align-items: center; gap: 8px;
            font-size: 16px; font-weight: 900; margin-bottom: 20px;
        }
        footer .foot-logo span { color: var(--yellow); }
        footer p { font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.2em; color: var(--text-muted); }

        /* ── WHATSAPP ── */
        .whatsapp-float {
            position: fixed; bottom: 24px; right: 24px; z-index: 9999;
            width: 52px; height: 52px; border-radius: 50%;
            background: #25d366; color: #fff;
            display: flex; align-items: center; justify-content: center;
            font-size: 22px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.4); transition: all 0.2s;
        }
        .whatsapp-float:hover { background: #1ebe5d; transform: translateY(-2px); color: #fff; }
    </style>
</head>
<body>

    {{-- NAV --}}
    <nav>
        <div class="nav-inner">
            <div class="nav-logo">
                <svg viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                    <rect width="100" height="100" rx="22" fill="#0d121f"/>
                    <ellipse cx="50" cy="54" rx="24" ry="28" fill="#eab308"/>
                    <ellipse cx="50" cy="54" rx="18" ry="22" fill="#fbbf24"/>
                </svg>
                Tizzila<span>App</span>
            </div>
            <div class="nav-links">
                <a href="#modulos">Módulos</a>
                <a href="#comparativa">Comparativa</a>
                <a href="{{ route('login') }}" class="btn-login">
                    <i class="fas fa-arrow-right-to-bracket"></i> Ingresar
                </a>
            </div>
        </div>
    </nav>

    {{-- HERO --}}
    <div class="hero">
        <div>
            <div class="hero-badge">
                <i class="fas fa-globe-americas"></i>
                Orquestación Avícola · Colombia & LATAM
            </div>
            <h1>La plataforma que <span>ordena</span> su operación avícola</h1>
            <p>Centralice pedidos, facturación, despachos y cartera. Tome decisiones con datos reales, no con hojas de cálculo.</p>
            <div class="hero-actions">
                <a href="{{ route('login') }}" class="btn-primary">
                    Acceder al Sistema <i class="fas fa-arrow-right"></i>
                </a>
                <a href="#modulos" class="btn-ghost">Ver Módulos</a>
            </div>
        </div>
        <div class="mockup">
            <div class="mockup-bar">
                <span class="mockup-title">Previsión de Margen</span>
                <div class="mockup-dots"><span></span><span></span><span></span></div>
            </div>
            <div class="chart-bars">
                <div class="chart-bar" style="height:60%"></div>
                <div class="chart-bar" style="height:85%"></div>
                <div class="chart-bar" style="height:50%"></div>
                <div class="chart-bar" style="height:92%"></div>
                <div class="chart-bar" style="height:68%"></div>
                <div class="chart-bar" style="height:100%"></div>
                <div class="chart-bar" style="height:75%"></div>
            </div>
            <div class="mockup-stats">
                <div class="mockup-stat">
                    <div class="mockup-stat-label">Precio IA</div>
                    <div class="mockup-stat-value">$3.450</div>
                    <div class="status-pill pill-green"><span class="pill-dot"></span> +4.2%</div>
                </div>
                <div class="mockup-stat">
                    <div class="mockup-stat-label">Utilidad Neta</div>
                    <div class="mockup-stat-value white">18.5%</div>
                    <div class="status-pill pill-green"><span class="pill-dot"></span> Al día</div>
                </div>
            </div>
        </div>
    </div>

    {{-- STATS BAR --}}
    <div class="stats-bar">
        <div class="stats-bar-inner">
            <div class="stat-item">
                <div class="stat-value">98%</div>
                <div class="stat-label">Precisión Forecast IA</div>
            </div>
            <div class="stat-item">
                <div class="stat-value">+15%</div>
                <div class="stat-label">Recuperación de Margen</div>
            </div>
            <div class="stat-item">
                <div class="stat-value">360°</div>
                <div class="stat-label">Visibilidad Operativa</div>
            </div>
            <div class="stat-item">
                <div class="stat-value">24/7</div>
                <div class="stat-label">Control en Tiempo Real</div>
            </div>
        </div>
    </div>

    {{-- MODULES --}}
    <section id="modulos">
        <div class="section-inner">
            <div class="section-header">
                <div class="section-tag">Plataforma</div>
                <h2 class="section-title">Ecosistema de <span>Módulos</span></h2>
                <p class="section-sub">Cada área de su operación conectada en un solo sistema.</p>
            </div>
            <div class="modules-grid">
                <div class="module-card">
                    <div class="module-icon"><i class="fas fa-chart-line"></i></div>
                    <div class="module-name">Dashboard Gerencial</div>
                    <div class="module-desc">Visión 360° de su negocio. KPIs de rentabilidad, cartera y operación en tiempo real.</div>
                </div>
                <div class="module-card">
                    <div class="module-icon"><i class="fas fa-calendar-check"></i></div>
                    <div class="module-name">Programación de Pedidos</div>
                    <div class="module-desc">Planificación inteligente de órdenes de compra. Evite roturas de stock y compras innecesarias.</div>
                </div>
                <div class="module-card">
                    <div class="module-icon"><i class="fas fa-truck"></i></div>
                    <div class="module-name">Despachos</div>
                    <div class="module-desc">Control de salidas, liquidación de furgones y trazabilidad de entregas al cliente.</div>
                </div>
                <div class="module-card">
                    <div class="module-icon"><i class="fas fa-file-invoice-dollar"></i></div>
                    <div class="module-name">Facturación y Cartera</div>
                    <div class="module-desc">Flujo de caja optimizado. Facturación electrónica, cobros y seguimiento de cartera vencida.</div>
                </div>
                <div class="module-card">
                    <div class="module-icon"><i class="fas fa-shield-alt"></i></div>
                    <div class="module-name">Gestión de Reclamos</div>
                    <div class="module-desc">Blindaje del servicio al cliente. Trazabilidad total de devoluciones y notas crédito.</div>
                </div>
                <div class="module-card" style="border-color: rgba(234,179,8,0.25); background: rgba(234,179,8,0.03);">
                    <div class="module-icon" style="background: rgba(234,179,8,0.15); border-color: rgba(234,179,8,0.3);"><i class="fas fa-brain"></i></div>
                    <div class="module-name" style="color: var(--yellow);">Motor de IA</div>
                    <div class="module-desc">Algoritmo predictivo para precio óptimo del pollito. Compre y venda en el momento exacto.</div>
                </div>
            </div>
        </div>
    </section>

    {{-- COMPARE --}}
    <section id="comparativa" style="background: var(--card); border-top: 1px solid var(--border); border-bottom: 1px solid var(--border);">
        <div class="section-inner">
            <div class="section-header">
                <div class="section-tag">Comparativa</div>
                <h2 class="section-title">Antes y <span>Después</span> de Tizzila</h2>
                <p class="section-sub">El caos operativo es costoso. Vea lo que cambia cuando centraliza todo.</p>
            </div>
            <div class="compare-grid">
                <div class="compare-card">
                    <div class="compare-header">
                        <div class="compare-dot red"></div>
                        <div class="compare-title">Sin Tizzila</div>
                    </div>
                    <div class="compare-item">
                        <i class="fas fa-times bad"></i>
                        <div>Pronósticos manuales en Excel con alto margen de error humano</div>
                    </div>
                    <div class="compare-item">
                        <i class="fas fa-times bad"></i>
                        <div>Cálculo incorrecto de utilidades reales por operación fragmentada</div>
                    </div>
                    <div class="compare-item">
                        <i class="fas fa-times bad"></i>
                        <div>Reclamos perdidos sin trazabilidad ni control de devoluciones</div>
                    </div>
                    <div class="compare-item">
                        <i class="fas fa-times bad"></i>
                        <div>Cartera vencida sin seguimiento y pérdida de flujo de caja</div>
                    </div>
                </div>
                <div class="compare-card featured">
                    <div class="compare-header">
                        <div class="compare-dot yellow"></div>
                        <div class="compare-title" style="color: var(--yellow);">Con Tizzila</div>
                    </div>
                    <div class="compare-item">
                        <i class="fas fa-check good"></i>
                        <div><span>Forecast automático con IA</span> — sin errores, sin Excel</div>
                    </div>
                    <div class="compare-item">
                        <i class="fas fa-check good"></i>
                        <div><span>Utilidades en tiempo real</span> por pedido, furgón y cliente</div>
                    </div>
                    <div class="compare-item">
                        <i class="fas fa-check good"></i>
                        <div><span>Reclamos con trazabilidad total</span> y resolución rápida</div>
                    </div>
                    <div class="compare-item">
                        <i class="fas fa-check good"></i>
                        <div><span>Cartera controlada</span> con alertas y seguimiento automático</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- CTA --}}
    <div class="cta-section">
        <div class="cta-inner">
            <h2>¿Listo para <span>blindar su margen</span>?</h2>
            <p>Únase a los líderes del sector avícola que toman decisiones basadas en datos, no en intuición.</p>
            <a href="{{ route('login') }}" class="btn-primary" style="font-size: 12px; height: 48px; padding: 0 36px;">
                Acceder a Tizzila <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </div>

    {{-- FOOTER --}}
    <footer>
        <div class="foot-logo">
            <svg viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg" style="width:28px;height:28px;">
                <rect width="100" height="100" rx="22" fill="#0d121f"/>
                <ellipse cx="50" cy="54" rx="24" ry="28" fill="#eab308"/>
                <ellipse cx="50" cy="54" rx="18" ry="22" fill="#fbbf24"/>
            </svg>
            Tizzila<span>App</span>
        </div>
        <p>© 2026 Tizzila App · Orquestación Avícola Inteligente · Todos los derechos reservados</p>
    </footer>

    {{-- WHATSAPP FLOAT --}}
    <a href="https://wa.me/57?text=Hola,%20quiero%20una%20demo%20de%20Tizzila%20App" target="_blank" class="whatsapp-float">
        <svg viewBox="0 0 24 24" fill="currentColor" style="width:24px;height:24px;"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.588-5.946 0-6.556 5.332-11.888 11.888-11.888 3.176 0 6.161 1.237 8.404 3.48s3.481 5.228 3.481 8.405c0 6.555-5.332 11.89-11.888 11.89-2.015 0-3.991-.511-5.741-1.486L0 24zm6.535-3.56c1.554.922 3.153 1.408 4.75 1.408 5.148 0 9.338-4.19 9.338-9.339 0-2.491-.97-4.832-2.731-6.593S13.832 3.19 11.341 3.19c-5.148 0-9.338 4.19-9.338 9.34 0 1.696.467 3.351 1.353 4.792L2.302 21.72l4.29-1.28zm10.596-6.111c-.26-.13-1.534-.757-1.772-.843-.238-.086-.412-.13-.585.13s-.672.843-.823 1.017c-.151.173-.303.195-.563.065-.26-.13-1.097-.404-2.09-1.288-.772-.69-1.293-1.542-1.444-1.803-.152-.26-.016-.4.114-.53.117-.117.26-.303.39-.455.13-.152.173-.26.26-.433.087-.173.043-.325-.022-.455-.065-.13-.585-1.408-.802-1.928-.211-.51-.423-.44-.585-.448-.152-.008-.325-.01-.499-.01-.173 0-.455.065-.693.325-.238.26-.91.888-.91 2.165 0 1.277.932 2.512 1.062 2.685.13.173 1.834 2.8 4.444 3.927.62.268 1.104.428 1.481.548.623.198 1.19.17 1.637.104.498-.074 1.534-.627 1.75-1.23.217-.607.217-1.127.152-1.213-.065-.086-.238-.13-.498-.26z"/></svg>
    </a>

</body>
</html>
