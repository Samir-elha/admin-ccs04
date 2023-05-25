<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Edition extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'date',
        'isActive',
        'numberOfAppointments',
        'deadline',
    ];

    public function scopeActiveId($query)
    {
        // Get the active edition
        $edition = $query->where('isActive', '=', true)->firstOrFail();

        // Return the id of the active edition
        return $edition->id;
    }

    public function StudentEditions()
    {
        return $this->hasMany(StudentEdition::class);
    }

    public function Announcements()
    {
        return $this->hasMany(Announcement::class);
    }

    public function Files()
    {
        return $this->hasMany(File::class);
    }

    public function TimeSlots()
    {
        return $this->hasMany(TimeSlot::class);
    }

    public function Questionaires()
    {
        return $this->hasMany(Questionaire::class);
    }
}
