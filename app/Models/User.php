<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Enums\RoleEnum;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'image'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        // 'role' =>  RoleEnum::class,
    ];

    // Define a relação entre o usuário e os tokens de verificação
    public function verificationTokens()
    {
        return $this->hasMany(VerificationToken::class);
    }

    // Relação com os cursos criados pelo usuário
    public function courses()
    {
        return $this->hasMany(Course::class);
    }

    // Relação com os cursos favoritos do usuário
    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    // Verifica se o usuário é um criador de cursos
    public function isCreator()
    {
        return $this->role === 'creator';
    }

    // Verifica se o usuário é apenas um visualizador de cursos
    public function isViewer()
    {
        return $this->role === 'viewer';
    }

    // Verifica se o usuário é administrador
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    // Relação com os cursos em que o usuário está inscrito
    public function subscribedCourses()
    {
        return $this->belongsToMany(Course::class, 'subscriptions')->withTimestamps();
    }

    public function reports()
    {
        return $this->hasMany(Report::class);
    }

    public function courseRatings()
    {
        return $this->hasMany(CourseRating::class);
    }

    public function completedLessons()
    {
        return $this->belongsToMany(Lesson::class, 'lesson_user', 'user_id', 'lesson_id')
            ->withPivot('completed_at')
            ->withTimestamps();
    }

    public function completedLessonsInCourseByUser($course)
    {
        return $this->completedLessons()
            ->whereHas('module', function ($query) use ($course) {
                $query->where('course_id', $course->id);
            })
            ->count();
    }

    public function getLastCompletedLessonInCourse($courseId)
    {
        return $this->completedLessons()
                    ->whereHas('module', function ($query) use ($courseId) {
                        $query->where('course_id', $courseId);
                    })
                    ->orderBy('pivot_created_at', 'desc')
                    ->first();
    }

    public function completions()
    {
        return $this->hasMany(CourseCompletion::class);
    }

    public function completedCourses()
    {
        return $this->belongsToMany(Course::class, 'course_completions', 'user_id', 'course_id')
            ->withTimestamps();
    }
}
