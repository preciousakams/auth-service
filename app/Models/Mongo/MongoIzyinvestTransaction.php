<?php
namespace App\Models\Mongo;

use Jenssegers\Mongodb\Eloquent\Model;

class MongoIzyinvestTransaction extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'userPlanCalculationHistory';

}
