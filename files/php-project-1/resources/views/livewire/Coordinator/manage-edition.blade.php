<div>
    <x-tmk.section class="block mt-1 mb-2">
        <div class="p-0 mb-4 flex gap-2">
            <x-button wire:click="setNewEdition()" class="h-12 w-44 flex justify-center">
                {{ __('crud.new') . ' ' . trans_choice('editions.editions', 1) }}
            </x-button>
        </div>

        <table class="text-center w-full border border-gray-300">
            <colgroup>
                <col class="w-14">
                <col class="w-14">
                <col class="w-20">
                <col class="w-48">
                <col class="w-10">
                <col class="w-10">
            </colgroup>
            <thead>
                <tr class="bg-gray-100 text-gray-700 [&>th]:p-2">
                    <th class="text-center cursor-pointer" wire:click="resort('name')">
                        <span data-tippy-content="Order by Edition Name">{{ __('editions.name') }}</span>
                        <x-heroicon-s-chevron-up
                            class="w-5 text-slate-400
                            {{ $orderAsc ?: 'rotate-180' }} {{ $orderBy === 'name' ? 'inline-block' : 'hidden' }} " />
                    </th>
                    <th class="text-center cursor-pointer" wire:click="resort('date')">
                        <span data-tippy-content="Order by Edition Date">{{ __('editions.date') }}</span>
                        <x-heroicon-s-chevron-up
                            class="w-5 text-slate-400
                            {{ $orderAsc ?: 'rotate-180' }} {{ $orderBy === 'date' ? 'inline-block' : 'hidden' }} " />
                    </th>
                    <th class="text-center">
                        <span>{{ __('editions.#OfAppointments') }}</span>
                    </th>
                    <th class="text-center">
                        <span>{{ __('editions.deadline') }}</span>
                    </th>
                    <th class="text-center">{{ __('editions.active') }}</th>
                    <th class="text-left"></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($editions as $edition)
                    @if ($edition->isActive == 1)
                        <tr class="border border-gray-300 [&>td]:p-3 bg-green-100"
                            wire:key="edition_{{ $edition->id }}">
                        @else
                        <tr class="border border-gray-300 [&>td]:p-3" wire:key="edition_{{ $edition->id }}">
                    @endif

                    <td>{{ $edition->name }}</td>
                    <td>{{ \Carbon\Carbon::parse($edition->date)->format('D j-n-Y') }}</td>
                    <td>{{ $edition->numberOfAppointments }}</td>
                    <td>{{ \Carbon\Carbon::parse($edition->deadline)->format('D j-n-Y H:i') }}</td>
                    <td>
                        <x-button wire:click="setMakeActive({{ $edition->id }})">
                            {{ __('editions.makeActive') }}
                        </x-button>
                    </td>
                    <td class="float-left">
                        <div class="flex gap-1 justify-center [&>*]:cursor-pointer [&>*]:outline-0 [&>*]:transition">
                            <button wire:click="setNewEdition({{ $edition->id }})">
                                <x-phosphor-pencil-line-duotone class="w-5 text-gray-300 hover:text-green-600" />
                            </button>
                            <button wire:click="setDeleteEdition({{ $edition->id }})">
                                <x-phosphor-trash-duotone class="w-5 text-gray-300 hover:text-red-600" />
                            </button>
                        </div>
                    </td>
                    </tr>
                @endforeach
            </tbody>

        </table>
        @if ($editions->isEmpty())
            <x-tmk.form.alert type="danger" class="w-full">
                Can't find any editions.
            </x-tmk.form.alert>
        @endif
    </x-tmk.section>

    {{-- The modal for a delete error of an edition --}}
    <x-confirmation-modal id="deleteErrorModal" wire:model="showDeleteErrorModal">
        <x-slot name="title">
            <h2>{{ __('crud.delete') . ' ' . trans_choice('editions.editions', 1) }}</h2>
        </x-slot>
        <x-slot name="content">
            <p>{{ __('editions.deleteErrorMessage') }}</b></p>
        </x-slot>
        <x-slot name="footer">
            <x-secondary-button @click="show = false">{{ __('crud.cancel') }}</x-secondary-button>
        </x-slot>
    </x-confirmation-modal>

    {{-- The modal for deleting an edition --}}
    <x-confirmation-modal id="deleteModal" wire:model="showDeleteModal">
        <x-slot name="title">
            <h2>{{ __('crud.delete') . ' ' . trans_choice('editions.editions', 1) }}</h2>
        </x-slot>
        <x-slot name="content">
            <p>{{ __('editions.deleteMessage') }}</b></p>
        </x-slot>
        <x-slot name="footer">
            <x-secondary-button @click="show = false">{{ __('crud.cancel') }}</x-secondary-button>
            <x-danger-button class="ml-2" wire:click="deleteEdition({{ $newEdition['id'] }})">
                {{ __('crud.delete') . ' ' . trans_choice('editions.editions', 1) }}
            </x-danger-button>
        </x-slot>
    </x-confirmation-modal>

    {{-- The modal for making an edition active --}}
    <x-confirmation-modal id="makeActiveModal" wire:model="showMakeActiveModal">
        <x-slot name="title">
            <h2>{{ __('editions.makeActive') }}</h2>
        </x-slot>
        <x-slot name="content">
            @if ($newEdition['isActive'] == 1)
                <p>{!! __('editions.alreadyActive') !!}</p>
            @else
                <p>{!! __('editions.makeActiveMessage') !!}</p>
            @endif

        </x-slot>
        <x-slot name="footer">
            <x-secondary-button @click="show = false">{{ __('crud.cancel') }}</x-secondary-button>
            <x-danger-button class="ml-2" wire:click="makeActive({{ $newEdition['id'] }})">
                {{ __('crud.confirm') }}
            </x-danger-button>
        </x-slot>
    </x-confirmation-modal>

    {{-- The modal for adding or editing an edition --}}
    <x-dialog-modal id="editionModal" wire:model="showEditModal">
        <x-slot name="title">
            <h2>{{ is_null($newEdition['id']) ? __('crud.new') . ' ' . trans_choice('editions.editions', 1) : __('crud.edit') . ' ' . trans_choice('editions.editions', 1) }}
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
                    <x-label for="name" value="{{ __('editions.name') }}" class="mt-4" />
                    <x-input id="name" type="text" wire:model.defer="newEdition.name"
                        class="mt-1 block w-full" />
                    <x-label for="date" value="{{ __('editions.date') }}*" class="mt-4" />
                    <x-input id="date" type="date" wire:model.defer="newEdition.date"
                        class="mt-1 block w-full" />
                    <x-label for="numberOfAppointments" value="{{ __('editions.#OfAppointments') }}*" class="mt-4" />
                    <x-input id="numberOfAppointments" type="number" step="1"
                        wire:model.defer="newEdition.numberOfAppointments" class="mt-1 block w-full" />
                    <x-label for="deadline" value="{{ __('editions.deadline') }}*" class="mt-4" />
                    <x-input id="deadline" type="datetime-local" wire:model.defer="newEdition.deadline"
                        class="mt-1 block w-full" />
                </div>
            </div>
        </x-slot>
        <x-slot name="footer">
            <x-secondary-button @click="show = false">{{ __('crud.cancel') }}</x-secondary-button>
            @if (is_null($newEdition['id']))
                <x-button wire:click="createEdition()" wire:loading.attr="disabled" class="ml-2">{{ __('crud.new') }}
                </x-button>
            @else
                <x-button wire:click="updateEdition({{ $newEdition['id'] }})" wire:loading.attr="disabled"
                    class="ml-2">{{ __('crud.save') }}</x-button>
            @endif
        </x-slot>
    </x-dialog-modal>
</div>
