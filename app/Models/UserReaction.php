<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserReaction extends Model
{
    protected $table = 'user_reactions';
    protected $with = 'reactionType';
    protected $fillable = [
        'user_id',
        'comment_id',
        'reaction_type_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function comment()
    {
        return $this->belongsTo(UserComment::class, 'comment_id');
    }

    public function reactionType()
    {
        return $this->belongsTo(Reaction::class, 'reaction_type_id');
    }
}
