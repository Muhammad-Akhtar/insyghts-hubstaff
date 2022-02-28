<?php

namespace Insyghts\Hubstaff\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServerTimestamp extends Model
{
    use HasFactory;
    protected $table = 'server_timestamps';

    public function getTimeString()
    {
        $timestring = ServerTimestamp::first();
        if(empty($timestring)){
            $timestring = false;
        }
        return $timestring;
    }

    public function saveTimeString($data)
    {
        $timeStamp = new ServerTimestamp();
        $timeStamp->server_timestamp = (string)$data['server_timestamp'];
        $timeStamp->created_at = $data['created_at'];
        $timeStamp->updated_at = $data['updated_at'];
        $result = $timeStamp->save();
        if($result){
            return $timeStamp;
        }
        return false;
    }

    public function updateClock($newTimeString)
    {
        $timeStamp = ServerTimestamp::first();
        $timeStamp->server_timestamp = $newTimeString;
        $timeStamp->updated_at = gmdate('Y-m-d G:i:s', $newTimeString);
        $timeStamp->save();
    }
}
