<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChatMessage extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'chat_id',
        'user_id',
        'message',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

}
