<?php

namespace ImportAI\Webhook\Listener;

use Flarum\Discussion\Event\Deleted;
use ImportAI\Webhook\Service\WebhookService;

class DiscussionDeletedListener
{
    protected WebhookService $webhookService;

    public function __construct(WebhookService $webhookService)
    {
        $this->webhookService = $webhookService;
    }

    public function handle(Deleted $event): void
    {
        $webhookUrl = $this->webhookService->getWebhookUrl();

        if ($webhookUrl === null) {
            return;
        }

        $discussion = $event->discussion;
        $actor = $event->actor;

        $payload = [
            'event' => 'discussion.deleted',
            'discussion' => $discussion->toArray(),
            'actor' => $actor ? $actor->toArray() : null,
        ];

        $this->webhookService->send($webhookUrl, $payload);
    }
}
