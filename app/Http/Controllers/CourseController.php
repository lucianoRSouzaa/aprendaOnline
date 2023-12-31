<?php

namespace App\Http\Controllers;

use Session;

use App\Models\Category;
use App\Models\User;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

use App\Events\CourseDeleted;
use App\Events\CourseViewed;


class CourseController extends Controller
{
    public function __construct() 
    { 
        $this->middleware('preventBackHistory');
    }
    
    public function indexViewer()
    {
        $favoriteCourseIds = null;
        $favoriteCourses = null;
        $subscribedCourses = null;
        $user = null;
        $totalUnreadMessages = null;

        if (Auth::check()) {
            $user = auth()->user();

            $favoriteCourseIds = $user->favorites()->pluck('course_id')->toArray();
            $favoriteCourses = Course::whereIn('id', $favoriteCourseIds)->get();

            // get total messages unread
            $totalUnreadMessages = $user->totalUnreadMessagesCount();

            $subscribedCourses = $user->subscribedCourses()->get();
        }

        $courses = Course::all();
        $popularCourses = Course::orderBy('views', 'desc')
                ->take(8)
                ->get();
    
        return view('courses.index-viewer', compact('courses', 'user', 'favoriteCourses', 'favoriteCourseIds', 'subscribedCourses', 'popularCourses', 'totalUnreadMessages'));
    }

    public function indexCreator()
    {
        if (Gate::denies('manage-courses')) {
            return redirect()->route('courses.viewer');
        }

        if (session('user_role') === 'viewer') {
            return redirect()->route('courses.viewer');
        }

        $user = auth()->user();

        // get total messages unread
        $totalUnreadMessages = $user->totalUnreadMessagesCount();

        $courses = $user->courses()->get();
        return view('courses.index-creator', compact('courses', 'user', 'totalUnreadMessages'));
    }

    public function toggleMode()
    {
        if (session('user_role')){
            Session::forget('user_role');
            return redirect()->route('courses.creator');
        }

        Session::put('user_role', 'viewer'); 
        return redirect()->route('courses.viewer');
    }

    public function CompletedCourses(){
        $user = User::find(auth()->user()->id);
        $nameUser = $user->name;

        $completedCourses = $user->completions->map(function ($completion) {
            return $completion->course()->withTrashed()->first();
        });

        return view('courses.completed', compact('completedCourses', 'user'));
    }
    
    public function favoritedCourses(){
        $user = User::find(auth()->user()->id);
        $nameUser = $user->name;

        $favoritedCourses = $user->favorites->map(function ($favorite) {
            return $favorite->course()->withTrashed()->first();
        });

        return view('courses.favorited', compact('favoritedCourses', 'user'));
    }

    public function create()
    {
        $categories = Category::all();
        $userEmail = auth()->user()->email;

        return view('courses.create', compact('categories', 'userEmail'));
    }


