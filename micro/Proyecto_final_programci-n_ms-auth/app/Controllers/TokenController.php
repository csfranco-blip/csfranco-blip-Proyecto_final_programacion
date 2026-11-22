<?php

namespace App\Controllers;

use App\Models\TokenAcceso;
use Illuminate\Database\Eloquent\Collection;

class TokenController
{
    function get_tokens(): Collection
    {
        return TokenAcceso::with('user')->get();
    }

    function find_token(int $id): TokenAcceso
    {
        return TokenAcceso::with('user')->findOrFail($id);
    }

    function get_by_user(int $userId): Collection
    {
        return TokenAcceso::where('user_id', $userId)
            ->where('revocado', false)
            ->where('expira_en', '>', date('Y-m-d H:i:s'))
            ->get();
    }

    function revoke(int $id): TokenAcceso
    {
        $token = TokenAcceso::findOrFail($id);
        $token->revocado = true;
        $token->save();
        return $token;
    }

    function delete(int $id): TokenAcceso
    {
        $token = TokenAcceso::findOrFail($id);
        $token->delete();
        return $token;
    }
}
