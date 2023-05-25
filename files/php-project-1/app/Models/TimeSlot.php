<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TimeSlot extends Model
{
    use HasFactory;

    protected $fillable = [
        'startTime',
        'endTime',
        'edition_id',
    ];

    public function Edition(){
        return $this -> belongsTo(Edition::class)->withDefault();
    }

    public function Appointments(){
        return $this -> hasMany(Appointment::class);
    }
}
