<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UpdatePasswordController extends Controller
{
    public function index(){
        return view('pages.user-pages.update-password');
    }

    public function update(Request $request){   
        
        $rules = [
            'current-password' => 'required',
            'new-password' => 'required|min:8|confirmed',
        ];

        if($request->email != auth()->user()->email){
            $rules['email'] = 'required|email:dns|unique:users|max:255';    
        }
        
        if(!Hash::check($request->get('current-password'), Auth::user()->password)) {
            //if current pass doesnt match the record in database
            return redirect()->back()->with('error', 'Your current password does not match with our record');
        }

        if(strcmp($request->get('current-password'), $request->get('new-password')) == 0 ){
            //strcmp is used to compare string and return integer
            //if the result == 0 || both string are same
            return redirect()->back()->with('error', 'New Password cannot be same as your current password!');
        }

        $validatedData = $request->validate($rules);

        // update password in database based on username
        $email = auth()->user()->email;

        DB::table('users')
            ->where('email' , $email )
            ->update([
                'password' => Hash::make($validatedData['new-password']),
                'email' => $request->get('email')
            ]);
        
        return redirect()->back()->with("updated", "Your data has been updated!");
    }
}
