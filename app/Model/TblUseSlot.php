<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class TblUseSlot extends Model
{
    protected $table = "tbl_use_slot";

    public function slotName(){
        return $this->belongsTo(TblSlot::class,'slot_id','id');
    }
}
