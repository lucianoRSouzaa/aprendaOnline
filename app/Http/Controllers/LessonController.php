<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\Module;
use App\Models\Video;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\FileController;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

use App\Events\LessonCreated;
use App\Events\LessonDeleted;
use App\Events\CourseViewed;


class LessonController extends Controller
{
    public function index($courseSlug)
    {
        $user = Auth::user();

        $course = Course::where('slug', $courseSlug)
                ->with(['modules' => function ($query) {
                    $query->orderBy('order');
                }, 'modules.lessons' => function ($query) {
                    $query->orderBy('order');
                }])
                ->firstOrFail();

        if ($user && $course->creator->id !== $user->id) {
            event(new CourseViewed($user, $course));
        }

        // Calcula a quantidade total de aulas em todos os módulos
        $qtdLessons = $course->modules->sum(function ($module) {
            return $module->lessons->count();
        });

        // Busca a última aula concluída pelo usuário neste curso
        $lastCompletedLesson = $user->getLastCompletedLessonInCourse($course->id);
        
        // obtém a quantidade de aulas concluídas pelo usuário
        $completedLessonsCount = $user->completedLessonsInCourseByUser($course);

        $perCent = 0;

        if ($qtdLessons > 0) {
            $perCent = round(($completedLessonsCount / $qtdLessons) * 100);
        }

        if ($lastCompletedLesson) {
            // Encontre o próximo módulo do mesmo curso
            $nextLesson = Lesson::where('module_id', $lastCompletedLesson->module_id)
            ->where('id', '>', $lastCompletedLesson->id)
            ->whereNotIn('id', $user->completedLessons->pluck('id'))
            ->orderBy('id')
            ->first();

            if (!$nextLesson) {
                // Se não houver próxima aula no módulo, encontre o próximo módulo
                $nextModule = Module::where('course_id', $lastCompletedLesson->module->course_id)
                    ->where('order', '>', $lastCompletedLesson->module->order)
                    ->orderBy('order')
                    ->first();

                if ($nextModule) {
                    $nextLesson = $nextModule->lessons->first();
                }
            }

            if ($nextLesson) {
                $course = $nextLesson->module->course;
                return redirect()->route('lessons.watch', ['courseSlug' => $course->slug, 'lessonSlug' => $nextLesson->slug]);
            }
        }

        return view('lessons.index', compact('course', 'qtdLessons', 'user', 'completedLessonsCount', 'perCent'));
    }

    public function create($moduleSlug)
    {
        $module = Module::where('slug', $moduleSlug)->firstOrFail();

        return view('lessons.create', compact('module'));
    }

