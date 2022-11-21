<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class MigrateTransactionData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'transaction:migrate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate transaction data';

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
        $transactions = \DB::connection('wallet_service')->table('transactions')->get();

        foreach ($transactions as $transaction){
            \DB::table('transactions')->updateOrInsert([
                'id' => $transaction->id,
                'reference' => $transaction->reference,
            ],[
                'amount' => $transaction->amount,
                'type' => $transaction->type,
                'meta' => $transaction->meta,
                'transactionable_id' => $transaction->transactionable_id,
                'transactionable_type' => $transaction->transactionable_type,
                'deleted_at' => $transaction->deleted_at,
                'created_at' => $transaction->created_at,
                'updated_at' => $transaction->updated_at,
            ]);
        }
        return 0;
    }
}
