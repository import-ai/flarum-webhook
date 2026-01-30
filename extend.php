<?php

namespace ImportAI\Webhook;

use Flarum\Extend;
use Flarum\Post\Event\Posted;
use Flarum\Post\Event\Revised;
use ImportAI\Webhook\Listener\PostCreatedListener;
use ImportAI\Webhook\Listener\PostRevisedListener;

return [
    (new Extend\Frontend('admin'))
        ->js(__DIR__ . '/js/dist/admin.js'),

    new Extend\Locales(__DIR__ . '/locale'),

    (new Extend\Event())
        ->listen(Posted::class, PostCreatedListener::class)
        ->listen(Revised::class, PostRevisedListener::class),

    (new Extend\Settings())
        ->default('import-ai-webhook.webhook_url', ''),
];
