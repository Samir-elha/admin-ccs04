<?php

namespace App\Http\Livewire\Coordinator;

use App\Models\Edition;
use App\Models\StudentEdition;
use App\Models\User;
use App\Models\Company;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;
use Livewire\WithPagination;

class ManageCompanyUsers Extends Component
{
    use WithPagination;

    public $search;
    public $user;

    public $perPage = 6;

    public $orderBy = 'firstName';
    public $orderAsc = true;

    public $showEditModal = false;
    public $showDeleteModal = false;


    public $newContact = [
        'id' => null,
        'firstName' => null,
        'lastName' => null,
        'email' => null,
        'company_id' => null,
        'typeID' => 2,
        'emailNotification' => true,
    ];


    protected function rules()
    {
        return [
            'newContact.firstName' => 'required|string',
            'newContact.lastName' => 'required|string',
            'newContact.email' => 'required|email|unique:users,email',
            'newContact.company_id' => 'required|integer',
            'newContact.type_id' => 'required|integer',
            'newContact.emailNotification' => 'required|boolean',
        ];
    }



    protected $validateAttributes = [
        'newContact.firstName' => 'first Name',
        'newContact.lastName' => 'last Name',
        'newContact.email' => 'Email',
        'newContact.company_id' => 'Company',
        'newContact.type_id' => 'Type',
        'newContact.emailNotification' => 'Email Notification',
    ];



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


    public function updated($propertyName, $propertyValue)
    {
        if (in_array($propertyName, ['search', 'perPage', 'orderBy', 'orderAsc'])) {
            $this->resetPage();
        }
    }



    public function setNewContact(User $contact = null)
    {
        $this->resetErrorBag();
        if ($contact->id) {
            $this->newContact['id'] = $contact->id;
            $this->newContact['firstName'] = $contact->firstName;
            $this->newContact['lastName'] = $contact->lastName;
            $this->newContact['email'] = $contact->email;
            $this->newContact['company_id'] = $contact-> company_id;
            $this->newContact['type_id'] = $contact->type_id;
            $this->newContact['emailNotification'] = $contact->emailNotification;
        } else {
            $this->newContact['id'] = null;
            $this->newContact['firstName'] = null;
            $this->newContact['lastName'] = null;
            $this->newContact['email'] = null;
            $this->newContact['company_id'] = null;
            $this->newContact['type_id'] = 2;
            $this->newContact['emailNotification'] = true;
            $this->newContact['password'] = bin2hex(random_bytes(5));
        }
        $this->showEditModal = true;
    }


    public function createContact()
    {
        // Get active edition
        $edition = Edition::where('isActive', true)->first();
        $this->validate();

        $contact = User::create([
            'firstName' => $this->newContact['firstName'],
            'lastName' => $this->newContact['lastName'],
            'email' => $this->newContact['email'],
            'company_id' => $this->newContact['company_id'],
            'type_id' => $this->newContact['type_id'],
            'password' => Hash::make($this->newContact['password'])
        ]);


        $recipient = $contact->email;
        $subject = 'Welcome to the Job Application System';
        Mail::to($recipient)->send(new ResetPassword($contact, $subject, $this->newContact['password']));

        $this->showEditModal = false;
        $this->dispatchBrowserEvent('swal:toast', [
            'background' => 'success',
            'html' => __('companyUsers.createContactToast'),
        ]);

    }


    public function updateContact(User $contact)
    {
        $this->validate();
        $contact->update([
            'firstName' => $this->newContact['firstName'],
            'lastName' => $this->newContact['lastName'],
            'email' => $this->newContact['email'],
            'company_id' => $this->newContact['company_id'],
        ]);
        $this->showEditModal = false;
        $this->dispatchBrowserEvent('swal:toast', [
            'background' => 'success',
            'html' => __('companyUsers.updateContactToast'),
        ]);
    }


    public function setDeleteContact(User $contact)
    {
        // reset the error bag
        $this->resetErrorBag();

        // set the values of the newUser array to the values of the given contact person
        $this->newContact['id'] = $contact->id;
        $this->newContact['firstName'] = $contact->firstName;
        $this->newContact['lastName'] = $contact->lastName;
       /* $this->newContact['company_id'] = $contact->company_id;*/

        // show the delete modal
        $this->showDeleteModal = true;
    }

    public function deleteContact(User $contact)
    {
        $studentEdition = StudentEdition::where('user_id', '=', $contact->id)->first();
        $contact->delete();
        $this->dispatchBrowserEvent('swal:toast', [
            'background' => 'success',
            'html' => __('companyUsers.deleteContactToast'),
        ]);
        $this->showDeleteModal = false;
    }

    public function render()
    {
        $allCompanies = Company::has('users')->withCount('users')->get();
        App::setLocale(session('locale') ?? 'en');
        $usersQuery = User::where('type_id', '=', 2);

        if ($this->user) {
            $usersQuery->where('company_id', '=', $this->user);
        }

        $contacts = $usersQuery->orderBy($this->orderBy, $this->orderAsc ? 'asc' : 'desc')
            ->SearchName($this->search)
            ->paginate($this->perPage);

        $companies = Company::get();

        return view('livewire.Coordinator.manage-company-users', compact('contacts', 'companies','allCompanies'))
            ->layout('layouts.jobapplication', [
                "description" => "Manage all the Contact Person Accounts",
                "title" => __('crud.manage') . " " . trans_choice('companyUsers.companyContacts', 2)
            ]);
    }
}
