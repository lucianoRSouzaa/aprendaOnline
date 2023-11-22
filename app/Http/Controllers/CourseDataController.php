<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

use App\Models\Course;
use App\Models\Favorite;
use App\Models\LessonUser;
use App\Models\Module;
use App\Models\Lesson;
use App\Models\User;
use App\Models\CourseCompletion;

use Carbon\Carbon;

class CourseDataController extends Controller
{
    public function index($courselug)
    {
        if (Gate::denies('manage-courses')) {
            session()->flash('creator', 'Você precisa ser um criador para ver detalhes de um curso!');
            return redirect()->route('courses.viewer');
        }

        $course = Course::where('slug', $courselug)->firstOrFail();

        return view('courses.data.index', compact('course'));
    }

    public function courseOverview($courselug)
    {
        $course = Course::where('slug', $courselug)->firstOrFail();
        $courseId = $course->id;

        // % de conclusão de aulas de cada módulo
        $modules = Module::where('course_id', $courseId)->get();
        $moduleData = [];
        foreach ($modules as $module) {
            $moduleName = $module->title;

            $lessons = Lesson::where('module_id', $module->id)->get();

            $completedCount = 0;
            $totalUserCount = $course->subscribers->count();

            foreach ($lessons as $lesson) {
                // Conte quantos usuários concluíram esta aula
                $completedAulaCount = LessonUser::where('lesson_id', $lesson->id)
                    ->whereNotNull('completed_at')
                    ->count();

                $completedCount += $completedAulaCount;
            }

            // Calcule a porcentagem média de aulas concluídas para este módulo
            $totalLessons = count($lessons);

            $completedPercentage = ($totalLessons > 0) ? ($completedCount / ($totalLessons * $totalUserCount)) * 100 : 0;
            $completedPercentage = round($completedPercentage, 1);

            // Adicione os dados do módulo ao array de dados do gráfico
            $moduleData[] = [
                'moduleName' => $moduleName,
                'completedPercentage' => $completedPercentage,
            ];
        }

        $totalRatings = $course->ratings->count();
        $averageRating = $course->average_rating;
        $totalLessons = $course->total_lessons;
        $totalCompletions = $course->completions->count();
        $totalSubscriptions = $course->subscriptions->count();
        $totalFavorites = $course->favorites->count();

        if ($totalSubscriptions != 0) {
            $completionRate = ($totalCompletions / $totalSubscriptions) * 100;
        } else {
            $completionRate = 0;
        }

        return view('courses.data.course_overview', compact('course', 'totalRatings', 'averageRating', 'totalLessons', 'totalSubscriptions', 'totalCompletions', 'completionRate', 'totalFavorites', 'moduleData'));
    }

