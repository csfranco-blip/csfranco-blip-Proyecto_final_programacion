<?php

namespace App\Controllers;

use App\Models\Empleado;
use App\Models\Incapacidad;

class ReporteController
{
    function resumenGeneral(): array
    {
        return [
            'total_empleados' => Empleado::count(),
            'total_incapacidades' => Incapacidad::count(),
            'dias_totales_incapacidad' => (int) Incapacidad::sum('dias_incapacidad'),
            'por_estado' => [
                'Radicada' => Incapacidad::where('estado', 'Radicada')->count(),
                'En Revisión' => Incapacidad::where('estado', 'En Revisión')->count(),
                'Aprobada' => Incapacidad::where('estado', 'Aprobada')->count(),
                'Rechazada' => Incapacidad::where('estado', 'Rechazada')->count(),
                'Pagada' => Incapacidad::where('estado', 'Pagada')->count(),
            ],
            'por_tipo' => [
                'enfermedad_general' => Incapacidad::where('tipo', 'enfermedad_general')->count(),
                'licencia_medica' => Incapacidad::where('tipo', 'licencia_medica')->count(),
            ]
        ];
    }

    function reportePorFechas(string $fecha_inicio, string $fecha_fin): array
    {
        $incapacidades = Incapacidad::with('empleado')
            ->whereBetween('fecha_inicio', [$fecha_inicio, $fecha_fin])
            ->get();

        return [
            'fecha_inicio' => $fecha_inicio,
            'fecha_fin' => $fecha_fin,
            'cantidad' => $incapacidades->count(),
            'dias_acumulados' => (int) $incapacidades->sum('dias_incapacidad'),
            'detalle' => $incapacidades
        ];
    }
}
