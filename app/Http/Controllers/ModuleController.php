<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Module;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ModuleController extends Controller
{
    public function index($courseSlug)
    {
        $course = Course::where('slug', $courseSlug)
            ->with(['modules' => function ($query) {
                $query->orderBy('order');
            }, 'modules.lessons' => function ($query) {
                $query->orderBy('order');
            }])
            ->firstOrFail();

        $module = null;

        $user = auth()->user();
        $nameUser = $user->name;

        return view('modules.index', compact('course', 'nameUser', 'module'));
    }

    public function create($courseSlug)
    {
        $course = Course::where('slug', $courseSlug)->firstOrFail();

        return view('modules.create', compact('course'));
    }

    public function store(Request $request, $courseSlug)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
        ]);

        $course = Course::where('slug', $courseSlug)->firstOrFail();

        $lastModule = $course->modules()->latest('order')->first();
        $order = $lastModule ? $lastModule->order + 1 : 1;

        $module = new Module();

        $module->title = $request->input('title');

        $slug = Str::slug($module->title);
        $uniqueSlug = $slug;
        $count = 2;
        // Verifica se o slug já existe no banco de dados
        while (Module::where('slug', $uniqueSlug)->withTrashed()->exists()) {
            $uniqueSlug = $slug . '-' . $count;
            $count++;
        }

        $module->slug = $uniqueSlug;
        $module->order = $order;

        $course->modules()->save($module);

        return redirect()->route('modules.index', $course->slug)->with('success', 'Módulo criado com sucesso.');
    }

    public function edit($courseSlug, $moduleSlug)
    {
        $course = Course::where('slug', $courseSlug)
            ->with(['modules' => function ($query) {
                $query->orderBy('order');
            }, 'modules.lessons' => function ($query) {
                $query->orderBy('order');
            }])
            ->firstOrFail();
            
        $module = $course->modules()->where('slug', $moduleSlug)->firstOrFail();

        $user = auth()->user();
        $nameUser = $user->name;

        return view('modules.index', compact('course', 'module', 'nameUser'));
    }

    public function update(Request $request, $courseSlug, $moduleSlug)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
        ]);

        $course = Course::where('slug', $courseSlug)->firstOrFail();
        $module = $course->modules()->where('slug', $moduleSlug)->firstOrFail();
        
        $module->title = $request->input('title');

        $slug = Str::slug($module->title);
        $uniqueSlug = $slug;
        $count = 2;
        // Verifica se o slug já existe no banco de dados
        while (Module::where('slug', $uniqueSlug)->withTrashed()->exists()) {
            $uniqueSlug = $slug . '-' . $count;
            $count++;
        }

        $module->slug = $uniqueSlug;
        $module->save();

        return redirect()->route('modules.index', $course->slug)->with('success', 'Módulo atualizado com sucesso.');
    }

    public function destroy($courseSlug, $moduleSlug)
    {
        $course = Course::where('slug', $courseSlug)->firstOrFail();
        $module = $course->modules()->where('slug', $moduleSlug)->firstOrFail();

        $module->delete();

        // Atualizando a ordem dos módulos restantes
        $remainingModules = $course->modules()->where('order', '>', $module->order)->get();
        foreach ($remainingModules as $remainingModule) {
            $remainingModule->order -= 1;
            $remainingModule->save();
        }

        return redirect()->route('modules.index', $course->slug)->with('success', 'Módulo excluído com sucesso.');
    }
}

