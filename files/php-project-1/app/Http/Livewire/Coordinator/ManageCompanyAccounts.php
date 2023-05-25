<?php

namespace App\Http\Livewire\Coordinator;

use App\Models\Company;
use App\Models\User;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Livewire\WithPagination;

use Livewire\Component;

class ManageCompanyAccounts extends Component
{
    use WithPagination;

    public $search;

    public $perPage = 10;

    public $orderBy = 'companies.id';
    public $orderAsc = true;

    public $showEditModal = false;
    public $showDeleteModal = false;

    /**
     * Array that contains the values for a new or updated version of the student
     * @var array
     */
    public $newCompany = [
        'id' => null,
        'companyName' => null,
        'website' => null,
        'description' => null,
        // 'specializations' => null,
    ];

    public $newContactPerson = [
        'id' => null,
        'firstName' => null,
        'lastName' => null,
        'email' => null,
        'type_id' => 2,
        'emailNotification' => true,
    ];

    /**
     * The validation rules.
     * @return array
     * @see https://laravel.com/docs/8.x/validation#available-validation-rules
     */
    protected function rules()
    {
        return [
            'newCompany.companyName' => 'required|string',
            'newCompany.website' => 'required|string',
            'newCompany.description' => 'required|string',
            // 'newCompany.specializations' => 'required|integer',
            'newContactPerson.firstName' => 'required|string',
            'newContactPerson.lastName' => 'required|string',
            'newContactPerson.email' => 'required|email',
            'newContactPerson.type_id' => 'required|integer',
            'newContactPerson.emailNotification' => 'required|boolean',
        ];
    }

