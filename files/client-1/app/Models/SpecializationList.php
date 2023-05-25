<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpecializationList extends Model
{
    use HasFactory;

    public function Recruiter()
    {
        return $this->belongsTo(Recruiter::class)->withDefault();
    }

    public function Specialization()
    {
        return $this->belongsTo(Specialization::class)->withDefault();
    }
    public function Company()
    {
        return $this->belongsTo(Company::class)->withDefault();
    }
}
