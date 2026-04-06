<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserRequest extends Model
{
    protected $fillable = [
        'user_id',
        'room_id',
        'section_id',
        'requested_date',
        'start_time',
        'end_time',
        'reason',
        'status',
        'reviewed_by',
        'reviewed_at',
        'admin_remark',
    ];

    protected function casts(): array
    {
        return [
            'requested_date' => 'date',
            'reviewed_at' => 'datetime',
        ];
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(Rooms::class, 'room_id');
    }

    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class, 'section_id');
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }
}
