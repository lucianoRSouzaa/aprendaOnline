<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use App\Models\User;
use App\Models\Report;
use App\Models\Course;
use App\Models\Module;
use App\Models\Lesson;
use App\Models\Login;

use App\Http\Controllers\FileController;

use App\Events\CourseDeleted;
use App\Events\RestoreOrderEvent;

use Carbon\Carbon;


class AdminController extends Controller
{
    public function index(Request $request)
    {
        $qtdDenuncia = Report::where('status', 'pendente')->count();
        $qtdTotalExcluido = Course::onlyTrashed()->count() + Module::onlyTrashed()->count() + Lesson::onlyTrashed()->count();
        $qtdCursos = Course::withTrashed()->count();
        $qtdUsers = User::all()->count();

        // gráfico de login diário
        $period = null;
        $countDate = null;
        $titleLine = null;

        $period2 = null;
        $countDate2 = null;
        $titleLine2 = null;

        $periods = [
            'hours_24' => 'nas últimas 24 horas',
            'days_5' => 'nos últimos 5 dias',
            'days_14' => 'nos últimos 14 dias',
            'months_3' => 'nos últimos 4 meses',
            'months_11' => 'nos últimos 12 meses',
        ];

        if ($request->has('period') && $request->input('period') != null) {

            $period = $request->input('period');

            if ($period == 'hours_24'){
                $startDate = now()->subHours(24);
                $endDate = now();
            }
            elseif ($period == 'days_14') {
                $startDate = now()->subDays(14);
                $endDate = now();
            }
            else{
                $startDate = now()->subMonth(preg_replace("/[^0-9]/", "", $period));
                $endDate = now()->endOfMonth();
            }

            $titleLine = $periods[$period];

            $logins = Login::whereBetween('login_time', [$startDate, $endDate])->get();

            $countDate = $this->calculateCounts($logins, $startDate, $endDate, $period);
        }

        if ($request->has('period2') && $request->input('period2') != null) {

            $period2 = $request->input('period2');

            if ($period2 == 'days_5' || $period2 == 'days_14'){
                $startDate2 = now()->subDays(preg_replace("/[^0-9]/", "", $period2));
                $endDate2 = now();
            }
            else {
                $startDate2 = now()->subMonth(preg_replace("/[^0-9]/", "", $period2));
                $endDate2 = now()->endOfMonth();
            }

            $titleLine2 = $periods[$period2];

            $registers = User::whereBetween('created_at', [$startDate2, $endDate2])->get();

            $countDate2 = $this->calculateCounts($registers, $startDate2, $endDate2, $period2);
        }

        return view('admin.dashboard', compact('qtdDenuncia', 'qtdTotalExcluido', 'qtdCursos', 'qtdUsers', 'period', 'countDate', 'titleLine', 'period2', 'titleLine2', 'countDate2'));
    }

    private function calculateCounts($data, $startDate, $endDate, $period)
    {
        if ($period === 'months_3' || $period === 'months_11'){
            return $this->calculateMonthlyCounts($data, $startDate, $endDate);
        }
        elseif ($period === 'days_14' || $period == 'days_5') {
            return $this->calculateDailyCounts($data, $startDate, $endDate);
        } 
        else {
            return $this->calculateHoursCounts($data, $startDate, $endDate);
        }
    }

    private function calculateHoursCounts($logins, $startDate, $endDate)
    {
        foreach ($logins as $login) {
            // Obtenha a data/hora da conclusão da lição.
            $loginTime = Carbon::parse($login->login_time);

            // Obtém a hora do dia
            $hourOfDay = $loginTime->format('H') . ':00'; 

            $loginTimes[] = $hourOfDay;
        }

        $hourCounts = array_count_values($loginTimes);

        $totalHours = 24;

        // Inicialize um novo array com valores padrão zero.
        $filledHourCounts = [];
        for ($i = 0; $i < $totalHours; $i++) {
            $j = $i . ':00';
            $filledHourCounts[$j] = 0;
        }

        // Substitua os valores padrão com os valores reais onde estiverem definidos.
        foreach ($hourCounts as $hour => $count) {
            $filledHourCounts[$hour] = $count;
        }

        return $filledHourCounts;
    }

