<?php

namespace ImportAI\Webhook\Listener;

use Flarum\Post\Event\Revised;
use ImportAI\Webhook\Service\WebhookService;

class PostRevisedListener
{
    protected WebhookService $webhookService;

    public function __construct(WebhookService $webhookService)
    {
        $this->webhookService = $webhookService;
    }

    public function handle(Revised $event): void
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
            'event' => 'post.revised',
            'user' => $user ? $user->toArray() : null,
            'post' => $post->toArray(),
            'discussion' => $discussion->toArray(),
            'actor' => $actor ? $actor->toArray() : null,
        ];

        $this->webhookService->send($webhookUrl, $payload);
    }
}
