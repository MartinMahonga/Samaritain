# KPay — Contexte d'intégration pour agents IA

Ce document décrit l'intégralité de l'API KPay (paiements et retraits
Mobile Money en Afrique) de façon auto-suffisante. Il est destiné à être
fourni comme contexte à un assistant IA pour générer ou déboguer une
intégration. Toutes les requêtes utilisent une seule URL de base ; c'est le
préfixe de la clé d'API qui sélectionne l'environnement.

- URL de base : `https://admin.kpay.site`
- Devise de référence : `XAF` (chaque transaction utilise la devise du pays de l'opérateur)
- Version d'API : `v1` (incluse dans le chemin, ex. `/api/v1/payments/init`)
- Généré le : 2026-08-01T20:20:57.662Z

Les exemples d'appel sont fournis en PHP. Les clés sont lues
depuis les variables d'environnement `KPAY_API_KEY` et `KPAY_SECRET_KEY`.

## Conventions générales

- API REST : verbes HTTP standard, corps et réponses en JSON.
- Les réponses de succès renvoient directement la ressource.
- Les erreurs suivent l'enveloppe : `{ "statusCode": number, "message": string, "error": string }`. Le champ `message` est toujours présent.
- `externalId` assure l'idempotence : un `409 Conflict` est renvoyé si un `externalId` est déjà actif (réutilisable seulement si la transaction précédente est `FAILED`/`CANCELLED`).
- Le `provider` (opérateur) détermine le pays et la devise. Codes exacts dans le catalogue des providers (plus bas).
- Liste blanche d'autorisation : les providers cochés sur une Application forment une liste blanche. Si elle est non vide, un paiement/retrait dont le provider déduit n'y figure pas est refusé (`400`). Liste vide = aucune restriction.

### Codes HTTP

| Code | Signification |
| --- | --- |
| 200 | OK — requête traitée, le corps contient la ressource. |
| 201 | Created — paiement/retrait initié. |
| 400 | Bad Request — paramètre manquant/invalide (montant hors limites, format de numéro, contrat de mode USSD/GATEWAY non respecté). |
| 401 | Unauthorized — clés manquantes ou invalides. |
| 403 | Forbidden — ressource n'appartenant pas à votre application. |
| 404 | Not Found — paiement, retrait ou session passerelle introuvable. |
| 409 | Conflict — `externalId` déjà utilisé. |
| 422 | Unprocessable Entity — solde wallet insuffisant pour le retrait. |
| 429 | Too Many Requests — limite de débit dépassée. |
| 500 | Internal Server Error — erreur interne ou indisponibilité de l'opérateur. |

### Limitation de débit

- 100 requêtes/minute par défaut.
- 20 requêtes/minute pour l'initialisation passerelle.
- 60 requêtes/minute pour le polling passerelle.
- Sur `429`, appliquez un backoff exponentiel (1 s, 2 s, 4 s…), en respectant l'en-tête `Retry-After` s'il est présent.

## Authentification

Chaque requête authentifiée transmet deux en-têtes HTTP :

| En-tête | Type | Description |
| --- | --- | --- |
| `X-API-Key` | string | Clé publique. Préfixe `kpay_test_` (sandbox) ou `kpay_live_` (production). |
| `X-Secret-Key` | string | Clé secrète. Préfixe `sk_test_` (sandbox) ou `sk_live_` (production). |

Environnements :

- Sandbox (test) : clés `kpay_test_` / `sk_test_`. KPay route vers l'environnement de test ; aucun argent réel. Disponible par défaut.
- Production (live) : clés `kpay_live_` / `sk_live_`, débloquées après validation KYC. Transactions réelles.

L'URL est identique dans les deux cas : seul le préfixe de clé change.
Les clés transitent uniquement de serveur à serveur (jamais côté client),
via HTTPS, stockées en variables d'environnement.

Exemple d'appel authentifié :

```php
$ch = curl_init("https://admin.kpay.site/api/v1/payments/init");
curl_setopt_array($ch, [
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_HTTPHEADER => [
    "X-API-Key: " . getenv("KPAY_API_KEY"),
    "X-Secret-Key: " . getenv("KPAY_SECRET_KEY"),
    "Content-Type: application/json",
  ],
  CURLOPT_POSTFIELDS => json_encode([
    "amount" => 5000,
    "provider" => "MTN_MOMO_CMR",
    "phoneNumber" => "237670000001",
    "externalId" => "ORDER-12345"
  ]),
]);
$data = json_decode(curl_exec($ch), true);
```

Erreurs d'authentification :

- `401` — `X-API-Key`/`X-Secret-Key` manquante ou invalide, ou environnement (test/live) incorrect. Corps : `{ "statusCode": 401, "message": "Invalid API credentials", "error": "Unauthorized" }`.
- `403` — Clés valides mais la ressource appartient à une autre application.

## Frais et limites

Valeurs en vigueur (source : `GET /api/public/platform-info`) :

| Paramètre | Valeur |
| --- | --- |
| Frais paiement (deposit) | 5.00 % |
| Frais retrait (payout) | 5.00 % |
| Retrait minimum | 100 XAF |
| Retrait maximum | 500 000 XAF |
| Email support | no-reply@kpay.site |
| Maintenance | non |

## Pays couverts et catalogue des providers

KPay couvre 12 pays. Le `code` ci-dessous est la
valeur EXACTE à passer dans le champ `provider` des endpoints
d'initialisation ; le pays et la devise en sont déduits. Décimales :
« Sans décimales » = montant en unité entière ; « 2 décimales » /
« Selon l'opération » = le provider gère des fractions.

| Pays | Indicatif | Opérateur | Code provider | Devise(s) | Décimales |
| --- | --- | --- | --- | --- | --- |
| Bénin (BEN) | +229 | MTN | `MTN_MOMO_BEN` | XOF | Sans décimales |
| Bénin (BEN) | +229 | Moov | `MOOV_BEN` | XOF | Sans décimales |
| Cameroun (CMR) | +237 | MTN | `MTN_MOMO_CMR` | XAF | Sans décimales |
| Cameroun (CMR) | +237 | Orange | `ORANGE_CMR` | XAF | Sans décimales |
| Côte d'Ivoire (CIV) | +225 | MTN | `MTN_MOMO_CIV` | XOF | Sans décimales |
| Côte d'Ivoire (CIV) | +225 | Orange | `ORANGE_CIV` | XOF | Sans décimales |
| RD Congo (COD) | +243 | Vodacom M-Pesa | `VODACOM_MPESA_COD` | CDF, USD | Selon l'opération |
| RD Congo (COD) | +243 | Airtel | `AIRTEL_COD` | CDF, USD | 2 décimales |
| RD Congo (COD) | +243 | Orange | `ORANGE_COD` | CDF, USD | 2 décimales |
| Gabon (GAB) | +241 | Airtel | `AIRTEL_GAB` | XAF | 2 décimales |
| Kenya (KEN) | +254 | M-Pesa | `MPESA_KEN` | KES | Selon l'opération |
| Congo (COG) | +242 | Airtel | `AIRTEL_COG` | XAF | Sans décimales |
| Congo (COG) | +242 | MTN | `MTN_MOMO_COG` | XAF | Sans décimales |
| Rwanda (RWA) | +250 | Airtel | `AIRTEL_RWA` | RWF | Sans décimales |
| Rwanda (RWA) | +250 | MTN | `MTN_MOMO_RWA` | RWF | Sans décimales |
| Sénégal (SEN) | +221 | Free | `FREE_SEN` | XOF | Sans décimales |
| Sénégal (SEN) | +221 | Orange | `ORANGE_SEN` | XOF | Sans décimales |
| Sierra Leone (SLE) | +232 | Orange | `ORANGE_SLE` | SLE | 2 décimales |
| Ouganda (UGA) | +256 | Airtel | `AIRTEL_OAPI_UGA` | UGX | Sans décimales |
| Ouganda (UGA) | +256 | MTN | `MTN_MOMO_UGA` | UGX | 2 décimales |
| Zambie (ZMB) | +260 | Airtel | `AIRTEL_OAPI_ZMB` | ZMW | 2 décimales |
| Zambie (ZMB) | +260 | MTN | `MTN_MOMO_ZMB` | ZMW | 2 décimales |
| Zambie (ZMB) | +260 | Zamtel | `ZAMTEL_ZMB` | ZMW | 2 décimales |

## Paiements (deposits)

Encaisser un paiement Mobile Money, en mode USSD (push direct) ou via la
passerelle hébergée KPay (le client saisit lui-même opérateur et numéro).

### L'objet Paiement

| Champ | Type | Description |
| --- | --- | --- |
| `id` | string | Identifiant unique du paiement. |
| `reference` | string | Référence interne KPay (source de vérité). |
| `providerReference` | string | null | Identifiant de l'opération côté opérateur (depositId, UUID). |
| `status` | enum | PENDING \| PROCESSING \| COMPLETED \| FAILED \| CANCELLED. |
| `amount` | number | Montant brut demandé, dans la devise du pays de l'opérateur. |
| `netAmount` | number | Montant net crédité après commission. |
| `feeAmount` | number | Commission prélevée. |
| `currency` | string | Devise déduite du pays de l'opérateur (XAF, XOF, KES…). |
| `externalId` | string | Votre identifiant de transaction (idempotence). |
| `provider` | string | null | Provider déduit du numéro (ex. MTN_MOMO_CMR). |
| `country` | string | null | Pays ISO 3166-1 alpha-3 (ex. CMR), déduit du numéro. |
| `phoneNumber` | string | Numéro Mobile Money du payeur (normalisé, sans + ni 0 initial). |
| `isTest` | boolean | true si initiée avec une clé de test (sandbox). |
| `metadata` | object | Données libres renvoyées telles quelles. |
| `completedAt` | string | null | Horodatage de complétion (si COMPLETED). |
| `failureReason` | string | null | Motif d'échec (si FAILED). |

Statuts : `PENDING` (attente validation client) → `PROCESSING` (traitement opérateur) → `COMPLETED` (réussi, net crédité au wallet). `FAILED` (échec : fonds insuffisants, timeout). `CANCELLED` (annulé par le client).

### Mode USSD — initier un paiement

`POST /api/v1/payments/init`

Indiquez explicitement le `provider`. Le pays et la devise en sont déduits.

| Champ | Type | Description |
| --- | --- | --- |
| `amount` | number (requis) | Montant dans la devise du provider, unité entière sauf si le provider supporte les décimales. Minimum 50 XAF en zone Cameroun. Une commission est prélevée. |
| `provider` | string (requis) | Code opérateur (ex. MTN_MOMO_CMR, ORANGE_CMR). Détermine pays et devise. |
| `phoneNumber` | string (requis) | Numéro Mobile Money au format international. Numéro de test en sandbox. |
| `externalId` | string (requis) | Identifiant unique de transaction. 409 si déjà actif. |
| `description` | string | Description présentée au client. |
| `customerName` | string | Nom complet du client. |
| `customerEmail` | string | Email du client. |
| `metadata` | object | Métadonnées JSON libres, renvoyées dans le statut. |

Exemple de requête :

```php
$ch = curl_init("https://admin.kpay.site/api/v1/payments/init");
curl_setopt_array($ch, [
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_HTTPHEADER => [
    "X-API-Key: " . getenv("KPAY_API_KEY"),
    "X-Secret-Key: " . getenv("KPAY_SECRET_KEY"),
    "Content-Type: application/json",
  ],
  CURLOPT_POSTFIELDS => json_encode([
    "amount" => 5000,
    "provider" => "MTN_MOMO_CMR",
    "phoneNumber" => "237670000001",
    "externalId" => "ORDER-12345"
  ]),
]);
$data = json_decode(curl_exec($ch), true);
```

Réponse `201` :

```json
{
  "id": "pay_abc123",
  "reference": "KPAY-20260514-ABC123",
  "providerReference": "f4401bd2-1568-4140-bf2d-eb77d2b2b639",
  "status": "PENDING",
  "amount": 5000,
  "currency": "XAF",
  "externalId": "ORDER-12345",
  "provider": "MTN_MOMO_CMR",
  "country": "CMR",
  "phoneNumber": "237670000001",
  "isTest": false,
  "message": "Paiement initié. Le client doit valider la demande sur son téléphone."
}
```

### Mode passerelle hébergée (GATEWAY)

En GATEWAY, KPay héberge la page de paiement. Appelez `POST /api/v1/payments/init`
SANS `phoneNumber` / `paymentMethod` / `customerName`, avec `returnUrl` (requis)
et `cancelUrl` (optionnel).

```php
$ch = curl_init("https://admin.kpay.site/api/v1/payments/init");
curl_setopt_array($ch, [
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_HTTPHEADER => [
    "X-API-Key: " . getenv("KPAY_API_KEY"),
    "X-Secret-Key: " . getenv("KPAY_SECRET_KEY"),
    "Content-Type: application/json",
  ],
  CURLOPT_POSTFIELDS => json_encode([
    "amount" => 5000,
    "externalId" => "ORDER-12346",
    "returnUrl" => "https://monsite.com/return",
    "cancelUrl" => "https://monsite.com/cancel"
  ]),
]);
$data = json_decode(curl_exec($ch), true);
```

Réponse `201` (GATEWAY) :

```json
{
  "id": "pay_xyz789",
  "reference": "KPAY-20260514-XYZ789",
  "externalId": "ORDER-12346",
  "status": "PENDING",
  "mode": "GATEWAY",
  "amount": 5000,
  "currency": "XAF",
  "gatewayUrl": "https://admin.kpay.site/gateway/gw_8sJ2...",
  "expiresAt": "2026-05-16T10:30:00.000Z",
  "isTest": false,
  "message": "Redirect the customer to gatewayUrl to complete the payment."
}
```

Redirection de retour (query signée) :

```text
{returnUrl}?status=COMPLETED&reference=KPAY-20260514-ABC123&externalId=ORDER-12345&ts=1747245600000&sig=<hmac-sha256-hex>
```

Règle d'or : ne marquez la commande payée qu'après signature VALIDE ET
statut `COMPLETED` confirmé via `GET /api/v1/payments/:id`. Rejetez si `ts`
a plus de 10 minutes (anti-rejeu). La chaîne signée est
`status|reference|externalId|ts`, HMAC-SHA256 hex avec le secret passerelle.

### Suivi du statut (polling)

`GET /api/v1/payments/:id`

```php
$ch = curl_init("https://admin.kpay.site/api/v1/payments/pay_abc123");
curl_setopt_array($ch, [
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_CUSTOMREQUEST => "GET",
  CURLOPT_HTTPHEADER => [
    "X-API-Key: " . getenv("KPAY_API_KEY"),
    "X-Secret-Key: " . getenv("KPAY_SECRET_KEY"),
  ],
]);
$data = json_decode(curl_exec($ch), true);
```

Espacez les appels (ex. toutes les 3 s) avec un délai croissant ; arrêtez-vous
sur un statut terminal (`COMPLETED`, `FAILED`, `CANCELLED`). Le webhook reste
la source d'autorité.

## Retraits (payouts)

Envoyer des fonds depuis votre wallet KPay vers un compte Mobile Money,
en mode USSD ou via la passerelle hebergee. Supporte les retraits
cross-country (payout interfrontalier) via le parametre `sourceCountry`.

### L'objet Retrait

| Champ | Type | Description |
| --- | --- | --- |
| `id` | string | Identifiant unique du retrait. |
| `reference` | string | Reference interne KPay. |
| `providerReference` | string | null | Identifiant cote operateur (payoutId, UUID). |
| `status` | enum | PENDING \| PROCESSING \| COMPLETED \| FAILED \| CANCELLED. |
| `amount` | number | Montant brut demande, dans la devise du pays de l'operateur. |
| `netAmount` | number | Montant net envoye au beneficiaire apres commission. |
| `feeAmount` | number | Commission de retrait prelevee. |
| `currency` | string | Devise du wallet source debite. |
| `payoutCurrency` | string | null | Devise envoyee au beneficiaire (present uniquement si cross-devise, ex. GHS). |
| `payoutAmount` | number | null | Montant converti envoye au beneficiaire en devise destination (present uniquement si cross-devise). |
| `exchangeRate` | number | null | Taux de change applique source vers destination (present uniquement si cross-devise). |
| `externalId` | string | Votre identifiant (idempotence, retry-safe). |
| `provider` | string | null | Provider deduit du numero (ex. MTN_MOMO_CMR). |
| `country` | string | null | Pays ISO 3166-1 alpha-3 (ex. CMR). |

### Mode USSD -- initier un retrait

`POST /api/v1/payments/withdraw`

| Champ | Type | Description |
| --- | --- | --- |
| `amount` | number (requis) | Montant dans la devise du provider, minimum 100 XAF en zone Cameroun. Une commission est prelevee. |
| `provider` | string (requis) | Code operateur du beneficiaire (ex. MTN_MOMO_CMR, ORANGE_GAB). Determine pays et devise. |
| `phoneNumber` | string (requis) | Numero Mobile Money du beneficiaire (mode USSD), format international. |
| `sourceCountry` | string | Code pays ISO3 du wallet source (ex. CMR). Permet un payout cross-country et cross-devise : debiter un wallet d'un pays pour payer dans un autre, meme si les devises different (ex. CMR/XAF vers CIV/XOF). La conversion est automatique au taux de change en temps reel. Si omis, le wallet du pays du provider est utilise. |
| `externalId` | string | Identifiant unique -- active l'idempotence (reessai sur). |
| `description` | string | Description pour reconciliation. |
| `metadata` | object | Metadonnees JSON libres. |

```php
$ch = curl_init("https://admin.kpay.site/api/v1/payments/withdraw");
curl_setopt_array($ch, [
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_HTTPHEADER => [
    "X-API-Key: " . getenv("KPAY_API_KEY"),
    "X-Secret-Key: " . getenv("KPAY_SECRET_KEY"),
    "Content-Type: application/json",
  ],
  CURLOPT_POSTFIELDS => json_encode([
    "amount" => 5000,
    "provider" => "MTN_MOMO_CMR",
    "phoneNumber" => "237670000001",
    "externalId" => "WD-ORDER-98765"
  ]),
]);
$data = json_decode(curl_exec($ch), true);
```

Réponse `201` :

```json
{
  "id": "wdr_xyz456",
  "reference": "KPAY-WD-20260514-XYZ456",
  "providerReference": "f4401bd2-1568-4140-bf2d-eb77d2b2b639",
  "status": "PENDING",
  "amount": 5000,
  "netAmount": 4750,
  "feeAmount": 250,
  "currency": "XAF",
  "externalId": "WD-ORDER-98765",
  "provider": "MTN_MOMO_CMR",
  "country": "CMR",
  "phoneNumber": "237670000001",
  "isTest": false,
  "message": "Retrait initié, transfert en cours auprès de l'opérateur."
}
```

### Payout cross-country (interfrontalier)

Pour payer un beneficiaire dans un autre pays en utilisant le solde d'un
wallet source, ajoutez `sourceCountry`. La conversion de devise est
automatique si les devises different (ex. XAF vers GHS, XAF vers XOF).
Le montant (`amount`) est en devise du wallet source. Le beneficiaire recoit
le montant converti (`payoutAmount`) apres deduction des frais et conversion.

Exemple meme devise (CMR vers GAB, XAF) :

```php
$ch = curl_init("https://admin.kpay.site/api/v1/payments/withdraw");
curl_setopt_array($ch, [
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_HTTPHEADER => [
    "X-API-Key: " . getenv("KPAY_API_KEY"),
    "X-Secret-Key: " . getenv("KPAY_SECRET_KEY"),
    "Content-Type: application/json",
  ],
  CURLOPT_POSTFIELDS => json_encode([
    "amount" => 10000,
    "provider" => "ORANGE_GAB",
    "phoneNumber" => "24174000001",
    "sourceCountry" => "CMR",
    "externalId" => "PAYOUT-CROSS-001",
    "description" => "Paiement fournisseur Gabon"
  ]),
]);
$data = json_decode(curl_exec($ch), true);
```

Exemple cross-devise (CMR/XAF vers CIV/XOF) :

```php
$ch = curl_init("https://admin.kpay.site/api/v1/payments/withdraw");
curl_setopt_array($ch, [
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_HTTPHEADER => [
    "X-API-Key: " . getenv("KPAY_API_KEY"),
    "X-Secret-Key: " . getenv("KPAY_SECRET_KEY"),
    "Content-Type: application/json",
  ],
  CURLOPT_POSTFIELDS => json_encode([
    "amount" => 50000,
    "provider" => "MTN_MOMO_CIV",
    "phoneNumber" => "2250503456089",
    "sourceCountry" => "CMR",
    "externalId" => "PAYOUT-CROSS-002",
    "description" => "Paiement fournisseur Cote d'Ivoire"
  ]),
]);
$data = json_decode(curl_exec($ch), true);
```

Reponse cross-devise `201` :

```json
{
  "id": "wdr_cross123",
  "reference": "KPAY-WD-20260626-CROSS123",
  "status": "PENDING",
  "amount": 50000,
  "netAmount": 47500,
  "feeAmount": 2500,
  "currency": "XAF",
  "payoutCurrency": "XOF",
  "payoutAmount": 48211.75,
  "exchangeRate": 1.0150,
  "isTest": false,
  "message": "Withdrawal request received. Processing via Mobile Money."
}
```

Les champs `payoutCurrency`, `payoutAmount` et `exchangeRate` n'apparaissent
que pour les payouts cross-devise. Pour les payouts meme devise, la reponse
reste identique au payout standard.

### Mode passerelle hebergee (GATEWAY)

Appelez `POST /api/v1/payments/withdraw` SANS `phoneNumber` / `paymentMethod`,
avec `returnUrl` (requis) et `cancelUrl` (optionnel). Le retour passerelle
utilise le même schéma de signature que les paiements (`status|reference|externalId|ts`).

```php
$ch = curl_init("https://admin.kpay.site/api/v1/payments/withdraw");
curl_setopt_array($ch, [
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_HTTPHEADER => [
    "X-API-Key: " . getenv("KPAY_API_KEY"),
    "X-Secret-Key: " . getenv("KPAY_SECRET_KEY"),
    "Content-Type: application/json",
  ],
  CURLOPT_POSTFIELDS => json_encode([
    "amount" => 5000,
    "externalId" => "WD-ORD-001",
    "returnUrl" => "https://monsite.com/return"
  ]),
]);
$data = json_decode(curl_exec($ch), true);
```

### Suivi du statut

`GET /api/v1/payments/withdraw/:id`

```php
$ch = curl_init("https://admin.kpay.site/api/v1/payments/withdraw/wdr_xyz456");
curl_setopt_array($ch, [
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_CUSTOMREQUEST => "GET",
  CURLOPT_HTTPHEADER => [
    "X-API-Key: " . getenv("KPAY_API_KEY"),
    "X-Secret-Key: " . getenv("KPAY_SECRET_KEY"),
  ],
]);
$data = json_decode(curl_exec($ch), true);
```

Si le solde du wallet ne couvre pas le montant (commission incluse),
l'initialisation renvoie `422 Unprocessable Entity`.

## Utilitaires

### Informations de l'application -- GET /api/v1/payments/me

`GET /api/v1/payments/me`

Retourne les informations de l'application et de la company associees aux
cles API fournies. Utile pour recuperer l'`applicationId` (necessaire pour
les transferts inter-wallet) et verifier l'environnement courant.

