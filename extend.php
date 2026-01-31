<?php

namespace ImportAI\Webhook;

use Flarum\Discussion\Event\Deleted as DiscussionDeleted;
use Flarum\Discussion\Event\Hidden as DiscussionHidden;
use Flarum\Discussion\Event\Renamed;
use Flarum\Extend;
use Flarum\Post\Event\Deleted as PostDeleted;
use Flarum\Post\Event\Hidden as PostHidden;
use Flarum\Post\Event\Posted;
use Flarum\Post\Event\Revised;
use ImportAI\Webhook\Listener\DiscussionDeletedListener;
use ImportAI\Webhook\Listener\DiscussionHiddenListener;
use ImportAI\Webhook\Listener\DiscussionRenamedListener;
use ImportAI\Webhook\Listener\PostCreatedListener;
use ImportAI\Webhook\Listener\PostDeletedListener;
use ImportAI\Webhook\Listener\PostHiddenListener;
use ImportAI\Webhook\Listener\PostRevisedListener;

return [
    (new Extend\Frontend('admin'))
        ->js(__DIR__ . '/js/dist/admin.js'),

    new Extend\Locales(__DIR__ . '/locale'),

    (new Extend\Event())
        ->listen(Posted::class, PostCreatedListener::class)
        ->listen(Revised::class, PostRevisedListener::class)
        ->listen(Renamed::class, DiscussionRenamedListener::class)
        ->listen(DiscussionDeleted::class, DiscussionDeletedListener::class)
        ->listen(DiscussionHidden::class, DiscussionHiddenListener::class)
        ->listen(PostDeleted::class, PostDeletedListener::class)
        ->listen(PostHidden::class, PostHiddenListener::class),

    (new Extend\Settings())
        ->default('import-ai-webhook.webhook_url', ''),
];
