<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class TestFtpDisk extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:ftp';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test ftp disk';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $disk = Storage::disk('media_ftp');

        // Test writing a file
        $disk->put('test.txt', 'Hello, FTP Server!');

        // Test reading the file
        $contents = $disk->get('test.txt');

        // Output the contents
        $this->info("Contents of test.txt: $contents");

        //delete the test file
        $disk->delete('test.txt');
    }
}
