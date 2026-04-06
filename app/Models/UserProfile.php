<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    //
    protected $fillable = [
        'user_id',
        'id_number',
        'section_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function section()
    {
       return $this->belongsTo(Section::class);
    }
    
}
