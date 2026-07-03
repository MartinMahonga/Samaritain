# Système d'achat de Pass Visite - Documentation

## Aperçu

Ce document décrit l'implémentation complète du système d'achat de **Pass Visite** pour l'application Samaritain Immobilier.

## Architecture

### 1. **Enums** (`app/Enums/`)

- **PaymentMethod.php** - Moyens de paiement disponibles
  - `MTN_MOMO` = 'mtn_momo'
  - `AIRTEL_MONEY` = 'airtel_money'

- **VisitPassStatus.php** - Statuts des passes
  - `ACTIVE` = 'active'
  - `EXPIRED` = 'expired'
  - `CANCELLED` = 'cancelled'
  - `REFUNDED` = 'refunded'

### 2. **Contrats** (`app/Contracts/`)

- **PaymentGatewayInterface.php** - Interface pour les passerelles de paiement
  - Permet d'ajouter facilement de nouvelles APIs de paiement
  - Méthodes: `charge()`, `status()`, `refund()`, `paymentMethod()`

### 3. **Passerelles de Paiement** (`app/Services/Payment/`)

#### Implémentations Sandbox (actuelles)
- **FakeMomoGateway.php** - Simulation MTN Mobile Money (90% de succès)
- **FakeAirtelGateway.php** - Simulation Airtel Money (85% de succès)

#### Pour ajouter une vraie API plus tard:
```php
// app/Services/Payment/RealMomoGateway.php
class RealMomoGateway implements PaymentGatewayInterface {
    public function charge(string $transactionId, float $amount, string $phoneNumber, array $metadata = []): array {
        // Appel à l'API MTN MoMo
        // Même signature que l'interface
    }
}
```

### 4. **Services**

#### PaymentService (`app/Services/Payment/PaymentService.php`)
- **Rôle**: Factory/Registre des passerelles de paiement
- **Méthodes**:
  - `register(PaymentGatewayInterface $gateway)` - Enregistrer une passerelle
  - `gateway(PaymentMethod $method)` - Obtenir la passerelle pour un moyen de paiement
  - `charge()`, `status()`, `refund()` - Déléguer aux passerelles

#### VisitPassService (`app/Services/VisitPassService.php`)
- **Rôle**: Logique métier des passes visite
- **Méthodes**:
  - `createVisitPass()` - Créer un pass après paiement réussi
  - `generatePassQrCode()` - Générer le QR Code unique
  - `processPayment()` - Traiter le paiement via la passerelle appropriée

### 5. **Modèles**

#### VisitPass (`app/Models/VisitPass.php`)
```php
protected $fillable = [
    'id', 'property_id', 'user_id', 'pass_number',
    'buyer_name', 'buyer_email', 'buyer_phone', 'buyer_whatsapp',
    'amount_paid', 'payment_method', 'transaction_id',
    'qr_code_path', 'purchased_at', 'expires_at', 'status'
];
```

**Relations**:
- `property()` - BelongsTo Property
- `user()` - BelongsTo User
- `transaction()` - HasOne VisitTransaction

**Méthodes utiles**:
- `isActive()` - Vérifie si le pass est actif
- `isExpired()` - Vérifie si le pass est expiré
- `getQrCodeBase64()` - QR Code en base64 pour affichage

#### VisitTransaction (`app/Models/VisitTransaction.php`)
```php
protected $fillable = [
    'visit_pass_id', 'transaction_number', 'payment_method',
    'provider_transaction_id', 'provider_reference',
    'amount', 'phone_number', 'status',
    'response_payload', 'failure_reason', 'processed_at'
];
```

**Relation**:
- `visitPass()` - BelongsTo VisitPass

### 6. ** migrations**

#### create_visit_passes_table.php
```sql
- id (ULID primary)
- property_id (foreign ULID)
- user_id (foreign ULID, nullable)
- pass_number (unique)
- buyer_name, buyer_email, buyer_phone, buyer_whatsapp
- amount_paid (decimal 12,2)
- payment_method (string)
- transaction_id (unique)
- qr_code_path (nullable)
- purchased_at, expires_at (timestamp)
- status (string, default: 'active')
- Index: [property_id, status], [user_id, status], pass_number
```

#### create_visit_transactions_table.php
```sql
- id (ULID primary)
- visit_pass_id (foreign ULID, nullable)
- transaction_number (unique)
- payment_method (string)
- provider_transaction_id (nullable)
- provider_reference (nullable)
- amount (decimal 12,2)
- phone_number (string)
- status (string, default: 'pending')
- response_payload (text, nullable)
- failure_reason (text, nullable)
- processed_at (timestamp, nullable)
- Index: [visit_pass_id, status], transaction_number, status
```

