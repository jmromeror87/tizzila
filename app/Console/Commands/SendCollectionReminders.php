<?php

namespace App\Console\Commands;

use App\Models\Customer\Customer;
use App\Models\WhatsappCollectionLog;
use App\Services\WhatsApp\WhatsAppService;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class SendCollectionReminders extends Command
{
    protected $signature   = 'collections:remind {--dry-run : Solo mostrar sin enviar}';
    protected $description = 'Envía recordatorios de cobro por WhatsApp a clientes con facturas vencidas';

    public function handle(WhatsAppService $wa): int
    {
        $dryRun = $this->option('dry-run');

        // Solo enviar si WhatsApp está conectado (excepto en dry-run)
        if (!$dryRun && !$wa->isConnected()) {
            $this->error('WhatsApp no está conectado. Conéctese primero desde el panel.');
            return self::FAILURE;
        }

        $customers = Customer::where('is_active', true)
            ->whereNotNull('phone')
            ->with(['invoices' => fn($q) => $q->where('balance', '>', 0)->where('payment_status', 'overdue')])
            ->get()
            ->filter(fn($c) => $c->invoices->count() > 0);

        $sent = 0;
        $skipped = 0;

        foreach ($customers as $customer) {
            $overdueInvoices = $customer->invoices;
            $totalOverdue    = $overdueInvoices->sum('balance');
            $oldestDue       = $overdueInvoices->min('due_date');
            $daysOverdue     = $oldestDue ? Carbon::parse($oldestDue)->diffInDays(now()) : 0;

            // No spamear: máximo 1 mensaje cada 3 días por cliente
            $lastSent = WhatsappCollectionLog::where('customer_id', $customer->id)
                ->where('type', 'overdue_reminder')
                ->where('sent', true)
                ->where('created_at', '>=', now()->subDays(3))
                ->exists();

            if ($lastSent) {
                $skipped++;
                $this->line("  ⏭  {$customer->name} — ya notificado hace menos de 3 días");
                continue;
            }

            $message = $wa->buildOverdueMessage(
                $customer->name,
                $totalOverdue,
                $daysOverdue,
                $overdueInvoices->count()
            );

            if ($dryRun) {
                $this->info("  [DRY] {$customer->name} ({$customer->phone}) — \${$totalOverdue} vencido");
                continue;
            }

            try {
                $wasSent = $wa->sendOverdueReminder(
                    $customer->phone,
                    $customer->name,
                    $totalOverdue,
                    $daysOverdue,
                    $overdueInvoices->count()
                );

                WhatsappCollectionLog::create([
                    'customer_id' => $customer->id,
                    'phone'       => $customer->phone,
                    'message'     => $message,
                    'type'        => 'overdue_reminder',
                    'sent'        => $wasSent,
                ]);

                if ($wasSent) {
                    $sent++;
                    $this->info("  ✓  {$customer->name} — mensaje enviado");
                }
            } catch (\Exception $e) {
                WhatsappCollectionLog::create([
                    'customer_id' => $customer->id,
                    'phone'       => $customer->phone,
                    'message'     => $message,
                    'type'        => 'overdue_reminder',
                    'sent'        => false,
                    'error'       => $e->getMessage(),
                ]);
                $this->warn("  ✗  {$customer->name} — error: " . $e->getMessage());
            }
        }

        $this->newLine();
        $this->info("Enviados: {$sent} | Omitidos (ya notificados): {$skipped}");

        return self::SUCCESS;
    }
}
