<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class MigrateReferralConfig extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'referral-config:migrate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate referral config data';

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
        $config = \DB::connection('wallet_service')->table('referral_config')->first();

        if ($config){
            \DB::table('referral_config')->updateOrInsert([
                'id' => $config->id,
            ],[
                'commission_percentage' => $config->commission_percentage,
                'created_at' => $config->created_at,
                'updated_at' => $config->updated_at,
            ]);
        }
        return 0;
    }
}
