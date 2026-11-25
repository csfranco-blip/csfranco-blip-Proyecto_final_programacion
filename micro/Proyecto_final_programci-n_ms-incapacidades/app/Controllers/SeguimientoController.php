<?php

namespace App\Controllers;

use App\Models\Seguimiento;
use Illuminate\Database\Eloquent\Collection;

class SeguimientoController
{
    function obtenerHistorial(int $incapacidad_id): Collection
    {
        return Seguimiento::where("incapacidad_id", $incapacidad_id)
            ->orderBy("created_at", "asc")
            ->get();
    }

    function create(array $data): Seguimiento
    {
        $seguimiento = new Seguimiento();
        $seguimiento->incapacidad_id = $data["incapacidad_id"];
        $seguimiento->estado_anterior = $data["estado_anterior"] ?? null;
        $seguimiento->estado_nuevo = $data["estado_nuevo"];
        $seguimiento->nota = $data["nota"] ?? null;
        $seguimiento->usuario_id = $data["usuario_id"] ?? null;
        $seguimiento->save();
        return $seguimiento;
    }

    function update(int $id, array $data): Seguimiento
    {
        $seguimiento = Seguimiento::findOrFail($id);
        $seguimiento->estado_nuevo = $data["estado_nuevo"];
        $seguimiento->nota = $data["nota"] ?? null;
        $seguimiento->save();
        return $seguimiento;
    }

    function delete(int $id): Seguimiento
    {
        $seguimiento = Seguimiento::findOrFail($id);
        $seguimiento->delete();
        return $seguimiento;
    }
}
