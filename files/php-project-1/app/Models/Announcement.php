<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'postDate',
        'isVisible',
        'user_id',
        'edition_id',
        'content'
    ];

    public function User()
    {
        return $this->belongsTo(User::class)->withDefault();
    }

    public function Files()
    {
        return $this->hasMany(File::class);
    }

    public function Edition()
    {
        return $this->belongsTo(Edition::class)->withDefault();
    }
}
