<x-mail::message>
# Invitation à rejoindre l'équipe

Vous avez été invité à rejoindre l'agence en tant que membre.

<x-mail::button :url="$acceptUrl">
Accepter l'invitation
</x-mail::button>

Ce lien expire le {{ $invitation->expires_at->format('d/m/Y à H:i') }}.

Si vous avez des difficultés à cliquer sur le bouton, copiez et collez l'URL ci-dessous dans votre navigateur :

{{ $acceptUrl }}

Merci,<br>
L'équipe
</x-mail::message>