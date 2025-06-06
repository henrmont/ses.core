<?php

namespace App\Http\Controllers;

use App\Mail\VerificationCodeMail;
use App\Models\User;
use App\Models\VerificationCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use PHPOpenSourceSaver\JWTAuth\Exceptions\TokenExpiredException;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function getUser($id)
    {
        $user = User::with(['verificationCode'])->find($id);
        return response()->json($user, 200);
    }

    public function sendVerificationCode(Request $request)
    {
        $user = User::with(['verificationCode'])->where('email', $request->email)->orWhere('id', $request->id)->first();
        if ($user) {
            if ($user->verificationCode) {
                $user->verificationCode->delete();
            }
            $code = VerificationCode::create([
                'user_id' => $user->id,
                'expire_at' => now()->addMinutes(10),
                'code' => rand(1000, 9999),
            ]);
            Mail::to($user->email, $user->name)->send(new VerificationCodeMail([
                'code' => $code->code,
                'subject' => 'Código de verificação',
                'expire' => $code->expire_at
            ]));
            return response()->json(['message' => 'Código de verificação enviado!', 'id' => $user->id], 200);
        } else {
            return response()->json(['message' => 'E-mail não encontrado!'], 400);
        }
    }

    public function checkVerificationCode(Request $request)
    {
        $user = User::with(['verificationCode'])->find($request->id);
        $verificationCode = $request->one.$request->two.$request->three.$request->four;
        if ($user->verificationCode->code == $verificationCode) {
            if ($user->verificationCode->expire_at > now()) {
                $user->update([
                    'email_verified_at' => now()
                ]);
            } else {
                return response()->json(['message' => 'Código de verificação expirou!'], 400);
            }
        } else {
            return response()->json(['message' => 'Código de verificação inválido!'], 400);
        }
    }

    public function resetPassword(Request $request)
    {
        $user = User::with(['verificationCode'])->find($request->id);
        $user->update([
            'password' => Hash::make($request->npassword),
        ]);
        $user->verificationCode->delete();
        return response()->json(['message' => 'Senha reedefinida com sucesso!'], 200);
    }

    public function login()
    {
        $credentials = request(['email', 'password']);

        if (! $token = auth('api')->attempt($credentials)) {
            return response()->json(['message' => 'Credenciais inválidas.'], 401);
        }

        return $this->respondWithToken($token);
    }

    public function me()
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            return response()->json($user->load(['roles.permissions','permissions','modules','module']));
        } catch (TokenExpiredException $e) {
            return response()->json(['error' => 'token_expired'], 401);
        }
    }

    public function logout()
    {
        auth('api')->logout();
        return response()->json(['message' => 'Successfully logged out']);
    }

    public function refresh()
    {
        $token = JWTAuth::refresh(JWTAuth::getToken());
        return $this->respondWithToken($token);
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => JWTAuth::factory()->getTTL() * 60
        ]);
    }
}
