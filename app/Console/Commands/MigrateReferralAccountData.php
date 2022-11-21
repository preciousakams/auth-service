<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class MigrateReferralAccountData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'referral-account:migrate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate referral account data';

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
        $accounts = \DB::connection('wallet_service')->table('referral_account')->get();

        foreach ($accounts as $account){
            \DB::table('referral_account')->updateOrInsert([
                'id' => $account->id,
                'user_id' => $account->user_id,
            ],[
                'balance' => $account->balance,
                'bank_id' => $account->bank_id,
                'account_number' => $account->account_number,
                'account_name' => $account->account_name,
                'deleted_at' => $account->deleted_at,
                'created_at' => $account->created_at,
                'updated_at' => $account->updated_at,
            ]);
        }
        return 0;
    }
}
