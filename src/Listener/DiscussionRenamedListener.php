<?php

namespace ImportAI\Webhook\Listener;

use Flarum\Discussion\Event\Renamed;
use ImportAI\Webhook\Service\WebhookService;

class DiscussionRenamedListener
{
    protected WebhookService $webhookService;

    public function __construct(WebhookService $webhookService)
    {
        $this->webhookService = $webhookService;
    }

    public function handle(Renamed $event): void
    {
        $webhookUrl = $this->webhookService->getWebhookUrl();

        if ($webhookUrl === null) {
            return;
        }

        $discussion = $event->discussion;
        $actor = $event->actor;
        $oldTitle = $event->oldTitle;

        $payload = [
            'event' => 'discussion.renamed',
            'discussion' => $discussion->toArray(),
            'old_title' => $oldTitle,
            'actor' => $actor ? $actor->toArray() : null,
        ];

        $this->webhookService->send($webhookUrl, $payload);
    }
}
