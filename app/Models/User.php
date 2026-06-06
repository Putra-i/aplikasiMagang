<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'id_pengguna', 'role', 'jurusan', 'prodi',
        'password',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function internshipApplications()
    {
        return $this->hasMany(InternshipApplication::class);
    }

    public function supervisedStudents()
    {
        return $this->hasMany(InternshipApplication::class, 'supervisor_id');
    }

    public function internshipReports()
    {
        return $this->hasMany(InternshipReport::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class)->orderBy('created_at', 'desc');
    }

    public function unreadNotifications()
    {
        return $this->notifications()->whereNull('read_at');
    }

    public function hasActiveInternship(): bool
    {
        return $this->internshipApplications()
            ->whereIn('status', ['approved_admin', 'approved'])
            ->where('period_end', '>=', now())
            ->exists();
    }

    public function getAssignedSupervisor()
    {
        $app = $this->internshipApplications()
            ->where('status', 'approved')
            ->whereNotNull('supervisor_id')
            ->latest()
            ->first();

        return $app ? $app->supervisor : null;
    }
}
