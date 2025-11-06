<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Exercise extends Model
{
    protected $fillable=['pause_time'];
    protected $casts = [
    'content' => 'array'
];
    public function video()
    {
        return $this->belongsTo(Video::class,'video_id');
    }
    public function submissions()
    {
        return $this->hasMany(Exercise_Submissions::class,'exercise_id');
    }
    public function exam()
{
    return $this->belongsTo(Exam::class);
}

}