    private function calculateDailyCounts($logins, $startDate, $endDate)
    {
        // Inicialize um array vazio para armazenar as contagens diárias
        $dailyCounts = [];

        // Percorra cada dia no intervalo de datas
        $currentDate = clone $startDate;
        while ($currentDate->lte($endDate)) {
            $formattedDate = $currentDate->format('d/m');
            $dailyCounts[$formattedDate] = 0; // Inicialize com zero inscrições

            // Verifique se há inscrições para este dia
            foreach ($logins as $subscription) {
                if ($subscription->created_at->format('d/m') === $formattedDate) {
                    $dailyCounts[$formattedDate]++;
                }
            }

            // Avance para o próximo dia
            $currentDate->addDay();
        }

        return $dailyCounts;
    }

    private function translateMonth($month)
    {
        $monthTranslations = [
            'January' => 'Janeiro',
            'February' => 'Fevereiro',
            'March' => 'Março',
            'April' => 'Abril',
            'May' => 'Maio',
            'June' => 'Junho',
            'July' => 'Julho',
            'August' => 'Agosto',
            'September' => 'Setembro',
            'October' => 'Outubro',
            'November' => 'Novembro',
            'December' => 'Dezembro',
        ];

        return $monthTranslations[$month];
    }

    private function calculateMonthlyCounts($logins, $startDate, $endDate)
    {
        $monthlyCounts = [];

        // Percorra cada mês no intervalo de datas
        $currentDate = clone $startDate;
        while ($currentDate->lte($endDate)) {
            $startOfMonth = $currentDate->copy()->startOfMonth();
            $endOfMonth = $currentDate->copy()->endOfMonth();
            $formattedMonth = $startOfMonth->format('F'); // Nome do mês
            $translatedMonth = $this->translateMonth($formattedMonth);
        
            // Inicialize com zero inscrições para este mês
            $monthlyCounts[$translatedMonth] = 0;
        
            // Verifique se há inscrições para este mês
            foreach ($logins as $subscription) {
                if ($subscription->created_at->between($startOfMonth, $endOfMonth)) {
                    $monthlyCounts[$translatedMonth]++;
                }
            }
        
            // Avance para o próximo mês
            $currentDate->addMonth();
        }

        return $monthlyCounts;
    }

    public function deletes()
    {
        $qtdCursosExcluido = Course::onlyTrashed()->count();
        $qtdModulosExcluido = Module::onlyTrashed()->count();
        $qtdAulasExcluido = Lesson::onlyTrashed()->count();

        $qtdTotalExcluido = $qtdCursosExcluido + $qtdModulosExcluido + $qtdAulasExcluido;

        return view('admin.deletes.index', compact('qtdCursosExcluido', 'qtdModulosExcluido', 'qtdAulasExcluido', 'qtdTotalExcluido'));
    }

    public function registrosExcluidos(Request $request, $tipo)
    {
        $searchTerm = $request->input('search_term');

        if ($tipo === 'cursos') {

            $registrosQuery = Course::onlyTrashed()->with(['modules' => function ($query) {
                $query->onlyTrashed()->with(['lessons' => function ($query) {
                    $query->onlyTrashed();
                }]);
            }]);

        } elseif ($tipo === 'modulos') {

            $registrosQuery = Module::onlyTrashed()->with(['lessons' => function ($query) {
                $query->onlyTrashed();
            }]);

        } elseif ($tipo === 'aulas') {

            $registrosQuery = Lesson::onlyTrashed()->with(['video' => function ($query) {
                $query->withTrashed();
            }]);

            if ($searchTerm) {
                $registrosQuery->where('title', 'LIKE', '%' . $searchTerm . '%');
            }

            $registros = $registrosQuery->paginate(8)->appends(['search_term' => $searchTerm]);

            $registros->map(function ($registro) {
                $videoNameBd = $registro->video->name;
                $pos = strpos($videoNameBd, "_");
                $registro->video->name = substr($videoNameBd, $pos + 1);

                return $registro;
            });

            return view('admin.deletes.registros_excluidos', compact('registros', 'tipo', 'searchTerm'));
        }

        if ($searchTerm) {
            $registrosQuery->where('title', 'LIKE', '%' . $searchTerm . '%');
        }

        $registros = $registrosQuery->paginate(8)->appends(['search_term' => $searchTerm]);

        return view('admin.deletes.registros_excluidos', compact('registros', 'tipo', 'searchTerm'));
    }

