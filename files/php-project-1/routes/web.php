<?php

use App\Http\Livewire\Company\ViewTimeslotSchedule;
use App\Http\Livewire\Coordinator\ManageAnnouncements;
use App\Http\Livewire\Coordinator\ManageCompanies;
use App\Http\Livewire\Coordinator\ManageEdition;
use App\Http\Livewire\Coordinator\ManageFiles;
use App\Http\Livewire\Coordinator\ManageQuestionnaire;
use App\Http\Livewire\Coordinator\ManageTimeslots;
use App\Http\Livewire\Coordinator\ManageStudentAccounts;
use App\Http\Livewire\Coordinator\ManageCompanyUsers;
use App\Http\Livewire\Coordinator\ViewFeedback;
use App\Http\Livewire\Student\BookTimeslot;
use App\Http\Livewire\Student\FillInQuestionnaire;
use App\Http\Livewire\Student\ViewAnnouncements;
use App\Http\Livewire\Student\ViewCompanies;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\App;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/



Route::get('/', function () {
    return view('home');
})->name('home');

// Route for the language change
Route::get('/locale/{locale}', function (string $locale) {
    if (!in_array($locale, ['en', 'nl'])) {
        abort(400);
    }
    session(['locale' => $locale]);
    App::setLocale($locale);

    return redirect()->back();
})->name('locale');

Route::middleware(['auth', 'admin'])->prefix('admin')
    ->name('admin.')->group(function () {
        Route::get('manage-company-users', ManageCompanyUsers::Class)->name('manage-company-users');
        Route::get('manage-student-accounts', ManageStudentAccounts::Class)->name('manage-student-accounts');
        Route::get('manage-companies', ManageCompanies::Class)->name('manage-companies');
        Route::get('manage-edition', ManageEdition::Class)->name('manage-edition');
        Route::get('manage-questionaire', ManageQuestionnaire::Class)->name("manage-questionaire");
        Route::get('view-feedback', ViewFeedback::class)->name('view-feedback');
        Route::get('manage-files', ManageFiles::Class)->name('manage-files');
        Route::get('manage-timeslots', ManageTimeslots::Class)->name('manage-timeslots');
        Route::get('manage-announcements', ManageAnnouncements::Class)->name('manage-announcements');
        Route::get('book-timeslot', BookTimeslot::Class)->name('book-timeslot');
        Route::get('view-announcements', ViewAnnouncements::Class)->name('view-announcements');
        Route::get('view-companies', ViewCompanies::Class)->name('view-companies');
        Route::get('fill-in-questionnaire', FillInQuestionnaire::Class)->name('fill-in-questionnaire');
        Route::get('view-timeslot-schedule', ViewTimeslotSchedule::Class)->name('view-timeslot-schedule');
    });

Route::middleware(['auth', 'coordinator'])->prefix('coordinator')
    ->name('coordinator.')->group(function () {
        Route::get('manage-company-users', ManageCompanyUsers::Class)->name('manage-company-users');
        Route::get('manage-student-accounts', ManageStudentAccounts::Class)->name('manage-student-accounts');
        Route::get('manage-companies', ManageCompanies::Class)->name('manage-companies');
        Route::get('manage-edition', ManageEdition::Class)->name('manage-edition');
        Route::get('manage-questionaire', ManageQuestionnaire::Class)->name("manage-questionaire");
        Route::get('view-feedback', ViewFeedback::class)->name('view-feedback');
        Route::get('manage-files', ManageFiles::Class)->name('manage-files');
        Route::get('manage-timeslots', ManageTimeslots::Class)->name('manage-timeslots');
        Route::get('manage-announcements', ManageAnnouncements::Class)->name('manage-announcements');
    });

Route::middleware(['auth', 'student'])->prefix('student')
    ->name('student.')->group(function () {
        Route::get('book-timeslot', BookTimeslot::Class)->name('book-timeslot');
        Route::get('view-announcements', ViewAnnouncements::Class)->name('view-announcements');
        Route::get('view-companies', ViewCompanies::Class)->name('view-companies');
        Route::get('fill-in-questionnaire', FillInQuestionnaire::Class)->name('fill-in-questionnaire');
    });

Route::middleware(['auth', 'company'])->prefix('company')
    ->name('company.')->group(function () {
        Route::get('view-timeslot-schedule', ViewTimeslotSchedule::Class)->name('view-timeslot-schedule');
    });

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    // Route::get('/files/{filename}', function ($filename) {
    //     $path = storage_path('app/public/files/' . $filename);

    //     if (!File::exists($path)) {
    //         abort(404);
    //     }

    //     $file = File::get($path);
    //     $type = File::mimeType($path);

    //     $response = Response::make($file, 200);
    //     $response->header("Content-Type", $type);

    //     return $response;
    // })->name('files.show');

    Route::get('/download/{filename}/{name}', function ($filename, $name) {
        $file_path = storage_path('app/public/files/' . $filename);
        return response()->download($file_path, $name);
    })->name('download');
});
