<table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
    <tr>
        <td style="padding: 0 0 16px 0;">
            <h1 style="margin: 0; font-size: 24px; font-weight: 700; color: #111827; line-height: 1.3; text-align: center;">
                {{ __("Confirmez votre adresse email") }}
            </h1>
        </td>
    </tr>
    <tr>
        <td style="padding: 0 0 8px 0;">
            @if(isset($user) && $user->name)
                <p style="margin: 0; font-size: 15px; color: #374151; line-height: 1.6;">
                    {{ __('Bonjour') }} <strong>{{ $user->name }}</strong>,
                </p>
            @else
                <p style="margin: 0; font-size: 15px; color: #374151; line-height: 1.6;">
                    {{ __('Bonjour') }},
                </p>
            @endif
        </td>
    </tr>
    <tr>
        <td style="padding: 0 0 24px 0;">
            <p style="margin: 0; font-size: 15px; color: #374151; line-height: 1.6;">
                {{ __("Merci de vous être inscrit.") }}
            </p>
            <p style="margin: 8px 0 0 0; font-size: 15px; color: #374151; line-height: 1.6;">
                {{ __("Veuillez confirmer votre adresse email afin d'activer votre compte.") }}
            </p>
        </td>
    </tr>
    <tr>
        <td align="center" style="padding: 0 0 32px 0;">
            <table role="presentation" cellpadding="0" cellspacing="0" border="0">
                <tr>
                    <td align="center" style="border-radius: 8px;" bgcolor="#b8860b">
                        <a href="{{ $verificationUrl ?? '#' }}" target="_blank" class="email-button" style="display: inline-block; padding: 14px 32px; font-size: 15px; font-weight: 600; line-height: 1.4; text-decoration: none; border-radius: 8px; color: #ffffff !important; background-color: #b8860b;">
                            {{ __("Confirmer mon adresse") }}
                        </a>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td style="padding: 0 0 8px 0; border-top: 1px solid #e5e7eb;">
            <p style="margin: 16px 0 0 0; font-size: 13px; color: #6b7280; line-height: 1.5;">
                {{ __("Si vous avez des difficultés à cliquer sur le bouton") }}, {{ __("copiez et collez l'URL ci-dessous dans votre navigateur") }} :
            </p>
            <p style="margin: 4px 0 0 0; font-size: 13px; color: #b8860b; word-break: break-all; line-height: 1.5;">
                <a href="{{ $verificationUrl ?? '#' }}" style="color: #b8860b; text-decoration: underline;">{{ $verificationUrl ?? '#' }}</a>
            </p>
        </td>
    </tr>
    <tr>
        <td style="padding: 16px 0 0 0;">
            <p style="margin: 0; font-size: 13px; color: #6b7280; line-height: 1.5; font-style: italic;">
                {{ __("Si vous n'avez pas créé de compte, vous pouvez ignorer cet email.") }}
            </p>
        </td>
    </tr>
</table>