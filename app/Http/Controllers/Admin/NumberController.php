<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\TblRecharge;
use App\Model\TblSim;
use App\Model\TblSlot;
use App\Model\TblUseSlot;

class NumberController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function ViewAllSlotNumber()
    {
        $data['allData'] = TblSlot::where('deleted_by',1)->get();
        return view('admin.number.index',$data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function StoreSlotNumber(Request $request)
    {
        $check = TblSim::where('number',$request->number)->exists();
        if($check){
            return redirect()->back()->with('error','Number Olredy Exist!');  
        }else{
        $data = new TblSim();
        $data->slot_id = $request->slot_id;
        $data->number = $request->number;
        $data->save();
        return redirect()->back()->with('success','Number Added Successfully');
        }
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $data['allData'] = TblSim::where('slot_id',$id)->where('deleted_by',1)->get();
        $data['slot_name'] = TblSlot::where('id',$id)->first();
        return view('admin.number.view_slot_number',$data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,$id)
    {
        $check = TblSim::where('number',$request->number)->exists();
        if($check){
            return redirect()->back()->with('error','Number Olredy Exist!');  
        }else{
            $data = new TblSim();
            $data->slot_id = $id;
            $data->number = $request->number;
            $data->save();
            return redirect()->back()->with('success','Number Added Successfully');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $editData = TblSim::find($id);
        return view('admin.flexiload.edit',compact('editData'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = TblSim::find($id);
        $data->slot_id = $request->slot_id;
        $data->number = $request->number;
        $data->save();
        return redirect()->back()->with('success','Number Updated Successfully');  
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = TblSim::where('id',$id)->delete();
        return redirect()->back()->with('success','Number Deleted Successfully');
    }
     /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editSlot($id)
    {
        $editData = TblSlot::find($id);
        return view('admin.number.add_note',compact('editData'));
    }
     /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function AddNote(Request $request, $id)
    {
        $data = TblSlot::find($id);
        $data->note = $request->note;
        $data->date = $request->date;
        if($data->save()){
            $data = new TblUseSlot();
            $data->slot_id = $id;
            $data->note = $request->note;
            $data->date = $request->date;
            $data->save();  
        }
        return redirect()->route('view.slot.number')->with('success','Note Added This Slot Successfully');  
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function on($id)
    {
        $data = TblSim::find($id);
        $data->status='1';
        $data->update();
        return redirect()->back()->with('success','Status Updated Successfully');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function off($id)
    {
        $data = TblSim::find($id);
        $data->status='0';
        $data->update();
        return redirect()->back()->with('success','Status Updated Successfully');
    }
     /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function ActiveSlotNumber($id)
    {
        $data = TblSim::find($id);
        $data->is_checked='1';
        $data->update();
        return redirect()->back()->with('success','Status Updated Successfully');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function DactiveSlotNumber($id)
    {
        $data = TblSim::find($id);
        $data->is_checked='0';
        $data->update();
        return redirect()->back()->with('success','Status Updated Successfully');
    }
     /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function ResetSlotNumber($id)
    {
        $update = TblSim::where('slot_id',$id)->update(array('fl_status'=>'0'));
       if($update){
        return redirect()->back()->with('success','Status Updated Successfully');
       }else{
        return redirect()->back()->with('error','Status Not Updated Successfully');
       }
        
    }
}
