<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

use App\Models\User;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email|min:3|max:255',
                'password' => 'required|min:3|max:255',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'error' => $validator->errors()
                ], 400);
            }

            $validated = $validator->validated();

            $user = User::where('email', $validated['email'])->first();

            if (!$user) {
                return response()->json([
                    'error' => 'No existe el usuario con ese correo'
                ], 400);
            }

            if(!Hash::check($validated['password'], $user->password)) {
                return response()->json([
                    'error' => 'Contraseña incorrecta'
                ], 400);
            } else {
                if(Auth::attempt(['email' => $validated['email'], 'password' => $validated['password']])) {
                    return response()->json([
                        'success' => $user->createToken($user->name)->plainTextToken,
                        'user' => [
                            'id' => $user->id,
                            'name' => $user->name,
                            'email' => $user->email,
                            'is_admin' => $user->is_admin,
                        ],
                    ], 200);
                }
            }
        } catch (\Throwable $th) {
            logger()->error($th->getMessage());

            return response()->json([
                'error' => 'Error en el login, intente de nuevo mas tarde'
            ], 400);
        }
    }

    public function register(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|min:3|max:255',
                'email' => 'required|email|min:3|max:255|unique:users',
                'password' => 'required|min:3|max:255',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'error' => $validator->errors()
                ], 400);
            }

            $validated = $validator->validated();

            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
            ]);

            return response()->json([
                'success' => $user->createToken($user->name)->plainTextToken,
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'is_admin' => $user->is_admin,
                ],
            ], 200);

        } catch (\Throwable $th) {
            logger()->error($th->getMessage());

            return response()->json([
                'error' => 'Error al registrar, intente de nuevo mas tarde'
            ], 400);
        }
    }

    public function logout(Request $request)
    {
        try {
            $request->user()->currentAccessToken()->delete();

            return response()->json([
                'success' => 'Sesión cerrada correctamente'
            ], 200);
        } catch (\Throwable $th) {
            logger()->error($th->getMessage());

            return response()->json([
                'error' => 'Error al cerrar sesión, intente de nuevo mas tarde'
            ], 400);
        }
    }

    public function user(Request $request)
    {
        try {
            return response()->json([
                'success' => $request->user()
            ], 200);
        } catch (\Throwable $th) {
            logger()->error($th->getMessage());

            return response()->json([
                'error' => 'Error al obtener el usuario, intente de nuevo mas tarde'
            ], 400);
        }
    }

}