    /**
     * The attributes that should be used for validation.
     * @var array
     * @see https://laravel.com/docs/8.x/validation#customizing-the-error-messages
     */
    protected $validateAttributes = [
        'newCompany.companyName' => 'company name',
        'newCompany.website' => 'website',
        'newCompany.description' => 'description',
        // 'newCompany.specializations' => 'specializations',

        'newContactPerson.firstName' => 'first name',
        'newContactPerson.lastName' => 'last name',
        'newContactPerson.email' => 'email',
        'newContactPerson.type_id' => 'type',
        'newContactPerson.emailNotification' => 'email notification',
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
     * Function to show the edit modal
     * @param Company $company
     * @param mixed $propertyValue
     */
    public function updated($propertyName, $propertyValue)
    {
        if (in_array($propertyName, ['search', 'perPage'])) {
            $this->resetPage();
        }
    }

    /**
     * Set the values of the newCompany array to the values of the given company.
     * If the given company is null, then set the values to default.
     * @param Company $company
     * @return void
     */
    public function setNewCompany(Company $company = null)
    {
        $this->resetErrorBag();
        if ($company->id) {

            $contactPerson = DB::table('users')->where('id', '=', $company->user_id)->get()[0];

            $this->newCompany['id'] = $company->id;
            $this->newCompany['companyName'] = $company->companyName;
            $this->newCompany['website'] = $company->website;
            $this->newCompany['description'] = $company->description;
            // $this->newCompany['specializations'] = $company->specializations;
            $this->newContactPerson['id'] = $contactPerson->id;
            $this->newContactPerson['firstName'] = $contactPerson->firstName;
            $this->newContactPerson['lastName'] = $contactPerson->lastName;
            $this->newContactPerson['email'] = $contactPerson->email;
            $this->newContactPerson['type_id'] = $contactPerson->type_id;
            $this->newContactPerson['emailNotification'] = $contactPerson->emailNotification;
        } else {
            $this->newCompany['id'] = null;
            $this->newCompany['companyName'] = null;
            $this->newCompany['website'] = null;
            $this->newCompany['description'] = null;
            // $this->newCompany['specializations'] = null;

            $this->newContactPerson['id'] = null;
            $this->newContactPerson['firstName'] = null;
            $this->newContactPerson['lastName'] = null;
            $this->newContactPerson['email'] = null;
            $this->newContactPerson['type_id'] = 2;
            $this->newContactPerson['emailNotification'] = true;
        }
        $this->showEditModal = true;
    }

    /**
     * Create a new student and add it to the database.
     * @return void
     */
    public function createCompany()
    {
        // $this->newStudent['type_id'] = 1;
        $this->validate();

        $contactPerson = User::create([
            'firstName' => $this->newContactPerson['firstName'],
            'lastName' => $this->newContactPerson['lastName'],
            'email' => $this->newContactPerson['email'],
            'type_id' => $this->newContactPerson['type_id'],
            'emailNotification' => $this->newContactPerson['emailNotification'],
        ]);

        $company = Company::create([
            'companyName' => $this->newCompany['companyName'],
            'website' => $this->newCompany['website'],
            'description' => $this->newCompany['description'],
            'user_id' => $contactPerson['id'],
        ]);

        $this->showEditModal = false;
        $this->dispatchBrowserEvent('swal:toast', [
            'background' => 'success',
            'html' => __('companyAccounts.createCompanyToast'),
        ]);
    }

    /**
     * Update the given company with the values in the newCompany array.
     * @param Company $company
     * @return void
     */
    public function updateCompany(Company $company)
    {
        $this->validate();

        $contactPerson = User::where('id', '=', $company->user_id)->first();

        $company->update([
            'companyName' => $this->newCompany['companyName'],
            'website' => $this->newCompany['website'],
            'description' => $this->newCompany['description'],
        ]);

        $contactPerson->update([
            'firstName' => $this->newContactPerson['firstName'],
            'lastName' => $this->newContactPerson['lastName'],
            'email' => $this->newContactPerson['email'],
            'type_id' => $this->newContactPerson['type_id'],
            'emailNotification' => $this->newContactPerson['emailNotification'],
        ]);

        $this->showEditModal = false;
        $this->dispatchBrowserEvent('swal:toast', [
            'background' => 'success',
            'html' => __('companyAccounts.updateCompanyToast'),
        ]);
    }

    /**
     * Set the values of the newCompany array to the values of the given company.
     * @param Company $company
     * @return void
     */
    public function setDeleteCompany(Company $company)
    {
        // reset the error bag
        $this->resetErrorBag();

        //set the values of the newCompany array to the values of the given company
        $this->newCompany['id'] = $company->id;
        $this->newCompany['companyName'] = $company->companyName;

        // show the delete modal
        $this->showDeleteModal = true;
    }

    /**
     * Delete the given company.
     * @param Company $company
     * @return void
     */
    public function deleteCompany(Company $company)
    {
        $contactPerson = User::where('id', '=', $company->user_id)->first();

        $company->delete();
        $contactPerson->delete();

        $this->showDeleteModal = false;
        $this->dispatchBrowserEvent('swal:toast', [
            'background' => 'success',
            'html' => __('companyAccounts.deleteCompanyToast'),
        ]);
    }

    /**
     * The main function that renders the page
     * @return view
     */
    public function render()
    {
        App::setLocale(session('locale') ?? 'en');
        // The join function and withCount function cannot be used together, thats why there are two queries that are almost the same
        $companies = Company::leftJoin('users', 'companies.user_id', '=', 'users.id')
            ->select('companies.*', 'users.firstName', 'users.lastName', 'users.email', 'users.type_id', 'users.active', 'users.emailNotification')->orderBy($this->orderBy, $this->orderAsc ? 'asc' : 'desc')->SearchCompany($this->search)
            ->paginate($this->perPage);
        // $companiesCounted = Company::withCount(['recruiters', 'files'])
        //     ->orderBy($this->orderBy, $this->orderAsc ? 'asc' : 'desc')
        //     ->paginate($this->perPage);

        // dd($companies->toArray());

        return view('livewire.Coordinator.manage-company-accounts', compact('companies'))
            ->layout('layouts.jobapplication', [
                "description" => "Manage all the Company Accounts",
                "title" => __('crud.manage') . " " . trans_choice('companyAccounts.companies', 2)
            ]);
    }
}