    public function coursePerformance(Request $request, $courselug)
    {
        $course = Course::where('slug', $courselug)->firstOrFail();

        // GRÁFICO DE LINHA (INSCRIÇÕES)
        $titleLine = null;
        $countDate = null;
        $period = null;

        if ($request->has('period')) {
            $period = $request->input('period');

            if ($period == 'last_7_days'){
                $titleLine = '7 dias';
                $startDate = now()->subDays(7);
                $endDate = now();
            }
            elseif ($period == 'last_21_days') {
                $titleLine = '21 dias';
                $startDate = now()->subDays(21);
                $endDate = now();
            }
            elseif ($period == 'last_3_months') {
                $titleLine = '3 meses';
                $startDate = now()->subMonth(2);
                $endDate = now()->endOfMonth();
            }
            elseif ($period == 'last_12_months') {
                $titleLine = '12 meses';
                $startDate = now()->subMonth(11);
                $endDate = now()->endOfMonth();
            }

            $subscriptions = $course->subscriptions()
                ->whereBetween('created_at', [$startDate, $endDate])
                ->get();

            if ($period === 'last_7_days' || $period === 'last_21_days') {
                $countDate = $this->calculateDailyCounts($subscriptions, $startDate, $endDate);
            } else {
                $countDate = $this->calculateMonthlyCounts($subscriptions, $startDate, $endDate);
            }
        }

        // GRÁFICO DE COLUNA (FAVORITOS POR MÊS)
        $favorites = Favorite::where('course_id', $course->id)
            ->whereBetween('created_at', [now()->subMonths(5), now()])
            ->get();

        // Formate os dados para contagem por mês
        $monthlyCounts = $favorites->groupBy(function ($date) {
            return $date->created_at->format('F');
        })->map->count();

        // Preencha os meses ausentes com contagem zero
        $lastFiveMonths = [];
        for ($i = 4; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $formattedDate = $date->format('F');
            $translatedMonth = $this->translateMonth($formattedDate);
            $lastFiveMonths[$translatedMonth] = $monthlyCounts->get($formattedDate, 0);
        }

        // GRÁFICO DE LINHA (ULTIMAS 5 AVALIAÇÕES)
        $lastFiveReviews = null;

        // pegando do primeiro para o ultimo
        $allReviews = $course->ratings()
            ->orderBy('created_at', 'asc')
            ->get();

        $chartReview = [];

        // Inicialize um array para rastrear todas as avaliações até a data atual
        $reviewsToDate = [];

        foreach ($allReviews as $review) {

            $formattedDate = $review->created_at->format('d/m');

            // Adicione esta avaliação ao rastreamento das avaliações até a data atual
            $reviewsToDate[] = $review->rating;
            // Calcule a média de todas as avaliações até a data atual
            $averageRating = count($reviewsToDate) > 0 ? array_sum($reviewsToDate) / count($reviewsToDate) : 0;
            $averageRating = round($averageRating, 2);

            $chartReview[] = [
                'date' => $formattedDate,
                'avaliacao' => $review->rating,
                'media' => $averageRating,
            ];

            // deixando somente os ultimos 5 registros
            $lastFiveReviews = array_slice($chartReview, -5);
        }

        // GRÁFICO DE LINHA (CONCLUSÕES DE AULAS)
        $monthlyData = [];
        $courseId = $course->id;
        
        $completedAulas = LessonUser::whereHas('lesson.module', function ($query) use ($courseId) {
            $query->where('course_id', $courseId);
        })
        ->whereBetween('completed_at', [now()->subMonths(5), now()])
        ->get();
        
        foreach ($completedAulas as $completedAula) {
            // Verifique se completed_at é uma data válida
            if (!empty($completedAula->completed_at)) {
                $completedDate = Carbon::parse($completedAula->completed_at);
        
                $formattedDateLesson = $this->translateMonth($completedDate->format('F'));
        
                // Adicione esta conclusão à contagem mensal
                $monthlyData[$formattedDateLesson] = ($monthlyData[$formattedDateLesson] ?? 0) + 1;
            }
        }

        // Preencha os meses ausentes com contagem zero
        $lastFiveMonthsLessons = [];
        $currentDate = now();
        for ($i = 4; $i >= 0; $i--) {
            $formattedDateLesoonNow = $this->translateMonth($currentDate->format('F'));
            $lastFiveMonthsLessons[$formattedDateLesoonNow] = $monthlyData[$formattedDateLesoonNow] ?? 0;
            $currentDate->subMonth();
        }

        $lastFiveMonthsLessons = array_reverse($lastFiveMonthsLessons);


        // GRÁFICO DE COLUNA (% DE AULAS COMPLETAS E INCOMPLETAS POR MÓDULO)     
        $modules = Module::where('course_id', $courseId)->get();

        $chartModuleData = [];

        foreach ($modules as $module) {
            $moduleName = $module->title;

            $lessons = Lesson::where('module_id', $module->id)->get();

            $completedCount = 0;
            $totalUserCount = $course->subscribers->count();

            foreach ($lessons as $lesson) {
                // Conte quantos usuários concluíram esta aula
                $completedAulaCount = LessonUser::where('lesson_id', $lesson->id)
                    ->whereNotNull('completed_at')
                    ->count();

                $completedCount += $completedAulaCount;
            }

            // Calcule a porcentagem média de aulas concluídas para este módulo
            $totalLessons = count($lessons);

            $completedPercentage = ($totalLessons > 0) ? ($completedCount / ($totalLessons * $totalUserCount)) * 100 : 0;
            $completedPercentage = round($completedPercentage, 1);
            $incompletePercentage = 100 - $completedPercentage;

            // Adicione os dados do módulo ao array de dados do gráfico
            $chartModuleData[] = [
                'moduleName' => $moduleName,
                'completedPercentage' => $completedPercentage,
                'incompletePercentage' => $incompletePercentage,
            ];
        }


        // GRÁFICO DE COLUNA (CONCLUSÕES DE CURSO)
        $monthlyDataCourseCompletion = [];
        
        $completedCourses = CourseCompletion::where('course_id', $courseId)
            ->whereBetween('completed_at', [now()->subMonths(5), now()])
            ->get();
        
        foreach ($completedCourses as $completedCourse) {
            // Verifique se completed_at é uma data válida
            if (!empty($completedCourse->completed_at)) {
                $completedCourseDate = Carbon::parse($completedCourse->completed_at);
        
                $formattedDateCouseCompletion = $this->translateMonth($completedCourseDate->format('F'));
        
                // Adicione esta conclusão à contagem mensal
                $monthlyDataCourseCompletion[$formattedDateCouseCompletion] = ($monthlyDataCourseCompletion[$formattedDateCouseCompletion] ?? 0) + 1;
            }
        }

        // Preencha os meses ausentes com contagem zero
        $lastFiveMonthsCouseCompletetion = [];
        $currentDateCourse = now();
        for ($j = 4; $j >= 0; $j--) {
            $formattedDateCourseNow = $this->translateMonth($currentDateCourse->format('F'));
            $lastFiveMonthsCouseCompletetion[$formattedDateCourseNow] = $monthlyDataCourseCompletion[$formattedDateCourseNow] ?? 0;
            $currentDateCourse->subMonth();
        }

        $lastFiveMonthsCouseCompletetion = array_reverse($lastFiveMonthsCouseCompletetion);
 
        return view('courses.data.course_performance', compact('course', 'countDate', 'period', 'lastFiveMonths', 'titleLine', 'lastFiveReviews', 'lastFiveMonthsLessons', 'chartModuleData', 'lastFiveMonthsCouseCompletetion'));
    }

