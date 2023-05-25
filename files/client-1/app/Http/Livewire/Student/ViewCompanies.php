<?php

namespace App\Http\Livewire\Student;

use App\Models\Company;
use App\Models\Language;
use App\Models\LanguageList;
use App\Models\Recruiter;
use App\Models\Specialization;
use App\Models\SpecializationList;
use Livewire\Component;
use Livewire\WithPagination;

class ViewCompanies extends Component
{
    use WithPagination;
    public $orderBy = 'companyName';
    public $orderAsc = true;
    public $name;
    public $perPage=5;
    public $specializationId;
    public $languageId;

    public $detailedCompany = [
      'companyName' => "",
      'website' => "",
      'description' => ""
    ];
    // resort the companies by the given column
    public function resort($column)
    {
        if ($this->orderBy === $column) {
            $this->orderAsc = !$this->orderAsc;
        } else {
            $this->orderAsc = true;
        }
        $this->orderBy = $column;
    }

    public function updatedSearch($propertyName, $propertyValue)
    {
        // dump($propertyName, $propertyValue);
        if (in_array($propertyName, ['perPage', 'name']))
            $this->resetPage();
    }
    //mount specializations, specializationLists

    public function render()
    {

        $query = Company::with('specializations')
            ->with('specializations.specialization')
            ->withCount("recruiters")
            ->with('recruiters.languageLists')
            ->with('recruiters.languageLists.language')->distinct()
            ->orderBy($this->orderBy, $this->orderAsc ? 'asc' : 'desc');

        if($this->specializationId != 0){
            //dd(1);
            $companyIds = SpecializationList::where("specialization_id","=",$this -> specializationId)
            ->get()
            ->toArray();
            $query->whereIn('id',array_column($companyIds,'company_id'));
        }

        if ($this->languageId != 0) {
            $query->whereHas('recruiters.languageLists.language', function ($query) {
                $query->where('language_id', $this->languageId);
            });
        }

        $companies=$query
            ->where('companyName','like','%'.$this->name.'%')
            ->paginate($this->perPage);
        //dd($companies);

        $languages = Language::get();
        $specializations = Specialization::get();

        return view('livewire.Student.view-companies',compact('companies','specializations','languages'))
            ->layout('layouts.jobapplication',[
                "description" => "View Companies",
                "title" => "View Companies"
            ]);
    }
}
