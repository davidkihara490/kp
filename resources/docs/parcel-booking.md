# Parcel booking API

Send `Accept: application/json` and `Authorization: Bearer <token>` with every request, including image downloads. Paths below include Laravel's default API prefix. Use the numeric parcel database ID in URLs. Display the separate `parcel_id` tracking number to the customer.

## Choose the route, items and courier

| Method and URL | Request | Success |
|---|---|---|
| GET /api/parcel-locations | No body | 200. `data[].towns[].points[]` contains stations grouped by county and town. Choose an origin station belonging to the signed-in PUDO partner. |
| GET /api/parcel-catalog | No body | 200. `data[].subcategories[].items[]` contains active bookable items. Use the item's ID for rates and booking. |
| POST /api/transport-partners/rates | JSON with the two town IDs and item rows, as below | 200. Each entry in `data` has id, name, logo, currency, total and rates. Empty array if no eligible courier prices every selected item. |

```json
{
  "sender_town_id": 1, "receiver_town_id": 6,
  "items": [
    {"item_id": 7, "declared_value": 2000, "insurance_required": true},
    {"item_id": 9, "declared_value": 400, "insurance_required": false}
  ]
}
```

Each rate contains item_id, source_zone_id, destination_zone_id and cost. Show the calculated KES total on the review screen. The booking endpoint recalculates the quote using the selected transport partner.

## Create a booking with photos

**POST /api/parcels**. Use multipart/form-data for files. Entries are zero-indexed. Append each file to its item's own images array. Each item accepts zero to five JPEG, PNG or WebP images of up to 5 MiB each. Send image_count per row to detect uploads truncated by the web server or PHP. Omit images for a row without photos. For a booking without photos, application/json is also supported.

Generate a UUID request_id for each new booking and retain it when retrying the same submission. Keep field values, file contents and item order identical. Optional item fields include declared_value, insurance_required, payment_on_delivery and special_notes. The package types are regular, fragile, perishable, valuable, hazardous and oversized. For multipart boolean fields use 1 or 0. Sender and receiver email and address are optional.

```bash
curl "$BASE_URL/api/parcels" \
  -H "Accept: application/json" -H "Authorization: Bearer $TOKEN" \
  -F 'request_id=6efa2ab0-f795-47a8-876c-3a76afd2b80f' \
  -F 'sender_town_id=1' -F 'receiver_town_id=6' -F 'transport_partner_id=12' \
  -F 'sender_pick_up_drop_off_point_id=3' -F 'delivery_pick_up_drop_off_point_id=8' \
  -F 'sender_name=Amina Maina' -F 'sender_phone=+254700000001' \
  -F 'receiver_name=John Otieno' -F 'receiver_phone=+254700000002' \
  -F 'items[0][item_id]=7' -F 'items[0][package_type]=fragile' \
  -F 'items[0][content_description]=Boxed cups' -F 'items[0][declared_value]=2000' \
  -F 'items[0][insurance_required]=1' -F 'items[0][image_count]=2' \
  -F 'items[0][images][]=@cups-front.jpg' -F 'items[0][images][]=@cups-top.webp' \
  -F 'items[1][item_id]=9' -F 'items[1][package_type]=regular' \
  -F 'items[1][content_description]=Books' -F 'items[1][image_count]=1' \
  -F 'items[1][images][]=@books.png'
```

Example success excerpt (HTTP 201). Each item creates a separate parcel with shared sender and receiver contacts. Direct partner bookings begin booked with pending M-Pesa payment. Replaying the same request ID and payload returns HTTP 200 with the existing parcels. Changed content under the same request ID returns 409.

```json
{"data":{"parcels":[
  {"id":101,"parcel_id":"KP20261125A10001","current_status":"booked","images":[
    {"position":1,"url":"https://example.test/api/parcels/101/images/1"},
    {"position":2,"url":"https://example.test/api/parcels/101/images/2"}]},
  {"id":102,"parcel_id":"KP20261125A10002","current_status":"booked","images":[
    {"position":1,"url":"https://example.test/api/parcels/102/images/1"}]}
]}}
```

## List, view and hand over parcels

| Method and URL | Fields | Result |
|---|---|---|
| GET /api/parcels | Optional query parameters search (tracking number), direction (incoming/outgoing by PUDO ownership), status, payment_status, from, to (YYYY-MM-DD), page, per_page (1–100) | 200. Scoped data array, links and meta. |
| GET /api/parcels/{id} | None | 200. One parcel in data with contact details, payment amounts, route IDs and images. |
| GET /api/parcels/{id}/timeline | None | 200. Chronological data array of status ID, status, current_location station ID, driver_id and created_at. |
| GET /api/parcels/{id}/images/{position} | None | 200. Private image bytes. Position starts at 1. Pass the authorization header from Flutter's image loader. |
| POST /api/parcels/{id}/accept | None | Origin PUDO accepts a paid, created web booking. Status becomes booked. |
| POST /api/parcels/{id}/assign-driver | driver_id and optional vehicle_id | Transport partner assigns its active driver. Status becomes assigned. Returned data includes driver_code for the dispatcher to relay privately to the driver. |
| POST /api/parcels/{id}/release-to-driver | driver_code (six-character string, preserve leading zeros) | Dispatch station verifies code. Status becomes in_transit. |
| POST /api/parcels/{id}/receive-from-driver | None | Receiving station confirms arrival. A warehouse first leg reaches warehouse. At final arrival the status becomes arrived_at_destination and a pickup SMS is queued. |
| POST /api/parcels/{id}/confirm-pickup | pickup_code, pickup_person_type (owner/other), confirm_terms (true). For other also send picker_name, picker_phone, picker_id_number. Optional picker_relationship and picker_reason. | Destination station verifies the unexpired code and records pickup. Status becomes picked. |

Handover POSTs return 200 with the updated parcel in data. Each action checks the current state under a row lock. After an interrupted handover request, refresh the parcel detail and timeline before retrying. A warehouse dispatch can start from warehouse status using assign-driver again.

**Errors.** 401 for missing/invalid authentication, 403 for an unauthorized actor or station, 404 for an inaccessible parcel or missing image, 409 for a conflicting transition or request ID, 422 for invalid fields and 429 for too many verification attempts. For example, six files on the first item produce a validation error keyed by `items.0.images`. Validation responses use Laravel's message and errors fields.
