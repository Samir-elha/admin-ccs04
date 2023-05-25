<?php

namespace App\Http\Livewire\Student;

use App\Models\Edition;
use App\Models\File;
use Livewire\Component;
use App\Models\Announcement;
use Illuminate\Support\Facades\App;

use Carbon\Carbon;

use Livewire\WithPagination;

class ViewAnnouncements extends Component
{
    use WithPagination;

    public $perPage = 5;
    public $showDetailedModal = false;
    public $showViewModal = false;
    public $contentViewerPlayer = "";
    public $downloadLink = "";
    public $originalFileName = "";
    public $announcementFilter;
    public $setAnnouncement = [
        'id' => null,
        'name' => '',
        'postDate' => null,
        'isVisible' => false,
        'content' => null,
        'user_id' => null,
        'edition_id' => null,
        'firstName' => null,
        'lastName' => null,
        'files' => []
    ];

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
        $fileExtension = strtolower(pathinfo($file->fileName, PATHINFO_EXTENSION));
        if (in_array($fileExtension, ['doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx'])) {
            $this->contentViewerPlayer = "<iframe id='myIframe' src='https://view.officeapps.live.com/op/embed.aspx?src=" . asset('storage/files/' . $this->newFile['fileUname']) . "' class='w-full' style='height: 80vh;'></iframe>";
        }
        // Check if the content is of type image
        else if (in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif'])) {
            $this->contentViewerPlayer = "<img src=" . asset('storage/files/' . $this->newFile['fileUname']) . " style='max-width: 100%;
            max-height: 100%;
            display: block;'>";
        }
        else {
            $this->contentViewerPlayer = "<iframe id='myIframe' src=" . asset('storage/files/' . $this->newFile['fileUname']) . " class='w-full' style='height: 80vh;'></iframe>";
        }

        $this->downloadLink = route('download', ['filename' => $this->newFile['fileUname'], 'name' => $this->newFile['fileName']]);
        $this->originalFileName = $this->newFile['fileName'];
        $this->showViewModal = true;
    }

    public function closeViewModal() {
        $this->showViewModal = false;


        $this->downloadLink = '';
        $this->contentViewerPlayer = '';
    }

    public function setDetailAnnouncement(Announcement $announcement)
    {
        $date = Carbon::parse($announcement->postDate);

        $this->setAnnouncement['id'] = $announcement->id;
        $this->setAnnouncement['name'] = $announcement->name;
        $this->setAnnouncement['postDate'] = $date->format('M d, Y h:i A');
        $this->setAnnouncement['isVisible'] = $announcement->isVisible;
        $this->setAnnouncement['content'] = $announcement->content;
        $this->setAnnouncement['user_id'] = $announcement->user_id;
        $this->setAnnouncement['edition_id'] = $announcement->edition_id;
        $this->setAnnouncement['firstName'] = $announcement->user->firstName;
        $this->setAnnouncement['lastName'] = $announcement->user->lastName;
        $this->showDetailedModal = true;
    }

    public function render()
    {
        App::setLocale(session('locale') ?? 'en');
        $activeEdition = Edition::where("isActive",true)->first();
        $announcements = Announcement::with('files')
            ->with('user')
            ->with('edition')
            ->where('isVisible', true)
            ->where('edition_id',"=",$activeEdition->id)
            ->where('name',"like",'%'.$this->announcementFilter.'%')
            ->orderBy("postDate",'desc')
            ->paginate($this->perPage);
        $files = File::all();
        return view('livewire.Student.view-announcements', compact('announcements','files'))
            ->layout('layouts.jobapplication', [
                "description" => "View Announcements",
                "title" => __('viewAnnouncements.view') . ' ' . trans_choice('viewAnnouncements.Announcements', 2)
            ]);
    }
}
