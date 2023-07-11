<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\TblRecharge;
use App\Model\TblSim;
use App\Model\TblSlot;
use App\Model\TblUseSlot;
use Carbon\Carbon;
use DB;
use Illuminate\Support\Str;

class FlexiloadController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['Slot'] = TblSlot::where('deleted_by',1)->get();
        $data['allData'] = TblRecharge::where('deleted_by',1)->get();
        return view('admin.flexiload.index',$data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = new TblRecharge();
        $data->slot_id = $request->slot_id;
        $data->amount = $request->amount;
        $data->date = Carbon::now();
        $data->status = '0';
        $data->save();
        return redirect()->back()->with('success','Recharge Added Successfully');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $Slot = TblSlot::where('deleted_by',1)->get();
        $editData = TblRecharge::find($id);
        return view('admin.flexiload.edit',compact('editData','Slot'));
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
        $data = TblRecharge::find($id);
        $data->slot_id = $request->slot_id;
        $data->amount = $request->amount;
        $data->status = $request->status;
        $data->save();
        return redirect()->route('view.flexiload')->with('success','Recharge Updated Successfully');  
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = TblRecharge::where('id',$id)->first();
        $data->deleted_by='0';
        $data->update();
        return redirect()->back()->with('success','Recharge Deleted Successfully');
    }

     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function ViewUseSlot()
    {
        $data['Slot'] = TblSlot::where('deleted_by',1)->get();
        $data['allData'] = TblUseSlot::where('deleted_by',1)->get();
        return view('admin.flexiload.view',$data);
    }
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function ViewUseSlotNameWise(Request $request)
    {
        $data['Slot'] = TblSlot::where('deleted_by',1)->get();
        $data['allData'] = TblUseSlot::where('slot_id',$request->slot_id)->where('deleted_by',1)->get();
        return view('admin.flexiload.slot_name_wise',$data);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function SendFlexiload($id)
    {
       $find_slot_id = TblRecharge::where('id',$id)->first();
       $find = TblSim::where('slot_id',$find_slot_id->slot_id)->where('status',1)->where('fl_status',0)->limit(5)->get();
       
        foreach($find as $row){
            if ($row->number['2'] == '7') {
                $operator = 'gp';
            }
            if ($row->number['2'] == '8') {
                $operator = 'rb';
            }
            if ($row->number['2'] == '5') {
                $operator = 'tt';
            }
            if ($row->number['2'] == '9') {
               echo  $operator = 'bl';
            }
            if ($row->number['2'] == '6') {
               $operator = 'at';
            }

             $keyid = random_int(0, 999999);
             $keyid = str_pad($keyid, 6, 0, STR_PAD_LEFT);
            
             $type = 1; // 1 = Prepaid / 2 = Postpaid  
             $id = $keyid; // Unique request id
             $user = 'tecno71api'; // User Name
             $key = 'B141QNS09HSNAJNBTAEX026C0AD6R2407PEW26ROLWF8FC2633';
             $operator = $operator;  //gp,rb,bl,at,sk,tt
             $service = '64'; 

             $params = array(
                 'number' => $row->number,
                 'amount' => $find_slot_id->amount,
                 'type' => $type,
                 'id' =>$id,	
                 'user' => $user,
                 'key' => $key,
                 'operator' =>$operator,
                 'service' =>$service,
             ); 

            //  print_r($params);
            //  exit();
             
             $flapi_key='B141QNS09HSNAJNBTAEX026C0AD6R2407PEW26ROLWF8FC2633';
             $flapi_userid='tecno71api';
              $header=array(
                   'api-key: '.$flapi_key.'',
                   'api-user: '.$flapi_userid.''
               );
                      
               $header2=array(
                   'band-key: flexisoftwarebd',
               );
              
             $mheader = array_merge($header2, $header); 

            //  $url = 'https://easyrecharge24.com/sendapi/request';

            //  $ch = curl_init($url);
            //  curl_setopt($ch, CURLOPT_HTTPHEADER,$mheader);
            //  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");  
            //  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            //  curl_setopt($ch, CURLOPT_POSTFIELDS,$params);
            //  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); 
            //  $result = curl_exec($ch);
            //  curl_close($ch);  // Seems like g

            //  $result_fl=json_decode($result);
        
            //  $status_l = $result_fl->status;  
             $status_l = 1;
             
            $datad= array();
              if ($status_l ==1) {
            $datad['fl_status']= 1;
              }
              else{
            $datad['fl_status']= 2;
              }
            DB::table('tbl_sim')->where('id', $row->id)->update($datad);

        }

        $allData = TblSim::where('slot_id',$find_slot_id->slot_id)->get();
        return view('admin.flexiload.view_all_fl_list',compact('allData'));
        
        // $data = new TblUseSlot();
        // $data->slot_id = $find_slot_id->slot_id;
        // $data->amount =  $find_slot_id->amount;
        // $data->date = Carbon::now();
        // $data->save();
        // return redirect()->route('view.use.slot.flexiload')->with('success','Recharge Added Successfully');
 
    }
}
