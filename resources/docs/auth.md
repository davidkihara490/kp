# PUDO Express authentication API

Use this API to sign in to the mobile app, restore the signed-in profile, and manage passwords and device tokens. The apis use the default Laravel API prefix. Set `{{base_url}}` in Postman to your deployment origin including `/api`, for example `https://dev.karibuparcels.com/api`. Append the paths below directly. The controller namespace includes v1, but the supplied routes have no URL version segment.

Send `Accept: application/json` on every request and `Content-Type: application/json` when sending a JSON body. For protected requests send `Authorization: Bearer {{token}}`, using the entire token returned at login, including its prefix and pipe. Example values are dummy. Messages below assume English.

| Method | Path after base URL | Access | Request body | Success |
|---|---|---|---|---|
| POST | /auth/login | Public | Login identifier(login), password, optional device name | 200, user and device token |
| GET | /auth/me | Bearer | None | 200, user with partner or null |
| POST | /auth/refresh-token | Bearer | Optional device name | 200, replacement token |
| POST | /auth/logout | Bearer | None | 200, current device token revoked |
| POST | /auth/logout-all | Bearer | None | 200, all user API tokens revoked |
| POST | /auth/forgot-password | Public | Email | 200, generic acknowledgement |
| POST | /auth/reset-password | Public | Email, reset token, new password and confirmation | 200, password reset |
| POST | /auth/change-password | Bearer | Current password, new password and confirmation | 200, other API tokens revoked |
| GET | /user | Bearer | None | 200, directly serialized user model |

## Sign in

**POST /auth/login** accepts two required strings, `login` and `password`. Supply either the registered email address or the phone number in its stored format. The server trims the identifier and does no phone-number conversion. Optional `device_name` is a string of up to 255 characters or null. Its default is `api-token`.

```json
{"login":"agent@example.com","password":"ExamplePass123!","device_name":"PUDO Android"}
```

For phone login, replace the value of `login` with a string such as `"+254700000001"`. A successful login returns HTTP 200.

```json
{
  "status": true,
  "message": "Login successful.",
  "data": {
    "user": {
      "id": 17, "first_name": "Amina", "second_name": null, "last_name": "Maina",
      "user_name": "amina", "user_type": "partner", "full_name": "Amina Maina",
      "email": "agent@example.com", "phone_number": "+254700000001",
      "status": "active", "partner": null,
      "created_at": "2026-11-01T08:00:00+03:00", "updated_at": "2026-11-01T08:00:00+03:00"
    },
    "token": "17|example-token", "token_type": "Bearer"
  }
}
```

Save `data.token` in the device's secure credential store. Missing fields or incorrect credentials return 422 (see Errors). Valid credentials for an inactive user return 403.

```json
{"status":false,"message":"Your account is not active. Please contact support."}
```

## Restore the signed-in profile

**GET /auth/me** takes no body. HTTP 200 puts the same user object directly in `data`. Both login and this endpoint load the partner relationship. A user without a linked partner receives `partner: null`.

```json
{"status":true,"data":{"id":17,"first_name":"Amina","second_name":null,"last_name":"Maina","user_name":"amina","user_type":"partner","full_name":"Amina Maina","email":"agent@example.com","phone_number":"+254700000001","status":"active","partner":null,"created_at":"2026-11-01T08:00:00+03:00","updated_at":"2026-11-01T08:00:00+03:00"}}
```

For a linked partner, use `partner.partner_type` to distinguish pickup/drop-off and transport workflows. Read `partner.onboarding_step` and `partner.verification_status` to display onboarding progress and review state. Login checks the user account's active status. Partner approval is a separate value. The resource passes through the stored type, status and step values. Confirm their allowed values against the deployed models before hard-coding navigation.

