<?php

namespace Insyghts\Hubstaff\Services;

use Exception;
use Illuminate\Support\Facades\Session;
use Insyghts\Hubstaff\Models\ServerTimestamp;

class HubstaffServerService
{
    function __construct(ServerTimestamp $serverTimestamp)
    {
        $this->serverTimestamp = $serverTimestamp;  
    }

    public function getTimestamp()
    {
        $response = [
            'success' => false,
            'data' => "Something went wrong"
        ];
        $timestring = strtotime(gmdate('Y-m-d G:i:s'));
        $response['data'] = $timestring;

        return $response;
        // try {
        //     $timestring = $this->serverTimestamp->getTimeString();
        //     if (!$timestring) {
        //         // First time set timestamp and return this timestamp
        //         $timestring = $this->saveTimestamp();
        //         if(!empty($timestring)){
        //             $response['success'] = true;
        //             $response['data'] = (int)$timestring->server_timestamp;
        //         }
        //     }else{
        //         $response['success'] = true;
        //         $response['data'] = (int)$timestring->server_timestamp;
        //     }
        // } catch (Exception $e) {
        //     $show = get_class($e) == 'Illuminate\Database\QueryException' ? false : true;
        //     if($show){
        //         $response['data'] = $e->getMessage();
        //     }
        // } finally {
        //     return $response;
        // }
    }

    public function saveTimestamp()
    {
        // $timestring = strtotime(gmdate('Y-m-d G:i:s'));
        // $data = [
        //     'server_timestamp' => $timestring,
        //     'created_at' => gmdate('Y-m-d G:i:s', $timestring),
        //     'updated_at' => gmdate('Y-m-d G:i:s', $timestring)
        // ];
        
        // $result = $this->serverTimestamp->saveTimeString($data);
        // return $result;
    }
}
