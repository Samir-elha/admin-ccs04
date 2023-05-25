<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class LanguageController extends Controller
{
    public function setLocale($locale)
    {
        App::setLocale($locale);
        Session::put('locale', $locale);

        // Redirect back to the previous page
        return redirect()->back();
    }
}
