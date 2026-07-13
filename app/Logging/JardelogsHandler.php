<?php

namespace App\Logging;

use App\Jobs\SendLogToJardelogs;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Level;
use Monolog\LogRecord;
use Throwable;

/**
 * Copy this class into your source Laravel app as app/Logging/JardelogsHandler.php.
 *
 * Dispatches a queued job so Log:: calls return immediately instead of waiting on HTTP.
 */
class JardelogsHandler extends AbstractProcessingHandler
{
    public function __construct(
        protected string $endpoint,
        protected string $token,
        int|string|Level $level = Level::Debug,
        bool $bubble = true,
    ) {
        parent::__construct($level, $bubble);
    }

    protected function write(LogRecord $record): void
    {
        try {
            $message = $record->message;

            if (str_starts_with(ltrim($message), 'LDAP (ldap://')
                || str_starts_with(ltrim($message), 'LDAP (ldaps://')) {
                return;
            }

            SendLogToJardelogs::dispatch(
                endpoint: $this->endpoint,
                token: $this->token,
                payload: [
                    'level' => strtolower($record->level->getName()),
                    'message' => $message,
                    'context' => $record->context,
                    'channel' => $record->channel,
                    'logged_at' => $record->datetime->format(DATE_ATOM),
                ],
            );
        } catch (Throwable) {
            // Intentionally swallow failures so logging never breaks the app.
        }
    }
}
