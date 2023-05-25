<div>
    <div class="fixed top-0 left-1/2 transform -translate-x-1/2" style="z-index: 500" wire:loading>
        <x-tmk.preloader class="bg-green-100 text-green-700 border border-green-700" />
    </div>
    <x-tmk.section class="block mt-1 mb-2">
        <div class="grid grid-cols-10 gap-4">
            <div
                x-data="{ name: @entangle('name') }"
                class="col-span-5 md:col-span-3 lg:col-span-2">
                <x-label for="name" value="Filter"/>
                <x-input id="name" type="text"
                             x-model.debounce.500ms="name"
                         wire:model.debounce.500ms="name"
                             class="block mt-1 w-full"
                             placeholder="Filter Companies"/>
                <div
                    x-show="name"
                    @click="name = '';"
                    class="w-5 absolute right-4 top-3 cursor-pointer">
                    <x-phosphor-x-duotone/>
                </div>
            </div>
            <div class="col-span-5 md:col-span-3 lg:col-span-2">
                <x-label for="specialization" value="Specialization"/>
                <x-tmk.form.select id="specialization"
                                   wire:model="specializationId"
                                   class="block mt-1 w-full">
                    <option value="0">-</option>
                    @foreach($specializations as $specialization)
                        <option value="{{$specialization->id}}">{{$specialization->name}}</option>
                    @endforeach
                </x-tmk.form.select>
            </div>
            <div class="col-span-5 md:col-span-3 lg:col-span-2">
                <x-label for="language" value="Language"/>
                <x-tmk.form.select id="language"
                                   wire:model="languageId"
                                   class="block mt-1 w-full">
                    <option value="0">-</option>
                    @foreach($languages as $language)
                        <option value="{{$language->id}}">{{$language->name}}</option>
                    @endforeach
                </x-tmk.form.select>
            </div>
            <div class="col-span-5 md:col-span-3 lg:col-span-2">
                <x-label for="perPage" value="Companies per page"/>
                <x-tmk.form.select id="perPage"
                                   wire:model="perPage"
                                   class="block mt-1 w-full">
                    @foreach([5,10,15,20,25] as $companiesPerPage)
                        <option value="{{$companiesPerPage}}">{{$companiesPerPage}}</option>
                    @endforeach
                </x-tmk.form.select>
            </div>
        </div>
    </x-tmk.section>

<x-tmk.section>

    <div class="my-4">{{ $companies->links() }}</div>
    <div class="hidden lg:block">
    <table class=" text-center w-full border border-gray-300">
        <colgroup>
            <col class="w-max">
            <col class="w-max">
            <col class="w-max">
            <col class="w-max">
            <col class="w-max">
            <col class="w-max">
        </colgroup>
        <thead>
            <tr class="bg-gray-100 text-gray-700 [&>th]:p-2">


                <th wire:click="resort('companyName')" class="text-center cursor-pointer">
                    <span data-tippy-content="Order by Company Name">Company</span>
                    <x-heroicon-s-chevron-up
                        class="w-5 text-slate-400
                     {{$orderAsc ?: 'rotate-180'}}
                     {{$orderBy === 'companyName' ? 'inline-block' : 'hidden'}}"/>
                </th>
                <th class="text-center">
                    <span>Description</span>

                </th>
                <th class="text-center">
                    <span>Website</span>
                </th>
                <th>Recruiters</th>
                <th>Specializations</th>
                <th>Languages</th>
            </tr>
        </thead>

        <tbody>
            @foreach($companies as $company)

            <tr class="border-t border-gray-300 [&>td]:p-3">
                <td>{{ $company->companyName }}</td>
                <td>{{ $company->description }}</td>
                <td>{{ $company->website }}</td>
                <td>{{$company->recruiters_count}}</td>
                <td>
                    @php
                        $uniqueSpecializations = [];
                    @endphp
                    @foreach($company->specializations as $specialization)
                        @if (!in_array($specialization->specialization->name, $uniqueSpecializations))
                            {{$specialization->specialization->name}}
                            @php
                                $uniqueSpecializations[] = $specialization->specialization->name;
                            @endphp
                        @endif
                    @endforeach
                </td>
                <td>
                    @php
                        $uniqueLanguages = [];
                    @endphp
                    @foreach($company->recruiters as $recruiter)
                        @foreach($recruiter->languageLists as $languageList)
                            @if (!in_array($languageList->language->abbreviation, $uniqueLanguages))
                                {{$languageList->language->abbreviation}}
                                @php
                                    $uniqueLanguages[] = $languageList->language->abbreviation;
                                @endphp
                            @endif
                        @endforeach
                    @endforeach
                </td>
            </tr>
            @endforeach
            </tbody>

    </table>
    </div>

    <div class="block lg:hidden">
        <div class="grid grid-cols-1  gap-4 justify-center">
            @foreach($companies as $comp)
                <div class="flex flex-row bg-gray-50 rounded-lg shadow-md p-6 mb-4">
                    <div>
                        <img  class="rounded-full h-32 w-32" src="https://ui-avatars.com/api/?length=1&name={{ $comp->companyName }}" alt="{{ $comp->companyName }}">
                    </div>
                    <div class="flex-grow pl-4">
                        <p class="text-lg font-bold">{{$comp->companyName}}</p>
                        <p class="mt-2">Description: {{$comp->description}}</p>
                        <p></p>
                        <div>Specializations:
                            @php
                                $uniqueSpecializations = [];
                            @endphp
                            @foreach($comp->specializations as $sp)
                                @if (!in_array($sp->specialization->name, $uniqueSpecializations))
                                    {{$sp->specialization->name}}
                                    @php
                                        $uniqueSpecializations[] = $sp->specialization->name;
                                    @endphp
                                @endif
                            @endforeach
                        </div>
                        <div>Languages:
                            @php
                                $uniqueLanguages = [];
                            @endphp
                            @foreach($comp->recruiters as $recruiter)
                                @foreach($recruiter->languageLists as $languageList)
                                    @if (!in_array($languageList->language->abbreviation, $uniqueLanguages))
                                        {{$languageList->language->abbreviation}}
                                        @php
                                            $uniqueLanguages[] = $languageList->language->abbreviation;
                                        @endphp
                                    @endif
                                @endforeach
                            @endforeach</div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    <div class="my-4">{{ $companies->links() }}</div>
    @if($companies->isEmpty())
        <x-tmk.form.alert type="danger" class="w-full">
            Couldn't find any companies.
        </x-tmk.form.alert>
    @endif
</x-tmk.section>


</div>
