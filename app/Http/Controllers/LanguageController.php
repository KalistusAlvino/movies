<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LanguageController extends Controller
{
    public function switch(Request $request, $locale)
    {
        try {
            if (in_array($locale, ['en', 'id'])) {
                app()->setLocale($locale);
                session()->put('locale', $locale);
            }

            return redirect()->back();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to change language.');
        }
    }
}
