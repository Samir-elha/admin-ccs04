<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use Livewire\Component;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'firstName',
        'lastName',
        'email',
        'active',
        'password',
        'type_id',
        'rNumber',
        'emailNotification',
        'specialization_id',
        'company_id',


    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    public function scopeSearchName($query, $search)
    {
        return $query->where('firstName', 'like', '%' . $search . '%')
            ->orWhere('lastName', 'like', '%' . $search . '%');
    }

    public function Type()
    {
        return $this->belongsTo(Type::class)->withDefault();
    }

    public function Specialization()
    {
        return $this->belongsTo(Specialization::class)->withDefault();
    }

    public function Appointments()
    {
        return $this->hasMany(Appointment::class);
    }



    public function files()
    {
        return $this->hasMany(File::class);
    }

    public function announcements()
    {
        return $this->hasMany(Announcement::class);
    }

    public function studentEdition()
    {
        return $this->hasMany(StudentEdition::class);
    }

    public function answers()
    {
        return $this->hasMany(Answer::class);
    }
    public function Company()
    {
        return $this->belongsTo(Company::class)->withDefault();
    }
}
