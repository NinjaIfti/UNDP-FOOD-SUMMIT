# Register_User_API

**Integration Reference · v1**

---

## Endpoint

| Field | Value |
|---|---|
| Method | POST |
| URL | https://api.dashbhutan.com/api/v1/integrations/register-user |
| Content-Type | application/json |
| Auth Header | X-API-Key: 16322867-246e-4675-b2d8-3c988ed7bf36 |
| Alt Auth Header | Authorization: Bearer 16322867-246e-4675-b2d8-3c988ed7bf36 |

---

## Request Body

### Required Fields

| Field | Type | Notes |
|---|---|---|
| email | string | Trimmed and lowercased before processing |
| name | string | Full display name |
| password | string | Min 8 chars; must include uppercase, lowercase, number, and special character |
| tag | string | Use format `from_UNDP` (your organisation identifier in uppercase). Normalized automatically if prefix is missing |
| price | number | Must be `>= 0`. Required in every request |

### Optional Fields

| Field | Type | Notes |
|---|---|---|
| nationality | string | Use ISO country code (e.g. `"BT"`, `"US"`, `"IN"`). Defaults to `"US"` if omitted or empty |
| phone | string | Optional contact number |

### Example Request Body

```json
{
  "email": "user@example.com",
  "name": "Full Name",
  "password": "SecurePass1!",
  "tag": "from_UNDP",
  "price": 349.99,
  "nationality": "BT",
  "phone": "17123456"
}
```

---

## Response Codes

| Code | Status | Response Body |
|---|---|---|
| 201 | New user registered | `{"status":"success","message":"User registered","user_uid":"..."}` |
| 200 | User already exists | `{"status":"success","message":"User already exists; tag added","user_uid":"..."}` |
| 400 | Invalid JSON body | `{"status":"fail","message":"Invalid request body"}` |
| 400 | Missing required fields | `{"status":"fail","message":"email, name, tag, and price are required"}` |
| 400 | Negative price | `{"status":"fail","message":"price must be greater than or equal to 0"}` |
| 400 | Missing password | `{"status":"fail","message":"password is required"}` |
| 400 | Weak password | `{"status":"fail","message":"password must be at least 8 characters long"}` |
| 401 | Invalid/missing API key | `{"status":"fail","message":"Invalid API key"}` |
| 409 | Email conflict | `{"status":"fail","message":"Email already registered"}` |
| 500 | Server/DB failure | `{"status":"fail","message":"Database error / Failed to create user"}` |
| 503 | API not configured | `{"status":"fail","message":"External registration API is not configured"}` |

---

## Idempotency

Submitting the same email address more than once is safe. The API checks for existing users by email (case-insensitive) and will not create duplicate accounts. On a repeat call:

- No new user is created.
- The provided tag is added to the user if not already present.
- A 200 OK response is returned.

---

## Additional Notes

- The `tag` field must follow the format `from_UNDP` — your organisation name in uppercase after the prefix. It will be normalized automatically if the prefix is missing.
- The `price` field is required but is not returned in the success response.
- Password strength rules: minimum 8 characters, at least one uppercase letter, one lowercase letter, one number, and one special character.

---

*Confidential — For integration use only.*