    public function todosRegistrosExcluidos(Request $request)
    {
        $searchTerm = $request->input('search_term');
        $searchType = $request->input('search_type');

        $cursos = Course::onlyTrashed()
                    ->when($searchType === 'course_title', function ($query) use ($searchTerm) {
                        return $query->where('title', 'like', "%$searchTerm%");
                    })
                    ->select('id', 'title')
                    ->get()
                    ->map(function ($curso) {
                        $curso->tipo = 'curso';
                        return $curso;
                    });

        $modulos = Module::onlyTrashed()
                    ->when($searchType === 'module_title', function ($query) use ($searchTerm) {
                        return $query->where('title', 'like', "%$searchTerm%");
                    })
                    ->select('id', 'title')
                    ->get()
                    ->map(function ($modulo) {
                        $modulo->tipo = 'modulo';
                        return $modulo;
                    });

        $aulas = Lesson::onlyTrashed()
                    ->when($searchType === 'lesson_title', function ($query) use ($searchTerm) {
                        return $query->where('title', 'like', "%$searchTerm%");
                    })
                    ->select('id', 'title')
                    ->get()
                    ->map(function ($aula) {
                        $aula->tipo = 'aula';
                        return $aula;
                    });

        if ($searchType === 'course_title') {
            $resultados = $cursos;
        } elseif ($searchType === 'module_title') {
            $resultados = $modulos;
        } elseif ($searchType === 'lesson_title') {
            $resultados = $aulas;
        } else {
            $resultados = $cursos->concat($modulos)->concat($aulas);
        }

        return view('admin.deletes.todos_registros_excluidos', compact('resultados', 'searchTerm'));
    }

    public function todosCursos(Request $request)
    {
        $searchTerm = $request->input('search_term');

        $registrosQuery = Course::with([
            'modules' => function ($query) {
                // carregando os módulos excluídos
                $query->withTrashed(); 
                $query->orderBy('order');
                // e suas aulas
                $query->with(['lessons' => function ($query) {
                    $query->withTrashed(); 
                    $query->orderBy('order');
                }]);
            },
        ])->withTrashed();

        if ($searchTerm) {
            $registrosQuery->where('title', 'LIKE', '%' . $searchTerm . '%');
        }

        $cursos = $registrosQuery->paginate(8)->appends(['search_term' => $searchTerm]);

        return view('admin.todos-cursos', compact('cursos', 'searchTerm'));
    }

