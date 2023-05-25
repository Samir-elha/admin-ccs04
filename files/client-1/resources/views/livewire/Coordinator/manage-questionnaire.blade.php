<div>
    <x-tmk.section class="block mt-1 mb-2">
        <div class="p-0 mb-4 flex gap-2">
            <x-button wire:click="setNewQuestionnaire()" class="h-12 w-44 flex justify-center">
                {{ trans_choice('crud.new', 1) . ' ' . trans_choice('questionnaire.questionnaire', 1) }}
            </x-button>
        </div>
        <table class="text-center w-full border border-gray-300">
            <colgroup>
                <col class="w-32">
                <col class="w-32">
                <col class="w-8">
            </colgroup>
            <thead>
                <tr class="bg-gray-100 text-gray-700 [&>th]:p-2">
                    <th class="text-center">{{ __('questionnaire.url') }}</th>
                    <th class="text-center cursor-pointer" wire:click="resort('date')">
                        <span data-tippy-content="Order by Edition Date">{{ __('questionnaire.edition') }}</span>
                        <x-heroicon-s-chevron-up
                            class="w-5 text-slate-400
                            {{ $orderAsc ?: 'rotate-180' }} {{ $orderBy === 'date' ? 'inline-block' : 'hidden' }} " />
                    </th>
                    <th class="text-left"></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($questionnaires as $questionnaire)
                    <tr class="border border-gray-300 [&>td]:p-3" wire:key="questionnaire_{{ $questionnaire->id }}">
                        <td class="text-blue-900 cursor-pointer">
                            <button wire:click="showQuestionnaire({{ $questionnaire->id }})">
                                {{ $questionnaire->url }}
                            </button>
                        </td>

                        <td>{{ $questionnaire->edition->name }}</td>
                        <td>
                            <button wire:click="setDeleteQuestionnaire({{ $questionnaire->id }})">
                                <x-phosphor-trash-duotone class="w-5 text-gray-300 hover:text-red-600" />
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @if ($questionnaires->isEmpty())
            <x-tmk.form.alert type="danger" class="w-full">
                Can't find any questionnaire.
            </x-tmk.form.alert>
        @endif
    </x-tmk.section>

    {{-- The modal for adding a questionnaire --}}
    <x-dialog-modal id="questionnaireModal" wire:model="showCreateModal">
        <x-slot name="title">
            <h2>{{ trans_choice('crud.new', 1) . ' ' . trans_choice('questionnaire.questionnaire', 1) }}
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
                    <x-label for="url" value="{{ __('questionnaire.url') }}" class="mt-4" />
                    <x-input id="url" type="text" wire:model.defer="newQuestionnaire.url"
                        class="mt-1 block w-full" />
                    <x-label for="date" value="{{ __('editions.date') }}*" class="mt-4" />
                    <select wire:model.defer="newQuestionnaire.edition_id" class="mt-1 block w-full">
                        <option value="">{{ __('questionnaire.selectEdition') }}</option>
                        @foreach ($editions as $edition)
                            <option value="{{ $edition->id }}">{{ $edition->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </x-slot>
        <x-slot name="footer">
            <x-secondary-button @click="show = false">{{ __('crud.cancel') }}</x-secondary-button>
            <x-button wire:click="createQuestionnaire()" wire:loading.attr="disabled" class="ml-2">
                {{ trans_choice('crud.new', 2) }}
            </x-button>
        </x-slot>
    </x-dialog-modal>

    {{-- The modal for viewing a questionnaire --}}
    <x-dialog-modal id="viewQuestionnaire" wire:model="showViewModal">
        <x-slot name="title"></x-slot>
        <x-slot name="content">
            <iframe src="{{ $newQuestionnaire['url'] }}" width="100%" height="800px" frameborder="0" marginheight="0"
                marginwidth="0">{{ __('crud.loading') }}</iframe>
        </x-slot>
        <x-slot name="footer">
            <x-secondary-button @click="show = false">{{ __('crud.cancel') }}</x-secondary-button>
        </x-slot>
    </x-dialog-modal>

    {{-- The modal for deleting a questionnaire --}}
    <x-dialog-modal id="deleteQuestionnaire" wire:model="showDeleteModal">
        <x-slot name="title">Delete questionnaire</x-slot>
        <x-slot name="content">
            <p>Are you sure you want to delete the questionnaire?</p>
        </x-slot>
        <x-slot name="footer">
            <x-secondary-button @click="show = false">{{ __('crud.cancel') }}</x-secondary-button>
            <x-button wire:click="deleteQuestionnaire({{ $newQuestionnaire['id'] }})">{{ __('crud.delete') }}
            </x-button>
        </x-slot>
    </x-dialog-modal>
</div>
