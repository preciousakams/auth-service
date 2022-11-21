<?php

namespace App\Console\Commands;

use App\Constants\Constants;
use App\Models\IzyInvest\IzyInvestTransaction as IzyInvestIzyInvestTransaction;
use App\Models\IzyInvestTransaction;
use App\Models\IzyInvestUserPlan;
use DB;
use Exception;
use Illuminate\Console\Command;
use Log;

class InterestGeneratorTask extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'interest:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate IzyInvest Interest';

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
        $plans = IzyInvestUserPlan::where([
            ['status', Constants::COMPLETED],
            ['active', 1]
        ])->whereDate('created_at', '<', Date('Y-m-d'))
        ->with('plan:id,monthly_interest')->get();

        if(empty($plans->toArray())) {
            return;
        }

        foreach($plans as $plan) {
            DB::beginTransaction();
            try {
                $plan->status = Constants::PROCESSING;
                $plan->save();

                $transaction = IzyInvestTransaction::where([
                    ['user_id', $plan->user_id],
                    ['type', Constants::INTEREST]
                ])->whereDate('created_at', Date('Y-m-d'))->first();

                if(!empty($transaction)) {
                    continue;
                }

                $interest = $plan->available_balance * ($plan->plan->monthly_interest/30) * 0.01;
                $newBalance = $plan->available_balance + $interest;

                $transaction = new IzyInvestTransaction();
                $transaction->user_id = $plan->user_id;
                $transaction->izyinvest_plan_id = $plan->id;
                $transaction->type = Constants::INTEREST;
                $transaction->amount = $interest;
                $transaction->opening_balance = $plan->available_balance;
                $transaction->closing_balance = $newBalance;
                $transaction->save();

                $plan->available_balance = $newBalance;
                $plan->closing_balance += $interest;
                $plan->status = Constants::COMPLETED;

                if(Date('Y-m-d', strtotime($plan->end_date)) == Date('Y-m-d')) {
                    $plan->active = 0;
                }

                $plan->save();

                DB::commit();
            }catch(Exception $e) {
                Log::error("Unable to Save Interest for user: {$plan->user_id}...");
                Log::error($e->getMessage());
                DB::rollback();
            }

        }
    }
}