```php
$ch = curl_init("https://admin.kpay.site/api/v1/payments/me");
curl_setopt_array($ch, [
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_CUSTOMREQUEST => "GET",
  CURLOPT_HTTPHEADER => [
    "X-API-Key: " . getenv("KPAY_API_KEY"),
    "X-Secret-Key: " . getenv("KPAY_SECRET_KEY"),
  ],
]);
$data = json_decode(curl_exec($ch), true);
```

Reponse `200` :

```json
{
  "application": {
    "id": "b8aefadd-88b4-561a-8a90-2f9814207bfb",
    "name": "Mon Application"
  },
  "company": {
    "id": "e7c3b4f5-8d9e-4a1b-9c2d-3e4f5a6b7c8d",
    "name": "My Startup Inc"
  },
  "environment": "TEST"
}
```

| Champ | Type | Description |
| --- | --- | --- |
| `application.id` | string | UUID de l'application. A utiliser dans le champ `applicationId` des transferts inter-wallet. |
| `application.name` | string | Nom de l'application. |
| `company.id` | string | UUID de la company proprietaire. |
| `company.name` | string | Nom de la company. |
| `environment` | string | Environnement resolu : TEST ou PRODUCTION. |

### Obtenir un token JWT -- POST /api/v1/payments/token

`POST /api/v1/payments/token`

