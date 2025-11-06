<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course_certificates extends Model
{
    public function user()
{
    return $this->belongsTo(User::class);
}

public function course()
{
    return $this->belongsTo(Course::class);
}

}
