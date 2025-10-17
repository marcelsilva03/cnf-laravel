<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Facades\File;

class StorageLink extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'storage:link-if-not-exists';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create the storage link if it does not exist';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     * @throws BindingResolutionException
     */
    public function handle(): int
    {
        $link = public_path('storage');
        $target = storage_path('app/public');

        if (File::exists($link)) {
            $this->info('The [public/storage] link already exists.');
        } else {
            $this->laravel->make('files')->link($target, $link);
            $this->info('The [public/storage] link has been connected.');
        }

        return 0;
    }
}