Echange vos cles API (X-API-Key + X-Secret-Key) contre un token JWT Bearer
valable 30 minutes. Ce token est requis pour les endpoints proteges par JWT,
notamment le transfert inter-wallet (`POST /api/wallets/transfer`).

L'environnement (TEST ou PRODUCTION) est deduit automatiquement du prefixe
de la cle API : `kpay_test_` produit un token sandbox, `kpay_live_` un token
production. Le token ne donne acces qu'aux wallets de l'environnement correspondant.

```php
$ch = curl_init("https://admin.kpay.site/api/v1/payments/token");
curl_setopt_array($ch, [
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_HTTPHEADER => [
    "X-API-Key: " . getenv("KPAY_API_KEY"),
    "X-Secret-Key: " . getenv("KPAY_SECRET_KEY"),
  ],
]);
$data = json_decode(curl_exec($ch), true);
```

Reponse `201` :

```json
{
  "accessToken": "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...",
  "expiresIn": "30m",
  "environment": "TEST"
}
```

| Champ | Type | Description |
| --- | --- | --- |
| `accessToken` | string | Token JWT a passer dans l'en-tete Authorization: Bearer <token>. |
| `expiresIn` | string | Duree de validite du token (30 minutes). |
| `environment` | string | Environnement du token : TEST ou PRODUCTION. |

