<?php

namespace App\Initializer;

use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;

class MakeCronTask
{
    use Dispatchable;
    use Queueable;

    /**
     * Execute the job.
     *
     * @return string
     */
    public function handle(): string
    {
        $base_path = base_path();
        $command = $base_path . '/artisan schedule:run >> /dev/null 2>&1';
        $task = '* * * * * php ';
        exec('(crontab -l |  grep -v -F "' . $command . '"; echo "' . $task . $command . '") | crontab -');

        return 'Base cron task for scheduling work created. Task: ' . $task;
    }
}
