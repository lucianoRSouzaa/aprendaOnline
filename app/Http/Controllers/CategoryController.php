<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Category;
use App\Models\Subscription;
use App\Models\Course;

use Carbon\Carbon;


class CategoryController extends Controller
{
    public function index()
    {
        return view('admin.categories.index');
    }

    public function create(Request $request)
    {
        $searchTerm = $request->input('search_term');

        $query = Category::query();

        if ($searchTerm) {
            $query->where('name', 'LIKE', '%' . $searchTerm . '%');
        }

        $categories = $query->paginate(8)->appends(['search_term' => $searchTerm]);

        return view('admin.categories.create', compact('categories', 'searchTerm'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:categories|max:255',
        ], [
            // personalizando mensagens de erros
            'name.unique' => 'Este nome não é válido, essa categoria já existe!',
        ]);

        $category = new Category();
        $category->name = $request->input('name');

        $category->save();

        return redirect()->route('admin.categories.create')->with('success', 'Categoria criada com sucesso!');
    }

    public function show($id, Request $request)
    {
        $searchTerm = $request->input('search_term');

        $category = Category::findOrFail($id);
        $queryCourses = $category->courses();
        $qtdCourse = $category->courses->count();

        // pegando média de clasificações
        $category->average_rating = $queryCourses->avg('average_rating');

        if ($searchTerm) {
            $queryCourses = $queryCourses->where('title', 'like', "%$searchTerm%");
        }

        $courses = $queryCourses->paginate(8)->appends(['search_term' => $searchTerm]);

        // pegando quantidade de usuários inscritos
        $users = Subscription::whereIn('course_id', function ($query) use ($category) {
            $query->select('id')->from('courses')->where('category_id', $category->id);
        })
        ->count();

        // Gráfico de linha
        $data = [];

        $startDate = Carbon::now()->startOfMonth();
        $endDate = Carbon::now()->startOfMonth()->subMonths(6);

        $totalCreated = null;

        for ($date = $endDate; $date->lte($startDate); $date->addMonth()) {
            $month = $date->format('F');
        
            $translatedMonth = $this->translateMonth($month);
        
            // Obtém a quantidade de cursos criados apenas neste mês para a categoria específica.
            $coursesCreatedInMonth = Course::where('category_id', $id)
                ->whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)
                ->count();
        
            $totalCreated += $coursesCreatedInMonth;
                
            $data[] = [$translatedMonth, $totalCreated, $coursesCreatedInMonth];
        }

        return view('admin.categories.show', compact('category', 'data', 'qtdCourse', 'users', 'courses', 'searchTerm'));
    }
  
    public function data(Request $request)
    {
        if ($request->isMethod('POST')) {
            $validatedData = $request->validate([
                'select-category1' => 'required|different:select-category2|different:select-category3|exists:categories,id',
                'select-category2' => 'different:select-category1|different:select-category3|exists:categories,id',
                'select-category3' => 'different:select-category1|different:select-category2|exists:categories,id',
                'since' => 'required|before:to',
                'to' => 'required|after:since',
                'year' => 'required',
            ]);        
        }

        $categories = Category::all();

        // GRÁFICO DE PIZZA de categorias com maiores qtds de cursos
        $categoriesChart = Category::withCount('courses as courses_count')
        ->orderBy('courses_count', 'desc')
        ->take(5)
        ->get();

        // GRÁFICO DE BARRAS com categorias mais bem avaliadas
        $categoriesBarChart = Category::with('courses')->get();
        // calculando a média das classificações dos cursos de cada categoria
        $categoriesBarChart->each(function ($category) {
            $category->average_rating = $category->courses->avg('average_rating');
        });
        $categoriesBarChart = $categoriesBarChart->sortByDesc('average_rating')->take(5);

        // GRÁFICO DINÂMICO DE LINHA
        $data = null;
        $category1Name = null;
        $category2Name = null;
        $category3Name = null;
        if ($request->isMethod('POST')){
            $startMonth = $validatedData['since']; // Mês início
            $endMonth = $validatedData['to']; // Mês término

            // Crie datas de início e término com base nos meses fornecidos.
            $startDate = Carbon::parse("1 $startMonth")->startOfMonth();
            $endDate = Carbon::parse("1 $endMonth")->endOfMonth();

            $category1 = Category::find($validatedData['select-category1']);
            $category1Name = $category1->name;

            $category2 = null;
            if ($request->input('select-category2')) {
                $category2 = Category::find($validatedData['select-category2']);
                $category2Name = $category2->name;
            }

            $category3 = null;
            if ($request->input('select-category3')) {
                $category3 = Category::find($validatedData['select-category3']);
                $category3Name = $category3->name;
            }
            
            $data = $this->generateChartData($startDate, $endDate, $category1, $category2, $category3);
        }

        return view('admin.categories.data', compact('categories', 'categoriesChart', 'categoriesBarChart', 'data', 'category1Name', 'category2Name', 'category3Name'));
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

    private function generateChartData($startDate, $endDate, $category1, $category2 = null, $category3 = null)
    {
        // array que guardará os dados formatados
        $data = [];

        $totalCreated1 = 0;
        $totalCreated2 = 0;
        $totalCreated3 = 0;

        for ($date = $startDate; $date->lte($endDate); $date->addMonth()) {
            $month = $date->format('F');
            
            // Traduz o nome do mês para o português.
            $translatedMonth = $this->translateMonth($month);

            // Obtém a quantidade de registros criados apenas neste mês (da categoria 1).
            $createdInMonth1 = Subscription::whereIn('course_id', function ($query) use ($category1) {
                $query->select('id')->from('courses')->where('category_id', $category1->id);
            })
            ->whereMonth('created_at', $date->month)
            ->whereYear('created_at', $date->year)
            ->count();

            $totalCreated1 += $createdInMonth1;

            // obtém da categoria 2
            if ($category2) {
                $createdInMonth2 = Subscription::whereIn('course_id', function ($query) use ($category2) {
                        $query->select('id')->from('courses')->where('category_id', $category2->id);
                    })
                    ->whereMonth('created_at', $date->month)
                    ->whereYear('created_at', $date->year)
                    ->count();

                $totalCreated2 += $createdInMonth2;
            }

            // obtém da categoria 3
            if ($category3) {
                $createdInMonth3 = Subscription::whereIn('course_id', function ($query) use ($category3) {
                        $query->select('id')->from('courses')->where('category_id', $category3->id);
                    })
                    ->whereMonth('created_at', $date->month)
                    ->whereYear('created_at', $date->year)
                    ->count();

                $totalCreated3 += $createdInMonth3;
            }

            if ($category3){
                $data[] = [$translatedMonth, $totalCreated1, $createdInMonth1, $totalCreated2, $createdInMonth2, $totalCreated3, $createdInMonth3];
            } 
            elseif ($category2){
                $data[] = [$translatedMonth, $totalCreated1, $createdInMonth1, $totalCreated2, $createdInMonth2];
            }else{
                $data[] = [$translatedMonth, $totalCreated1, $createdInMonth1];
            }

        }

        return $data;
    }
}
