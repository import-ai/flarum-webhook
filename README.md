# Flarum Webhook

This file provides guidance to developers when working with code in this repository.

## Overview

This is a Flarum extension (`import-ai/flarum-webhook`) that triggers HTTP webhooks for various forum events including post creation, editing, deletion, and discussion changes. It sends POST requests with JSON payloads containing full model data.

## Development Commands

### Frontend (JavaScript)

```bash
cd js
npm install          # Install dependencies
npm run dev          # Watch mode for development
npm run build        # Production build
```

## Architecture

### Backend (PHP)

- **Namespace**: `ImportAI\Webhook`
- **Entry point**: `extend.php` - Registers extenders for admin frontend, locales, event listeners, and settings
- **Event listeners**:
  - `src/Listener/PostCreatedListener.php` - Listens to `Flarum\Post\Event\Posted` for new posts
  - `src/Listener/PostRevisedListener.php` - Listens to `Flarum\Post\Event\Revised` for post edits
  - `src/Listener/PostDeletedListener.php` - Listens to `Flarum\Post\Event\Deleted` for post deletions
  - `src/Listener/DiscussionRenamedListener.php` - Listens to `Flarum\Discussion\Event\Renamed` for title changes
  - `src/Listener/DiscussionDeletedListener.php` - Listens to `Flarum\Discussion\Event\Deleted` for discussion deletions

### Frontend (JavaScript)

- **Admin panel**: `js/src/admin/index.js` - Registers a settings field for the webhook URL

### Settings

- `import-ai-webhook.webhook_url` - The target URL for webhook POST requests

### Webhook Payload

The webhook sends a JSON payload with full model data. The payload structure varies by event type:

**Post Events** (`post.created`, `post.revised`, `post.deleted`):
```json
{
  "event": "post.created | post.revised | post.deleted",
  "user": { /* full user model attributes */ },
  "post": { /* full post model attributes */ },
  "discussion": { /* full discussion model attributes */ },
  "actor": { /* full actor model attributes (user who triggered the event) */ }
}
```

**Discussion Renamed Event** (`discussion.renamed`):
```json
{
  "event": "discussion.renamed",
  "discussion": { /* full discussion model attributes */ },
  "old_title": "Previous discussion title",
  "actor": { /* full actor model attributes */ }
}
```

**Discussion Deleted Event** (`discussion.deleted`):
```json
{
  "event": "discussion.deleted",
  "discussion": { /* full discussion model attributes */ },
  "actor": { /* full actor model attributes */ }
}
```

**Event Types**:
- `post.created` - Triggered when a new post is created
- `post.revised` - Triggered when an existing post is edited
- `post.deleted` - Triggered when a post is deleted
- `discussion.renamed` - Triggered when a discussion title is changed
- `discussion.deleted` - Triggered when a discussion is deleted

### Identifying Discussion Creation

Flarum Webhook does not have a separate `discussion.created` event. When a new discussion is created, it triggers a `post.created` event for the first post. To identify if a `post.created` event represents a new discussion:

```json
{
  "event": "post.created",
  "post": {
    "number": 1,           // First post has number = 1
    "id": 9,
    // ...
  },
  "discussion": {
    "first_post_id": null,  // null for newly created discussions (or equals post.id)
    "comment_count": 1,      // Only one comment in the discussion
    "last_post_id": 9,       // Equals post.id
    // ...
  }
}
```

**Check conditions**:
- `post.number === 1` - The post is the first in sequence
- `discussion.comment_count === 1` - Only one comment exists
- `discussion.first_post_id === null` OR `discussion.first_post_id === post.id` - For newly created discussions

### Localization

- `locale/en.yml` - English translations
- `locale/zh-Hans.yml` - Simplified Chinese translations

## Git Commit Guidelines

**Format**: `type(scope): Description`

**Types**:

- `feat` - New features
- `fix` - Bug fixes
- `docs` - Documentation changes
- `style` - Styling changes
- `refactor` - Code refactoring
- `perf` - Performance improvements
- `test` - Test additions or changes
- `chore` - Maintenance tasks
- `revert` - Revert previous commits
- `build` - Build system changes

**Rules**:

- Scope is required (e.g., `sidebar`, `tasks`, `auth`)
- Description in sentence case with capital first letter
- Use present tense action verbs (Add, Fix, Support, Update, Replace, Optimize)
- No period at the end
- Keep it concise and focused

**Examples**:

```
feat(apple): Support apple signin
fix(sidebar): Change the abnormal scrolling
chore(children): Optimize children api
refactor(tasks): Add timeout status
```

**Do NOT include**:

- "Generated with Claude Code" or similar attribution
- "Co-Authored-By: Claude" or any Claude co-author tags
