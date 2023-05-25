<?php

namespace App\Http\Livewire\Coordinator;

use App\Models\TimeSlot;
use App\Models\Edition;

use Livewire\Component;
use Illuminate\Support\Facades\App;

class ManageTimeslots extends Component
{

    public $showAddModal = false;

    public $newTimeslot = [
        'id' => 'null',
        'startTime' => '',
        'endTime' => '',
        'edition' => 'null',
    ];

    protected function rules ()
    {
        return [
            'newTimeslot.startTime' => 'required|date_format:H:i|after:07:59|before:20:01',
            'newTimeslot.endTime' => 'required|date_format:H:i|after:newTimeslot.startTime|before:20:01',
            'newTimeslot.edition' => 'required|exists:editions,id',
        ];
    }

    protected $validationAttributes = [
        'newTimeslot.startTime' => 'start',
        'newTimeslot.endTime' => 'end',
    ];

    // Create a function to add a new timeslot
    public function setNewTimeslot(TimeSlot $timeslot = null)
    {
        $this->resetErrorBag();

        if ($timeslot->id) {
            $this->newTimeslot = $timeslot;
        } else {
            $this->newTimeslot = [
                'id' => 'null',
                'startTime' => '',
                'endTime' => '',
                'edition' => Edition::orderBy('date', 'desc')->first()->id,
            ];

            $this->timeslot = null;
        }

        $this->showAddModal = true;
    }

    public function addTimeslot()
    {
        $this->validate();

        $edition = Edition::find($this->newTimeslot['edition']);
        $start_time = $this->newTimeslot['startTime'];
        $end_time = $this->newTimeslot['endTime'];

        // Check if there are any overlapping timeslots for the selected edition. If the end time of the first timeslot is fe 10:00 and the start time of the second timeslot is 10:00, they are not overlapping.
        $overlapping_timeslots = $edition->timeslots()
            ->where(function ($query) use ($start_time, $end_time) {
                $query->where(function ($query) use ($start_time, $end_time) {
                    $query->where('startTime', '<', $end_time)
                        ->where('endTime', '>', $start_time);
                })
                ->orWhere(function ($query) use ($start_time, $end_time) {
                    $query->where('startTime', '=', $start_time)
                        ->where('endTime', '=', $end_time);
                });
            })
            ->exists();


        if ($overlapping_timeslots) {
            $this->addError('newTimeslot.startTime', 'The new timeslot overlaps with an existing timeslot.');

            return;
        }


        $timeslot = TimeSlot::create([
            'startTime' => $this->newTimeslot['startTime'],
            'endTime' => $this->newTimeslot['endTime'],
            'edition_id' => $this->newTimeslot['edition'],
        ]);

        $this->dispatchBrowserEvent('swal:toast', [
            'background' => 'success',
            // Format the time to 24 hour format with xx:xx
            'html' => "The timeslot <b><i>" . $this->formatTime($timeslot->startTime) . " - " . $this->formatTime($timeslot->endTime) . "</i></b> has been added",
        ]);

        $this->showAddModal = false;
    }

    public function deleteTimeslot(TimeSlot $timeslot)
    {
        // Ask if they're sure before deleting


        $timeslot->delete();

        $this->dispatchBrowserEvent('swal:toast', [
            'background' => 'success',
            'html' => "The timeslot <b><i>" . $this->formatTime($timeslot->startTime) . " - " . $this->formatTime($timeslot->endTime) . "</i></b> has been deleted",
        ]);
    }

    function formatTime($time)
    {
        return date('H:i', strtotime($time));
    }

    public function render()
    {
        App::setLocale(session('locale') ?? 'en');

        $editionId = Edition::orderBy('date', 'desc')->first()->id;

        $this->timeslots = TimeSlot::where('edition_id', $editionId)->get();

        return view('livewire.Coordinator.manage-timeslots')
            ->layout('layouts.jobapplication',[
                "description" => "Manage Timeslots",
                "title" => "Manage Timeslots"
            ]);
    }
}
