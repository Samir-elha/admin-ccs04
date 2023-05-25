<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    use HasFactory;

    protected $fillable = [
        'fileUname',
        'fileName',
        'isFolder',
        'parentFolder',
        'isVisible',
        'user_id',
        'company_id',
        'announcement_id',
        'postDate',
        'edition_id',
    ];

    public function Edition()
    {
        return $this->belongsTo(Edition::class)->withDefault();
    }

    public function Announcement()
    {
        return $this->belongsTo(Announcement::class)->withDefault();
    }

    public function User()
    {
        return $this->belongsTo(User::class)->withDefault();
    }

    public function Company()
    {
        return $this->belongsTo(Company::class)->withDefault();
    }

}
