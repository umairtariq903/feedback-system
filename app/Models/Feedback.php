<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    use HasFactory;

    protected $table = "feedbacks";


    function users()
    {
        return $this->belongsTo(User::class, "user");
    }

    function votes()
    {
        return $this->hasMany(Vote::class, "feedback");
    }

    function comment()
    {
        return $this->hasMany(Comment::class, "feedback");
    }
}
