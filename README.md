# Tizzila App — Plataforma de Orquestación Avícola Inteligente

> Sistema de orquestación enterprise para distribuidoras avícolas de alto volumen en América Latina.  
> Centraliza, controla, predice y recomienda a lo largo de toda la cadena de valor avícola.

---

## ¿Qué es Tizzila App?

Tizzila App **no es un ERP genérico ni un software de inventario**. Es un **sistema de orquestación especializado** diseñado para empresas distribuidoras avícolas que operan con millones de aves al año.

El cliente ideal **no incuba ni transporta** directamente: orquesta la logística y las finanzas que conectan las incubadoras con los clientes finales. Tizzila controla exactamente esos procesos críticos.

| Función | ¿Tizzila lo gestiona? |
|---|---|
| Gestión de inventario físico | No |
| Costos de transporte propios | No |
| Contabilidad general | No |
| Control de procesos operativos críticos | **Sí** |
| Gestión de contratos y SLAs | **Sí** |
| Control financiero operativo | **Sí** |

---

## Problemas que resuelve

- **Operación fragmentada** en hojas de cálculo, WhatsApp y correos
- **Forecast manual** sin trazabilidad ni histórico
- **Poca visibilidad del proveedor** — sin registro de confirmaciones ni despachos
- **Reclamaciones mal hechas o fuera de plazo** — pérdidas directas en el margen
- **Utilidad mal calculada** — costos y reclamos no consolidados
- **Decisiones tomadas tarde** — información desactualizada para la gerencia

---

## Stack Tecnológico

| Capa | Tecnología |
|---|---|
| Backend | PHP 8.2 / Laravel 12 |
| Frontend reactivo | Livewire 4 |
| Estilos | Tailwind CSS 3 / Alpine.js |
| Build | Vite 7 |
| PDF | barryvdh/laravel-dompdf |
| SMS / Notificaciones | Twilio SDK |
| Base de datos | MySQL (migrations incluidas) |

---

## Instalación

### Requisitos previos

- PHP >= 8.2
- Composer
- Node.js >= 18
- MySQL >= 8.0

### Pasos

```bash
# 1. Clonar el repositorio
git clone <url-del-repo> tizzila
cd tizzila

# 2. Instalar dependencias PHP
composer install

# 3. Instalar dependencias JS
npm install

# 4. Configurar entorno
cp .env.example .env
php artisan key:generate

# 5. Configurar base de datos en .env
# DB_DATABASE=tizzila
# DB_USERNAME=...
# DB_PASSWORD=...

# 6. Ejecutar migraciones
php artisan migrate

# 7. (Opcional) Seeders con datos iniciales
php artisan db:seed

# 8. Compilar assets
npm run build

# 9. Levantar servidor de desarrollo
php artisan serve
npm run dev   # en otra terminal
```

---

## Arquitectura de Módulos

Los módulos están interconectados: cada uno alimenta al siguiente, creando una única fuente de verdad operativa de principio a fin.

```
Motor IA de Precios (Módulo 0)
        ↓
Programación de Pedidos (Módulo 1)
        ↓
Forecast de Demanda (Módulo 2)
        ↓
Confirmación del Proveedor (Módulo 3)
        ↓
Rutas Sugeridas & Instrucciones Técnicas (Módulo 4)
        ↓
Asignación de Clientes por Ruta (Módulo 5)
        ↓
Despacho (Módulo 6)  +  Registro de Transporte (Módulo 6.1)
        ↓
Tracking y SLA (Módulo 7)
        ↓
Entrega, Validación y Novedades (Módulo 8)
        ↓
Reclamaciones al Proveedor (Módulo 9)
        ↓
Compras / Factura Proveedor (Módulo 9.1)  →  CxP (Módulo 9.2)
        ↓
Facturación al Cliente (Módulo 10)
        ↓
Cartera / CxC (Módulo 11)  +  Gastos Internos (Módulo 12)
        ↓
Reportes (Módulo 13)  →  Tablero Gerencial Predictivo BI + IA (Módulo 14)
```

---

## Descripción de Módulos

### Módulo 0 — Motor IA de Precios
Núcleo financiero y estratégico. Calcula el **precio piso** (costo + impuestos + margen mínimo), analiza datos históricos, predice escenarios de mercado y recomienda precios por semana, tipo de cliente y zona geográfica.

### Módulo 1 — Programación de Pedidos
Registra la demanda futura (3, 6+ meses) por cliente/fecha/cantidad. Versiona cada cambio para crear un historial auditable. Es la **fuente de verdad de la demanda**.

