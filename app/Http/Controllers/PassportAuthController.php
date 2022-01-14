<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Validator;
use App\Models\User;

class PassportAuthController extends Controller
{
    /**
     * Registration
     */
    public function register(Request $request)
    {

        $scope = $this->get_scope($request->rol_id);

        if (!$scope) {
            return response()->json(['message' => 'rol_id erroneo'], 400);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|min:4',
            'email' => 'required|unique:users|email',
            'password' => 'required|min:8',
            'rol_id' => 'required',
        ],[
            'name.required' => 'El nombre es requerido.',
            'email.required' => 'El correo es requerido.',
            'email.unique' => 'El correo ya se encuentra registrado.',
            'password.required' => 'La contraseñas es requerida.',
            'rol_id.required' => 'El rol_id es requerido.',
        ]);

        if ( $validator->fails() ) {
           return response()->json(['message' => $validator->errors()->first()], 400);
        }
 
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'rol_id' => $request->rol_id
        ]);

       
        $token = $user->createToken('LaravelAuthApp', [$scope])->accessToken;
 
        return response()->json(['token' => $token], 200);
    }
 
    /**
     * Login
     */
    public function login(Request $request)
    {
        $data = [
            'email' => $request->email,
            'password' => $request->password
        ];

        $user_rol_id = User::where('email', $request->email)->value('rol_id');

        $scope = $this->get_scope($user_rol_id);
 
        if (auth()->attempt($data)) {
            $token = auth()->user()->createToken('LaravelAuthApp',[$scope])->accessToken;
            return response()->json(['token' => $token], 200);
        } else {
            return response()->json(['error' => 'Unauthorised'], 401);
        }
    }

    public function logout (Request $request)
    {
        Auth::user()->token()->revoke();
        return response()->json(['message' => 'sesion cerrada'], 200);
    }
    
    public function check_session (Request $request)
    {
        return response()->json(['user_rol' => $request->user()->rol_id], 200);
    }

    public function get_scope ($rol_id)
    {
        switch ($rol_id) {
            case 1:
                return 'admin';
                break;

            case 2:
                return 'cliente';
                break;
            
            default:
                return false;
                break;
        }
    }
}