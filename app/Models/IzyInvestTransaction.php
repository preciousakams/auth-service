<?php

namespace App\Models;

use App\Models\BaseModel;
use App\Traits\UUID;
use Illuminate\Database\Eloquent\Model;

class IzyInvestTransaction extends Model
{
    use UUID;

    protected $table = 'izyinvest_transactions';

    protected $hidden = [
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function userPlan()
    {
        return $this->belongsTo(IzyInvestUserPlan::class, 'izyinvest_plan_id', 'id');
    }

    public function plan()
    {
        return $this->belongsTo(IzyInvestPlan::class, 'izyinvest_plan_id', 'id');
    }
}
