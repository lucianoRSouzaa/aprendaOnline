<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use Illuminate\Support\Facades\Auth;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $nameUser = null;

        if (Auth::check()) {
            $user = auth()->user();
            $nameUser = $user->name;
        }

        $searchTerm = $request->input('searchTerm');
        $courses = Course::where('title', 'like', '%' . $searchTerm . '%')->get();

        dd($request);

        // Retorne a view desejada com os resultados da pesquisa
        return view('courses.index-viewer', compact('courses', 'nameUser', 'searchTerm'));
    }
}
