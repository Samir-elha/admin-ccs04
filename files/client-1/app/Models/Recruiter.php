<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recruiter extends Model
{
    use HasFactory;

    public function Appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function Company()
    {
        return $this->belongsTo(Company::class)->withDefault();
    }

    public function languageLists()
    {
        return $this->hasMany(LanguageList::class);
    }

    public function SpecializationLists()
    {
        return $this->hasMany(SpecializationList::class);
    }
}
