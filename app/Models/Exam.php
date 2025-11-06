<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    protected $fillable = [
    'video_id',
    'title',
    'description',
    'duration_minutes',
    'passing_score'
];

 
    public function video() {
    return $this->belongsTo(Video::class);
}
public function questions()
{
    return $this->hasMany(Question::class);
}


}
