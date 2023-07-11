<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\UploadTrait;
use Illuminate\Support\Str;
use Session;
use DB;

class NewsController extends Controller
{
    use UploadTrait;
   //  public function __construct()
   //  {
   //      $this->middleware('role:superadministrator');
   //  }

    public function ViewNews()
    {
        $allnumber = DB::table('flexiloads')->orderBy('id', 'DESC')->get();
        return view('admin.news.view-news',compact('allnumber'));
    }

    public function AddNews()
    {
        return view('admin.news.add-news');
    }

    public function AddNewsSubmit(Request $request)
    {
        $request->validate([
            'number' => ['required'],
            'amount' => ['required'],
            'time' => ['required'],
            'repet_count' => ['required'],
        ]);
          $number = $request->number;
          $amount = $request->amount;
          $time = $request->time;
          $repet_count = $request->repet_count;
          $status = '0';

          $addnews = DB::table('flexiloads')->insert(
            array(
                   'number' => $number, 
                   'amount' => $amount,
                   'how_much_time' => $time,
                   'repet_time' => $repet_count,
                   'status' => $status
                 )
            );
            if($addnews){
                return redirect()->route('view.news')->with('success','You Successfully Add Number!');
            }else{
                return redirect()->route('view.news')->with('error','Your Number are Not Added!');
            }
  
    }

    public function EditNews(Request $request, $id)
    {
        $selectnews =  DB::table('flexiloads')->where('id',$id)->first();
        return view('admin.news.edit-news',compact('selectnews'));
    }

    public function EditNewsSubmit(Request $request, $id)
    {
         $number = $request->number;
         $amount = $request->amount;
         $time = $request->time;
         $repet_count = $request->repet_count;
         $status = '0';
         $update =  DB::table('flexiloads')->where('id', $id)
         ->update(['number' => $number,'amount' => $amount,'how_much_time' => $time,'repet_time' => $repet_count]);
         if($update){
               return redirect()->back()->with('success','You Successfully Update Number');
            }else{
               return redirect()->back()->with('error','Somthing Went To wrong!');
            }
    }

    public function DeleteNews($id){

        $delete =  DB::table('flexiloads')->where('id', $id)->delete();
        if($delete){
            return redirect()->back()->with('success','Number Delete Successfully!');
         }else{
            return redirect()->back()->with('success','Sry Somthing Went To Wrong!');
         }   
    }

    public function activeNews(Request $request, $id)
    {
        $update =  DB::table('flexiloads')->where('id', $id)
         ->update(['status' => 1]);
 
         // Return user back and show a flash message
         if($update){
            return redirect()->back()->with('success','Successfully Update Status!');
         }else{
            return redirect()->back()->with('error','Somthing Went To wrong!');
         }    
    }

    public function deactiveNews(Request $request, $id)
    {
        $update =  DB::table('flexiloads')->where('id', $id)
         ->update(['status' => 0]);
 
         // Return user back and show a flash message
         if($update){
            return redirect()->back()->with('success','Successfully Update Status!');
         }else{
            return redirect()->back()->with('error','Somthing Went To wrong!');
         }  
    }

