<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SMTP Test</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; background-color: #0f172a; color: #e2e8f0; margin: 0; padding: 0; }
        .wrapper { max-width: 520px; margin: 32px auto; background-color: #1e293b; border-radius: 12px; overflow: hidden; border: 1px solid #334155; }
        .header { background: linear-gradient(135deg, #10b981, #059669); padding: 28px 24px; text-align: center; }
        .header h1 { margin: 0; color: #ffffff; font-size: 20px; font-weight: 800; }
        .content { padding: 24px; text-align: center; }
        .icon { font-size: 48px; margin-bottom: 16px; }
        .p { color: #cbd5e1; font-size: 15px; line-height: 1.6; margin-bottom: 20px; }
        .info-card { background: #0f172a; border-radius: 8px; padding: 14px; border: 1px solid #334155; font-size: 13px; color: #94a3b8; text-align: left; }
        .info-card div { padding: 4px 0; }
        .footer { text-align: center; padding: 16px; color: #64748b; font-size: 12px; border-top: 1px solid #334155; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="header">
            <h1>SMTP Test Successful</h1>
        </div>
        <div class="content">
            <div class="icon">🚀</div>
            <p class="p">
                Congratulations! If you are reading this email, your <strong>SMTP mail settings</strong> in {{ config('app.name', 'Nawarika Store') }} are configured correctly and active.
            </p>
            <div class="info-card">
                <div><strong>Host:</strong> {{ config('mail.mailers.smtp.host') }}</div>
                <div><strong>Port:</strong> {{ config('mail.mailers.smtp.port') }}</div>
                <div><strong>From:</strong> {{ config('mail.from.address') }} ({{ config('mail.from.name') }})</div>
                <div><strong>Dispatched At:</strong> {{ now()->toDateTimeString() }}</div>
            </div>
        </div>
        <div class="footer">
            {{ config('app.name', 'Nawarika Store') }} Admin Diagnostic
        </div>
    </div>
</body>
</html>
