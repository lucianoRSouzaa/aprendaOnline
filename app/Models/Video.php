<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;


class Video extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'id_google_drive'
    ];

    protected $dates = ['deleted_at'];

    // Relação com a aula à qual o vídeo pertence
    public function lesson()
    {
        return $this->hasOne(Lesson::class);
    }
}

