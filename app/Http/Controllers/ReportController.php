<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Report;
use App\Models\Course;

class ReportController extends Controller
{
    public function reportsTable(Request $request){
        $searchType = $request->input('search_type');
        $searchTerm = $request->input('search_term');
    
        $query = Report::where('status', 'pendente')
            ->with(['course' => function ($query) {
                $query->withTrashed();
            }, 'lesson' => function ($query) {
                $query->withTrashed();
            }, 'user']);
    
        if ($searchType && $searchTerm) {
            switch ($searchType) {
                case 'course_title':
                        $query->whereHas('course', function ($q) use ($searchTerm) {
                            $q->where('title', 'like', "%$searchTerm%")->withTrashed();
                        });
                    break;
                case 'author':
                        $query->whereHas('course', function ($q) use ($searchTerm) {
                            $q->whereHas('creator', function ($qq) use ($searchTerm) {
                                $qq->where('name', 'like', "%$searchTerm%");
                            })->withTrashed();
                        });
                    break;
                case 'lesson_title':
                        $query->whereHas('lesson', function ($q) use ($searchTerm) {
                            $q->where('title', 'like', "%$searchTerm%")->withTrashed();
                        });
                    break;
                case 'user':
                        $query->whereHas('user', function ($q) use ($searchTerm) {
                            $q->where('name', 'like', "%$searchTerm%");
                        });
                    break;
                case 'description':
                        $query->where('description', 'like', "%$searchTerm%");
                    break;
            }
        }
    
        $reports = $query->paginate(8)->appends(['search_type' => $searchType, 'search_term' => $searchTerm]);
        
        return view('admin.reports.table', compact('reports', 'searchTerm'));
    }    

    public function reportsSlides(){
        $reports = Report::where('status', 'pendente')
            ->with(['course' => function ($query) {
                $query->withTrashed();
            }, 'lesson' => function ($query) {
                $query->withTrashed();
            }, 'user'])->get();

        return view('admin.reports.slides', compact('reports'));
    }

    public function show($id)
    {
        $report = Report::with(['course' => function ($query) {
            $query->withTrashed();
        }, 'lesson' => function ($query) {
            $query->withTrashed();
        }, 'user'])->find($id);

        return view('admin.reports.single_slide', compact('report'));
    }

    public function store(Request $request, $courseSlug)
    {
        $course = Course::where('slug', $courseSlug)->firstOrFail();
        
        // Validar os dados recebidos do formulário
        $validatedData = $request->validate([
            'denuncia' => 'required',
            'selecao-aula' => 'required_if:denuncia,aula',
            'desc' => 'required',
        ]);

        $report = new Report();

        $report->description = $validatedData['desc'];
        $report->user_id = auth()->user()->id;

        // Verificando qual a denuncia
        if ($validatedData['denuncia'] === 'curso') {
            $report->course_id = $course->id;
        }
        elseif ($validatedData['denuncia'] === 'aula') {
            $report->course_id = $course->id;
            $report->lesson_id = $validatedData['selecao-aula'];
        }

        $report->save();

        return redirect()->back()->with('success', 'Denúncia enviada com sucesso!');
    }
}
