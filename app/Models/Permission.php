<?php

namespace App\Models;

use App\Traits\UUID;
use \Spatie\Permission\Models\Permission as BaseModel;

class Permission extends BaseModel
{
    use UUID;

    protected $hidden = [
        'guard_name', 'created_at', 'updated_at'
    ];
}
