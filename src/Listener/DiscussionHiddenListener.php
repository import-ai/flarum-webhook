<?php

namespace ImportAI\Webhook\Listener;

use Flarum\Discussion\Event\Hidden;
use ImportAI\Webhook\Service\WebhookService;

class DiscussionHiddenListener
{
    protected WebhookService $webhookService;

    public function __construct(WebhookService $webhookService)
    {
        $this->webhookService = $webhookService;
    }

    public function handle(Hidden $event): void
    {
        $webhookUrl = $this->webhookService->getWebhookUrl();

        if ($webhookUrl === null) {
            return;
        }

        $discussion = $event->discussion;
        $actor = $event->actor;

        $payload = [
            'event' => 'discussion.hidden',
            'discussion' => $discussion->toArray(),
            'actor' => $actor ? $actor->toArray() : null,
        ];

        $this->webhookService->send($webhookUrl, $payload);
    }
}
