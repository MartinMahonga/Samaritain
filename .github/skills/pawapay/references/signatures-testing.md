# Signatures (RFC-9421) & Sandbox Testing

## Why

The API token alone secures calls. Optionally, sign financial requests (deposits/payouts/refunds) and verify signed callbacks to add a second layer of security — protects you even if the API token leaks. Based on [RFC-9421](https://datatracker.ietf.org/doc/rfc9421/) HTTP Message Signatures.

Enable in the pawaPay Dashboard: upload your public key (for signing your requests) and enable signed callbacks (to receive signed responses from pawaPay).

## Signing outgoing financial requests

1. Hash the JSON body (SHA-256 or SHA-512) → `Content-Digest` header.
2. Build a signature base from these components, in order:
   - `@method`, `@authority`, `@path` (derived from the request)
   - `signature-date`, `content-digest`, `content-type` headers
3. Sign the signature base with your private key using one of: `rsa-pss-sha512`, `ecdsa-p256-sha256`, `rsa-v1_5-sha256`, `ecdsa-p384-sha384`.
4. Attach `Signature` and `Signature-Input` headers (include `alg`, `created`, `expires`, `keyid`).

Reference Node implementation from pawaPay: https://github.com/PawaPay/signatures-node-example. For PHP/Laravel there's no official pawaPay library — implement RFC-9421 signing manually (a `http-message-signatures`-style package or hand-rolled OpenSSL signing) or keep signatures disabled and rely on the bearer token if the project doesn't need this extra layer yet. Flag this trade-off to the user rather than skipping it silently when they explicitly ask for "sécurisé"/production-hardened callbacks.

## Verifying incoming callbacks

Callbacks include `Signature`, `Signature-Input`, `Signature-Date`, `Content-Type`, `Content-Digest` headers.

1. Recompute the content digest from the raw request body and compare to `Content-Digest`.
2. Rebuild the signature base from the components listed in `Signature-Input`.
3. Fetch pawaPay's current public key from `GET /v2/public-keys` (cache it, keys rotate rarely but check `keyid`).
4. Verify the `Signature` against the signature base using the public key.

Only trust the callback payload if both checks pass. If verification isn't implemented yet, at minimum: whitelist pawaPay's platform IP addresses at the network/firewall level for the callback route (available from pawaPay support/status page), and treat callback data as informational, always confirming state-changing outcomes via `check-status` before anything destructive.

---

## Sandbox testing

Sandbox is fully isolated — no real money, no real PIN prompt (auth step is skipped automatically). Special MSISDNs simulate outcomes.

Base URL: `https://api.sandbox.pawapay.io`. Sandbox token is separate from production — generate from the sandbox Dashboard.

### Republic of the Congo (COG) — relevant for Brazzaville-based apps

**Airtel (`AIRTEL_COG`)**

| Operation | MSISDN | Result | failureCode |
|---|---|---|---|
| Deposit | 242053456039 | FAILED | PAYMENT_NOT_APPROVED |
| Deposit | 242053456049 | FAILED | INSUFFICIENT_BALANCE |
| Deposit | 242053456069 | FAILED | UNSPECIFIED_FAILURE |
| Deposit | 242053456129 | SUBMITTED | – |
| Deposit | 242053456789 | COMPLETED | – |
| Payout | 242053456089 | FAILED | RECIPIENT_NOT_FOUND |
| Payout | 242053456119 | FAILED | UNSPECIFIED_FAILURE |
| Payout | 242053456129 | SUBMITTED | – |
| Payout | 242053456789 | COMPLETED | – |

**MTN (`MTN_MOMO_COG`)**

| Operation | MSISDN | Result | failureCode |
|---|---|---|---|
| Deposit | 242063456029 | FAILED | PAYER_NOT_FOUND |
| Deposit | 242063456039 | FAILED | PAYMENT_NOT_APPROVED |
| Deposit | 242063456049 | FAILED | INSUFFICIENT_BALANCE |
| Deposit | 242063456069 | FAILED | UNSPECIFIED_FAILURE |
| Deposit | 242063456129 | SUBMITTED | – |
| Deposit | 242063456789 | COMPLETED | – |
| Payout | 242063456089 | FAILED | RECIPIENT_NOT_FOUND |
| Payout | 242063456119 | FAILED | UNSPECIFIED_FAILURE |
| Payout | 242063456129 | SUBMITTED | – |
| Payout | 242063456789 | COMPLETED | – |

### Other countries

pawaPay provides equivalent test-number tables for every supported market (Benin, Burkina Faso, Cameroon, Côte d'Ivoire, DRC, Ethiopia, Gabon, Ghana, Kenya, Lesotho, Malawi, Mozambique, Nigeria, Rwanda, Senegal, Sierra Leone, Tanzania, Uganda, Zambia). Full tables: https://docs.pawapay.io/v2/docs/test_numbers — fetch this page if the app needs to support a market other than COG.

### Testing checklist

- [ ] `COMPLETED` deposit → local row updates to `COMPLETED`, callback received and processed idempotently (calling the callback handler twice shouldn't double-apply side effects).
- [ ] `FAILED` deposit → `failureReason` stored, user sees a retry-friendly message (not the raw `failureMessage`, which is meant for support/ops).
- [ ] `SUBMITTED`/`PROCESSING` → UI correctly shows a waiting state, not stuck spinner forever (test the reconciliation job by *not* configuring a callback URL and confirming polling still resolves it).
- [ ] Duplicate `depositId` reuse → confirm `DUPLICATE_IGNORED` is handled without crashing.
- [ ] Payout `COMPLETED`/`FAILED` for both Airtel and MTN COG numbers above.
- [ ] Refund of a `COMPLETED` sandbox deposit — full and partial.
