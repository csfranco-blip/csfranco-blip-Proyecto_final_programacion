<?php

namespace App\Controllers;

use App\Models\Seguimiento;
use DateTime;
use Exception;

class ControlTiempoController
{
    private const LIMITE_EPS_DIAS = 180;
    private const LIMITE_MAXIMO_LEGAL_DIAS = 540;

    function calcularVencimientos(): array
    {
        $incapacidadesActivas = $this->obtenerIncapacidadesActivas();
        $hoy = new DateTime("today");
        $resultados = [];

        foreach ($incapacidadesActivas as $incapacidad) {
            $incapacidadId = (int) $incapacidad["id"];

            $ultimoSeguimiento = Seguimiento::where("incapacidad_id", $incapacidadId)
                ->orderBy("fecha", "desc")
                ->orderBy("id", "desc")
                ->first();

            if ($ultimoSeguimiento && in_array($ultimoSeguimiento->estado, ["finalizada", "rechazada"])) {
                continue;
            }

            $fechaInicio = new DateTime($incapacidad["fecha_inicio"]);
            $fechaFin = new DateTime($incapacidad["fecha_fin"]);

            $diasTotales = (int) $fechaInicio->diff($fechaFin)->days + 1;

            $fechaReintegro = (clone $fechaFin)->modify("+1 day");
            $diferencia = $hoy->diff($fechaReintegro);
            $diasParaReintegro = $diferencia->invert ? -$diferencia->days : $diferencia->days;

            $alertaLegal = $this->evaluarLimiteLegal($diasTotales);

            $resultados[] = [
                "incapacidad_id"       => $incapacidadId,
                "empleado_id"          => $incapacidad["empleado_id"] ?? null,
                "estado_seguimiento"   => $ultimoSeguimiento ? $ultimoSeguimiento->estado : "registrada",
                "ultimo_comentario"    => $ultimoSeguimiento ? $ultimoSeguimiento->comentario : null,
                "fecha_inicio"         => $fechaInicio->format("Y-m-d"),
                "fecha_fin"            => $fechaFin->format("Y-m-d"),
                "fecha_reintegro"      => $fechaReintegro->format("Y-m-d"),
                "dias_totales"         => $diasTotales,
                "dias_para_reintegro"  => $diasParaReintegro,
                "vencida_sin_cierre"   => $diasParaReintegro < 0,
                "supera_limite_legal"  => $alertaLegal["supera_limite"],
                "nivel_alerta"         => $alertaLegal["nivel"],
                "detalle_legal"        => $alertaLegal["mensaje"],
            ];
        }

        return $resultados;
    }

    private function evaluarLimiteLegal(int $dias): array
    {
        if ($dias > self::LIMITE_MAXIMO_LEGAL_DIAS) {
            return [
                "supera_limite" => true,
                "nivel"         => "CRITICO",
                "mensaje"       => "Supera el límite máximo de 540 días de cobertura legal.",
            ];
        }

        if ($dias > self::LIMITE_EPS_DIAS) {
            return [
                "supera_limite" => true,
                "nivel"         => "ALTO",
                "mensaje"       => "Supera los 180 días de cobertura EPS (requiere concepto de rehabilitación / AFP).",
            ];
        }

        return [
            "supera_limite" => false,
            "nivel"         => "NORMAL",
            "mensaje"       => "Dentro de los límites legales de cobertura.",
        ];
    }

    private function obtenerIncapacidadesActivas(): array
    {
        $baseUrl = $_ENV["MS_INCAPACIDADES_URL"] ?? "http://ms-incapacidades";
        $url = rtrim($baseUrl, "/") . "/api/incapacidades?estado=aprobada";

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode !== 200 || !$response) {
            throw new Exception("No se pudieron consultar las incapacidades activas en ms-incapacidades", 502);
        }

        $data = json_decode($response, true);

        if (!is_array($data)) {
            return [];
        }

        return $data["data"] ?? $data;
    }
}
