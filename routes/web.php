<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\ModuleController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CourseRatingsController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ConfigsController; 
use App\Http\Controllers\CourseDataController;
use App\Http\Controllers\EmailController;


Route::get('/', [HomeController::class, 'index'])->name('home');

Route::middleware(['auth', 'userVerified'])->group(function () { 
    // Rotas para CRUD de cursos
    Route::get('/courses/create', [CourseController::class, 'create'])->name('courses.create');
    Route::post('/courses', [CourseController::class, 'store'])->name('courses.store');
    Route::get('/courses/{courseSlug}/edit', [CourseController::class, 'edit'])->name('courses.edit');
    Route::put('/courses/{courseSlug}', [CourseController::class, 'update'])->name('courses.update');
    Route::delete('/courses/{courseSlug}', [CourseController::class, 'destroy'])->name('courses.destroy');
    // Rota para o criador do curso dizer que o curso ficou 100% completo
    Route::post('/courses/{course}/complete', [CourseController::class, 'markComplete'])->name('courses.mark-complete');
    // Rota para o criador do curso desdizer que o curso ficou 100% completo (voltar atrás)
    Route::post('/courses/{course}/uncomplete', [CourseController::class, 'unmarkComplete'])->name('courses.unmark-complete');
    // Rota para usuário ver os cursos concluídos por ele
    Route::get('/courses/completed', [CourseController::class, 'CompletedCourses'])->name('courses.completed');
    // rota para criador de curso
    Route::get('/courses/creator', [CourseController::class, 'indexCreator'])->name('courses.creator')->middleware('auth');
    Route::get('/courses/creator/toggle/mode', [CourseController::class, 'toggleMode'])->name('courses.toggleMode')->middleware('auth', 'can:manage-courses');
    // rota para configurações do curso
    Route::get('/course/{course}/config', [CourseController::class, 'config'])->name('course.config');

    // rotas para criador de curso ver dados do seu curso
    Route::get('/course/data/index/{course}', [CourseDataController::class, 'index'])->name('course.data.index');
    Route::get('/course/{course}/data/overview', [CourseDataController::class, 'courseOverview'])->name('course.data.overview');
    Route::get('/course/{course}/data/reviews', [CourseDataController::class, 'courseReviews'])->name('course.data.reviews'); 
    Route::get('/course/{course}/data/progress', [CourseDataController::class, 'studentProgress'])->name('course.data.progress');
    Route::get('/course/{course}/data/performance', [CourseDataController::class, 'coursePerformance'])->name('course.data.performance');
    Route::post('/course/{course}/data/performance', [CourseDataController::class, 'coursePerformance'])->name('course.data.performance.form');  
    
    // Rotas para CRUD de módulos
    Route::get('/{courseSlug}/modules', [ModuleController::class, 'index'])->name('modules.index');
    Route::get('/{courseSlug}/modules/create', [ModuleController::class, 'create'])->name('modules.create');
    Route::post('/{courseSlug}/modules', [ModuleController::class, 'store'])->name('modules.store');
    Route::get('/{courseSlug}/modules/{moduleSlug}', [ModuleController::class, 'show'])->name('modules.show');
    Route::get('/{courseSlug}/modules/{moduleSlug}/edit', [ModuleController::class, 'edit'])->name('modules.edit');
    Route::put('/{courseSlug}/modules/{moduleSlug}', [ModuleController::class, 'update'])->name('modules.update');
    Route::delete('/{courseSlug}/modules/{moduleSlug}', [ModuleController::class, 'destroy'])->name('modules.destroy');

    // Rotas para CRUD de aulas (lessons)
    Route::get('modules/{courseSlug}/lessons', [LessonController::class, 'index'])->name('lessons.index');
    Route::get('modules/{moduleSlug}/lessons/create', [LessonController::class, 'create'])->name('lessons.create');
    Route::post('modules/{moduleSlug}/lessons', [LessonController::class, 'store'])->name('lessons.store');
    Route::get('modules/{moduleSlug}/lessons/{lessonSlug}/edit', [LessonController::class, 'edit'])->name('lessons.edit');
    Route::put('modules/{moduleSlug}/lessons/{lessonSlug}', [LessonController::class, 'update'])->name('lessons.update');
    Route::delete('modules/{moduleSlug}/lessons/{lessonSlug}', [LessonController::class, 'destroy'])->name('lessons.destroy');
    Route::get('course/{courseSlug}/lessons/{lessonSlug}/watch', [LessonController::class, 'show'])->name('lessons.watch');

    // Rota referente a favoritação
    Route::post('/courses/{courseSlug}/favorite', [FavoriteController::class, 'toggleFavorite'])->name('courses.favorite.toggle');

    // Rota referente a inscrição de um usuário em um curso
    Route::post('/courses/{courseSlug}/subscribe', [SubscriptionController::class, 'subscribe'])->name('courses.subscribe');
    // Rota referente a cancelar inscrição de um usuário em um curso
    Route::delete('/courses/{courseSlug}/unsubscribe', [SubscriptionController::class, 'unsubscribe'])->name('courses.unsubscribe');

    // Rota para denunciar um curso ou aula
    Route::post('/report/{courseSlug}', [ReportController::class, 'store'])->name('report.store');

    // Rota para classificar um curso
    Route::post('/courses/{courseSlug}/rate', [CourseRatingsController::class, 'rateCourse'])->name('courses.rate');

    // rota para marcar aula como concluída
    Route::post('/lesson/done', [LessonController::class, 'markLessonCompleted'])->name('lesson.done');
    // rota para desmarcar aula como concluída
    Route::post('/lesson/unmark', [LessonController::class, 'unmarkLessonCompleted'])->name('lesson.unmark');

    // rota para ver perfil
    Route::get('user/show/{id}', [UserController::class, 'show'])->name('user.show');
    // rota para modificar dados de usuário
    Route::get('user/edit/{id}', [UserController::class, 'edit'])->name('user.edit');
    // rota para modificar
    Route::put('user/update/{user}', [UserController::class, 'update'])->name('user.update');
});

