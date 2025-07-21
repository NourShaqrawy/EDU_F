<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseImage extends Model
{
    protected $table = 'courses'; // نتعامل مباشرة مع جدول الدورات
    protected $primaryKey = 'id';
    protected $fillable = ['image_data'];

    public $timestamps = true;
}
