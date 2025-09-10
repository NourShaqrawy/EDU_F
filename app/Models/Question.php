<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = [
        'video_id',
        'question_text',
        'options',
        'correct_key'
    ];

    protected $casts = [
        'options' => 'array'
    ];
   public function exam()
{
    return $this->belongsTo(Exam::class);
}
public function video()
{
    return $this->belongsTo(Video::class);
}


}
