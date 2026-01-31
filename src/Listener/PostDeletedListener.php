<?php

namespace ImportAI\Webhook\Listener;

use Flarum\Post\Event\Deleted;
use ImportAI\Webhook\Service\WebhookService;

class PostDeletedListener
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

        $post = $event->post;
        $user = $post->user;
        $discussion = $post->discussion;
        $actor = $event->actor;

        $payload = [
            'event' => 'post.deleted',
            'user' => $user ? $user->toArray() : null,
            'post' => $post->toArray(),
            'discussion' => $discussion ? $discussion->toArray() : null,
            'actor' => $actor ? $actor->toArray() : null,
        ];

        $this->webhookService->send($webhookUrl, $payload);
    }
}
