import app from 'flarum/admin/app';

app.initializers.add('import-ai-webhook', function() {
  app.extensionData
    .for('import-ai-webhook')
    .registerSetting({
      setting: 'import-ai-webhook.webhook_url',
      label: app.translator.trans('import-ai-webhook.admin.settings.webhook_url_label'),
      help: app.translator.trans('import-ai-webhook.admin.settings.webhook_url_help'),
      type: 'text',
      placeholder: 'https://example.com/webhook',
    })
    .registerSetting({
      setting: 'import-ai-webhook.bearer_token',
      label: app.translator.trans('import-ai-webhook.admin.settings.bearer_token_label'),
      help: app.translator.trans('import-ai-webhook.admin.settings.bearer_token_help'),
      type: 'text',
      placeholder: 'your-bearer-token-here',
    });
});