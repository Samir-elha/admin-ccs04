<div>
    <x-tmk.section>

        <div class="p-0 mb-4 flex gap-2 justify-between">
            <x-button wire:click="setNewContact()" class="h-12 w-44 flex justify-center">
                {{ __('crud.new') . ' ' . trans_choice('companyUsers.name', 1) }}</x-button>


            <div class="flex">
                <x-tmk.form.select id="user" wire:model="user" class="mt-1 flex w-44 mr-1">
                    <option value="" selected>{{ __('companyUsers.allCompanies') }}</option>
                    @foreach ($allCompanies as $company)
                        <option value="{{ $company->id }}">{{$company->companyName}}({{ $company->users_count}})</option>
                    @endforeach
                </x-tmk.form.select>
            </div>
            <div class="flex">
                <x-tmk.form.select wire:model="perPage" class="mt-1 flex w-44 mr-1">
                    <option disabled value="">{{ __('crud.perPage') }}</option>
                    @for ($perPageValues = 1; $perPageValues <= 5; $perPageValues++)
                        <option value="{{ $perPageValues * 5 }}">{{ $perPageValues * 5 }}</option>
                    @endfor
                </x-tmk.form.select>
                <x-input id="search" type="text" wire:model.debounce.500ms="search" class="mt-1 w-44"
                         placeholder="{{ __('crud.search') . ' ' . __('companyUsers.name') }}" />
            </div>
        </div>
        <table class="text-center w-full border border-gray-300 mb-2">
            <thead>
            <tr class="bg-gray-100 text-gray-700 [&>th]:p-2">
                <th class="text-center cursor-pointer" wire:click="resort('companyName')">
                    <span data-tippy-content="Order by Name">{{ __('companyUsers.name') }}</span>
                    <x-heroicon-s-chevron-up
                        class="w-5 text-slate-400
                        {{ $orderAsc ?: 'rotate-180' }} {{ $orderBy === 'firstName' ? 'inline-block' : 'hidden' }} " />
                </th>

                <th class="text-center">
                    <span>{{ __('companyUsers.email') }}</span>
                </th>
                <th class="text-center">
                    <span>{{ __('companyUsers.companyName') }}</span>
                </th>
                <th class="text-left"></th>
            </tr>
            </thead>
            <tbody>
            @foreach ($contacts as $user)
                <tr class="border border-gray-300 [&>td]:p-3" wire:key="contact_{{ $user->id }}">
                    <td>{{ $user->firstName . ' ' . $user->lastName }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->Company->companyName }}</td>
                    <td class="float-left">
                        <div
                            class="flex gap-1 justify-center [&>*]:cursor-pointer [&>*]:outline-0 [&>*]:transition">
                            <button wire:click="setNewContact({{ $user->id }})">
                                <x-phosphor-pencil-line-duotone class="w-5 text-gray-300 hover:text-green-600" />
                            </button>
                            <button wire:click="setDeleteContact({{ $user->id }})">
                                <x-phosphor-trash-duotone class="w-5 text-gray-300 hover:text-red-600" />
                            </button>
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        @if ($contacts->isEmpty())
            <x-tmk.form.alert type="danger" class="w-full">
                Can't find any Contacts.
            </x-tmk.form.alert>
        @endif
        {{ $contacts->links() }}
    </x-tmk.section>

    {{-- The modal for deleting a contact person --}}
    <x-confirmation-modal id="deleteModal" wire:model="showDeleteModal">

        <x-slot name="title">
            <h2>{{ __('crud.delete') . ' ' . trans_choice('companyUsers.contacts', 1) . ' ' . $newContact['firstName'] . ' ' . $newContact['lastName']  }}
            </h2>
        </x-slot>
        <x-slot name="content">
            <p>{{ __('companyUsers.deleteMessage') }}</b></p>
        </x-slot>
        <x-slot name="footer">
            <x-secondary-button @click="show = false">{{ __('crud.cancel') }}</x-secondary-button>
            <x-danger-button class="ml-2" wire:click="deleteContact({{ $newContact['id'] }})">
                {{ __('crud.delete') . ' ' . trans_choice('companyUsers.contacts', 1) }}
            </x-danger-button>
        </x-slot>
    </x-confirmation-modal>

    {{-- The modal for adding or editing a contact person --}}
    <x-dialog-modal id="companyModal" wire:model="showEditModal">
        <x-slot name="title">
            <h2>{{ is_null($newContact['id']) ? __('crud.new') . ' ' . trans_choice('companyUsers.contacts', 1) : __('crud.edit') . ' ' . trans_choice('companyUsers.contacts', 1) }}
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
                        <x-label for="name" value="{{ __('companyUsers.name') }}" class="mt-4" />
                        <x-input id="name" type="text" wire:model.defer="newContact.firstName"
                                 class="mt-1 inline w-5/12" />
                        <x-input id="name" type="text" wire:model.defer="newContact.lastName"
                                 class="mt-1 inline w-5/12" />
                        <x-label for="email" value="{{ __('companyUsers.email') }}" class="mt-4" />
                        <x-input id="email" type="email" wire:model.defer="newContact.email"
                                 class="mt-1 block w-full" />
                        <x-label for="company_id" value="{{ __('companyUsers.companyName') }}" class="mt-4" />
                        <x-tmk.form.select id="company_id" wire:model="newContact.company_id" class="mt-1 flex w-44 mr-1">
                            <option disabled value="null">
                                {{__('crud.select') . '' . __('companyUsers.companyName')}}
                            </option>
                            @foreach($companies as $company)
                                <option value="{{ $company->id }}">{{ $company->companyName }}</option>
                            @endforeach
                        </x-tmk.form.select>
                    </div>
                </div>
        </x-slot>
        <x-slot name="footer">
            <x-secondary-button @click="show = false">{{ __('crud.cancel') }}</x-secondary-button>
            @if (is_null($newContact['id']))
                <x-button wire:click="createContact()" wire:loading.attr="disabled" class="ml-2">
                    {{ __('crud.new') }}
                </x-button>
            @else
                <x-button wire:click="updateContact({{ $newContact['id'] }})" wire:loading.attr="disabled"
                          class="ml-2">{{ __('crud.save') }}</x-button>
            @endif
        </x-slot>
    </x-dialog-modal>
</div>