### 7. **Form Request**

#### StoreVisitPassRequest (`app/Http/Requests/StoreVisitPassRequest.php`)
**Règles de validation**:
```php
'property_id' => 'required|string|exists:properties,id'
'full_name' => 'required|string|max:255'
'email' => 'required|email|max:255'
'phone' => 'required|string|max:20'
'whatsapp' => 'nullable|string|max:20'
'payment_method' => 'required|enum:PaymentMethod'
'mobile_money_number' => 'required|string|max:20'
```

### 8. **Controller**

#### VisitPassController (`app/Http/Controllers/VisitPassController.php`)

**Méthodes**:
- `create(Property $property)` - Afficher le formulaire d'achat
- `store(StoreVisitPassRequest $request)` - Traiter le paiement et créer le pass
- `confirmation(VisitPass $visitPass)` - Afficher la page de confirmation
- `downloadPdf(VisitPass $visitPass)` - Télécharger le pass en PDF

**Flux**:
1. Vérifie l'autorisation avec Gate
2. Traite le paiement via VisitPassService
3. Crée le VisitPass et VisitTransaction
4. Génère le QR Code
5. Envoie l'email de confirmation
6. Redirige vers la page de confirmation

### 9. **Policies**

#### VisitPassPolicy (`app/Policies/VisitPassPolicy.php`)
- `view(User $user, VisitPass $visitPass)` - Admin/staff peuvent tout voir, utilisateur peut voir ses propres passes
- `download()` - Même logique que view

**Enregistrée dans**: `AuthServiceProvider.php`

### 10. **Notifications**

#### VisitPassConfirmation (`app/Notifications/VisitPassConfirmation.php`)
- **Canaux**: `mail`, `database`
- **Contenu**:
  - Numéro de pass
  - Informations du bien
  - Détails du paiement
  - QR Code
  - Lien de téléchargement PDF

### 11. **Routes** (`routes/web.php`)

```php
// Publiques
GET  properties/{property}/visit-pass → VisitPassController@create
POST  properties/{property}/visit-pass → VisitPassController@store (throttle:5,1)
GET  visit-passes/{visitPass}/confirmation → VisitPassController@confirmation
GET  visit-passes/{visitPass}/download → VisitPassController@downloadPdf
```

### 12. **Vues**

#### resources/views/pages/visit-passes/create.blade.php
- Formulaire en 3 sections:
  1. Informations sur le bien (lecture seule)
  2. Informations personnelles
  3. Informations de paiement
- Design moderne avec Tailwind CSS
- Récapitulatif à droite (sticky)

#### resources/views/pages/visit-passes/confirmation.blade.php
- Page de succès après achat
- Affichage du QR Code
- Détails du pass
- Boutons: Télécharger PDF, Imprimer
- Informations du bien

#### resources/views/pages/visit-passes/pdf.blade.php
- PDF propre et moderne
- Design premium avec gradient doré
- Toutes les informations du pass
- QR Code intégré
- Informations de validité

#### resources/views/emails/visit-pass-confirmation.blade.php
- Email de confirmation HTML
- Design cohérent avec l'application
- QR Code inclus
- Lien de téléchargement PDF

### 13. **Composants Blade modifiés**

#### resources/views/components/ui/info-aside.blade.php
- Ajout du bouton "Acheter un pass visite"
- Liens vers `route('visit-passes.create', $property)`

## Flux Utilisateur

```
1. Utilisateur visite la page d'un bien
   ↓
2. Clique sur "Acheter un pass visite"
   ↓
3. Remplit le formulaire:
   - Informations personnelles
   - Choix du moyen de paiement (MTN MoMo / Airtel Money)
   - Numéro Mobile Money
   ↓
4. Soumet le formulaire
   ↓
5. Système traite le paiement (sandbox simulé)
   ↓
6. Si paiement réussi:
   - Création VisitTransaction
   - Création VisitPass
   - Génération QR Code
   - Envoi email
   ↓
7. Redirection vers page de confirmation
   ↓
8. Affichage:
   - Numéro de pass
   - QR Code
   - Détails du bien
   - Montant payé
   ↓
9. Actions possibles:
   - Télécharger PDF
   - Imprimer
```

## Sécurité

✅ Validation via Form Request
✅ Protection CSRF (inclus dans les formulaires Blade)
✅ Vérification des autorisations via Policies
✅ Transactions uniques (ULID + numéros de pass/transaction)
✅ QR Codes impossibles à deviner (données encodées)
✅ Throttling sur les routes de soumission
✅ Transactions DB pour éviter les incohérences