| Partner object or group | Fields returned |
|---|---|
| Identity and lifecycle | id, partner_type, owner_id, additional_notes, onboarding_step, verification_status, approved_at, created_at, updated_at |
| company | name, registration_number, registration_certificate, kra_pin, pin_certificate |
| points | count, have_phone, have_computer, have_internet, officers_knowledgeable, compliant, compliance_certificate |
| operations | operating_hours, maximum_capacity_per_day, maximum_daily_capacity, maximum_distance, storage_facility_type, security_measures, insurance_coverage, insurance_coverage_amount, years_in_operation, previous_courier_experience, safety_measures, tracking_system, has_computer, has_internet, has_dedicated_allocator, allocator, booking_emails |
| operations.allocator | name and phone when a dedicated allocator exists, otherwise null |
| coverage | service_towns, can_handle_fragile, can_handle_perishable, can_handle_valuables |
| fleet | count, ownership, insured, insurance_certificate, compliant, compliance_certificate, driver_count, drivers_compliant, drivers_certificate, types |
| fleet.types | motorcycles, vans, trucks, refrigerated, other |

Certificate fields contain stored paths. Partner data can vary by partner type and onboarding stage. The controller loads only the partner itself. Treat driver, fleet and town counts as optional. Date fields serialized by the resources use ISO 8601 or null.

**GET /user** is also protected and takes no body. The route returns the authenticated model directly with HTTP 200. Its top-level JSON fields depend on the User model's visible, hidden and appended attributes. Use `/auth/me` for the mobile profile contract.

## Rotate a device token

**POST /auth/refresh-token** accepts an empty body or the following optional field (string, maximum 255 characters, or null).

```json
{"device_name":"PUDO Android"}
```

HTTP 200 replaces the token used for this request. If the name is omitted or null, the device keeps its token name. Store the new token before making another protected request. Coordinate refreshes so only one runs per device. A revoked or expired token requires a fresh login. Invalid device names return 422.

```json
{"status":true,"message":"Token refreshed successfully.","data":{"token":"18|example-replacement","token_type":"Bearer"}}
```

## Sign out

**POST /auth/logout** takes no body. HTTP 200 revokes the calling token. Clear the locally saved token.

```json
{"status":true,"message":"Logged out successfully."}
```

**POST /auth/logout-all** takes no body. HTTP 200 revokes every personal access token for this user, including the caller's.

```json
{"status":true,"message":"Logged out from all devices successfully."}
```

## Recover a forgotten password

**POST /auth/forgot-password** requires the registered email address, including for accounts that sign in by phone.

```json
{"email":"agent@example.com"}
```

A missing or malformed email returns 422. For a valid email format, the normal HTTP 200 acknowledgement is the same for an unknown address, a throttled repeat request, and a reset notification sent to a registered user.

```json
{"status":true,"message":"If that email exists, a password reset link has been sent."}
```

**POST /auth/reset-password** requires all four fields below. Use the token from the emailed reset link. The new password must be a string of at least eight characters and the confirmation must match.

```json
{"email":"agent@example.com","token":"token-from-reset-link","password":"ReplacementPass123!","password_confirmation":"ReplacementPass123!"}
```

HTTP 200 consumes the reset token and revokes all existing API tokens. Return the user to login.

```json
{"status":true,"message":"Password has been reset successfully."}
```

Missing or invalid fields return the validation 422 envelope. An unknown email or an unusable reset token (incorrect, expired or already consumed) returns HTTP 422 with this body.

```json
{"status":false,"message":"Invalid or expired reset token."}
```

## Change a known password

**POST /auth/change-password** requires all three fields below. The new password follows the same length and confirmation rules as reset.

```json
{"current_password":"ExamplePass123!","password":"ReplacementPass123!","password_confirmation":"ReplacementPass123!"}
```

HTTP 200 keeps the calling device's token and revokes the user's other API tokens. Field errors and an incorrect current password return 422.

```json
{"status":true,"message":"Password changed successfully."}
```

## Errors shared across routes

**401 on protected routes.** Missing, revoked or expired credentials normally produce the Laravel middleware response below. Application-level exception customization can change the message or envelope.

```json
{"message":"Unauthenticated."}
```

Token-specific actions (logout, refresh, change password) can also return the controller's 401 if a persisted bearer token is required.

```json
{"status":false,"message":"A valid API bearer token is required."}
```

**422 on routes that validate input.** Read the arrays under `errors` for field-level feedback. Incorrect credentials use the `login` field. An incorrect current password uses `current_password`. A mismatched confirmation normally appears under `password`. Messages can be translated.

```json
{"status":false,"message":"Validation failed.","errors":{"login":["These credentials do not match our records."]}}
```
