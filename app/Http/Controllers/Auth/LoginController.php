<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

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
    protected $redirectTo = RouteServiceProvider::HOME;

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

        $email = $request->email;
        $user = User::where(function($query) use ($email){
            $query->orWhere('email', $email);
            $query->orWhere('username', $email);
        })->first();
        $checkPassword = Hash::check($request->password, $user->password);
        if($checkPassword)
        {
            if ($user->user_type == 3) {
                auth()->attempt(array('email' => $input['email'], 'password' => $input['password']));
                return redirect()->route('admin.home')->with('status',"Selamat anda telah berhasil login");
            }else{
                if ($user->user_type == 2) {
                    auth()->attempt(array('email' => $input['email'], 'password' => $input['password']));
                    return redirect()->route('home');
                }
                else{
                    return redirect()->route('login')
                    ->with('error','Email-Address And Password Are Wrong.');
                }
            }
        }else{
            return redirect()->route('login')
                ->with('error','Email-Address And Password Are Wrong.');
        }
          
    }
}
