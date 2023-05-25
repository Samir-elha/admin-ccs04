<?php

namespace App\Http\Livewire\Coordinator;

use Livewire\Component;

use Illuminate\Support\Facades\App;

use App\Models\Questionaire;
use App\Models\Edition;

class ManageQuestionnaire extends Component
{
    public $orderBy = 'date';
    public $orderAsc = false;

    public $showCreateModal = false;
    public $showViewModal = false;
    public $showDeleteModal = false;

    public function resort($column)
    {
        if ($this->orderBy !== $column) {
            $this->orderAsc = true;
        } else {
            $this->orderAsc = !$this->orderAsc;
        }
    }

    // Model list for the questionnaire
    public $newQuestionnaire = [
        'id' => null,
        'url' => '',
        'edition_id' => null,
    ];

    // Validation rules for questionnaire
    protected function rules()
    {
        return [
            'newQuestionnaire.url' => 'required|string',
            'newQuestionnaire.edition_id' => 'required|integer',
        ];
    }

    // Validation messages for questionnaire
    protected $messages = [
        'newQuestionnaire.url.required' => 'The url field is required.',
        'newQuestionnaire.edition_id.required' => 'The edition field is required.',
    ];

    /**
     * Set the values for the new questionnaire
     * @return void
     */
    public function setNewQuestionnaire()
    {
        // Reset the values
        $this->newQuestionnaire = [
            'id' => null,
            'url' => '',
            'edition_id' => null,
        ];

        // Show the create modal
        $this->showCreateModal = true;
    }

    /**
     * Create a new questionnaire
     * @return void
     */
    public function createQuestionnaire()
    {
        // Validate the entries made in the input fields
        $this->validate();

        // Create the questionnaire
        Questionaire::create([
            'url' => $this->newQuestionnaire['url'],
            'edition_id' => $this->newQuestionnaire['edition_id'],
        ]);

        // automatically hide the modal after creating the questionnaire
        $this->showCreateModal = false;

        // Show confirmation message that the questionnaire has been created successfully
        $this->dispatchBrowserEvent('swal:toast', [
            'background' => 'success',
            'html' => __('questionnaire.createMessage')
        ]);
    }

    /**
     * Set the values for the new questionnaire
     * @return void
     */
    public function showQuestionnaire(Questionaire $questionnaire)
    {
        // Set the values for the selected questionnaire
        $this->newQuestionnaire = [
            'id' => $questionnaire->id,
            'url' => $questionnaire->url,
            'edition_id' => $questionnaire->edition_id,
        ];

        // Show the view modal
        $this->showViewModal = true;
    }

    /**
     * Set the values for the new questionnaire
     * @return void
     */
    public function setDeleteQuestionnaire(Questionaire $questionnaire)
    {
        // Set the values for the selected questionnaire
        $this->newQuestionnaire = [
            'id' => $questionnaire->id,
            'url' => $questionnaire->url,
            'edition_id' => $questionnaire->edition_id,
        ];

        // Show the delete modal
        $this->showDeleteModal = true;
    }

    /**
     * Update a questionnaire
     * @return void
     */
    public function deleteQuestionnaire(Questionaire $questionnaire)
    {
        // Delete the questionnaire
        $questionnaire->delete();

        // automatically hide the modal after deleting the questionnaire
        $this->showDeleteModal = false;

        // Show confirmation message that the questionnaire has been deleted successfully
        $this->dispatchBrowserEvent('swal:toast', [
            'background' => 'success',
            'html' => __('questionnaire.deleteMessage')
        ]);
    }

    /**
     * Array that contains the values for a new or updated version of the edition
     * @var array
     */
    public function render()
    {
        // Set the locale for the application
        App::setLocale(session('locale') ?? 'en');

        // Get the questionnaires
        $questionnaires = Questionaire::leftJoin('editions', 'questionaires.edition_id', '=', 'editions.id')
            ->select('questionaires.*', 'editions.id as edition_id')
            ->get();

        // Get the editions
        $editions = Edition::get();

        // Return the view
        return view('livewire.Coordinator.manage-questionnaire', compact('questionnaires', 'editions'))
            ->layout('layouts.jobapplication', [
                "description" => "Manage questionnaire",
                "title" => "Manage questionnaire"
            ]);
    }
}
