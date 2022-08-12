<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\ResponseController as RC;
use Zttp\Zttp;
use GuzzleHttp\Exception\BadResponseException;
use App\Models\User;

class AuthController extends RC
{
    public function login(Request $request) {
        $email = $request->email;
        $password = $request->password;
        $url = config('service.passport.login_endpoint');

        if (empty($email) || empty($password)) {
            return $this->sendError('error', 'Debes insgresar un usuario y contraseña');
        }
        try {
            //aqui agregamos los demas datos requeridos para formar menu y rol
            $credentials = [
                "client_secret" => config('service.passport.client_secret'),
                "grant_type" => "password",
                "client_id" => config('service.passport.client_id'),
                "username" => $email,
                "password" => $password
            ];

            $response = Zttp::post($url, $credentials);
            // dd($response);

            if (isset($response->json()["error"])) {
                return $this->sendError(
                                '',
                                $response->json()["message"]
                );
            }

            /* obtenemos los datos del perfil */
            $profile = User::where("email", "=", $email)->first();

            $responsedata = [
                "access_token" => $response->json()["access_token"],
                "refresh_token" => $response->json()["refresh_token"],
                "user" => [
                    "sub" => $profile->id,
                    "email" => $profile->email,
                    "name" => $profile->name                    
                ]
            ];

            return $this->sendResponse($responsedata, 'Login exitoso');
        } catch (BadResponseException $ex) {
            return $this->sendError('error', $ex->getMessage());
        }
    }

    public function logout(Request $request) {
        try {
            auth()->user()->tokens()->each(function ($token) {
                $token->delete();
            });
            return $this->sendResponse("exito", "Session cerrada correctamente");
        } catch (\Exception $ex) {
            return $this->sendError('error', $ex->getMessage());
        }
    }
}
