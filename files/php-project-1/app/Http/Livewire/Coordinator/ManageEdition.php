<?php

namespace App\Http\Livewire\Coordinator;

use App\Models\Edition;

use Livewire\Component;

use Illuminate\Support\Facades\App;

class ManageEdition extends Component
{
    public $orderBy = 'date';
    public $orderAsc = false;

    public $showEditModal = false;
    public $showDeleteModal = false;
    public $showDeleteErrorModal = false;
    public $showMakeActiveModal = false;

    // Model variable for the language
    public $language;


    /**
     * Array that contains the values for a new or updated version of the edition
     * @var array
     * @see https://laravel.com/docs/8.x/eloquent#mass-assignment
     */
    public $newEdition = [
        'id' => null,
        'name' => '',
        'date' => null,
        'isActive' => false,
        'numberOfAppointments' => null,
        'deadline' => null,
    ];

    /**
     * The validation rules.
     * @return array
     * @see https://laravel.com/docs/8.x/validation#available-validation-rules
     */
    protected function rules()
    {
        return [
            'newEdition.name' => 'string',
            'newEdition.date' => 'required|date',
            'newEdition.isActive' => 'boolean',
            'newEdition.numberOfAppointments' => 'required|integer|min:1',
            'newEdition.deadline' => 'required|date|before:newEdition.date',
        ];
    }

    /**
     * The attributes that should be used for validation.
     * @var array
     * @see https://laravel.com/docs/8.x/validation#customizing-the-error-messages
     */
    protected $validationAttributes = [
        'newEdition.name' => 'name',
        'newEdition.date' => 'date',
        'newEdition.isActive' => 'is active',
        'newEdition.numberOfAppointments' => 'number of appointments',
        'newEdition.deadline' => 'deadline',
    ];

    /**
     * Sort the editions by the given column.
     * @param string $column
     * @return void
     */
    public function resort($column)
    {
        // if the column is the same as the current orderBy, then reverse the order
        if ($this->orderBy !== $column) {
            $this->orderBy = $column;
            $this->orderAsc = false;
        } else {
            $this->orderAsc = !$this->orderAsc;
        }
    }

    /**
     * Set the values of the newEdition array to the values of the given edition.
     * If the given edition is null, then set the values to default.
     * @param Edition|null $edition
     * @return void
     */
    public function setNewEdition(Edition $edition = null)
    {
        $this->resetErrorBag();
        if ($edition->id) {
            $this->newEdition['id'] = $edition->id;
            $this->newEdition['name'] = $edition->name;
            $this->newEdition['date'] = $edition->date;
            $this->newEdition['isActive'] = $edition->isActive;
            $this->newEdition['numberOfAppointments'] = $edition->numberOfAppointments;
            $this->newEdition['deadline'] = $edition->deadline;
        } else {
            $this->newEdition['id'] = null;
            $this->newEdition['name'] = '';
            $this->newEdition['date'] = null;
            $this->newEdition['isActive'] = false;
            $this->newEdition['numberOfAppointments'] = null;
            $this->newEdition['deadline'] = null;
        }
        $this->showEditModal = true;
    }

    /**
     * Create a new edition and add it to the database.
     * @return void
     */
    public function createEdition()
    {
        $this->validate();
        $edition = Edition::create([
            'name' => $this->newEdition['name'],
            'date' => $this->newEdition['date'],
            'isActive' => $this->newEdition['isActive'],
            'numberOfAppointments' => $this->newEdition['numberOfAppointments'],
            'deadline' => $this->newEdition['deadline'],
        ]);
        $this->showEditModal = false;
        $this->dispatchBrowserEvent('swal:toast', [
            'background' => 'success',
            'html' => 'The edition has been created.'
        ]);
    }

    /**
     * Update the given edition with the values in the newEdition array.
     * @param Edition $edition
     * @return void
     */
    public function updateEdition(Edition $edition)
    {
        $this->validate();
        $edition->update([
            'name' => $this->newEdition['name'],
            'date' => $this->newEdition['date'],
            'isActive' => $this->newEdition['isActive'],
            'numberOfAppointments' => $this->newEdition['numberOfAppointments'],
            'deadline' => $this->newEdition['deadline'],
        ]);
        $this->showEditModal = false;
        $this->dispatchBrowserEvent('swal:toast', [
            'background' => 'success',
            'html' => 'The edition has been updated.'
        ]);
    }

    /**
     * Set the values of the newEdition array to the values of the given edition.
     * @param Edition $edition
     * @return void
     */


    /**
     * Delete the given edition.
     * @param Edition $edition
     * @return void
     */
    public function deleteEdition(Edition $edition)
    {
        $edition->delete();
        $this->dispatchBrowserEvent('swal:toast', [
            'background' => 'success',
            'html' => 'The edition has been deleted.'
        ]);
        $this->showDeleteModal = false;
    }

    /**
     * Set the given edition to active.
     * @param Edition $edition
     * @return void
     */
    public function setMakeActive(Edition $edition)
    {
        // reset the error bag
        $this->resetErrorBag();

        // set the values of the newEdition array to the values of the given edition
        $this->newEdition['id'] = $edition->id;
        $this->newEdition['name'] = $edition->name;
        $this->newEdition['date'] = $edition->date;
        $this->newEdition['isActive'] = $edition->isActive;
        $this->newEdition['numberOfAppointments'] = $edition->numberOfAppointments;
        $this->newEdition['deadline'] = $edition->deadline;

        // show the delete modal
        $this->showMakeActiveModal = true;
    }

    /**
     * Set the given edition to active.
     * @param Edition $edition
     * @return void
     */
    public function makeActive(Edition $edition)
    {
        // disable the make active modal
        $this->showMakeActiveModal = false;

        // get the active edition
        $activeEdition = Edition::where('isActive', true)->first();

        // set it to inactive
        $activeEdition->update([
            'isActive' => false,
        ]);

        // set the given edition to active
        $edition->update([
            'isActive' => true,
        ]);

        // show a toast mesage to confirm it
        $this->dispatchBrowserEvent('swal:toast', [
            'background' => 'success',
            'html' => 'The edition has been set to active.'
        ]);
    }

    /**
     * Show the delete modal.
     * @param Edition $edition
     * @return view
     */
    public function render()
    {
        App::setLocale(session('locale') ?? 'en');
        $editions = Edition::orderBy($this->orderBy, $this->orderAsc ? 'asc' : 'desc')->get();
        return view('livewire.Coordinator.manage-edition', compact('editions'))
            ->layout('layouts.jobapplication', [
                "description" => "Manage all the Editions",
                "title" => __('crud.manage') . " " . trans_choice('editions.editions', 2)
            ]);
    }
}
