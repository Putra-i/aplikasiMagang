<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InternshipApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'company_id', 'custom_company_name', 'custom_company_address',
        'period_start', 'period_end', 'status', 'letter_file',
        'rejection_reason', 'supervisor_id',
    ];

    protected function casts(): array
    {
        return [
            'period_start' => 'date',
            'period_end' => 'date',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function supervisor()
    {
        return $this->belongsTo(User::class, 'supervisor_id');
    }

    public function getCompanyNameAttribute(): string
    {
        return $this->company?->name ?? $this->custom_company_name ?? '-';
    }
}
