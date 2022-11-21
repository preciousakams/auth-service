<?php
namespace App\Models\Mongo;

use Jenssegers\Mongodb\Eloquent\Model;

class MongoIzyinvestUserPlan extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'userPlan';
    protected $appends = ['id'];
    protected $hidden = ['_id'];

}
