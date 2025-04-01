<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Chat extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'person_one',
        'person_two',
    ];

    public function person_one(): HasOne
    {
        return $this->hasOne(User::class,'id', 'person_one');
    }

    public function person_two(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'person_two');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(ChatMessage::class)->orderBy('id','desc');
    }
}
