<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\NotifyReleaseApp;

class ReleaseNewVersion extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'xme:release_app_version';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command will dispatch the event of release new version of app.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $myfile = fopen("version", "w") or die("Unable to open version file!");
        $txt = time();
        fwrite($myfile, $txt);
        fclose($myfile);
        
        dispatch(new NotifyReleaseApp());
        $this->info('Version '.$txt.' released.');
        return Command::SUCCESS;
    }
}
