<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class Lesson extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'title', 
        'slug',
        'description', 
        'module_id',
        'video_id',
        'order'
    ];

    protected $dates = ['deleted_at'];

    // Relação com o módulo ao qual a aula pertence
    public function module()
    {
        return $this->belongsTo(Module::class);
    }

    public function video()
    {
        return $this->belongsTo(Video::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'lesson_user', 'lesson_id', 'user_id')
            ->withPivot('completed_at')
            ->withTimestamps();
    }
}
