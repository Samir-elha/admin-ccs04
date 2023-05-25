<?php

namespace App\Http\Livewire\Coordinator;

use App\Models\User;
use App\Models\Specialization;
use App\Models\StudentEdition;
use App\Models\Edition;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

use App\Mail\ResetPassword;

use Livewire\WithPagination;
use Livewire\Component;

class ManageStudentAccounts extends Component
{
    use WithPagination;

    // create all the public variables
    public $search;

    public $perPage = 10;

    public $orderBy = 'firstName';
    public $orderAsc = true;

    public $showEditModal = false;
    public $showDeleteModal = false;

    /**
     * Array that contains the values for a new or updated version of the student
     * @var array
     */
    public $newStudent = [
        'id' => null,
        'firstName' => null,
        'lastName' => null,
        'email' => null,
        'specialization_id' => null,
        'rNumber' => null,
        'typeID' => 1,
        'emailNotification' => true,
    ];

    /**
     * The validation rules
     * @return array
     * @see https://laravel.com/docs/8.x/validation#available-validation-rules
     */
    protected function rules()
    {
        return [
            'newStudent.firstName' => 'required|string',
            'newStudent.lastName' => 'required|string',
            'newStudent.email' => 'required|email',
            'newStudent.rNumber' => ['required', 'string', 'regex:/(r|R)[0-9]{7}/'],
            'newStudent.type_id' => 'required|integer',
            'newStudent.emailNotification' => 'required|boolean',
        ];
    }

    /**
     * The attributes that should be used for validation
     * @var array
     * @see https://laravel.com/docs/8.x/validation#customizing-the-error-messages
     */
    protected $validateAttributes = [
        'newStudent.firstName' => 'first name',
        'newStudent.lastName' => 'last name',
        'newStudent.email' => 'email|unique:users',
        'newStudent.specialization_id' => 'specialization',
        'newStudent.rNumber' => 'R-number',
        'newStudent.type_id' => 'type',
        'newStudent.emailNotification' => 'email notification',
    ];

    /**
     * Function to sort the table by a column
     * @param string $column
     * @return arrar
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
     * Function to reset the page when a value is changed
     * @param string $propertyName
     * @param mixed $propertyValue
     */
    public function updated($propertyName, $propertyValue)
    {
        // if the property is inside the following list, then reset the page
        if (in_array($propertyName, ['search', 'perPage', 'orderBy', 'orderAsc'])) {
            $this->resetPage();
        }
    }

    /**
     * Set the values of the newStudent array to the values of the given student.
     * If the given student is null, then set the values to default.
     * @param User|null $student
     * @return void
     */
    public function setNewStudent(User $student = null)
    {
        // reset the error bag
        $this->resetErrorBag();

        // if the given student is not null, then set the values to the values of the given student
        // else set the values to default
        if ($student->id) {
            $this->newStudent['id'] = $student->id;
            $this->newStudent['firstName'] = $student->firstName;
            $this->newStudent['lastName'] = $student->lastName;
            $this->newStudent['email'] = $student->email;
            $this->newStudent['specialization_id'] = $student->specialization_id;
            $this->newStudent['rNumber'] = $student->rNumber;
            $this->newStudent['type_id'] = $student->type_id;
            $this->newStudent['emailNotification'] = $student->emailNotification;
        } else {
            $this->newStudent['id'] = null;
            $this->newStudent['firstName'] = null;
            $this->newStudent['lastName'] = null;
            $this->newStudent['email'] = null;
            $this->newStudent['specialization_id'] = null;
            $this->newStudent['rNumber'] = null;
            $this->newStudent['type_id'] = 1;
            $this->newStudent['emailNotification'] = true;
            $this->newStudent['password'] = bin2hex(random_bytes(5));
        }

        // show the edit modal
        $this->showEditModal = true;
    }

    /**
     * Create a new student and add it to the database.
     * @return void
     */
    public function createStudent()
    {
        // Get active edition
        $edition = Edition::where('isActive', true)->first();

        // validate the input fields to see if all the information is within regulations
        $this->validate();

        // create the student with the information from the input fields
        $student = User::create([
            'firstName' => $this->newStudent['firstName'],
            'lastName' => $this->newStudent['lastName'],
            'email' => $this->newStudent['email'],
            'specialization_id' => $this->newStudent['specialization_id'],
            'rNumber' => $this->newStudent['rNumber'],
            'type_id' => $this->newStudent['type_id'],
            'password' => Hash::make($this->newStudent['password']),
        ]);

        // couple the student to the active edition
        $studentEdition = StudentEdition::create([
            'user_id' => $student['id'],
            'edition_id' => $edition->id,
        ]);

        // send the student an email with their password and the instructions to change it
        $recipient = $student->email;
        $subject = 'Welcome to the Job Application System';
        Mail::to($recipient)->send(new ResetPassword($student, $subject, $this->newStudent['password']));

        // automatically close the modal used to create a new student
        $this->showEditModal = false;

        // show a toast to confirm the creation of the student
        $this->dispatchBrowserEvent('swal:toast', [
            'background' => 'success',
            'html' => __('studentAccounts.createStudentToast'),
        ]);
    }

    /**
     * Update the given student with the values in the newStudent array.
     * @param User $student
     * @return void
     */
    public function updateStudent(User $student)
    {
        // validate the input fields to see if all the information is within regulations
        $this->validate();

        // update the student with the information from the input fields
        $student->update([
            'firstName' => $this->newStudent['firstName'],
            'lastName' => $this->newStudent['lastName'],
            'email' => $this->newStudent['email'],
            'specialization_id' => $this->newStudent['specialization_id'],
            'rNumber' => $this->newStudent['rNumber'],
        ]);

        // automatically close the modal used to edit a student
        $this->showEditModal = false;

        // show a toast to confirm the update of the student
        $this->dispatchBrowserEvent('swal:toast', [
            'background' => 'success',
            'html' => __('studentAccounts.updateStudentToast'),
        ]);
    }

    /**
     * Set the values of the newStudent array to the values of the given student.
     * @param User $student
     * @return void
     */
    public function setDeleteStudent(User $student)
    {
        // reset the error bag
        $this->resetErrorBag();

        // set the values of the newStudent array to the values of the given student
        $this->newStudent['id'] = $student->id;
        $this->newStudent['firstName'] = $student->firstName;
        $this->newStudent['lastName'] = $student->lastName;

        // show the delete modal
        $this->showDeleteModal = true;
    }

    /**
     * Delete the given student.
     * @param User $student
     * @return void
     */
    public function deleteStudent(User $student)
    {
        // delete the student
        $student->delete();

        // automatically close the modal used to delete a student
        $this->dispatchBrowserEvent('swal:toast', [
            'background' => 'success',
            'html' => __('studentAccounts.deleteStudentToast'),
        ]);

        // show a toast to confirm the deletion of the student
        $this->showDeleteModal = false;
    }

    /**
     * The main function that renders the page
     * @return view
     */
    public function render()
    {
        // initiate the language change system
        App::setLocale(session('locale') ?? 'en');

        // get all the students from the database and get all the specializations
        $users = User::where('type_id', '=', 1)->withCount(['appointments'])->orderBy($this->orderBy, $this->orderAsc ? 'asc' : 'desc')->SearchName($this->search)->paginate($this->perPage);
        $specializations = Specialization::get();

        // return the view with the students and specializations
        return view('livewire.Coordinator.manage-student-accounts', compact('users', 'specializations'))
            ->layout('layouts.jobapplication', [
                "description" => "Manage all the Student Accounts",
                "title" => __('crud.manage') . " " . trans_choice('studentAccounts.students', 2)
            ]);
    }
}
