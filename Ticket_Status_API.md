# Ticket_Status_API

**Integration Reference · v1**

---

## Endpoint

| Field | Value |
|---|---|
| Method | GET |
| URL | https://api.dashbhutan.com/api/v1/integrations/ticket-status?email=user@example.com |
| Content-Type | application/json |
| Read-only | Yes (no insert / update / delete) |
| Auth Header | X-API-Key: \<YOUR_API_KEY\> |
| Alt Auth Header | Authorization: Bearer \<YOUR_API_KEY\> |

---

## Request

### Required Query Parameter

| Parameter | Type | Notes |
|---|---|---|
| email | string | Trimmed and lowercased before lookup |

### Example Request

```
GET https://api.dashbhutan.com/api/v1/integrations/ticket-status?email=user@example.com
X-API-Key: <YOUR_API_KEY>
```

---

## Response Codes

| Code | Status | Response Body |
|---|---|---|
| 200 | User has a ticket | `{"status":"success","email":"user@example.com","ticket_purchased":true}` |
| 200 | User has no ticket | `{"status":"success","email":"user@example.com","ticket_purchased":false}` |
| 400 | Missing or invalid email | `{"status":"fail","message":"Invalid request"}` |
| 401 | Invalid/missing API key | `{"status":"fail","message":"Invalid API key"}` |
| 404 | User not found | `{"status":"fail","message":"User not found"}` |
| 500 | Server/DB failure | `{"status":"fail","message":"Database error"}` |

---

## Ticket Status Logic

The `ticket_purchased` field is `true` when the user has at least one ticket in any of the following statuses:

- `paid`

Otherwise `ticket_purchased` returns `false`.

---

## Additional Notes

- `email` is trimmed and lowercased before lookup — casing in the query parameter does not matter.
- A `404` means the email address was not found in the system. A `200` with `ticket_purchased: false` means the user exists but has no qualifying ticket.

---

*Confidential — For integration use only.*
