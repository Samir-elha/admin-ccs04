<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'companyName',
        'description',
        'website',

    ];

    public function scopeSearchCompany($query, $search)
    {
        return $query->where('companyName', 'like', '%' . $search . '%');
    }

    public function Users()
    {
        return $this->hasMany(User::class);
    }

    public function Recruiters()
    {
        return $this->hasMany(Recruiter::class);
    }

    public function Specializations()
    {
        return $this->hasMany(SpecializationList::class);
    }

    public function Files()
    {
        return $this->hasMany(File::class);
    }
}
