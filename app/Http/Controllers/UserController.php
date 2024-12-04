<?php

namespace App\Http\Controllers;

use App\Models\Projeto;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
   public function registo(Request $request){
    $incomingFields = $request->validate([
        'name' => 'required',
        'email' => 'required',
        'password' => 'required']);
    

    $emailExist = User::where('email', $request->get('email'))->count() > 0;

   

    if($emailExist){
        return redirect()->back()->withErrors(['message'=>'Utilizador já existe']);
    }

    $incomingFields['password'] = bcrypt($incomingFields['password']);
    $user = User::create($incomingFields);
    auth()->login($user);



    return redirect('/');
   }

   public function logout(){
    auth()->logout();
    return redirect('/');

    }

    public function login(Request $request){
        $incomingFields = $request->validate([
            'loginemail' =>  'required',
            'loginpassword' => 'required'
        ]);

        if (auth()->attempt(['email' => $incomingFields['loginemail'], 'password' => $incomingFields['loginpassword']])) {
            $request->session()->regenerate();

            return redirect('/home');
        }

        return redirect('/');

    }


}
