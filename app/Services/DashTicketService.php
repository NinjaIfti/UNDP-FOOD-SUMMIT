<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DashTicketService
{
    protected string $baseUrl;

    protected ?string $apiKey;

    protected string $tag = 'from_UNDP';

    public function __construct()
    {
        $this->baseUrl = rtrim(config('services.dashticket.url', 'https://api.dashbhutan.com'), '/');
        $this->apiKey = config('services.dashticket.api_key');
    }

    public function isConfigured(): bool
    {
        return ! empty($this->apiKey);
    }

    /**
     * Register a user with DashTicket after summit registration.
     *
     * Returns true on success (201/200), false on failure. Logs errors.
     */
    public function registerUser(array $registration): bool
    {
        if (! $this->isConfigured()) {
            Log::warning('DashTicket: API key not configured, skipping user registration');

            return false;
        }

        $price = PackageService::getPriceForTier($registration['price_tier'] ?? null);
        $customFlexibleAmount = $registration['flexible_support_amount'] ?? null;
        $flexibleTierIds = ['flexible', 'flexible_any', 'flexible_hardcoded'];
        $isFlexibleTier = in_array((string) ($registration['price_tier'] ?? ''), $flexibleTierIds, true);
        if ($isFlexibleTier && $customFlexibleAmount !== null && $customFlexibleAmount !== '') {
            $custom = (float) $customFlexibleAmount;
            if ($custom >= 970) {
                $price = $custom;
            }
        }
        if ($price === null) {
            Log::warning('DashTicket: Could not resolve price for tier', ['price_tier' => $registration['price_tier'] ?? null]);

            return false;
        }

        $payload = [
            'email' => strtolower(trim($registration['email'] ?? '')),
            'name' => trim(($registration['first_name'] ?? '') . ' ' . ($registration['last_name'] ?? '')),
            'password' => $this->generateSecurePassword(),
            'tag' => $this->tag,
            'price' => (float) $price,
            'phone' => trim($registration['phone'] ?? '') ?: null,
            'nationality' => $this->countryToIso($registration['country'] ?? null),
        ];

        $payload = array_filter($payload, fn ($v) => $v !== null && $v !== '');

        $url = $this->baseUrl . '/api/v1/integrations/register-user';

        try {
            $response = Http::timeout(15)
                ->withHeaders([
                    'X-API-Key' => $this->apiKey,
                    'Content-Type' => 'application/json',
                ])
                ->post($url, $payload);

            if ($response->successful()) {
                Log::info('DashTicket: User registered', [
                    'email' => $payload['email'],
                    'status' => $response->status(),
                ]);

                return true;
            }

            Log::warning('DashTicket: Registration failed', [
                'email' => $payload['email'],
                'status' => $response->status(),
                'body' => $response->json(),
            ]);

            return false;
        } catch (\Throwable $e) {
            Log::error('DashTicket: Registration exception', [
                'email' => $payload['email'] ?? null,
                'message' => $e->getMessage(),
            ]);

            return false;
        }
    }

    /**
     * Check ticket purchase status for a user by email.
     *
     * Returns an array on success:
     * [
     *     'email' => string,
     *     'ticket_purchased' => bool,
     *     'found' => bool, // false when API returns 404
     * ]
     *
     * Returns null on configuration or transport errors.
     */
    public function getTicketStatus(string $email): ?array
    {
        if (! $this->isConfigured()) {
            Log::warning('DashTicket: API key not configured, skipping ticket status check');

            return null;
        }

        $normalizedEmail = strtolower(trim($email));
        if ($normalizedEmail === '') {
            Log::warning('DashTicket: Empty email provided for ticket status check');

            return null;
        }

        $url = $this->baseUrl . '/api/v1/integrations/ticket-status';

        try {
            $response = Http::timeout(10)
                ->withHeaders([
                    'X-API-Key' => $this->apiKey,
                    'Content-Type' => 'application/json',
                ])
                ->get($url, ['email' => $normalizedEmail]);

            if ($response->status() === 200) {
                $data = $response->json() ?? [];

                return [
                    'email' => $data['email'] ?? $normalizedEmail,
                    'ticket_purchased' => (bool) ($data['ticket_purchased'] ?? false),
                    'found' => true,
                ];
            }

            if ($response->status() === 404) {
                Log::info('DashTicket: Ticket status user not found', [
                    'email' => $normalizedEmail,
                ]);

                return [
                    'email' => $normalizedEmail,
                    'ticket_purchased' => false,
                    'found' => false,
                ];
            }

            Log::warning('DashTicket: Ticket status check failed', [
                'email' => $normalizedEmail,
                'status' => $response->status(),
                'body' => $response->json(),
            ]);

            return null;
        } catch (\Throwable $e) {
            Log::error('DashTicket: Ticket status exception', [
                'email' => $normalizedEmail,
                'message' => $e->getMessage(),
            ]);

            return null;
        }
    }

    protected function generateSecurePassword(): string
    {
        $uppercase = 'ABCDEFGHJKLMNPQRSTUVWXYZ';
        $lowercase = 'abcdefghjkmnpqrstuvwxyz';
        $digits = '23456789';
        $special = '!@#$%&*';

        $password = $uppercase[random_int(0, strlen($uppercase) - 1)]
            . $lowercase[random_int(0, strlen($lowercase) - 1)]
            . $digits[random_int(0, strlen($digits) - 1)]
            . $special[random_int(0, strlen($special) - 1)];

        $pool = $uppercase . $lowercase . $digits . $special;
        for ($i = 0; $i < 8; $i++) {
            $password .= $pool[random_int(0, strlen($pool) - 1)];
        }

        return str_shuffle($password);
    }

    protected function countryToIso(?string $country): ?string
    {
        if (empty($country)) {
            return null;
        }

        $country = trim($country);

        if (strlen($country) === 2 && ctype_alpha($country)) {
            return strtoupper($country);
        }

        $map = [
            'Afghanistan' => 'AF', 'Albania' => 'AL', 'Algeria' => 'DZ', 'Andorra' => 'AD', 'Angola' => 'AO',
            'Argentina' => 'AR', 'Armenia' => 'AM', 'Australia' => 'AU', 'Austria' => 'AT', 'Azerbaijan' => 'AZ',
            'Bahamas' => 'BS', 'Bahrain' => 'BH', 'Bangladesh' => 'BD', 'Barbados' => 'BB', 'Belarus' => 'BY',
            'Belgium' => 'BE', 'Belize' => 'BZ', 'Benin' => 'BJ', 'Bhutan' => 'BT', 'Bolivia' => 'BO',
            'Bosnia and Herzegovina' => 'BA', 'Botswana' => 'BW', 'Brazil' => 'BR', 'Brunei' => 'BN',
            'Bulgaria' => 'BG', 'Burkina Faso' => 'BF', 'Burundi' => 'BI', 'Cambodia' => 'KH', 'Cameroon' => 'CM',
            'Canada' => 'CA', 'Central African Republic' => 'CF', 'Chad' => 'TD', 'Chile' => 'CL',
            'China' => 'CN', 'Colombia' => 'CO', 'Comoros' => 'KM', 'Congo' => 'CG', 'Costa Rica' => 'CR',
            'Croatia' => 'HR', 'Cuba' => 'CU', 'Cyprus' => 'CY', 'Czech Republic' => 'CZ', 'Denmark' => 'DK',
            'Djibouti' => 'DJ', 'Dominican Republic' => 'DO', 'DR Congo' => 'CD', 'Ecuador' => 'EC',
            'Egypt' => 'EG', 'El Salvador' => 'SV', 'Equatorial Guinea' => 'GQ', 'Eritrea' => 'ER',
            'Estonia' => 'EE', 'Eswatini' => 'SZ', 'Ethiopia' => 'ET', 'Fiji' => 'FJ', 'Finland' => 'FI',
            'France' => 'FR', 'Gabon' => 'GA', 'Gambia' => 'GM', 'Georgia' => 'GE', 'Germany' => 'DE',
            'Ghana' => 'GH', 'Greece' => 'GR', 'Guatemala' => 'GT', 'Guinea' => 'GN', 'Guinea-Bissau' => 'GW',
            'Guyana' => 'GY', 'Haiti' => 'HT', 'Honduras' => 'HN', 'Hungary' => 'HU', 'Iceland' => 'IS',
            'India' => 'IN', 'Indonesia' => 'ID', 'Iran' => 'IR', 'Iraq' => 'IQ', 'Ireland' => 'IE',
            'Israel' => 'IL', 'Italy' => 'IT', 'Ivory Coast' => 'CI', 'Jamaica' => 'JM', 'Japan' => 'JP',
            'Jordan' => 'JO', 'Kazakhstan' => 'KZ', 'Kenya' => 'KE', 'Kiribati' => 'KI', 'Kuwait' => 'KW',
            'Kyrgyzstan' => 'KG', 'Laos' => 'LA', 'Latvia' => 'LV', 'Lebanon' => 'LB', 'Lesotho' => 'LS',
            'Liberia' => 'LR', 'Libya' => 'LY', 'Liechtenstein' => 'LI', 'Lithuania' => 'LT',
            'Luxembourg' => 'LU', 'Madagascar' => 'MG', 'Malawi' => 'MW', 'Malaysia' => 'MY', 'Maldives' => 'MV',
            'Mali' => 'ML', 'Malta' => 'MT', 'Marshall Islands' => 'MH', 'Mauritania' => 'MR',
            'Mauritius' => 'MU', 'Mexico' => 'MX', 'Micronesia' => 'FM', 'Moldova' => 'MD', 'Monaco' => 'MC',
            'Mongolia' => 'MN', 'Montenegro' => 'ME', 'Morocco' => 'MA', 'Mozambique' => 'MZ', 'Myanmar' => 'MM',
            'Namibia' => 'NA', 'Nauru' => 'NR', 'Nepal' => 'NP', 'Netherlands' => 'NL', 'New Zealand' => 'NZ',
            'Nicaragua' => 'NI', 'Niger' => 'NE', 'Nigeria' => 'NG', 'North Korea' => 'KP',
            'North Macedonia' => 'MK', 'Norway' => 'NO', 'Oman' => 'OM', 'Pakistan' => 'PK', 'Palau' => 'PW',
            'Palestine' => 'PS', 'Panama' => 'PA', 'Papua New Guinea' => 'PG', 'Paraguay' => 'PY',
            'Peru' => 'PE', 'Philippines' => 'PH', 'Poland' => 'PL', 'Portugal' => 'PT', 'Qatar' => 'QA',
            'Romania' => 'RO', 'Russia' => 'RU', 'Rwanda' => 'RW', 'Saint Kitts and Nevis' => 'KN',
            'Saint Lucia' => 'LC', 'Samoa' => 'WS', 'San Marino' => 'SM', 'Saudi Arabia' => 'SA',
            'Senegal' => 'SN', 'Serbia' => 'RS', 'Seychelles' => 'SC', 'Sierra Leone' => 'SL',
            'Singapore' => 'SG', 'Slovakia' => 'SK', 'Slovenia' => 'SI', 'Solomon Islands' => 'SB',
            'Somalia' => 'SO', 'South Africa' => 'ZA', 'South Korea' => 'KR', 'South Sudan' => 'SS',
            'Spain' => 'ES', 'Sri Lanka' => 'LK', 'Sudan' => 'SD', 'Suriname' => 'SR', 'Sweden' => 'SE',
            'Switzerland' => 'CH', 'Syria' => 'SY', 'Taiwan' => 'TW', 'Tajikistan' => 'TJ',
            'Tanzania' => 'TZ', 'Thailand' => 'TH', 'Timor-Leste' => 'TL', 'Togo' => 'TG', 'Tonga' => 'TO',
            'Trinidad and Tobago' => 'TT', 'Tunisia' => 'TN', 'Turkey' => 'TR', 'Turkmenistan' => 'TM',
            'Tuvalu' => 'TV', 'Uganda' => 'UG', 'Ukraine' => 'UA', 'United Arab Emirates' => 'AE',
            'United Kingdom' => 'GB', 'United States' => 'US', 'Uruguay' => 'UY', 'Uzbekistan' => 'UZ',
            'Vanuatu' => 'VU', 'Vatican City' => 'VA', 'Venezuela' => 'VE', 'Vietnam' => 'VN',
            'Yemen' => 'YE', 'Zambia' => 'ZM', 'Zimbabwe' => 'ZW',
        ];

        return $map[$country] ?? null;
    }
}