// rotas do administrador
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.dashboard');
    // rotas para denuncias
    Route::get('/admin/reports/table', [ReportController::class, 'reportsTable'])->name('admin.reports');
    Route::get('/admin/reports/slides', [ReportController::class, 'reportsSlides'])->name('admin.reports.slides');
    Route::get('/admin/reports/slide/{report}', [ReportController::class, 'show'])->name('admin.reports.slide');
    // rota que dá acesso as exclusões
    Route::get('/admin/deletes', [AdminController::class, 'deletes'])->name('admin.deletes');
    // rota para ver exclusões (de curso, módulos e aulas)
    Route::get('/admin/registros-excluidos/{tipo}', [AdminController::class, 'registrosExcluidos'])->name('admin.registros.excluidos');
    Route::get('/admin/todos-registros-excluidos', [AdminController::class, 'todosRegistrosExcluidos'])->name('admin.todos.registros.excluidos');
    // rota para restaurar exclusões (de curso, módulos e aulas)
    Route::post('/restore/{id}/{type}', [AdminController::class, 'restore'])->name('admin.restore');
    // rota para excluir permanentemente (de curso, módulos e aulas)
    Route::post('/delete-permanently/{id}/{type}', [AdminController::class, 'deletePermanently'])->name('admin.delete-permanently');

    // rota para admin ver todos os cursos da plataforma
    Route::get('/admin/todos-cursos', [AdminController::class, 'todosCursos'])->name('admin.todos.cursos');

    // rotas referentes a categorias
    Route::get('/admin/categories', [CategoryController::class, 'index'])->name('admin.categories.index');
    Route::get('/admin/categories/create', [CategoryController::class, 'create'])->name('admin.categories.create');
    Route::post('/admin/categories/store', [CategoryController::class, 'store'])->name('admin.categories.store');
    Route::get('/admin/categories/data', [CategoryController::class, 'data'])->name('admin.categories.data');
    Route::post('/admin/categories/data', [CategoryController::class, 'data'])->name('admin.categories.data');
    Route::get('/admin/categories/data/show/{id}', [CategoryController::class, 'show'])->name('admin.categories.data.show');

    // rotas referentes a usuários
    Route::get('/admin/users', [UserController::class, 'index'])->name('admin.user.index');

    // rota para ver página de detalhes de curso de um curso deletado (para denuncias)
    Route::get('/courses/{courseSlug}/deleted', [CourseController::class, 'showDeleted'])->name('courses.show.deleted');
    // rota para ver aula deletada (para denuncias)
    Route::get('course/{courseSlug}/lessons/{lessonSlug}/watch/deleted', [LessonController::class, 'showDeleted'])->name('lessons.watch.deleted');
});

// rotas de login e cadastro
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

// Rotas abertas de cursos
Route::get('/courses/viewer', [CourseController::class, 'indexViewer'])->name('courses.viewer');
Route::get('/courses/{courseSlug}', [CourseController::class, 'show'])->name('courses.show');

// Rota para pesquisa
Route::get('/courses/search', [SearchController::class, 'search'])->name('search');

// rota de configurações
Route::get('/configs', [ConfigsController::class, 'index'])->name('configs');
Route::get('/{locale?}', [ConfigsController::class, 'setLang'])->where('locale', 'en|pt|es')->name('lang');

// rota para verificação de email
Route::get('/verify-email/{token}', [EmailController::class, 'verifyEmail'])->name('verification.verify');