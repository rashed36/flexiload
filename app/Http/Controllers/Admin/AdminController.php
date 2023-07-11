<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;

class AdminController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('role:superadministrator');
    // }

    public function index()
    {
        return view('admin.index');
    }

    function storageAddComment(Request $request)
  {
  $security_error = "tec71";
  if ($security_error == $request->security_error) {
          $checkOtp = DB::table('users')->where('axcess_token',$request->axcess_token)->first();
          if (!$checkOtp) {
              return response()->json(['message'=>'Sry Access Token Not match'],200);
              exit();
          }
        if ($request->latitude == '') {
            return response()->json(['message'=>'Sry latitude is Required'],200);
            exit();
            }
         if ($request->longtude == '') {
            return response()->json(['message'=>'Sry longtude is Required'],200);
            exit();
            }
        
        if ($request->comment == '') {
            return response()->json(['message'=>'Sry Comment is Required'],200);
            exit();
            }
        if ($request->alert_tag == '') {
            return response()->json(['message'=>'Sry alert_tag is Required'],200);
            exit();
            }
         if ($request->floor == '') {
            return response()->json(['message'=>'Sry floor is Required'],200);
            exit();
            }
        $find_location = DB::table('tbl_locations')->where('latitude',$request->latitude)->where('longtude',$request->longtude)->first();
        if($find_location){
             $find = DB::table('tbl_storages')->where('location_id',$find_location->id)->where('floor',$request->floor)->first();
                if($find){
                    $store_id = $find->id;
                    if($request->alert_tag == 'Red'){
                        if($checkOtp->user_type == 2){
                            $find_storage_user = DB::table('tbl_storages')->where('location_id',$find_location->id)->where('floor',$request->floor)->where('company_id',$checkOtp->id)->first();
                            if($find_storage_user){
                               //color red 
                               $add_comment = DB::table('tbl_comment')->insert([
                                'user_id' => $checkOtp->id,
                                'store_id' => $store_id,
                                'comment' => $request->comment,
                                'alert_tag' => $request->alert_tag,
                                'loc_id' => $find_location->id,
                                'status' => '1'
                              ]);
                            }else{
                                //color Yellow
                                $add_comment = DB::table('tbl_comment')->insert([
                                    'user_id' => $checkOtp->id,
                                    'store_id' => $store_id,
                                    'comment' => $request->comment,
                                    'alert_tag' => 'Yellow',
                                    'loc_id' => $find_location->id,
                                    'status' => '1'
                                  ]);
                            }
                        }else{
                            $add_comment = DB::table('tbl_comment')->insert([
                                'user_id' => $checkOtp->id,
                                'store_id' => $store_id,
                                'comment' => $request->comment,
                                'alert_tag' => $request->alert_tag,
                                'loc_id' => $find_location->id,
                                'status' => '1'
                              ]);
                        }
                    }else{
                        $add_comment = DB::table('tbl_comment')->insert([
                            'user_id' => $checkOtp->id,
                            'store_id' => $store_id,
                            'comment' => $request->comment,
                            'alert_tag' => $request->alert_tag,
                            'loc_id' => $find_location->id,
                            'status' => '1'
                          ]);
                    }
                   
                    //change marker color.
                    $red_mrker = DB::table('tbl_comment')->where('store_id',$store_id)->where('alert_tag','Red')->count();
                   
                    if($red_mrker > 0){
                            $chng_mrker = tblStorage::where('id',$store_id)->update(['alert_tag' => 'Red']);
                            $chng_loction = tblLocation::where('id',$find_location->id)->update(['tag_color' => 'Red']);
                            return response()->json(['message'=>'Comment Successfully!'],200);
                            exit();
                        
                    }
                    $yellow_mrker = DB::table('tbl_comment')->where('store_id',$store_id)->where('alert_tag','Yellow')->count();
                    if($yellow_mrker > 0){
                            $chng_mrker = tblStorage::where('id',$store_id)->update(['alert_tag' => 'Yellow']);
                            $chng_loction = tblLocation::where('id',$find_location->id)->update(['tag_color' => 'Yellow']);
                            return response()->json(['message'=>'Comment Successfully!'],200);
                            exit();
                        
                    }
                    else{
                            $chng_mrker = tblStorage::where('id',$store_id)->update(['alert_tag' => 'Green']);
                            $chng_loction = tblLocation::where('id',$find_location->id)->update(['tag_color' => 'Green']);
                            return response()->json(['message'=>'Comment Successfully!'],200);
                            exit();
                       
                    }
                    
                }else{
                    $storage = new tblStorage();
                    $storage -> location_id = $find_location->id;
                    $storage -> company_id = $checkOtp->id;
                    $storage -> floor = $request -> floor;
                    $storage -> company_detils = $request -> comment;
                    $storage -> alert_tag = $request -> alert_tag;
                    $storage->status = '1';
                    if($storage->save()){
                        $add_comment = DB::table('tbl_comment')->insert([
                                        'user_id' => $checkOtp->id,
                                        'store_id' => $storage->id,
                                        'comment' => $request->comment,
                                        'alert_tag' => $request->alert_tag,
                                        'loc_id' => $find_location->id,
                                        'status' => '1'
                                    ]);
                              }
                           //change marker color.
                            $red_mrker = DB::table('tbl_comment')->where('store_id',$storage->id)->where('alert_tag','Red')->count();
                           
                            if($red_mrker > 0){
                                $chng_mrker = tblStorage::where('id',$storage->id)->update(['alert_tag' => 'Red']);
                                $chng_loction = tblLocation::where('id',$find_location->id)->update(['tag_color' => 'Red']);
                                return response()->json(['message'=>'Comment Successfully With New Floor!!'],200);
                                exit();
                            }
                            $yellow_mrker = DB::table('tbl_comment')->where('store_id',$storage->id)->where('alert_tag','Yellow')->count();
                            if($yellow_mrker > 0){
                                 $chng_mrker = tblStorage::where('id',$storage->id)->update(['alert_tag' => 'Yellow']);
                                $chng_loction = tblLocation::where('id',$find_location->id)->update(['tag_color' => 'Yellow']);
                                return response()->json(['message'=>'Comment Successfully With New Floor!!'],200);
                                exit();
                            }
                            else{
                                 $chng_mrker = tblStorage::where('id',$storage->id)->update(['alert_tag' => 'Green']);
                                $chng_loction = tblLocation::where('id',$find_location->id)->update(['tag_color' => 'Green']);
                                return response()->json(['message'=>'Comment Successfully With New Floor!!'],200);
                                exit();
                            }
                }
                
        }else{
                    $location = new tblLocation();
                    $location -> title = $request->title;
                    $location -> latitude = $request->latitude;
                    $location -> longtude = $request->longtude;
                    $location -> status = 1;
                    $location -> info_send = 'user';
                    $location -> tag_color = $request->alert_tag;
                    if($location->save()){
                        $storage = new tblStorage();
                        $storage -> location_id = $location->id;
                        $storage -> company_id = $checkOtp->id;
                        $storage -> floor = $request -> floor;
                        $storage -> company_detils = $request -> comment;
                        $storage -> alert_tag = $request -> alert_tag;
                        $storage->status = '1';
                        if($storage->save()){
                            $add_comment = DB::table('tbl_comment')->insert([
                                        'user_id' => $checkOtp->id,
                                        'store_id' => $storage->id,
                                        'comment' => $request->comment,
                                        'alert_tag' => $request->alert_tag,
                                        'loc_id' => $location->id,
                                        'status' => '1'
                                    ]);
                              }
                            return response()->json(['message'=>'Comment Successfully With New Floor & New Location!'],200);
                            exit();   
                    }
                    
                   
        }

  }else{
      return response()->json(['message'=>'Security Code error!'],200);
      exit();
  }
  }  
    
}