### Taux de change -- GET /api/v1/payments/exchange-rate

`GET /api/v1/payments/exchange-rate?from=XAF&to=XOF`

Retourne le taux de change entre deux devises. Les taux sont mis en cache
1 heure cote KPay. Utile pour estimer le montant d'un transfert inter-wallet
cross-devise avant de l'effectuer.

| Champ | Type | Description |
| --- | --- | --- |
| `from` | string (requis, query) | Code devise source (ex. XAF, XOF, KES). |
| `to` | string (requis, query) | Code devise destination (ex. XOF, NGN, GHS). |

```php
$ch = curl_init("https://admin.kpay.site/api/v1/payments/exchange-rate?from=XAF&to=XOF");
curl_setopt_array($ch, [
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_CUSTOMREQUEST => "GET",
  CURLOPT_HTTPHEADER => [
    "X-API-Key: " . getenv("KPAY_API_KEY"),
    "X-Secret-Key: " . getenv("KPAY_SECRET_KEY"),
  ],
]);
$data = json_decode(curl_exec($ch), true);
```

Reponse `200` :

```json
{
  "from": "XAF",
  "to": "XOF",
  "rate": 1.0153
}
```

Note : les devises d'une meme zone monetaire (ex. XAF/XAF entre pays CEMAC)
renvoient un taux de `1`.