    private function calculateDailyCounts($subscriptions, $startDate, $endDate)
    {
        // Inicialize um array vazio para armazenar as contagens diárias
        $dailyCounts = [];

        // Percorra cada dia no intervalo de datas
        $currentDate = clone $startDate;
        while ($currentDate->lte($endDate)) {
            $formattedDate = $currentDate->format('d/m');
            $dailyCounts[$formattedDate] = 0; // Inicialize com zero inscrições

            // Verifique se há inscrições para este dia
            foreach ($subscriptions as $subscription) {
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

    private function calculateMonthlyCounts($subscriptions, $startDate, $endDate)
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
            foreach ($subscriptions as $subscription) {
                if ($subscription->created_at->between($startOfMonth, $endOfMonth)) {
                    $monthlyCounts[$translatedMonth]++;
                }
            }
        
            // Avance para o próximo mês
            $currentDate->addMonth();
        }

        return $monthlyCounts;
    }

    public function courseReviews($courselug, Request $request)
    {        
        $starFilter = null;
        
        $course = Course::where('slug', $courselug)->firstOrFail();

        $ratingsQuery  = $course->ratings();
        $ratingsCount = $course->ratings->count();

        if ($request->has('starFilter')) {
            // Aqui, você pode aplicar o filtro com base no valor enviado pelo formulário.
            $starFilter = $request->input('starFilter');
            $ratingsQuery->where('rating', $starFilter);
        }

        $ratings = $ratingsQuery->paginate(5)->appends(['starFilter' => $starFilter]);

        $allRatings = $course->ratings;
        // Calculando a média das classificações por nota
        $averageRatingsPerScore = $allRatings->groupBy('rating')->map(function ($group) use ($allRatings) {
            $totalCount = $allRatings->count();
            $scoreCount = $group->count();
            return ($scoreCount / $totalCount) * 100;
        });

        return view('courses.data.course_reviews', compact('course', 'ratings', 'averageRatingsPerScore', 'starFilter', 'ratingsCount'));
    }

    public function studentProgress($courselug)
    {
        $course = Course::where('slug', $courselug)->firstOrFail();
        $modules = $course->modules;

        // gráfico de conclusão de aula
        $datas = [];
        $completionTimes = [];

        foreach ($modules as $module) {
            foreach ($module->lessons as $lesson){
                // Conte quantos alunos concluíram cada aula.
                $completedCount = LessonUser::where('lesson_id', $lesson->id)->count();

                // Formate os dados para o gráfico.
                $datas[] = [
                    'lesson' => $lesson->title,
                    'completed' => $completedCount,
                ];

                // Para o gráfico de horário de conclusão de aulas
                // Relacionamento belongsToMany (lesson_user é tabela pivo)
                $completed = $lesson->users()->get();
                foreach ($completed as $user) {
                    // Obtenha a data/hora da conclusão da lição.
                    $completionTime = $user->pivot->created_at;

                    // Obtém a hora do dia
                    $hourOfDay = $completionTime->format('H'); 

                    $completionTimes[] = $hourOfDay;
                }
            }
        }

        // contagem das ocorrências de cada hora.
        $hourCounts = array_count_values($completionTimes);

        $totalHours = 24;

        // Inicialize um novo array com valores padrão zero.
        $filledHourCounts = array_fill(0, $totalHours, 0);

        // Substitua os valores padrão com os valores reais onde estiverem definidos.
        foreach ($hourCounts as $hour => $count) {
            $filledHourCounts[$hour] = $count;
        }

        // gráfico de conclusão de curso
        $completions = $course->completions->count();
        $subscriptions = $course->subscriptions->count();

        return view('courses.data.student_progress', compact('course', 'datas', 'completions', 'subscriptions', 'filledHourCounts'));
    }
}