    public function store(Request $request)
    {
        // Valide os dados do formulário
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'required|mimes:jpeg,png,jpg,gif',
            'category' => 'required|exists:categories,id',
            'email' => 'required|email',
            'learn' => 'array',
        ]);

        if (empty(array_filter($request->input('learn')))) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['learn' => 'Pelo menos um campo de aprendizado deve ser preenchido']);
        }

        $learnData = $request->input('learn');

        // Remove valores nulos do array
        $cleanedLearnData = array_filter($learnData, function ($value) {
            return $value !== null;
        });

        // Gere um nome único para o arquivo da imagem usando md5()
        $uniqueFileName = md5(uniqid()) . '.' . $validatedData['image']->getClientOriginalExtension();

        // Salve a imagem no diretório "public/images"
        $imagePath = $validatedData['image']->storeAs('images', $uniqueFileName, 'public');

        // Crie um novo curso com os dados e o caminho da imagem
        $course = new Course();
        $course->title = $validatedData['title'];
        $course->description = $validatedData['description'];
        $course->image = $imagePath;

        $slug = Str::slug($validatedData['title']);
        $uniqueSlug = $slug;
        $count = 2;
        // Verifica se o slug já existe no banco de dados
        while (Course::where('slug', $uniqueSlug)->withTrashed()->exists()) {
            $uniqueSlug = $slug . '-' . $count;
            $count++;
        }

        $course->slug = $uniqueSlug;
        $course->user_id = Auth::id();
        $course->category_id = $validatedData['category'];
        $course->contact_email = $validatedData['email'];
        $course->what_students_learn = json_encode($cleanedLearnData);
        $course->save();

        return redirect()->route('courses.creator')->with('success', 'Curso criado com sucesso');
    }

    public function show($slug, Request $request)
    {
        // Obtendo o curso com base no slug e seus módulos e suas aulas
        $course = Course::where('slug', $slug)
                ->with(['modules' => function ($query) {
                    $query->orderBy('order');
                }, 'modules.lessons' => function ($query) {
                    $query->orderBy('order');
                }])
                ->firstOrFail();

        $starFilter = null;
        $userIsSubscribed = null;
        $admin = false;
        $user = null;
        $user = auth()->user();

        if ($user && $course->creator->id !== $user->id) {
            event(new CourseViewed($user, $course));
        }

        if ($user && $user->isAdmin()) {
            $admin = true;
        }

        $ratingsQuery  = $course->ratings();
        $ratingsCount = $course->ratings->count();

        if ($request->has('starFilter')) {
            // Aplicando o filtro com base no valor enviado pelo formulário.
            $starFilter = $request->input('starFilter');
            $ratingsQuery->where('rating', $starFilter);
        }

        $ratings = $ratingsQuery->paginate(5)->appends(['starFilter' => $starFilter]);

        // categoria do curso
        $category = $course->category->name;

        // Calculando a média das classificações por nota
        $allRatings = $course->ratings;
        $averageRatingsPerScore = $allRatings->groupBy('rating')->map(function ($group) use ($allRatings) {
            $totalCount = $allRatings->count();
            $scoreCount = $group->count();
            return ($scoreCount / $totalCount) * 100;
        });
        
        if (auth()->user()) {
            $userIsSubscribed = $course->isUserSubscribed(auth()->user());
        }

        $whatWillLeran = json_decode($course->what_students_learn);

        return view('courses.show', compact('course', 'ratings', 'averageRatingsPerScore', 'userIsSubscribed', 'user', 'category', 'admin', 'starFilter', 'whatWillLeran', 'ratingsCount'));
    }

    public function showDeleted($slug, Request $request)
    {
        $starFilter = null;
        $userIsSubscribed = null;
        $user = null;
        $admin = true;

        // Obtendo o curso denunciado com base no slug
        $course = Course::where('slug', $slug)
            ->with(['modules' => function ($query) {
                $query->withTrashed();
                $query->orderBy('order');
            }, 'modules.lessons' => function ($query) {
                $query->withTrashed();
                $query->orderBy('order');
            }])
            ->firstOrFail();

        // Obtendo as avaliações do curso
        $ratings = $course->ratings;

        if ($request->has('starFilter')) {
            // Aplicando o filtro com base no valor enviado pelo formulário.
            $starFilter = $request->input('starFilter');
            $ratingsQuery->where('rating', $starFilter);
        }

        $user = auth()->user();

        // categoria do curso
        $category = $course->category->name;

        // Calculando a média das notas
        $averageRating = $ratings->avg('rating');

        // Calculando a média das classificações por nota
        $averageRatingsPerScore = $ratings->groupBy('rating')->map(function ($group) use ($ratings) {
            $totalCount = $ratings->count();
            $scoreCount = $group->count();
            return ($scoreCount / $totalCount) * 100;
        });
        
        if (auth()->user()) {
            $userIsSubscribed = $course->isUserSubscribed(auth()->user());
        }

        $whatWillLeran = json_decode($course->what_students_learn);

        return view('courses.show', compact('course', 'ratings', 'averageRating', 'averageRatingsPerScore', 'userIsSubscribed', 'starFilter', 'user', 'category', 'admin', 'whatWillLeran'));
    }

    public function edit($slug)
    {
        $course = Course::where('slug', $slug)->firstOrFail();

        $categories = Category::all();

        $whatWillLeran = json_decode($course->what_students_learn);

        // Renderize a view edit para editar o curso
        return view('courses.edit', compact('course', 'categories', 'whatWillLeran'));
    }

    public function update(Request $request, $slug)
    {
        $course = Course::where('slug', $slug)->firstOrFail();

        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|mimes:jpeg,png,jpg,gif',
            'category_id' => 'required|exists:categories,id',
            'contact_email' => 'required|email',
            'learn' => 'array',
        ]);

        $learnData = $request->input('learn');

        // Remove valores nulos do array
        $cleanedLearnData = array_filter($learnData, function ($value) {
            return $value !== null;
        });

        if (empty($cleanedLearnData)) {
            return redirect()
                ->back()
                ->withErrors(['learn' => 'Pelo menos um campo de aprendizado deve ser preenchido'])
                ->withInput();
        }

        if ($request->hasFile('image')) {
            // Exclua a imagem antiga
            Storage::disk('public')->delete($course->image);

            $imageFile = $request->file('image');
            $imageName = md5(uniqid()) . '.' . $imageFile->getClientOriginalExtension();
            $imagePath = $imageFile->storeAs('images', $imageName, 'public');
            $data['image'] = $imagePath;
        }

        $course->fill($data);
    
        $slug = Str::slug($course->title);
        $uniqueSlug = $slug;
        $count = 2;

        while (Course::where('slug', $uniqueSlug)->where('id', '!=', $course->id)->withTrashed()->exists()) {
            $uniqueSlug = $slug . '-' . $count;
            $count++;
        }

        $course->slug = $uniqueSlug;
        $course->what_students_learn = json_encode($cleanedLearnData);
        $course->contact_email = $data['contact_email'];
        $course->save();

        return redirect()->route('courses.creator')->with('success', 'Curso editado com sucesso');
    }

    public function destroy(Request $request, $slug)
    {
        $course = Course::where('slug', $slug)->firstOrFail();

        $password = $request->input('password');
        if (!Hash::check($password, auth()->user()->password)) {
            return redirect()->back()->with('error', 'A senha inserida está incorreta.');
        }

        // Exclui o curso do banco de dados
        $course->delete();

        // despachando o evento que atualiza qtd de aulas do curso
        event(new CourseDeleted($course));

        if (auth()->user()->isAdmin()) {
            return redirect()->back()->with('success', 'Curso excluído com sucesso');
        }
        return redirect()->route('courses.creator')->with('success', 'Curso excluído com sucesso');
    }  
    
    public function markComplete(Course $course)
    {
        $course->update(['is_completed' => true]);

        return redirect()->route('course.config', $course->slug)->with('success', 'Curso marcado como concluído!');
    }

    public function unmarkComplete(Course $course)
    {
        $course->update(['is_completed' => false]);

        return redirect()->route('course.config', $course->slug)->with('success', 'Curso desmarcado como concluído!');
    }

    public function config($courseSlug) 
    {
        $course = Course::where('slug', $courseSlug)->firstOrFail();
        $isCourseCompleted = $course->is_completed;

        if($isCourseCompleted){
            $status = "concluído";
        }else{
            $status = "em andamento";
        }

        return view('courses.data.configs', compact('course', 'isCourseCompleted', 'status'));
    }

    public function search(Request $request)
    {
        $user = auth()->user();
        $selectedCategories = $request->input('categories', []) ?? null;
        $courseTitleSearch = null;
        $author = $request->input('author') ?? null;
        $selectedRating = $request->input('rating', null) ?? null;
        $selectedSort = $request->input('sort', null);

        $courses = Course::query();
        $categories = Category::all();

        // Aplicando filtros
        if ($request->filled('title')) {
            $courseTitleSearch = $request->input('title');
            $courses->where('title', 'like', '%' . $request->input('title') . '%');
        }

        if (!empty($author)) {
            $courses->whereHas('creator', function ($creatorQuery) use ($author) {
                $creatorQuery->where('name', 'like', '%' . $author . '%');
            });
        }

        if (!empty($selectedCategories)) {
            // aplicando filtro para várias categorias
            $courses->whereHas('category', function ($query) use ($selectedCategories) {
                $query->whereIn('category_id', $selectedCategories);
            });
        }

        if ($selectedRating !== null) {
            // Filtrando os cursos com base na avaliação
            $courses->where('average_rating', '>=', $selectedRating);
        }

        if ($selectedSort !== null) {
            if ($selectedSort === 'popularity') {
                // Ordenar por popularidade (cursos com mais inscrições)
                $courses->withCount('subscriptions')->orderByDesc('subscriptions_count');
            } elseif ($selectedSort === 'rating') {
                // Ordenar por melhores avaliações
                $courses->orderBy('average_rating', 'desc');
            } elseif ($selectedSort === 'newest') {
                // Ordenar por cursos mais recentes
                $courses->orderBy('created_at', 'desc');
            } elseif ($selectedSort === 'oldest') {
                // Ordenar por cursos mais antigos
                $courses->orderBy('created_at', 'asc');
            }
        }

        // Executando a consulta
        $filteredCourses = $courses->paginate(12)->appends(['title' => $courseTitleSearch, 'author' => $author, 'categories' => $selectedCategories, 'rating' => $selectedRating, 'sort' => $selectedSort]);

        // quantidade
        $filteredCoursesQtd = $filteredCourses->count();

        return view('courses.search', compact('filteredCourses', 'user', 'filteredCoursesQtd', 'courseTitleSearch', 'author', 'categories', 'selectedCategories', 'selectedRating', 'selectedSort'));
    }
}
