<?php

namespace App\Http\Livewire\Company;

use Livewire\Component;

class ViewTimeslotSchedule extends Component
{
    public function render()
    {
        return view('livewire.Company.view-timeslot-schedule')
            ->layout('layouts.jobapplication',[
                'description' => 'View Timeslot Schedule',
                'title' => 'View Timeslot Schedule'
            ]);
    }
}
