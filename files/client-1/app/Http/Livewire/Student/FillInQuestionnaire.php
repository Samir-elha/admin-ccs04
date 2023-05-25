<?php

namespace App\Http\Livewire\Student;

use Illuminate\Support\Facades\App;

use Livewire\Component;
use App\Models\Questionaire;
use App\Models\Edition;

use function GuzzleHttp\Promise\queue;

class FillInQuestionnaire extends Component
{
    /**
     * Render the view for the fill in questionnaire page
     * @return \Illuminate\View\View
     */
    public function render()
    {
        // Set the locale for the application
        App::setLocale(session('locale') ?? 'en');

        // Get the questionnaire for the active edition
        $questionnaire = Questionaire::where('edition_id', '=', Edition::ActiveId())->first();

        // Return the view
        return view('livewire.Student.fill-in-questionnaire', compact('questionnaire'))
            ->layout('layouts.jobapplication', [
                "description" => "Fill in Questionnaire",
                "title" => "Fill in Questionnaire"
            ]);
    }
}
