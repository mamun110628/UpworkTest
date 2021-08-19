<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use \App\Models\User;
class LoginController extends Controller
{
    public function login(Request $request){
        $this->validate($request, [
            'email' => 'required',
            'password' => 'required',
        ]);
        $email = $request->input('email');
        $password = $request->input('password');

        if (Auth::attempt(['email' => $email, 'password' => $password, 'user_active' => 1])) {
            $user = User::where('id', \Auth::user()->id)->first();
            if ($user->is_admin == 1) {
                Auth::guard('admin')->login($user);
                return redirect('/admin/dashboard');
            } else {
                Auth::guard('web')->login($user);
                return redirect('/');
            }
        } else {
            return redirect()->back()
                            ->with('error', 'Email-Address or Password is wrong.');
        }
    }
    public function logout(Request $request) {
         Auth::guard('admin')->logout();

        $request->session()->invalidate();

        return redirect('/login');
    }
}
