<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class MigrateReferralBonusHistory extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bonus-history:migrate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate bonus history data';

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
        $bonusHistory = \DB::connection('wallet_service')->table('referral_bonus_histories')->get();

        foreach ($bonusHistory as $history){
            \DB::table('referral_bonus_histories')->updateOrInsert([
                'id' => $history->id,
                'user_id' => $history->user_id,
            ],[
                'balance' => $history->balance,
                'created_at' => $history->created_at,
                'updated_at' => $history->updated_at,
            ]);
        }

        return 0;
    }
}
