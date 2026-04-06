<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rooms extends Model
{
    //
    protected $fillable = [
        'roomName',
        'roomType',
        'status',
    
    ];

    public function schedules()
    {
        return $this->hasMany(Schedule::class, 'room_id');
    }

    public function userRequests()
    {
        return $this->hasMany(UserRequest::class, 'room_id');
    }
}
