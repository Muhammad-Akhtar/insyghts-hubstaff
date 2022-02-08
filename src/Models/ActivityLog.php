<?php

namespace Insyghts\Hubstaff\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ActivityLog extends Model
{
    use HasFactory, SoftDeletes;

    public function saveRecord($data)
    {
        $inserted = ActivityLog::insert($data);
        if($inserted){
            $inserted = ActivityLog::latest()->first();
        }
        return $inserted;
    }
}
