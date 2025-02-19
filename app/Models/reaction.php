<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reaction extends Model
{
    //
    protected $table = 'reactions';
    protected $fillable = [
        'react_type',
    ];
}
