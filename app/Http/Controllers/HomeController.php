<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
//        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('admin.dashboard');
    }
    public function post_invite(Request $request){
        $this->validate($request, [
            'email' => 'required',
        ]);
        $user = \App\Models\User::where('email',$request->email)->first();
        if($user){
            return redirect()->back()->with('error','This email is already stored');
        }else{
            $check_invite = \App\Models\InviteUser::where('email',$request->email)->first();
            if ($check_invite) {
                return redirect()->back()->with('error', 'This email is already invited');
            }
            $invite = new \App\Models\InviteUser();
            $invite->email = $request->email;
            $invite->token = md5($request->email);
            $invite->save();
            $data = array(
                'email'=>$request->email,
                'token'=>md5($request->email)
            );
            Mail::to($request->email)->send(new \App\Mail\InviteMail($data));
            return redirect()->back()->with('success', 'Successfully invited');
        }
    }
    
    public function user_register($invite_code = 'null'){
        $invite = \App\Models\InviteUser::where('token',$invite_code)->first();
        if($invite){
            return view('register');
        }else{
            echo '<h1>This is only for invited user</h1>';
        }
    }
    public function user_register_store(Request $request){
        $this->validate($request, [
            'email' => 'required|email|unique:users',
            'name'=>'required',
            'password' => 'required',
        ]);
        $user = new \App\Models\User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->remember_token = rand(10000, 99999);
        $user->save();
        $invite = \App\Models\InviteUser::where('email',$request->email)->first();
        if($invite){
            $invite = \App\Models\InviteUser::find($invite->id)->delete();
        }
        
        $token = \Illuminate\Support\Facades\Crypt::encryptString($request->email);
        $data = array(
                'email'=>$request->email,
            'name'=> $request->name,
                'code'=>$user->remember_token,
               'token'=>  $token
            );
        Mail::to($request->email)->send(new \App\Mail\VerifiactionEmail($data));
        return redirect()->route('verify.user',$token)->with('success', 'Successfully invited');
    }
    public function verify_user($token){
        $email = \Illuminate\Support\Facades\Crypt::decryptString($token);
        $user = \App\Models\User::where('email',$email)->where('user_active',0)->first();
        if($user){
            return view('user_verify',compact('email'));
        }else{
            echo '<h1>This is invalide request</h1>';
        }
    }
    public function verification(Request $request){
        $this->validate($request, [
            'email' => 'required',
            'verification_code' => 'required',
        ]);
        $user = \App\Models\User::where('email',$request->email)->where('remember_token',$request->verification_code)->first();
        if($user){
            $user = \App\Models\User::find($user->id);
            $user->user_active =1;
            $user->email_verified_at = date('Y-m-d H:i:s');
            $user->save();
            return redirect()->route('login')->with('success', 'Successfully Verified');
        }else{
             echo '<h1>Invalid Verification code</h1>';
        }
    }
    
    public function profile(){
        $this->middleware('auth');
        return view('profile');
    }
    public function update_profile(Request $request){
        $this->validate($request, [
            'email' => 'required',
            'name'=>'required',
        ]);
        $user->name = $request->name;
        if($request->password && $request->password == $request->confirm_password){
            $user->password = Hash::make($request->password);
        }
        return redirect()->back()->with('success', 'Successfully invited');
    }
}