### Deviner l'operateur d'un numero -- POST /api/v1/payments/predict-provider

`POST /api/v1/payments/predict-provider`

Identifie le provider Mobile Money et le pays d'un numero. Le resultat est
une suggestion ; vous restez responsable du `provider` transmis a l'init.
En sandbox, seuls les numeros de test sont reconnus.

| Champ | Type | Description |
| --- | --- | --- |
| `phoneNumber` | string (requis) | Numero Mobile Money (format national ou international, ex. 237670000001, 260763456789). |

```php
$ch = curl_init("https://admin.kpay.site/api/v1/payments/predict-provider");
curl_setopt_array($ch, [
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_HTTPHEADER => [
    "X-API-Key: " . getenv("KPAY_API_KEY"),
    "X-Secret-Key: " . getenv("KPAY_SECRET_KEY"),
    "Content-Type: application/json",
  ],
  CURLOPT_POSTFIELDS => json_encode([
    "phoneNumber" => "237670000001"
  ]),
]);
$data = json_decode(curl_exec($ch), true);
```

Reponse `201` :

```json
{
  "country": "CMR",
  "provider": "MTN_MOMO_CMR",
  "phoneNumber": "237670000001"
}
```

### Disponibilite des operateurs -- GET /api/v1/payments/availability

`GET /api/v1/payments/availability`

Etat operationnel de chaque provider, par pays et par type d'operation
(`DEPOSIT`, `PAYOUT`, `REMITTANCE`). Donnees mises en cache 1 minute cote KPay.
Statuts : `OPERATIONAL` (fonctionnel), `DELAYED` (delais anormaux), `CLOSED` (indisponible).

```php
$ch = curl_init("https://admin.kpay.site/api/v1/payments/availability");
curl_setopt_array($ch, [
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_CUSTOMREQUEST => "GET",
  CURLOPT_HTTPHEADER => [
    "X-API-Key: " . getenv("KPAY_API_KEY"),
    "X-Secret-Key: " . getenv("KPAY_SECRET_KEY"),
  ],
]);
$data = json_decode(curl_exec($ch), true);
```

Reponse `200` :

```json
[
  {
    "country": "CMR",
    "providers": [
      {
        "provider": "MTN_MOMO_CMR",
        "operationTypes": [
          { "operationType": "DEPOSIT", "status": "OPERATIONAL" },
          { "operationType": "PAYOUT", "status": "OPERATIONAL" }
        ]
      }
    ]
  }
]
```

### Consulter le solde du wallet -- GET /api/v1/payments/balance

`GET /api/v1/payments/balance`

```php
$ch = curl_init("https://admin.kpay.site/api/v1/payments/balance");
curl_setopt_array($ch, [
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_CUSTOMREQUEST => "GET",
  CURLOPT_HTTPHEADER => [
    "X-API-Key: " . getenv("KPAY_API_KEY"),
    "X-Secret-Key: " . getenv("KPAY_SECRET_KEY"),
  ],
]);
$data = json_decode(curl_exec($ch), true);
```

Reponse `200` :

```json
[
  {
    "currency": "XAF",
    "balance": 150000,
    "reservedBalance": 25000,
    "availableBalance": 125000
  }
]
```

| Champ | Type | Description |
| --- | --- | --- |
| `currency` | string | Devise du wallet (ex. XAF). |
| `balance` | number | Solde total du wallet. |
| `reservedBalance` | number | Montant reserve pour des retraits en cours. |
| `availableBalance` | number | balance - reservedBalance. Montant utilisable pour des retraits. |

## Transferts interfrontaliers (cross-border)

KPay permet de deplacer des fonds entre wallets de pays differents au sein
d'une meme application. Deux mecanismes complementaires :

- **Transfert inter-wallet** (`POST /api/wallets/transfer`) : deplace des fonds
  d'un wallet pays A vers un wallet pays B, avec conversion de devise automatique
  si necessaire. Authentification par JWT (obtenu via `POST /api/v1/payments/token`).
- **Payout cross-country** (`POST /api/v1/payments/withdraw` avec `sourceCountry`) :
  debite un wallet d'un pays pour payer un beneficiaire dans un autre pays,
  avec conversion de devise automatique si necessaire (ex. CMR/XAF vers CIV/XOF).
  Les champs `payoutCurrency`, `payoutAmount` et `exchangeRate` sont retournes
  dans la reponse. Voir section Retraits.

### Transfert inter-wallet

`POST /api/wallets/transfer`

Authentification : en-tete `Authorization: Bearer <token>` (token JWT obtenu
via `POST /api/v1/payments/token`). Les cles API (X-API-Key / X-Secret-Key)
ne sont pas acceptees sur cet endpoint.

| Champ | Type | Description |
| --- | --- | --- |
| `applicationId` | string (requis) | UUID de l'application. Recuperable via GET /api/v1/payments/me (champ application.id) ou depuis le tableau de bord. |
| `sourceCountry` | string (requis) | Code pays ISO 3166-1 alpha-3 du wallet source (ex. CMR). |
| `destinationCountry` | string (requis) | Code pays ISO 3166-1 alpha-3 du wallet destination (ex. GAB, SEN). |
| `amount` | number (requis) | Montant a debiter du wallet source (min. 100, max. 10 000 000). En devise du pays source. |
| `description` | string | Description du transfert. |
| `externalId` | string | Identifiant pour l'idempotence (409 si deja actif). |

Exemple meme devise (CMR vers GAB, XAF vers XAF, taux 1:1) :

```json
// POST /api/wallets/transfer
{
  "applicationId": "e7c3b4f5-8d9e-4a1b-9c2d-3e4f5a6b7c8d",
  "sourceCountry": "CMR",
  "destinationCountry": "GAB",
  "amount": 50000,
  "description": "Transfert de fonds CMR vers GAB",
  "externalId": "TRF-2026-001"
}
```

Reponse `201` :

```json
{
  "id": "a1b2c3d4-...",
  "sourceTransaction": {
    "id": "a1b2c3d4-...",
    "reference": "TRF-OUT-ABC123",
    "type": "WALLET_TRANSFER_OUT",
    "amount": 50000,
    "currency": "XAF",
    "country": "CMR"
  },
  "destinationTransaction": {
    "id": "e5f6a7b8-...",
    "reference": "TRF-IN-ABC123",
    "type": "WALLET_TRANSFER_IN",
    "amount": 50000,
    "currency": "XAF",
    "country": "GAB"
  },
  "exchangeRate": 1,
  "description": "Transfert de fonds CMR vers GAB",
  "externalId": "TRF-2026-001",
  "createdAt": "2026-06-23T10:00:00.000Z"
}
```

