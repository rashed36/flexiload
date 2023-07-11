<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class TblRecharge extends Model
{
    protected $table = "tbl_recharge";

    public function slotName(){
        return $this->belongsTo(TblSlot::class,'slot_id','id');
    }
}