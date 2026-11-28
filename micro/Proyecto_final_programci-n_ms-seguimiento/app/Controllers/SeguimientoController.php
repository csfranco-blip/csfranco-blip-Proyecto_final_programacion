<?php

namespace App\Controllers;

use App\Models\Seguimiento;
use Exception;
use Illuminate\Database\Eloquent\Collection;

class SeguimientoController
{
    function obtenerPorIncapacidad(int $incapacidad_id): Collection
    {
        return Seguimiento::where("incapacidad_id", $incapacidad_id)
            ->orderBy("fecha", "asc")
            ->get();
    }

    function obtenerPorEmpleado(int $empleado_id): Collection
    {
        $incapacidadesIds = $this->obtenerIdsIncapacidadesPorEmpleado($empleado_id);

        if (empty($incapacidadesIds)) {
            return new Collection();
        }

        return Seguimiento::whereIn("incapacidad_id", $incapacidadesIds)
            ->orderBy("fecha", "desc")
            ->get();
    }

    function registrarEvento(array $data): Seguimiento
    {
        $this->validarIncapacidadExiste((int) $data["incapacidad_id"]);

        $seguimiento = new Seguimiento();
        $seguimiento->incapacidad_id = $data["incapacidad_id"];
        $seguimiento->fecha = $data["fecha"] ?? date("Y-m-d H:i:s");
        $seguimiento->comentario = $data["comentario"];
        $seguimiento->estado = $data["estado"];
        $seguimiento->usuario_responsable = $data["usuario_responsable"];
        $seguimiento->save();

        return $seguimiento;
    }

    function listarActividadReciente(int $limite = 15): Collection
    {
        return Seguimiento::orderBy("fecha", "desc")
            ->limit($limite)
            ->get();
    }

    private function validarIncapacidadExiste(int $incapacidad_id): void
    {
        $baseUrl = $_ENV["MS_INCAPACIDADES_URL"] ?? "http://ms-incapacidades";
        $url = rtrim($baseUrl, "/") . "/api/incapacidades/" . $incapacidad_id;

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode !== 200) {
            throw new Exception("La incapacidad con ID {$incapacidad_id} no existe en ms-incapacidades", 404);
        }
    }

    private function obtenerIdsIncapacidadesPorEmpleado(int $empleado_id): array
    {
        $baseUrl = $_ENV["MS_INCAPACIDADES_URL"] ?? "http://ms-incapacidades";
        $url = rtrim($baseUrl, "/") . "/api/incapacidades/empleado/" . $empleado_id;

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode !== 200 || !$response) {
            return [];
        }

        $incapacidades = json_decode($response, true);

        if (!is_array($incapacidades)) {
            return [];
        }

        $lista = $incapacidades["data"] ?? $incapacidades;
        return array_column($lista, "id");
    }
}
