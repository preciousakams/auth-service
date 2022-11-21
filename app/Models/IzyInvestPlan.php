<?php

namespace App\Models;

use App\Models\BaseModel;
use App\Traits\UUID;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class IzyInvestPlan extends Model
{
    use UUID;
    use SoftDeletes;

    protected $table = 'izyinvest_plans';

    protected $hidden = [
        'deleted_at',
    ];
}
