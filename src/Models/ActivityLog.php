<?php

namespace Insyghts\Hubstaff\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Insyghts\Common\Models\BaseModel;

class ActivityLog extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = [];
    
    public function saveRecord($data)
    {
        $activityLogs = [];
        array_push($activityLogs, $data);
        $inserted = ActivityLog::insert($activityLogs);
        if($inserted){
            $inserted = ActivityLog::latest()->first();
        }
        return $inserted;
    }
}
