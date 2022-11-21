<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class MigrateBankData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bank:migrate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate bank data';

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
        $banks = \DB::connection('wallet_service')->table('banks')->get();

        foreach ($banks as $bank){
            \DB::table('banks')->insert([
                'id' => $bank->id,
                'name' => $bank->name,
                'alias' => $bank->alias,
                'code' => $bank->code,
                'created_at' => $bank->created_at,
                'updated_at' => $bank->updated_at,
            ]);
        }

        return 0;
    }
}
