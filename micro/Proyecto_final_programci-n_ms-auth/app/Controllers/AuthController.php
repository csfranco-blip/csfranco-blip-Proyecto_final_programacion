<?php

namespace App\Controllers;

use App\Models\TokenAcceso;
use App\Models\User;
use App\Models\Token;
use app\Models\UsuarioAuth;
use Exception;

class AuthController
{
    /**
     * Verifica correo y contraseña, genera un token, lo guarda en BD y devuelve los datos.
     */
    function login(string $correo, string $password): array
    {
        $user = UsuarioAuth::where("correo", $correo)->first();

        if (!$user || !password_verify($password, $user->password)) {
            throw new Exception("Credenciales inválidas", 401);
        }

        // Generar token aleatorio seguro
        $tokenString = bin2hex(random_bytes(32));
        $expiraEn = date('Y-m-d H:i:s', strtotime('+8 hours'));

        // Guardar en BD con Eloquent
        $token = new TokenAcceso();
        $token->user_id = $user->id;
        $token->token = $tokenString;
        $token->expira_en = $expiraEn;
        $token->revocado = false;
        $token->save();

        return [
            "token" => $token->token,
            "expira_en" => $token->expira_en,
            "user" => [
                "id" => $user->id,
                "nombre" => $user->nombre,
                "correo" => $user->correo
            ]
        ];
    }

    /**
     * Recibe el token actual y lo marca como revocado (o lo elimina).
     */
    function logout(string $tokenString): bool
    {
        $token = TokenAcceso::where("token", $tokenString)->first();

        if (!$token) {
            throw new Exception("Token no encontrado", 404);
        }

        // Opción A: Marcar como revocado
        $token->revocado = true;
        $token->save();

        // Opción B: Si prefieres eliminarlo físicamente de la BD, descomenta la siguiente línea:
        // $token->delete();

        return true;
    }

    /**
     * Valida si un token es auténtico, no está revocado y no ha expirado.
     * Endpoint interno: GET /auth/validate
     */
    function validarToken(string $tokenString): array
    {
        $token = TokenAcceso::with('user')
            ->where("token", $tokenString)
            ->where("revocado", false)
            ->where("expira_en", ">", date('Y-m-d H:i:s'))
            ->first();

        if (!$token || !$token->user) {
            throw new Exception("Token inválido o expirado", 401);
        }

        return [
            "valido" => true,
            "user_id" => $token->user->id,
            "nombre" => $token->user->nombre,
            "correo" => $token->user->correo
        ];
    }

    function register(array $data): UsuarioAuth
    {
        if (empty($data['correo']) || empty($data['password'])) {
            throw new Exception("Correo y contraseña requeridos", 400);
        }

        if (UsuarioAuth::where("correo", $data['correo'])->exists()) {
            throw new Exception("El correo ya está registrado", 409);
        }

        $user = new UsuarioAuth();
        $user->nombre = $data['nombre'] ?? '';
        $user->correo = $data['correo'];
        $user->password = password_hash($data['password'], PASSWORD_DEFAULT);
        $user->save();

        return $user;
    }
}
