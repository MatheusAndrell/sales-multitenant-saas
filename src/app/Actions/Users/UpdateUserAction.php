<?php

namespace App\Actions\Users;

use App\DTOs\User\UserData;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Exception;

class UpdateUserAction
{
    public function execute(User $user, UserData $data): User
    {
        if ($data->email && $data->email !== $user->email) {
            $existingUser = User::where('email', $data->email)
                ->where('id', '!=', $user->id)
                ->first();

            if ($existingUser) {
                throw new Exception('Este email já está cadastrado.');
            }
        }

        $updateData = [];

        if ($data->name) {
            $updateData['name'] = $data->name;
        }

        if ($data->email) {
            $updateData['email'] = $data->email;
        }

        if ($data->password) {
            $updateData['password'] = Hash::make($data->password);
        }

        $user->update($updateData);

        if ($data->role) {
            $user->syncRoles($data->role);
        }

        return $user;
    }
}