### Módulo 2 — Forecast de Demanda
Consolida la programación en un documento formal para la incubadora. Genera reportes estandarizados y guarda el historial de forecasts enviados como respaldo contractual.

### Módulo 3 — Confirmación del Proveedor
Registra la capacidad real de suministro confirmada por la incubadora. Detecta riesgos de incumplimiento y **bloquea sobreprogramaciones** cuando lo confirmado es inferior a lo pedido.

### Módulo 4 — Rutas Sugeridas & Instrucciones Técnicas
Planifica la ejecución del despacho: sugiere rutas, define secuencia de clientes y registra instrucciones técnicas obligatorias para el transportista.

### Módulo 5 — Asignación de Clientes por Ruta
Agrupa pedidos por zona geográfica y fecha, respeta prioridades obligatorias y cierra las rutas para evitar modificaciones no autorizadas antes del despacho.

### Módulo 6 — Despacho
Registra el inicio del despacho: marca el momento en que la mercancía sale de la incubadora, vinculando ruta, proveedor y clientes.

### Módulo 6.1 — Registro Informativo de Transporte
Registra conductor, vehículo y empresa transportista. Provee visibilidad y evidencia sobre la ejecución del transporte tercerizado.

### Módulo 7 — Tracking y Tiempos
Captura hitos de tiempo clave (salida, llegada a cliente), calcula el cumplimiento real contra los **SLAs pactados** y emite alertas automáticas ante desviaciones.

### Módulo 8 — Entrega, Validación y Novedades
Valida la recepción física: confirma entrega, registra pollitos muertos/merma y guarda evidencia multimedia. Es el **disparador del proceso de reclamación**.

### Módulo 9 — Reclamaciones al Proveedor
Genera reclamos automáticos a partir del Módulo 8, usando los formatos exigidos por cada proveedor. Controla vencimientos y emite alertas para garantizar que ninguna reclamación quede sin gestionar.

### Módulos 9.1 y 9.2 — Compras (Factura Proveedor) y CxP
Registra el costo real de la operación descontando los reclamos validados. **Fuente de verdad del costo real.** El Módulo 9.2 asegura que las obligaciones de pago reflejen únicamente ese costo justo.

### Módulos 10, 11 y 12 — Facturación, Cartera y Gastos Internos
- **Módulo 10**: Factura al cliente solo por lo entregado y validado.
- **Módulo 11**: Controla cuentas por cobrar y riesgo de crédito.
- **Módulo 12**: Centraliza gastos operativos propios del negocio.

### Módulo 13 — Reportes
Reportes operativos estandarizados del estado del negocio, accesibles para todos los roles relevantes.

### Módulo 14 — Tablero Gerencial Predictivo (BI + IA)
Responde las tres preguntas estratégicas de la gerencia:

1. **¿Cómo vamos?** — KPIs del estado actual y pasado
2. **¿Qué va a pasar?** — Proyecciones de demanda, costos y rentabilidad
3. **¿Qué debo ajustar?** — Recomendaciones prescriptivas para corregir desviaciones

---

## Estructura del Proyecto

```
app/
├── Http/Controllers/
│   ├── Accounting/       # Contabilidad operativa
│   ├── Auth/             # Autenticación
│   ├── Cartera/          # Cuentas por cobrar
│   ├── Claim/            # Reclamaciones al proveedor
│   ├── Customer/         # Gestión de clientes
│   ├── Dispatch/         # Despacho y transporte
│   ├── Driver/           # Conductores
│   ├── Expenses/         # Gastos internos
│   ├── Invoice/          # Facturación
│   └── Poultry/          # Módulos avícolas core
├── Livewire/             # Componentes reactivos Livewire
├── Models/               # Modelos Eloquent
└── Services/             # Lógica de negocio

database/migrations/
├── 000_global/           # Países, estados, ciudades, empresas
└── 001_poultry/          # Tablas del núcleo avícola
```

---

## Propuesta de Valor

| Pilar | Descripción |
|---|---|
| **Cumplimiento** | Trazabilidad y evidencia digital en cada acuerdo con proveedores y clientes |
| **Rentabilidad** | Precio piso calculado, reclamos gestionados, facturación precisa |
| **Predicción** | IA que anticipa escenarios de mercado y demanda |
| **Decisión Oportuna** | Dashboard gerencial con información consolidada y recomendaciones accionables |

---

## Licencia

Propietario — Tizzila App. Todos los derechos reservados.
