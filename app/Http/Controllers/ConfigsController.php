<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ConfigsController extends Controller
{
    public function index() 
    {
        return view('configs.index');
    }

    public function setLang($locale)
    {
        if (in_array($locale, ['en', 'pt', 'es'])) {
            // Armazene a preferência de idioma do usuário em um cookie
            return redirect()->route('courses.creator')->withCookie(cookie()->forever('preferred_language', $locale));
        }

        // Caso o idioma não seja suportado, redirecione para a página inicial
        return redirect('/');
    }

    public function setTheme($theme)
    {
        if ($theme === 'light' || $theme === 'dark') {
            return redirect()->route('courses.creator')->withCookie(cookie()->forever('theme_preference', $theme));
        }

        return redirect('/');
    }
}
