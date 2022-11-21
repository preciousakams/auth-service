<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class MigrateVirtualBankAccountData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'virtual-account:migrate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate virtual account';

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
        $accounts = \DB::connection('wallet_service')->table('virtual_bank_accounts')->get();

        foreach ($accounts as $account){
            \DB::table('virtual_bank_accounts')->updateOrInsert([
                'id' => $account->id,
                'user_id' => $account->user_id,
            ],[
                'account_name' => $account->account_name,
                'account_number' => $account->account_number,
                'bank_id' => $account->bank_id,
                'status' => $account->status,
                'created_at' => $account->created_at,
                'updated_at' => $account->updated_at,
            ]);
        }
        return 0;
    }
}
