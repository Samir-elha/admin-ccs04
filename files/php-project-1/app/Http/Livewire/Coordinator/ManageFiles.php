<?php

namespace App\Http\Livewire\Coordinator;

use App\Models\File;
use App\Models\Edition;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Storage;

class ManageFiles extends Component
{
    use WithPagination;
    use WithFileUploads;

    // Filter variables
    public $orderBy = 'id';
    public $orderAsc = false;

    public $perPage = 12;
    public $fileName;
    public $fileType = "%";
    public $postDate = "%";

    public $showViewModal = false;
    public $showEditModal = false;
    public $showDeleteModal = false;
    public $showDeleteErrorModal = false;
    public $showNewFolderModal = false;
    public $showEditFolderModal = false;


    // File deletion counter
    public $fileDeletionCounter = 0;

    // File upload variable
    public $file;

    // Current folder variable
    public $currentFolder = null;

    // Model variable for the language
    public $language;

    // Model variable for the new file
    public $newFile = [
        'id' => null,
        'fileUname' => null,
        'fileName' => '',
        'isFolder' => null,
        'parentFolder' => null,
        'isVisible' =>  true,
        'user_id' => null,
        'company_id' => null,
        'announcement_id' => null,
        'postDate' => null,
        'edition_id' => null,
    ];

    // Model variable for the View File modal
    public $contentViewerPlayer = "";
    public $downloadLink = "";
    public $originalFileName = "";

    // Model protected $rules for validation
    protected function rules()
    {
        return [
            'newFile.isVisible' => 'boolean',
            'newFile.user_id' => 'integer',
            'newFile.company_id' => 'nullable|integer',
            'newFile.announcement_id' => 'nullable|integer',
            'newFile.postDate' => 'nullable|date',
            'newFile.edition_id' => 'required|integer',
        ];
    }

    protected $validationAttributes = [
        'newFile.fileName' => 'folder name',
        'newFile.isVisible' => 'is visible',
        'newFile.user_id' => 'user id',
        'newFile.company_id' => 'company id',
        'newFile.announcement_id' => 'announcement id',
        'newFile.postDate' => 'post date',
        'newFile.edition_id' => 'edition id',
        'file' => 'file',
    ];

    protected $table = 'manageFiles';

    protected $listeners = [
        'delete-folder' => 'deleteFolderConfirmed',
        'overwrite-file' => 'overwriteFileConfirmed',
    ];

    // Function for the view
    public function setNewFile(File $file = null)
    {
        // Reset the error bag
        $this->resetErrorBag();

        // Clear the file upload
        $this->file = null;

        // Check if there is a file is given
        if($file->id) {
            // If there is a file given, set the values of the newFile array to the values of the given file
            $this->newFile = [
                'id' => $file->id,
                'fileUname' => $file->fileUname,
                'fileName' => $file->fileName,
                'isVisible' => !$file->isVisible,
                'parentFolder' => $file->parentFolder,
                'isFolder' => $file->isFolder,
                'user_id' => $file->user_id,
                'company_id' => $file->company_id,
                'announcement_id' => $file->announcement_id,
                'postDate' => $file->postDate,
                'edition_id' => $file->edition_id,
            ];
        } else {
            // Check if there is a current folder
            if ($this->currentFolder) {
                // If there is a current folder, set the values of the newFile array to the values of the current folder
                $parentfFolder = $this->currentFolder['id'];
            } else {
                // If there is no current folder, set the values of the newFile array to the default values
                $parentfFolder = null;
            }

            // If there is no file given, set the values of the newFile array to the default values
            $this->newFile = [
                'id' => null,
                'fileUname' => '',
                'fileName' => '',
                'isVisible' => false,
                'parentFolder' => $parentfFolder,
                'isFolder' => false,
                'user_id' => auth()->user()->id,
                'company_id' => null,
                'announcement_id' => null,
                'postDate' => null,
                'edition_id' => Edition::orderBy('date', 'desc')->first()->id,
            ];

        }
        
        $this->showEditModal = true;
    }

