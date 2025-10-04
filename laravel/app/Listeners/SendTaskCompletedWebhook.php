<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\TaskCompleted;
use App\Services\WebhookService;
class SendTaskCompletedWebhook
{
    /**
     * Create the event listener.
     */
    public function __construct(private WebhookService $webhook)
    {

    }


    /**
     * Handle the event.
     */
    public function handle(TaskCompleted $event): void
    {
        $this->webhook->sendTaskCompleted($event->task);
    }
}
