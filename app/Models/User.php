<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles, HasUuids;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'no_telepon',
        'gauth_id',
        'gauth_type',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function jabatan(): BelongsToMany
    {
        return $this->belongsToMany(Jabatan::class, 'jabatan_user', 'user_id', 'jabatan_id')
            ->withPivot('prodi_id', 'fakultas_id', 'unit_id')->withTimestamps();
    }

    public function fakultas(): BelongsToMany
    {
        return $this->belongsToMany(Fakultas::class, 'jabatan_user',  'user_id', 'fakultas_id')
            ->withPivot('jabatan_id')->withTimestamps();
    }
    public function prodi(): BelongsToMany
    {
        return $this->belongsToMany(Prodi::class, 'jabatan_user',  'user_id', 'prodi_id')
            ->withPivot('jabatan_id')->withTimestamps();
    }
    public function unit(): BelongsToMany
    {
        return $this->belongsToMany(Unit::class, 'jabatan_user',  'user_id', 'unit_id')
            ->withPivot('jabatan_id')->withTimestamps();
    }

    public function auditee(): HasOne
    {
        return $this->hasOne(Auditee::class, 'user_id', 'id');
    }

    public function auditor(): HasOne
    {
        return $this->hasOne(Auditor::class, 'user_id', 'id');
    }
}
