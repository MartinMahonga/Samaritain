# pawaPay API Reference (v2)

Full docs: https://docs.pawapay.io/v2/docs/ · OpenAPI spec: https://docs.pawapay.io/v2/api-reference/openapi_v2.yaml

## Base URLs

| Environment | Base URL |
|---|---|
| Sandbox | `https://api.sandbox.pawapay.io` |
| Production | `https://api.pawapay.io` |

## Authentication

`Authorization: Bearer <API_TOKEN>` on every request. Token generated per-environment in the pawaPay Dashboard (Dashboard → System configuration → API tokens). Sandbox and production tokens are different — never share.

---

## Deposits (collect money from a customer)

### Initiate — `POST /v2/deposits`

```json
{
  "depositId": "afb57b93-7849-49aa-babb-4c3ccbfe3d79",   // UUIDv4, client-generated, idempotency key
  "amount": "100",
  "currency": "XAF",
  "payer": {
    "type": "MMO",
    "accountDetails": {
      "phoneNumber": "242063456789",
      "provider": "MTN_MOMO_COG"
    }
  }
}
```

Response:
```json
{ "depositId": "afb57b93-...", "status": "ACCEPTED", "nextStep": "FINAL_STATUS", "created": "2025-05-15T07:38:56Z" }
```
`status`: `ACCEPTED` | `REJECTED` | `DUPLICATE_IGNORED`. If `REJECTED`, response includes `failureReason.failureCode` / `failureMessage`.

For redirection-based providers (Wave SEN/CIV), also send `successfulUrl` / `failedUrl`; response `nextStep` will be `GET_AUTH_URL` then `REDIRECT_TO_AUTH_URL`, and the callback/status-check will include an `authorizationUrl` to redirect the customer to.

For preauthorized providers (Orange BFA), include `preAuthorisationCode` (OTP the customer generates via USSD before initiation).

### Check status — `GET /v2/deposits/{depositId}`
```json
{ "status": "FOUND", "data": { "depositId": "...", "status": "COMPLETED", "amount": "100.00", ... } }
```
`status`: `FOUND` | `NOT_FOUND`. Only treat as `FAILED` locally when `NOT_FOUND` (never invented from a network error alone).

### Callback (webhook) — final status pushed to your configured callback URL
```json
{
  "depositId": "afb57b93-...",
  "status": "COMPLETED",
  "amount": "100.00",
  "currency": "XAF",
  "country": "COG",
  "payer": { "type": "MMO", "accountDetails": { "phoneNumber": "242063456789", "provider": "MTN_MOMO_COG" } },
  "customerMessage": "DEMO",
  "created": "2025-05-15T07:38:56Z",
  "providerTransactionId": "df0e9405-..."
}
```
Final `status` values: `COMPLETED` | `FAILED` | `IN_RECONCILIATION` (transient, no action needed — pawaPay resolves it automatically).

### Resend callback — `POST /v2/deposits/resend-callback/{depositId}`

---

## Payouts (send money to a customer)

### Initiate — `POST /v2/payouts`
```json
{
  "payoutId": "afb57b93-7849-49aa-babb-4c3ccbfe3d79",
  "amount": "100",
  "currency": "XAF",
  "recipient": {
    "type": "MMO",
    "accountDetails": { "phoneNumber": "242063456789", "provider": "MTN_MOMO_COG" }
  }
}
```
Response: `{ "payoutId": "...", "status": "ACCEPTED", "created": "..." }`. No PIN prompt — payouts don't need customer authorization, processed within seconds (unless provider is `DELAYED`, see below).

### Bulk payouts — `POST /v2/payouts/bulk` — array of the same payload shape.

### Check status — `GET /v2/payouts/{payoutId}` — same `FOUND`/`NOT_FOUND` pattern as deposits. A found payout can be in `ENQUEUED` status if the provider is `DELAYED` (see Provider availability below).

### Cancel an enqueued payout — `GET /v2/payouts/fail-enqueued/{payoutId}` — only works while `ENQUEUED`; rejected if already `PROCESSING`. Triggers a `FAILED` callback once cancelled.

