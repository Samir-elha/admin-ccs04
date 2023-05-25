<div>
    <x-tmk.section>
        <div class="p-0 mb-4 flex gap-2 justify-between">
            <x-button wire:click="setNewCompany()" class="h-12 w-44 flex justify-center">
                {{ __('crud.new') . ' ' . trans_choice('companyAccounts.companies', 1) }}</x-button>
            <div class="flex">
                <x-tmk.form.select wire:model="perPage" class="mt-1 flex w-44 mr-1">
                    <option disabled value="">{{ __('crud.perPage') }}</option>
                    @for ($perPageValues = 1; $perPageValues <= 5; $perPageValues++)
                        <option value="{{ $perPageValues * 5 }}">{{ $perPageValues * 5 }}</option>
                    @endfor
                </x-tmk.form.select>
                <x-input id="search" type="text" wire:model.debounce.500ms="search" class="mt-1 w-44"
                    placeholder="{{ __('crud.search') . ' ' . __('companyAccounts.name') }}" />
            </div>
        </div>
        <table class="text-center w-full border border-gray-300 mb-2">
            <colgroup>
                <col class="w-14">
                <col class="w-14">
                <col class="w-20">
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
                        <span>{{ __('companyAccounts.name') }}</span>
                    </th>
                    <th class="text-center">
                        <span>{{ __('companyAccounts.email') }}</span>
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
                @foreach ($companies as $index => $value)
                    <tr class="border border-gray-300 [&>td]:p-3" wire:key="company_{{ $companies[$index]->id }}">
                        <td>{{ $companies[$index]->companyName }}</td>
                        <td>{{ $companies[$index]->firstName . ' ' . $companies[$index]->lastName }}</td>
                        <td>{{ $companies[$index]->email }}</td>
                        <td>{{ $companies[$index]->description }}</td>
                        <td>{{ $companies[$index]->website }}</td>
                        <td class="float-left">
                            <div
                                class="flex gap-1 justify-center [&>*]:cursor-pointer [&>*]:outline-0 [&>*]:transition">
                                <button wire:click="setNewCompany({{ $companies[$index]->id }})">
                                    <x-phosphor-pencil-line-duotone class="w-5 text-gray-300 hover:text-green-600" />
                                </button>
                                <button wire:click="setDeleteCompany({{ $companies[$index]->id }})">
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
        {{ $companies->links('vendor.pagination.tailwind') }}
    </x-tmk.section>

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
                    <x-tmk.form.textarea id="description" type="text" wire:model.defer="newCompany.description"
                        class="mt-1 block w-full" />
                    <x-label for="name" value="{{ __('studentAccounts.name') }}" class="mt-4" />
                    <div class="content-between">
                        <x-input id="name" type="text" wire:model.defer="newContactPerson.firstName"
                            class="mt-1 inline w-5/12" />
                        <x-input id="name" type="text" wire:model.defer="newContactPerson.lastName"
                            class="mt-1 inline w-5/12" />
                    </div>
                    <x-label for="email" value="{{ __('companyAccounts.email') }}" class="mt-4" />
                    <x-input id="email" type="email" wire:model.defer="newContactPerson.email"
                        class="mt-1 block w-full" />
                    <x-label for="emailNotification" value="{{ __('companyAccounts.emailNotification') }}"
                        class="mt-4" />
                    <x-checkbox id="emailNotification" wire:model.defer="newContactPerson.emailNotification"
                        class="mt-1 block" />
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
