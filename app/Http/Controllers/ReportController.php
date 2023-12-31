<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Report;
use App\Models\Course;
use App\Models\User;
use App\Models\Conversation;

use App\Notifications\ReportRejected;
use App\Notifications\ReportAccepted;
use App\Notifications\NewReport;
use App\Notifications\FirstReportNotification;
use App\Notifications\SecondReportNotification;
use App\Notifications\ThirdReportNotification;

class ReportController extends Controller
{
    public function reportsTable(Request $request){
        $searchType = $request->input('search_type');
        $searchTerm = $request->input('search_term');

        // total de mensagens não lidas 
        $qtdMsg = auth()->user()->totalUnreadMessagesCount();
    
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
        
        return view('admin.reports.table', compact('reports', 'searchTerm', 'qtdMsg'));
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

        $admin = User::where('role', 'admin')->firstOrFail();
        $admin->notify(new NewReport($report));

        return redirect()->route('courses.show', $course->slug)->with('success', 'Denúncia do curso "' . $course->title . '" enviada com sucesso! A avaliação ocorrerá em até 7 dias. Agradecemos sua paciência.');
    }

    public function acceptReporting($reportId)
    {
        $report = Report::findOrFail($reportId);
        $report->load('user', 'course');

        $userReported = $report->course->creator;
        $userReported->update(['report_count' => $userReported->report_count + 1]);

        if ($userReported->report_count == 1) {
            $userReported->notify(new FirstReportNotification($report->course->title));
        } elseif ($userReported->report_count == 2) {
            $userReported->notify(new SecondReportNotification($report->course->title));
        } elseif ($userReported->report_count == 3) {
            $userReported->suspend(7);
            $userReported->notify(new ThirdReportNotification($report->course->title));
        } elseif ($userReported->report_count == 4) {
            $userReported->ban();
        }

        $report->update(['status' => 'aceita']);

        $madeBy = $report->user;
        $madeBy->notify(new ReportAccepted($report->course->title));

        return redirect()->route('admin.reports')->with('success', 'Denúncia aceita com sucesso!');
    }

    public function declineReporting($reportId)
    {
        $report = Report::findOrFail($reportId);
        $report->load('user', 'course');

        $report->update(['status' => 'recusada']);

        $madeBy = $report->user;
        $madeBy->notify(new ReportRejected($report->course->title));

        return redirect()->route('admin.reports')->with('success', 'Denúncia recusada com sucesso!');
    }

    public function chatWithAuthor($authorId)
    {
        $adminId = auth()->id();

        // Check if conversation already exists
        $existingConversation = Conversation::where(function ($query) use ($adminId, $authorId) {
                $query->where('sender_id', $adminId)
                    ->where('receiver_id', $authorId);
                })
                ->orWhere(function ($query) use ($adminId, $authorId) {
                    $query->where('sender_id', $authorId)
                        ->where('receiver_id', $adminId);
                })->first();
            
        if ($existingConversation) {
            // Conversation already exists, redirect to existing conversation
            return redirect()->route('chat', ['query' => $existingConversation->id]);
        }
    
        // Create new conversation
        $createdConversation = Conversation::create([
            'sender_id' => $adminId,
            'receiver_id' => $authorId,
        ]);
 
        return redirect()->route('chat', ['query' => $createdConversation->id]);
    }
}