### Callback — same shape as deposit callback but with `recipient` instead of `payer`.

---

## Refunds (return money from a completed deposit)

### Initiate — `POST /v2/refunds`
```json
{ "refundId": "f02b543c-541c-4f21-bbea-20d2d56063d6", "depositId": "afb57b93-..." }
```
Full refund by default. For a **partial refund**, add `amount` + `currency`:
```json
{ "refundId": "...", "depositId": "...", "amount": "30", "currency": "XAF" }
```
Rules: only one refund `PROCESSING` at a time per deposit (else `REFUND_IN_PROGRESS` rejection). Total refunded can't exceed the original deposit amount (else `AMOUNT_TOO_LARGE`/`DEPOSIT_ALREADY_REFUNDED`). Multiple partial refunds allowed; a final full refund (no `amount`) refunds the remainder.

### Check status — `GET /v2/refunds/{refundId}`

---

## Toolkit endpoints (use these, don't hardcode provider lists)

### Active configuration — `GET /v2/active-conf?country={ISO3}&operationType={DEPOSIT|PAYOUT|REFUND}`
Returns, per country/provider/currency: `displayName`, `logo`, `prefix` (calling code), `authType` (`PROVIDER_AUTH` | others), `pinPrompt` (`AUTOMATIC`/`MANUAL`), `pinPromptRevivable`, `pinPromptInstructions`, `decimalsInAmount` (`NONE`/`TWO_PLACES`), `minAmount`, `maxAmount`, provider `status` (`OPERATIONAL`/`CLOSED`). Drives dynamic provider-selection UI without hardcoding — new providers appear automatically.

### Provider availability — `GET /v2/availability?country={ISO3}&operationType={...}`
Lighter-weight than active-conf, returns `OPERATIONAL` | `DELAYED` | `CLOSED` per provider. `DELAYED` = requests get `ACCEPTED` then queued as `ENQUEUED` until the provider recovers; `CLOSED` = requests rejected outright.

### Predict provider (phone validation) — `POST /v2/predict-provider`
```json
{ "phoneNumber": "24206 345-6789a" }
```
→ `{ "country": "COG", "provider": "MTN_MOMO_COG", "phoneNumber": "242063456789" }`. Always run customer-entered phone numbers through this before initiating a payment — handles whitespace, leading zeros, ITU E.164 edge cases.

### Wallet balances — `GET /v2/wallet-balances`

### Public keys (for callback signature verification) — `GET /v2/public-keys`

---

## Checkouts / Payment Page (hosted alternative)

If the user doesn't want to build a custom deposit form, pawaPay offers a hosted widget:
- **Payment Page** — `POST /v2/paymentpage` — returns a redirect URL for a single deposit.
- **Checkouts** — `POST /v2/checkouts` — a full hosted checkout intent (supports amount chosen by pawaPay's widget, multiple providers, tracked as one `checkoutId` through completion). Use `GET /v2/checkouts/{checkoutId}` to check status and a `checkout-callback` for the final result.

Recommend this route when the user wants something shippable fast without building phone-number/provider-selection UI themselves.

---

## Status & failure codes (high level)

- Initiation `status`: `ACCEPTED`, `REJECTED`, `DUPLICATE_IGNORED`.
- Final `status` (via callback/check): `COMPLETED`, `FAILED`, `ENQUEUED` (payouts/refunds only, provider delayed), `IN_RECONCILIATION` (transient).
- `REJECTED`/`FAILED` responses carry `failureReason: { failureCode, failureMessage }`. Common codes: `INVALID_AMOUNT`, `AMOUNT_OUT_OF_BOUNDS`, `INVALID_CURRENCY`, `PROVIDER_TEMPORARILY_UNAVAILABLE`, `PAYER_NOT_FOUND` / `RECIPIENT_NOT_FOUND`, `PAYMENT_NOT_APPROVED`, `INSUFFICIENT_BALANCE`, `UNSPECIFIED_FAILURE` (provider gave no detail), `UNKNOWN_ERROR` (HTTP 500 — status unknown, must poll check-status, never assume failed).
- Full list: https://docs.pawapay.io/v2/docs/failure_codes