Exemple cross-devise (CMR vers SEN, XAF vers XOF) :

```json
// POST /api/wallets/transfer
{
  "applicationId": "e7c3b4f5-8d9e-4a1b-9c2d-3e4f5a6b7c8d",
  "sourceCountry": "CMR",
  "destinationCountry": "SEN",
  "amount": 100000,
  "description": "Approvisionnement wallet Sénégal",
  "externalId": "TRF-2026-002"
}
```

Reponse `201` :

```json
{
  "id": "c3d4e5f6-...",
  "sourceTransaction": {
    "id": "c3d4e5f6-...",
    "reference": "TRF-OUT-DEF456",
    "type": "WALLET_TRANSFER_OUT",
    "amount": 100000,
    "currency": "XAF",
    "country": "CMR"
  },
  "destinationTransaction": {
    "id": "g7h8i9j0-...",
    "reference": "TRF-IN-DEF456",
    "type": "WALLET_TRANSFER_IN",
    "amount": 101530,
    "currency": "XOF",
    "country": "SEN"
  },
  "exchangeRate": 1.0153,
  "description": "Approvisionnement wallet Sénégal",
  "externalId": "TRF-2026-002",
  "createdAt": "2026-06-23T10:05:00.000Z"
}
```

Le champ `exchangeRate` indique le taux applique. Pour les transferts au sein
d'une meme zone monetaire (ex. CMR vers GAB, tous deux XAF), le taux est `1`.
Pour consulter le taux avant d'effectuer le transfert, utilisez
`GET /api/v1/payments/exchange-rate?from=XAF&to=XOF`.

### Erreurs specifiques aux transferts inter-wallet

| Code | Cause |
| --- | --- |
| 400 | Parametres invalides (pays source/destination identiques, montant hors bornes, applicationId manquant). |
| 403 | L'application n'appartient pas a votre compte. |
| 404 | Wallet source ou destination introuvable pour le pays indique. |
| 409 | `externalId` deja utilise par un transfert actif. |
| 422 | Solde insuffisant sur le wallet source. |

### Pays et zones monetaires supportes

| Zone | Pays | Code | Devise |
| --- | --- | --- | --- |
| CEMAC (XAF) | Cameroun | CMR | XAF |
| CEMAC (XAF) | Gabon | GAB | XAF |
| CEMAC (XAF) | Congo (Rep.) | COG | XAF |
| UEMOA (XOF) | Senegal | SEN | XOF |
| UEMOA (XOF) | Cote d'Ivoire | CIV | XOF |
| UEMOA (XOF) | Benin | BEN | XOF |
| Autres | Kenya | KEN | KES |
| Autres | RD Congo | COD | CDF |
| Autres | Ouganda | UGA | UGX |
| Autres | Rwanda | RWA | RWF |

Les transferts intra-zone (meme devise, ex. CMR vers GAB) s'effectuent au
taux 1:1. Les transferts inter-zones (devises differentes, ex. XAF vers XOF)
utilisent un taux de change en temps reel (cache 1 heure).

