<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VerificationToken extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'token'];

    // Define a relação com a tabela de usuários
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
