<div>
    <x-tmk.section class="block mt-1 mb-2">
        {{-- filter section: fileNames, fileTypes, postDates and creationDates --}}
        <div class="grid grid-cols-10 gap-4">
            <div class="p-0 flex gap-2 col-2 mt-auto">
                <x-button wire:click="setNewFile()" class="h-12 w-44 flex justify-center">
                    {{ __('files.newFile') }}</x-button>
            </div>
            <div class="p-0 flex gap-2 col-2 mt-auto">
                <x-button wire:click="setNewFolder()" class="h-12 w-44 flex justify-center">
                    {{ __('files.newFolder') }}</x-button>
            </div>

            <div class="col-span-10 md:col-span-5 lg:col-span-3">
                <x-label for="fileName" value="Filter"/>
                <div
                    class="relative">
                    <x-input id="fileName" type="text"
                                wire:model="fileName"
                                class="block mt-1 w-full"
                                placeholder="{{__('files.filterFileNames')}}"/>
                    <div
                        class="w-5 absolute right-4 top-3 cursor-pointer">
                        <x-phosphor-x-duotone/>
                    </div>
                </div>
            </div>
            <div class="col-span-5 md:col-span-3 lg:col-span-2">
                <x-label for="perPage" value="{{__('files.perPage')}}"/>
                <x-tmk.form.select id="perPage"
                    class="block mt-1 w-full" wire:model="perPage">
                    @foreach([3,6,9,12,15,18,24] as $filesPerPage)
                        <option value="{{$filesPerPage}}">{{$filesPerPage}}</option>
                    @endforeach
                </x-tmk.form.select>
            </div>
        </div>
        {{-- end filter section --}}
    </x-tmk.section>


    <x-tmk.section class="block mt-1 mb-2">
        <div class="my-4">{{ $files->links() }}</div>

        {{-- Create a back arrow --}}
        @if ($currentFolder)
            <div class="flex gap-2">
                <button wire:click="openTopFolder({{ $currentFolder['parentFolder'] !== null ? $currentFolder['parentFolder'] : 0 }})">
                    <x-fas-arrow-left class="w-5 text-gray-300 hover:text-blue-600" />
                </button>
                <div class="flex gap-2">
                    <button wire:click="openTopFolder({{ 0 }})">
                        {{ __('files.root') }}
                    </button>
                    {{-- Loop through the path but don't show the last one --}}
                    @for($i = 0; $i < count($this->generatePathArray($currentFolder)) - 1; $i++)
                        <span> / </span>
                        <button wire:click="openFolder({{ $this->generatePathArray($currentFolder)[$i] }})">
                            {{ $this->generatePathArray($currentFolder)[$i]['fileName'] }}
                        </button>
                    @endfor

                    {{-- Show the last one --}}
                    @if (count($this->generatePathArray($currentFolder)) > 0)
                        <span> / </span>
                        <span class="font-bold">
                            {{ $this->generatePathArray($currentFolder)[count($this->generatePathArray($currentFolder)) - 1]['fileName'] }}
                        </span>
                    @endif
                </div>
            </div>
        @endif

        <table class="text-center w-full border border-grey-300 table-fixed">
            <colgroup>
                <col class="w-40 text-center">
                <col class="w-14 text-center">
                <col class="w-40 text-center">
                <col class="w-40 text-center">
                <col class="w-20 text-center">
            </colgroup>
            <thead>
                <tr class="bg-gray-100 text-gray-700 [&>th]:p-2">
                    <th class="text-center cursor-pointer" wire:click="resort('fileName')">
                        <span data-tippy-content="Order by File Name">{{ __('files.filename') }}</span>
                        <x-heroicon-s-chevron-up
                            class="w-5 text-slate-400
                            {{ $orderAsc ?: 'rotate-180' }} {{ $orderBy === 'fileName' ? 'inline-block' : 'hidden' }} " />
                    </th>
                    <th class="text-center cursor-pointer" wire:click="resort('isFolder')">
                        <span>{{ __('files.filetype') }}</span>
                        <x-heroicon-s-chevron-up
                            class="w-5 text-slate-400
                            {{ $orderAsc ?: 'rotate-180' }} {{ $orderBy === 'isFolder' ? 'inline-block' : 'hidden' }} " />
                    </th>

                    <th class="text-center cursor-pointer" wire:click="resort('postDate')">
                        <span data-tippy-content="Order by Post Date">{{ __('files.postDate') }}</span>
                        <x-heroicon-s-chevron-up
                            class="w-5 text-slate-400
                            {{ $orderAsc ?: 'rotate-180' }} {{ $orderBy === 'postDate' ? 'inline-block' : 'hidden' }} " />
                    </th>
                    <th class="text-center cursor-pointer" wire:click="resort('created_at')">
                        <span data-tippy-content="Order by Creation Date">{{ __('files.creationDate') }}</span>
                        <x-heroicon-s-chevron-up
                            class="w-5 text-slate-400
                            {{ $orderAsc ?: 'rotate-180' }} {{ $orderBy === 'created_at' ? 'inline-block' : 'hidden' }} " />
                    </th>
                    <th class="text-left"></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($files as $file)
                    {{-- Check if the type is a folder --}}
                    @if ($file['isFolder'])
                        <tr class="border border-gray-300 [&>td]:p-3">
                            {{-- If there is a postdate set, check if the postdate is already in the past. Then make it green otherwise if the postdate is still in the future or the isVisible is just false, turn it red --}}
                            <td class="text-blue-600 hover:text-blue-800 cursor-pointer" wire:click="openFolder({{ $file }})">
                                {{ $file->fileName }}
                            </td>
                            <td>{{ __('files.folder') }}</td>
                            <td>{{ is_null($file->postDate) ? '' : \Carbon\Carbon::parse($file->postDate)->format('D j-n-Y H:i') }}</td>
                            <td>{{ is_null($file->created_at) ? '' : \Carbon\Carbon::parse($file->created_at)->format('D j-n-Y H:i') }}</td>
                            <td>
                                <div
                                    class="flex gap-1 justify-center [&>*]:cursor-pointer [&>*]:outline-0 [&>*]:transition">
                                    <button wire:click="setEditFolder({{ $file }})">
                                        <x-phosphor-pencil-line-duotone class="w-5 text-gray-300 hover:text-green-600" />
                                    </button>
                                    <button wire:click="deleteFolder({{ $file }})">
                                        <x-phosphor-trash-duotone class="w-5 text-gray-300 hover:text-red-600" />
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @else
                        <tr class="border border-gray-300 [&>td]:p-3" wire:key="edition_{{ $file->id }}">
                            {{-- If there is a postdate set, check if the postdate is already in the past. Then make it green otherwise if the postdate is still in the future or the isVisible is just false, turn it red --}}
                            <td {{ $file->isVisible && $file->postDate < now() ? 'class=text-green-600' : 'class=text-red-600' }}>
                                {{ $file->fileName }}
                            </td>
                            <td>{{ $file->fileType }}</td>
                            <td>{{ is_null($file->postDate) ? '' : \Carbon\Carbon::parse($file->postDate)->format('D j-n-Y H:i') }}</td>
                            <td>{{ is_null($file->created_at) ? '' : \Carbon\Carbon::parse($file->created_at)->format('D j-n-Y H:i') }}</td>
                            <td>
                                <div
                                    class="flex gap-1 justify-center [&>*]:cursor-pointer [&>*]:outline-0 [&>*]:transition">
                                    {{-- Add a view button --}}
                                    <button wire:click="setViewFile({{ $file }})">
                                        <x-phosphor-eye-duotone class="w-5 text-gray-300 hover:text-blue-600" />
                                    <button wire:click="setNewFile({{ $file }})">
                                        <x-phosphor-pencil-line-duotone class="w-5 text-gray-300 hover:text-green-600" />
                                    </button>
                                    <button wire:click="setDeleteFile({{ $file }})">
                                        <x-phosphor-trash-duotone class="w-5 text-gray-300 hover:text-red-600" />
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endif
                @endforeach
            </tbody>

        </table>
        @if ($files->isEmpty())
            <x-tmk.form.alert type="danger" class="w-full">
                Can't find any files.
            </x-tmk.form.alert>
        @endif

        <div class="my-4">{{ $files->links() }}</div>
    </x-tmk.section>

    {{-- The modal for adding or editing a file --}}
    <x-dialog-modal id="fileModal"  wire:model="showEditModal">
        <x-slot name="title">
            <h2>{{ is_null($newFile['id']) ? __('files.newFile') : __('files.editFile') }}</h2>
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
                @if (is_null($newFile['id']))
                    <div class="flex-1 flex-col gap-4">
                        <x-label for="fileUpload" value="{{ __('files.file') }}" class="mt-4" />
                        <x-input id="fileUpload" type="file" wire:model="file"
                            class="mt-1 block w-full" />
                    </div>
                @else
                    {{-- Show the same but disabled --}}
                    <div class="flex-1 flex-col gap-4">
                        <x-label value="{{ __('files.file') }}" class="mt-4" />
                        <p class="mt-1 block w-full">{{ $newFile['fileName'] }}</p>
                    </div>
                @endif

            </div>
            <div class="flex flex-row gap-4 mt-4">
                <div class="flex-1 flex-col gap-4">
                    <x-label for="edition" value="{{ __('files.edition') }}" class="mt-4" />
                    <x-tmk.form.select id="edition" wire:model.defer="newFile.edition_id" class="mt-1 block w-full">
                        @foreach ($editions as $edition)
                            <option value="{{ $edition->id }}">{{ $edition->name }}</option>
                        @endforeach
                    </x-tmk.form.select>
                </div>
            </div>
            <div class="flex flex-row gap-4 mt-4">
                <div class="flex-1 flex-col gap-4">
                    <x-label for="visibility" value="{{ __('files.visibility') }}" class="mt-4" />
                    <div class="flex">
                        <x-tmk.form.switch id="visibility"
                        class="text-white shadow-lg rounded-full w-28 mt-1" 
                        color-off="bg-green-800" color-on="bg-orange-800"
                        text-off="{{ __('files.visible') }}" text-on="{{ __('files.invisible') }}"
                        wire:model="newFile.isVisible"
                        />

                        @if($newFile['isVisible'])
                            <x-input id="dateInput" type="datetime-local" wire:model.defer="newFile.postDate"
                                class="mt-1 shadow-lg ml-3 w-auto" />
                        @else
                            <div wire:ignore>
                                <x-input id="dateInput" type="datetime-local" wire:model.defer="newFile.postDate"
                                    class="mt-1 shadow-lg ml-3 w-auto hidden" />
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </x-slot>
        <x-slot name="footer">
            <x-secondary-button @click="show = false">{{ __('crud.cancel') }}</x-secondary-button>
            @if (is_null($newFile['id']))
                <x-button wire:click="createFile()" wire:loading.attr="disabled" class="ml-2">{{ __('files.upload') }}
                </x-button>
            @else
                <x-button wire:click="updateFile({{ $newFile['id'] }})" wire:loading.attr="disabled"
                    class="ml-2">{{ __('files.save') }}</x-button>
            @endif
        </x-slot>
    </x-dialog-modal>

    {{-- The modal for viewing a file --}}
    <x-dialog-modal id="viewFileModal"  wire:model="showViewModal" maxWidth="auto">
        <x-slot name="title">
            <h2>{{ __('files.view') }}</h2>
        </x-slot>
        <x-slot name="content">
            <p class="text-sky-400 mb-4 text-xl"><a class="text-center flex" href="{{ $downloadLink }}">
                {{ $originalFileName }} <x-phosphor-download-duotone class="w-7 text-gray-300 hover:text-sky-400"/>
            </a>
            </p>
                {!!$contentViewerPlayer!!}
        </x-slot>
        <x-slot name="footer">
            <x-secondary-button wire:click="closeViewModal()">{{ __('files.close') }}</x-secondary-button>
        </x-slot>
    </x-dialog-modal>

    {{-- The modal for deleting a file --}}
    <x-confirmation-modal id="deleteModal" wire:model="showDeleteModal">
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

    {{-- The modal for creating a folder --}}
    <x-dialog-modal id="folderModal"  wire:model="showNewFolderModal">
        <x-slot name="title">
            <h2>{{ __('files.newFolder') }}</h2>
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
                <div class="flex-1 flex-col gap-4">
                    <x-label for="folderName" value="{{ __('files.folderName') }}" class="mt-4" />
                    <x-input id="folderName" type="text" wire:model.defer="newFile.fileName"
                        class="mt-1 block w-full" />
                </div>
            </div>
        </x-slot>
        <x-slot name="footer">
            <x-secondary-button @click="show = false">{{ __('crud.cancel') }}</x-secondary-button>
            <x-button wire:click="createFolder()" wire:loading.attr="disabled" class="ml-2">{{ __('files.newFolder') }}
            </x-button>
        </x-slot>
    </x-dialog-modal>

    {{-- The modal for editing a folder --}}
    <x-dialog-modal id="editFolderModal"  wire:model="showEditFolderModal">
        <x-slot name="title">
            <h2>{{ __('files.editFolder') }}</h2>
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
                <div class="flex-1 flex-col gap-4">
                    <x-label for="folderName" value="{{ __('files.folderName') }}" class="mt-4" />
                    <x-input id="folderName" type="text" wire:model.defer="newFile.fileName"
                        class="mt-1 block w-full" />
                </div>
            </div>
        </x-slot>
        <x-slot name="footer">
            <x-secondary-button @click="show = false">{{ __('crud.cancel') }}</x-secondary-button>
            <x-button wire:click="updateFolder({{ $newFile['id'] }})" wire:loading.attr="disabled"
                class="ml-2">{{ __('files.save') }}</x-button>
        </x-slot>
    </x-dialog-modal>

</div>
