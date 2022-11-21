<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class MigrateReferralPayoutHistory extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payout-history:migrate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate payout history data';

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
        $payoutHistory = \DB::connection('wallet_service')->table('referral_payout_histories')->get();

        foreach ($payoutHistory as $history){
            \DB::table('referral_payout_histories')->updateOrInsert([
                'id' => $history->id,
                'refferral_id' => $history->refferral_id,
            ],[
                'amount' => $history->amount,
                'transaction_status' => $history->transaction_status,
                'transaction_reference' => $history->transaction_reference,
                'transaction_gateway' => $history->transaction_gateway,
                'transaction_details' => $history->transaction_details,
                'admin_id' => $history->admin_id,
                'created_at' => $history->created_at,
                'updated_at' => $history->updated_at,
            ]);
        }
        return 0;
    }
}
