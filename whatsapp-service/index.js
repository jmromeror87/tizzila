import express from 'express';
import { createRequire } from 'module';
import {
    makeWASocket,
    useMultiFileAuthState,
    DisconnectReason,
    fetchLatestBaileysVersion,
} from '@whiskeysockets/baileys';
import pino from 'pino';
import QRCode from 'qrcode';
import { existsSync, mkdirSync } from 'fs';

// ─── Config ────────────────────────────────────────────────────────────────
const PORT        = process.env.PORT        || 3001;
const SECRET      = process.env.WA_SECRET   || 'tizzila_wa_secret';
const SESSION_DIR = './session';

if (!existsSync(SESSION_DIR)) mkdirSync(SESSION_DIR);

// ─── State ─────────────────────────────────────────────────────────────────
let sock         = null;
let currentQr    = null;   // base64 PNG del QR actual
let status       = 'disconnected'; // disconnected | qr_ready | connected

// ─── Express ───────────────────────────────────────────────────────────────
const app = express();
app.use(express.json());

// Middleware de autenticación por header
app.use((req, res, next) => {
    if (req.path === '/health') return next();
    const key = req.headers['x-wa-secret'];
    if (key !== SECRET) return res.status(401).json({ error: 'Unauthorized' });
    next();
});

// GET /health — sin autenticación (para checks de disponibilidad)
app.get('/health', (_req, res) => res.json({ ok: true }));

// GET /status — estado de la conexión y QR
app.get('/status', (_req, res) => {
    res.json({ status, qr: currentQr });
});

// POST /disconnect — cerrar sesión y resetear
app.post('/disconnect', async (_req, res) => {
    if (sock) {
        await sock.logout().catch(() => {});
        sock = null;
    }
    currentQr = null;
    status    = 'disconnected';
    startConnection(); // reinicia para mostrar nuevo QR
    res.json({ ok: true });
});

// POST /send — enviar mensaje de texto
app.post('/send', async (req, res) => {
    const { phone, message } = req.body;

    if (!phone || !message) {
        return res.status(400).json({ error: 'phone y message son requeridos' });
    }

    if (status !== 'connected') {
        return res.status(503).json({ error: 'WhatsApp no está conectado', status });
    }

    try {
        // Normalizar número: solo dígitos, agregar @s.whatsapp.net
        const jid = phone.replace(/[^0-9]/g, '') + '@s.whatsapp.net';
        await sock.sendMessage(jid, { text: message });
        res.json({ ok: true, jid });
    } catch (err) {
        res.status(500).json({ error: err.message });
    }
});

// ─── Baileys Connection ────────────────────────────────────────────────────
async function startConnection() {
    const { state, saveCreds } = await useMultiFileAuthState(SESSION_DIR);
    const { version }          = await fetchLatestBaileysVersion();

    sock = makeWASocket({
        version,
        auth:   state,
        logger: pino({ level: 'warn' }),
        printQRInTerminal: true,
        browser: ['Tizzila App', 'Chrome', '1.0.0'],
    });

    // Guardar credenciales cada vez que cambian
    sock.ev.on('creds.update', saveCreds);

    // Manejar QR
    sock.ev.on('connection.update', async (update) => {
        const { connection, lastDisconnect, qr } = update;

        if (qr) {
            status    = 'qr_ready';
            currentQr = await QRCode.toDataURL(qr);
            console.log('[WhatsApp] QR listo para escanear');
        }

        if (connection === 'open') {
            status    = 'connected';
            currentQr = null;
            console.log('[WhatsApp] Conectado');
        }

        if (connection === 'close') {
            const code   = lastDisconnect?.error?.output?.statusCode;
            const logout = code === DisconnectReason.loggedOut;

            console.log(`[WhatsApp] Desconectado (código ${code}). Logout: ${logout}`);

            status    = 'disconnected';
            currentQr = null;

            if (logout) {
                // Sesión cerrada desde el teléfono — limpiar y pedir nuevo QR
                const { rmSync } = await import('fs');
                rmSync(SESSION_DIR, { recursive: true, force: true });
                mkdirSync(SESSION_DIR);
            }

            // Reconectar siempre (salvo logout ya se limpió)
            setTimeout(startConnection, 3000);
        }
    });
}

// ─── Arrancar ──────────────────────────────────────────────────────────────
startConnection();
app.listen(PORT, () => console.log(`[WhatsApp Service] Escuchando en puerto ${PORT}`));
