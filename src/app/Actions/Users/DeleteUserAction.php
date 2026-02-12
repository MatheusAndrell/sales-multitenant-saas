<?php

namespace App\Actions\Users;

use App\Models\User;
use Exception;

class DeleteUserAction
{
    public function execute(User $user): void
    {
        if ($user->id === auth()->id()) {
            throw new Exception('Você não pode deletar sua própria conta.');
        }

        $user->delete();
    }
}
