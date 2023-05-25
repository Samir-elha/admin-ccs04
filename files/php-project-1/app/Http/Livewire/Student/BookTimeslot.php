<?php

namespace App\Http\Livewire\Student;

use Livewire\Component;

class BookTimeslot extends Component
{
    public function render()
    {
        return view('livewire.Student.book-timeslot')
            ->layout("layouts.jobapplication",[
                "description" => "Book Timeslot",
                "title" => "Book Timeslot"
            ]);
    }
}