    // Function for overwriting a file
    public function overwriteFileConfirmed($params) {
        // Get the unique name of the file from the database and overwrite the file in the storage
        $fileUname = File::where('id', $params['id'])->first()->fileUname;
        
        // Overwrite the file in the storage
        Storage::disk('public')->delete('files/' . $fileUname);

        // Save the new file in the storage with the same unique name streamed
        Storage::putFileAs('public/files', $this->file, $fileUname);

        // Show a toast message when the file has been overwritten successfully
        $this->dispatchBrowserEvent('swal:toast', [
            'background' => 'success',
            'html' => "The file has been overwritten successfully.",
        ]);

        $this->showEditModal = false;
        
    }

    // Function for creating a new file
    public function createFile() {

        $this->validate([
            'newFile.isVisible' => 'boolean',
            'newFile.user_id' => 'integer',
            'newFile.company_id' => 'nullable|integer',
            'newFile.announcement_id' => 'nullable|integer',
            'newFile.postDate' => 'nullable|date',
            'newFile.edition_id' => 'required|integer',
            'file' => 'required|file|max:200000',
        ]);

        $this->newFile['isVisible'] = is_bool($this->newFile['isVisible']);
        $this->newFile['fileName'] = $this->file->getClientOriginalName();

        // Get the File row from the database
        $fileObject = File::where('fileName', $this->newFile['fileName'])->where('parentFolder', $this->newFile['parentFolder'])->first();
        // Check if there is a file with the same name in the same folder
        if ($fileObject) {
            // Ask the user if he wants to overwrite the file
            $this->dispatchBrowserEvent('swal:confirm', [
                'icon' => 'warning',
                'title' => 'Are you sure?',
                'text' => "Are you sure you want to overwrite the file? The original settings will be kept.",
                'confirmButtonText' => 'Yes',
                'cancelButtonText' => 'No',
                'next' => [
                    'event' => 'overwrite-file',
                    'params' => [
                        'id' => $fileObject['id'],
                    ],
                ]
            ]);
            return;
        }

        // Set the unique name of the file
        $this->newFile['fileUname'] = str_replace('files/', '', $this->file->store('files', 'public'));
        // Create the new file in the database
        $fileUpload = File::create([
            'fileUname' => $this->newFile['fileUname'],
            'fileName' => $this->newFile['fileName'],
            'isVisible' => $this->newFile['isVisible'],
            'parentFolder' => $this->newFile['parentFolder'],
            'user_id' => $this->newFile['user_id'],
            'company_id' => $this->newFile['company_id'],
            'announcement_id' => $this->newFile['announcement_id'],
            'postDate' => $this->newFile['postDate'],
            'edition_id' => $this->newFile['edition_id'],
        ]);

        // Show a toast message when the file has been uploaded successfully
        $this->dispatchBrowserEvent('swal:toast', [
            'background' => 'success',
            'html' => "The File has '$fileUpload->fileName' been uploaded successfully!",
        ]);

        $this->showEditModal = false;
    }

    // Function for updating a file
    public function updateFile() {
        // Validate the input
        $this->validate();

        // Check if the file is visible
        $this->newFile['isVisible'] = is_bool($this->newFile['isVisible']);

        // Update the file in the database
        $fileUpload = File::find($this->newFile['id']);

        // Update the file in the database
        $fileUpload->update([
            'isVisible' => $this->newFile['isVisible'],
            'user_id' => $this->newFile['user_id'],
            'company_id' => $this->newFile['company_id'],
            'announcement_id' => $this->newFile['announcement_id'],
            'postDate' => $this->newFile['postDate'],
            'edition_id' => $this->newFile['edition_id'],
        ]);

        // Show a toast message when the file has been updated successfully
        $this->dispatchBrowserEvent('swal:toast', [
            'background' => 'success',
            'html' => "The File has '$fileUpload->fileName' been updated successfully!",
        ]);

        $this->showEditModal = false;
    }

