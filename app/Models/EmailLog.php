<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailLog extends Model
{
    protected $fillable = [
        'type',
        'recipient_email',
        'summit_registration_id',
    ];

    public function registration()
    {
        return $this->belongsTo(SummitRegistration::class, 'summit_registration_id');
    }
}
