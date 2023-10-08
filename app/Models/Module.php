<?php

namespace App\Models;

use App\Models\Lesson;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;
use Dyrynda\Database\Support\CascadeSoftDeletes;


class Module extends Model
{
    use HasFactory;
    use SoftDeletes;
    use CascadeSoftDeletes;

    protected $fillable = [
        'title',
        'order', 
        'course_id'
    ];

    protected $dates = ['deleted_at'];

    protected $cascadeDeletes = ['lessons'];


    // Relação com o curso ao qual o módulo pertence
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    // Relação com as aulas do módulo
    public function lessons()
    {
        return $this->hasMany(Lesson::class);
    }
}
