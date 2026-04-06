<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    //
    protected $fillable = [
        'sectionName',
        'year_level',
        'department',
    ];

    public function userProfiles()
    {
        return $this->hasMany(UserProfile::class);
    }
    public function schedules()
    {
       return $this->hasMany(Schedule::class);
    }
}
