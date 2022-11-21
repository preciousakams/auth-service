<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class MigrateIzyPayData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'izypay:migrate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate Izypay Data';

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
        $izypay = \DB::connection('wallet_service')->table('izypay')->get();

        foreach ($izypay as $pay){

            \DB::table('izypay')->updateOrInsert([
                'id' => $pay->id,
                'user_id' => $pay->user_id,
            ],[
                'balance' => $pay->balance,
                'deleted_at' => $pay->deleted_at,
                'created_at' => $pay->created_at,
                'updated_at' => $pay->updated_at
            ]);
        }

        return 0;
    }
}