    public function store(Request $request, $moduleSlug)
    {
        $validatedData = $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
            'video' => 'required|mimes:mp4,mov,ogg,mkv,webm'
        ]);

        $module = Module::where('slug', $moduleSlug)->firstOrFail();
        $lesson = new Lesson($validatedData);

        $lastLesson = $module->lessons()->latest('order')->first();
        $order = $lastLesson ? $lastLesson->order + 1 : 1;

        $lesson->title = $request->input('title');
        $lesson->description = $request->input('description');

        $slug = Str::slug($lesson->title);
        $uniqueSlug = $slug;
        $count = 2;
        // Verifica se o slug já existe no banco de dados
        while (Lesson::where('slug', $uniqueSlug)->withTrashed()->exists()) {
            $uniqueSlug = $slug . '-' . $count;
            $count++;
        }

        $lesson->slug = $uniqueSlug;
        $lesson->order = $order;

        // Upload do vídeo para o GoogleDrive
        $fileController = new FileController();
        $videoId = $fileController->upload($request->file('video'));

        $lesson->video_id = $videoId;

        $module->lessons()->save($lesson);

        // despachando o evento que atualiza qtd de aulas do curso
        event(new LessonCreated($lesson));

        return redirect()->route('modules.index', ['courseSlug' => $module->course->slug])->with('success', 'Aula criada com sucesso');
    }

    public function edit($moduleSlug, $lessonSlug)
    {
        $module = Module::where('slug', $moduleSlug)->firstOrFail();
        $lesson = $module->lessons()->where('slug', $lessonSlug)->firstOrFail();

        // pegando o nome do arquivo do BD
        $videoNameBd = $lesson->video->name;
        // pegando posição do primeiro "_" (por conta da date() adicionada ao salvar o registro no BD)
        $pos = strpos($videoNameBd, "_");
        // cortando tudo o que vem antes do primeiro "_" (Para ficar só o nome do arquivo mesmo)
        $videoName = substr($videoNameBd, $pos + 1);

        return view('lessons.edit', compact('module', 'lesson', 'videoName'));
    }

    public function update(Request $request, $moduleSlug, $lessonSlug)
    {
        $validatedData = $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
            'video' => 'mimes:mp4,mov,ogg,mkv,webm'
        ]);

        $module = Module::where('slug', $moduleSlug)->firstOrFail();
        $lesson = $module->lessons()->where('slug', $lessonSlug)->firstOrFail();

        $lesson->fill($validatedData);
    
        $slug = Str::slug($lesson->title);
        $uniqueSlug = $slug;
        $count = 2;

        while (Lesson::where('slug', $uniqueSlug)->where('id', '!=', $lesson->id)->withTrashed()->exists()) {
            $uniqueSlug = $slug . '-' . $count;
            $count++;
        }

        // ==========================================================================================
        //   PRECISA EXCLUIR O VÍDEO DO GOOGLE DRIVE ANTIGO AINDA (NÃO IMPLATDA ESSA FUNCIONALIDADE)
        // ==========================================================================================
        // se foi mudado o vídeo da aula
        if ($request->file('video') != null) {
            // Upload do vídeo para o GoogleDrive
            $fileController = new FileController();
            $videoId = $fileController->upload($request->file('video'));

            $lesson->video_id = $videoId;
        }

        $lesson->slug = $uniqueSlug;
        $lesson->save();

        return redirect()->route('modules.index', ['courseSlug' => $module->course->slug])->with('success', 'Aula editada com sucesso');
    }

    public function destroy(Request $request, $moduleSlug, $lessonSlug)
    {
        $module = Module::where('slug', $moduleSlug)->firstOrFail();
        $lesson = $module->lessons()->where('slug', $lessonSlug)->firstOrFail();

        $password = $request->input('password');
        if (!Hash::check($password, auth()->user()->password)) {
            return redirect()->back()->with('error', 'A senha inserida está incorreta.');
        }

        $video = Video::find($lesson->video_id);

        $lesson->delete();
        $video->delete();

        // despachando o evento que atualiza qtd de aulas do curso
        event(new LessonDeleted($lesson));

        // Atualizando a ordem dos módulos restantes
        $remainingLessons = $module->lessons()->where('order', '>', $lesson->order)->get();
        foreach ($remainingLessons as $remainingLesson) {
            $remainingLesson->order -= 1;
            $remainingLesson->save();
        }

        if (auth()->user()->isAdmin()) {
            return redirect()->back()->with('success', 'Aula excluída com sucesso');
        }
        return redirect()->route('modules.index', ['courseSlug' => $module->course->slug])->with('success', 'Aula excluída com sucesso');
    }

    public function show($courseSlug, $lessonSlug)
    {
        $user = Auth::user();

        $course = Course::where('slug', $courseSlug)
            ->with(['modules' => function ($query) {
                $query->orderBy('order');
            }, 'modules.lessons' => function ($query) {
                $query->orderBy('order');
            }])
            ->firstOrFail();

        // Calcula a quantidade total de aulas em todos os módulos
        $qtdLessons = $course->modules->sum(function ($module) {
            return $module->lessons->count();
        });

        // obtém a quantidade de aulas concluídas pelo usuário
        $completedLessonsCount = $user->completedLessonsInCourseByUser($course);

        $perCent = 0;

        if ($qtdLessons > 0) {
            $perCent = round(($completedLessonsCount / $qtdLessons) * 100);
        }

        // para exibir o vídeo
        $lesson = Lesson::where('slug', $lessonSlug)->firstOrFail();

        return view('lessons.show', compact('course', 'lesson', 'qtdLessons', 'user', 'completedLessonsCount', 'perCent'));
    }

    public function showDeleted($courseSlug, $lessonSlug)
    {
        $user = Auth::user();

        // Obtendo o curso denunciado com base no slug
        $course = Course::where('slug', $courseSlug)
            ->with(['modules' => function ($query) {
                $query->orderBy('order');
            }, 'modules.lessons' => function ($query) {
                $query->orderBy('order');
            }])
            ->firstOrFail();

        // Calcula a quantidade total de aulas em todos os módulos
        $qtdLessons = $course->modules->sum(function ($module) {
            return $module->lessons->count();
        });

        // obtém a quantidade de aulas concluídas pelo usuário
        $completedLessonsCount = $user->completedLessonsInCourseByUser($course);

        $perCent = 0;

        if ($qtdLessons > 0) {
            $perCent = round(($completedLessonsCount / $qtdLessons) * 100);
        }

        // para exibir o vídeo
        $lesson = Lesson::withTrashed()->where('slug', $lessonSlug)->firstOrFail();

        return view('lessons.show', compact('course', 'lesson', 'qtdLessons', 'user', 'completedLessonsCount', 'perCent'));
    }

    public function markLessonCompleted(Request $request)
    {
        $user = Auth::user();
        $lessonId = $request->input('lesson_completed');
        $course = Lesson::find($lessonId)->module->course;

        // Verifica se o usuário ainda não concluiu essa aula
        if (!$user->completedLessons->contains($lessonId) && !$user->isAdmin() && $user->id != $course->creator->id) {
            $user->completedLessons()->attach($lessonId, ['completed_at' => now()]);

            // Verifica se o usuário concluiu todas as aulas do curso
            $completedLessonsCount = $user->completedLessonsInCourseByUser($course);
            
            if ($completedLessonsCount === $course->total_lessons && $course->is_completed === 1) {
                // Verifica se o usuário já concluiu esse curso antes
                if (!$user->completedCourses->contains($course->id)) {
                    // Cria um registro de conclusão de curso
                    $course->completions()->create([
                        'user_id' => $user->id,
                        'completed_at' => now(),
                    ]);

                    return redirect()->route('courses.completed', $course->slug)->with('success', 'Parabéns! Você completou o curso "' . $course->title . '" com sucesso. Continue aprendendo e alcançando novos marcos. Seu esforço e dedicação são verdadeiramente inspiradores!');
                }
            }

            // Encontre a aula atual
            $lesson = Lesson::findOrFail($lessonId);

            // Encontre o próximo módulo do mesmo curso
            $nextLesson = Lesson::where('module_id', $lesson->module_id)
                ->where('id', '>', $lessonId)
                ->whereNotIn('id', $user->completedLessons->pluck('id'))
                ->orderBy('id')
                ->first();

            if (!$nextLesson) {
                // Se não houver próxima aula no módulo, encontre o próximo módulo
                $nextModule = Module::where('course_id', $lesson->module->course_id)
                    ->where('order', '>', $lesson->module->order)
                    ->orderBy('order')
                    ->first();

                if ($nextModule) {
                    $nextLesson = $nextModule->lessons->first();
                }
            }

            if ($nextLesson) {
                $course = $nextLesson->module->course;
                return redirect()->route('lessons.watch', ['courseSlug' => $course->slug, 'lessonSlug' => $nextLesson->slug]);
            }
        }

        return redirect()->route('lessons.index', $course->slug);
    }

    public function unmarkLessonCompleted(Request $request)
    {
        $user = Auth::user();
        $lessonId = $request->input('lesson_uncompleted');

        // retira o registro de aula feita pelo usuário no BD
        $user->completedLessons()->detach($lessonId);

        $lesson = Lesson::findOrFail($lessonId);
        $course = $lesson->module->course;

        return redirect()->route('lessons.watch', ['courseSlug' => $course->slug, 'lessonSlug' => $lesson->slug]);
    }

    public function alterOrder($moduleSlug)
    {
        $module = Module::where('slug', $moduleSlug)->firstOrFail();
        $lessons = $module->lessons()->orderBy('order')->get();

        $moduleTitle = $module->title;

        $course = $module->course;

        return view('lessons.alterOrder', compact('lessons', 'course', 'moduleTitle'));
    }

    public function reorder(Request $request)
    {
        // Recendo dados da nova ordem (input hidden)
        $newOrder = json_decode($request->input('novaOrdem'));

        // Atualize a ordem das aulas no banco de dados
        foreach ($newOrder as $position => $lessonId) {
            Lesson::where('id', $lessonId)->update(['order' => $position + 1]);
        }

        return redirect()->back()->with('success', 'Ordem das aulas atualizada com sucesso');
    }
}
