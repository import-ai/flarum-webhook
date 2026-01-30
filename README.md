# Flarum Webhook

This file provides guidance to developers when working with code in this repository.

## Overview

This is a Flarum extension (`import-ai/flarum-webhook`) that triggers HTTP webhooks when users create new posts. It sends POST requests with JSON payloads containing post data.

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
- **Entry point**: `extend.php` - Registers extenders for admin frontend, locales, event listener, and settings
- **Event listener**: `src/Listener/PostCreatedListener.php` - Listens to `Flarum\Post\Event\Posted` and sends webhook requests via cURL

### Frontend (JavaScript)

- **Admin panel**: `js/src/admin/index.js` - Registers a settings field for the webhook URL

### Settings

- `import-ai-webhook.webhook_url` - The target URL for webhook POST requests

### Webhook Payload

```json
{
  "username": "string",
  "create_time": "ISO8601 timestamp",
  "markdown": "post content",
  "title": "discussion title (only for first post)"
}
```

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
