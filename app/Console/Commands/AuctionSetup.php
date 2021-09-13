<?php

namespace App\Console\Commands;

use ZipArchive;
use Illuminate\Console\Command;

class AuctionSetup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'auction:setup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automates setting up the backend for use:
        create database.sqlite,
        migrate tables,
        seed test users,
        extract item images';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // file_put_contents(database_path('database.sqlite'), null);
        $this->call('migrate:refresh');
        $this->call('db:seed');
        $this->unzip();
        return 0;
    }

    private function unzip()
    {
        $archive = public_path('archives/items.zip');

        if (!file_exists($archive)) return;

        $zip = new ZipArchive();
        $zip->open($archive, ZipArchive::CREATE);
        $zip->extractTo(public_path('/images'));
        $zip->close();
    }
}
