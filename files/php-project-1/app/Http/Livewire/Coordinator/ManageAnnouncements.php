<?php

namespace App\Http\Livewire\Coordinator;

use App\Mail\AnnouncementMail;
use App\Models\Announcement;
use App\Models\Edition;
use App\Models\File;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class ManageAnnouncements extends Component
{
    use WithPagination;
    use WithFileUploads;
    public $orderBy = 'postDate';
    public $title;
    public $perPage=5;
    public $showEditModal = false;
    public $showDeleteAnnouncementModal = false;
    public $announcementFilter;
    public $files = [];
    public $editionId;
    public $showEditFileModal = false;
    public $showDeleteFileModal = false;
    public $showSendEmailModal= false;

    protected function rules()
    {
        return [
            'newAnnouncement.name' => 'required|string',
            'newAnnouncement.content' => 'required|string',
            'newAnnouncement.isVisible' => 'boolean'
        ];
    }
    protected $listeners = ['refreshComponent' => '$refresh'];
    public $newAnnouncement = [
        'id' => null,
        'name' => '',
        'content' => '',
        'isVisible' => false,
        'user_id' => null,
        'edition_id' => null,
        'postDate' => null
    ];

    public $newFile = [
        'id' => null,
        'fileUname' => '',
        'fileName' => '',
        'isVisible' =>  true,
        'user_id' => null,
        'company_id' => null,
        'announcement_id' => null,
        'postDate' => null,
        'edition_id' => null,
    ];

    protected $validationAttributes = [
        'newAnnouncement.name' => 'Title',
        'newAnnouncement.content' => 'Content',
        'newAnnouncement.isVisible' => 'Visibility',
    ];



    public function setUpdateAnnouncementVisibility(Announcement $announcement){
        $this->newAnnouncement = [
            'id' => $announcement->id,
            'name' => $announcement->name,
            'postDate' => $announcement->postDate,
            'isVisible' => $announcement->isVisible,
            'content' => $announcement->content,
            'user_id' => $announcement->user_id,
            'edition_id' => $announcement->edition_id,
        ];

        // show the delete modal
        if(!$announcement->isVisible){
            $this->showSendEmailModal = true;
        }else{
            $announcement->isVisible = false;
            $announcement->postDate = null;
            $announcement->save();
        }
    }
    public function updateAnnouncementVisibility($announcementId)
    {
        $announcement = Announcement::findOrFail($announcementId);

        $announcement->isVisible = true;
        $announcement->postDate = now();
        $this->dispatchBrowserEvent('swal:toast', [
            'background' => 'success',
            'html' => 'An e-mail has been sent to all students.'
        ]);

        $students = User::where('type_id','=',1)
                    ->get();
        $subject = "New Announcement";
        foreach ($students as $student) {
            Mail::to($student->email)->send(new AnnouncementMail($student,$subject));
        }

        $this->showSendEmailModal = false;
        $announcement->save();
    }


    public function setNewFile(File $file = null)
    {
        $this->resetErrorBag();

        // Check if there is a file is given
        if($file->id) {
            // If there is a file given, set the values of the newFile array to the values of the given file
            $this->newFile = [
                'id' => $file->id,
                'fileUname' => $file->fileUname,
                'fileName' => $file->fileName,
                'isVisible' => !$file->isVisible,
                'user_id' => $file->user_id,
                'company_id' => $file->company_id,
                'announcement_id' => $file->announcement_id,
                'postDate' => $file->postDate,
                'edition_id' => $file->edition_id,
            ];
        } else {
            // If there is no file given, set the values of the newFile array to the default values
            $this->newFile = [
                'id' => null,
                'fileUname' => '',
                'fileName' => '',
                'isVisible' => false,
                'user_id' => auth()->user()->id,
                'company_id' => null,
                'announcement_id' => null,
                'postDate' => null,
                'edition_id' => Edition::orderBy('date', 'desc')->first()->id,
            ];

            $this->file = null;
        }

        $this->showEditFileModal = true;
    }


    public function createAnnouncement()
    {
        $this->newAnnouncement['isVisible'] = is_bool($this->newAnnouncement['isVisible']);
        $this->validate();
        $activeEdition = Edition::where('isActive', true)->first();
        $announcement = Announcement::create([
            'name' => $this -> newAnnouncement['name'],
            'content' => $this -> newAnnouncement['content'],
            'isVisible' => $this -> newAnnouncement['isVisible'],
            'postDate' => $this -> newAnnouncement['postDate'],
            'user_id' => Auth::user()->id,
            'edition_id' => $activeEdition->id,
            'created_at' => now()
        ]);

        $this->showEditModal = false;
        if ($this->files) {
            foreach ($this->files as $file) {
                if ($this->files[0]) {
                    $this->newFile['fileName'] = $file->getClientOriginalName();
                    $this->newFile['fileUname'] = str_replace('files/', '', $file->store('files', 'public'));
                    $announcement->Files()->create([
                        'fileUname' => $this->newFile['fileUname'],
                        'fileName' => $this->newFile['fileName'],
                        'isVisible' => true,
                        'user_id' => Auth::user()->id,
                        'company_id' => null,
                        'announcement_id' => $this->newAnnouncement['id'],
                        'postDate' => $this->newFile['postDate'],
                        'edition_id' => $activeEdition->id,
                    ]);

                }
            }
        }
        $this->files = [];
        $this->dispatchBrowserEvent('swal:toast', [
            'background' => 'success',
            'html' => 'The announcement has been created.'
        ]);
    }

    public function addFileInput()
    {
        $newIndex = count($this->files);
        $this->files[$newIndex] = null;
    }

    public function setDeleteAnnouncement(Announcement $announcement)
    {


        // set the values of the newAnnouncement array to the values of the given announcement
        $this->newAnnouncement = [
            'id' => $announcement->id,
            'name' => $announcement->name,
            'postDate' => $announcement->postDate,
            'isVisible' => $announcement->isVisible,
            'content' => $announcement->content,
            'user_id' => $announcement->user_id,
            'edition_id' => $announcement->edition_id,
        ];

        // show the delete modal
        $this->showDeleteAnnouncementModal = true;
    }

    public function deleteAnnouncement(Announcement $announcement)
    {
        //delete files attached to announcements from storage

       foreach($announcement->files as $file) {
           Storage::disk('public')->delete($file->fileUname);
           $file->delete();
       }
       $announcement->delete();
       $this->dispatchBrowserEvent('swal:toast', [
            'background' => 'success',
            'html' => 'The announcement has been deleted.',
       ]);


        $this->showDeleteAnnouncementModal = false;

    }


    public function updateAnnouncement(Announcement $announcement)
    {
        $activeEdition = Edition::where('isActive', true)->first();
        $this->validate();
        $announcement->update([
            'name' => $this->newAnnouncement['name'],
            'content' => $this-> newAnnouncement['content']
        ]);
        if ($this->files) {
            foreach ($this->files as $file) {
                if ($file) {
                    $fileName = $file->getClientOriginalName();
                    $this->newFile['fileUname'] = str_replace('files/', '', $file->store('files', 'public'));
                    $announcementFile = [
                        'fileName' => $fileName,
                        'fileUname' => $this->newFile['fileUname'],
                        'isVisible' => false,
                        'user_id' => Auth::user()->id,
                        'company_id' => null,
                        'postDate' => now(),
                        'edition_id' => $activeEdition->id,
                    ];

                    $existingFile = File::where('fileName', $fileName)
                        ->where('announcement_id', $announcement->id)
                        ->first();

                    if ($existingFile === null) {
                        $announcement->Files()->create($announcementFile);
                    } else {
                        $this->dispatchBrowserEvent('swal:toast', [
                            'background' => 'error',
                            'html' => 'The file(s) already exist.'
                        ]);
                        return;
                    }
                }
            }
        }
        $this->showEditModal = false;
        $announcement->save();
        $this->files = [];
        $this->dispatchBrowserEvent('swal:toast', [
            'background' => 'success',
            'html' => 'The announcement has been updated.'
        ]);
    }
    public function setDeleteFile(File $file) {

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

        $this->showDeleteFileModal = true;
    }
    public function deleteFile(File $file) {
        try {
            Storage::disk('public')->delete($file->fileUname);
            $file->delete();
            $this->dispatchBrowserEvent('swal:toast', [
                'background' => 'success',
                'html' => "The File has '$file->fileName' been deleted successfully!",
            ]);
            $this->showDeleteFileModal = false;

        } catch (\Exception $e) {
            $this->dispatchBrowserEvent('swal:toast', [
                'background' => 'error',
                'html' => "The File has '$file->fileName' not been deleted!",
            ]);
        }
    }
    public function setNewAnnouncement(Announcement $announcement = null){
        $this -> resetErrorBag();
        if($announcement->id){
            $this->newAnnouncement['id'] = $announcement->id;
            $this->newAnnouncement['name'] = $announcement->name;
            $this->newAnnouncement['content'] = $announcement->content;
            $this->newAnnouncement['user_id'] = $announcement->user_id;
            $this->newAnnouncement['isVisible'] = $announcement->isVisible;
            $this->newAnnouncement['postDate'] = $announcement->postDate;
            $this->newAnnouncement['edition_id'] = $announcement->edition_id;
        }
        else{
            $this->reset ('newAnnouncement');
        }
        $this->showEditModal = true;
    }


    public function render()
    {
        $editions = Edition::get();

        $query = Announcement::with('files')
            ->with('user')
            ->with('edition');

        if($this -> editionId != 0){
            $query->where("edition_id", "=", $this->editionId);
        }

        $announcements = $query
            ->where('name','like','%'.$this->announcementFilter.'%')
            ->orderBy("created_at",'desc')
            ->paginate($this->perPage);

        App::setLocale(session('locale') ?? 'en');
        return view('livewire.Coordinator.manage-announcements',compact('announcements','editions'))
            ->layout("layouts.jobapplication",[
                "description" => "Manage Announcements",
                "title" => __('crud.manage') . " " . trans_choice('manage-announcements.Announcements', 2)
            ]);
    }
}
