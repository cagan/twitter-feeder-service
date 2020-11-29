<?php

namespace App\Http\Requests;

use App\Models\User;

class RegisterVerifyRequest extends MainRequest
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $id = $this->route()->parameter('id');
        $activationToken = $this->route()->parameter('activation_token');

        $user = User::find($id);

        if (!$user) {
            return false;
        }

        $token = $user->where('activation_token', $activationToken)->exists();

        if (!$token) {
            return false;
        }

        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'activation_code' => 'required'
        ];
    }

}
