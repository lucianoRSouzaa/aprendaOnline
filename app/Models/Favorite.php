<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 
        'course_id'
    ];

    public $timestamps = true;

    // Relação com o usuário que favoritou o curso
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relação com o curso que foi favoritado
    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
