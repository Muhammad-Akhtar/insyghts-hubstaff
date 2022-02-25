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

    public function listActivityLog($filters=[])
    {
        $actLogsQuery = ActivityLog::query();
        $actLogsQuery->orderBy('id', 'desc');
        if(count($filters) > 0 ){
            if(isset($filters['user_id']) && !is_null($filters['user_id'])) {
                $actLogsQuery->where('user_id','=',$filters['user_id']);
            }
            if(isset($filters['activity_date']) && !is_null($filters['activity_date'])){
                $activityDate = gmdate('Y-m-d G:i:s', strtotime($filters['activity_date']));
                $actLogsQuery->where('activity_date','=',$activityDate);
            }
            if(isset($filters['time_type']) && !is_null($filters['time_type'])){
                $actLogsQuery->where('time_type','=',$filters['time_type']);
            }
        }
        $actLogs = $actLogsQuery->paginate(30);
        return $actLogs;
    }

    public function saveRecord($data)
    {
        // $activityLogs = [];
        // array_push($activityLogs, $data);
        $inserted = ActivityLog::insert($data);
        if($inserted){
            $inserted = ActivityLog::latest()->first();
        }
        return $inserted;
    }

    public function deleteActivityLog($id, &$response)
    {
        $actLog = ActivityLog::find($id);
        if(!$actLog){
            $response['data'] = "No recod with id: {$id} found!";
        }
        if($actLog->delete()){
            $actLog->deleted_by = app('loginUser')->getUser()->id;
            $actLog->save();
        }
        return $actLog;
    }
}
