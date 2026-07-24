<?php

namespace App\Models;

use App\Services\AdminPaymentService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SummitRegistration extends Model
{
    protected $table = 'summit_registrations';

    protected $fillable = [
        'first_name', 'middle_name', 'last_name', 'gender', 'email', 'phone', 'country', 'country_of_residence', 'organization', 'organization_type', 'organization_type_other',
        'organization_size', 'participation_budget_support', 'job_title',
        'website', 'passport_number', 'passport_expiry', 'arrival_date', 'departure_date',
        'price_tier', 'price_tier_raw', 'price_tier_name', 'confirm_tier', 'flexible_support_amount', 'admin_override_price', 'subsidized_price_justification',
        'field_visit', 'accessibility_needed', 'accessibility_details',
        'dietary', 'dietary_other', 'heard_from', 'heard_from_other', 'additional_members',
        'cofsa_member', 'cofsa_newsletter', 'emergency_name', 'emergency_phone',
        'emergency_email', 'emergency_relation', 'photo', 'status', 'user_id',
    ];

    protected $casts = [
        'field_visit' => 'boolean',
        'confirm_tier' => 'boolean',
        'flexible_support_amount' => 'decimal:2',
        'admin_override_price' => 'decimal:2',
        'passport_expiry' => 'date',
        'arrival_date' => 'date',
        'departure_date' => 'date',
        'heard_from' => 'array',
        'additional_members' => 'array',
    ];

    public const STATUS_PENDING = 'pending';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_REJECTED = 'rejected';
    public const STATUS_WAITLISTED = 'waitlisted';

    /**
     * Runtime-only flag (never persisted) that an admin action must explicitly set,
     * after user confirmation, to delete a registration that is marked paid.
     */
    public bool $forceDeletePaid = false;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    protected static function boot(): void
    {
        parent::boot();

        // Guard at the ORM level so a paid registration can never be deleted from any
        // code path (controller, tinker, future jobs) without an explicit, confirmed override.
        static::deleting(function (self $registration) {
            $status = app(AdminPaymentService::class)->get($registration->id)['status'] ?? 'pending';
            if ($status === 'paid' && ! $registration->forceDeletePaid) {
                throw new \RuntimeException(
                    "Cannot delete registration #{$registration->id}: it is marked as paid. Update the payment status before deleting."
                );
            }
        });
    }
}
