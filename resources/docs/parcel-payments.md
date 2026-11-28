# Parcel payments

Base URL ends in /api/. Send Accept: application/json and the partner's Authorization: Bearer token. Use the numeric parcel ID from the booking response.

| Method | URL after the base | Result |
|---|---|---|
| GET | parcels/{parcel}/payments | Amount due, completed payments, balance and payment history |
| POST | parcels/{parcel}/payments/mpesa | Request a prompt and receive the payment ID |
| GET | payments/{payment} | Current payment status, receipt and failure reason |

### Initiate payment

Postman bulk edit (x-www-form-urlencoded). Use a fresh UUID per intentional payment. Replay the same UUID and fields after a connection timeout. Notes are optional. Amount is a positive whole KES value.

```text
amount:500
phone:0712345678
notes:First instalment
request_id:30d43673-78c8-4a45-a2ac-9eb6aa0f0bbb
```

Example pending response, HTTP 202:

```json
{"data":{"id":18,"parcel_id":42,"method":"mpesa","amount":"500.00","status":"pending","receipt_number":null,"failure_reason":null,"reference_number":null,"notes":"First instalment","created_at":"2026-11-26T10:00:00+03:00"}}
```

Poll payments/18 with backoff. A resolved attempt returns paid or failed. A paid attempt may briefly have a null receipt while the provider callback is in transit. On failure show failure_reason. A separate intentional retry uses a new UUID. A completed summary for the first instalment looks like:

```json
{"data":{"parcel_id":42,"currency":"KES","total_amount":"2525.00","total_paid":"500.00","balance":"2025.00","payment_history":[{"id":18,"parcel_id":42,"method":"mpesa","amount":"500.00","status":"paid","receipt_number":"SAMPLE1234","failure_reason":null,"reference_number":"SAMPLE1234","notes":"First instalment","created_at":"2026-11-26T10:00:00+03:00"}]}}
```

HTTP 401 requires login. HTTP 403 means the account lacks partner access. HTTP 404 covers missing or inaccessible records. HTTP 409 covers a closed parcel, an outstanding prompt, or changed fields for a reused UUID. HTTP 422 returns field validation errors. HTTP 429 limits repeated initiation.