**Note** : les operateurs Wave (Cote d'Ivoire et Senegal) sont temporairement
suspendus le temps de finaliser l'integration de leur nouveau protocole
d'authentification. Les autres operateurs de ces pays restent disponibles.

### Scenarios types

**1. Payout cross-country meme devise** : debiter un wallet CMR (XAF) pour
payer un beneficiaire au Gabon (XAF) — un seul appel a
`POST /api/v1/payments/withdraw` avec `sourceCountry: "CMR"`.

**2. Payout cross-devise (1 seul appel)** : debiter un wallet CMR (XAF) pour
payer un beneficiaire en Cote d'Ivoire (XOF) — un seul appel a
`POST /api/v1/payments/withdraw` avec `sourceCountry: "CMR"`. La conversion
est automatique. La reponse inclut `payoutCurrency`, `payoutAmount`, `exchangeRate`.

**3. Consolidation** : rapatrier les fonds de plusieurs wallets pays vers un
wallet central via des transferts inter-wallet successifs.

## Webhooks

KPay envoie un `POST` à vos URLs de callback à chaque changement de statut.
Jusqu'à 4 URLs configurables sur l'application : générique (fallback),
Dépôts (`payment.*`), Retraits (`payout.*`), Remboursements (`refund.*`).
KPay cherche d'abord l'URL spécifique au type, sinon l'URL générique ; si
aucune n'est configurée, la notification n'est pas envoyée.

### Objet événement

```json
{
  "event": "payment.completed",
  "paymentId": "pay_abc123",
  "reference": "KPAY-DEP-12345",
  "status": "COMPLETED",
  "amount": 5000,
  "phoneNumber": "237670000001",
  "externalId": "ORDER-12345",
  "metadata": { "orderId": "12345" },
  "completedAt": "2026-05-14T10:02:30.000Z",
  "failedAt": null,
  "failureReason": null,
  "timestamp": "2026-05-14T10:02:31.000Z"
}
```

| Champ | Type | Description |
| --- | --- | --- |
| `event` | string | Type d'événement (voir liste). |
| `paymentId` | string | Identifiant unique de la transaction KPay. |
| `reference` | string | Référence interne KPay. |
| `status` | enum | COMPLETED \| FAILED \| CANCELLED. |
| `amount` | number | Montant de la transaction. |
| `phoneNumber` | string | Numéro du client. |
| `externalId` | string | Votre identifiant (si fourni à l'init). |
| `metadata` | object | Métadonnées transmises à l'init. |
| `completedAt` | string | null | Horodatage de complétion (si COMPLETED). |
| `failedAt` | string | null | Horodatage d'échec (si FAILED/CANCELLED). |
| `failureReason` | string | null | Motif d'échec le cas échéant. |
| `timestamp` | string | Horodatage d'envoi du webhook (ISO 8601). |

### Types d'événements

- Dépôts : `payment.completed`, `payment.failed`, `payment.cancelled`.
- Retraits : `payout.completed`, `payout.failed`, `payout.cancelled`.
- Remboursements : `refund.completed`, `refund.failed`, `refund.cancelled`.

### En-têtes de la requête entrante

| En-tête | Description |
| --- | --- |
| `X-KPAY-Signature` | HMAC-SHA256 (hex) calculé sur le corps JSON BRUT reçu. |
| `X-KPAY-Event` | Nom de l'événement (ex. payment.completed). |
| `User-Agent` | KPAY-Webhook/1.0 |

### Sécurité et bonnes pratiques

- Calculez le HMAC-SHA256 sur le corps BRUT reçu (non re-sérialisé) avec votre secret webhook, comparez en temps constant, puis seulement traitez. Cette signature est distincte de la signature de retour passerelle.
- Répondez `200` rapidement (avant tout traitement long ; traitez en asynchrone si besoin).
- Idempotence : un même événement peut arriver plusieurs fois ; déduisez via `paymentId` / `externalId`.
- Réessais KPay : 3 tentatives avec backoff (1 s, 2 s, 4 s), timeout 3 s/tentative. Pas de réessai sur `4xx` ; réessai sur `5xx`/réseau.
- HTTPS obligatoire, certificat valide.
- Le webhook est la source d'autorité du statut final ; `GET /api/v1/payments/:id` en est le complément de secours.

## Mode test (sandbox)

Avec une clé `kpay_test_…`, KPay route vos requêtes vers le sandbox.
Le NUMÉRO utilisé détermine l'issue de la transaction : `COMPLETED`,
`FAILED` (avec un `failureCode` précis) ou `SUBMITTED` (reste en attente,
utile pour tester le polling). Les numéros diffèrent entre paiements
(deposits) et retraits (payouts). Passez en production après validation KYC
depuis le tableau de bord.

### Numéros de test par pays

#### Bénin — MTN_MOMO_BEN, MOOV_BEN

Paiements (deposits) :

| Numéro (MSISDN) | Résultat | failureCode |
| --- | --- | --- |
| `22951345029` | FAILED | PAYER_NOT_FOUND |
| `22951345039` | FAILED | PAYMENT_NOT_APPROVED |
| `22951345069` | FAILED | UNSPECIFIED_FAILURE |
| `22951345129` | SUBMITTED | — |
| `22951345789` | COMPLETED | — |

Retraits (payouts) :

| Numéro (MSISDN) | Résultat | failureCode |
| --- | --- | --- |
| `22951345089` | FAILED | RECIPIENT_NOT_FOUND |
| `22951345119` | FAILED | UNSPECIFIED_FAILURE |
| `22951345129` | SUBMITTED | — |
| `22951345789` | COMPLETED | — |

#### Cameroun — MTN_MOMO_CMR, ORANGE_CMR

Paiements (deposits) :

| Numéro (MSISDN) | Résultat | failureCode |
| --- | --- | --- |
| `237653456019` | FAILED | PAYER_LIMIT_REACHED |
| `237653456029` | FAILED | PAYER_NOT_FOUND |
| `237653456039` | FAILED | PAYMENT_NOT_APPROVED |
| `237653456069` | FAILED | UNSPECIFIED_FAILURE |
| `237653456129` | SUBMITTED | — |
| `237653456789` | COMPLETED | — |

Retraits (payouts) :

| Numéro (MSISDN) | Résultat | failureCode |
| --- | --- | --- |
| `237653456089` | FAILED | RECIPIENT_NOT_FOUND |
| `237653456119` | FAILED | UNSPECIFIED_FAILURE |
| `237653456129` | SUBMITTED | — |
| `237653456789` | COMPLETED | — |

#### Côte d'Ivoire — MTN_MOMO_CIV, ORANGE_CIV

Paiements (deposits) :

| Numéro (MSISDN) | Résultat | failureCode |
| --- | --- | --- |
| `2250503456029` | FAILED | PAYER_NOT_FOUND |
| `2250503456039` | FAILED | PAYMENT_NOT_APPROVED |
| `2250503456069` | FAILED | UNSPECIFIED_FAILURE |
| `2250503456129` | SUBMITTED | — |
| `2250503456789` | COMPLETED | — |

Retraits (payouts) :

| Numéro (MSISDN) | Résultat | failureCode |
| --- | --- | --- |
| `2250503456089` | FAILED | RECIPIENT_NOT_FOUND |
| `2250503456119` | FAILED | UNSPECIFIED_FAILURE |
| `2250503456129` | SUBMITTED | — |
| `2250503456789` | COMPLETED | — |

#### RD Congo — VODACOM_MPESA_COD, AIRTEL_COD, ORANGE_COD

Paiements (deposits) :

| Numéro (MSISDN) | Résultat | failureCode |
| --- | --- | --- |
| `243813456019` | FAILED | PAYER_LIMIT_REACHED |
| `243813456029` | FAILED | PAYER_NOT_FOUND |
| `243813456039` | FAILED | PAYMENT_NOT_APPROVED |
| `243813456049` | FAILED | INSUFFICIENT_BALANCE |
| `243813456069` | FAILED | UNSPECIFIED_FAILURE |
| `243813456129` | SUBMITTED | — |
| `243813456789` | COMPLETED | — |

Retraits (payouts) :

| Numéro (MSISDN) | Résultat | failureCode |
| --- | --- | --- |
| `243813456089` | FAILED | RECIPIENT_NOT_FOUND |
| `243813456119` | FAILED | UNSPECIFIED_FAILURE |
| `243813456129` | SUBMITTED | — |
| `243813456789` | COMPLETED | — |

#### Gabon — AIRTEL_GAB

Paiements (deposits) :

| Numéro (MSISDN) | Résultat | failureCode |
| --- | --- | --- |
| `24174345048` | FAILED | INSUFFICIENT_BALANCE |
| `24174345068` | FAILED | UNSPECIFIED_FAILURE |
| `24174345128` | SUBMITTED | — |
| `24174345678` | COMPLETED | — |

Retraits (payouts) :

| Numéro (MSISDN) | Résultat | failureCode |
| --- | --- | --- |
| `24174345088` | FAILED | RECIPIENT_NOT_FOUND |
| `24174345118` | FAILED | UNSPECIFIED_FAILURE |
| `24174345128` | SUBMITTED | — |
| `24174345678` | COMPLETED | — |

#### Kenya — MPESA_KEN

Paiements (deposits) :

| Numéro (MSISDN) | Résultat | failureCode |
| --- | --- | --- |
| `254703456019` | FAILED | PAYER_LIMIT_REACHED |
| `254703456039` | FAILED | PAYMENT_NOT_APPROVED |
| `254703456049` | FAILED | INSUFFICIENT_BALANCE |
| `254703456059` | FAILED | TRANSACTION_ALREADY_IN_PROCESS |
| `254703456069` | FAILED | UNSPECIFIED_FAILURE |
| `254703456129` | SUBMITTED | — |
| `254703456789` | COMPLETED | — |

Retraits (payouts) :

| Numéro (MSISDN) | Résultat | failureCode |
| --- | --- | --- |
| `254703456089` | FAILED | RECIPIENT_NOT_FOUND |
| `254703456099` | FAILED | WALLET_LIMIT_REACHED |
| `254703456109` | FAILED | RECIPIENT_LIMIT_REACHED |
| `254703456119` | FAILED | UNSPECIFIED_FAILURE |
| `254703456129` | SUBMITTED | — |
| `254703456789` | COMPLETED | — |

#### Congo — AIRTEL_COG, MTN_MOMO_COG

Paiements (deposits) :

| Numéro (MSISDN) | Résultat | failureCode |
| --- | --- | --- |
| `242053456039` | FAILED | PAYMENT_NOT_APPROVED |
| `242053456049` | FAILED | INSUFFICIENT_BALANCE |
| `242053456069` | FAILED | UNSPECIFIED_FAILURE |
| `242053456129` | SUBMITTED | — |
| `242053456789` | COMPLETED | — |

Retraits (payouts) :

| Numéro (MSISDN) | Résultat | failureCode |
| --- | --- | --- |
| `242053456089` | FAILED | RECIPIENT_NOT_FOUND |
| `242053456119` | FAILED | UNSPECIFIED_FAILURE |
| `242053456129` | SUBMITTED | — |
| `242053456789` | COMPLETED | — |

#### Rwanda — AIRTEL_RWA, MTN_MOMO_RWA

Paiements (deposits) :

| Numéro (MSISDN) | Résultat | failureCode |
| --- | --- | --- |
| `250733456039` | FAILED | PAYMENT_NOT_APPROVED |
| `250733456049` | FAILED | INSUFFICIENT_BALANCE |
| `250733456069` | FAILED | UNSPECIFIED_FAILURE |
| `250733456129` | SUBMITTED | — |
| `250733456789` | COMPLETED | — |

Retraits (payouts) :

| Numéro (MSISDN) | Résultat | failureCode |
| --- | --- | --- |
| `250733456089` | FAILED | RECIPIENT_NOT_FOUND |
| `250733456119` | FAILED | UNSPECIFIED_FAILURE |
| `250733456129` | SUBMITTED | — |
| `250733456789` | COMPLETED | — |

#### Sénégal — FREE_SEN, ORANGE_SEN

Paiements (deposits) :

| Numéro (MSISDN) | Résultat | failureCode |
| --- | --- | --- |
| `221763456049` | FAILED | INSUFFICIENT_BALANCE |
| `221763456069` | FAILED | UNSPECIFIED_FAILURE |
| `221763456129` | SUBMITTED | — |
| `221763456789` | COMPLETED | — |

Retraits (payouts) :

| Numéro (MSISDN) | Résultat | failureCode |
| --- | --- | --- |
| `221763456119` | FAILED | UNSPECIFIED_FAILURE |
| `221763456129` | SUBMITTED | — |
| `221763456789` | COMPLETED | — |

#### Sierra Leone — ORANGE_SLE

Paiements (deposits) :

| Numéro (MSISDN) | Résultat | failureCode |
| --- | --- | --- |
| `23276123456` | COMPLETED | — |

Retraits (payouts) :

| Numéro (MSISDN) | Résultat | failureCode |
| --- | --- | --- |
| `23276123456` | COMPLETED | — |

#### Ouganda — AIRTEL_OAPI_UGA, MTN_MOMO_UGA

Paiements (deposits) :

| Numéro (MSISDN) | Résultat | failureCode |
| --- | --- | --- |
| `256753456019` | FAILED | PAYER_LIMIT_REACHED |
| `256753456039` | FAILED | PAYMENT_NOT_APPROVED |
| `256753456049` | FAILED | INSUFFICIENT_BALANCE |
| `256753456069` | FAILED | UNSPECIFIED_FAILURE |
| `256753456129` | SUBMITTED | — |
| `256753456789` | COMPLETED | — |

Retraits (payouts) :

| Numéro (MSISDN) | Résultat | failureCode |
| --- | --- | --- |
| `256753456089` | FAILED | RECIPIENT_NOT_FOUND |
| `256753456099` | FAILED | WALLET_LIMIT_REACHED |
| `256753456119` | FAILED | UNSPECIFIED_FAILURE |
| `256753456129` | SUBMITTED | — |
| `256753456789` | COMPLETED | — |

#### Zambie — AIRTEL_OAPI_ZMB, MTN_MOMO_ZMB, ZAMTEL_ZMB

Paiements (deposits) :

| Numéro (MSISDN) | Résultat | failureCode |
| --- | --- | --- |
| `260973456019` | FAILED | PAYER_LIMIT_REACHED |
| `260973456039` | FAILED | PAYMENT_NOT_APPROVED |
| `260973456049` | FAILED | INSUFFICIENT_BALANCE |
| `260973456069` | FAILED | UNSPECIFIED_FAILURE |
| `260973456129` | SUBMITTED | — |
| `260973456789` | COMPLETED | — |

Retraits (payouts) :

| Numéro (MSISDN) | Résultat | failureCode |
| --- | --- | --- |
| `260973456089` | FAILED | RECIPIENT_NOT_FOUND |
| `260973456119` | FAILED | UNSPECIFIED_FAILURE |
| `260973456129` | SUBMITTED | — |
| `260973456789` | COMPLETED | — |

## Erreurs — diagnostic

Enveloppe d'erreur : `{ "statusCode": 400, "message": "...", "error": "Bad Request" }`.

Erreurs spécifiques aux paiements :

- `400` Montant invalide — inférieur au minimum (50 XAF) ou non numérique.
- `400` Numéro / opérateur — `phoneNumber` mal formé (format international requis) ou opérateur/pays non supporté.
- `400` Provider non autorisé — provider déduit absent de la liste blanche de l'Application.
- `400` Contrat de mode — `phoneNumber`/`paymentMethod`/`customerName` interdits en GATEWAY ; `returnUrl` requis en GATEWAY.
- `409` externalId dupliqué — un paiement actif existe déjà pour cet `externalId`.
- `500` Erreur fournisseur — réessayez après quelques secondes.

Erreurs spécifiques aux retraits :

- `422` Solde insuffisant — le solde disponible ne couvre pas le montant (commission incluse).
- `400` Montant minimum — inférieur au minimum de retrait (100 XAF).
- `400` Bénéficiaire invalide — `phoneNumber` manquant en USSD, ou `returnUrl` absent en GATEWAY.

failureCode courants (sandbox/opérateur) : `PAYER_NOT_FOUND`,
`RECIPIENT_NOT_FOUND`, `INSUFFICIENT_BALANCE`, `PAYMENT_NOT_APPROVED`,
`PAYER_LIMIT_REACHED`, `UNSPECIFIED_FAILURE`.

Pour les erreurs transitoires (réseau, `429`, `500`), implémentez un réessai
avec backoff exponentiel.

## Spécification OpenAPI live

La spécification OpenAPI complète et à jour est disponible en JSON :

```php
$ch = curl_init("https://admin.kpay.site/api/docs/public-json");
curl_setopt_array($ch, [
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_CUSTOMREQUEST => "GET",
]);
$data = json_decode(curl_exec($ch), true);
```

Utilisez-la pour générer un client typé ou valider les schémas exacts.

---

Fin du contexte KPay. Référez-vous toujours à la spécification OpenAPI live

pour les schémas exacts et aux endpoints publics pour les valeurs courantes.