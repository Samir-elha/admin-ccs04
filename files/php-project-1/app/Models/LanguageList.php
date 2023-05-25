<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LanguageList extends Model
{
    use HasFactory;

    public function Language()
    {
        return $this->belongsTo(Language::class)->withDefault();
    }

    public function Recruiter()
    {
        return $this->belongsTo(Recruiter::class)->withDefault();
    }
}