    public function setViewFile(File $file) {
        // If there is a file given, set the values of the newFile array to the values of the given file
        $this->newFile = [
            'id' => $file->id,
            'fileUname' => $file->fileUname,
            'fileName' => $file->fileName,
            'isVisible' => $file->isVisible,
            'user_id' => $file->user_id,
            'company_id' => $file->company_id,
            'announcement_id' => $file->announcement_id,
            'postDate' => $file->postDate,
            'edition_id' => $file->edition_id,
        ];

        // Check if the file has a microsoft office extension by extracting the extension from the file name
        $fileNameParts = explode('.', $file->fileName);
        $fileExtension = strtolower(end($fileNameParts));

        // Check if the content is of type microsoft office
        if (in_array($fileExtension, ['doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx'])) {
            // Set the content viewer player to the microsoft office viewer
            $this->contentViewerPlayer = "<iframe id='myIframe' src='https://view.officeapps.live.com/op/embed.aspx?src=" . asset('storage/files/' . $this->newFile['fileUname']) . "' class='w-full' style='height: 80vh;'></iframe>";
        } 
        // Check if the content is of type image
        else if (in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif'])) {
            // Set the content viewer player to the image viewer
            $this->contentViewerPlayer = "<img src=" . asset('storage/files/' . $this->newFile['fileUname']) . " style='max-width: 100%;
            max-height: 100%;
            display: block;'>";
        }
        else {
            // Set the content viewer player to the default viewer
            $this->contentViewerPlayer = "<iframe id='myIframe' src=" . asset('storage/files/' . $this->newFile['fileUname']) . " class='w-full' style='height: 80vh;'></iframe>";
        }

        // Set the download link to the file
        $this->downloadLink = route('download', ['filename' => $this->newFile['fileUname'], 'name' => $this->newFile['fileName']]);
        // Set the original file name to the file
        $this->originalFileName = $this->newFile['fileName'];

        $this->showViewModal = true;
    }

    // Function for closing the view modal
    public function closeViewModal() {
        $this->showViewModal = false;
        // Reset the values of the variables
        $this->downloadLink = '';
        $this->contentViewerPlayer = '';
    }

    // Function for setting the delete file modal
    public function setDeleteFile(File $file) {

        // Set the values of the newFile array to the values of the given file
        $this->newFile = [
            'id' => $file->id,
            'fileUname' => $file->fileUname,
            'fileName' => $file->fileName,
            'isVisible' => $file->isVisible,
            'user_id' => $file->user_id,
            'company_id' => $file->company_id,
            'announcement_id' => $file->announcement_id,
            'postDate' => $file->postDate,
            'edition_id' => $file->edition_id,
        ];

        $this->showDeleteModal = true;
    }

    // Function for deleting a file
    public function deleteFile(File $file) {
        // Try to delete the file from the database and the storage
        try {
            // Delete the file from the storage
            Storage::disk('public')->delete('files/' . $file->fileUname);
            // Delete the file from the database
            $file->delete();
            // Show a toast message when the file has been deleted successfully
            $this->dispatchBrowserEvent('swal:toast', [
                'background' => 'success',
                'html' => "The File has '$file->fileName' been deleted successfully!",
            ]);
            $this->showDeleteModal = false;
        } catch (\Exception $e) {
            // Show a toast message when the file has not been deleted successfully
            $this->dispatchBrowserEvent('swal:toast', [
                'background' => 'error',
                'html' => "The File has '$file->fileName' not been deleted!",
            ]);
        }
    }

    // Function for setting the new folder modal
    public function setNewFolder() {
        // Set the parent folder to the current folder if there is a current folder, else set the parent folder to null
        if ($this->currentFolder) {
            $parentfFolder = $this->currentFolder['id'];
        } else {
            $parentfFolder = null;
        }

        // Set the values of the newFile array to the values of the given file
        $this -> newFile = [
            'id' => null,
            'fileUname' => null,
            'fileName' => '',
            'isVisible' => false,
            'parentFolder' => $parentfFolder,
            'isFolder' => true,
            'user_id' => auth()->user()->id,
            'company_id' => null,
            'announcement_id' => null,
            'postDate' => null,
            'edition_id' => Edition::orderBy('date', 'desc')->first()->id,
        ];

        $this->showNewFolderModal = true;
    }

    // Function for creating a new folder
    public function createFolder() {
        // Validate the input fields
        $this->validate([
            'newFile.fileName' => 'required|min:3|max:255',
        ]);

        $this->validate();

        // Check if the file is visible
        $this->newFile['isVisible'] = is_bool($this->newFile['isVisible']);

        // Create the folder in the database
        $fileUpload = File::create([
            'fileUname' => $this->newFile['fileUname'],
            'fileName' => $this->newFile['fileName'],
            'isVisible' => $this->newFile['isVisible'],
            'parentFolder' => $this->newFile['parentFolder'],
            'isFolder' => $this->newFile['isFolder'],
            'user_id' => $this->newFile['user_id'],
            'company_id' => $this->newFile['company_id'],
            'announcement_id' => $this->newFile['announcement_id'],
            'postDate' => $this->newFile['postDate'],
            'edition_id' => $this->newFile['edition_id'],
        ]);

        // Show a toast message when the folder has been created successfully
        $this->dispatchBrowserEvent('swal:toast', [
            'background' => 'success',
            'html' => "The Folder has '$fileUpload->fileName' been created successfully!",
        ]);

        $this->showNewFolderModal = false;
    }

