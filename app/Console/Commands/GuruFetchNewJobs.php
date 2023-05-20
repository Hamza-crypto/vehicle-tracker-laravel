<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class GuruFetchNewJobs extends Command
{
    protected $signature = 'guru:fetch-new-jobs';


    protected $description = 'Fetch new jobs from Guru.com';

    public function handle()
    {
        #Add guru controller
        $guru = new \App\Http\Controllers\GuruController();
        $guru->store_jobs();

        return Command::SUCCESS;
    }
}
