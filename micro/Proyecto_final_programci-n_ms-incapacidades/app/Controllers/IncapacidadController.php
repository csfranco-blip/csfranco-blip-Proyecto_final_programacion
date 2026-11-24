<?php

namespace App\Controllers;

use App\Models\Incapacidad;
use Illuminate\Database\Eloquent\Collection;

class IncapacidadesController
{
    function get_incapacidades(): Collection
    {
        return Incapacidad::all();
    }

    function find_incapacidad(int $id): Incapacidad
    {
        return Incapacidad::findOrFail($id);
    }

    function create(array $data): Incapacidad
    {
        $incapacidad = new Incapacidad();
        $incapacidad->empleado_id = $data["empleado_id"];
        $incapacidad->fecha_inicio = $data["fecha_inicio"];
        $incapacidad->fecha_fin = $data["fecha_fin"];
        $incapacidad->tipo = $data["tipo"];
        $incapacidad->diagnostico_general = $data["diagnostico_general"];
        $incapacidad->entidad_medica = $data["entidad_medica"];
        $incapacidad->observaciones = $data["observaciones"] ?? null;
        $incapacidad->dias_incapacidad = $data["dias_incapacidad"];
        $incapacidad->estado = $data["estado"] ?? 'Radicada';
        $incapacidad->save();
        return $incapacidad;
    }

    function update(int $id, array $data): Incapacidad
    {
        $incapacidad = Incapacidad::findOrFail($id);
        $incapacidad->empleado_id = $data["empleado_id"];
        $incapacidad->fecha_inicio = $data["fecha_inicio"];
        $incapacidad->fecha_fin = $data["fecha_fin"];
        $incapacidad->tipo = $data["tipo"];
        $incapacidad->diagnostico_general = $data["diagnostico_general"];
        $incapacidad->entidad_medica = $data["entidad_medica"];
        $incapacidad->observaciones = $data["observaciones"] ?? null;
        $incapacidad->dias_incapacidad = $data["dias_incapacidad"];
        $incapacidad->estado = $data["estado"];
        $incapacidad->save();
        return $incapacidad;
    }

    function delete(int $id): Incapacidad
    {
        $incapacidad = Incapacidad::findOrFail($id);
        $incapacidad->delete();
        return $incapacidad;
    }
}
