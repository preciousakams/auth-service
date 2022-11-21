<?php

namespace App\Console\Commands;

use App\Constants\GoogleSheets\UserReport;
use App\Models\User;
use App\Services\GoogleSheets;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ScheduledTask extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scheduled:task';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scheduler Task';

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
    public function handle(): int
    {
        $users = User::select('first_name', 'last_name', 'email', 'phone', 'email_verified_at', 'created_at')
            ->whereDate('created_at', Date('Y-m-d'))
            ->whereNotNull('email_verified_at')
            ->get();

        $SheetService = new GoogleSheets(UserReport::SHEET_ID, UserReport::USERS_REPORT_SHEET);
        if(!empty($users->toArray())) {
            $data = [];

            foreach ($users as $user) {
                $data[] = [
                    $user->first_name ?? '',
                    $user->last_name ?? '',
                    $user->email,
                    $user->phone ?? '',
                    $user->is_email_verified,
                    !empty($user->created_at) ? ltrim($user->created_at->toDateTimeString(), "'") : ''
                ];
            }
            $SheetService->addEntry($data);
        }

        return 0;

    }
}
