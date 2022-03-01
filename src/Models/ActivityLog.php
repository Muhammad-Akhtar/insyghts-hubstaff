<?php

namespace Insyghts\Hubstaff\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Insyghts\Common\Models\BaseModel;

class ActivityLog extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = [];

    public function listActivityLog($filters=[])
    {
        $limit = !empty($filters['limit']) ? $filters['limit'] : 30;
        $actLogsQuery = ActivityLog::query();
        $actLogsQuery->orderBy('id', 'desc');
        if(count($filters) > 0 ){
            // [
            //     {
            //     "key":"name"
            //     "condition":"like"
            //     "value":"awheed"
            //     },{
            //     "key":"age"
            //     "condition":">",
            //     "value":27
            //     }
            // ]
            foreach($filters as $filter){
                if($filter['condition'] == 'like' || $filter['condition'] == 'LIKE'){
                    $actLogsQuery->where($filter['key'], $filter['condition'], "%{$filter['value']}%");
                }else{
                    $actLogsQuery->where($filter['key'], $filter['condition'], $filter['value']);
                }
            }
        }
        $actLogs = $actLogsQuery->paginate($limit);

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