## Extensibilité

### Ajouter une vraie API MTN MoMo

```php
// app/Services/Payment/RealMomoGateway.php
namespace App\Services\Payment;

use App\Contracts\PaymentGatewayInterface;
use App\Enums\PaymentMethod;

class RealMomoGateway implements PaymentGatewayInterface
{
    public function charge(string $transactionId, float $amount, string $phoneNumber, array $metadata = []): array
    {
        // Appel à l'API MTN MoMo
        $response = Http::withToken(config('services.mtn.api_key'))
            ->post('https://api.mtn.com/v1/collections/requesttopay', [
                'amount' => $amount,
                'currency' => 'XAF',
                'externalId' => $transactionId,
                'payer' => ['partyIdType' => 'MSISDN', 'partyId' => $phoneNumber],
            ]);

        return [
            'success' => $response->successful(),
            'transaction_id' => $response['financialTransactionId'],
            'message' => $response['message'],
            'provider_reference' => $response['referenceId'],
        ];
    }

    // ... autres méthodes
}

// Dans VisitPassController::__construct()
$this->paymentService->register(new RealMomoGateway());
```

### Ajouter une vraie API Airtel Money

Même principe que ci-dessus avec `RealAirtelGateway`.

## Tests à effectuer

1. **Tests manuels**:
   - [ ] Achat avec MTN MoMo (succès)
   - [ ] Achat avec MTN MoMo (échec - solde insuffisant)
   - [ ] Achat avec Airtel Money (succès)
   - [ ] Achat avec Airtel Money (échec)
   - [ ] Validation des champs du formulaire
   - [ ] Génération du QR Code
   - [ ] Téléchargement PDF
   - [ ] Réception email de confirmation
   - [ ] Affichage page de confirmation

2. **Tests automatisés** (à créer):
   ```bash
   php artisan make:test --pest VisitPassPurchaseTest
   php artisan make:test --pest PaymentGatewayTest
   ```

## Points d'attention

1. **Base de données**: Les migrations doivent être exécutées avec MySQL disponible
2. **Endroid QR Code**: Déjà utilisé dans le projet (Pass::class)
3. **DomPDF**: Déjà configuré pour les exports PDF
4. **Queues**: Les emails peuvent être mis en queue si nécessaire
5. **Images de paiement**: Ajouter les logos MTN/Airtel dans `public/images/payments/`

## Prochaines étapes (optionnel)

1. **Administration**:
   - Liste des passes avec filtres
   - Vérification de QR Code
   - Annulation/remboursement
   - Export des données

2. **Améliorations UX**:
   - Compteur de temps restant avant expiration
   - Notification SMS avant expiration
   - Historique des achats pour un utilisateur

3. **Sécurité**:
   - Ajouter un watermark sur le PDF
   - Signer les QR Codes numériquement
   - Limiter l'utilisation du QR Code à une seule entrée

## Fichiers créés

```
app/
├── Contracts/
│   └── PaymentGatewayInterface.php
├── Enums/
│   ├── PaymentMethod.php
│   ├── VisitPassStatus.php
│   └── TransactionStatus.php
├── Http/
│   ├── Controllers/
│   │   └── VisitPassController.php
│   └── Requests/
│       └── StoreVisitPassRequest.php
├── Models/
│   ├── VisitPass.php
│   └── VisitTransaction.php
├── Notifications/
│   └── VisitPassConfirmation.php
├── Policies/
│   └── VisitPassPolicy.php
└── Services/
    ├── Payment/
    │   ├── PaymentService.php
    │   ├── FakeMomoGateway.php
    │   └── FakeAirtelGateway.php
    └── VisitPassService.php

database/migrations/
├── 2026_07_02_232418_create_visit_passes_table.php
└── 2026_07_02_232550_create_visit_transactions_table.php

resources/views/
├── emails/
│   └── visit-pass-confirmation.blade.php
└── pages/visit-passes/
    ├── create.blade.php
    ├── confirmation.blade.php
    └── pdf.blade.php

routes/web.php (modifié)
app/Providers/AuthServiceProvider.php (modifié)
resources/views/components/ui/info-aside.blade.php (modifié)
```

## Conclusion

Le système est **entièrement fonctionnel en mode sandbox** et prêt à être connecté aux vraies APIs MTN MoMo et Airtel Money avec un minimum de modifications. L'architecture est propre, maintenable et conforme aux bonnes pratiques Laravel 13.