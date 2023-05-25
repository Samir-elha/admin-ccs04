<div>
    <div class="fixed top-0 left-1/2 transform -translate-x-1/2" style="z-index: 500" wire:loading>
        <x-tmk.preloader class="bg-green-100 text-green-700 border border-green-700" />
    </div>
    <x-tmk.section >
        <div class="flex flex-col w-full md:flex-row md:w-auto">
            <div x-data="{ announcementFilter: @entangle('announcementFilter') }" class="w-full">
                <x-label for="announcementFilter"  >Filter {{trans_choice('manage-announcements.Announcements',1)}}</x-label>
                <x-input id="announcementFilter" type="text"
                         x-model.debounce.500ms="announcementFilter"
                         wire:model.debounce.500ms="announcementFilter"
                         class="block mt-1 w-full"
                         data-tippy-content="{{__('manage-announcements.Filter announcement')}}"/>
            </div>

            <div class="w-full md:mx-2">
                <x-label for="perPage" value="{{__('manage-announcements.per page')}}" />
                <x-tmk.form.select id="perPage" wire:model="perPage" class="block mt-1 w-full" >
                    @foreach([5, 10, 15, 20, 25] as $announcementsPerPage)
                        <option value="{{$announcementsPerPage}}">{{$announcementsPerPage}}</option>
                    @endforeach
                </x-tmk.form.select>
            </div>
        </div>
    </x-tmk.section>
    <div class="mt-3">
    @if ($announcements)
        @foreach ($announcements as $announcement)
            @php
                $carbonDate = \Carbon\Carbon::parse($announcement->postDate);
            @endphp
            <div class="max-w-full bg-white rounded-lg shadow-lg overflow-hidden mb-2">
                <div class="p-4">
                    <button class="text-2xl font-bold mb-2"
                            data-tippy-content="{{__('viewAnnouncements.click name')}}"
                        wire:click="setDetailAnnouncement({{ $announcement->id }})">{{ $announcement->name }}</button>

                    <div class="flex justify-between items-center mb-2">
                        <p class="text-gray-500">{{ __('viewAnnouncements.Post Date') }}:
                                {{ $carbonDate->format('M d, Y h:i A') }}
                        </p>
                    </div>
                </div>
            </div>
        @endforeach
        <div class="my-4">{{ $announcements->links('vendor.pagination.tailwind') }}</div>
    @else
        <div class="max-w-full bg-white rounded-lg shadow-lg overflow-hidden mb-2">
            <div class="p-4">
                <p class="text-gray-700 mb-4">{{ __('viewAnnouncements.noAnnouncements') }}</p>
            </div>
        </div>
    @endif
        @if($announcements->isEmpty())
            <x-tmk.form.alert type="danger" class="w-full">
                Couldn't find any announcements.
            </x-tmk.form.alert>
        @endif
    </div>

    {{-- detailed announcement modal --}}
    <x-dialog-modal id="announcementDetailedModal" wire:model="showDetailedModal">
        <x-slot name="title">{{ $setAnnouncement['name'] }}</x-slot>
        <x-slot name="content">
            <x-tmk.section class="p-4">
                {{ $setAnnouncement['content'] }}
            </x-tmk.section>

            @if(sizeof($files)>0)
                <x-tmk.section class="mt-3">
                    <h3>Files:</h3>
                    @foreach($files as $file)
                        @if($file->announcement_id == $setAnnouncement['id'])
                            @if($file->isVisible == true)
                                <div class="flex justify-between">
                                    <p>{{ $file->fileName }}</p>
                                    <div class="flex">
                                        <a href="{{ Storage::url($file->fileUname) }}" target="download"><x-phosphor-download-duotone class="w-5 text-gray-300 hover:text-blue-600"/></a>
                                        <button wire:click="setViewFile({{ $file}})">
                                            <x-phosphor-eye-duotone class="w-5 text-gray-300 hover:text-blue-600" />
                                        </button>
                                    </div>
                                </div>
                            @endif
                        @endif
                    @endforeach
                </x-tmk.section>
            @endif
        </x-slot>
        <x-slot name="footer">
            <div class="mr-3 w-full flex">
                <div>
                    {{ $setAnnouncement['firstName'] . ' ' . $setAnnouncement['lastName'] }} ∙
                    {{ $setAnnouncement['postDate'] }}
                </div>
            </div>
            <div>
                <x-secondary-button class="" @click="show = false">{{ __('crud.cancel') }}</x-secondary-button>
            </div>
        </x-slot>
    </x-dialog-modal>


        {{-- The modal for viewing a file --}}
        <x-dialog-modal id="viewFileModal"  wire:model="showViewModal" maxWidth="auto">
            <x-slot name="title">
                <h2>{{ __('files.view') }}</h2>
            </x-slot>
            <x-slot name="content">
                <p class="text-sky-400 mb-4 text-xl">{{ $originalFileName }}</p>
                {!!$contentViewerPlayer!!}
            </x-slot>
            <x-slot name="footer">
                <x-secondary-button wire:click="closeViewModal()">{{ __('files.close') }}</x-secondary-button>
            </x-slot>
        </x-dialog-modal>
</div>
