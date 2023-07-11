<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Rules\MatchOldPassword;
use Illuminate\Support\Facades\Hash;
use App\User;
use App\Traits\UploadTrait;
use Illuminate\Support\Str;
use Session;

class SettingController extends Controller
{
    use UploadTrait;

    // public function __construct()
    // {
    //     $this->middleware('role:superadministrator');
    // }
    

    public function ChangePassword()
    {
        return view('admin.setting.change_password');
    }

    public function ChangePasswordSubmit(Request $request)
    {
        $request->validate([
            'current_password' => ['required', new MatchOldPassword],
            'new_password' => ['required'],
            'new_confirm_password' => ['same:new_password'],
        ]);
        User::find(auth()->user()->id)->update(['password'=> Hash::make($request->new_password)]);

        session::flash('success','You Successfully Changed Profile.');
        
        return redirect()->back()->with('success','Password Update Successfully!');;
    }

    public function UpdateProfile()
    {
        return view('admin.setting.update_profile');
    }

    public function UpdateProfileSubmit(Request $request)
    {
          // Get current user
          $user = User::findOrFail(auth()->user()->id);
          // Set user name
          $user->name = $request->input('name');
          $user->mobile_no = $request->input('mobile_no');
          $user->user_ip = $request->input('user_ip');
  
          // Check if a profile image has been uploaded
          if ($request->has('profile_image')) {
              $imageName = time().'.'.$request->profile_image->extension();  
              $request->profile_image->move(public_path('storage/images/admin'), $imageName);
              $user->profile_image = 'https://flexiload.techno71bd.com/public'.'/storage/images/admin/'.$imageName;
          }
          // Persist user record to database
          $user->save();
  
          // Return user back and show a flash message
          return redirect()->route('setting.update.profile')->with('success','You Successfully Changed Profile!');
    }
}
