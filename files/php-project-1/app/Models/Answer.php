<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    use HasFactory;

    public function User()
    {
        return $this->belongsTo(User::class)->withDefault();
    }

    public function Question()
    {
        return $this->belongsTo(Question::class)->withDefault();
    }
}