    public function SingleFlexiload(Request $request, $id)
    {
        
      $selectnews =  DB::table('flexiloads')->where('id',$id)->first();

      $number = $selectnews->number;
      $amount = $selectnews->amount;
      
      $keyid = random_int(0, 999999);
      $keyid = str_pad($keyid, 6, 0, STR_PAD_LEFT);
      
      $type = 1; // 1 = Prepaid / 2 = Postpaid  
      $id = $keyid; // Unique request id
      $user = 'tecno71api'; // User Name
      $key = 'B141QNS09HSNAJNBTAEX026C0AD6R2407PEW26ROLWF8FC2633';
      $operator = 'bl';  //gp,rb,bl,at,sk,tt
      $service = '64'; 

      $url = 'https://easyrecharge24.com/sendapi/request';

      $params = array(
          'number' => $number,
          'amount' => $amount,
          'type' => $type,
          'id' =>$id,	
          'user' => $user,
          'key' => $key,
          'operator' =>$operator,
          'service' =>$service,
        ); 


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

      $url = 'https://easyrecharge24.com/sendapi/request';

      $ch = curl_init($url);
      curl_setopt($ch, CURLOPT_HTTPHEADER,$mheader);
      curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");  
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_POSTFIELDS,$params);
      curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); 
      $result = curl_exec($ch);
      curl_close($ch);  // Seems like g

      $result_fl=json_decode($result);

      $status_l = $result_fl->status;
        //  exit();
        if ($status_l ==1) {
            $addFlgeReport = DB::table('flexiload_reports')->insert(
                array(
                      'number' => $number, 
                      'amount' => $amount,
                      'status' => 'Success'
                     )
                );
                if($addFlgeReport){
                    return redirect()->route('flexiload.report')->with('success','You Successfully Flexiload This Number!');
                }else{
                    return redirect()->route('flexiload.report')->with('error','Somthing Went To wrong!');
                }
        }else{
            return redirect()->back()->with('success','Flexiload Not Complete This Number!');
        }
    }

    public function MultipleFlexiload()
    {
      $selectnews =  DB::table('flexiloads')->where('status',1)->first();
      if($selectnews){
        $id = $selectnews->id;
        $status = $selectnews->status;
        $repet_time = $selectnews->repet_time;
        if($status == '1'){
           if($repet_time != '0'){
               
            //   $user_add = DB::table('blog')->insert([
            //              'title' => "ok",
            //             ]);
            
                    $keyid = random_int(0, 999999);
                    $keyid = str_pad($keyid, 6, 0, STR_PAD_LEFT);
             
              $number = $selectnews->number;
              $amount = $selectnews->amount;
              $type = 1; // 1 = Prepaid / 2 = Postpaid  
              $id = $keyid; // Unique request id
              $user = 'tecno71api'; // User Name
              $key = 'B141QNS09HSNAJNBTAEX026C0AD6R2407PEW26ROLWF8FC2633';
              $operator = 'bl';  //gp,rb,bl,at,sk,tt
              $service = '64'; 
        
              $url = 'https://easyrecharge24.com/sendapi/request';
        
              $params = array(
                  'number' => $number,
                  'amount' => $amount,
                  'type' => $type,
                  'id' =>$id,	
                  'user' => $user,
                  'key' => $key,
                  'operator' =>$operator,
                  'service' =>$service,
                ); 

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
        
              $url = 'https://easyrecharge24.com/sendapi/request';
        
              $ch = curl_init($url);
              curl_setopt($ch, CURLOPT_HTTPHEADER,$mheader);
              curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");  
              curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
              curl_setopt($ch, CURLOPT_POSTFIELDS,$params);
              curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); 
              $result = curl_exec($ch);
              curl_close($ch);  // Seems like g
        
              $result_fl=json_decode($result);
        
              $status_l = $result_fl->status;
                if ($status_l ==1) {
                    $addFlgeReport = DB::table('flexiload_reports')->insert(
                        array(
                              'number' => $number, 
                              'amount' => $amount,
                              'status' => 'Success'
                             )
                        );
                        if($addFlgeReport){
                         $repet_time_update =  DB::table('flexiloads')->where('id', $id)
                         ->update(['repet_time' => $repet_time - 1]);
                         exit();
                      }else{
                          return redirect()->route('flexiload.report')->with('error','Somthing Went To wrong!');
                          exit();
                      }
                }else{
                    return redirect()->back()->with('success','Flexiload Not Complete This Number!');
                    exit();
                }
           }else{
              $update =  DB::table('flexiloads')->where('id', $id)
              ->update(['status' => 0]);
              exit();
           }  
        }else{
           exit();
        }
      }else{
        echo "not status 1";
      }
     
     
    }


    
    public function FlexiloadReport()
    {
        $report = DB::table('flexiload_reports')->orderBy('id', 'DESC')->get();
        return view('admin.news.view-report',compact('report'));
    }
}
