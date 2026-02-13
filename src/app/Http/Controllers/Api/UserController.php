<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Actions\Users\CreateUserAction;
use App\Actions\Users\UpdateUserAction;
use App\Actions\Users\DeleteUserAction;
use App\Actions\Users\ListUsersAction;
use App\DTOs\User\UserData;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Models\User;

class UserController extends Controller
{
    public function __construct(
        private CreateUserAction $createAction,
        private UpdateUserAction $updateAction,
        private DeleteUserAction $deleteAction,
        private ListUsersAction $listAction,
    ) {
    }

    public function index()
    {
        try {
            $users = $this->listAction->execute();

            return response()->json($users);
        } catch (\Exception $e) {
            \Log::error('Erro ao listar usuários', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id()
            ]);

            return response()->json([
                'message' => 'Erro interno ao listar usuários.'
            ], 500);
        }
    }

    public function store(StoreUserRequest $request)
    {
        try {
            $data = UserData::fromArray($request->validated());

            $user = $this->createAction->execute($data);

            return response()->json([
                'message' => 'Usuário criado com sucesso.',
                'data' => $user
            ], 201);

        } catch (\Exception $e) {
            \Log::error('Erro ao criar usuário', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id()
            ]);

            return response()->json([
                'message' => 'Erro interno ao criar usuário.'
            ], 500);
        }
    }

    public function show(User $user)
    {
        if ($user->tenant_id !== auth()->user()->tenant_id) {
            return response()->json([
                'message' => 'Usuário não encontrado ou inacessível para este tenant.'
            ], 403);
        }

        return response()->json($user);
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        try {
            if ($user->tenant_id !== auth()->user()->tenant_id) {
                return response()->json([
                    'message' => 'Usuário não encontrado ou inacessível para este tenant.'
                ], 403);
            }

            $data = UserData::fromUpdate($request->validated());

            $updated = $this->updateAction->execute($user, $data);

            return response()->json([
                'message' => 'Usuário atualizado com sucesso.',
                'data' => $updated
            ]);

        } catch (\Exception $e) {
            \Log::error('Erro ao atualizar usuário', [
                'error' => $e->getMessage(),
                'user_id' => $user->id
            ]);

            return response()->json([
                'message' => 'Erro interno ao atualizar usuário.'
            ], 500);
        }
    }

    public function destroy(User $user)
    {
        try {
            if ($user->tenant_id !== auth()->user()->tenant_id) {
                return response()->json([
                    'message' => 'Usuário não encontrado ou inacessível para este tenant.'
                ], 403);
            }

            $this->deleteAction->execute($user);

            return response()->json([
                'message' => 'Usuário removido com sucesso.'
            ]);

        } catch (\Exception $e) {
            \Log::error('Erro ao remover usuário', [
                'error' => $e->getMessage(),
                'user_id' => $user->id,
                'authenticated_user_id' => auth()->id()
            ]);

            return response()->json([
                'message' => 'Erro interno ao remover usuário.'
            ], 500);
        }
    }
}