    // Function for setting the edit folder modal
    public function setEditFolder(File $file) {

        // Set the values of the newFile array to the values of the given file
        $this->newFile = [
            'id' => $file->id,
            'fileUname' => $file->fileUname,
            'fileName' => $file->fileName,
            'isVisible' => $file->isVisible,
            'parentFolder' => $file->parentFolder,
            'isFolder' => $file->isFolder,
            'user_id' => $file->user_id,
            'company_id' => $file->company_id,
            'announcement_id' => $file->announcement_id,
            'postDate' => $file->postDate,
            'edition_id' => $file->edition_id,
        ];

        $this->showEditFolderModal = true;
    }

    // Function for updating a folder
    public function updateFolder() {
        // Validate the input fields
        $this->validate([
            'newFile.fileName' => 'required|min:3|max:255',
        ]);

        $this->validate();

        // Get the file from the database
        $fileUpload = File::find($this->newFile['id']);

        // Update the folder in the database
        $fileUpload->update([
            'fileUname' => $this->newFile['fileUname'],
            'fileName' => $this->newFile['fileName'],
            'isVisible' => $this->newFile['isVisible'],
            'parentFolder' => $this->newFile['parentFolder'],
            'isFolder' => $this->newFile['isFolder'],
            'user_id' => $this->newFile['user_id'],
            'company_id' => $this->newFile['company_id'],
            'announcement_id' => $this->newFile['announcement_id'],
            'postDate' => $this->newFile['postDate'],
            'edition_id' => $this->newFile['edition_id'],
        ]);

        // Show a toast message when the folder has been edited successfully
        $this->dispatchBrowserEvent('swal:toast', [
            'background' => 'success',
            'html' => "The Folder has '$fileUpload->fileName' been edited successfully!",
        ]);

        $this->showEditFolderModal = false;
    }

    // Function for opening a folder
    public function openFolder($folder) {
        // Check if the folder is the root folder
        if ($folder == 0) {
            // Set the current folder to null
            $this->currentFolder = null;
        } else {
            // Find the file in the database by getting the id from the $folder variable
            $this->currentFolder = $folder;
        }
    }

    // Function for opening the top folder
    public function openTopFolder($parentId) {
        // Check if the parent id is 0 (root folder)
        if ($parentId == 0) {
            // Set the current folder to null
            $this->currentFolder = null;
        } else {
            // Find the file in the database by getting the id from the $folder variable
            $this->currentFolder = File::find($parentId);
        }
    }

    // This function is used for generating the path array
    private function generatePathArray($folder) {
        // Create an empty array
        $pathArray = [];

        // Check if the folder is not null
        if ($folder) {
            // Add the folder to the array
            $pathArray[] = $folder;
            // Loop through the folders until the root folder is reached
            while ($folder['parentFolder']) {
                // Find the parent folder
                $folder = File::find($folder['parentFolder']);
                // Add the parent folder to the array
                $pathArray[] = $folder;
            }
        }

        // Reverse the array and return it
        return array_reverse($pathArray);
    }

    // Function for getting the amount of files and folders inside a folder
    private function getAmountOfFilesAndFoldersInsideFolder($folderId)
    {
        // Get all the files and folders that are in the current folder
        $files = File::where('parentFolder', $folderId)->get();
        // If there are folders inside the current folder, get the amount of files and folders inside those folders
        if ($files->count()) {
            foreach ($files as $file) {
                $this->fileDeletionCounter++;
                // Add the file to the array
                if ($file->isFolder) {
                    // Get the amount of files and folders inside the folder
                    $this->getAmountOfFilesAndFoldersInsideFolder($file->id);
                }
            }
        }
    }

