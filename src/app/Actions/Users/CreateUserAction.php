<?php

namespace App\Actions\Users;

use App\DTOs\User\UserData;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Exception;

class CreateUserAction
{
    public function execute(UserData $data): User
    {
        $existingUser = User::where('email', $data->email)->first();

        if ($existingUser) {
            throw new Exception('Este email já está cadastrado.');
        }

        $user = User::create([
            'name' => $data->name,
            'email' => $data->email,
            'password' => Hash::make($data->password),
            'tenant_id' => $data->tenant_id,
        ]);

        if ($data->role) {
            $user->assignRole($data->role);
        }

        return $user;
    }
}
