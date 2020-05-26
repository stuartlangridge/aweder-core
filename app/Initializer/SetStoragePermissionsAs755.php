<?php

namespace App\Initializer;

use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class SetStoragePermissionsAs755
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
        $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(base_path('storage')));

        foreach ($iterator as $item) {
            chmod($item->getPathname(), 0775);
        }

        return 'Set the storage permissions as 755.';
    }
}