    // Function for deleting a folder
    public function deleteFolder(File $file) {
        // Set the file deletion counter to 0
        $this->fileDeletionCounter = 0;
        // Get the amount of files and folders inside the folder
        $this->getAmountOfFilesAndFoldersInsideFolder($file->id);

        // Check if there are files and folders inside the folder
        if ($this->fileDeletionCounter > 0) {
            $questionMessage = "This will delete $this->fileDeletionCounter files and folders!";
            $deleteMessage = "$this->fileDeletionCounter files and folders have been deleted successfully!";
        } else {
            $questionMessage = "This will delete the folder '$file->fileName'!";
            $deleteMessage = "The folder '$file->fileName' has been deleted successfully!";
        }

        // Show a confirmation message
        $this->dispatchBrowserEvent('swal:confirm', [
            'icon' => 'warning',
            'title' => 'Are you sure?',
            'text' => $questionMessage,
            'confirmButtonText' => 'Yes',
            'cancelButtonText' => 'No',
            'next' => [
                'event' => 'delete-folder',
                'params' => [
                    'id' => $file->id,
                    'message' => $deleteMessage,
                ],
            ]
        ]);
    }

    // Function for deleting a folder
    private function deleteFolderAndContent($folderId)
    {
        // Get all the files and folders that are in the current folder
        $files = File::where('parentFolder', $folderId)->get();
        // If there are folders inside the current folder, get the amount of files and folders inside those folders
        if ($files->count()) {
            foreach ($files as $file) {
                // Delete the file
                $file->delete();
                // Get the unique file name and delete this file from the storage
                $uniqueFileName = $file->fileUname;
                // Check if it is a file
                if(!$file->isFolder) {
                    // Delete the file from the storage
                    Storage::disk('public')->delete('files/' . $uniqueFileName);
                }
                // Add the file to the array
                if ($file->isFolder) {
                    // Call the function again to delete the files and folders inside the folder
                    $this->deleteFolderAndContent($file->id);
                }
            }
        }
    }

    // Function for deleting a folder
    public function deleteFolderConfirmed($params) {

        // Delete the folder and all the files and folders inside it
        $this->deleteFolderAndContent($params['id']);

        // Finally delete the current folder itself
        $file = File::find($params['id'])->delete();

        $this->dispatchBrowserEvent('swal:toast', [
            'background' => 'success',
            'html' => $params['message'],
        ]);
    }

    // resort the genres by the given column
    public function resort($column)
    {
        // if the column is already the current column, reverse the order
        if ($this->orderBy === $column) {
            $this->orderAsc = !$this->orderAsc;
        } else {
            $this->orderAsc = true;
        }
        // set the current column to the given column
        $this->orderBy = $column;
    }

    public function render()
    {
        // Set the locale to the language that is stored in the session
        App::setLocale(session('locale') ?? 'en');

        // Check if the current folder is the root folder
        if ($this->currentFolder) {
            // If it is not the root folder, get all the files that are in the current folder
            $files = File::orderBy($this->orderBy, $this->orderAsc ? 'asc' : 'desc')->where([
                ['parentFolder', '=', $this->currentFolder['id']],
                ['fileName', 'like', "%{$this->fileName}%"]
            ])
            ->paginate($this->perPage);
        } else {
            // If it is the root folder, get all the files that are not in a folder
            $files = File::orderBy($this->orderBy, $this->orderAsc ? 'asc' : 'desc')->where([
                ['parentFolder', '=', null],
                ['fileName', 'like', "%{$this->fileName}%"]
            ])
            ->paginate($this->perPage);
        }
        // Add the type to the files array by extracting it from the filename by uppercasing it and save it as a new property
        foreach ($files as $file) {
            // Check if there is an extension at the end of the filename
            if(strpos($file->fileName, '.') !== false) {
                // If there is an extension, extract it and save it as a new property
                $file->fileType = strtoupper(explode('.', $file->fileName)[1]);
            } else {
                // If there is no extension, save an empty string as a new property
                $file->fileType = 'none';
            }
        }

        // Order the editions by date ascending
        $editions = Edition::orderBy('date', 'desc')->get();

        // Get all the unique file types
        $fileTypes = $files->map(function ($file) {
            return $file->fileType;
        })->unique()->values()->toArray();


        return view('livewire.Coordinator.manage-files', compact('files', 'editions', 'fileTypes'))
            ->layout('layouts.jobapplication',[
                "description" => "Manage Files",
                "title" => "Manage Files"
            ]);
    }
}
