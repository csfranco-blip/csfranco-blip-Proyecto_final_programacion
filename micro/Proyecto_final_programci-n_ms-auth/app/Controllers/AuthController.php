<?php

namespace App\Controllers;

use App\Models\TokenAcceso;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Collection;

class TokensAccesoController
{
    function get_tokens(): Collection
    {
        return TokenAcceso::all();
    }

    function find_token(string $token): TokenAcceso
    {
        $tokenAcceso = TokenAcceso::where("token", $token)->firstOrFail();

        if (!$tokenAcceso->esValido()) {
            throw new Exception("El token es inválido o ha expirado", 1);
        }

        return $tokenAcceso;
    }

    function create(array $data): TokenAcceso
    {
        $tokenAcceso = new TokenAcceso();
        $tokenAcceso->token = $data["token"] ?? bin2hex(random_bytes(32));
        $tokenAcceso->usuario_id = $data["usuario_id"];
        $tokenAcceso->fecha_expiracion = $data["fecha_expiracion"] ?? Carbon::now()->addHours(24);
        $tokenAcceso->revocado = $data["revocado"] ?? false;
        $tokenAcceso->save();

        return $tokenAcceso;
    }

    function revocar(string $token): TokenAcceso
    {
        $tokenAcceso = TokenAcceso::where("token", $token)->firstOrFail();
        $tokenAcceso->revocar();

        return $tokenAcceso;
    }

    function delete(int $id): TokenAcceso
    {
        $tokenAcceso = TokenAcceso::findOrFail($id);
        $tokenAcceso->delete();

        return $tokenAcceso;
    }
}
