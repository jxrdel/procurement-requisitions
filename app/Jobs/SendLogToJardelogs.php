<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Http;
use Throwable;

/**
 * Copy this class into your source Laravel app as app/Jobs/SendLogToJardelogs.php.
 *
 * Posts a single log entry to jardelogs. Requires a queue worker
 * (php artisan queue:work) unless QUEUE_CONNECTION=sync.
 */
class SendLogToJardelogs implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;

    public int $timeout = 10;

    /**
     * @param  array{level: string, message: string, context?: array<string, mixed>, channel?: string|null, logged_at?: string}  $payload
     */
    public function __construct(
        public string $endpoint,
        public string $token,
        public array $payload,
    ) {}

    public function handle(): void
    {
        try {
            Http::timeout(5)
                ->connectTimeout(2)
                ->withToken($this->token)
                ->acceptJson()
                ->asJson()
                ->post($this->endpoint, $this->payload);
        } catch (Throwable) {
            // Drop quietly after attempts; never break the source app.
        }
    }
}
