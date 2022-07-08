<?php

namespace Users;

use App\Http\Requests\Request;
use Illuminate\Validation\Rules\Password;

/**
 * Class UserRequest
 * @package Users
 */
class UserRequest extends Request
{
    /**
     * @return string[]
     */
    public function validateToLogin()
    {
        return [
            'email'    => 'required',
            'password' => 'required',
        ];
    }

    /**
     * @return string[]
     */
    public function validateToIndex()
    {
        return [
            'name'          => '',
            'role'          => '',
        ];
    }

    /**
     * @return string[]
     */
    public function validateToStore()
    {
        return [
            'email'         => 'required|string|email|unique:users',
            'phone'         => 'required|string',
            'name'          => 'required',
            'role'          => 'required',
            'password'      => 'nullable|string|min:6',
        ];
    }

    /**
     * @return string[]
     */
    public function validateToUpdate()
    {
        $pendingPassword = User::STATUS_PENDING_PASSWORD;
        $active          = User::STATUS_ACTIVE;
        $blocked         = User::STATUS_BLOCKED;

        return [
            'email'         => 'string|email',
            'name'          => '',
            'role'          => '',
            'password'      => 'nullable|string|min:6',
            'status'        => "in:$pendingPassword,$active,$blocked",
        ];
    }

    /**
     * @return string[]
     */
    public function validateToForgotPassword()
    {
        return [
            'email' => 'required|email',
        ];
    }

    /**
     * @return array[]
     */
    public function validateToResetPassword()
    {
        return [
            'password' => [
                'required',
                'string',
                Password::min(6)
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
                    ->uncompromised(),
                'confirmed'
            ],
        ];
    }
}
