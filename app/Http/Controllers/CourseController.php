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

use App\Events\CourseDeleted;


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

        if (Auth::check()) {
            $user = auth()->user();

            $favoriteCourseIds = $user->favorites()->pluck('course_id')->toArray();
            $favoriteCourses = Course::whereIn('id', $favoriteCourseIds)->get();

            $subscribedCourses = $user->subscribedCourses()->get();
        }

        $courses = Course::all();
        return view('courses.index-viewer', compact('courses', 'user', 'favoriteCourses', 'favoriteCourseIds', 'subscribedCourses'));
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

        $courses = $user->courses()->get();
        return view('courses.index-creator', compact('courses', 'user'));
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
        $nameUser = null;

        $user = User::find(auth()->user()->id);
        $completedCourses = $user->completions->pluck('course');

        $nameUser = $user->name;

        return view('courses.completed', compact('completedCourses', 'nameUser'));
    }


    public function create()
    {
        if (Gate::allows('manage-courses')) {
            $categories = Category::all();
            return view('courses.create', compact('categories'));
        }

        session()->flash('creator', 'Você precisa ser um criador para criar um curso!');
        return redirect()->route('courses.viewer');
    }


    public function store(Request $request)
    {
        if (Gate::denies('manage-courses')) {
            session()->flash('creator', 'Você precisa ser um criador para criar um curso!');
            return redirect()->route('courses.viewer');
        }

        // Valide os dados do formulário
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'category' => 'required|exists:categories,id',
        ]);

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
        $course->save();

        return redirect()->route('courses.creator')->with('success', 'Curso excluído com sucesso');
    }

    public function show($slug, Request $request)
    {
        $starFilter = null;
        $userIsSubscribed = null;
        $admin = false;
        $user = null;
        $user = auth()->user();

        if ($user && $user->isAdmin()) {
            $admin = true;
        }

        // Obtendo o curso com base no slug
        $course = Course::where('slug', $slug)->firstOrFail();

        // Obtendo os módulos relacionados ao curso
        $modules = $course->modules;

        $ratingsQuery  = $course->ratings();

        if ($request->has('starFilter')) {
            // Aqui, você pode aplicar o filtro com base no valor enviado pelo formulário.
            $starFilter = $request->input('starFilter');
            $ratingsQuery->where('rating', $starFilter);
        }

        $ratings = $ratingsQuery->get();

        // categoria do curso
        $category = $course->category->name;

        // Calculando a média das notas
        $averageRating = $ratings->avg('rating');

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

        return view('courses.show', compact('course', 'modules', 'ratings', 'averageRating', 'averageRatingsPerScore', 'userIsSubscribed', 'user', 'category', 'admin', 'starFilter'));
    }

    public function showDeleted($slug)
    {
        $userIsSubscribed = null;
        $user = null;
        $admin = true;

        // Obtendo o curso denunciado com base no slug
        $course = Course::withTrashed()->where('slug', $slug)->firstOrFail();

        // Obtendo os módulos e suas aulas (excluídos) relacionados ao curso
        $modules = $course->modules()->with(['lessons' => function ($query) {
            $query->withTrashed();
        }])->withTrashed()->get();

        // Obtendo as avaliações do curso
        $ratings = $course->ratings;

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

        return view('courses.show', compact('course', 'modules', 'ratings', 'averageRating', 'averageRatingsPerScore', 'userIsSubscribed', 'user', 'category', 'admin'));
    }

    public function edit($slug)
    {
        if (Gate::denies('manage-courses')) {
            session()->flash('creator', 'Você precisa ser um criador para criar um curso!');
            return redirect()->route('courses.viewer');
        }

        $course = Course::where('slug', $slug)->firstOrFail();

        $categories = Category::all();

        // Verifique se o usuário autenticado é o criador do curso
        // if ($course->creator_id !== Auth::id()) {
        //     abort(403, 'Unauthorized');
        // }

        // Renderize a view edit para editar o curso
        return view('courses.edit', compact('course', 'categories'));
    }

    public function update(Request $request, $slug)
    {
        if (Gate::denies('manage-courses')) {
            session()->flash('creator', 'Você precisa ser um criador para criar um curso!');
            return redirect()->route('courses.viewer');
        }

        $course = Course::where('slug', $slug)->firstOrFail();

        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'category_id' => 'required|exists:categories,id',
        ]);

        if ($request->hasFile('image')) {
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
        $course->save();

        return redirect()->route('courses.creator')->with('success', 'Curso excluído com sucesso');
    }

    public function destroy($slug)
    {
        if (Gate::denies('manage-courses')) {
            session()->flash('creator', 'Você precisa ser um criador para criar um curso!');
            return redirect()->route('courses.viewer');
        }

        // Verifique se o usuário autenticado é o criador do curso
        // if ($course->creator_id !== Auth::id()) {
        //     abort(403, 'Unauthorized');
        // }

        $course = Course::where('slug', $slug)->firstOrFail();

        // Deleta a imagem associada ao curso, se existir
        // if ($course->image) {
        //     Storage::disk('public')->delete($course->image);
        // }

        // Exclui o curso do banco de dados
        $course->delete();

        // despachando o evento que atualiza qtd de aulas do curso
        event(new CourseDeleted($course));

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

        return redirect()->route('course.config', $course->slug)->with('success', 'Curso marcado como concluído!');
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
}
