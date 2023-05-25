<div>
    <div class="fixed top-0 left-1/2 transform -translate-x-1/2" style="z-index: 500" wire:loading>
        <x-tmk.preloader class="bg-green-100 text-green-700 border border-green-700" />
    </div>
    <x-tmk.section >
        <div class="flex flex-col w-full md:flex-row md:w-auto justify-between">
            <x-button wire:click="setNewCompany()">
                {{ trans_choice('crud.new',1) . ' ' . __('companyAccounts.company') }}</x-button>
            <div class="flex flex-col w-full md:flex-row md:w-auto">
                <div x-data="{ companyFilter: @entangle('companyFilter') }" class="w-full">
                    <x-label for="companyFilter"  >Filter</x-label>
                    <x-input id="companyFilter" type="text"
                             x-model.debounce.500ms="companyFilter"
                             wire:model.debounce.500ms="companyFilter"
                             class="block mt-1 w-full"
                             data-tippy-content=""/>
                </div>

                <div class="w-full md:mx-2">
                    <x-label for="perPage" value="Companies per page" />
                    <x-tmk.form.select id="perPage" wire:model="perPage" class="block mt-1 w-full" >
                        @foreach([5, 10, 15, 20, 25] as $companiesPerPage)
                            <option value="{{$companiesPerPage}}">{{$companiesPerPage}}</option>
                        @endforeach
                    </x-tmk.form.select>
                </div>
            </div>
        </div>
    </x-tmk.section>

    <x-tmk.section class="hidden md:block mt-3">
        <table class="text-center w-full border border-gray-300 mb-2">
            <colgroup>
                <col class="w-14">
                <col class="w-48">
                <col class="w-20">
                <col class="w-10">
            </colgroup>
            <thead>
            <tr class="bg-gray-100 text-gray-700 [&>th]:p-2">
                <th class="text-center cursor-pointer" wire:click="resort('companyName')">
                    <span data-tippy-content="Order by Company Name">{{ __('companyAccounts.companyName') }}</span>
                    <x-heroicon-s-chevron-up
                        class="w-5 text-slate-400
                            {{ $orderAsc ?: 'rotate-180' }} {{ $orderBy === 'companyName' ? 'inline-block' : 'hidden' }} " />
                </th>

                <th class="text-center">
                    <span>{{ __('companyAccounts.description') }}</span>
                </th>
                <th class="text-center">
                    <span>{{ __('companyAccounts.website') }}</span>
                </th>
                <th class="text-left"></th>
            </tr>
            </thead>
            <tbody>
            @foreach ($companies as $company)
                <tr class="border border-gray-300 [&>td]:p-3" wire:key="company_{{ $company->id }}">
                    <td>{{ $company->companyName }}</td>
                    <td>{{ $company->description }}</td>
                    <td>{{ $company->website }}</td>
                    <td class="float-left">
                        <div
                            class="flex gap-1 justify-center [&>*]:cursor-pointer [&>*]:outline-0 [&>*]:transition">
                            <button wire:click="setNewCompany({{ $company->id }})">
                                <x-phosphor-pencil-line-duotone class="w-5 text-gray-300 hover:text-green-600" />
                            </button>
                            <button wire:click="setDeleteCompany({{ $company->id }})">
                                <x-phosphor-trash-duotone class="w-5 text-gray-300 hover:text-red-600" />
                            </button>
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        @if ($companies->isEmpty())
            <x-tmk.form.alert type="danger" class="w-full">
                Can't find any Companies.
            </x-tmk.form.alert>
        @endif
        {{-- $companies->links('vendor.pagination.tailwind') --}}

    {{--Mobile view for the companies--}}
    </x-tmk.section>
    <div class="block md:hidden mt-3">
        @foreach($companies as $company)
        <x-tmk.section class="mb-3">
            <div class="flex flex-col justify-center align-middle">
                <img class="rounded-full h-32 w-full"
                    src="https://ui-avatars.com/api/?name={{ $company->companyName  }}"
                     alt="{{ $company->companyName }}">

                <div class="[&>*]:p-2">
                    <div>
                        Company name: {{$company->companyName}}
                    </div>
                    <div>
                        Company website: <a href="{{$company->website}}">{{$company->website}}</a>
                    </div>
                    <div>
                        Company description: {{$company->description}}
                    </div>
                </div>
                <div
                    class="flex gap-1 justify-end [&>*]:cursor-pointer [&>*]:outline-0 [&>*]:transition">
                    <button wire:click="setNewCompany({{ $company->id }})">
                        <x-phosphor-pencil-line-duotone class="w-5 text-gray-300 hover:text-green-600" />
                    </button>
                    <button wire:click="setDeleteCompany({{ $company->id }})">
                        <x-phosphor-trash-duotone class="w-5 text-gray-300 hover:text-red-600" />
                    </button>
                </div>
            </div>
        </x-tmk.section>
        @endforeach
    </div>
    {{-- The modal for deleting a company --}}
    <x-confirmation-modal id="deleteModal" wire:model="showDeleteModal">

        <x-slot name="title">
            <h2>{{ __('crud.delete') . ' ' . trans_choice('companyAccounts.companies', 1) . ' ' . $newCompany['companyName'] }}
            </h2>
        </x-slot>
        <x-slot name="content">
            <p>{{ __('companyAccounts.deleteMessage') }}</b></p>
        </x-slot>
        <x-slot name="footer">
            <x-secondary-button @click="show = false">{{ __('crud.cancel') }}</x-secondary-button>
            <x-danger-button class="ml-2" wire:click="deleteCompany({{ $newCompany['id'] }})">
                {{ __('crud.delete') . ' ' . trans_choice('companyAccounts.companies', 1) }}
            </x-danger-button>
        </x-slot>
    </x-confirmation-modal>

    {{-- The modal for adding or editing a company --}}
    <x-dialog-modal id="companyModal" wire:model="showEditModal">
        <x-slot name="title">
            <h2>{{ is_null($newCompany['id']) ? __('crud.new') . ' ' . trans_choice('companyAccounts.companies', 1) : __('crud.edit') . ' ' . trans_choice('companyAccounts.companies', 1) }}
            </h2>
        </x-slot>
        <x-slot name="content">
            @if ($errors->any())
                <x-tmk.form.alert type="danger">
                    <x-tmk.list>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </x-tmk.list>
                </x-tmk.form.alert>
            @endif
            <div class="flex flex-row gap-4 mt-4">
                <div class="flex-1 flex-col gap-2">
                    <x-label for="name" value="{{ __('companyAccounts.companyName') }}" class="mt-4" />
                    <x-input id="name" type="text" wire:model.defer="newCompany.companyName"
                             class="mt-1 inline w-full" />
                    <x-label for="website" value="{{ __('companyAccounts.website') }}" class="mt-4" />
                    <x-input id="website" type="text" wire:model.defer="newCompany.website"
                             class="mt-1 inline w-full" />
                    <x-label for="description" value="{{ __('companyAccounts.description') }}" class="mt-4" />
                    <x-input id="name" type="text" wire:model.defer="newCompany.description"
                             class="mt-1 inline w-full" />

                </div>
            </div>
        </x-slot>
        <x-slot name="footer">
            <x-secondary-button @click="show = false">{{ __('crud.cancel') }}</x-secondary-button>
            @if (is_null($newCompany['id']))
                <x-button wire:click="createCompany()" wire:loading.attr="disabled" class="ml-2">
                    {{ __('crud.new') }}
                </x-button>
            @else
                <x-button wire:click="updateCompany({{ $newCompany['id'] }})" wire:loading.attr="disabled"
                          class="ml-2">{{ __('crud.save') }}</x-button>
            @endif
        </x-slot>
    </x-dialog-modal>

</div>
