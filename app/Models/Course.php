<?php

namespace App\Models;

use App\Models\Module;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;
use Dyrynda\Database\Support\CascadeSoftDeletes;


class Course extends Model
{
    use HasFactory;
    use SoftDeletes;
    use CascadeSoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'slug',
        'image',
        'is_completed',
        'total_lessons',
        'category_id',
        'user_id',
        'views'
    ];

    protected $dates = ['deleted_at'];

    protected $cascadeDeletes = ['modules'];


    // Relação com o usuário criador do curso
    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relação com os módulos do curso
    public function modules()
    {
        return $this->hasMany(Module::class);
    }

    // Relação com os usuários que favoritaram o curso
    public function favorites()
    {
        return $this->belongsToMany(User::class, 'favorites');
    }

    // Relação com a categoria do curso
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    public function subscribers()
    {
        return $this->belongsToMany(User::class, 'subscriptions');
    }

    public function isUserSubscribed(User $user)
    {
        return $this->subscribers()->where('user_id', $user->id)->exists();
    }

    public function ratings()
    {
        return $this->hasMany(CourseRating::class);
    }

    public function hasUserRated(User $user)
    {
        return $this->ratings()->where('user_id', $user->id)->exists();
    }

    public function completions()
    {
        return $this->hasMany(CourseCompletion::class);
    }

    public function updateTotalLessonsCount()
    {
        $this->total_lessons = $this->modules->sum(function ($module) {
            return $module->lessons->count();
        });
        $this->save();
    }

    public function views()
    {
        return $this->hasMany(CourseView::class);
    }
}