    public function restore(Request $request, $id, $type)
    {
        $password = $request->input('password');
        if (!Hash::check($password, auth()->user()->password)) {
            return redirect()->back()->with('errorPassword', 'A senha inserida está incorreta.');
        }

        $course = null;

        switch ($type) {
            case 'curso':
                $course = Course::onlyTrashed()->findOrFail($id);
                
                $course->restore();

                break;
            case 'modulo':

                $modulo = Module::onlyTrashed()->with(['course' => function ($query) {
                    $query->withTrashed();
                }])->findOrFail($id);

                $course = $modulo->course;
                
                if ($course->trashed()) {
                    session()->flash('title', $course->title);
                    session()->flash('type', "course_title");
                    return redirect()->back()->with('error', 'Não é possível restaurar o módulo: "' . $modulo->title . '" porque o curso desse módulo (' . $course->title . ') está excluído, se você deseja restaurar esse módulo, restaure o curso primeiro.');
                }

                $modulo->restore();

                event(new RestoreOrderEvent($modulo));

                break;
            case 'aula':

                $lesson = Lesson::onlyTrashed()->with(['module' => function ($query) {
                    $query->withTrashed();
                }])->findOrFail($id);

                $module = $lesson->module;

                $course = $module->course;

                if ($module->trashed()) {
                    session()->flash('title', $module->title);
                    session()->flash('type', "module_title");
                    return redirect()->back()->with('error', 'Não é possível restaurar a aula : "' . $lesson->title . '" porque o módulo dessa aula (' . $module->title . ') está excluído, se você deseja restaurar essa aula, restaure o módulo primeiro.');
                }

                $video = $lesson->video()->withTrashed()->first();

                $lesson->restore();
                $video->restore();

                event(new RestoreOrderEvent($lesson));

                break;
            default:
                return redirect()->back()->with('error', 'Invalid type provided.');
        }

        if ($course) {
            // despachando o evento que atualiza qtd de aulas do curso
            event(new CourseDeleted($course));
        }

        return redirect()->back()->with('success', ucfirst($type) . ' restaurado com sucesso!');
    }

    public function deletePermanently(Request $request, $id, $type)
    {
        $password = $request->input('password');
        if (!Hash::check($password, auth()->user()->password)) {
            return redirect()->back()->with('errorPassword', 'A senha inserida está incorreta.');
        }

        switch ($type) {
            case 'curso':

                $course = Course::withTrashed()->findOrFail($id);
                $modules = $course->modules()->withTrashed()->get();

                if ($modules->count() > 0) {
                    foreach ($modules as $module) {

                        $lessons = $module->lessons()->withTrashed()->get();
                        if ($lessons->count() > 0) {
                            foreach ($lessons as $lesson) {
                                $video = $lesson->video()->withTrashed()->first();
                            
                                if ($video) {
                                    $videoName = $video->name;
                            
                                    // Upload do vídeo para o GoogleDrive
                                    $fileController = new FileController();
                                    $booleanDeleted = $fileController->delete($videoName);
                            
                                    if ($booleanDeleted) {
                                        $lesson->forceDelete();
                                        $video->forceDelete();
                                    }
                                    else{
                                        return redirect()->back()->with('error', 'Invalid type provided.');
                                    }
                                }
                            }
                        }
                        $module->forceDelete();
                    }
                }
                $course->forceDelete();

                break;
            case 'modulo':

                $module = Module::withTrashed()->findOrFail($id);
                $lessons = $module->lessons()->withTrashed()->get();

                if ($lessons->count() > 0) {
                    foreach ($lessons as $lesson) {
                        $video = $lesson->video()->withTrashed()->first();
                    
                        if ($video) {
                            $videoName = $video->name;
                    
                            // Upload do vídeo para o GoogleDrive
                            $fileController = new FileController();
                            $booleanDeleted = $fileController->delete($videoName);
                    
                            if ($booleanDeleted) {
                                $lesson->forceDelete();
                                $video->forceDelete();
                            }
                            else{
                                return redirect()->back()->with('error', 'Invalid type provided.');
                            }
                        }
                    }
                }

                $module->forceDelete();

                break;
            case 'aula':

                $lesson = Lesson::withTrashed()->with(['video' => function ($query) {
                    $query->withTrashed();
                }])->findOrFail($id);

                $video = $lesson->video;

                $videoName = $video->name;

                // Upload do vídeo para o GoogleDrive
                $fileController = new FileController();
                $booleanDeleted = $fileController->delete($videoName);

                if ($booleanDeleted) {
                    $lesson->forceDelete();
                    $video->forceDelete();
                }
                else{
                    return redirect()->back()->with('error', 'Invalid type provided.');
                }

                break;
            default:
                return redirect()->back()->with('error', 'Invalid type provided.');
        }

        return redirect()->back()->with('success', ucfirst($type) . ' excluído permanetemente!');
    }
}
