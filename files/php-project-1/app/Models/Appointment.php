<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    public function User()
    {
        return $this->belongsTo(User::class)->withDefault();
    }

    public function TimeSlot()
    {
        return $this->belongsTo(TimeSlot::class)->withDefault();
    }

    public function State()
    {
        return $this->belongsTo(State::class)->withDefault();
    }

    public function Recruiter()
    {
        return $this->belongsTo(Recruiter::class)->withDefault();
    }
}
