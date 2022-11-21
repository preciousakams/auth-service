<?php
namespace App\Models\Mongo;

use Jenssegers\Mongodb\Eloquent\Model;

class MongoIzyinvestPlan extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'plan';

}
