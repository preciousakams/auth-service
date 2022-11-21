<?php

namespace App\Console\Commands;

use App\Models\IzyInvestPlan;
use App\Models\IzyInvestTransaction;
use App\Models\IzyInvestUserPlan;
use App\Models\Mongo\MongoIzyinvestPlan;
use App\Models\Mongo\MongoIzyinvestTransaction;
use App\Models\Mongo\MongoIzyinvestUserPlan;
use Illuminate\Console\Command;
use Log;

class MigrateIzyinvest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:izyinvest';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate Izyinvest from MongoDB to MySQL';

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
        $this->migratePlans();
        // $this->migrateUserPlans();
        $this->migrateTransactions();

    }

    private function migratePlans()
    {
        $data = MongoIzyinvestPlan::all();

        if(count($data) == 0) {
            return;
        }

        foreach($data as $plan) {
            $check = IzyInvestPlan::where('plan', $plan->plan)->first();
            if($check) {
                continue;
            }

            $model = new IzyInvestPlan();
            $model->plan = $plan->plan;
            $model->monthly_interest = $plan->monthlyInterest;
            $model->min_duration = $plan->minimumDurationInDays;
            $model->min_init_deposit = $plan->minimumFirstDeposit;
            $model->min_recur_deposit = $plan->minimumRecurringDeposit;
            $model->active = $plan->active;
            $model->save();
        }
    }

    private function migrateTransactions()
    {
        $trans = MongoIzyinvestTransaction::all();

        if(count($trans) == 0) {
            return;
        }

        foreach($trans as $item) {
            $userPlan = MongoIzyinvestUserPlan::where('uuid', $item->userPlanUuid)->first();

            if(empty($userPlan)) {
                continue;
            }
            // var_dump($item->toArray());exit;

            $model = new IzyInvestTransaction();
            $model->user_id = $userPlan->createdBy;

            $newUserPlan = $this->migrateUserPlans($userPlan);

            if(!$newUserPlan) {
                continue;
            }

            $model->izyinvest_plan_id = $newUserPlan->id;
            $model->type = 'interest';
            $model->amount = $item->interest;
            $model->opening_balance = $item->previousBalance;
            $model->closing_balance = $item->newBalance;
            $model->save();
        }
    }

    private function migrateUserPlans($item)
    {
        $plan = IzyInvestPlan::where('plan', $item->plan)->first();
        if(empty($plan)) {
            Log::error("Plan {$item->plan} does not exist");
            return false;
        }

        $bank = \DB::connection('wallet_service')->table('banks')->where('name', $item->bank)->first();

        $UserPlan = new IzyInvestUserPlan();
        $UserPlan->izyinvest_plan_id = $plan->id;
        $UserPlan->user_id = $item->createdBy;
        $UserPlan->bank_id = $bank->id ?? null;
        $UserPlan->account_name = $item->accountName;
        $UserPlan->account_number = $item->accountNumber;
        $UserPlan->monthly_interest = $item->monthlyInterest;
        $UserPlan->daily_interest = $item->dailyInterest;
        $UserPlan->duration = $item->minimumDurationInDays;
        $UserPlan->deposit_count = $item->depositCount;
        $UserPlan->contributory_balance = $item->contributoryBalance;
        $UserPlan->available_balance = $item->availableBalance;
        $UserPlan->closing_balance = $item->closingBalance;
        $UserPlan->status = $item->completed ? 'completed' : 'processing';
        $UserPlan->active = $item->active;
        $UserPlan->end_date = $item->payoutDate->toDateTime()->format('Y-m-d H:i:s');
        $UserPlan->created_at = $item->createdDate->toDateTime()->format('Y-m-d H:i:s');
        $UserPlan->save();

        return $UserPlan;
    }
}
