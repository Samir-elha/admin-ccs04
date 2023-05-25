<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Questionaire extends Model
{
    use HasFactory;

    protected $fillable = [
        'url',
        'edition_id',
    ];

    public function Edition()
    {
        return $this->belongsTo(Edition::class)->withDefault();
    }
}
