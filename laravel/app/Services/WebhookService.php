<?php

namespace App\Services;

use App\Models\Task;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
class WebhookService
{
    public function __construct(private Client $http){}

    public function sendTaskCompleted(Task $task): void
    {
        $payload = [
            'user_id'   => $task->user_id,
            'task_id'   => $task->id,
            'message'   => 'Task marked as done',
            'timestamp' => now()->toIso8601String(),
        ];

        // this to match of signature.
        $raw = json_encode($payload, JSON_UNESCAPED_SLASHES);
        $secret = config('services.webhook.secret');
        // HMAC => Hash-Based Message Authentication Codes
        // hash_hmac  => algorithm , data that hashed , secret key

        $signature = 'sha256=' . hash_hmac('sha256', $raw, $secret);


        try {
            $res = $this->http->post(config('services.webhook.url'), [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'X-Signature'  => $signature,
                ],
                'body'    => $raw,  // send the SAME raw JSON we signed
                'timeout' => 3,
            ]);

            Log::info('webhook.dispatch.ok', [
                'status' => $res->getStatusCode(),
                'task_id' => $task->id,
            ]);

        } catch (\Throwable $e) {
            Log::error('webhook.dispatch.fail', [
                'task_id' => $task->id,
                'error'   => $e->getMessage(),
            ]);

        }

    }

}