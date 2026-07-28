<?php

namespace App\Http\Controllers;

class ThemeController extends Controller
{
    public function switchTheme($theme)
    {
        if (in_array($theme, ['light', 'dark'])) {
            session(['theme' => $theme]);
        }

        return back();
    }
}
