<?php

namespace ImportAI\Webhook\Service;

use Flarum\Settings\SettingsRepositoryInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Log\LoggerInterface;

class WebhookService
{
    protected SettingsRepositoryInterface $settings;
    protected LoggerInterface $logger;
    protected Client $client;

    public function __construct(SettingsRepositoryInterface $settings, LoggerInterface $logger)
    {
        $this->settings = $settings;
        $this->logger = $logger;
        $this->client = new Client(['timeout' => 10]);
    }

    public function getWebhookUrl(): ?string
    {
        $url = $this->settings->get('import-ai-webhook.webhook_url');
        return empty($url) ? null : $url;
    }

    public function send(string $url, array $payload): void
    {
        try {
            $response = $this->client->post($url, [
                'json' => $payload,
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
            ]);

            $statusCode = $response->getStatusCode();
            if ($statusCode >= 400) {
                $this->logger->warning('Webhook returned error status', [
                    'url' => $url,
                    'http_code' => $statusCode,
                    'response' => $response->getBody()->getContents(),
                ]);
            }
        } catch (GuzzleException $e) {
            $this->logger->error('Webhook request failed', [
                'url' => $url,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
