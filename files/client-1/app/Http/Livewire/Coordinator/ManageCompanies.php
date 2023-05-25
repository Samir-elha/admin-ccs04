<?php

namespace App\Http\Livewire\Coordinator;

use App\Http\Middleware\Company;
use App\Models\User;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class ManageCompanies extends Component
{
    use WithPagination;
    public $companyFilter;
    public $orderBy = 'companies.id';
    public $orderAsc = true;
    public $showEditModal = false;
    public $showDeleteModal = false;
    public $perPage = 5;
    protected function rules()
    {
        return [
            'newCompany.companyName' => 'required|string',
            'newCompany.website' => 'required|string',
            'newCompany.description' => 'required|string'
        ];
    }
    protected $validateAttributes = [
        'newCompany.companyName' => 'company name',
        'newCompany.website' => 'website',
        'newCompany.description' => 'description'
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
    public $newCompany = [
            "id" => null,
            "companyName" => "",
            "decription" => "",
            "website" => ""
        ];
    public function updated($propertyName, $propertyValue)
    {
        if (in_array($propertyName, ['search', 'perPage'])) {
            $this->resetPage();
        }
    }
    public function setNewCompany(\App\Models\Company $company = null)
    {
        $this->resetErrorBag();
        if ($company->id) {
            $this->newCompany['id'] = $company->id;
            $this->newCompany['companyName'] = $company->companyName;
            $this->newCompany['website'] = $company->website;
            $this->newCompany['description'] = $company->description;

        } else {
            $this->newCompany['id'] = null;
            $this->newCompany['companyName'] = null;
            $this->newCompany['website'] = null;
            $this->newCompany['description'] = null;
        }
        $this->showEditModal = true;
    }

    public function createCompany(){
        $this->validate();
        $company = \App\Models\Company::create([
            'companyName' => $this->newCompany['companyName'],
            'website' => $this->newCompany['website'],
            'description' => $this->newCompany['description'],
        ]);
        $this->showEditModal = false;
        $this->dispatchBrowserEvent('swal:toast', [
            'background' => 'success',
            'html' => __('companyAccounts.createCompanyToast'),
        ]);
    }
    public function updateCompany(\App\Models\Company $company)
    {
        $this->validate();

        $company->update([
            'companyName' => $this->newCompany['companyName'],
            'website' => $this->newCompany['website'],
            'description' => $this->newCompany['description'],
        ]);

        $this->showEditModal = false;
        $this->dispatchBrowserEvent('swal:toast', [
            'background' => 'success',
            'html' => __('companyAccounts.updateCompanyToast'),
        ]);
    }
    public function setDeleteCompany(\App\Models\Company $company)
    {
        // reset the error bag
        $this->resetErrorBag();

        //set the values of the newCompany array to the values of the given company
        $this->newCompany['id'] = $company->id;
        $this->newCompany['companyName'] = $company->companyName;

        // show the delete modal
        $this->showDeleteModal = true;
    }
    public function deleteCompany(\App\Models\Company $company)
    {

        $company->delete();

        $this->showDeleteModal = false;
        $this->dispatchBrowserEvent('swal:toast', [
            'background' => 'success',
            'html' => __('companyAccounts.deleteCompanyToast'),
        ]);
    }


    public function render()
    {
        App::setLocale(session('locale') ?? 'en');
        $companies = \App\Models\Company::where("companyName", "like", "%". $this->companyFilter ."%")
            ->orderBy($this->orderBy, $this->orderAsc ? 'asc' : 'desc')
            ->paginate($this->perPage);
        return view('livewire.Coordinator.manage-companies',compact('companies'))
            ->layout('layouts.jobapplication',[
                'description'=> 'You can manage companies here',
                'title' => 'Manage Companies'
            ]);
    }
}
