<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="color-scheme" content="light">
    <meta name="supported-color-schemes" content="light">
    <title>{{ $title ?? config('app.name') }}</title>
    <style>
        /* Base */
        body {
            margin: 0;
            padding: 0;
            width: 100% !important;
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
            background-color: #f6f8fa;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Inter', Roboto, Helvetica, Arial, sans-serif;
        }

        table {
            border-collapse: collapse;
            mso-table-lspace: 0;
            mso-table-rspace: 0;
        }

        td {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Inter', Roboto, Helvetica, Arial, sans-serif;
        }

        img {
            border: 0;
            outline: none;
            text-decoration: none;
            -ms-interpolation-mode: bicubic;
        }

        /* Card */
        .email-card {
            background-color: #ffffff;
            border-radius: 12px;
            overflow: hidden;
        }

        /* Button */
        .email-button {
            display: inline-block;
            padding: 14px 32px;
            font-size: 15px;
            font-weight: 600;
            line-height: 1.4;
            text-decoration: none;
            border-radius: 8px;
            color: #ffffff !important;
            background-color: #b8860b;
        }

        /* Footer */
        .email-footer {
            color: #6b7280;
            font-size: 13px;
            line-height: 1.5;
        }

        .email-footer a {
            color: #6b7280;
            text-decoration: underline;
        }

        /* Responsive */
        @media only screen and (max-width: 600px) {
            .email-container {
                width: 100% !important;
                max-width: 100% !important;
            }
            .email-padding {
                padding-left: 24px !important;
                padding-right: 24px !important;
            }
            .email-button {
                display: block !important;
                width: auto !important;
                text-align: center !important;
            }
        }
    </style>
</head>
<body style="margin: 0; padding: 0; background-color: #f6f8fa; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Inter', Roboto, Helvetica, Arial, sans-serif;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color: #f6f8fa;">
        <tr>
            <td align="center" style="padding: 40px 16px 24px 16px;">
                <!--[if mso]>
                <table role="presentation" width="560" cellpadding="0" cellspacing="0" border="0">
                <tr>
                <td>
                <![endif]-->

                <!-- Logo -->
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" class="email-container" style="max-width: 560px;">
                    <tr>
                        <td align="center" style="padding-bottom: 32px;">
                            <a href="{{ config('app.url') }}" style="text-decoration: none;">
                                <img src="{{ asset('dark_logo.svg') }}" alt="{{ config('app.name') }}" width="160" style="display: block; border: 0; outline: none; height: auto; max-height: 48px; width: auto;" />
                            </a>
                        </td>
                    </tr>
                </table>

                <!-- Card -->
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" class="email-container" style="max-width: 560px;">
                    <tr>
                        <td style="background-color: #ffffff; border-radius: 12px; padding: 0;" class="email-card">
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                                <tr>
                                    <td class="email-padding" style="padding: 40px 40px 32px 40px;">
                                        {{ $slot ?? '' }}
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>

                <!-- Footer -->
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" class="email-container" style="max-width: 560px;">
                    <tr>
                        <td align="center" class="email-footer" style="padding: 24px 16px 0 16px; color: #6b7280; font-size: 13px; line-height: 1.5;">
                            <p style="margin: 0 0 8px 0;">
                                {{ config('app.name') }} &mdash; {{ __('Tous droits réservés.') }}
                            </p>
                            <p style="margin: 0;">
                                {{ __("Cet email a été envoyé automatiquement, merci de ne pas y répondre.") }}
                            </p>
                            <p style="margin: 8px 0 0 0;">
                                &copy; {{ date('Y') }} {{ config('app.name') }}.
                            </p>
                        </td>
                    </tr>
                </table>

                <!--[if mso]>
                </td>
                </tr>
                </table>
                <![endif]-->
            </td>
        </tr>
    </table>
</body>
</html>