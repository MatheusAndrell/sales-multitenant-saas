<?php

namespace App\Actions\Users;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class ListUsersAction
{
    public function execute(): Collection
    {
        $tenantId = auth()->user()->tenant_id;

        return User::where('tenant_id', $tenantId)
            ->with('roles')
            ->get();
    }
}
