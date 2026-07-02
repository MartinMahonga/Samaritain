<table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
    <tr>
        <td style="padding: 0 0 16px 0;">
            <h1 style="margin: 0; font-size: 24px; font-weight: 700; color: #111827; line-height: 1.3; text-align: center;">
                {{ __("Nouvelle demande de visite") }}
            </h1>
        </td>
    </tr>
    <tr>
        <td style="padding: 0 0 24px 0;">
            <p style="margin: 0; font-size: 15px; color: #374151; line-height: 1.6;">
                {{ __("Une nouvelle demande de visite a été soumise.") }}
            </p>
        </td>
    </tr>
    <tr>
        <td style="padding: 0 0 16px 0;">
            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color: #f9fafb; border-radius: 8px;">
                <tr>
                    <td style="padding: 16px 20px;">
                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                            <tr>
                                <td style="padding: 4px 0; font-size: 14px; color: #6b7280; width: 40%;">{{ __("Nom") }}</td>
                                <td style="padding: 4px 0; font-size: 14px; color: #111827; font-weight: 500;">{{ $visitRequest->full_name }}</td>
                            </tr>
                            <tr>
                                <td style="padding: 4px 0; font-size: 14px; color: #6b7280;">{{ __("Téléphone") }}</td>
                                <td style="padding: 4px 0; font-size: 14px; color: #111827; font-weight: 500;">{{ $visitRequest->phone }}</td>
                            </tr>
                            <tr>
                                <td style="padding: 4px 0; font-size: 14px; color: #6b7280;">{{ __("Ville") }}</td>
                                <td style="padding: 4px 0; font-size: 14px; color: #111827; font-weight: 500;">{{ $visitRequest->city }}</td>
                            </tr>
                            <tr>
                                <td style="padding: 4px 0; font-size: 14px; color: #6b7280;">{{ __("Catégorie") }}</td>
                                <td style="padding: 4px 0; font-size: 14px; color: #111827; font-weight: 500;">{{ $visitRequest->property_category }}</td>
                            </tr>
                            <tr>
                                <td style="padding: 4px 0; font-size: 14px; color: #6b7280;">{{ __("Date souhaitée") }}</td>
                                <td style="padding: 4px 0; font-size: 14px; color: #111827; font-weight: 500;">{{ $visitRequest->preferred_date->format('d/m/Y') }}</td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </tr>
    @if($visitRequest->message)
        <tr>
            <td style="padding: 0 0 16px 0;">
                <p style="margin: 0 0 8px 0; font-size: 14px; font-weight: 600; color: #374151;">{{ __("Message") }}</p>
                <p style="margin: 0; font-size: 14px; color: #6b7280; line-height: 1.6; font-style: italic;">
                    "{{ $visitRequest->message }}"
                </p>
            </td>
        </tr>
    @endif
    <tr>
        <td align="center" style="padding: 8px 0 0 0;">
            <table role="presentation" cellpadding="0" cellspacing="0" border="0">
                <tr>
                    <td align="center" style="border-radius: 8px;" bgcolor="#b8860b">
                        <a href="{{ url('/admin/visits') }}" target="_blank" class="email-button" style="display: inline-block; padding: 14px 32px; font-size: 15px; font-weight: 600; line-height: 1.4; text-decoration: none; border-radius: 8px; color: #ffffff !important; background-color: #b8860b;">
                            {{ __("Voir les demandes") }}
                        </a>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>