---
name: pawapay-integration
description: Integrate pawaPay (mobile money payments for Sub-Saharan Africa - MTN MoMo, Airtel, Orange, M-Pesa, Wave, etc.) into an application. Use this whenever the user wants to accept mobile money deposits, send payouts/disbursements, process refunds, or set up pawaPay callbacks/webhooks - especially in a Laravel/PHP app. Trigger on mentions of "pawaPay", "paiement mobile money", "dépôt/deposit", "payout", "MTN MoMo", "Airtel Money", "Orange Money" combined with payment integration, even if the user doesn't explicitly say "pawaPay skill". Covers sandbox setup, API tokens, initiate deposit/payout/refund, callback handling, signature verification (RFC-9421), phone number validation, and status reconciliation.
---

# pawaPay Integration

pawaPay is a mobile money payment gateway for Sub-Saharan Africa (MTN MoMo, Airtel Money, Orange Money, M-Pesa, Wave, etc.). The API is **asynchronous**: you initiate a payment, get an `ACCEPTED`/`REJECTED` response immediately, then the **final status** (`COMPLETED`/`FAILED`) arrives later via a **callback**.

Default to Laravel unless the user's stack is clearly something else (their existing projects use Laravel 12/13 + Inertia + Vue). Adapt the same logic to other frameworks if asked.

## Before writing any code

1. Ask/confirm (or infer from context) which operations are needed: **deposits** (collect money), **payouts** (send money), **refunds**, or several of these.
2. Ask/confirm which countries/providers matter. Republic of Congo (Brazzaville) providers are `AIRTEL_COG` and `MTN_MOMO_COG`, currency `XAF`.
3. Check whether the app already has a `.env` / config pattern to follow — match it rather than inventing a new one.

Don't ask more than necessary — if the user just says "intègre pawaPay pour recevoir des paiements", proceed with deposits only, Laravel, and state the assumption.

## Core concepts to always apply

- **Base URLs** differ by environment:
  - Sandbox: `https://api.sandbox.pawapay.io`
  - Production: `https://api.pawapay.io`
- **Auth**: Bearer token in `Authorization` header, generated in the pawaPay Dashboard. Different token per environment. Never hardcode it — always env var.
- **Idempotency**: every deposit/payout/refund needs a client-generated **UUIDv4** id (`depositId`/`payoutId`/`refundId`), generated and **persisted in your DB before calling the API**. Reusing an id returns `DUPLICATE_IGNORED`. This id is the reconciliation anchor if the network call fails.
- **Never mark a payment FAILED just because the HTTP call errored or timed out.** Only trust `NOT_FOUND` from a status-check call for that. See `references/laravel-implementation.md` reconciliation section — this is the single most important correctness rule in the whole integration.
- **Amounts** are strings, not floats (e.g. `"100"`). Some providers don't support decimals — check `decimalsInAmount` from the active-configuration endpoint before rounding/formatting amounts.
- **Phone numbers**: always run through the `/v2/predict-provider` endpoint to normalize to MSISDN format and get the predicted provider — don't hand-roll phone validation/regex.

## Implementation workflow

1. **Config & env** — add `PAWAPAY_API_TOKEN`, `PAWAPAY_BASE_URL` (or sandbox/live toggle), `PAWAPAY_CALLBACK_SECRET`/public key config. See `references/laravel-implementation.md` for the config file.
2. **Service class** — a `PawaPayService` wrapping deposits/payouts/refunds via Laravel's `Http` facade. Full example in `references/laravel-implementation.md`.
3. **Migration** — a `payments` (or similarly named) table storing the UUID, status, amount, currency, provider, raw payload. Never rely only on pawaPay as the source of truth.
4. **Initiate flow** — controller/route that: generates UUID → saves row as `PENDING` → calls pawaPay → updates row based on `ACCEPTED`/`REJECTED`.
5. **Callback route** — a public POST route (CSRF-exempt) that verifies the signature (if enabled) and updates the local row to the final status. Must respond `200` quickly — do heavy work in a queued job.
6. **Reconciliation job** — scheduled command that re-checks any row stuck `PENDING`/`PROCESSING` for >15 min via the status-check endpoint. Non-negotiable for production; without it, missed callbacks silently strand payments.
7. **Sandbox testing** — use the phone numbers in `references/signatures-testing.md` to simulate COMPLETED/FAILED/SUBMITTED without real money.

Read `references/laravel-implementation.md` before writing code — it has copy-pasteable, ready-to-adapt files (config, service, migration, controller, form request, job) rather than snippets to reconstruct from memory.

Read `references/api-reference.md` for exact endpoint paths, request/response payloads for deposits/payouts/refunds/checkouts, and failure/status codes.

Read `references/signatures-testing.md` when the user wants request/callback signing (RFC-9421, optional but recommended extra layer of security) or needs sandbox test phone numbers per country/provider (Republic of Congo included).

## Common pitfalls to avoid

- Don't default-select a provider in a dropdown — mis-selected provider is a top cause of failed payments (pawaPay's own guidance).
- Don't assume decimal amounts work everywhere — check `decimalsInAmount` (`NONE` vs `TWO_PLACES`) via active-configuration.
- Don't skip signature verification silently if the user asks for "sécurisé" / production-grade — mention it as a recommended addition even if not implemented by default.
- Redirection-based providers (Wave in Senegal/Ivory Coast) and preauthorized providers (Orange Burkina Faso) need different flows than the standard PIN-prompt flow — see `references/api-reference.md` if those markets come up. Not relevant for Congo-Brazzaville (Airtel/MTN, standard PIN-prompt auth).
- The Payment Page / Checkouts hosted-widget option exists if the user wants a no-build-required payment UI instead of a custom form — mention it as an alternative when relevant, don't build a custom form and the hosted option both.
