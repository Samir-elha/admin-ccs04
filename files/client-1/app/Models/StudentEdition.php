<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentEdition extends Model
{
    use HasFactory;

    protected $fillable = [
        'edition_id',
        'user_id',
    ];

    public function User()
    {
        return $this->belongsTo(User::class)->withDefault();
    }

    public function Edition()
    {
        return $this->belongsTo(Edition::class)->withDefault();
    }
}
