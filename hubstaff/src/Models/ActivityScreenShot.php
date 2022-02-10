<?php

namespace Insyghts\Hubstaff\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ActivityScreenShot extends Model
{
    use HasFactory, SoftDeletes;

    public function saveRecord($bulk_data)
    {
        $result = false;
        $result = ActivityScreenShot::Insert($bulk_data);
        return $result;
    }
}
