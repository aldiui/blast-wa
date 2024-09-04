<?php

namespace App\Jobs;

use App\Services\WhatsappService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendBulkMessageJob implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    protected array $bulkMessages;

    public function __construct(array $bulkMessages)
    {

        $this->bulkMessages = $bulkMessages;
    }

    public function handle(WhatsappService $whatsappService): void
    {
        $whatsappService->sendBulkMessage($this->bulkMessages);
    }
}
