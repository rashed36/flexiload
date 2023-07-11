<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    // protected function authenticated(Request $request, $user){
    // if($user->hasRole('superadministrator')){
    //     return redirect('/admin');
    // }
    // if($user->hasRole('administrator')){
    //     return redirect('/administrator');
    // }
    // if($user->hasRole('user')){
    //     return redirect('/user');
    // }

    // }
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }


    public function login(Request $request)
    {   
        $input = $request->all();
  
        $this->validate($request, [
            'email' => 'required',
            'password' => 'required',
        ]);
  
        $fieldType = filter_var($request->email, FILTER_VALIDATE_EMAIL) ? 'email' : 'email';
        if(auth()->attempt(array($fieldType => $input['email'], 'password' => $input['password'])))
        // if(auth()->attempt(array($fieldType => $input['email'], 'password' => $input['password'], 'user_ip' => request()->ip() )))
        {
            if(Auth::user()->u_type == 'superadministrator')
            {        
                return redirect()->route('admin');
            }
            if(Auth::user()->u_type == 'administrator')
            {
                 return redirect()->route('administrator');
            }
            else
            {
                return redirect()->route('user');
            }
        }else{
            return redirect()->route('login')
                ->with('error','Email-Address And Password Are Wrong OR IP Not Match!');
        }
          
    }
}
