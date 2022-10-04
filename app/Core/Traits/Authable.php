<?php

namespace App\Core\Traits;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

trait Authable
{


    public function login(array $params)
    {
        extract($params);
        try {
            $user = $this->where('email', $email)->firstOrFail();
            if (Hash::check($password, $user->password)) {
                $token = $user->createToken('User Token');
                $model = $token->token;
                $model->update(['device_token' => $device_id ?? null, 'device_type' => $device_type ?? null]);
                return $token->accessToken;
            }
            throw new \Exception('invalid user or password');
        } catch (\Throwable $th) {
            // dd($th);
            throw $th;
        }
    }

    public function register(array $params)
    {
        try {
            return $this->fill($params)->save();
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function logout()
    {
        try {
            return $this->token()->revoke();
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
