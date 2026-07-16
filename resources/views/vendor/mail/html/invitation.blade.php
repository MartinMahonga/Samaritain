<table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
    <tr>
        <td style="padding: 0 0 16px 0;">
            <h1 style="margin: 0; font-size: 24px; font-weight: 700; color: #111827; line-height: 1.3; text-align: center;">
                {{ __("Invitation à rejoindre l'équipe") }}
            </h1>
        </td>
    </tr>
    <tr>
        <td style="padding: 0 0 24px 0;">
            <p style="margin: 0; font-size: 15px; color: #374151; line-height: 1.6;">
                {{ __("Vous avez été invité à rejoindre l'entreprise en tant que membre.") }}
            </p>
            <p style="margin: 12px 0 0 0; font-size: 15px; color: #374151; line-height: 1.6;">
                {{ __("Cliquez sur le bouton ci-dessous pour créer votre compte et rejoindre l'équipe.") }}
            </p>
        </td>
    </tr>
    <tr>
        <td align="center" style="padding: 0 0 24px 0;">
            <table role="presentation" cellpadding="0" cellspacing="0" border="0">
                <tr>
                    <td align="center" style="border-radius: 8px;" bgcolor="#b8860b">
                        <a href="{{ $acceptUrl }}" target="_blank" class="email-button" style="display: inline-block; padding: 14px 32px; font-size: 15px; font-weight: 600; line-height: 1.4; text-decoration: none; border-radius: 8px; color: #ffffff !important; background-color: #b8860b;">
                            {{ __("Accepter l'invitation") }}
                        </a>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td style="padding: 0 0 8px 0; border-top: 1px solid #e5e7eb;">
            <p style="margin: 16px 0 0 0; font-size: 13px; color: #6b7280; line-height: 1.5;">
                {{ __("Ce lien expire le") }} {{ $invitation->expires_at->format('d/m/Y à H:i') }}.
            </p>
        </td>
    </tr>
    <tr>
        <td style="padding: 0 0 8px 0;">
            <p style="margin: 8px 0 0 0; font-size: 13px; color: #6b7280; line-height: 1.5;">
                {{ __("Si vous avez des difficultés à cliquer sur le bouton") }}, {{ __("copiez et collez l'URL ci-dessous dans votre navigateur") }} :
            </p>
            <p style="margin: 4px 0 0 0; font-size: 13px; color: #b8860b; word-break: break-all; line-height: 1.5;">
                <a href="{{ $acceptUrl }}" style="color: #b8860b; text-decoration: underline;">{{ $acceptUrl }}</a>
            </p>
        </td>
    </tr>
</table>