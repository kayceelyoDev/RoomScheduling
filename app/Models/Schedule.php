<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    //
    protected $fillable = [
        'room_id',
        'section_id',
        'user_id',
        'date',
        'start_time',
        'end_time',
        'is_recurring',
        'repeat_type',
        'repeat_days',
        'repeat_until',
    ];

    // This helper ensures 'repeat_days' is automatically handled as an array
    protected $casts = [
        'repeat_days' => 'array',
        'is_recurring' => 'boolean',
    ];
    public function room()
    {
        return $this->belongsTo(Rooms::class, 'room_id');
    }
    public function section()
    {
        return $this->belongsTo(Section::class, 'section_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
