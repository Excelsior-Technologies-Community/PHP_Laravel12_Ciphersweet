<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    protected $table = 'audit_logs';

    protected $fillable = [
        'user_id', 'secure_contact_id', 'action', 'ip_address'
    ];

    public function contact()
    {
        return $this->belongsTo(SecureContact::class, 'secure_contact_id');
    }
}