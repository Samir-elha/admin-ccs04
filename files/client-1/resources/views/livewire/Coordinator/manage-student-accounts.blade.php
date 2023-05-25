<div>
    <x-tmk.section class="block mt-1 mb-2">
        <div class="p-0 mb-4 flex gap-2 justify-between">
            <x-button wire:click="setNewStudent()" class="h-12 w-44 flex justify-center">
                {{ trans_choice('crud.new', 1) . ' ' . trans_choice('studentAccounts.students', 1) }}</x-button>
            <div class="flex">
                <x-tmk.form.select wire:model="perPage" class="mt-1 flex w-44 mr-1">
                    <option disabled value="">{{ __('crud.perPage') }}</option>
                    @for ($perPageValues = 1; $perPageValues <= 5; $perPageValues++)
                        <option value="{{ $perPageValues * 5 }}">{{ $perPageValues * 5 }}</option>
                    @endfor
                </x-tmk.form.select>
                <x-input id="search" type="text" wire:model.debounce.500ms="search" class="mt-1 w-44"
                    placeholder="{{ __('crud.search') . ' ' . __('studentAccounts.name') }}" />
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
                    <th class="text-center cursor-pointer" wire:click="resort('firstName')">
                        <span
                            data-tippy-content="{{ __('studentAccounts.tippy.name') }}">{{ __('studentAccounts.name') }}</span>
                        <x-heroicon-s-chevron-up
                            class="w-5 text-slate-400
                            {{ $orderAsc ?: 'rotate-180' }} {{ $orderBy === 'firstName' ? 'inline-block' : 'hidden' }} " />
                    </th>
                    <th class="text-center">
                        <span>{{ __('studentAccounts.rNumber') }}</span>
                    </th>
                    <th class="text-center">
                        <span>{{ __('studentAccounts.email') }}</span>
                    </th>
                    <th class="text-center">
                        <span>{{ __('studentAccounts.fieldOfStudy') }}</span>
                    </th>
                    <th class="text-center cursor-pointer" wire:click="resort('appointments_count')">
                        <span
                            data-tippy-content="{{ __('studentAccounts.tippy.appointments') }}">{{ __('studentAccounts.appointments') }}</span>
                        <x-heroicon-s-chevron-up
                            class="w-5 text-slate-400
                            {{ $orderAsc ?: 'rotate-180' }} {{ $orderBy === 'appointments_count' ? 'inline-block' : 'hidden' }} " />
                    </th>
                    <th class="text-left"></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr class="border border-gray-300 [&>td]:p-3" wire:key="student_{{ $user->id }}">
                        <td>{{ $user->firstName . ' ' . $user->lastName }}</td>
                        <td>{{ $user->rNumber }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->Specialization->name }}</td>
                        <td>{{ $user->appointments_count }}</td>
                        <td class="float-left">
                            <div
                                class="flex gap-1 justify-center [&>*]:cursor-pointer [&>*]:outline-0 [&>*]:transition">
                                <button wire:click="setNewStudent({{ $user->id }})">
                                    <x-phosphor-pencil-line-duotone class="w-5 text-gray-300 hover:text-green-600" />
                                </button>
                                <button wire:click="setDeleteStudent({{ $user->id }})">
                                    <x-phosphor-trash-duotone class="w-5 text-gray-300 hover:text-red-600" />
                                </button>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @if ($users->isEmpty())
            <x-tmk.form.alert type="danger" class="w-full">
                Can't find any Students.
            </x-tmk.form.alert>
        @endif
        {{ $users->links('vendor.pagination.tailwind') }}
    </x-tmk.section>

    {{-- The modal for deleting a student --}}
    <x-confirmation-modal id="deleteModal" wire:model="showDeleteModal">
        <x-slot name="title">
            <h2>{{ __('crud.delete') . ' ' . trans_choice('studentAccounts.students', 1) . ' ' . $newStudent['firstName'] . ' ' . $newStudent['lastName'] }}
            </h2>
        </x-slot>
        <x-slot name="content">
            <p>{{ __('studentAccounts.deleteMessage') }}</b></p>
        </x-slot>
        <x-slot name="footer">
            <x-secondary-button @click="show = false">{{ __('crud.cancel') }}</x-secondary-button>
            <x-danger-button class="ml-2" wire:click="deleteStudent({{ $newStudent['id'] }})">
                {{ __('crud.delete') . ' ' . trans_choice('studentAccounts.students', 1) }}
            </x-danger-button>
        </x-slot>
    </x-confirmation-modal>

    {{-- The modal for adding or editing a student --}}
    <x-dialog-modal id="studentModal" wire:model="showEditModal">
        <x-slot name="title">
            <h2>{{ is_null($newStudent['id']) ? __('crud.new') . ' ' . trans_choice('studentAccounts.students', 1) : __('crud.edit') . ' ' . trans_choice('studentAccounts.students', 1) }}
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
                    <x-label for="name" value="{{ __('studentAccounts.name') }}" class="mt-4" />
                    <x-input id="name" type="text" wire:model.defer="newStudent.firstName"
                        class="mt-1 inline w-5/12" />
                    <x-input id="name" type="text" wire:model.defer="newStudent.lastName"
                        class="mt-1 inline w-5/12" />
                    <x-label for="email" value="{{ __('studentAccounts.email') }}" class="mt-4" />
                    <x-input id="email" type="email" wire:model.defer="newStudent.email"
                        class="mt-1 block w-full" />
                    <x-label for="rNumber" value="{{ __('studentAccounts.rNumber') }}" class="mt-4" />
                    <x-input id="rNumber" type="text" wire:model.defer="newStudent.rNumber"
                        class="mt-1 block w-full" />
                    <x-label for="fieldOfStudy" value="{{ __('studentAccounts.fieldOfStudy') }}" class="mt-4" />
                    <select wire:model.defer="newStudent.specialization_id" class="mt-1 block w-full">
                        <option disabled value="null">
                            {{ __('crud.select') . ' ' . __('companyAccounts.specialization') }}</option>
                        @foreach ($specializations as $specialization)
                            <option value="{{ $specialization->id }}">{{ $specialization->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </x-slot>
        <x-slot name="footer">
            <x-secondary-button @click="show = false">{{ __('crud.cancel') }}</x-secondary-button>
            @if (is_null($newStudent['id']))
                <x-button wire:click="createStudent()" wire:loading.attr="disabled" class="ml-2">
                    {{ trans_choice('crud.new', 2) }}
                </x-button>
            @else
                <x-button wire:click="updateStudent({{ $newStudent['id'] }})" wire:loading.attr="disabled"
                    class="ml-2">{{ __('crud.save') }}</x-button>
            @endif
        </x-slot>
    </x-dialog-modal>
</div>
