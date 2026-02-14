<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class VerifyPasswordRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'password' => 'required|string',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $user = Auth::guard('usuarios')->user();
            
            if (!$user) {
                $validator->errors()->add('password', 'Usuario no autenticado.');
                return;
            }

            if (!Hash::check($this->password, $user->password)) {
                $validator->errors()->add('password', 'La contraseña ingresada es incorrecta.');
            }
        });
    }
}