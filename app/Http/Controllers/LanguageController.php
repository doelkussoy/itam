<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LanguageController extends Controller
{
    public function switchLang($lang)
    {
        if (array_key_exists($lang, ['en' => 'English', 'id' => 'Indonesian'])) {
            session()->put('locale', $lang);
        }
        return redirect()->back();
    }
}
