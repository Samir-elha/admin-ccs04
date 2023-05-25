<?php

namespace App\Http\Livewire\Coordinator;

use Livewire\Component;

class ViewFeedback extends Component
{
    public function render()
    {
        return view('livewire.Coordinator.view-feedback')
            ->layout('layouts.jobapplication',[
                "description" => "View Feedback",
                "title" => "View feedback"
            ]);
    }
}
