<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reaction extends Model
{
    //
    protected $table = 'reactions';
    protected $fillable = [
        'react_type',
        'icon',
    ];

    public function contentReactions()
    {
        return $this->hasMany(ContentReaction::class, 'reaction_type_id');
    }

    public function userReactions()
    {
        return $this->hasMany(UserReaction::class, 'reaction_type_id');
    }
}
