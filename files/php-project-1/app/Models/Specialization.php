<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Specialization extends Model
{
    use HasFactory;

    public function SpecializationLists()
    {
        return $this->hasMany(SpecializationList::class);
    }

    public function User()
    {
        return $this->hasMany(User::class)->withDefault();
    }

}
