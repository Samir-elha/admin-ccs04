<?php

namespace App\Console;

use App\Mail\AnnouncementMail;
use App\Models\Announcement;
use App\Models\User;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();
        $schedule->call(function () {
            // Get the timestamp of the last scheduled task run
            $lastRun = Carbon::parse($this->app['config']['scheduler.last_run']);

            // Get all the announcements posted since the last scheduled task run
            $announcements = Announcement::where('postDate', '<=', Carbon::now())->where('isVisible', '=', false)->get();

            // Send an email notification for each newly posted announcement
            $students = User::where('type_id','=',1)->get();
            $subject = "New Announcement";
            foreach ($announcements as $announcement) {
                foreach ($students as $student) {
                    Mail::to($student->email)->send(new AnnouncementMail($student,$subject));
                }
                $announcement->isVisible = true;
                $announcement->save();
            }

            // Update the timestamp of the last scheduled task run to the current time
            $this->app['config']['scheduler.last_run'] = Carbon::now()->toDateTimeString();

        })->everyMinute();

    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
