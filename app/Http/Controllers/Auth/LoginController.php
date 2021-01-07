<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
//use Illuminate\Foundation\Auth\AuthenticatesUsers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Dato;
use App\User;
//use App\CierreCaja;

class LoginController extends Controller
{
    /**
     *
     */
    public function showLoginForm(){
        $dato = Dato::get();
        return view('auth.login', ['dato'=>$dato]);
    }

    public function login(Request $request){

        $this->validateLogin($request);
        $user = User::select('id_almacen', 'idrole')->where('usuario', '=', $request->usuario)->get();
        //$cierre_caja = CierreCaja::select('id_usuario', 'fecha', 'estado')->where('id_almacen', '=', $codigo)->orderBy('id', 'desc')->take(2)->get();
        if( Auth::attempt(['usuario' => $request->usuario, 'password' => $request->password, 'condicion'=>1])){
            if($user[0]->id_almacen != 0 || ($user[0]->id_almacen == 0 && $user[0]->idrole == 4)){
                return redirect()->route('principal');
            }else{
                return redirect()->route('contenido');
            }
        }

        return back()
        ->withErrors(['usuario' => trans('auth.failed')])
        ->withInput(request(['usuario']));
    }

    protected function validateLogin(Request $request){

        $this->validate($request,[

                'usuario' => 'required|string',
                'password' => 'required|string'
        ]);
    }

    public function logout(Request $request){
        Auth::logout();
        $request->session()->invalidate();
        return redirect('/');

    }

}
