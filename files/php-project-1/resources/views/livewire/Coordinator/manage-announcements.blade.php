<div>
    <div class="fixed top-0 left-1/2 transform -translate-x-1/2" style="z-index: 500" wire:loading>
            <x-tmk.preloader class="bg-green-100 text-green-700 border border-green-700" />
    </div>
    {{--filter the annonucements--}}
    <x-tmk.section >
        <div class="p-0 mt-auto flex flex-col md:flex-row gap-2 align-middle justify-between">
            <x-button wire:click="setNewAnnouncement()" data-tippy-content="{{__('manage-announcements.new announcement')}}" class="w-full md:w-auto">{{ trans_choice('crud.new',1) . ' ' . trans_choice('manage-announcements.Announcements', 1) }}</x-button>

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

                <div class="w-full md:mx-2">
                    <x-label for="filterEdition" value="{{__('manage-announcements.choose')}}" />
                    <x-tmk.form.select id="editionFilter" wire:model="editionId" class="block mt-1 w-full" data-tippy-content="{{__('manage-announcements.change edition')}}">
                        <option value="0">{{__('manage-announcements.all')}}</option>
                        @foreach($editions as $edition)
                            <option value="{{$edition->id}}">{{$edition->name}}</option>
                        @endforeach
                    </x-tmk.form.select>
                </div>
            </div>
        </div>

    </x-tmk.section>

    {{--Read all announcements--}}
    <div class="my-4">{{ $announcements->withQueryString()->links() }}</div>
    <div>
    @foreach($announcements as $announcement)
    <div class="max-w-full bg-white rounded-lg shadow-lg overflow-hidden mb-2">
        <div class="p-4">
            <h1 class="text-2xl font-bold mb-2" >{{$announcement -> name}}</h1>
            <p class="text-gray-700 mb-4">{{$announcement -> content}}</p>
            {{--Display related files--}}
            <h2>{{__('manage-announcements.files')}}</h2>
            @foreach($announcement->files as $file)
                <div class="flex">
                    <p>{{$file->fileName}}</p>
                    <div class="flex">
                        <a href="{{ Storage::url($file->fileUname) }}" download="{{ $file->fileName }}" target="download">
                            <x-phosphor-download-duotone class="w-5 text-gray-300 hover:text-black" data-tippy-content="{{__('manage-announcements.download tippy')}}"/></a>
                        <button wire:click="setDeleteFile({{ $file->id }})" data-tippy-content="{{__('manage-announcements.delete tippy')}}">
                            <x-phosphor-trash-duotone class="w-5 text-gray-300 hover:text-red-600" />
                        </button>
                    </div>
                </div>
            @endforeach
            <div class="flex justify-between items-center mt-2">
                <p class="text-gray-500">{{__('manage-announcements.Post Date')}}:
                    @if($announcement -> postDate == null)
                        {{__('manage-announcements.Not yet posted')}}
                    @else
                        {{$announcement -> postDate}}
                    @endif
                </p>
                <div
                    class="flex gap-1 justify-center [&>*]:cursor-pointer [&>*]:outline-0 [&>*]:transition">
                    <button wire:click="setNewAnnouncement({{ $announcement->id }})" data-tippy-content="{{__('manage-announcements.edit tippy')}}">
                        <x-phosphor-pencil-line-duotone class="w-5 text-gray-300 hover:text-green-600" />
                    </button>
                    <button wire:click="setDeleteAnnouncement({{ $announcement->id }})" data-tippy-content="{{__('manage-announcements.delete tippy')}}">
                        <x-phosphor-trash-duotone class="w-5 text-gray-300 hover:text-red-600" />
                    </button>
                    <button wire:click="setUpdateAnnouncementVisibility({{$announcement->id}})" data-tippy-content="{{__('manage-announcements.visibility tippy')}}">
                        <x-far-eye  class="{{ $announcement->isVisible ? 'text-green-600 w-5' : 'text-red-600 w-5' }}"></x-far-eye>
                    </button>
                </div>
            </div>

        </div>
    </div>

    @endforeach
        @if($announcements->isEmpty())
            <x-tmk.form.alert type="danger" class="w-full">
                {{__('manage-announcements.no announcement')}}
            </x-tmk.form.alert>
        @endif
    </div>

    {{-- Delete an announcement --}}
    <x-confirmation-modal id="deleteModal" wire:model="showDeleteAnnouncementModal">
        <x-slot name="title">
            <h2>{{ __('crud.delete') . ' ' . trans_choice('manage-announcements.Announcements', 1) }}</h2>
        </x-slot>
        <x-slot name="content">
            <p>{{ __('manage-announcements.deleteMessage') }}</b></p>
        </x-slot>
        <x-slot name="footer">
            <x-secondary-button @click="show = false">{{ __('crud.cancel') }}</x-secondary-button>
            <x-danger-button class="ml-2" wire:click="deleteAnnouncement({{ $newAnnouncement['id'] }})">
                {{ __('crud.delete') . ' ' . trans_choice('manage-announcements.Announcements', 1) }}
            </x-danger-button>
        </x-slot>
    </x-confirmation-modal>

    {{-- Confirmation to change visibility of an announcement --}}
    <x-confirmation-modal id="updateVisibilityModal" wire:model="showSendEmailModal">
        <x-slot name="title">
            <h2>{{ __('crud.save') . ' ' . trans_choice('manage-announcements.Announcements', 1) }}</h2>
        </x-slot>
        <x-slot name="content">
            <p>{{__('manage-announcements.visibility confirmation')}}</p>
        </x-slot>
        <x-slot name="footer">
            <x-secondary-button @click="show = false">{{ __('crud.cancel') }}</x-secondary-button>
            <x-danger-button class="ml-2" wire:click="updateAnnouncementVisibility({{$newAnnouncement['id']}})">
                {{ __('crud.save') . ' ' . trans_choice('manage-announcements.Announcements', 1) }}
            </x-danger-button>
        </x-slot>
    </x-confirmation-modal>



    {{--Create or edit an announcement--}}
    <x-dialog-modal id="announcementModal" wire:model="showEditModal">
        <x-slot name="title">
            <h2>{{ is_null($newAnnouncement['id']) ? trans_choice('crud.new',1) . ' ' . trans_choice('manage-announcements.Announcements', 1) : __('crud.edit') . ' ' . trans_choice('manage-announcements.Announcements', 1) }}
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
                    <x-label for="name" value="{{ __('manage-announcements.Title') }}" class="mt-4" />
                    <x-input id="name" type="text"  wire:model.defer="newAnnouncement.name"
                             class="mt-1 block w-full" />
                    <x-label for="content" value="{{ __('manage-announcements.Content') }}*" class="mt-4" />
                    <textarea id="content"  type="text" wire:model.defer="newAnnouncement.content"
                              class="mt-1 block w-full" ></textarea>
                    <div id="fileInputField">
                        @foreach($files as $index => $file)
                            <div>
                                <x-input class="mt-3" type="file" wire:model="files.{{ $index }}" />

                            </div>
                        @endforeach
                    </div>

                    <x-button wire:click="addFileInput" id="inputButton" class="mt-3">{{__('manage-announcements.new file')}}</x-button>
                    <div class="mt-2">
                        <x-label for="visibility" value="{{ __('files.visibility') }}" class="mt-4" />
                        <x-tmk.form.switch id="visibility"
                                           class="text-white shadow-lg rounded-full w-28 mt-1"
                                           color-off="bg-green-800" color-on="bg-orange-800"
                                           text-off="{{ __('files.visible') }}" text-on="{{ __('files.invisible') }}"
                                           wire:model="newAnnouncement.isVisible"
                        />
                        @if($newAnnouncement['isVisible'])
                            <x-input id="dateInput" type="datetime-local" wire:model.defer="newAnnouncement.postDate"
                                     class="mt-1 shadow-lg ml-3 w-auto" />
                        @else
                            <div wire:ignore>
                                <x-input id="dateInput" type="datetime-local" wire:model.defer="newAnnouncement.postDate"
                                         class="mt-1 shadow-lg ml-3 w-auto hidden" />
                            </div>
                        @endif
                    </div>
                </div>

            </div>
        </x-slot>
        <x-slot name="footer">
            <x-secondary-button @click="show = false">{{ __('crud.cancel') }}</x-secondary-button>

            @if (is_null($newAnnouncement['id']))
                <x-button wire:click="createAnnouncement()" class="ml-2">{{ trans_choice('crud.new',1) . ' ' . trans_choice('manage-announcements.Announcements',1)}}
                </x-button>
            @else
                <x-button wire:click="updateAnnouncement({{ $newAnnouncement['id'] }},{{$newFile['id']}})" wire:loading.attr="disabled"
                          class="ml-2">{{ __('crud.save') . ' ' .  trans_choice('manage-announcements.Announcements',1)}} </x-button>
            @endif
        </x-slot>
    </x-dialog-modal>


    {{-- The modal for deleting a file --}}
    <x-confirmation-modal id="deleteModal" wire:model="showDeleteFileModal">
        <x-slot name="title">
            <h2>{{ __('crud.delete') . ' ' . trans_choice('files.file', 1) }}</h2>
        </x-slot>
        <x-slot name="content">
            <p>{{ __('files.deleteMessage') }}</b></p>
        </x-slot>
        <x-slot name="footer">
            <x-secondary-button @click="show = false">{{ __('crud.cancel') }}</x-secondary-button>
            <x-danger-button class="ml-2" wire:click="deleteFile({{ $newFile['id'] }})">
                {{ __('crud.delete') . ' ' . trans_choice('files.file', 1) }}
            </x-danger-button>
        </x-slot>
    </x-confirmation-modal>

</div>
