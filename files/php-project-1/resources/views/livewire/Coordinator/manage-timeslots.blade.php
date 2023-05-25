<div>

    <div class="p-0 flex gap-2 col-2 mt-auto">
        <x-button wire:click="setNewTimeslot()" class="h-12 w-44 flex justify-center">
            {{ __('timeslots.newTimeslot') }}</x-button>
    </div>

    <table>
        <colgroup>
            <col class="w-40">
            <col class="w-80">
        </colgroup>
        <thead>
            <tr>
                <th></th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            {{-- Generate this pattern with a for loop, start from 8:00 to 20:00 --}}
            @for ($i = 0; $i < 12; $i++)
                <tr>
                    <td class="bg-zinc-500 text-center border border-black">{{ $i + 8 }}:00</td>
                    <td class="text-center border border-black">
                        <div>
                            {{-- Check how many timeslots are between the time --}}
                            @foreach ($timeslots as $timeslot)
                                {{-- Check if the start time or end time of the timeslot falls between the hour, If the end time is fe 16:00 and the start time is 15:00, it will be shown in only the 15:00 hour --}}
                                @if ($timeslot->startTime >= $i + 8 . ':00' && $timeslot->startTime < $i + 9 . ':00' || $timeslot->endTime > $i + 8 . ':01' && $timeslot->endTime <= $i + 9 . ':00')
                                    {{-- <p class="bg-orange-600 border border-black rounded-lg">{{ $timeslot->startTime }}
                                        - {{ $timeslot->endTime }}</p> --}}
                                    {{-- Make it clickable so when the user hovers over it a trash can appears --}}
                                    <div class="relative">
                                        <p class="bg-orange-600 border border-black rounded-lg">
                                            {{ $this->formatTime($timeslot->startTime) }} - {{ $this->formatTime($timeslot->endTime) }}</p>
                                        <div class="absolute top-0 right-0">
                                            <x-button wire:click="deleteTimeslot({{ $timeslot->id }})"
                                                @click="$dispatch('swal:confirm', {
                                                    html: 'Delete {{ $timeslot->startTime }}?',
                                                    cancelButtonText: 'NO!',
                                                    confirmButtonText: 'YES DELETE THIS GENRE',
                                                    next: {
                                                        event: 'delete-genre',
                                                        params: {
                                                            id: {{ $timeslot->id }}
                                                        }
                                                    }
                                                })">
                                                <x-phosphor-trash-duotone class="h-4 w-4" />
                                            </x-button>
                                        </div>
                                @endif
                            @endforeach
                        </div>
                    </td>
                </tr>
            @endfor
        </tbody>
    </table>

    {{-- The modal for adding a timeslot --}}
    <x-dialog-modal id="addModal" wire:model="showAddModal">
        <x-slot name="title">
            <h2>{{ __('timeslots.addTimeslot') }}</h2>
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
            <div class="flex-1 flex-col gap-4">
                <x-label for="timeslotStart" value="{{ __('timeslots.start') }}" class="mt-4" />
                <x-input id="timeslotStart" type="time" wire:model="newTimeslot.startTime"
                    class="mt-1 block w-full" />
                <x-label for="timeslotEnd" value="{{ __('timeslots.end') }}" class="mt-4" />
                <x-input id="timeslotEnd" type="time" wire:model="newTimeslot.endTime"
                    class="mt-1 block w-full" />
            </div>
        </x-slot>
        <x-slot name="footer">
            <x-secondary-button @click="show = false">{{ __('crud.cancel') }}</x-secondary-button>
            <x-button wire:click="addTimeslot()">{{ __('crud.add') }}</x-button>
        </x-slot>
    </x-dialog-modal>
</div>
