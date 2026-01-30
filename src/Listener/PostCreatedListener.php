<?php

namespace ImportAI\Webhook\Listener;

use Flarum\Post\Event\Posted;
use Flarum\Settings\SettingsRepositoryInterface;
use Illuminate\Contracts\Queue\Queue;
use Psr\Log\LoggerInterface;

class PostCreatedListener
{
    protected SettingsRepositoryInterface $settings;
    protected LoggerInterface $logger;

    public function __construct(SettingsRepositoryInterface $settings, LoggerInterface $logger)
    {
        $this->settings = $settings;
        $this->logger = $logger;
    }

    public function handle(Posted $event): void
    {
        $webhookUrl = $this->settings->get('import-ai-webhook.webhook_url');

        if (empty($webhookUrl)) {
            return;
        }

        $post = $event->post;
        $user = $post->user;
        $discussion = $post->discussion;

        // Build the payload with full model attributes (includes extension fields)
        $payload = [
            'user' => $user ? $user->toArray() : null,
            'post' => $post->toArray(),
            'discussion' => $discussion->toArray(),
        ];

        $this->sendWebhook($webhookUrl, $payload);
    }

    protected function sendWebhook(string $url, array $payload): void
    {
        try {
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
            ]);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

            if (curl_errno($ch)) {
                $this->logger->error('Webhook request failed', [
                    'url' => $url,
                    'error' => curl_error($ch),
                ]);
            } elseif ($httpCode >= 400) {
                $this->logger->warning('Webhook returned error status', [
                    'url' => $url,
                    'http_code' => $httpCode,
                    'response' => $response,
                ]);
            }

            curl_close($ch);
        } catch (\Exception $e) {
            $this->logger->error('Webhook exception', [
                'url' => $url,
                'exception' => $e->getMessage(),
            ]);
        }
    }
}
