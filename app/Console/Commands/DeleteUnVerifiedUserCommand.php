<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;

class DeleteUnVerifiedUserCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'unverifieduser:delete';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete non verified users 48hours after registration';

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
     */
    public function handle()
    {
        DB::table('users')
            ->whereNull('email_verified_at')
            ->whereDate('created_at', '<', now()->subDays(2))
            ->delete();
    }
}
